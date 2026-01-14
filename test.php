<?php

echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . __DIR__ . "<br>";

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    echo "✓ vendor/autoload.php exists<br>";
} else {
    echo "✗ vendor/autoload.php NOT FOUND<br>";
}

if (file_exists(__DIR__.'/.env')) {
    echo "✓ .env exists<br>";
} else {
    echo "✗ .env NOT FOUND<br>";
}

if (file_exists(__DIR__.'/public/index.php')) {
    echo "✓ public/index.php exists<br>";
} else {
    echo "✗ public/index.php NOT FOUND<br>";
}

echo "<br><strong>Testing Laravel...</strong><br>";

try {
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    echo "✓ Laravel loaded successfully!<br>";

    // Test database connection
    $pdo = new PDO('mysql:host=localhost;dbname=caseto_clinicza', 'caseto_clinicza', '@Aa112233++');
    echo "✓ Database connected successfully!<br>";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}
