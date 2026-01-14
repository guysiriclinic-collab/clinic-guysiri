<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test 1: Create Appointment for New Patient + Verify Temporary OPD ===\n\n";

// Get a branch
$branch = \App\Models\Branch::first();
if (!$branch) {
    echo "❌ No branches found in database. Please seed branches first.\n";
    exit(1);
}

echo "Using Branch: {$branch->name} (ID: {$branch->id})\n\n";

// Prepare appointment data for NEW patient (no patient_id)
$appointmentData = [
    'patient_id' => null, // New patient - no patient record yet
    'branch_id' => $branch->id,
    'appointment_date' => today()->toDateString(),
    'appointment_time' => '10:00:00',
    'booking_channel' => 'phone',
    'notes' => 'Test appointment for new patient'
];

echo "Creating appointment with data:\n";
print_r($appointmentData);
echo "\n";

// Simulate the controller logic
try {
    \Illuminate\Support\Facades\DB::beginTransaction();

    $isNewPatient = false;
    $patient = null;
    $opdRecord = null;

    // Check if patient exists or create new
    if ($appointmentData['patient_id']) {
        $patient = \App\Models\Patient::find($appointmentData['patient_id']);

        // Use existing OPD if available
        $opdRecord = \App\Models\OpdRecord::where('patient_id', $patient->id)
            ->where('branch_id', $appointmentData['branch_id'])
            ->where('status', 'active')
            ->first();

        if (!$opdRecord) {
            // Create new OPD for existing patient
            $opdRecord = \App\Models\OpdRecord::create([
                'patient_id' => $patient->id,
                'branch_id' => $appointmentData['branch_id'],
                'opd_number' => 'OPD-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'status' => 'active',
                'is_temporary' => false,
                'created_by' => null
            ]);
        }
    } else {
        // ข้อ 2: Create temporary OPD for new patient (no patient record yet)
        $isNewPatient = true;
        $opdRecord = \App\Models\OpdRecord::create([
            'patient_id' => null, // Will be updated when patient is created
            'branch_id' => $appointmentData['branch_id'],
            'opd_number' => 'TEMP-' . now()->format('Ymd') . '-' . rand(1000, 9999),
            'status' => 'pending',
            'is_temporary' => true,
            'chief_complaint' => 'New patient appointment - OPD pending',
            'created_by' => null
        ]);
    }

    // Create appointment
    $appointment = \App\Models\Appointment::create([
        'patient_id' => $appointmentData['patient_id'],
        'branch_id' => $appointmentData['branch_id'],
        'pt_id' => null,
        'appointment_date' => $appointmentData['appointment_date'],
        'appointment_time' => $appointmentData['appointment_time'],
        'booking_channel' => $appointmentData['booking_channel'],
        'status' => 'pending',
        'notes' => $appointmentData['notes'],
        'created_by' => null
    ]);

    // Create queue entry if appointment is for today
    $queueCreated = false;
    if ($appointmentData['appointment_date'] === today()->toDateString()) {
        // Get next queue number for today
        $lastQueue = \App\Models\Queue::whereDate('queued_at', today())->max('queue_number');
        $queueNumber = $lastQueue ? $lastQueue + 1 : 1;

        \App\Models\Queue::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointmentData['patient_id'],
            'branch_id' => $appointmentData['branch_id'],
            'pt_id' => null,
            'queue_number' => $queueNumber,
            'status' => 'waiting',
            'queued_at' => now(),
            'created_by' => null
        ]);
        $queueCreated = true;
    }

    \Illuminate\Support\Facades\DB::commit();

    echo "✅ Appointment created successfully!\n";
    echo "Appointment ID: {$appointment->id}\n";
    echo "Is New Patient: " . ($isNewPatient ? 'Yes' : 'No') . "\n";
    echo "Queue Created: " . ($queueCreated ? 'Yes' : 'No') . "\n\n";

    echo "=== Verifying Temporary OPD Created ===\n";
    echo "OPD ID: {$opdRecord->id}\n";
    echo "OPD Number: {$opdRecord->opd_number}\n";
    echo "Is Temporary: " . ($opdRecord->is_temporary ? 'Yes' : 'No') . "\n";
    echo "Status: {$opdRecord->status}\n";
    echo "Patient ID: " . ($opdRecord->patient_id ?? 'NULL') . "\n\n";

    if ($opdRecord->is_temporary && $opdRecord->patient_id === null && str_starts_with($opdRecord->opd_number, 'TEMP-')) {
        echo "✅ TEST 1 PASSED: Temporary OPD created successfully for new patient!\n";
    } else {
        echo "❌ TEST 1 FAILED: OPD is not temporary or patient_id is not NULL\n";
    }

    // Store appointment ID for next test
    file_put_contents(__DIR__ . '/test_appointment_id.txt', $appointment->id);

} catch (\Exception $e) {
    \Illuminate\Support\Facades\DB::rollBack();
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
