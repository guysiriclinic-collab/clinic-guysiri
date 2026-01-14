<?php
/**
 * Check invoices table structure
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

echo "\n================================================\n";
echo "     INVOICES TABLE STRUCTURE                   \n";
echo "================================================\n\n";

// Get column information
echo "📋 Table Columns:\n";
echo "----------------------------------------\n";
$columns = DB::select("SHOW COLUMNS FROM invoices");

foreach ($columns as $column) {
    echo "   • {$column->Field} ({$column->Type})" .
         ($column->Null == 'YES' ? ' [nullable]' : ' [required]') . "\n";
}

echo "\n📊 Sample Invoice (first record):\n";
echo "----------------------------------------\n";
$sample = DB::table('invoices')->first();

if ($sample) {
    foreach ($sample as $key => $value) {
        if ($value !== null) {
            echo "   • {$key}: {$value}\n";
        }
    }
} else {
    echo "   ⚠️ No invoices found in database\n";
}

echo "\n================================================\n";
?>