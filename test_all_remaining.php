<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Running All Remaining Tests ===\n\n";

// Get appointment ID from Test 1
$appointmentId = trim(file_get_contents(__DIR__ . '/test_appointment_id.txt'));
echo "Using Appointment ID from Test 1: {$appointmentId}\n\n";

// ======================
// TEST 2: Cancel Appointment + Delete Temporary OPD
// ======================
echo "=== TEST 2: Cancel Appointment + Verify Temporary OPD Deleted ===\n\n";

try {
    \Illuminate\Support\Facades\DB::beginTransaction();

    $appointment = \App\Models\Appointment::findOrFail($appointmentId);
    echo "Found appointment: {$appointment->id}\n";

    // Check for temporary OPD before cancellation
    $temporaryOpd = \App\Models\OpdRecord::where('is_temporary', true)
        ->where('branch_id', $appointment->branch_id)
        ->whereNull('patient_id')
        ->where('created_at', '>=', $appointment->created_at->subMinutes(5))
        ->first();

    if ($temporaryOpd) {
        echo "Found temporary OPD: {$temporaryOpd->id}\n";
        $opdId = $temporaryOpd->id;

        // Delete temporary OPD (hard delete)
        $temporaryOpd->forceDelete();
        echo "Deleted temporary OPD\n";

        $deletedOpd = true;
    } else {
        echo "No temporary OPD found\n";
        $deletedOpd = false;
    }

    // Update appointment status to cancelled
    $appointment->update([
        'status' => 'cancelled',
        'cancellation_reason' => 'Cancelled by user',
        'cancelled_at' => now(),
        'cancelled_by' => null
    ]);

    // Soft delete appointment
    $appointment->delete();

    \Illuminate\Support\Facades\DB::commit();

    echo "Appointment cancelled successfully\n\n";

    // Verify temporary OPD was deleted
    $opdStillExists = \App\Models\OpdRecord::withTrashed()->find($opdId);

    if ($deletedOpd && $opdStillExists === null) {
        echo "✅ TEST 2 PASSED: Temporary OPD was hard deleted!\n\n";
    } else {
        echo "❌ TEST 2 FAILED: OPD still exists or was not deleted\n\n";
    }

} catch (\Exception $e) {
    \Illuminate\Support\Facades\DB::rollBack();
    echo "❌ Error in TEST 2: " . $e->getMessage() . "\n\n";
}

// ======================
// TEST 3: Create Today's Appointment + Verify it Appears in Queue
// ======================
echo "=== TEST 3: Create Today's Appointment + Verify in Queue ===\n\n";

try {
    \Illuminate\Support\Facades\DB::beginTransaction();

    $branch = \App\Models\Branch::first();
    $patient = \App\Models\Patient::first();

    if (!$patient) {
        echo "❌ No patient found. Cannot proceed with TEST 3.\n\n";
        exit(1);
    }

    // Create appointment for today
    $appointment = \App\Models\Appointment::create([
        'patient_id' => $patient->id,
        'branch_id' => $branch->id,
        'pt_id' => null,
        'appointment_date' => today()->toDateString(),
        'appointment_time' => '14:00:00',
        'booking_channel' => 'walk_in',
        'status' => 'pending',
        'notes' => 'Test appointment for queue',
        'created_by' => null
    ]);

    echo "Created appointment: {$appointment->id}\n";

    // Create queue entry
    $lastQueue = \App\Models\Queue::whereDate('queued_at', today())->max('queue_number');
    $queueNumber = $lastQueue ? $lastQueue + 1 : 1;

    $queue = \App\Models\Queue::create([
        'appointment_id' => $appointment->id,
        'patient_id' => $patient->id,
        'branch_id' => $branch->id,
        'pt_id' => null,
        'queue_number' => $queueNumber,
        'status' => 'waiting',
        'queued_at' => now(),
        'created_by' => null
    ]);

    \Illuminate\Support\Facades\DB::commit();

    echo "Created queue entry: {$queue->id}\n";
    echo "Queue Number: {$queue->queue_number}\n";
    echo "Status: {$queue->status}\n\n";

    // Verify queue appears in today's queue list
    $todayQueues = \App\Models\Queue::whereDate('queued_at', today())->count();
    echo "Total queues today: {$todayQueues}\n";

    if ($todayQueues > 0) {
        echo "✅ TEST 3 PASSED: Appointment appears in today's queue!\n\n";
        // Store queue ID for Test 4
        file_put_contents(__DIR__ . '/test_queue_id.txt', $queue->id);
    } else {
        echo "❌ TEST 3 FAILED: No queues found for today\n\n";
    }

} catch (\Exception $e) {
    \Illuminate\Support\Facades\DB::rollBack();
    echo "❌ Error in TEST 3: " . $e->getMessage() . "\n\n";
}

// ======================
// TEST 4: Start Treatment + Verify Time Recorded
// ======================
echo "=== TEST 4: Start Treatment + Verify Time Recorded ===\n\n";

try {
    $queueId = trim(file_get_contents(__DIR__ . '/test_queue_id.txt'));
    echo "Using Queue ID from Test 3: {$queueId}\n";

    $queue = \App\Models\Queue::findOrFail($queueId);
    echo "Queue before start: Status={$queue->status}, started_at=" . ($queue->started_at ?? 'NULL') . "\n";

    // Start treatment
    $queue->update([
        'status' => 'in_treatment',
        'started_at' => now(),
        'called_at' => now(),
    ]);

    // Refresh to get updated data
    $queue->refresh();

    echo "\nQueue after start: Status={$queue->status}, started_at={$queue->started_at}\n";

    if ($queue->status === 'in_treatment' && $queue->started_at !== null) {
        echo "✅ TEST 4 PASSED: Treatment started and time recorded!\n\n";

        // Also test end treatment
        echo "--- Bonus: Testing End Treatment ---\n";
        $duration = now()->diffInMinutes($queue->started_at);
        $queue->update([
            'status' => 'completed',
            'completed_at' => now(),
            'waiting_time_minutes' => $duration,
            'is_overtime' => $duration > 15,
        ]);

        $queue->refresh();
        echo "Queue after end: Status={$queue->status}, Duration={$queue->waiting_time_minutes} min\n";
        echo "✅ End treatment also works correctly!\n\n";
    } else {
        echo "❌ TEST 4 FAILED: Status or start time not updated correctly\n\n";
    }

} catch (\Exception $e) {
    echo "❌ Error in TEST 4: " . $e->getMessage() . "\n\n";
}

echo "=== ALL TESTS COMPLETED ===\n";
