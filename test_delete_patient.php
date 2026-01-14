<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get patient (including soft deleted)
$patient = \App\Models\Patient::withTrashed()->where('phone', '0812345678')->first();

echo "Before Delete:\n";
echo "Name: {$patient->name}\n";
echo "Deleted At: " . ($patient->deleted_at ? $patient->deleted_at : 'NULL') . "\n\n";

// Soft delete patient
$patient->delete();

// Refresh patient data (must use withTrashed)
$patient = \App\Models\Patient::withTrashed()->where('phone', '0812345678')->first();

echo "After Delete:\n";
echo "Name: {$patient->name}\n";
echo "Deleted At: " . ($patient->deleted_at ? $patient->deleted_at : 'NULL') . "\n";

if ($patient->deleted_at !== null) {
    echo "\n✅ Soft Delete successful! deleted_at is NOT NULL\n";
} else {
    echo "\n❌ Soft Delete FAILED! deleted_at is still NULL\n";
}

// Check if patient is hidden from normal queries
$normalQuery = \App\Models\Patient::where('phone', '0812345678')->first();
if ($normalQuery === null) {
    echo "✅ Patient is hidden from normal queries (soft deleted)\n";
} else {
    echo "❌ Patient is still visible in normal queries\n";
}
