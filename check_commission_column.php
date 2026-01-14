<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$columns = DB::select('SHOW COLUMNS FROM course_packages WHERE Field = "per_session_commission_rate"');
foreach ($columns as $column) {
    echo "Column: " . $column->Field . "\n";
    echo "Type: " . $column->Type . "\n";
    echo "Null: " . $column->Null . "\n";
    echo "Default: " . $column->Default . "\n";
}

echo "\n";
$columns2 = DB::select('SHOW COLUMNS FROM course_packages WHERE Field = "commission_rate"');
foreach ($columns2 as $column) {
    echo "Column: " . $column->Field . "\n";
    echo "Type: " . $column->Type . "\n";
    echo "Null: " . $column->Null . "\n";
    echo "Default: " . $column->Default . "\n";
}