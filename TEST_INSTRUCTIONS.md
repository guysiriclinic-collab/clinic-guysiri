# คู่มือรัน Test บน Laragon

## ขั้นตอนการรัน Test

### 1. เปิด Laragon Terminal
- คลิก Terminal ใน Laragon หรือ
- คลิกขวาที่ Laragon > Terminal

### 2. สร้าง Testing Database
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS cg_testing;"
```

### 3. เข้าไปที่โฟลเดอร์โปรเจค
```bash
cd C:\laragon\www\new\clinic
```

### 4. รัน Migration สำหรับ Testing
```bash
php artisan migrate:fresh --env=testing --seed
```

### 5. รัน Test Suite
```bash
# รันทุก test
php artisan test --filter=SystemScenarioTest --env=testing

# หรือรันแบบ verbose เพื่อดูรายละเอียด
php artisan test --filter=SystemScenarioTest --env=testing -v
```

## Test Scenarios ที่จะถูกทดสอบ

### 1. Patient Management Tests
- ✅ สร้างผู้ป่วยใหม่
- ✅ ค้นหาผู้ป่วยด้วยเบอร์โทร
- ✅ Validate ข้อมูลผู้ป่วย

### 2. Appointment & Queue Tests
- ✅ สร้างนัดหมาย
- ✅ จัดการคิว
- ✅ เปลี่ยนสถานะคิว

### 3. Treatment Tests
- ✅ สร้าง Treatment
- ✅ Generate HN Number (ป้องกัน race condition)
- ✅ บันทึกการรักษา

### 4. Course Purchase Tests
- ✅ ซื้อคอร์สเต็มจำนวน
- ✅ ซื้อคอร์สแบบผ่อน
- ✅ ตรวจสอบวันหมดอายุ

### 5. Course Usage Tests
- ✅ ใช้คอร์ส
- ✅ ตรวจสอบจำนวนครั้งที่เหลือ
- ✅ ป้องกันการใช้เกินจำนวน
- ✅ ตรวจสอบคอร์สหมดอายุ

### 6. Course Cancellation Tests
- ✅ ยกเลิกคอร์ส
- ✅ คำนวณเงินคืน
- ✅ ตรวจสอบเงื่อนไขการยกเลิก

### 7. Payment Tests
- ✅ สร้างการชำระเงิน
- ✅ ตรวจสอบยอดเงิน
- ✅ ป้องกันการชำระเงินติดลบ

### 8. Commission Tests
- ✅ คำนวณค่าคอมมิชชั่น
- ✅ แบ่งค่าคอมมิชชั่น (70/30)
- ✅ บันทึกประวัติค่าคอมมิชชั่น

### 9. DF Payment Tests
- ✅ สร้าง DF Payment
- ✅ คำนวณยอด DF
- ✅ ตรวจสอบสถานะ DF

### 10. Edge Cases Tests
- ✅ คอร์สไม่มีวันหมดอายุ (null)
- ✅ ราคาติดลบ
- ✅ ข้อมูลไม่ครบ
- ✅ Concurrent access (race conditions)

## ตรวจสอบผลลัพธ์

หลังจากรัน test แล้ว ควรเห็นผลลัพธ์ประมาณนี้:

```
PASS  Tests\Feature\SystemScenarioTest
✓ can create patient
✓ can search patient by phone
✓ validates patient data
✓ can create appointment
✓ can manage queue
✓ can create treatment with hn number
✓ prevents hn number race condition
✓ can purchase course full payment
✓ can purchase course installment
✓ can use course session
✓ prevents course session overflow
✓ validates course expiry date
✓ can cancel course with refund
✓ can process payment
✓ validates payment amounts
✓ can calculate commission
✓ can generate df payment
✓ handles concurrent access correctly
✓ handles null course expiry
✓ prevents negative prices

Tests:  20 passed
Time:   X.XX s
```

## Troubleshooting

### ถ้า MySQL connection ล้มเหลว
- ตรวจสอบว่า MySQL service ใน Laragon กำลังทำงาน
- ตรวจสอบ username/password ใน .env.testing

### ถ้า Migration ล้มเหลว
- ตรวจสอบว่า database cg_testing ถูกสร้างแล้ว
- ลองรัน: `php artisan config:clear`

### ถ้า Test ล้มเหลว
- ตรวจสอบ Factory files ว่าครบถ้วน
- ตรวจสอบ .env.testing configuration
- ดู log ใน storage/logs/laravel.log