# Social Commerce CRM Module - Technical Design Document

## Version: 1.0
## Date: 2025-12-12
## System: GCMS (Guysiri Clinic Management System) - Laravel 10.10

---

## Table of Contents

1. [Overview](#1-overview)
2. [Architecture Diagram](#2-architecture-diagram)
3. [Existing System Integration](#3-existing-system-integration)
4. [New Database Schema](#4-new-database-schema)
5. [Migration Files](#5-migration-files)
6. [Model Definitions](#6-model-definitions)
7. [Webhook Processing Workflow](#7-webhook-processing-workflow)
8. [Booking Integration Workflow](#8-booking-integration-workflow)
9. [Admin Chat Interface Logic](#9-admin-chat-interface-logic)
10. [ROI & Analytics Queries](#10-roi--analytics-queries)

---

## 1. Overview

### Purpose
The Social Commerce CRM Module enables GCMS to:
- Receive and respond to messages from LINE and Facebook Messenger
- Track leads from social channels with daily status progression
- Link social users to existing patient records
- Enable booking appointments directly from chat
- Analyze ROI per ad source and sales agent performance

### Key Principles
- **UUID Primary Keys**: Consistent with existing GCMS architecture
- **BranchScope Compliance**: All data filtered by branch_id
- **Non-Destructive Integration**: Uses existing `users` and `patients` tables without modification

---

## 2. Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           EXTERNAL CHANNELS                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│    ┌──────────────┐                    ┌──────────────┐                     │
│    │  LINE OA     │                    │  Facebook    │                     │
│    │  Messaging   │                    │  Messenger   │                     │
│    └──────┬───────┘                    └──────┬───────┘                     │
│           │                                   │                             │
│           │ Webhook                           │ Webhook                     │
│           ▼                                   ▼                             │
│    ┌─────────────────────────────────────────────────────────┐              │
│    │              GCMS Webhook Controller                     │              │
│    │         /api/webhook/line  |  /api/webhook/facebook     │              │
│    └─────────────────────────┬───────────────────────────────┘              │
│                              │                                              │
└──────────────────────────────┼──────────────────────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           SOCIAL CRM MODULE                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│    ┌───────────────────┐      ┌───────────────────┐                         │
│    │ SocialIdentityService │◄──►│ social_identities │                         │
│    │ - findOrCreate()      │      │ (Bridge Table)    │                         │
│    │ - linkToPatient()     │      └─────────┬─────────┘                         │
│    └───────────┬───────────┘                │                               │
│                │                            │ 1:N                           │
│                ▼                            ▼                               │
│    ┌───────────────────┐      ┌───────────────────┐                         │
│    │ ConversationService │◄──►│ chat_conversations │                         │
│    │ - getOrCreate()      │      │ (Session Table)    │                         │
│    │ - assignAgent()      │      └─────────┬─────────┘                         │
│    │ - closeConversation()│                │                               │
│    └───────────┬───────────┘                │ 1:N                           │
│                │                            ▼                               │
│                │              ┌───────────────────┐                         │
│                │              │  chat_messages    │                         │
│                │              │ (Content Table)   │                         │
│                │              └───────────────────┘                         │
│                │                                                            │
│                ▼              ┌───────────────────┐                         │
│    ┌───────────────────┐      │ daily_lead_tracks │                         │
│    │ LeadTrackingService │◄──►│ (Analytics Table) │                         │
│    │ - trackDaily()       │      └───────────────────┘                         │
│    │ - updateStatus()     │                                                 │
│    │ - getROIReport()     │                                                 │
│    └───────────────────────┘                                                │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
                               │
                               │ Integration
                               ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                        EXISTING GCMS SYSTEM                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│    ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                 │
│    │    users     │    │   patients   │    │ appointments │                 │
│    │ (Admin/Agent)│    │  (Customers) │    │  (Bookings)  │                 │
│    └──────────────┘    └──────────────┘    └──────────────┘                 │
│           │                   ▲                   ▲                         │
│           │                   │                   │                         │
│           └───────────────────┴───────────────────┘                         │
│                    Linked via Foreign Keys                                  │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. Existing System Integration

### 3.1 Users Table (Admin/Agent)
```php
// Existing: app/Models/User.php
// Used for: current_agent_id, sale_closed_by
// No modifications required

// Relevant fields:
- id (UUID)
- name
- role_id (FK to roles)
- branch_id (FK to branches)
- is_active
```

### 3.2 Patients Table (Customers)
```php
// Existing: app/Models/Patient.php
// Used for: patient_id in social_identities
// No modifications required

// Relevant fields for matching:
- id (UUID)
- phone          // Primary match field
- name
- first_name
- last_name
- email          // Secondary match field
- branch_id
```

### 3.3 BranchScope Compliance
```php
// Existing: app/Scopes/BranchScope.php
// All new models MUST include:

use App\Models\Scopes\BranchScope;

protected static function booted(): void
{
    static::addGlobalScope(new BranchScope);
}
```

---

## 4. New Database Schema

### 4.1 Entity Relationship Diagram

```
┌─────────────────────┐
│      patients       │ (Existing)
│─────────────────────│
│ id (PK, UUID)       │
│ phone               │
│ name                │
│ branch_id           │
└──────────┬──────────┘
           │ 1
           │
           │ 0..N (one patient can have multiple social accounts)
           ▼
┌─────────────────────┐
│  social_identities  │ (New - Bridge Table)
│─────────────────────│
│ id (PK, UUID)       │
│ patient_id (FK)     │◄─── Nullable until linked
│ provider            │
│ provider_user_id    │◄─── LINE UserID / FB PSID
│ profile_name        │
│ avatar_url          │
│ meta_data (JSON)    │
│ created_at          │
│ updated_at          │
└──────────┬──────────┘
           │ 1
           │
           │ 0..N (one identity can have multiple conversations)
           ▼
┌─────────────────────┐       ┌─────────────────────┐
│  chat_conversations │       │        users        │ (Existing)
│─────────────────────│       │─────────────────────│
│ id (PK, UUID)       │       │ id (PK, UUID)       │
│ social_identity_id  │       │ name                │
│ branch_id (FK)      │       │ role_id             │
│ current_agent_id    │──────►│ branch_id           │
│ status              │       └─────────────────────┘
│ last_interaction_at │
│ created_at          │
│ updated_at          │
│ deleted_at          │
└──────────┬──────────┘
           │ 1
           │
           ├──────────────────────────────┐
           │ 1..N                         │ 1..N
           ▼                              ▼
┌─────────────────────┐       ┌─────────────────────┐
│   chat_messages     │       │  daily_lead_tracks  │
│─────────────────────│       │─────────────────────│
│ id (PK, UUID)       │       │ id (PK, UUID)       │
│ conversation_id(FK) │       │ conversation_id(FK) │
│ sender_type         │       │ tracking_date       │◄─── Date only, not datetime
│ sender_id           │       │ status              │
│ message_type        │       │ sale_closed_by (FK) │──────► users.id
│ content (TEXT)      │       │ ad_source_id        │
│ media_url           │       │ utm_data (JSON)     │
│ is_read             │       │ notes               │
│ read_at             │       │ created_at          │
│ created_at          │       │ updated_at          │
└─────────────────────┘       └─────────────────────┘
```

### 4.2 Table Specifications

#### Table: `social_identities`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | UUID | PK | Primary key |
| patient_id | UUID | FK nullable | Links to patients.id |
| provider | ENUM('line','facebook') | NOT NULL | Social platform |
| provider_user_id | VARCHAR(255) | NOT NULL | LINE UserID / FB PSID |
| profile_name | VARCHAR(255) | NULL | Display name from social |
| avatar_url | VARCHAR(500) | NULL | Profile picture URL |
| meta_data | JSON | NULL | Extra data (language, etc.) |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Indexes:**
- UNIQUE: `provider` + `provider_user_id`
- INDEX: `patient_id`

---

#### Table: `chat_conversations`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | UUID | PK | Primary key |
| social_identity_id | UUID | FK NOT NULL | Links to social_identities.id |
| branch_id | UUID | FK NOT NULL | For BranchScope |
| current_agent_id | UUID | FK nullable | Admin handling this chat |
| status | ENUM('open','pending','closed') | DEFAULT 'open' | Conversation state |
| last_interaction_at | TIMESTAMP | NULL | Last message timestamp |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |
| deleted_at | TIMESTAMP | NULL | Soft delete |

**Indexes:**
- INDEX: `social_identity_id`
- INDEX: `branch_id`
- INDEX: `current_agent_id`
- INDEX: `status`
- INDEX: `last_interaction_at`

---

#### Table: `daily_lead_tracks`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | UUID | PK | Primary key |
| conversation_id | UUID | FK NOT NULL | Links to chat_conversations.id |
| tracking_date | DATE | NOT NULL | The date being tracked |
| status | ENUM | NOT NULL | See status enum below |
| sale_closed_by | UUID | FK nullable | Agent who closed the sale |
| ad_source_id | VARCHAR(100) | NULL | e.g., "fb_ad_001", "line_oa" |
| utm_data | JSON | NULL | UTM parameters |
| notes | TEXT | NULL | Daily notes |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Status ENUM Values:**
```
'new'        - First contact today
'contacted'  - Agent has responded
'interested' - Customer shows interest
'booked'     - Appointment created
'completed'  - Treatment completed
'no_show'    - Did not show up
'cancelled'  - Cancelled appointment
'lost'       - Lead lost/unresponsive
```

**Indexes:**
- UNIQUE: `conversation_id` + `tracking_date`
- INDEX: `tracking_date`
- INDEX: `status`
- INDEX: `ad_source_id`
- INDEX: `sale_closed_by`

---

#### Table: `chat_messages`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | UUID | PK | Primary key |
| conversation_id | UUID | FK NOT NULL | Links to chat_conversations.id |
| sender_type | ENUM('user','customer','bot','system') | NOT NULL | Who sent it |
| sender_id | UUID | NULL | user.id if sender_type='user' |
| message_type | ENUM | NOT NULL | See type enum below |
| content | TEXT | NULL | Text content |
| media_url | VARCHAR(500) | NULL | Image/file URL |
| meta_data | JSON | NULL | Extra data (location coords, etc.) |
| is_read | BOOLEAN | DEFAULT false | Read by agent |
| read_at | TIMESTAMP | NULL | When read |
| created_at | TIMESTAMP | | |

**Message Type ENUM Values:**
```
'text'      - Plain text message
'image'     - Image attachment
'video'     - Video attachment
'audio'     - Audio/voice message
'file'      - Document/file
'sticker'   - LINE sticker
'location'  - Location share
'slip'      - Payment slip image
'template'  - Rich message/carousel
'system'    - System notification
```

**Indexes:**
- INDEX: `conversation_id`
- INDEX: `created_at`
- INDEX: `is_read`
- INDEX: `sender_type`

---

## 5. Migration Files

### 5.1 Migration: create_social_identities_table

```php
<?php
// database/migrations/2025_12_12_000001_create_social_identities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_identities', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Link to existing patients table (nullable for unlinked leads)
            $table->uuid('patient_id')->nullable();
            $table->foreign('patient_id')
                  ->references('id')
                  ->on('patients')
                  ->nullOnDelete();

            // Social provider info
            $table->enum('provider', ['line', 'facebook']);
            $table->string('provider_user_id', 255);
            $table->string('profile_name', 255)->nullable();
            $table->string('avatar_url', 500)->nullable();
            $table->json('meta_data')->nullable();

            $table->timestamps();

            // Ensure unique social identity per provider
            $table->unique(['provider', 'provider_user_id'], 'social_provider_unique');
            $table->index('patient_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_identities');
    }
};
```

### 5.2 Migration: create_chat_conversations_table

```php
<?php
// database/migrations/2025_12_12_000002_create_chat_conversations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Link to social identity
            $table->uuid('social_identity_id');
            $table->foreign('social_identity_id')
                  ->references('id')
                  ->on('social_identities')
                  ->cascadeOnDelete();

            // BranchScope compliance
            $table->uuid('branch_id');
            $table->foreign('branch_id')
                  ->references('id')
                  ->on('branches')
                  ->cascadeOnDelete();

            // Agent assignment (from existing users table)
            $table->uuid('current_agent_id')->nullable();
            $table->foreign('current_agent_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();

            // Conversation state
            $table->enum('status', ['open', 'pending', 'closed'])->default('open');
            $table->timestamp('last_interaction_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('social_identity_id');
            $table->index('branch_id');
            $table->index('current_agent_id');
            $table->index('status');
            $table->index('last_interaction_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_conversations');
    }
};
```

### 5.3 Migration: create_daily_lead_tracks_table

```php
<?php
// database/migrations/2025_12_12_000003_create_daily_lead_tracks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_lead_tracks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Link to conversation
            $table->uuid('conversation_id');
            $table->foreign('conversation_id')
                  ->references('id')
                  ->on('chat_conversations')
                  ->cascadeOnDelete();

            // Daily tracking
            $table->date('tracking_date');

            // Lead status progression
            $table->enum('status', [
                'new',        // First contact
                'contacted',  // Agent responded
                'interested', // Shows interest
                'booked',     // Appointment made
                'completed',  // Treatment done
                'no_show',    // Did not come
                'cancelled',  // Cancelled
                'lost'        // Unresponsive/lost
            ])->default('new');

            // Sales attribution (from existing users table)
            $table->uuid('sale_closed_by')->nullable();
            $table->foreign('sale_closed_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();

            // Marketing attribution
            $table->string('ad_source_id', 100)->nullable();
            $table->json('utm_data')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();

            // One status per conversation per day
            $table->unique(['conversation_id', 'tracking_date'], 'daily_track_unique');

            // Indexes for reporting
            $table->index('tracking_date');
            $table->index('status');
            $table->index('ad_source_id');
            $table->index('sale_closed_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_lead_tracks');
    }
};
```

### 5.4 Migration: create_chat_messages_table

```php
<?php
// database/migrations/2025_12_12_000004_create_chat_messages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Link to conversation
            $table->uuid('conversation_id');
            $table->foreign('conversation_id')
                  ->references('id')
                  ->on('chat_conversations')
                  ->cascadeOnDelete();

            // Sender info
            $table->enum('sender_type', ['user', 'customer', 'bot', 'system']);
            $table->uuid('sender_id')->nullable(); // users.id if sender_type='user'

            // Message content
            $table->enum('message_type', [
                'text',
                'image',
                'video',
                'audio',
                'file',
                'sticker',
                'location',
                'slip',
                'template',
                'system'
            ])->default('text');

            $table->text('content')->nullable();
            $table->string('media_url', 500)->nullable();
            $table->json('meta_data')->nullable();

            // Read status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamp('created_at')->useCurrent();

            // Indexes
            $table->index('conversation_id');
            $table->index('created_at');
            $table->index('is_read');
            $table->index('sender_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
```

---

## 6. Model Definitions

### 6.1 SocialIdentity Model

```php
<?php
// app/Models/SocialIdentity.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialIdentity extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'patient_id',
        'provider',
        'provider_user_id',
        'profile_name',
        'avatar_url',
        'meta_data',
    ];

    protected $casts = [
        'meta_data' => 'array',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function conversations()
    {
        return $this->hasMany(ChatConversation::class);
    }

    public function latestConversation()
    {
        return $this->hasOne(ChatConversation::class)->latestOfMany();
    }

    // Scopes
    public function scopeByProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeUnlinked($query)
    {
        return $query->whereNull('patient_id');
    }

    // Helper: Check if linked to patient
    public function isLinkedToPatient(): bool
    {
        return !is_null($this->patient_id);
    }
}
```

### 6.2 ChatConversation Model

```php
<?php
// app/Models/ChatConversation.php

namespace App\Models;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatConversation extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'social_identity_id',
        'branch_id',
        'current_agent_id',
        'status',
        'last_interaction_at',
    ];

    protected $casts = [
        'last_interaction_at' => 'datetime',
    ];

    // BranchScope
    protected static function booted(): void
    {
        static::addGlobalScope(new BranchScope);

        static::creating(function (ChatConversation $conversation) {
            if (!$conversation->branch_id) {
                $conversation->branch_id = session('selected_branch_id');
            }
        });
    }

    // Relationships
    public function socialIdentity()
    {
        return $this->belongsTo(SocialIdentity::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function currentAgent()
    {
        return $this->belongsTo(User::class, 'current_agent_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    public function dailyTracks()
    {
        return $this->hasMany(DailyLeadTrack::class, 'conversation_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'conversation_id')->latestOfMany();
    }

    public function todayTrack()
    {
        return $this->hasOne(DailyLeadTrack::class, 'conversation_id')
                    ->where('tracking_date', today());
    }

    // Accessors
    public function getPatientAttribute()
    {
        return $this->socialIdentity?->patient;
    }

    public function getUnreadCountAttribute(): int
    {
        return $this->messages()
                    ->where('sender_type', 'customer')
                    ->where('is_read', false)
                    ->count();
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('current_agent_id');
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('current_agent_id', $userId);
    }

    // Actions
    public function assignAgent(User $agent): void
    {
        $this->update([
            'current_agent_id' => $agent->id,
            'status' => 'open',
        ]);
    }

    public function close(): void
    {
        $this->update(['status' => 'closed']);
    }

    public function reopen(): void
    {
        $this->update(['status' => 'open']);
    }
}
```

### 6.3 DailyLeadTrack Model

```php
<?php
// app/Models/DailyLeadTrack.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLeadTrack extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'conversation_id',
        'tracking_date',
        'status',
        'sale_closed_by',
        'ad_source_id',
        'utm_data',
        'notes',
    ];

    protected $casts = [
        'tracking_date' => 'date',
        'utm_data' => 'array',
    ];

    // Status constants
    const STATUS_NEW = 'new';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_INTERESTED = 'interested';
    const STATUS_BOOKED = 'booked';
    const STATUS_COMPLETED = 'completed';
    const STATUS_NO_SHOW = 'no_show';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_LOST = 'lost';

    // Relationships
    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class);
    }

    public function saleClosedBy()
    {
        return $this->belongsTo(User::class, 'sale_closed_by');
    }

    // Scopes
    public function scopeForDate($query, $date)
    {
        return $query->where('tracking_date', $date);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByAdSource($query, string $adSourceId)
    {
        return $query->where('ad_source_id', $adSourceId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tracking_date', [$startDate, $endDate]);
    }

    // Helper: Get or create today's track
    public static function getOrCreateToday(string $conversationId, array $attributes = []): self
    {
        return static::firstOrCreate(
            [
                'conversation_id' => $conversationId,
                'tracking_date' => today(),
            ],
            array_merge(['status' => self::STATUS_NEW], $attributes)
        );
    }

    // Helper: Update status with validation
    public function updateStatus(string $newStatus, ?string $agentId = null): bool
    {
        $data = ['status' => $newStatus];

        if (in_array($newStatus, [self::STATUS_BOOKED, self::STATUS_COMPLETED]) && $agentId) {
            $data['sale_closed_by'] = $agentId;
        }

        return $this->update($data);
    }
}
```

### 6.4 ChatMessage Model

```php
<?php
// app/Models/ChatMessage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory, HasUuids;

    const UPDATED_AT = null; // Messages don't need updated_at

    protected $fillable = [
        'conversation_id',
        'sender_type',
        'sender_id',
        'message_type',
        'content',
        'media_url',
        'meta_data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Sender type constants
    const SENDER_USER = 'user';       // Admin/Agent
    const SENDER_CUSTOMER = 'customer';
    const SENDER_BOT = 'bot';
    const SENDER_SYSTEM = 'system';

    // Message type constants
    const TYPE_TEXT = 'text';
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_FILE = 'file';
    const TYPE_STICKER = 'sticker';
    const TYPE_LOCATION = 'location';
    const TYPE_SLIP = 'slip';
    const TYPE_TEMPLATE = 'template';
    const TYPE_SYSTEM = 'system';

    // Relationships
    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeFromCustomer($query)
    {
        return $query->where('sender_type', self::SENDER_CUSTOMER);
    }

    public function scopeFromAgent($query)
    {
        return $query->where('sender_type', self::SENDER_USER);
    }

    // Actions
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    // Helper: Check if message is from customer
    public function isFromCustomer(): bool
    {
        return $this->sender_type === self::SENDER_CUSTOMER;
    }

    // Helper: Check if message has media
    public function hasMedia(): bool
    {
        return !empty($this->media_url);
    }
}
```

---

## 7. Webhook Processing Workflow

### 7.1 Sequence Diagram

```
Customer                LINE/FB              Webhook              Services                 Database
   │                       │                    │                    │                        │
   │ Send Message          │                    │                    │                        │
   ├──────────────────────►│                    │                    │                        │
   │                       │ POST /webhook      │                    │                        │
   │                       ├───────────────────►│                    │                        │
   │                       │                    │                    │                        │
   │                       │                    │ 1. Parse Payload   │                        │
   │                       │                    ├───────────────────►│                        │
   │                       │                    │                    │                        │
   │                       │                    │ 2. Find/Create     │                        │
   │                       │                    │    SocialIdentity  │                        │
   │                       │                    │◄───────────────────┤                        │
   │                       │                    │                    │ Query social_identities│
   │                       │                    │                    ├───────────────────────►│
   │                       │                    │                    │◄───────────────────────┤
   │                       │                    │                    │                        │
   │                       │                    │ 3. Auto-detect     │                        │
   │                       │                    │    Patient         │                        │
   │                       │                    │◄───────────────────┤                        │
   │                       │                    │                    │ Query patients         │
   │                       │                    │                    │ by phone/name          │
   │                       │                    │                    ├───────────────────────►│
   │                       │                    │                    │◄───────────────────────┤
   │                       │                    │                    │                        │
   │                       │                    │ 4. Get/Create      │                        │
   │                       │                    │    Conversation    │                        │
   │                       │                    │◄───────────────────┤                        │
   │                       │                    │                    │ Insert/Update          │
   │                       │                    │                    │ chat_conversations     │
   │                       │                    │                    ├───────────────────────►│
   │                       │                    │                    │◄───────────────────────┤
   │                       │                    │                    │                        │
   │                       │                    │ 5. Store Message   │                        │
   │                       │                    │◄───────────────────┤                        │
   │                       │                    │                    │ Insert chat_messages   │
   │                       │                    │                    ├───────────────────────►│
   │                       │                    │                    │◄───────────────────────┤
   │                       │                    │                    │                        │
   │                       │                    │ 6. Track Lead      │                        │
   │                       │                    │◄───────────────────┤                        │
   │                       │                    │                    │ Upsert daily_lead_tracks│
   │                       │                    │                    ├───────────────────────►│
   │                       │                    │                    │◄───────────────────────┤
   │                       │                    │                    │                        │
   │                       │                    │ 7. Broadcast       │                        │
   │                       │                    │    to Admin UI     │                        │
   │                       │                    ├───────────────────►│ (WebSocket/Pusher)     │
   │                       │                    │                    │                        │
   │                       │    200 OK         │                    │                        │
   │                       │◄───────────────────┤                    │                        │
   │                       │                    │                    │                        │
```

### 7.2 Webhook Controller Implementation

```php
<?php
// app/Http/Controllers/Api/WebhookController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SocialCrm\WebhookProcessor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    public function __construct(
        private WebhookProcessor $processor
    ) {}

    /**
     * LINE Webhook Endpoint
     * POST /api/webhook/line
     */
    public function line(Request $request): Response
    {
        // Verify LINE signature
        $signature = $request->header('X-Line-Signature');
        if (!$this->verifyLineSignature($request->getContent(), $signature)) {
            return response('Invalid signature', 401);
        }

        // Process events
        $events = $request->input('events', []);
        foreach ($events as $event) {
            $this->processor->processLineEvent($event);
        }

        return response('OK', 200);
    }

    /**
     * Facebook Webhook Endpoint
     * GET  /api/webhook/facebook - Verification
     * POST /api/webhook/facebook - Messages
     */
    public function facebook(Request $request): Response
    {
        // Webhook verification (GET)
        if ($request->isMethod('get')) {
            return $this->verifyFacebookWebhook($request);
        }

        // Process messages (POST)
        $entries = $request->input('entry', []);
        foreach ($entries as $entry) {
            foreach ($entry['messaging'] ?? [] as $event) {
                $this->processor->processFacebookEvent($event);
            }
        }

        return response('EVENT_RECEIVED', 200);
    }

    private function verifyLineSignature(string $body, ?string $signature): bool
    {
        $channelSecret = config('services.line.channel_secret');
        $hash = base64_encode(hash_hmac('sha256', $body, $channelSecret, true));
        return hash_equals($hash, $signature ?? '');
    }

    private function verifyFacebookWebhook(Request $request): Response
    {
        $verifyToken = config('services.facebook.verify_token');

        if ($request->input('hub_verify_token') === $verifyToken) {
            return response($request->input('hub_challenge'), 200);
        }

        return response('Forbidden', 403);
    }
}
```

### 7.3 Patient Auto-Detection Logic

```php
<?php
// app/Services/SocialCrm/PatientDetector.php

namespace App\Services\SocialCrm;

use App\Models\Patient;
use App\Models\SocialIdentity;

class PatientDetector
{
    /**
     * Attempt to auto-detect and link patient to social identity
     *
     * Detection Priority:
     * 1. Phone number match (most reliable)
     * 2. Name + Phone partial match
     * 3. Email match (if available)
     *
     * @param SocialIdentity $identity
     * @param array $additionalData Data from conversation (e.g., shared phone)
     * @return Patient|null
     */
    public function detectAndLink(SocialIdentity $identity, array $additionalData = []): ?Patient
    {
        // Already linked
        if ($identity->patient_id) {
            return $identity->patient;
        }

        $patient = null;

        // 1. Try phone number match
        if (!empty($additionalData['phone'])) {
            $phone = $this->normalizePhone($additionalData['phone']);
            $patient = Patient::where('phone', $phone)->first();
        }

        // 2. Try name matching (fuzzy)
        if (!$patient && $identity->profile_name) {
            $patient = $this->findByName($identity->profile_name);
        }

        // 3. Try email match
        if (!$patient && !empty($additionalData['email'])) {
            $patient = Patient::where('email', $additionalData['email'])->first();
        }

        // Link if found
        if ($patient) {
            $identity->update(['patient_id' => $patient->id]);
        }

        return $patient;
    }

    /**
     * Manually link social identity to patient
     */
    public function manualLink(SocialIdentity $identity, Patient $patient): void
    {
        $identity->update(['patient_id' => $patient->id]);
    }

    /**
     * Find patient by name (fuzzy matching)
     */
    private function findByName(string $name): ?Patient
    {
        $normalizedName = $this->normalizeName($name);

        // Exact match on name
        $patient = Patient::where('name', 'LIKE', "%{$normalizedName}%")->first();

        if (!$patient) {
            // Try first_name + last_name
            $parts = explode(' ', $normalizedName);
            if (count($parts) >= 2) {
                $patient = Patient::where('first_name', 'LIKE', "%{$parts[0]}%")
                                  ->where('last_name', 'LIKE', "%{$parts[1]}%")
                                  ->first();
            }
        }

        return $patient;
    }

    private function normalizePhone(string $phone): string
    {
        // Remove all non-digits
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // Thai phone: convert +66 to 0
        if (str_starts_with($digits, '66') && strlen($digits) === 11) {
            $digits = '0' . substr($digits, 2);
        }

        return $digits;
    }

    private function normalizeName(string $name): string
    {
        // Remove emoji, special chars
        $name = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $name);
        $name = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $name);
        return trim($name);
    }
}
```

### 7.4 Complete Webhook Processor

```php
<?php
// app/Services/SocialCrm/WebhookProcessor.php

namespace App\Services\SocialCrm;

use App\Models\{SocialIdentity, ChatConversation, ChatMessage, DailyLeadTrack, Branch};
use App\Events\NewChatMessage;
use Illuminate\Support\Facades\DB;

class WebhookProcessor
{
    public function __construct(
        private PatientDetector $patientDetector
    ) {}

    /**
     * Process LINE webhook event
     */
    public function processLineEvent(array $event): void
    {
        if ($event['type'] !== 'message') {
            return; // Only handle messages for now
        }

        $userId = $event['source']['userId'];
        $profile = $this->getLineProfile($userId);

        DB::transaction(function () use ($event, $userId, $profile) {
            // 1. Find or create social identity
            $identity = SocialIdentity::firstOrCreate(
                [
                    'provider' => 'line',
                    'provider_user_id' => $userId,
                ],
                [
                    'profile_name' => $profile['displayName'] ?? null,
                    'avatar_url' => $profile['pictureUrl'] ?? null,
                    'meta_data' => $profile,
                ]
            );

            // 2. Try to auto-detect patient
            $this->patientDetector->detectAndLink($identity);

            // 3. Get or create conversation
            $conversation = $this->getOrCreateConversation($identity);

            // 4. Store message
            $message = $this->storeMessage($conversation, $event['message'], 'line');

            // 5. Track lead for today
            $this->trackLead($conversation, $event);

            // 6. Broadcast to admin UI
            event(new NewChatMessage($conversation, $message));
        });
    }

    /**
     * Process Facebook webhook event
     */
    public function processFacebookEvent(array $event): void
    {
        if (empty($event['message'])) {
            return; // Only handle messages
        }

        $senderId = $event['sender']['id'];
        $profile = $this->getFacebookProfile($senderId);

        DB::transaction(function () use ($event, $senderId, $profile) {
            // 1. Find or create social identity
            $identity = SocialIdentity::firstOrCreate(
                [
                    'provider' => 'facebook',
                    'provider_user_id' => $senderId,
                ],
                [
                    'profile_name' => $profile['name'] ?? null,
                    'avatar_url' => $profile['profile_pic'] ?? null,
                    'meta_data' => $profile,
                ]
            );

            // 2. Try to auto-detect patient
            $this->patientDetector->detectAndLink($identity);

            // 3. Get or create conversation
            $conversation = $this->getOrCreateConversation($identity);

            // 4. Store message
            $message = $this->storeMessage($conversation, $event['message'], 'facebook');

            // 5. Track lead for today
            $this->trackLead($conversation, $event);

            // 6. Broadcast to admin UI
            event(new NewChatMessage($conversation, $message));
        });
    }

    private function getOrCreateConversation(SocialIdentity $identity): ChatConversation
    {
        // Find existing open conversation
        $conversation = ChatConversation::withoutGlobalScope(BranchScope::class)
            ->where('social_identity_id', $identity->id)
            ->where('status', '!=', 'closed')
            ->first();

        if (!$conversation) {
            // Determine branch (from patient or default)
            $branchId = $identity->patient?->branch_id
                       ?? Branch::where('is_active', true)->first()?->id;

            $conversation = ChatConversation::create([
                'social_identity_id' => $identity->id,
                'branch_id' => $branchId,
                'status' => 'open',
                'last_interaction_at' => now(),
            ]);
        } else {
            $conversation->update(['last_interaction_at' => now()]);
        }

        return $conversation;
    }

    private function storeMessage(ChatConversation $conversation, array $messageData, string $provider): ChatMessage
    {
        $messageType = $this->mapMessageType($messageData, $provider);
        $content = $messageData['text'] ?? null;
        $mediaUrl = null;

        // Handle media messages
        if (in_array($messageType, ['image', 'video', 'audio', 'file'])) {
            $mediaUrl = $this->downloadAndStoreMedia($messageData, $provider);
        }

        return ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => ChatMessage::SENDER_CUSTOMER,
            'message_type' => $messageType,
            'content' => $content,
            'media_url' => $mediaUrl,
            'meta_data' => $messageData,
        ]);
    }

    private function trackLead(ChatConversation $conversation, array $event): void
    {
        // Extract UTM data if present (from rich menu click, etc.)
        $utmData = $this->extractUtmData($event);
        $adSourceId = $utmData['utm_source'] ?? $this->detectAdSource($event);

        DailyLeadTrack::getOrCreateToday($conversation->id, [
            'ad_source_id' => $adSourceId,
            'utm_data' => $utmData,
        ]);
    }

    private function mapMessageType(array $message, string $provider): string
    {
        if ($provider === 'line') {
            return match ($message['type'] ?? 'text') {
                'text' => 'text',
                'image' => 'image',
                'video' => 'video',
                'audio' => 'audio',
                'file' => 'file',
                'location' => 'location',
                'sticker' => 'sticker',
                default => 'text',
            };
        }

        // Facebook
        if (!empty($message['attachments'])) {
            $type = $message['attachments'][0]['type'] ?? 'text';
            return match ($type) {
                'image' => 'image',
                'video' => 'video',
                'audio' => 'audio',
                'file' => 'file',
                'location' => 'location',
                default => 'text',
            };
        }

        return 'text';
    }

    private function getLineProfile(string $userId): array
    {
        // Call LINE API to get profile
        // Implementation depends on LINE SDK
        return [];
    }

    private function getFacebookProfile(string $psid): array
    {
        // Call Facebook API to get profile
        // Implementation depends on FB SDK
        return [];
    }

    private function downloadAndStoreMedia(array $message, string $provider): ?string
    {
        // Download and store to S3/local storage
        // Return the stored URL
        return null;
    }

    private function extractUtmData(array $event): array
    {
        // Extract UTM parameters from message or postback
        return [];
    }

    private function detectAdSource(array $event): ?string
    {
        // Detect ad source from referral, postback, etc.
        return null;
    }
}
```

---

## 8. Booking Integration Workflow

### 8.1 Sequence Diagram

```
Admin                    Chat UI               BookingService        AppointmentController      Database
  │                         │                        │                        │                    │
  │ Click "Book" button     │                        │                        │                    │
  ├────────────────────────►│                        │                        │                    │
  │                         │                        │                        │                    │
  │                         │ Show Booking Modal     │                        │                    │
  │                         │ (pre-filled patient)   │                        │                    │
  │◄────────────────────────┤                        │                        │                    │
  │                         │                        │                        │                    │
  │ Submit booking form     │                        │                        │                    │
  ├────────────────────────►│                        │                        │                    │
  │                         │                        │                        │                    │
  │                         │ createBookingFromChat()│                        │                    │
  │                         ├───────────────────────►│                        │                    │
  │                         │                        │                        │                    │
  │                         │                        │ Check/Create Patient   │                    │
  │                         │                        ├───────────────────────►│                    │
  │                         │                        │◄───────────────────────┤                    │
  │                         │                        │                        │                    │
  │                         │                        │ Call store() method    │                    │
  │                         │                        ├───────────────────────►│                    │
  │                         │                        │                        │                    │
  │                         │                        │                        │ Create Appointment │
  │                         │                        │                        ├───────────────────►│
  │                         │                        │                        │◄───────────────────┤
  │                         │                        │                        │                    │
  │                         │                        │                        │ Create Queue       │
  │                         │                        │                        │ (if today)         │
  │                         │                        │                        ├───────────────────►│
  │                         │                        │                        │◄───────────────────┤
  │                         │                        │                        │                    │
  │                         │                        │ Appointment created    │                    │
  │                         │                        │◄───────────────────────┤                    │
  │                         │                        │                        │                    │
  │                         │                        │ Update DailyLeadTrack  │                    │
  │                         │                        │ status = 'booked'      │                    │
  │                         │                        ├────────────────────────────────────────────►│
  │                         │                        │◄────────────────────────────────────────────┤
  │                         │                        │                        │                    │
  │                         │                        │ Send confirmation msg  │                    │
  │                         │                        │ to customer via LINE/FB│                    │
  │                         │                        ├─────────►(LINE/FB API) │                    │
  │                         │                        │                        │                    │
  │                         │ Return success         │                        │                    │
  │                         │◄───────────────────────┤                        │                    │
  │                         │                        │                        │                    │
  │ Show success message    │                        │                        │                    │
  │◄────────────────────────┤                        │                        │                    │
  │                         │                        │                        │                    │
```

### 8.2 Booking Service Implementation

```php
<?php
// app/Services/SocialCrm/ChatBookingService.php

namespace App\Services\SocialCrm;

use App\Models\{
    Patient,
    Appointment,
    ChatConversation,
    DailyLeadTrack,
    SocialIdentity
};
use App\Http\Controllers\AppointmentController;
use App\Services\SocialCrm\MessageSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatBookingService
{
    public function __construct(
        private AppointmentController $appointmentController,
        private MessageSender $messageSender
    ) {}

    /**
     * Create booking from chat interface
     * Integrates with existing AppointmentController
     */
    public function createBookingFromChat(
        ChatConversation $conversation,
        array $bookingData,
        string $agentId
    ): array {
        return DB::transaction(function () use ($conversation, $bookingData, $agentId) {
            $identity = $conversation->socialIdentity;
            $patient = $identity->patient;

            // Step 1: Ensure patient exists
            if (!$patient) {
                $patient = $this->createPatientFromIdentity($identity, $bookingData);
                $identity->update(['patient_id' => $patient->id]);
            }

            // Step 2: Prepare request for AppointmentController
            $request = new Request([
                'customer_type' => 'existing',
                'patient_id' => $patient->id,
                'branch_id' => $conversation->branch_id,
                'appointment_date' => $bookingData['date'],
                'appointment_time' => $bookingData['time'],
                'pt_id' => $bookingData['pt_id'] ?? null,
                'booking_channel' => $this->mapProviderToChannel($identity->provider),
                'purpose' => $bookingData['purpose'] ?? 'PHYSICAL_THERAPY',
                'notes' => $this->buildNotesFromChat($conversation, $bookingData),
            ]);

            // Step 3: Call existing AppointmentController::store()
            $response = $this->appointmentController->store($request);
            $result = json_decode($response->getContent(), true);

            if (!$result['success']) {
                throw new \Exception($result['message'] ?? 'Booking failed');
            }

            // Step 4: Update lead tracking status
            $this->updateLeadStatus($conversation, $agentId);

            // Step 5: Send confirmation to customer
            $appointment = Appointment::find($result['appointment']['id']);
            $this->sendBookingConfirmation($conversation, $appointment);

            // Step 6: Add system message to chat
            $this->addBookingSystemMessage($conversation, $appointment);

            return [
                'success' => true,
                'appointment' => $appointment,
                'patient' => $patient,
            ];
        });
    }

    /**
     * Create patient from social identity
     */
    private function createPatientFromIdentity(
        SocialIdentity $identity,
        array $bookingData
    ): Patient {
        $nameParts = $this->parseThaiName($bookingData['name'] ?? $identity->profile_name);

        return Patient::create([
            'name' => $bookingData['name'] ?? $identity->profile_name,
            'first_name' => $nameParts['first_name'],
            'last_name' => $nameParts['last_name'],
            'phone' => $bookingData['phone'] ?? null,
            'email' => $bookingData['email'] ?? null,
            'booking_channel' => $this->mapProviderToChannel($identity->provider),
            'is_temporary' => false,
            'branch_id' => $bookingData['branch_id'] ?? null,
            'chief_complaint' => $bookingData['symptoms'] ?? null,
        ]);
    }

    /**
     * Update lead tracking when booking is made
     */
    private function updateLeadStatus(ChatConversation $conversation, string $agentId): void
    {
        $track = DailyLeadTrack::getOrCreateToday($conversation->id);
        $track->updateStatus(DailyLeadTrack::STATUS_BOOKED, $agentId);
    }

    /**
     * Send booking confirmation via LINE/Facebook
     */
    private function sendBookingConfirmation(
        ChatConversation $conversation,
        Appointment $appointment
    ): void {
        $identity = $conversation->socialIdentity;

        $message = $this->buildConfirmationMessage($appointment);

        $this->messageSender->send(
            $identity->provider,
            $identity->provider_user_id,
            $message
        );
    }

    /**
     * Add system message to chat history
     */
    private function addBookingSystemMessage(
        ChatConversation $conversation,
        Appointment $appointment
    ): void {
        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => ChatMessage::SENDER_SYSTEM,
            'message_type' => ChatMessage::TYPE_SYSTEM,
            'content' => sprintf(
                "Appointment booked: %s at %s",
                $appointment->appointment_date->format('d/m/Y'),
                substr($appointment->appointment_time, 0, 5)
            ),
            'meta_data' => [
                'action' => 'booking_created',
                'appointment_id' => $appointment->id,
            ],
        ]);
    }

    private function mapProviderToChannel(string $provider): string
    {
        return match ($provider) {
            'line' => 'line',
            'facebook' => 'facebook',
            default => 'other',
        };
    }

    private function parseThaiName(string $fullName): array
    {
        $parts = explode(' ', trim($fullName), 2);
        return [
            'first_name' => $parts[0] ?? $fullName,
            'last_name' => $parts[1] ?? '',
        ];
    }

    private function buildNotesFromChat(
        ChatConversation $conversation,
        array $bookingData
    ): string {
        $notes = [];
        $notes[] = "Booked via Social CRM";
        $notes[] = "Provider: " . $conversation->socialIdentity->provider;

        if (!empty($bookingData['notes'])) {
            $notes[] = $bookingData['notes'];
        }

        return implode("\n", $notes);
    }

    private function buildConfirmationMessage(Appointment $appointment): array
    {
        return [
            'type' => 'text',
            'text' => sprintf(
                "ยืนยันการนัดหมาย\n\nวันที่: %s\nเวลา: %s\nสาขา: %s\n\nขอบคุณที่ใช้บริการค่ะ",
                $appointment->appointment_date->format('d/m/Y'),
                substr($appointment->appointment_time, 0, 5),
                $appointment->branch->name ?? '-'
            ),
        ];
    }
}
```

### 8.3 Chat Booking Controller

```php
<?php
// app/Http/Controllers/SocialCrm/ChatBookingController.php

namespace App\Http\Controllers\SocialCrm;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Services\SocialCrm\ChatBookingService;
use Illuminate\Http\{Request, JsonResponse};

class ChatBookingController extends Controller
{
    public function __construct(
        private ChatBookingService $bookingService
    ) {}

    /**
     * Create booking from chat
     * POST /social-crm/conversations/{conversation}/book
     */
    public function store(Request $request, ChatConversation $conversation): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'purpose' => 'required|in:PHYSICAL_THERAPY,FOLLOW_UP',
            'pt_id' => 'nullable|exists:users,id',
            'symptoms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $result = $this->bookingService->createBookingFromChat(
                $conversation,
                $validated,
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'appointment' => $result['appointment'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get available time slots
     * GET /social-crm/available-slots
     */
    public function availableSlots(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'pt_id' => 'nullable|exists:users,id',
        ]);

        // Logic to get available slots based on existing appointments
        // ... (reuse existing appointment availability logic)

        return response()->json([
            'slots' => [], // Available time slots
        ]);
    }
}
```

---

## 9. Admin Chat Interface Logic

### 9.1 Chat Controller

```php
<?php
// app/Http/Controllers/SocialCrm/ChatController.php

namespace App\Http\Controllers\SocialCrm;

use App\Http\Controllers\Controller;
use App\Models\{ChatConversation, ChatMessage, User};
use App\Services\SocialCrm\MessageSender;
use Illuminate\Http\{Request, JsonResponse};

class ChatController extends Controller
{
    public function __construct(
        private MessageSender $messageSender
    ) {}

    /**
     * List conversations
     * GET /social-crm/conversations
     */
    public function index(Request $request): JsonResponse
    {
        $conversations = ChatConversation::with([
                'socialIdentity.patient',
                'currentAgent',
                'latestMessage',
                'todayTrack',
            ])
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('sender_type', 'customer')->where('is_read', false);
            }])
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->agent_id, fn($q, $a) => $q->where('current_agent_id', $a))
            ->when($request->unassigned, fn($q) => $q->whereNull('current_agent_id'))
            ->orderByDesc('last_interaction_at')
            ->paginate(20);

        return response()->json($conversations);
    }

    /**
     * Get conversation messages
     * GET /social-crm/conversations/{conversation}/messages
     */
    public function messages(ChatConversation $conversation): JsonResponse
    {
        // Mark messages as read
        $conversation->messages()
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'conversation' => $conversation->load('socialIdentity.patient', 'currentAgent'),
            'messages' => $messages,
        ]);
    }

    /**
     * Send message
     * POST /social-crm/conversations/{conversation}/messages
     */
    public function sendMessage(Request $request, ChatConversation $conversation): JsonResponse
    {
        $validated = $request->validate([
            'message_type' => 'required|in:text,image,template',
            'content' => 'required_if:message_type,text|string',
            'media_url' => 'required_if:message_type,image|url',
            'template_data' => 'required_if:message_type,template|array',
        ]);

        // Store message in database
        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => ChatMessage::SENDER_USER,
            'sender_id' => auth()->id(),
            'message_type' => $validated['message_type'],
            'content' => $validated['content'] ?? null,
            'media_url' => $validated['media_url'] ?? null,
            'meta_data' => $validated['template_data'] ?? null,
            'is_read' => true, // Agent's own message
        ]);

        // Send to customer via LINE/Facebook
        $identity = $conversation->socialIdentity;
        $this->messageSender->send(
            $identity->provider,
            $identity->provider_user_id,
            $this->formatOutgoingMessage($validated)
        );

        // Update last interaction
        $conversation->update(['last_interaction_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Assign agent to conversation
     * POST /social-crm/conversations/{conversation}/assign
     */
    public function assign(Request $request, ChatConversation $conversation): JsonResponse
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:users,id',
        ]);

        $agent = User::find($validated['agent_id']);
        $conversation->assignAgent($agent);

        // Add system message
        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => ChatMessage::SENDER_SYSTEM,
            'message_type' => ChatMessage::TYPE_SYSTEM,
            'content' => "Assigned to {$agent->name}",
        ]);

        return response()->json([
            'success' => true,
            'conversation' => $conversation->fresh('currentAgent'),
        ]);
    }

    /**
     * Update lead status
     * PUT /social-crm/conversations/{conversation}/status
     */
    public function updateStatus(Request $request, ChatConversation $conversation): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,interested,booked,completed,no_show,cancelled,lost',
            'notes' => 'nullable|string',
        ]);

        $track = DailyLeadTrack::getOrCreateToday($conversation->id);
        $track->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $track->notes,
        ]);

        return response()->json([
            'success' => true,
            'track' => $track,
        ]);
    }

    /**
     * Link conversation to existing patient
     * POST /social-crm/conversations/{conversation}/link-patient
     */
    public function linkPatient(Request $request, ChatConversation $conversation): JsonResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $conversation->socialIdentity->update([
            'patient_id' => $validated['patient_id'],
        ]);

        return response()->json([
            'success' => true,
            'conversation' => $conversation->fresh('socialIdentity.patient'),
        ]);
    }

    private function formatOutgoingMessage(array $data): array
    {
        return match ($data['message_type']) {
            'text' => ['type' => 'text', 'text' => $data['content']],
            'image' => ['type' => 'image', 'originalContentUrl' => $data['media_url'], 'previewImageUrl' => $data['media_url']],
            'template' => $data['template_data'],
            default => ['type' => 'text', 'text' => $data['content'] ?? ''],
        };
    }
}
```

---

## 10. ROI & Analytics Queries

### 10.1 Daily Lead Report

```php
<?php
// app/Services/SocialCrm/AnalyticsService.php

namespace App\Services\SocialCrm;

use App\Models\DailyLeadTrack;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get daily lead summary
     */
    public function getDailySummary(string $date, ?string $branchId = null): array
    {
        return DailyLeadTrack::query()
            ->where('tracking_date', $date)
            ->when($branchId, function ($query) use ($branchId) {
                $query->whereHas('conversation', fn($q) => $q->where('branch_id', $branchId));
            })
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get conversion funnel
     */
    public function getConversionFunnel(string $startDate, string $endDate, ?string $adSourceId = null): array
    {
        $query = DailyLeadTrack::query()
            ->whereBetween('tracking_date', [$startDate, $endDate])
            ->when($adSourceId, fn($q) => $q->where('ad_source_id', $adSourceId));

        return [
            'total_leads' => (clone $query)->distinct('conversation_id')->count(),
            'contacted' => (clone $query)->whereIn('status', ['contacted', 'interested', 'booked', 'completed'])->distinct('conversation_id')->count(),
            'interested' => (clone $query)->whereIn('status', ['interested', 'booked', 'completed'])->distinct('conversation_id')->count(),
            'booked' => (clone $query)->whereIn('status', ['booked', 'completed'])->distinct('conversation_id')->count(),
            'completed' => (clone $query)->where('status', 'completed')->distinct('conversation_id')->count(),
            'no_show' => (clone $query)->where('status', 'no_show')->distinct('conversation_id')->count(),
        ];
    }

    /**
     * Get ROI by ad source
     */
    public function getROIByAdSource(string $startDate, string $endDate): array
    {
        return DailyLeadTrack::query()
            ->whereBetween('tracking_date', [$startDate, $endDate])
            ->whereNotNull('ad_source_id')
            ->selectRaw("
                ad_source_id,
                COUNT(DISTINCT conversation_id) as total_leads,
                COUNT(DISTINCT CASE WHEN status = 'booked' THEN conversation_id END) as bookings,
                COUNT(DISTINCT CASE WHEN status = 'completed' THEN conversation_id END) as completed
            ")
            ->groupBy('ad_source_id')
            ->get()
            ->map(function ($row) {
                $row->booking_rate = $row->total_leads > 0
                    ? round(($row->bookings / $row->total_leads) * 100, 2)
                    : 0;
                $row->completion_rate = $row->bookings > 0
                    ? round(($row->completed / $row->bookings) * 100, 2)
                    : 0;
                return $row;
            })
            ->toArray();
    }

    /**
     * Get agent performance
     */
    public function getAgentPerformance(string $startDate, string $endDate): array
    {
        return DailyLeadTrack::query()
            ->whereBetween('tracking_date', [$startDate, $endDate])
            ->whereNotNull('sale_closed_by')
            ->with('saleClosedBy:id,name')
            ->selectRaw("
                sale_closed_by,
                COUNT(*) as total_closed,
                COUNT(CASE WHEN status = 'booked' THEN 1 END) as bookings,
                COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed
            ")
            ->groupBy('sale_closed_by')
            ->get()
            ->toArray();
    }
}
```

### 10.2 Sample SQL Queries

```sql
-- Daily Lead Summary
SELECT
    tracking_date,
    status,
    COUNT(*) as count
FROM daily_lead_tracks
WHERE tracking_date BETWEEN '2025-12-01' AND '2025-12-31'
GROUP BY tracking_date, status
ORDER BY tracking_date, status;

-- Conversion Funnel by Ad Source
SELECT
    ad_source_id,
    COUNT(DISTINCT conversation_id) as total_leads,
    COUNT(DISTINCT CASE WHEN status IN ('contacted', 'interested', 'booked', 'completed')
          THEN conversation_id END) as contacted,
    COUNT(DISTINCT CASE WHEN status IN ('interested', 'booked', 'completed')
          THEN conversation_id END) as interested,
    COUNT(DISTINCT CASE WHEN status IN ('booked', 'completed')
          THEN conversation_id END) as booked,
    COUNT(DISTINCT CASE WHEN status = 'completed'
          THEN conversation_id END) as completed,
    ROUND(
        COUNT(DISTINCT CASE WHEN status IN ('booked', 'completed') THEN conversation_id END) * 100.0 /
        NULLIF(COUNT(DISTINCT conversation_id), 0), 2
    ) as booking_rate
FROM daily_lead_tracks
WHERE tracking_date BETWEEN '2025-12-01' AND '2025-12-31'
GROUP BY ad_source_id
ORDER BY total_leads DESC;

-- Agent Performance
SELECT
    u.name as agent_name,
    COUNT(*) as leads_handled,
    COUNT(CASE WHEN dlt.status = 'booked' THEN 1 END) as bookings,
    COUNT(CASE WHEN dlt.status = 'completed' THEN 1 END) as completed,
    ROUND(
        COUNT(CASE WHEN dlt.status = 'booked' THEN 1 END) * 100.0 /
        NULLIF(COUNT(*), 0), 2
    ) as booking_rate
FROM daily_lead_tracks dlt
JOIN users u ON u.id = dlt.sale_closed_by
WHERE dlt.tracking_date BETWEEN '2025-12-01' AND '2025-12-31'
GROUP BY dlt.sale_closed_by, u.name
ORDER BY bookings DESC;

-- Lead Source ROI (with estimated revenue)
SELECT
    dlt.ad_source_id,
    COUNT(DISTINCT dlt.conversation_id) as total_leads,
    COUNT(DISTINCT CASE WHEN dlt.status = 'completed' THEN dlt.conversation_id END) as conversions,
    COALESCE(SUM(i.total_amount), 0) as total_revenue
FROM daily_lead_tracks dlt
LEFT JOIN chat_conversations cc ON cc.id = dlt.conversation_id
LEFT JOIN social_identities si ON si.id = cc.social_identity_id
LEFT JOIN patients p ON p.id = si.patient_id
LEFT JOIN invoices i ON i.patient_id = p.id
    AND i.invoice_date BETWEEN dlt.tracking_date AND DATE_ADD(dlt.tracking_date, INTERVAL 30 DAY)
WHERE dlt.tracking_date BETWEEN '2025-12-01' AND '2025-12-31'
GROUP BY dlt.ad_source_id
ORDER BY total_revenue DESC;
```

---

## 11. API Routes Summary

```php
<?php
// routes/api.php (additions)

use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\SocialCrm\{ChatController, ChatBookingController};

// Webhook endpoints (no auth)
Route::post('/webhook/line', [WebhookController::class, 'line']);
Route::match(['get', 'post'], '/webhook/facebook', [WebhookController::class, 'facebook']);

// Social CRM API (requires auth)
Route::middleware('auth:sanctum')->prefix('social-crm')->group(function () {
    // Conversations
    Route::get('/conversations', [ChatController::class, 'index']);
    Route::get('/conversations/{conversation}/messages', [ChatController::class, 'messages']);
    Route::post('/conversations/{conversation}/messages', [ChatController::class, 'sendMessage']);
    Route::post('/conversations/{conversation}/assign', [ChatController::class, 'assign']);
    Route::put('/conversations/{conversation}/status', [ChatController::class, 'updateStatus']);
    Route::post('/conversations/{conversation}/link-patient', [ChatController::class, 'linkPatient']);

    // Booking
    Route::post('/conversations/{conversation}/book', [ChatBookingController::class, 'store']);
    Route::get('/available-slots', [ChatBookingController::class, 'availableSlots']);

    // Analytics
    Route::get('/analytics/daily', [AnalyticsController::class, 'daily']);
    Route::get('/analytics/funnel', [AnalyticsController::class, 'funnel']);
    Route::get('/analytics/roi', [AnalyticsController::class, 'roi']);
    Route::get('/analytics/agents', [AnalyticsController::class, 'agents']);
});
```

---

## 12. Implementation Checklist

### Phase 1: Database & Models
- [ ] Run migrations for 4 new tables
- [ ] Create Model files with relationships
- [ ] Add BranchScope to ChatConversation

### Phase 2: Webhook Integration
- [ ] Set up LINE Messaging API credentials
- [ ] Set up Facebook Page webhook
- [ ] Implement WebhookController
- [ ] Implement WebhookProcessor service
- [ ] Test webhook reception

### Phase 3: Admin Interface
- [ ] Create ChatController
- [ ] Build conversation list UI
- [ ] Build chat interface UI
- [ ] Implement real-time updates (Pusher/WebSocket)

### Phase 4: Booking Integration
- [ ] Create ChatBookingService
- [ ] Integrate with existing AppointmentController
- [ ] Add booking modal to chat UI
- [ ] Send confirmations via LINE/FB

### Phase 5: Analytics
- [ ] Create AnalyticsService
- [ ] Build ROI dashboard
- [ ] Create daily/weekly reports

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2025-12-12 | System | Initial design document |

---

*End of Technical Design Document*
