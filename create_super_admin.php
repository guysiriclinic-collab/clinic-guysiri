<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Creating Super Admin User ===\n\n";

try {
    // Get Admin Role
    $adminRole = \App\Models\Role::where('name', 'Admin')->first();

    if (!$adminRole) {
        echo "❌ ERROR: Admin role not found. Please run db:seed first.\n";
        exit(1);
    }

    // Get Main Branch
    $mainBranch = \App\Models\Branch::where('code', 'MAIN')->first();

    if (!$mainBranch) {
        echo "❌ ERROR: Main branch not found. Please run db:seed first.\n";
        exit(1);
    }

    // Check if admin user already exists
    $existingAdmin = \App\Models\User::where('username', 'admin')->first();

    if ($existingAdmin) {
        echo "⚠️  WARNING: Admin user already exists!\n";
        echo "   Username: admin\n";
        echo "   Name: {$existingAdmin->name}\n";
        echo "   Email: {$existingAdmin->email}\n\n";
        echo "Do you want to recreate? (This will delete the existing user)\n";
        echo "Type 'yes' to continue: ";

        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);

        if (trim($line) !== 'yes') {
            echo "❌ Cancelled.\n";
            exit(0);
        }

        $existingAdmin->forceDelete();
        echo "✅ Deleted existing admin user.\n\n";
    }

    // Create Super Admin User
    $admin = \App\Models\User::create([
        'name' => 'Super Administrator',
        'username' => 'admin',
        'email' => 'admin@guysiri.com',
        'password' => 'password', // Will be hashed by model
        'role_id' => $adminRole->id,
        'branch_id' => $mainBranch->id,
        'is_active' => true,
    ]);

    echo "✅ SUCCESS: Super Admin User created!\n\n";
    echo "=== Login Credentials ===\n";
    echo "URL: http://localhost:8000/login\n";
    echo "Username: admin\n";
    echo "Password: password\n";
    echo "Name: {$admin->name}\n";
    echo "Email: {$admin->email}\n";
    echo "Role: {$adminRole->name}\n";
    echo "Branch: {$mainBranch->name}\n\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
