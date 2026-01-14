<?php

require 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Updating DF Amounts for Course Packages ===" . PHP_EOL . PHP_EOL;

try {
    // Use Laravel DB connection
    $pdo = DB::connection()->getPdo();

    echo "✓ Connected to database successfully" . PHP_EOL . PHP_EOL;

    // Step 1: Update services that don't have df_amount
    echo "Step 1: Updating services..." . PHP_EOL;
    $sql1 = "
        UPDATE services
        SET df_amount = COALESCE(default_df_rate, 0)
        WHERE df_amount IS NULL
    ";
    $stmt1 = $pdo->exec($sql1);
    echo "  → Updated {$stmt1} services" . PHP_EOL . PHP_EOL;

    // Step 2: Update course packages from linked services
    echo "Step 2: Updating course packages..." . PHP_EOL;
    $sql2 = "
        UPDATE course_packages cp
        INNER JOIN services s ON cp.service_id = s.id
        SET cp.df_amount = COALESCE(s.df_amount, s.default_df_rate, 0)
        WHERE cp.df_amount IS NULL
    ";
    $stmt2 = $pdo->exec($sql2);
    echo "  → Updated {$stmt2} course packages" . PHP_EOL . PHP_EOL;

    // Step 3: Show updated course packages
    echo "Step 3: Checking updated course packages..." . PHP_EOL;
    $sql3 = "
        SELECT cp.name, cp.df_amount, s.name as service_name
        FROM course_packages cp
        LEFT JOIN services s ON cp.service_id = s.id
        WHERE cp.deleted_at IS NULL
        ORDER BY cp.name
    ";
    $stmt3 = $pdo->query($sql3);
    $packages = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    foreach ($packages as $pkg) {
        $dfAmount = $pkg['df_amount'] ?? 'NULL';
        echo "  - {$pkg['name']} ({$pkg['service_name']}): DF = {$dfAmount} บาท" . PHP_EOL;
    }

    echo PHP_EOL . "=== Update Complete ===" . PHP_EOL;
    echo "✓ Course packages now have DF amounts set!" . PHP_EOL;
    echo "✓ New course sales will now record DF payments correctly!" . PHP_EOL;

} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
