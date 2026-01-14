<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$branches = \App\Models\Branch::all();

foreach ($branches as $branch) {
    echo "{$branch->id} - {$branch->name}\n";
}
