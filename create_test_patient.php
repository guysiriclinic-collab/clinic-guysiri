<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$branch = \App\Models\Branch::first();

if (!$branch) {
    echo "❌ No branch found!\n";
    exit(1);
}

$patient = \App\Models\Patient::create([
    'phone' => '0812345678',
    'name' => 'John Doe Test',
    'email' => 'john@test.com',
    'date_of_birth' => '1990-01-01',
    'gender' => 'male',
    'address' => '123 Test St',
    'first_visit_branch_id' => $branch->id
]);

echo "✅ Patient created: {$patient->name} ({$patient->phone})\n";
echo "   Branch: {$branch->name}\n";
echo "   Patient ID: {$patient->id}\n";
