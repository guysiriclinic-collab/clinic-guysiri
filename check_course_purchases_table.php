<?php
/**
 * Check Course Purchases Table Structure
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n================================================\n";
echo "     COURSE PURCHASES TABLE STRUCTURE          \n";
echo "================================================\n\n";

// Check if table exists
if (Schema::hasTable('course_purchases')) {
    echo "✅ Table 'course_purchases' exists\n\n";

    // Get column information
    echo "📋 Table Columns:\n";
    echo "----------------------------------------\n";
    $columns = DB::select("SHOW COLUMNS FROM course_purchases");

    foreach ($columns as $column) {
        echo "   • {$column->Field} ({$column->Type})" .
             ($column->Null == 'YES' ? ' [nullable]' : ' [required]') . "\n";
    }

    echo "\n📊 Sample Data (first 3 records):\n";
    echo "----------------------------------------\n";
    $samples = DB::table('course_purchases')->limit(3)->get();

    if ($samples->isEmpty()) {
        echo "   ⚠️ No data found in course_purchases table\n";
    } else {
        foreach ($samples as $index => $sample) {
            echo "\n   Record " . ($index + 1) . ":\n";
            foreach ($sample as $key => $value) {
                if ($value !== null) {
                    echo "      • {$key}: {$value}\n";
                }
            }
        }
    }
} else {
    echo "❌ Table 'course_purchases' does not exist!\n";
    echo "   Need to run migrations first.\n";
}

echo "\n================================================\n";
?>