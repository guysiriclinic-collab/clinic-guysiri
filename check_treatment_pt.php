<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

use App\Models\Treatment;
use App\Models\User;

$treatment = Treatment::latest()->first();

if ($treatment) {
    echo "=== Latest Treatment ===\n";
    echo "Treatment ID: {$treatment->id}\n";
    echo "Appointment ID: {$treatment->appointment_id}\n";
    echo "PT ID: {$treatment->pt_id}\n";

    if ($treatment->pt_id) {
        $pt = User::find($treatment->pt_id);
        echo "PT Name: " . ($pt ? $pt->name : 'Not found') . "\n";
    }

    echo "Service ID: {$treatment->service_id}\n";
    echo "Billing Status: {$treatment->billing_status}\n";
} else {
    echo "No treatments found\n";
}
