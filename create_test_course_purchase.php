<?php
/**
 * Create a test course purchase for an existing patient
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Patient;
use App\Models\CoursePurchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

echo "\n================================================\n";
echo "     CREATE TEST COURSE PURCHASE                \n";
echo "================================================\n\n";

// Get first existing patient
$patient = Patient::first();

if (!$patient) {
    echo "❌ No patients found in database!\n";
    exit;
}

echo "✅ Found patient: {$patient->name}\n";
echo "   • ID: {$patient->id}\n";
echo "   • Phone: {$patient->phone}\n\n";

// Get required IDs
$branch = DB::table('branches')->first();
$package = DB::table('course_packages')->first();

if (!$branch || !$package) {
    echo "❌ Missing required data (branches or packages)!\n";
    exit;
}

// Create an invoice for the purchase
$invoiceId = Str::uuid();
DB::table('invoices')->insert([
    'id' => $invoiceId,
    'invoice_number' => 'INV-TEST-' . date('YmdHis'),
    'patient_id' => $patient->id,
    'branch_id' => $branch->id,
    'invoice_type' => 'course',
    'invoice_date' => now(),
    'subtotal' => 5000,
    'discount_amount' => 0,
    'tax_amount' => 0,
    'total_amount' => 5000,
    'paid_amount' => 5000,
    'outstanding_amount' => 0,
    'status' => 'paid',
    'created_at' => now(),
    'updated_at' => now()
]);

echo "📦 Creating course purchase...\n";

// Create course purchase
$purchase = CoursePurchase::create([
    'id' => Str::uuid(),
    'course_number' => 'CRS-TEST-' . date('YmdHis'),
    'patient_id' => $patient->id,
    'package_id' => $package->id,
    'invoice_id' => $invoiceId,
    'purchase_branch_id' => $branch->id,
    'purchase_pattern' => 'full_payment',
    'purchase_date' => now(),
    'activation_date' => now(),
    'expiry_date' => now()->addMonths(3),
    'total_sessions' => 10,
    'used_sessions' => 2,
    'remaining_sessions' => 8,
    'status' => 'active',
    'allow_branch_sharing' => true,
    'created_by' => 'a061a15d-2458-4ed0-82e2-57334a080d0a'
]);

echo "\n✅ Course purchase created successfully!\n";
echo "   • Course Number: {$purchase->course_number}\n";
echo "   • Package: {$package->name}\n";
echo "   • Branch: {$branch->name}\n";
echo "   • Expiry Date: " . $purchase->expiry_date->format('Y-m-d') . "\n";
echo "   • Sessions: {$purchase->remaining_sessions}/{$purchase->total_sessions}\n";
echo "   • Status: {$purchase->status}\n\n";

// Verify the filter would find this patient
$hasActiveCourse = Patient::where('id', $patient->id)
    ->whereHas('coursePurchases', function($q) {
        $q->where('status', 'active')
          ->where('expiry_date', '>=', now())
          ->where('remaining_sessions', '>', 0);
    })->exists();

if ($hasActiveCourse) {
    echo "✅ This patient will appear when course filter is applied!\n\n";
} else {
    echo "❌ This patient will NOT appear in filtered results\n\n";
}

echo "📝 Test the filter:\n";
echo "   1. Go to: http://cg.test/patients\n";
echo "   2. Check the 'แสดงเฉพาะลูกค้าคอร์ส' checkbox\n";
echo "   3. You should see: {$patient->name}\n\n";

echo "================================================\n";
?>