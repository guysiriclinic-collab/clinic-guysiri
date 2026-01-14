<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;

$patient = Patient::first();
if ($patient) {
    echo "Patient ID: " . $patient->id . PHP_EOL;
    echo "Name: " . $patient->name . PHP_EOL;
    echo "address: " . ($patient->address ?: 'EMPTY') . PHP_EOL;
    echo "emergency_contact: " . ($patient->emergency_contact ?: 'EMPTY') . PHP_EOL;
    echo "subdistrict: " . ($patient->subdistrict ?: 'EMPTY') . PHP_EOL;
    echo "district: " . ($patient->district ?: 'EMPTY') . PHP_EOL;
    echo "province: " . ($patient->province ?: 'EMPTY') . PHP_EOL;
    echo "phone: " . ($patient->phone ?: 'EMPTY') . PHP_EOL;
    echo "email: " . ($patient->email ?: 'EMPTY') . PHP_EOL;
    echo "line_id: " . ($patient->line_id ?: 'EMPTY') . PHP_EOL;
} else {
    echo "No patient found" . PHP_EOL;
}
