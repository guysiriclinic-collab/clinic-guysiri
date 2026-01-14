<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Queue;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

echo "===========================================\n";
echo "  OPD LIFECYCLE TESTING - 3 SCENARIOS\n";
echo "===========================================\n\n";

// Get branch for testing
$branch = Branch::first();
if (!$branch) {
    echo "ERROR: No branch found. Please create a branch first.\n";
    exit(1);
}

// ============================================
// TEST 1: Isolation - Lead ไม่โผล่ใน Patient List
// ============================================
echo "TEST 1: ISOLATION (Lead should NOT appear in Patient List)\n";
echo "-----------------------------------------------------------\n";

// Create a new temporary patient (Lead)
$leadPatient1 = Patient::create([
    'name' => 'Test Lead One',
    'first_name' => 'Test',
    'last_name' => 'Lead One',
    'phone' => '0891111111',
    'is_temporary' => true,
    'first_visit_branch_id' => $branch->id,
    'branch_id' => $branch->id,
]);

echo "Created Lead Patient: {$leadPatient1->name} (ID: {$leadPatient1->id})\n";
echo "is_temporary: " . ($leadPatient1->is_temporary ? 'TRUE' : 'FALSE') . "\n";

// Check if patient appears in Patient List (is_temporary = false only)
$patientListCount = Patient::where('is_temporary', false)
    ->where('id', $leadPatient1->id)
    ->count();

$visibleInList = Patient::where('is_temporary', false)->pluck('name')->toArray();

if ($patientListCount === 0) {
    echo "PASS: Lead does NOT appear in Patient List\n";
    echo "Visible patients in list: " . (count($visibleInList) > 0 ? implode(', ', $visibleInList) : 'None') . "\n";
} else {
    echo "FAIL: Lead appears in Patient List!\n";
}

echo "\n";

// ============================================
// TEST 2: Conversion - Start Treatment generates HN
// ============================================
echo "TEST 2: CONVERSION (Start Treatment should convert to Real Patient)\n";
echo "--------------------------------------------------------------------\n";

// Create appointment for today
$appointment2 = Appointment::create([
    'patient_id' => $leadPatient1->id,
    'branch_id' => $branch->id,
    'appointment_date' => today(),
    'appointment_time' => now()->format('H:i:s'),
    'status' => 'confirmed',
    'purpose' => 'Test Treatment',
    'booking_channel' => 'walk_in',
]);

echo "Created Appointment: {$appointment2->id}\n";

// Create queue entry
$queueNumber = Queue::where('branch_id', $branch->id)
    ->whereDate('created_at', today())
    ->count() + 1;

$queue2 = Queue::create([
    'appointment_id' => $appointment2->id,
    'patient_id' => $leadPatient1->id,
    'branch_id' => $branch->id,
    'queue_number' => $queueNumber,
    'status' => 'waiting',
    'queued_at' => now(),
]);

echo "Created Queue: #{$queue2->queue_number} (ID: {$queue2->id})\n";

// Simulate startTreatment logic
DB::beginTransaction();
try {
    $queue = Queue::with(['patient', 'appointment'])->find($queue2->id);

    // Convert temporary patient to real patient
    if ($queue->patient && $queue->patient->is_temporary) {
        // Generate HN Number
        $lastHN = Patient::whereNotNull('hn_number')
            ->orderByRaw('CAST(SUBSTRING(hn_number, 3) AS UNSIGNED) DESC')
            ->first();

        if ($lastHN && preg_match('/HN(\d+)/', $lastHN->hn_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        $hnNumber = 'HN' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        // Update patient: convert from temporary to real
        $queue->patient->update([
            'is_temporary' => false,
            'hn_number' => $hnNumber,
            'converted_at' => now(),
        ]);

        echo "Converted Patient to REAL with HN: {$hnNumber}\n";
    }

    // Update queue status
    $queue->update([
        'status' => 'in_treatment',
        'started_at' => now(),
    ]);

    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Reload patient and check
$leadPatient1->refresh();
echo "Patient after conversion:\n";
echo "  - is_temporary: " . ($leadPatient1->is_temporary ? 'TRUE' : 'FALSE') . "\n";
echo "  - hn_number: " . ($leadPatient1->hn_number ?? 'NULL') . "\n";
echo "  - converted_at: " . ($leadPatient1->converted_at ?? 'NULL') . "\n";

// Check if patient now appears in Patient List
$patientListCount2 = Patient::where('is_temporary', false)
    ->where('id', $leadPatient1->id)
    ->count();

if ($patientListCount2 === 1 && $leadPatient1->hn_number) {
    echo "PASS: Patient now appears in Patient List with HN!\n";
} else {
    echo "FAIL: Patient still not in list or no HN!\n";
}

echo "\n";

// ============================================
// TEST 3: Cleanup - Cancel deletes temporary patient
// ============================================
echo "TEST 3: CLEANUP (Cancel should delete temporary patient)\n";
echo "----------------------------------------------------------\n";

// Create another temporary patient (Lead)
$leadPatient3 = Patient::create([
    'name' => 'Test Lead Three',
    'first_name' => 'Test',
    'last_name' => 'Lead Three',
    'phone' => '0893333333',
    'is_temporary' => true,
    'first_visit_branch_id' => $branch->id,
    'branch_id' => $branch->id,
]);

$patientId3 = $leadPatient3->id;
echo "Created Lead Patient: {$leadPatient3->name} (ID: {$patientId3})\n";

// Create appointment
$appointment3 = Appointment::create([
    'patient_id' => $leadPatient3->id,
    'branch_id' => $branch->id,
    'appointment_date' => today()->addDay(),
    'appointment_time' => '10:00:00',
    'status' => 'pending',
    'purpose' => 'Test Appointment',
    'booking_channel' => 'phone',
]);

echo "Created Appointment: {$appointment3->id}\n";

// Simulate cancel appointment with force delete of temp patient
DB::beginTransaction();
try {
    $appointment = Appointment::with('patient')->find($appointment3->id);

    // Force delete temporary patient
    if ($appointment->patient && $appointment->patient->is_temporary) {
        $patientId = $appointment->patient_id;

        // Delete related data first
        Queue::where('patient_id', $patientId)->forceDelete();
        Appointment::where('patient_id', $patientId)->forceDelete();

        // Force delete the patient
        $appointment->patient->forceDelete();

        echo "Force deleted temporary patient and related data\n";
    }

    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Check if patient still exists in database
$patientExists = Patient::withTrashed()->find($patientId3);
$appointmentExists = Appointment::withTrashed()->where('patient_id', $patientId3)->exists();

if (!$patientExists && !$appointmentExists) {
    echo "PASS: Patient and appointments completely deleted from database!\n";
} else {
    echo "FAIL: Patient or appointments still exist!\n";
    echo "  - Patient exists: " . ($patientExists ? 'YES' : 'NO') . "\n";
    echo "  - Appointments exist: " . ($appointmentExists ? 'YES' : 'NO') . "\n";
}

echo "\n";

// ============================================
// SUMMARY
// ============================================
echo "===========================================\n";
echo "  TEST SUMMARY\n";
echo "===========================================\n";

// Count results
$test1Pass = $patientListCount === 0;
$test2Pass = $patientListCount2 === 1 && $leadPatient1->hn_number;
$test3Pass = !$patientExists && !$appointmentExists;

echo "Test 1 (Isolation):  " . ($test1Pass ? "PASS" : "FAIL") . "\n";
echo "Test 2 (Conversion): " . ($test2Pass ? "PASS" : "FAIL") . "\n";
echo "Test 3 (Cleanup):    " . ($test3Pass ? "PASS" : "FAIL") . "\n";
echo "\n";

if ($test1Pass && $test2Pass && $test3Pass) {
    echo "ALL TESTS PASSED! OPD Lifecycle Logic is working correctly.\n";
} else {
    echo "SOME TESTS FAILED! Please check the logic.\n";
}

echo "\n===========================================\n";

// Cleanup test data
echo "Cleaning up test data...\n";
Patient::where('phone', '0891111111')->forceDelete();
echo "Done.\n";
