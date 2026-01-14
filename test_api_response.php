<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

use App\Models\{Treatment, Invoice, InvoiceItem, CoursePurchase, User};

// Get a recent completed appointment with course
$treatment = Treatment::whereNotNull('invoice_id')
    ->latest()
    ->first();

if (!$treatment) {
    echo "No treatment with invoice found\n";
    exit;
}

echo "=== Treatment ===\n";
echo "Appointment ID: {$treatment->appointment_id}\n";
echo "Invoice ID: {$treatment->invoice_id}\n";
echo "Course Purchase ID: {$treatment->course_purchase_id}\n\n";

$invoice = Invoice::find($treatment->invoice_id);
$items = InvoiceItem::where('invoice_id', $invoice->id)->get();

echo "=== Invoice Items ===\n";
foreach ($items as $item) {
    echo "Type: {$item->item_type} | Item ID: {$item->item_id} | Desc: {$item->description}\n";
}

echo "\n=== Purchased Courses (from invoice) ===\n";
$purchasedCourses = CoursePurchase::with('package')
    ->where('invoice_id', $invoice->id)
    ->get();

foreach ($purchasedCourses as $cp) {
    $sellerIds = $cp->seller_ids ?? [];
    $sellerNames = [];
    if (!empty($sellerIds)) {
        $sellerNames = User::whereIn('id', $sellerIds)->pluck('name')->toArray();
    }
    echo "Course ID: {$cp->id}\n";
    echo "  Package ID: {$cp->package_id}\n";
    echo "  Package Name: " . ($cp->package->name ?? 'Unknown') . "\n";
    echo "  Seller IDs: " . json_encode($sellerIds) . "\n";
    echo "  Seller Names: " . json_encode($sellerNames) . "\n";
}
