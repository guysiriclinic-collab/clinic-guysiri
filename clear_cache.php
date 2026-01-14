<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "Clearing config cache...\n";
$kernel->call('config:clear');

echo "Clearing application cache...\n";
$kernel->call('cache:clear');

echo "Clearing route cache...\n";
$kernel->call('route:clear');

echo "Clearing view cache...\n";
$kernel->call('view:clear');

echo "\nAll caches cleared successfully!";
