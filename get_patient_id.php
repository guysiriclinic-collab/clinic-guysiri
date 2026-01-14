<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$patient = \App\Models\Patient::where('phone', '0812345678')->first();

if ($patient) {
    echo $patient->id . "\n";
    echo $patient->name . "\n";
} else {
    echo "Patient not found\n";
}
