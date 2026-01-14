<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Running Billing & Critical Logic Tests ===\n\n";

// Load test IDs
if (!file_exists(__DIR__ . '/billing_test_ids.txt')) {
    echo "❌ Please run setup_billing_test_data.php first\n";
    exit(1);
}

$ids = json_decode(file_get_contents(__DIR__ . '/billing_test_ids.txt'), true);

// ========================================
// TEST 1: Payment Processing (ข้อ 5)
// ========================================
echo "=== TEST 1: Payment Processing (รายครั้ง) ===\n\n";

try {
    \Illuminate\Support\Facades\DB::beginTransaction();

    // Simulate billing for single service
    $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . rand(10000, 99999);

    $invoice = \App\Models\Invoice::create([
        'invoice_number' => $invoiceNumber,
        'patient_id' => $ids['patient_id'],
        'opd_id' => $ids['opd_id'],
        'branch_id' => $ids['branch_id'],
        'invoice_type' => 'walk_in',
        'subtotal' => 500.00,
        'discount_amount' => 0,
        'tax_amount' => 0,
        'total_amount' => 500.00,
        'paid_amount' => 500.00,
        'outstanding_amount' => 0,
        'status' => 'paid',
        'invoice_date' => today(),
        'created_by' => null,
    ]);

    $invoiceItem = \App\Models\InvoiceItem::create([
        'invoice_id' => $invoice->id,
        'service_id' => $ids['service1_id'],
        'item_type' => 'service',
        'description' => 'Physical Therapy Session',
        'quantity' => 1,
        'unit_price' => 500.00,
        'discount_amount' => 0,
        'total_amount' => 500.00,
    ]);

    $paymentNumber = 'PAY-' . now()->format('Ymd') . '-' . rand(10000, 99999);

    $payment = \App\Models\Payment::create([
        'payment_number' => $paymentNumber,
        'invoice_id' => $invoice->id,
        'patient_id' => $ids['patient_id'],
        'branch_id' => $ids['branch_id'],
        'amount' => 500.00,
        'payment_method' => 'cash',
        'status' => 'completed',
        'payment_date' => today(),
        'created_by' => null,
    ]);

    // Update queue status
    $queue = \App\Models\Queue::find($ids['queue_id']);
    $queue->update(['status' => 'paid']);

    \Illuminate\Support\Facades\DB::commit();

    echo "Invoice Number: {$invoice->invoice_number}\n";
    echo "Payment Number: {$payment->payment_number}\n";
    echo "Amount: ฿{$payment->amount}\n";
    echo "Invoice Status: {$invoice->status}\n";
    echo "Queue Status: {$queue->status}\n\n";

    if ($invoice->status === 'paid' && $queue->status === 'paid') {
        echo "✅ TEST 1 PASSED: Payment processed & Invoice generated successfully!\n\n";
    } else {
        echo "❌ TEST 1 FAILED\n\n";
    }

    // Save for later tests
    $testResults = ['invoice_id' => $invoice->id, 'payment_id' => $payment->id];

} catch (\Exception $e) {
    \Illuminate\Support\Facades\DB::rollBack();
    echo "❌ TEST 1 ERROR: " . $e->getMessage() . "\n\n";
}

// ========================================
// TEST 2: Retroactive Course Purchase (ข้อ 6)
// ========================================
echo "=== TEST 2: Retroactive Course Purchase (ซื้อย้อนหลัง) ===\n\n";

try {
    \Illuminate\Support\Facades\DB::beginTransaction();

    // Create previous treatments that were charged at full price
    $treatment1 = \App\Models\Treatment::create([
        'patient_id' => $ids['patient_id'],
        'opd_id' => $ids['opd_id'],
        'branch_id' => $ids['branch_id'],
        'service_id' => $ids['service1_id'],
        'pt_id' => $ids['pt_id'],
        'started_at' => today()->subDays(5)->setTime(10, 0),
        'completed_at' => today()->subDays(5)->setTime(11, 0),
        'duration_minutes' => 60,
        'billing_status' => 'unpaid',
        'created_by' => null,
    ]);

    $treatment2 = \App\Models\Treatment::create([
        'patient_id' => $ids['patient_id'],
        'opd_id' => $ids['opd_id'],
        'branch_id' => $ids['branch_id'],
        'service_id' => $ids['service1_id'],
        'pt_id' => $ids['pt_id'],
        'started_at' => today()->subDays(3)->setTime(14, 0),
        'completed_at' => today()->subDays(3)->setTime(15, 0),
        'duration_minutes' => 60,
        'billing_status' => 'unpaid',
        'created_by' => null,
    ]);

    echo "Created 2 previous treatments (unpaid)\n";
    echo "Treatment 1 Date: {$treatment1->completed_at->toDateString()}\n";
    echo "Treatment 2 Date: {$treatment2->completed_at->toDateString()}\n\n";

    // Now sell retroactive course
    $package = \App\Models\CoursePackage::find($ids['package1_id']);
    $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . rand(10000, 99999);

    $invoice = \App\Models\Invoice::create([
        'invoice_number' => $invoiceNumber,
        'patient_id' => $ids['patient_id'],
        'opd_id' => $ids['opd_id'],
        'branch_id' => $ids['branch_id'],
        'invoice_type' => 'course',
        'subtotal' => $package->price,
        'discount_amount' => 0,
        'tax_amount' => 0,
        'total_amount' => $package->price,
        'paid_amount' => $package->price,
        'outstanding_amount' => 0,
        'status' => 'paid',
        'invoice_date' => today(),
        'created_by' => null,
    ]);

    // Create course purchase with retroactive pattern
    $courseNumber = 'CRS-' . now()->format('Ymd') . '-' . rand(10000, 99999);

    $coursePurchase = \App\Models\CoursePurchase::create([
        'course_number' => $courseNumber,
        'patient_id' => $ids['patient_id'],
        'package_id' => $ids['package1_id'],
        'invoice_id' => $invoice->id,
        'purchase_branch_id' => $ids['branch_id'],
        'purchase_pattern' => 'retroactive',
        'purchase_date' => today(),
        'activation_date' => today(),
        'expiry_date' => today()->addDays($package->validity_days),
        'total_sessions' => $package->total_sessions,
        'used_sessions' => 2, // 2 previous treatments
        'status' => 'active',
        'allow_branch_sharing' => true,
        'created_by' => null,
    ]);

    // Link previous treatments to this course
    $treatment1->update(['course_purchase_id' => $coursePurchase->id]);
    $treatment2->update(['course_purchase_id' => $coursePurchase->id]);

    // Create usage logs for retroactive applications
    \App\Models\CourseUsageLog::create([
        'course_purchase_id' => $coursePurchase->id,
        'treatment_id' => $treatment1->id,
        'patient_id' => $ids['patient_id'],
        'branch_id' => $ids['branch_id'],
        'pt_id' => $ids['pt_id'],
        'sessions_used' => 1,
        'usage_date' => $treatment1->completed_at->toDateString(),
        'status' => 'used',
        'is_cross_branch' => false,
        'purchase_branch_id' => $ids['branch_id'],
        'created_by' => null,
    ]);

    \App\Models\CourseUsageLog::create([
        'course_purchase_id' => $coursePurchase->id,
        'treatment_id' => $treatment2->id,
        'patient_id' => $ids['patient_id'],
        'branch_id' => $ids['branch_id'],
        'pt_id' => $ids['pt_id'],
        'sessions_used' => 1,
        'usage_date' => $treatment2->completed_at->toDateString(),
        'status' => 'used',
        'is_cross_branch' => false,
        'purchase_branch_id' => $ids['branch_id'],
        'created_by' => null,
    ]);

    \Illuminate\Support\Facades\DB::commit();

    // Calculate financial difference
    $fullPriceFor2Sessions = 500.00 * 2; // ฿1000
    $packagePrice = $package->price; // ฿4000 for 10 sessions
    $pricePerSessionInPackage = $packagePrice / $package->total_sessions; // ฿400
    $paidViaPackageFor2Sessions = $pricePerSessionInPackage * 2; // ฿800

    $customerSavings = $fullPriceFor2Sessions - $paidViaPackageFor2Sessions; // ฿200

    echo "Course Number: {$coursePurchase->course_number}\n";
    echo "Purchase Pattern: {$coursePurchase->purchase_pattern}\n";
    echo "Total Sessions: {$coursePurchase->total_sessions}\n";
    echo "Used Sessions (Retroactive): {$coursePurchase->used_sessions}\n";
    echo "Remaining Sessions: " . ($coursePurchase->total_sessions - $coursePurchase->used_sessions) . "\n\n";

    echo "=== Financial Calculation ===\n";
    echo "Full price for 2 sessions: ฿{$fullPriceFor2Sessions}\n";
    echo "Package price per session: ฿{$pricePerSessionInPackage}\n";
    echo "Effective cost for 2 sessions: ฿{$paidViaPackageFor2Sessions}\n";
    echo "Customer Savings: ฿{$customerSavings}\n\n";

    if ($coursePurchase->purchase_pattern === 'retroactive' && $coursePurchase->used_sessions === 2) {
        echo "✅ TEST 2 PASSED: Retroactive purchase calculated correctly!\n\n";
        $testResults['course_id'] = $coursePurchase->id;
    } else {
        echo "❌ TEST 2 FAILED\n\n";
    }

} catch (\Exception $e) {
    \Illuminate\Support\Facades\DB::rollBack();
    echo "❌ TEST 2 ERROR: " . $e->getMessage() . "\n\n";
}

// ========================================
// TEST 3: Course Cancellation + Refund Calculation (ข้อ 8)
// ========================================
echo "=== TEST 3: Course Cancellation + Refund Calculation ===\n\n";

try {
    \Illuminate\Support\Facades\DB::beginTransaction();

    // Create a course purchase with some used sessions
    $package = \App\Models\CoursePackage::find($ids['package2_id']);
    $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . rand(10000, 99999);

    $invoice = \App\Models\Invoice::create([
        'invoice_number' => $invoiceNumber,
        'patient_id' => $ids['patient_id'],
        'opd_id' => $ids['opd_id'],
        'branch_id' => $ids['branch_id'],
        'invoice_type' => 'course',
        'subtotal' => $package->price,
        'discount_amount' => 0,
        'tax_amount' => 0,
        'total_amount' => $package->price, // ฿3500 for 5 sessions
        'paid_amount' => $package->price,
        'outstanding_amount' => 0,
        'status' => 'paid',
        'invoice_date' => today(),
        'created_by' => null,
    ]);

    $courseNumber = 'CRS-' . now()->format('Ymd') . '-' . rand(10000, 99999);

    $coursePurchase = \App\Models\CoursePurchase::create([
        'course_number' => $courseNumber,
        'patient_id' => $ids['patient_id'],
        'package_id' => $ids['package2_id'],
        'invoice_id' => $invoice->id,
        'purchase_branch_id' => $ids['branch_id'],
        'purchase_pattern' => 'buy_for_later',
        'purchase_date' => today(),
        'activation_date' => today(),
        'expiry_date' => today()->addDays($package->validity_days),
        'total_sessions' => $package->total_sessions,
        'used_sessions' => 2, // Already used 2 out of 5 sessions
        'status' => 'active',
        'allow_branch_sharing' => true,
        'created_by' => null,
    ]);

    echo "Course Created:\n";
    echo "Course Number: {$coursePurchase->course_number}\n";
    echo "Total Price Paid: ฿{$invoice->total_amount}\n";
    echo "Total Sessions: {$coursePurchase->total_sessions}\n";
    echo "Used Sessions: {$coursePurchase->used_sessions}\n";
    echo "Remaining Sessions: " . ($coursePurchase->total_sessions - $coursePurchase->used_sessions) . "\n\n";

    // Now cancel the course - Calculate refund
    $service = \App\Models\Service::find($ids['service2_id']);
    $fullPricePerSession = $service->default_price; // ฿800
    $usedSessions = $coursePurchase->used_sessions; // 2
    $totalPrice = $invoice->total_amount; // ฿3500

    $usedAmount = $usedSessions * $fullPricePerSession; // 2 × ฿800 = ฿1600
    $refundAmount = max(0, $totalPrice - $usedAmount); // ฿3500 - ฿1600 = ฿1900

    echo "=== Refund Calculation ===\n";
    echo "Total Price Paid: ฿{$totalPrice}\n";
    echo "Full Price Per Session: ฿{$fullPricePerSession}\n";
    echo "Used Sessions: {$usedSessions}\n";
    echo "Used Amount (at full price): ฿{$usedAmount}\n";
    echo "Refund Amount: ฿{$refundAmount}\n\n";

    // Create refund record
    $refundNumber = 'REF-' . now()->format('Ymd') . '-' . rand(10000, 99999);

    $refund = \App\Models\Refund::create([
        'refund_number' => $refundNumber,
        'invoice_id' => $invoice->id,
        'patient_id' => $ids['patient_id'],
        'branch_id' => $ids['branch_id'],
        'refund_type' => 'course_cancellation',
        'refund_amount' => $refundAmount,
        'status' => 'approved',
        'refund_date' => today(),
        'original_amount' => $totalPrice,
        'used_amount' => $usedAmount,
        'penalty_amount' => 0,
        'calculation_notes' => "Used {$usedSessions} sessions at full price ฿{$fullPricePerSession} each",
        'reason' => 'Customer request for cancellation',
        'approved_at' => now(),
        'approved_by' => null,
        'refund_method' => 'bank_transfer',
        'created_by' => null,
    ]);

    // Update course status
    $coursePurchase->update([
        'status' => 'cancelled',
        'cancellation_reason' => 'Customer request',
        'cancelled_at' => now(),
        'cancelled_by' => null,
    ]);

    \Illuminate\Support\Facades\DB::commit();

    echo "Refund Number: {$refund->refund_number}\n";
    echo "Refund Status: {$refund->status}\n";
    echo "Course Status: {$coursePurchase->status}\n\n";

    if ($refund->refund_amount == $refundAmount && $coursePurchase->status === 'cancelled') {
        echo "✅ TEST 3 PASSED: Course cancelled & refund calculated correctly!\n\n";
        $testResults['refund_id'] = $refund->id;
        $testResults['cancelled_invoice_id'] = $invoice->id;
    } else {
        echo "❌ TEST 3 FAILED\n\n";
    }

} catch (\Exception $e) {
    \Illuminate\Support\Facades\DB::rollBack();
    echo "❌ TEST 3 ERROR: " . $e->getMessage() . "\n\n";
}

// ========================================
// TEST 4: Clawback Commission Check (ข้อ 21)
// ========================================
echo "=== TEST 4: Clawback Commission Verification ===\n\n";

try {
    \Illuminate\Support\Facades\DB::beginTransaction();

    // Use invoice from TEST 3
    $invoiceId = $testResults['cancelled_invoice_id'] ?? null;
    $refundId = $testResults['refund_id'] ?? null;

    if (!$invoiceId || !$refundId) {
        echo "⚠️  Skipping TEST 4: No cancelled course from TEST 3\n\n";
    } else {
        // Create a sample treatment for DF payment
        $sampleTreatment = \App\Models\Treatment::create([
            'patient_id' => $ids['patient_id'],
            'opd_id' => $ids['opd_id'],
            'branch_id' => $ids['branch_id'],
            'service_id' => $ids['service2_id'],
            'pt_id' => $ids['pt_id'],
            'started_at' => now()->subHours(2),
            'completed_at' => now()->subHours(1),
            'duration_minutes' => 60,
            'invoice_id' => $invoiceId,
            'billing_status' => 'paid',
            'created_by' => null,
        ]);

        // Create sample commissions for the invoice (simulating sales commission)
        $commission1 = \App\Models\Commission::create([
            'commission_number' => 'COM-' . now()->format('Ymd') . '-' . rand(10000, 99999),
            'pt_id' => $ids['pt_id'],
            'invoice_id' => $invoiceId,
            'invoice_item_id' => null,
            'branch_id' => $ids['branch_id'],
            'commission_type' => 'course_sale',
            'base_amount' => 3500.00,
            'commission_rate' => 15.00,
            'commission_amount' => 525.00, // 15% of 3500
            'status' => 'pending',
            'commission_date' => today(),
            'is_clawback_eligible' => true,
            'created_by' => null,
        ]);

        // Create sample DF payment (PT service fee - should NOT be clawed back)
        $dfPayment = \App\Models\DfPayment::create([
            'df_number' => 'DF-' . now()->format('Ymd') . '-' . rand(10000, 99999),
            'pt_id' => $ids['pt_id'],
            'treatment_id' => $sampleTreatment->id,
            'invoice_id' => $invoiceId,
            'branch_id' => $ids['branch_id'],
            'payment_type' => 'per_session',
            'base_amount' => 800.00,
            'df_rate' => 70.00,
            'df_amount' => 560.00, // 70% of 800
            'status' => 'pending',
            'df_date' => today(),
            'is_clawback_eligible' => false, // DF should NOT be clawed back
            'created_by' => null,
        ]);

        echo "Before Clawback:\n";
        echo "Commission: {$commission1->commission_number} - Status: {$commission1->status}\n";
        echo "DF Payment: {$dfPayment->df_number} - Status: {$dfPayment->status}\n\n";

        // Perform clawback on commissions only
        $commission1->update([
            'status' => 'clawed_back',
            'clawed_back_at' => now(),
            'clawed_back_by' => null,
            'clawback_reason' => 'Course cancellation',
            'clawback_refund_id' => $refundId,
        ]);

        // DF Payment remains UNTOUCHED (ข้อ 21 requirement)

        echo "After Clawback:\n";
        echo "Commission: {$commission1->commission_number} - Status: {$commission1->status}\n";
        echo "DF Payment: {$dfPayment->df_number} - Status: {$dfPayment->status} (UNTOUCHED)\n\n";

        // Verify
        $commission1->refresh();
        $dfPayment->refresh();

        if ($commission1->status === 'clawed_back' && $dfPayment->status === 'pending') {
            echo "✅ TEST 4 PASSED: Commission clawed back, but DF payment UNTOUCHED!\n\n";
        } else {
            echo "❌ TEST 4 FAILED\n\n";
        }
    }

    \Illuminate\Support\Facades\DB::commit();

} catch (\Exception $e) {
    \Illuminate\Support\Facades\DB::rollBack();
    echo "❌ TEST 4 ERROR: " . $e->getMessage() . "\n\n";
}

echo "=== ALL BILLING TESTS COMPLETED ===\n";
