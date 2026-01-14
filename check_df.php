<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

// Check treatments table columns
echo "=== Treatments Table Columns ===\n";
$columns = DB::select('SHOW COLUMNS FROM treatments');
foreach ($columns as $col) {
    echo $col->Field . ' | ' . $col->Type . "\n";
}

echo "\n=== Services DF Amount ===\n";
$services = \App\Models\Service::take(3)->get(['id', 'name', 'df_amount']);
foreach ($services as $s) {
    echo "{$s->name} | DF: " . ($s->df_amount ?? 0) . "\n";
}

echo "\n=== Course Packages DF Amount ===\n";
$packages = \App\Models\CoursePackage::take(3)->get(['id', 'name', 'df_amount']);
foreach ($packages as $p) {
    echo "{$p->name} | DF: " . ($p->df_amount ?? 0) . "\n";
}
