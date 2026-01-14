<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;

$patient = Patient::where('id', 'a06a7b56-4613-4079-8365-0456619d36b9')->first();
if ($patient) {
    echo "=== All Patient Data ===" . PHP_EOL;
    foreach ($patient->toArray() as $key => $value) {
        echo $key . ": " . ($value ?? 'NULL') . PHP_EOL;
    }
} else {
    echo "Patient not found" . PHP_EOL;
}
