<?php
/**
 * Test 2: Thai Search Functionality Test
 *
 * Requirements from PM:
 * - Search Thai names must return correct results
 * - Search by phone number must work
 * - No English error messages
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;
use Illuminate\Support\Facades\DB;

echo "==============================================\n";
echo "Test 2: Thai Search Functionality Test\n";
echo "==============================================\n\n";

// Create test patients with Thai names
echo "Setting up test data...\n";
echo "----------------------\n";

DB::beginTransaction();

try {
    // Create test patients
    $testPatients = [
        [
            'name' => 'สมชาย ใจดี',
            'phone' => '0812345678',
            'gender' => 'male',
            'first_visit_branch_id' => DB::table('branches')->first()->id,
        ],
        [
            'name' => 'สมหญิง รักสวย',
            'phone' => '0823456789',
            'gender' => 'female',
            'first_visit_branch_id' => DB::table('branches')->first()->id,
        ],
        [
            'name' => 'ประเสริฐ มั่นคง',
            'phone' => '0834567890',
            'gender' => 'male',
            'first_visit_branch_id' => DB::table('branches')->first()->id,
        ],
    ];

    $createdIds = [];
    foreach ($testPatients as $data) {
        $patient = Patient::create($data);
        $createdIds[] = $patient->id;
        echo "✓ Created patient: {$data['name']} (HN: {$patient->hn})\n";
    }

    echo "\nRunning Search Tests...\n";
    echo "-----------------------\n";

    // Test 1: Search by Thai name - Full name
    echo "\nTest 1: Search by full Thai name 'สมชาย ใจดี'\n";
    $results = Patient::where('name', 'LIKE', '%สมชาย ใจดี%')->get();
    if ($results->count() > 0) {
        echo "✓ PASS: Found {$results->count()} patient(s)\n";
        foreach ($results as $p) {
            echo "  - {$p->name} (HN: {$p->hn})\n";
        }
    } else {
        echo "✗ FAIL: No results found\n";
    }

    // Test 2: Search by Thai name - Partial (first name only)
    echo "\nTest 2: Search by partial name 'สมชาย'\n";
    $results = Patient::where('name', 'LIKE', '%สมชาย%')->get();
    if ($results->count() > 0) {
        echo "✓ PASS: Found {$results->count()} patient(s)\n";
        foreach ($results as $p) {
            echo "  - {$p->name} (HN: {$p->hn})\n";
        }
    } else {
        echo "✗ FAIL: No results found\n";
    }

    // Test 3: Search by Thai name - Partial (last name only)
    echo "\nTest 3: Search by last name 'รักสวย'\n";
    $results = Patient::where('name', 'LIKE', '%รักสวย%')->get();
    if ($results->count() > 0) {
        echo "✓ PASS: Found {$results->count()} patient(s)\n";
        foreach ($results as $p) {
            echo "  - {$p->name} (HN: {$p->hn})\n";
        }
    } else {
        echo "✗ FAIL: No results found\n";
    }

    // Test 4: Search by phone number
    echo "\nTest 4: Search by phone '0812345678'\n";
    $results = Patient::where('phone', 'LIKE', '%0812345678%')->get();
    if ($results->count() > 0) {
        echo "✓ PASS: Found {$results->count()} patient(s)\n";
        foreach ($results as $p) {
            echo "  - {$p->name} ({$p->phone})\n";
        }
    } else {
        echo "✗ FAIL: No results found\n";
    }

    // Test 5: Search by partial phone number
    echo "\nTest 5: Search by partial phone '0823'\n";
    $results = Patient::where('phone', 'LIKE', '%0823%')->get();
    if ($results->count() > 0) {
        echo "✓ PASS: Found {$results->count()} patient(s)\n";
        foreach ($results as $p) {
            echo "  - {$p->name} ({$p->phone})\n";
        }
    } else {
        echo "✗ FAIL: No results found\n";
    }

    // Test 6: Search combining name OR phone (simulating form search)
    echo "\nTest 6: Combined search (name OR phone) - 'สมหญิง'\n";
    $searchTerm = 'สมหญิง';
    $results = Patient::where(function($query) use ($searchTerm) {
        $query->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
    })->get();
    if ($results->count() > 0) {
        echo "✓ PASS: Found {$results->count()} patient(s)\n";
        foreach ($results as $p) {
            echo "  - {$p->name} ({$p->phone})\n";
        }
    } else {
        echo "✗ FAIL: No results found\n";
    }

    // Test 7: Search that should return no results
    echo "\nTest 7: Search with no matches 'จอห์น สมิธ'\n";
    $results = Patient::where('name', 'LIKE', '%จอห์น สมิธ%')->get();
    if ($results->count() === 0) {
        echo "✓ PASS: Correctly returned 0 results\n";
    } else {
        echo "✗ FAIL: Should return 0 results but found {$results->count()}\n";
    }

    // Test 8: Gender filter
    echo "\nTest 8: Gender filter - female only\n";
    $results = Patient::where('gender', 'female')
        ->whereIn('id', $createdIds)
        ->get();
    if ($results->count() === 1 && $results->first()->name === 'สมหญิง รักสวย') {
        echo "✓ PASS: Gender filter found female patient correctly\n";
    } else {
        echo "✗ FAIL: Gender filter did not work correctly\n";
    }

    // Test 9: Gender filter - male only
    echo "\nTest 9: Gender filter - male only\n";
    $results = Patient::where('gender', 'male')
        ->whereIn('id', $createdIds)
        ->get();
    if ($results->count() === 2) {
        echo "✓ PASS: Gender filter found 2 male patients\n";
        foreach ($results as $p) {
            echo "  - {$p->name}\n";
        }
    } else {
        echo "✗ FAIL: Expected 2 male patients, found {$results->count()}\n";
    }

    // Test 10: Combined search and gender filter
    echo "\nTest 10: Combined search + gender filter\n";
    $searchTerm = 'สม';
    $results = Patient::where('gender', 'male')
        ->where(function($query) use ($searchTerm) {
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
        })
        ->whereIn('id', $createdIds)
        ->get();

    if ($results->count() === 1 && $results->first()->name === 'สมชาย ใจดี') {
        echo "✓ PASS: Combined filter works correctly\n";
        echo "  - Found: {$results->first()->name} (male)\n";
    } else {
        echo "✗ FAIL: Combined filter not working correctly\n";
        echo "  - Expected: 1 result ('สมชาย ใจดี')\n";
        echo "  - Found: {$results->count()} result(s)\n";
    }

    // Clean up
    echo "\nCleaning up test data...\n";
    foreach ($createdIds as $id) {
        Patient::find($id)->delete();
    }
    echo "✓ Test patients deleted\n";

    DB::commit();

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n==============================================\n";
echo "Test 2 Complete!\n";
echo "==============================================\n\n";

echo "Manual Test Instructions:\n";
echo "-------------------------\n";
echo "1. Go to: http://localhost:8000/patients\n";
echo "2. In search box, type Thai name: 'สมชาย'\n";
echo "3. Click 'ค้นหา' button\n";
echo "4. Verify results show patients with 'สมชาย' in name\n";
echo "5. Try searching by phone: '081'\n";
echo "6. Verify results show patients with '081' in phone\n";
echo "7. Verify all text remains in Thai (no English errors)\n\n";
