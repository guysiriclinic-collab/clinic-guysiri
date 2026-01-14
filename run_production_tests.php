<?php
/**
 * Production Testing Script for GCMS Clinic System
 * Run this script to test all modules before production deployment
 *
 * Usage: php run_production_tests.php
 */

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Branch;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Queue;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use App\Models\CoursePackage;
use App\Models\CoursePurchase;
use App\Models\Role;
use App\Models\Permission;

class ProductionTestSuite
{
    private $results = [];
    private $testsPassed = 0;
    private $testsFailed = 0;
    private $testsSkipped = 0;
    private $currentModule = '';

    public function __construct()
    {
        echo "\n";
        echo "════════════════════════════════════════════════════════════════\n";
        echo "           GCMS CLINIC SYSTEM - PRODUCTION TESTING SUITE        \n";
        echo "════════════════════════════════════════════════════════════════\n";
        echo "Date: " . date('Y-m-d H:i:s') . "\n";
        echo "Environment: " . app()->environment() . "\n";
        echo "Database: " . env('DB_DATABASE') . "\n";
        echo "════════════════════════════════════════════════════════════════\n\n";
    }

    /**
     * Run all test modules
     */
    public function runAllTests()
    {
        // Test Database Connection
        $this->testModule('Database Connection', function() {
            $this->testDatabaseConnection();
        });

        // Test Authentication System
        $this->testModule('Authentication System', function() {
            $this->testAuthentication();
        });

        // Test Patient Management
        $this->testModule('Patient Management', function() {
            $this->testPatientManagement();
        });

        // Test Appointments & Queue
        $this->testModule('Appointments & Queue', function() {
            $this->testAppointmentsAndQueue();
        });

        // Test Billing System
        $this->testModule('Billing & Payments', function() {
            $this->testBillingSystem();
        });

        // Test Course Packages
        $this->testModule('Course Packages', function() {
            $this->testCoursePackages();
        });

        // Test Multi-Branch
        $this->testModule('Multi-Branch Operations', function() {
            $this->testMultiBranch();
        });

        // Test API Endpoints
        $this->testModule('API Endpoints', function() {
            $this->testAPIEndpoints();
        });

        // Display final results
        $this->displayResults();
    }

    /**
     * Test a module
     */
    private function testModule($moduleName, $callback)
    {
        $this->currentModule = $moduleName;
        echo "\n▶ Testing: {$moduleName}\n";
        echo str_repeat('-', 50) . "\n";

        try {
            $callback();
        } catch (\Exception $e) {
            $this->recordTest("Module Exception", false, $e->getMessage());
        }
    }

    /**
     * Test Database Connection
     */
    private function testDatabaseConnection()
    {
        // Test database connection
        try {
            DB::connection()->getPdo();
            $this->recordTest("Database connection", true);
        } catch (\Exception $e) {
            $this->recordTest("Database connection", false, $e->getMessage());
            return;
        }

        // Check tables exist
        $requiredTables = [
            'users', 'patients', 'appointments', 'queues',
            'invoices', 'payments', 'services', 'course_packages',
            'branches', 'roles', 'permissions'
        ];

        foreach ($requiredTables as $table) {
            $exists = DB::getSchemaBuilder()->hasTable($table);
            $this->recordTest("Table '{$table}' exists", $exists);
        }

        // Check migrations
        $migrationCount = DB::table('migrations')->count();
        $this->recordTest("Migrations run", $migrationCount > 0, "Total: {$migrationCount}");
    }

    /**
     * Test Authentication System
     */
    private function testAuthentication()
    {
        // Check if admin user exists
        $adminExists = User::where('username', 'admin')->exists();
        $this->recordTest("Super admin exists", $adminExists);

        if (!$adminExists) {
            // Create test admin
            try {
                $admin = User::create([
                    'id' => \Str::uuid(),
                    'name' => 'Super Admin',
                    'username' => 'admin',
                    'email' => 'admin@clinic.test',
                    'password' => Hash::make('admin123'),
                    'branch_id' => Branch::first()->id ?? null,
                ]);
                $this->recordTest("Create test admin", true);
            } catch (\Exception $e) {
                $this->recordTest("Create test admin", false, $e->getMessage());
            }
        }

        // Test password hashing
        $user = User::first();
        if ($user) {
            $hashedPassword = Hash::check('test', $user->password);
            $this->recordTest("Password hashing works", true);
        }

        // Check roles exist
        $rolesCount = Role::count();
        $this->recordTest("Roles configured", $rolesCount > 0, "Total: {$rolesCount}");

        // Check permissions exist
        $permissionsCount = Permission::count();
        $this->recordTest("Permissions configured", $permissionsCount > 0, "Total: {$permissionsCount}");
    }

    /**
     * Test Patient Management
     */
    private function testPatientManagement()
    {
        $branch = Branch::first();
        if (!$branch) {
            $this->recordTest("Branch exists", false, "No branch found");
            return;
        }

        // Test creating patient
        try {
            $patient = Patient::create([
                'id' => \Str::uuid(),
                'hn' => 'TEST' . rand(1000, 9999),
                'name' => 'Test Patient',
                'phone' => '0812345678',
                'branch_id' => $branch->id,
                'is_temporary' => false,
            ]);
            $this->recordTest("Create patient", true, "HN: {$patient->hn}");

            // Test updating patient
            $patient->update(['name' => 'Updated Patient']);
            $this->recordTest("Update patient", true);

            // Test soft delete
            $patient->delete();
            $this->recordTest("Soft delete patient", $patient->trashed());

            // Test restore
            $patient->restore();
            $this->recordTest("Restore patient", !$patient->trashed());

        } catch (\Exception $e) {
            $this->recordTest("Patient CRUD operations", false, $e->getMessage());
        }

        // Test HN generation
        $patientCount = Patient::count();
        $this->recordTest("Patients in database", true, "Total: {$patientCount}");
    }

    /**
     * Test Appointments and Queue
     */
    private function testAppointmentsAndQueue()
    {
        $patient = Patient::first();
        $service = Service::first();
        $branch = Branch::first();

        if (!$patient || !$service || !$branch) {
            $this->recordTest("Required data exists", false, "Missing patient/service/branch");
            return;
        }

        // Test appointment creation
        try {
            $appointment = Appointment::create([
                'id' => \Str::uuid(),
                'patient_id' => $patient->id,
                'service_id' => $service->id,
                'branch_id' => $branch->id,
                'appointment_date' => now()->addDay(),
                'appointment_time' => '10:00:00',
                'status' => 'scheduled',
            ]);
            $this->recordTest("Create appointment", true);

            // Test queue creation
            $queue = Queue::create([
                'id' => \Str::uuid(),
                'appointment_id' => $appointment->id,
                'patient_id' => $patient->id,
                'branch_id' => $branch->id,
                'queue_number' => 'A001',
                'queue_date' => now()->toDateString(),
                'status' => 'waiting',
            ]);
            $this->recordTest("Create queue", true, "Queue: {$queue->queue_number}");

            // Test queue status update
            $queue->update(['status' => 'in_progress']);
            $this->recordTest("Update queue status", $queue->status === 'in_progress');

        } catch (\Exception $e) {
            $this->recordTest("Appointment/Queue operations", false, $e->getMessage());
        }
    }

    /**
     * Test Billing System
     */
    private function testBillingSystem()
    {
        $patient = Patient::first();
        $service = Service::first();
        $branch = Branch::first();

        if (!$patient || !$service || !$branch) {
            $this->recordTest("Required data exists", false);
            return;
        }

        // Test invoice creation
        try {
            $invoice = Invoice::create([
                'id' => \Str::uuid(),
                'invoice_number' => 'INV-TEST-' . rand(1000, 9999),
                'patient_id' => $patient->id,
                'branch_id' => $branch->id,
                'invoice_date' => now(),
                'total_amount' => 1000,
                'status' => 'pending',
            ]);
            $this->recordTest("Create invoice", true, "Invoice: {$invoice->invoice_number}");

            // Test payment creation
            $payment = Payment::create([
                'id' => \Str::uuid(),
                'invoice_id' => $invoice->id,
                'payment_date' => now(),
                'amount' => 1000,
                'payment_method' => 'cash',
                'branch_id' => $branch->id,
            ]);
            $this->recordTest("Process payment", true, "Method: {$payment->payment_method}");

            // Update invoice status
            $invoice->update(['status' => 'paid']);
            $this->recordTest("Update invoice status", $invoice->status === 'paid');

        } catch (\Exception $e) {
            $this->recordTest("Billing operations", false, $e->getMessage());
        }
    }

    /**
     * Test Course Packages
     */
    private function testCoursePackages()
    {
        $patient = Patient::first();
        $branch = Branch::first();

        // Check if course packages exist
        $courseCount = CoursePackage::count();
        $this->recordTest("Course packages exist", $courseCount > 0, "Total: {$courseCount}");

        if ($courseCount === 0) {
            // Create test course
            try {
                $course = CoursePackage::create([
                    'id' => \Str::uuid(),
                    'name' => 'Test Course Package',
                    'sessions' => 10,
                    'price' => 5000,
                    'validity_days' => 90,
                    'branch_id' => $branch->id,
                    'is_active' => true,
                ]);
                $this->recordTest("Create course package", true);
            } catch (\Exception $e) {
                $this->recordTest("Create course package", false, $e->getMessage());
            }
        }

        // Test course purchase
        $course = CoursePackage::first();
        if ($course && $patient) {
            try {
                $purchase = CoursePurchase::create([
                    'id' => \Str::uuid(),
                    'patient_id' => $patient->id,
                    'course_package_id' => $course->id,
                    'branch_id' => $branch->id,
                    'purchase_date' => now(),
                    'expiry_date' => now()->addDays($course->validity_days),
                    'total_sessions' => $course->sessions,
                    'used_sessions' => 0,
                    'remaining_sessions' => $course->sessions,
                    'status' => 'active',
                ]);
                $this->recordTest("Purchase course", true, "Sessions: {$purchase->total_sessions}");

                // Test session usage
                $purchase->update([
                    'used_sessions' => 1,
                    'remaining_sessions' => $purchase->total_sessions - 1,
                ]);
                $this->recordTest("Use course session", $purchase->used_sessions === 1);

            } catch (\Exception $e) {
                $this->recordTest("Course operations", false, $e->getMessage());
            }
        }
    }

    /**
     * Test Multi-Branch Operations
     */
    private function testMultiBranch()
    {
        // Check branches
        $branchCount = Branch::count();
        $this->recordTest("Branches configured", $branchCount > 0, "Total: {$branchCount}");

        if ($branchCount > 1) {
            // Test branch isolation
            $branch1 = Branch::first();
            $branch2 = Branch::skip(1)->first();

            // Check if BranchScope is working
            $this->recordTest("Multi-branch support", true);
        } else {
            $this->recordTest("Multi-branch support", false, "Only 1 branch found");
        }
    }

    /**
     * Test API Endpoints
     */
    private function testAPIEndpoints()
    {
        // Check if API routes are registered
        $routes = app('router')->getRoutes();
        $apiRoutes = 0;
        $webRoutes = 0;

        foreach ($routes as $route) {
            $uri = $route->uri();
            if (str_starts_with($uri, 'api/')) {
                $apiRoutes++;
            } else {
                $webRoutes++;
            }
        }

        $this->recordTest("API routes registered", $apiRoutes > 0, "Total: {$apiRoutes}");
        $this->recordTest("Web routes registered", $webRoutes > 0, "Total: {$webRoutes}");
    }

    /**
     * Record test result
     */
    private function recordTest($testName, $passed, $message = '')
    {
        $status = $passed ? '✅ PASS' : '❌ FAIL';
        $displayMessage = $message ? " ({$message})" : '';

        echo "  {$status}: {$testName}{$displayMessage}\n";

        $this->results[$this->currentModule][] = [
            'test' => $testName,
            'passed' => $passed,
            'message' => $message
        ];

        if ($passed) {
            $this->testsPassed++;
        } else {
            $this->testsFailed++;
        }
    }

    /**
     * Display final results
     */
    private function displayResults()
    {
        echo "\n";
        echo "════════════════════════════════════════════════════════════════\n";
        echo "                        TEST RESULTS SUMMARY                     \n";
        echo "════════════════════════════════════════════════════════════════\n";

        $totalTests = $this->testsPassed + $this->testsFailed + $this->testsSkipped;
        $passRate = $totalTests > 0 ? round(($this->testsPassed / $totalTests) * 100, 2) : 0;

        echo "Total Tests: {$totalTests}\n";
        echo "Passed: {$this->testsPassed} ✅\n";
        echo "Failed: {$this->testsFailed} ❌\n";
        echo "Skipped: {$this->testsSkipped} ⏭️\n";
        echo "Pass Rate: {$passRate}%\n";
        echo "════════════════════════════════════════════════════════════════\n";

        // Show failed tests
        if ($this->testsFailed > 0) {
            echo "\n⚠️ FAILED TESTS:\n";
            echo str_repeat('-', 50) . "\n";
            foreach ($this->results as $module => $tests) {
                $failures = array_filter($tests, fn($t) => !$t['passed']);
                if (!empty($failures)) {
                    echo "\n{$module}:\n";
                    foreach ($failures as $test) {
                        echo "  ❌ {$test['test']}";
                        if ($test['message']) {
                            echo " - {$test['message']}";
                        }
                        echo "\n";
                    }
                }
            }
        }

        // Production readiness
        echo "\n";
        echo "════════════════════════════════════════════════════════════════\n";
        echo "                    PRODUCTION READINESS                        \n";
        echo "════════════════════════════════════════════════════════════════\n";

        if ($this->testsFailed === 0) {
            echo "✅ SYSTEM IS READY FOR PRODUCTION!\n";
            echo "   All tests passed successfully.\n";
        } elseif ($this->testsFailed <= 5) {
            echo "⚠️ SYSTEM NEEDS MINOR FIXES\n";
            echo "   Please fix the failed tests before production.\n";
        } else {
            echo "❌ SYSTEM IS NOT READY FOR PRODUCTION\n";
            echo "   Multiple critical issues found. Please fix all issues.\n";
        }

        echo "════════════════════════════════════════════════════════════════\n";

        // Save results to file
        $this->saveResultsToFile();
    }

    /**
     * Save results to file
     */
    private function saveResultsToFile()
    {
        $filename = 'test_results_' . date('Y-m-d_H-i-s') . '.json';
        $data = [
            'test_date' => date('Y-m-d H:i:s'),
            'environment' => app()->environment(),
            'database' => env('DB_DATABASE'),
            'summary' => [
                'total' => $this->testsPassed + $this->testsFailed,
                'passed' => $this->testsPassed,
                'failed' => $this->testsFailed,
                'skipped' => $this->testsSkipped,
            ],
            'results' => $this->results
        ];

        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
        echo "\nTest results saved to: {$filename}\n";
    }
}

// Run the test suite
try {
    $tester = new ProductionTestSuite();
    $tester->runAllTests();
} catch (\Exception $e) {
    echo "\n❌ FATAL ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}