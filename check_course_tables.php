<?php
/**
 * Check what tables exist related to courses
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
echo "     CHECKING COURSE-RELATED TABLES            \n";
echo "================================================\n\n";

// Get all tables
$tables = DB::select('SHOW TABLES');
$dbName = env('DB_DATABASE', 'gcms_db');
$tableKey = "Tables_in_{$dbName}";

echo "📋 All tables in database:\n";
echo "----------------------------------------\n";

$courseRelatedTables = [];
foreach ($tables as $table) {
    $tableName = $table->$tableKey;
    echo "   • {$tableName}";

    // Check if table might be course-related
    if (stripos($tableName, 'course') !== false ||
        stripos($tableName, 'package') !== false ||
        stripos($tableName, 'program') !== false ||
        stripos($tableName, 'service') !== false ||
        stripos($tableName, 'treatment') !== false) {
        echo " ⭐";
        $courseRelatedTables[] = $tableName;
    }
    echo "\n";
}

echo "\n📦 Potentially course-related tables:\n";
echo "----------------------------------------\n";

foreach ($courseRelatedTables as $tableName) {
    echo "\n➤ Table: {$tableName}\n";
    $columns = DB::select("SHOW COLUMNS FROM {$tableName}");
    foreach ($columns as $column) {
        echo "   • {$column->Field} ({$column->Type})\n";
    }
}

// Check sample course_purchases data to understand package_id
echo "\n🔍 Checking course_purchases package_id references:\n";
echo "----------------------------------------\n";

$samplePurchase = DB::table('course_purchases')->first();
if ($samplePurchase) {
    echo "Sample package_id: {$samplePurchase->package_id}\n";

    // Try to find this ID in other tables
    foreach ($courseRelatedTables as $tableName) {
        if ($tableName == 'course_purchases') continue;

        // Check if table has an 'id' column
        if (Schema::hasColumn($tableName, 'id')) {
            $found = DB::table($tableName)
                ->where('id', $samplePurchase->package_id)
                ->first();

            if ($found) {
                echo "✅ Found matching ID in table: {$tableName}\n";

                // Show some data
                foreach ($found as $key => $value) {
                    if (in_array($key, ['id', 'name', 'title', 'description'])) {
                        echo "   • {$key}: {$value}\n";
                    }
                }
            }
        }
    }
}

echo "\n================================================\n";
?>