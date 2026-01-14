<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Setting Up Billing Test Data ===\n\n";

try {
    \Illuminate\Support\Facades\DB::beginTransaction();

    // 1. Create Branch
    $branch = \App\Models\Branch::firstOrCreate(
        ['code' => 'MAIN'],
        [
            'name' => 'Main Branch',
            'address' => '123 Main Street',
            'phone' => '02-123-4567',
            'is_active' => true
        ]
    );
    echo "✅ Branch: {$branch->name}\n";

    // 2. Create PT User for testing (use DB::table to bypass fillable)
    $existingUser = \App\Models\User::where('email', 'pt.test@example.com')->first();
    if (!$existingUser) {
        \Illuminate\Support\Facades\DB::table('users')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'email' => 'pt.test@example.com',
            'username' => 'pt.test',
            'name' => 'PT Test',
            'password' => bcrypt('password'),
            'branch_id' => $branch->id,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $ptUser = \App\Models\User::where('email', 'pt.test@example.com')->first();
    } else {
        $ptUser = $existingUser;
    }
    echo "✅ PT User: {$ptUser->username}\n";

    // 2. Create Patient
    $patient = \App\Models\Patient::firstOrCreate(
        ['phone' => '0812345678'],
        [
            'name' => 'John Doe Test',
            'email' => 'john.test@example.com',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'first_visit_branch_id' => $branch->id,
        ]
    );
    echo "✅ Patient: {$patient->name}\n";

    // 3. Create OPD Record
    $opdRecord = \App\Models\OpdRecord::firstOrCreate(
        ['patient_id' => $patient->id, 'branch_id' => $branch->id, 'status' => 'active'],
        [
            'opd_number' => 'OPD-' . now()->format('Ymd') . '-' . rand(1000, 9999),
            'status' => 'active',
            'is_temporary' => false,
            'chief_complaint' => 'Test OPD for billing',
            'created_by' => null
        ]
    );
    echo "✅ OPD: {$opdRecord->opd_number}\n";

    // 4. Create Services
    $service1 = \App\Models\Service::firstOrCreate(
        ['code' => 'PT-001'],
        [
            'name' => 'Physical Therapy Session',
            'description' => 'Standard PT session',
            'default_price' => 500.00,
            'is_active' => true,
            'category' => 'treatment',
            'created_by' => null
        ]
    );
    echo "✅ Service 1: {$service1->name} (฿{$service1->default_price})\n";

    $service2 = \App\Models\Service::firstOrCreate(
        ['code' => 'PT-002'],
        [
            'name' => 'Premium PT Session',
            'description' => 'Premium PT session with advanced equipment',
            'default_price' => 800.00,
            'is_active' => true,
            'category' => 'treatment',
            'created_by' => null
        ]
    );
    echo "✅ Service 2: {$service2->name} (฿{$service2->default_price})\n";

    // 5. Create Course Packages
    $package1 = \App\Models\CoursePackage::firstOrCreate(
        ['code' => 'PKG-001'],
        [
            'name' => 'PT Package 10 Sessions',
            'description' => '10 sessions physical therapy package',
            'price' => 4000.00, // Discounted from 5000 (10 x 500)
            'total_sessions' => 10,
            'validity_days' => 90,
            'is_active' => true,
            'service_id' => $service1->id,
            'commission_rate' => 10.00,
            'per_session_commission_rate' => 0.00,
            'df_rate' => 60.00,
            'allow_buy_and_use' => true,
            'allow_buy_for_later' => true,
            'allow_retroactive' => true,
            'created_by' => null
        ]
    );
    echo "✅ Package 1: {$package1->name} (฿{$package1->price}, {$package1->total_sessions} sessions)\n";

    $package2 = \App\Models\CoursePackage::firstOrCreate(
        ['code' => 'PKG-002'],
        [
            'name' => 'Premium PT Package 5 Sessions',
            'description' => '5 sessions premium PT package',
            'price' => 3500.00, // Discounted from 4000 (5 x 800)
            'total_sessions' => 5,
            'validity_days' => 60,
            'is_active' => true,
            'service_id' => $service2->id,
            'commission_rate' => 15.00,
            'per_session_commission_rate' => 0.00,
            'df_rate' => 70.00,
            'allow_buy_and_use' => true,
            'allow_buy_for_later' => true,
            'allow_retroactive' => true,
            'created_by' => null
        ]
    );
    echo "✅ Package 2: {$package2->name} (฿{$package2->price}, {$package2->total_sessions} sessions)\n";

    // 6. Create Appointment for today
    $appointment = \App\Models\Appointment::create([
        'patient_id' => $patient->id,
        'branch_id' => $branch->id,
        'pt_id' => null,
        'appointment_date' => today(),
        'appointment_time' => '10:00:00',
        'booking_channel' => 'walk_in',
        'status' => 'completed',
        'notes' => 'Test appointment for billing',
        'created_by' => null
    ]);
    echo "✅ Appointment created\n";

    // 7. Create Queue entry (completed - waiting for payment)
    $lastQueue = \App\Models\Queue::whereDate('queued_at', today())->max('queue_number');
    $queueNumber = $lastQueue ? $lastQueue + 1 : 1;

    $queue = \App\Models\Queue::create([
        'appointment_id' => $appointment->id,
        'patient_id' => $patient->id,
        'branch_id' => $branch->id,
        'pt_id' => null,
        'queue_number' => $queueNumber,
        'status' => 'completed',
        'queued_at' => now(),
        'started_at' => now()->subMinutes(30),
        'completed_at' => now()->subMinutes(5),
        'waiting_time_minutes' => 25,
        'is_overtime' => false,
        'created_by' => null
    ]);
    echo "✅ Queue: Q{$queue->queue_number} (status: {$queue->status})\n";

    \Illuminate\Support\Facades\DB::commit();

    echo "\n=== Test Data Setup Complete! ===\n";
    echo "Queue ID: {$queue->id}\n";
    echo "Patient ID: {$patient->id}\n";
    echo "OPD ID: {$opdRecord->id}\n";
    echo "Service 1 ID: {$service1->id}\n";
    echo "Service 2 ID: {$service2->id}\n";
    echo "Package 1 ID: {$package1->id}\n";
    echo "Package 2 ID: {$package2->id}\n\n";

    // Save IDs for tests
    file_put_contents(__DIR__ . '/billing_test_ids.txt', json_encode([
        'queue_id' => $queue->id,
        'patient_id' => $patient->id,
        'opd_id' => $opdRecord->id,
        'service1_id' => $service1->id,
        'service2_id' => $service2->id,
        'package1_id' => $package1->id,
        'package2_id' => $package2->id,
        'branch_id' => $branch->id,
        'pt_id' => $ptUser->id,
    ]));

    echo "Ready for testing!\n";

} catch (\Exception $e) {
    \Illuminate\Support\Facades\DB::rollBack();
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
