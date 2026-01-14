<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get patient
$patient = \App\Models\Patient::where('phone', '0812345678')->first();

echo "Before Update:\n";
echo "Name: {$patient->name}\n";
echo "Updated At: {$patient->updated_at}\n\n";

// Update patient
$patient->update([
    'name' => 'John Doe Updated',
    'email' => 'john.updated@test.com',
]);

// Refresh patient data
$patient->refresh();

echo "After Update:\n";
echo "Name: {$patient->name}\n";
echo "Updated At: {$patient->updated_at}\n";
echo "\n✅ Update successful!\n";
