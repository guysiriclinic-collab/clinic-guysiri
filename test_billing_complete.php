<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Phase 2.4 Part 3: Billing & Critical Logic Tests ===\n\n";

try {
    \Illuminate\Support\Facades\DB::beginTransaction();

    // Setup test environment
    $branch = \App\Models\Branch::first();
    $patient = \App\Models\Patient::first();

    // Get any user for PT testing (simplified for test)
    $ptUser = \App\Models\User::first();
    if (!$ptUser) {
        echo "❌ ERROR: No users found. Run seeders first.\n";
        exit(1);
    }

    if (!$branch || !$patient) {
        echo "❌ ERROR: Missing branch or patient. Run seeders first.\n";
        exit(1);
    }

    // Create test service and course package
    $service = \App\Models\Service::firstOrCreate(
        ['code' => 'TEST_SERVICE'],
        [
            'name' => 'Test Service',
            'category' => 'treatment',
            'default_price' => 1000,
            'is_active' => true
        ]
    );

    $package = \App\Models\CoursePackage::firstOrCreate(
        ['code' => 'TEST_COURSE'],
        [
            'name' => 'Test Course Package',
            'description' => 'Test course for 5 sessions',
            'price' => 4000, // 4000 for 5 sessions (discounted from 5000)
            'total_sessions' => 5,
            'validity_days' => 90,
            'is_active' => true,
            'service_id' => $service->id,
            'commission_rate' => 10.00,
            'per_session_commission_rate' => 5.00,
            'df_rate' => 15.00,
            'allow_buy_and_use' => true,
            'allow_buy_for_later' => true,
            'allow_retroactive' => true,
        ]
    );

    echo "📋 Test Environment:\n";
    echo "   - Branch: {$branch->name}\n";
    echo "   - Patient: {$patient->name}\n";
    echo "   - Service: {$service->name} (฿{$service->default_price})\n";
    echo "   - Package: {$package->name} (฿{$package->price}, {$package->total_sessions} sessions)\n\n";

    // ========================================
    // TEST 1: Pay-per-Session → Generate Invoice (ข้อ 5)
    // ========================================
    echo "=== TEST 1: Pay-per-Session (ข้อ 5) ===\n";

    // Create queue for billing
    $queue = \App\Models\Queue::create([
        'patient_id' => $patient->id,
        'branch_id' => $branch->id,
        'queue_number' => 999,
        'status' => 'completed',
        'queued_at' => now(),
        'started_at' => now()->subHour(),
        'completed_at' => now(),
    ]);

    // Create OPD
    $opd = \App\Models\OpdRecord::firstOrCreate(
        ['patient_id' => $patient->id, 'branch_id' => $branch->id, 'status' => 'active'],
        [
            'opd_number' => 'OPD-TEST-' . rand(1000, 9999),
            'is_temporary' => false,
        ]
    );

    // Process payment
    $controller = new \App\Http\Controllers\BillingController();
    $request = \Illuminate\Http\Request::create('/billing/process-payment', 'POST', [
        'queue_id' => $queue->id,
        'patient_id' => $patient->id,
        'opd_id' => $opd->id,
        'items' => [
            [
                'type' => 'service',
                'id' => $service->id,
                'name' => $service->name,
                'quantity' => 1,
                'price' => $service->default_price,
                'total' => $service->default_price,
            ]
        ],
        'subtotal' => $service->default_price,
        'discount' => 0,
        'total' => $service->default_price,
        'payment_method' => 'cash',
        'amount_paid' => $service->default_price,
    ]);

    $response = $controller->processPayment($request);
    $data = json_decode($response->getContent(), true);

    if ($data['success']) {
        // Verify invoice created
        $invoice = \App\Models\Invoice::where('invoice_number', $data['invoice_number'])->first();
        $payment = \App\Models\Payment::where('invoice_id', $invoice->id)->first();
        $queue->refresh();

        if ($invoice && $payment && $queue->status === 'paid') {
            echo "✅ TEST 1 PASSED: Pay-per-session invoice generated!\n";
            echo "   - Invoice Number: {$invoice->invoice_number}\n";
            echo "   - Total Amount: ฿{$invoice->total_amount}\n";
            echo "   - Payment Method: {$payment->payment_method}\n";
            echo "   - Queue Status: {$queue->status} (should be 'paid')\n";
            echo "   - Invoice Status: {$invoice->status}\n\n";
        } else {
            echo "❌ TEST 1 FAILED: Invoice or payment not created properly\n\n";
            \Illuminate\Support\Facades\DB::rollBack();
            exit(1);
        }
    } else {
        echo "❌ TEST 1 FAILED: {$data['message']}\n\n";
        \Illuminate\Support\Facades\DB::rollBack();
        exit(1);
    }

    // ========================================
    // TEST 2: Buy Course Retroactive (ข้อ 6)
    // ========================================
    echo "=== TEST 2: Buy Course Retroactive (ข้อ 6) ===\n";

    // Create 2 past treatments (unpaid)
    $treatment1 = \App\Models\Treatment::create([
        'opd_id' => $opd->id,
        'patient_id' => $patient->id,
        'branch_id' => $branch->id,
        'pt_id' => $ptUser->id, // Added PT
        'service_id' => $service->id,
        'started_at' => now()->subDays(5),
        'completed_at' => now()->subDays(5),
        'duration_minutes' => 30,
        'billing_status' => 'pending',
    ]);

    $treatment2 = \App\Models\Treatment::create([
        'opd_id' => $opd->id,
        'patient_id' => $patient->id,
        'branch_id' => $branch->id,
        'pt_id' => $ptUser->id, // Added PT
        'service_id' => $service->id,
        'started_at' => now()->subDays(3),
        'completed_at' => now()->subDays(3),
        'duration_minutes' => 30,
        'billing_status' => 'pending',
    ]);

    // Buy course retroactive
    $queue2 = \App\Models\Queue::create([
        'patient_id' => $patient->id,
        'branch_id' => $branch->id,
        'queue_number' => 1000,
        'status' => 'completed',
        'queued_at' => now(),
        'completed_at' => now(),
    ]);

    $request2 = \Illuminate\Http\Request::create('/billing/process-payment', 'POST', [
        'queue_id' => $queue2->id,
        'patient_id' => $patient->id,
        'opd_id' => $opd->id,
        'items' => [
            [
                'type' => 'course',
                'id' => $package->id,
                'name' => $package->name,
                'quantity' => 1,
                'price' => $package->price,
                'total' => $package->price,
                'pattern' => 'retroactive', // KEY: Retroactive pattern
            ]
        ],
        'subtotal' => $package->price,
        'discount' => 0,
        'total' => $package->price,
        'payment_method' => 'cash',
        'amount_paid' => $package->price,
    ]);

    $response2 = $controller->processPayment($request2);
    $data2 = json_decode($response2->getContent(), true);

    if ($data2['success']) {
        // Verify retroactive course purchase
        $invoice2 = \App\Models\Invoice::where('invoice_number', $data2['invoice_number'])->first();
        $coursePurchase = \App\Models\CoursePurchase::where('invoice_id', $invoice2->id)->first();
        $usageLogs = \App\Models\CourseUsageLog::where('course_purchase_id', $coursePurchase->id)->get();

        if ($coursePurchase && $coursePurchase->purchase_pattern === 'retroactive' && $coursePurchase->used_sessions === 2) {
            echo "✅ TEST 2 PASSED: Retroactive course purchase successful!\n";
            echo "   - Course Number: {$coursePurchase->course_number}\n";
            echo "   - Purchase Pattern: {$coursePurchase->purchase_pattern}\n";
            echo "   - Total Sessions: {$coursePurchase->total_sessions}\n";
            echo "   - Used Sessions: {$coursePurchase->used_sessions} (should be 2 from past treatments)\n";
            echo "   - Remaining Sessions: " . ($coursePurchase->total_sessions - $coursePurchase->used_sessions) . "\n";
            echo "   - Usage Logs Created: {$usageLogs->count()}\n";
            echo "   - Past treatments linked: YES\n\n";

            $testCoursePurchaseId = $coursePurchase->id; // Store for TEST 3
        } else {
            echo "❌ TEST 2 FAILED: Retroactive logic not working\n";
            echo "   - Expected used_sessions: 2\n";
            echo "   - Actual used_sessions: {$coursePurchase->used_sessions}\n\n";
            \Illuminate\Support\Facades\DB::rollBack();
            exit(1);
        }
    } else {
        echo "❌ TEST 2 FAILED: {$data2['message']}\n\n";
        \Illuminate\Support\Facades\DB::rollBack();
        exit(1);
    }

    // ========================================
    // TEST 3: Cancel Course → Calculate Refund (ข้อ 8)
    // ========================================
    echo "=== TEST 3: Cancel Course + Refund Calculation (ข้อ 8) ===\n";

    // Create commissions for the course purchase (simulate sales commission)
    $commission = \App\Models\Commission::create([
        'commission_number' => 'COM-TEST-' . rand(1000, 9999),
        'pt_id' => $ptUser->id, // Use test PT for commission
        'invoice_id' => $invoice2->id,
        'branch_id' => $branch->id,
        'commission_type' => 'course_sale',
        'base_amount' => $package->price,
        'commission_rate' => $package->commission_rate,
        'commission_amount' => $package->price * ($package->commission_rate / 100),
        'status' => 'pending',
        'commission_date' => today(),
        'is_clawback_eligible' => true,
    ]);

    // Create DF payments (service fees for PT who did the treatments)
    $dfPayment1 = \App\Models\DfPayment::create([
        'df_number' => 'DF-TEST-' . rand(1000, 9999),
        'pt_id' => $ptUser->id, // PT who did treatment
        'treatment_id' => $treatment1->id,
        'invoice_id' => $invoice2->id,
        'branch_id' => $branch->id,
        'payment_type' => 'per_session',
        'base_amount' => $service->default_price,
        'df_rate' => $package->df_rate,
        'df_amount' => $service->default_price * ($package->df_rate / 100),
        'status' => 'pending',
        'df_date' => today(),
        'is_clawback_eligible' => false, // KEY: DF not eligible for clawback
    ]);

    $dfPayment2 = \App\Models\DfPayment::create([
        'df_number' => 'DF-TEST-' . rand(1000, 9999),
        'pt_id' => $ptUser->id, // PT who did treatment
        'treatment_id' => $treatment2->id,
        'invoice_id' => $invoice2->id,
        'branch_id' => $branch->id,
        'payment_type' => 'per_session',
        'base_amount' => $service->default_price,
        'df_rate' => $package->df_rate,
        'df_amount' => $service->default_price * ($package->df_rate / 100),
        'status' => 'pending',
        'df_date' => today(),
        'is_clawback_eligible' => false,
    ]);

    echo "   Pre-cancellation state:\n";
    echo "   - Commission Amount: ฿{$commission->commission_amount} (status: {$commission->status})\n";
    echo "   - DF Payment 1: ฿{$dfPayment1->df_amount} (status: {$dfPayment1->status})\n";
    echo "   - DF Payment 2: ฿{$dfPayment2->df_amount} (status: {$dfPayment2->status})\n\n";

    // Cancel course
    $request3 = \Illuminate\Http\Request::create("/billing/cancel-course/{$testCoursePurchaseId}", 'POST', [
        'reason' => 'Customer request',
        'course_purchase_id' => $testCoursePurchaseId,
    ]);

    $response3 = $controller->storeCancellation($request3);
    $data3 = json_decode($response3->getContent(), true);

    if ($data3['success']) {
        // Verify refund calculation
        $refundAmount = $data3['refund_amount'];
        $usedSessions = $data3['used_sessions'];
        $usedAmount = $data3['used_amount'];

        // Expected: Refund = 4000 - (2 × 1000) = 2000
        $expectedRefund = $package->price - ($usedSessions * $service->default_price);

        if (abs($refundAmount - $expectedRefund) < 0.01) {
            echo "✅ TEST 3 PASSED: Refund calculation correct!\n";
            echo "   - Course Price: ฿{$package->price}\n";
            echo "   - Used Sessions: {$usedSessions} × ฿{$service->default_price} (full price) = ฿{$usedAmount}\n";
            echo "   - Refund Amount: ฿{$refundAmount}\n";
            echo "   - Calculation: ฿{$package->price} - ฿{$usedAmount} = ฿{$refundAmount}\n";
            echo "   - Formula: Total Price - (Used × Full Price) ✓\n\n";
        } else {
            echo "❌ TEST 3 FAILED: Refund calculation incorrect\n";
            echo "   - Expected Refund: ฿{$expectedRefund}\n";
            echo "   - Actual Refund: ฿{$refundAmount}\n\n";
            \Illuminate\Support\Facades\DB::rollBack();
            exit(1);
        }
    } else {
        echo "❌ TEST 3 FAILED: {$data3['message']}\n\n";
        \Illuminate\Support\Facades\DB::rollBack();
        exit(1);
    }

    // ========================================
    // TEST 4: Clawback Commission BUT NOT DF (ข้อ 21)
    // ========================================
    echo "=== TEST 4: Clawback Commission, Keep DF (ข้อ 21) ===\n";

    // Refresh records
    $commission->refresh();
    $dfPayment1->refresh();
    $dfPayment2->refresh();

    // Check commission was clawed back
    $commissionClawedBack = ($commission->status === 'clawed_back' && $commission->clawed_back_at !== null);

    // Check DF payments remain untouched
    $dfPayment1Untouched = ($dfPayment1->status === 'pending'); // Should still be pending, not clawed back
    $dfPayment2Untouched = ($dfPayment2->status === 'pending');

    if ($commissionClawedBack && $dfPayment1Untouched && $dfPayment2Untouched) {
        echo "✅ TEST 4 PASSED: Clawback logic correct!\n";
        echo "   ✅ Commission Status: {$commission->status} (clawed back)\n";
        echo "   ✅ Commission Clawed Back At: {$commission->clawed_back_at->format('Y-m-d H:i:s')}\n";
        echo "   ✅ Commission Clawback Reason: {$commission->clawback_reason}\n";
        echo "   ✅ DF Payment 1 Status: {$dfPayment1->status} (untouched - still pending)\n";
        echo "   ✅ DF Payment 2 Status: {$dfPayment2->status} (untouched - still pending)\n";
        echo "   ✅ DF Amount 1: ฿{$dfPayment1->df_amount} (ไม่ถูกหัก)\n";
        echo "   ✅ DF Amount 2: ฿{$dfPayment2->df_amount} (ไม่ถูกหัก)\n";
        echo "   ✅ Clawback Logic: หักค่าคอม แต่ไม่หักค่ามือ PT ✓\n\n";
    } else {
        echo "❌ TEST 4 FAILED: Clawback logic incorrect\n";
        echo "   - Commission clawed back: " . ($commissionClawedBack ? 'YES' : 'NO') . "\n";
        echo "   - DF Payment 1 untouched: " . ($dfPayment1Untouched ? 'YES' : 'NO') . " (status: {$dfPayment1->status})\n";
        echo "   - DF Payment 2 untouched: " . ($dfPayment2Untouched ? 'YES' : 'NO') . " (status: {$dfPayment2->status})\n\n";
        \Illuminate\Support\Facades\DB::rollBack();
        exit(1);
    }

    \Illuminate\Support\Facades\DB::commit();

    echo "=== ALL TESTS PASSED! ===\n";
    echo "✅ TEST 1: Pay-per-Session → Invoice Generated - PASSED\n";
    echo "✅ TEST 2: Retroactive Course Purchase - PASSED\n";
    echo "✅ TEST 3: Cancel Course → Refund Calculation - PASSED\n";
    echo "✅ TEST 4: Clawback Commission, Keep DF - PASSED\n\n";

    echo "Phase 2.4 Part 3 - Billing & Critical Logic is COMPLETE!\n";

} catch (\Exception $e) {
    \Illuminate\Support\Facades\DB::rollBack();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    exit(1);
}
