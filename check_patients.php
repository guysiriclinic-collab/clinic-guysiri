<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$patients = \App\Models\Patient::with('firstVisitBranch')->get();

echo "Total Patients in Database: " . $patients->count() . "\n\n";

foreach ($patients as $patient) {
    echo "ID: {$patient->id}\n";
    echo "Phone: {$patient->phone}\n";
    echo "Name: {$patient->name}\n";
    echo "Email: {$patient->email}\n";
    echo "Branch: {$patient->firstVisitBranch->name}\n";
    echo "Created: {$patient->created_at}\n";
    echo str_repeat('-', 50) . "\n";
}
