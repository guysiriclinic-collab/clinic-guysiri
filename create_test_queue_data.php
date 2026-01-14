<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Branch;

echo "=== Creating Test Queue Data ===\n\n";

// Get branch
$branch = Branch::first();
if (!$branch) {
    echo "No branch found. Creating one...\n";
    $branch = Branch::create([
        'name' => 'สาขาหลัก',
        'code' => 'HQ',
        'is_active' => true
    ]);
}

// Get or create test patients
$patients = Patient::take(10)->get();

if ($patients->count() < 10) {
    echo "Creating test patients...\n";
    $names = [
        'สมชาย ใจดี',
        'สมหญิง รักษา',
        'วิชัย แข็งแรง',
        'มาลี สุขสันต์',
        'ประเสริฐ ดีมาก',
        'สุภา เจริญผล',
        'อรุณ สว่าง',
        'นภา ฟ้าใส',
        'พิชัย กล้าหาญ',
        'รัตนา งามตา'
    ];

    foreach ($names as $name) {
        $existing = Patient::where('name', $name)->first();
        if (!$existing) {
            Patient::create([
                'name' => $name,
                'phone' => '08' . rand(10000000, 99999999),
                'branch_id' => $branch->id
            ]);
        }
    }
    $patients = Patient::take(10)->get();
}

// Delete today's appointments first
$deleted = Appointment::whereDate('appointment_date', today())->delete();
echo "Deleted $deleted existing appointments for today.\n";

// Create appointments for today
$times = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '13:00', '13:30', '14:00', '14:30'];
$statuses = ['pending', 'pending', 'pending', 'pending', 'confirmed', 'pending', 'pending', 'pending', 'pending', 'pending'];
$purposes = ['PHYSICAL_THERAPY', 'FOLLOW_UP'];

echo "\nCreating today's appointments:\n";

foreach ($patients as $index => $patient) {
    if ($index >= count($times)) break;

    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'branch_id' => $branch->id,
        'appointment_date' => today(),
        'appointment_time' => $times[$index] . ':00',
        'status' => $statuses[$index],
        'purpose' => $purposes[array_rand($purposes)],
        'booking_channel' => 'phone',
        'notes' => 'Test appointment'
    ]);

    echo "  - " . str_pad($index + 1, 2, '0', STR_PAD_LEFT) . ". {$patient->name} @ {$times[$index]} ({$statuses[$index]})\n";
}

echo "\n=== Test Data Created Successfully ===\n";
echo "\nYou can now test:\n";
echo "  - Queue page: http://localhost:8000/queue\n";
echo "  - TV Display: http://localhost:8000/queue/display\n";
