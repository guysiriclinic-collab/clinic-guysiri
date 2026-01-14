<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h3>Checking Routes for 'finish-treatment':</h3>";

$routes = \Illuminate\Support\Facades\Route::getRoutes();

foreach ($routes as $route) {
    $uri = $route->uri();
    if (strpos($uri, 'finish-treatment') !== false) {
        echo "✓ Found: " . $route->methods()[0] . " /" . $uri . "<br>";
    }
}

echo "<br><h3>All Queue Routes:</h3>";

foreach ($routes as $route) {
    $uri = $route->uri();
    if (strpos($uri, 'queue') !== false) {
        echo $route->methods()[0] . " /" . $uri . "<br>";
    }
}
