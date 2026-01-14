<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Creating test data...\n\n";

// Create a branch
$branch = \App\Models\Branch::firstOrCreate(
    ['code' => 'MAIN'],
    [
        'name' => 'Main Branch',
        'address' => '123 Main Street',
        'phone' => '02-123-4567',
        'is_active' => true
    ]
);

echo "✅ Branch created: {$branch->name} (ID: {$branch->id})\n";

// Create a patient for testing
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

echo "✅ Patient created: {$patient->name} (ID: {$patient->id})\n";

echo "\nTest data ready!\n";
