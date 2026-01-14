<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

echo "<h2>🗑️ ล้างข้อมูลทั้งหมด (ยกเว้น Admin และ Roles)</h2>";
echo "<p style='color: red;'><strong>⚠️ คำเตือน: การดำเนินการนี้จะลบข้อมูลทั้งหมดและไม่สามารถกู้คืนได้!</strong></p>";

if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'YES_DELETE_ALL') {
    echo "<p>คลิกลิงก์ด้านล่างเพื่อยืนยันการลบข้อมูล:</p>";
    echo "<a href='?confirm=YES_DELETE_ALL' style='background: red; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>
            ✅ ยืนยันลบข้อมูลทั้งหมด
          </a>";
    exit;
}

echo "<hr>";
echo "<h3>กำลังลบข้อมูล...</h3>";

try {
    DB::beginTransaction();
    // ปิด foreign key checks ชั่วคราว
    echo "🔓 ปิด Foreign Key Checks...<br>";
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    // 1. ลบข้อมูลการรักษาก่อน (ต้องลบก่อน appointments)
    echo "🗑️ ลบข้อมูลการรักษา...<br>";
    DB::table('treatments')->delete();

    // 2. ลบข้อมูลคิว
    echo "🗑️ ลบข้อมูลคิว...<br>";
    DB::table('queues')->delete();

    // 3. ลบข้อมูลนัดหมาย
    echo "🗑️ ลบข้อมูลนัดหมาย...<br>";
    DB::table('appointments')->delete();

    // 4. ลบข้อมูลใบเสร็จและรายการ
    echo "🗑️ ลบข้อมูลใบเสร็จ...<br>";
    DB::table('invoice_items')->delete();
    DB::table('invoices')->delete();

    // 5. ลบข้อมูลคอร์สและแพ็คเกจ
    echo "🗑️ ลบข้อมูลคอร์สที่ซื้อ...<br>";
    DB::table('course_purchases')->delete();
    DB::table('course_shared_users')->delete();

    // 6. ลบข้อมูลผู้ป่วย
    echo "🗑️ ลบข้อมูลผู้ป่วย...<br>";
    DB::table('patients')->delete();

    // 7. ลบข้อมูล Refunds
    echo "🗑️ ลบข้อมูล Refunds...<br>";
    DB::table('refunds')->delete();

    // 8. ลบข้อมูล Revenue Adjustments
    echo "🗑️ ลบข้อมูล Revenue Adjustments...<br>";
    DB::table('revenue_adjustments')->delete();

    // 9. ลบข้อมูล DF Payments
    echo "🗑️ ลบข้อมูล DF Payments...<br>";
    DB::table('df_payments')->delete();

    // 10. ลบข้อมูล CRM Calls
    echo "🗑️ ลบข้อมูล CRM Calls...<br>";
    DB::table('crm_calls')->delete();

    // 11. ลบข้อมูล Expenses
    echo "🗑️ ลบข้อมูล Expenses...<br>";
    DB::table('expenses')->delete();

    // 12. ลบข้อมูล Confirmation List
    echo "🗑️ ลบข้อมูล Confirmation List...<br>";
    DB::table('confirmation_lists')->delete();

    // 13. ลบข้อมูล Stock
    echo "🗑️ ลบข้อมูล Stock Transactions...<br>";
    DB::table('stock_transactions')->delete();

    // 14. ลบข้อมูล Equipment
    echo "🗑️ ลบข้อมูล Equipment Maintenance...<br>";
    DB::table('maintenance_logs')->delete();

    // 15. ลบข้อมูล Audit Logs
    echo "🗑️ ลบข้อมูล Audit Logs...<br>";
    DB::table('audit_logs')->delete();

    // 16. ลบพนักงาน (ยกเว้น admin)
    echo "🗑️ ลบพนักงาน (ยกเว้น admin)...<br>";
    $deletedUsers = DB::table('users')
        ->where('username', '!=', 'admin')
        ->delete();
    echo "   ลบพนักงาน {$deletedUsers} คน<br>";

    // 17. รีเซ็ต Auto Increment (ถ้าเป็น MySQL)
    echo "🔄 รีเซ็ต Auto Increment...<br>";
    $tables = [
        'queues', 'appointments', 'treatments', 'invoices', 'invoice_items',
        'course_purchases', 'patients', 'refunds', 'revenue_adjustments',
        'df_payments', 'crm_calls', 'expenses', 'confirmation_lists',
        'stock_transactions', 'maintenance_logs', 'audit_logs'
    ];

    foreach ($tables as $table) {
        try {
            DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = 1");
        } catch (\Exception $e) {
            // ไม่มี AUTO_INCREMENT (ใช้ UUID) ข้ามไป
        }
    }

    // เปิด foreign key checks กลับ
    echo "🔒 เปิด Foreign Key Checks...<br>";
    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    DB::commit();

    echo "<hr>";
    echo "<h3 style='color: green;'>✅ ลบข้อมูลทั้งหมดสำเร็จ!</h3>";
    echo "<p><strong>ข้อมูลที่เหลืออยู่:</strong></p>";
    echo "<ul>";
    echo "<li>✅ Admin User (username: admin)</li>";
    echo "<li>✅ Roles และ Permissions</li>";
    echo "<li>✅ Services (บริการ)</li>";
    echo "<li>✅ Course Packages (แพ็คเกจคอร์ส)</li>";
    echo "<li>✅ Commission Rates (อัตราค่าคอมมิชชั่น)</li>";
    echo "<li>✅ Branches (สาขา)</li>";
    echo "</ul>";

    echo "<br><a href='/clinic/cg' style='background: blue; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
            🏠 กลับหน้าหลัก
          </a>";

} catch (\Exception $e) {
    DB::rollBack();
    echo "<h3 style='color: red;'>❌ เกิดข้อผิดพลาด!</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
