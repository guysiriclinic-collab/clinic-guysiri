<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

use App\Models\CoursePurchase;
use App\Models\User;

$courses = CoursePurchase::with('package')->latest()->take(3)->get();

echo "=== Course Purchases with Seller Names ===\n";
foreach ($courses as $c) {
    $sellerIds = $c->seller_ids ?? [];
    $sellerNames = [];
    if (!empty($sellerIds)) {
        $sellerNames = User::whereIn('id', $sellerIds)->pluck('name')->toArray();
    }

    echo "ID: {$c->id}\n";
    echo "  Package: " . ($c->package->name ?? 'Unknown') . "\n";
    echo "  Seller IDs: " . json_encode($sellerIds) . "\n";
    echo "  Seller Names: " . json_encode($sellerNames) . "\n";
    echo "  Created: {$c->created_at}\n";
    echo "---\n";
}
