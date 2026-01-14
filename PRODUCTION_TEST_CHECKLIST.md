# Production Testing Checklist - GCMS Clinic System
Generated: 2026-01-14
Version: 1.0

## 🎯 วัตถุประสงค์
ทดสอบระบบ Clinic Management System ทุกฟังก์ชันก่อนขึ้น Production เพื่อให้มั่นใจว่าทุกอย่างทำงานได้สมบูรณ์

## 📋 Test Environment Setup

### 1. Pre-Testing Configuration
- [ ] Clone .env เป็น .env.testing
- [ ] ตั้งค่า Test Database (cg_test)
- [ ] Clear all caches (config, route, view)
- [ ] Run migrations สำหรับ test database
- [ ] Seed test data (branches, roles, permissions)
- [ ] ตั้งค่า APP_DEBUG=true สำหรับ testing

### 2. Test Accounts Setup
```
Super Admin:
- Username: admin
- Password: [to be set]

Branch Admin:
- Username: branch_admin
- Password: [to be set]

Staff User:
- Username: staff01
- Password: [to be set]

PT User:
- Username: pt01
- Password: [to be set]
```

---

## ✅ Module Testing Checklist

### 1. 🔐 Authentication & Authorization
#### 1.1 Login System
- [ ] Login with valid credentials
- [ ] Login with invalid credentials (error message)
- [ ] Remember me functionality
- [ ] Logout functionality
- [ ] Session timeout handling
- [ ] Password field masking

#### 1.2 Role-Based Access Control
- [ ] Super Admin - เห็นข้อมูลทุก branch
- [ ] Branch Admin - เลือก branch ได้
- [ ] Staff - เห็นเฉพาะ branch ตัวเอง
- [ ] PT - เห็นเฉพาะผู้ป่วยที่ดูแล

#### 1.3 Permissions Testing
- [ ] View permission - ดูข้อมูลได้อย่างเดียว
- [ ] Create permission - สร้างข้อมูลใหม่
- [ ] Edit permission - แก้ไขข้อมูล
- [ ] Delete permission - ลบข้อมูล
- [ ] Unauthorized access - redirect to 403

---

### 2. 👥 Patient Management
#### 2.1 Patient CRUD Operations
- [ ] Create new patient (ผู้ป่วยใหม่)
- [ ] Auto-generate HN number
- [ ] Required fields validation
- [ ] Duplicate HN prevention
- [ ] Edit patient information
- [ ] Delete patient (soft delete)
- [ ] View patient details
- [ ] Patient profile photo upload

#### 2.2 Patient Search & Filter
- [ ] Search by HN number
- [ ] Search by name (Thai)
- [ ] Search by phone number
- [ ] Filter by branch
- [ ] Filter by status (active/inactive)
- [ ] Pagination works correctly

#### 2.3 Temporary to Permanent
- [ ] Create temporary patient (walk-in)
- [ ] Convert temp to permanent
- [ ] Data retention after conversion

#### 2.4 Patient Notes
- [ ] Add patient notes
- [ ] Edit notes
- [ ] Delete notes
- [ ] Notes timestamp and author

---

### 3. 📅 Appointments & Queue Management
#### 3.1 Appointment CRUD
- [ ] Create appointment
- [ ] Select date/time
- [ ] Select service
- [ ] Select PT/Staff
- [ ] Edit appointment
- [ ] Cancel appointment (with reason)
- [ ] View appointment details

#### 3.2 Calendar View
- [ ] Calendar displays correctly
- [ ] Appointments show on calendar
- [ ] Click to view appointment
- [ ] Drag & drop reschedule
- [ ] Different colors for status

#### 3.3 Queue Management
- [ ] Auto-generate queue number
- [ ] Check-in patient (นัดมา)
- [ ] Walk-in queue creation
- [ ] Call queue (เรียกคิว)
- [ ] Start treatment (เริ่มรักษา)
- [ ] End treatment (จบการรักษา)
- [ ] Skip queue
- [ ] Cancel queue

#### 3.4 Queue Display (TV)
- [ ] Public URL accessible
- [ ] Auto-refresh queue
- [ ] Display current queue
- [ ] Display waiting list
- [ ] Sound notification
- [ ] Responsive for TV screens

#### 3.5 Confirmation Lists
- [ ] Generate daily confirmation list
- [ ] Mark confirmed/not confirmed
- [ ] Add confirmation notes
- [ ] Print confirmation list

---

### 4. 💰 Billing & Payments
#### 4.1 Invoice Creation
- [ ] Create invoice from appointment
- [ ] Add services to invoice
- [ ] Add products to invoice
- [ ] Calculate totals correctly
- [ ] Apply discounts
- [ ] Tax calculation (if applicable)
- [ ] Save as draft
- [ ] Finalize invoice

#### 4.2 Payment Processing
- [ ] Cash payment
- [ ] Credit card payment
- [ ] Bank transfer
- [ ] Split payment methods
- [ ] Installment plan setup
- [ ] Record payment date/time
- [ ] Auto-update balance

#### 4.3 Receipt & Documents
- [ ] Generate receipt (ใบเสร็จ)
- [ ] Print receipt
- [ ] Email receipt (if configured)
- [ ] Receipt numbering sequence
- [ ] Reprint receipt
- [ ] Void receipt (with reason)

#### 4.4 Refund Management
- [ ] Create refund request
- [ ] Refund reason required
- [ ] Partial refund
- [ ] Full refund
- [ ] Refund approval workflow
- [ ] Update patient balance
- [ ] Refund receipt generation

---

### 5. 📦 Course Packages
#### 5.1 Course Management
- [ ] Create course package
- [ ] Set number of sessions
- [ ] Set validity period
- [ ] Set price
- [ ] Edit course details
- [ ] Activate/Deactivate course
- [ ] Delete course (if unused)

#### 5.2 Course Purchase
- [ ] Purchase course for patient
- [ ] Apply course discount
- [ ] Link to invoice
- [ ] Payment verification
- [ ] Generate course card/document

#### 5.3 Course Usage Tracking
- [ ] Use course session
- [ ] Track remaining sessions
- [ ] Track expiry date
- [ ] Warning for expiring courses
- [ ] Block usage after expiry
- [ ] Usage history log

#### 5.4 Course Sharing
- [ ] Share course to family member
- [ ] Transfer ownership
- [ ] Track shared usage
- [ ] Sharing permissions

#### 5.5 Course Renewal
- [ ] Renew expiring course
- [ ] Extend validity
- [ ] Add more sessions
- [ ] Renewal pricing

---

### 6. 💸 Commission & Compensation
#### 6.1 Commission Setup
- [ ] Set commission rates by service
- [ ] Set commission rates by staff level
- [ ] Special commission rules
- [ ] Commission calculation methods

#### 6.2 Commission Calculation
- [ ] Auto-calculate from invoices
- [ ] Commission split (multiple staff)
- [ ] Override commission manually
- [ ] Commission approval workflow

#### 6.3 Doctor Fee (DF) Payment
- [ ] Calculate DF from treatments
- [ ] Manual DF entry
- [ ] DF payment processing
- [ ] DF payment history
- [ ] DF reports

#### 6.4 Sales Commission
- [ ] Track seller for each sale
- [ ] Calculate sales commission
- [ ] Commission payment tracking

---

### 7. 📦 Stock & Equipment
#### 7.1 Stock Management
- [ ] Add stock items
- [ ] Stock categories
- [ ] Stock in (receive)
- [ ] Stock out (usage)
- [ ] Stock adjustment
- [ ] Stock transfer between branches
- [ ] Low stock alerts
- [ ] Stock valuation

#### 7.2 Stock Transactions
- [ ] Record all stock movements
- [ ] Transaction history
- [ ] Transaction reversal
- [ ] Stock count/audit

#### 7.3 Equipment Management
- [ ] Register equipment
- [ ] Equipment categories
- [ ] Assign to branch/room
- [ ] Equipment status tracking

#### 7.4 Maintenance Logs
- [ ] Schedule maintenance
- [ ] Log maintenance performed
- [ ] Maintenance costs
- [ ] Next maintenance alerts

---

### 8. 💬 Social CRM & Marketing
#### 8.1 Facebook Messenger Integration
- [ ] Receive messages
- [ ] Send replies
- [ ] Message history
- [ ] Link chat to patient
- [ ] Auto-detect existing patient

#### 8.2 Lead Management
- [ ] Track lead status (new → contacted → booked → completed)
- [ ] Daily lead progression
- [ ] Lead source tracking
- [ ] ROI per ad campaign

#### 8.3 CRM Actions
- [ ] Create appointment from chat
- [ ] Call tracking
- [ ] Follow-up reminders
- [ ] Agent assignment

#### 8.4 Loyalty Points
- [ ] Earn points from purchases
- [ ] Points calculation rules
- [ ] Redeem points
- [ ] Points history
- [ ] Points expiry

---

### 9. 👨‍⚕️ Staff & HR Management
#### 9.1 Staff Management
- [ ] Add new staff
- [ ] Staff profiles
- [ ] Staff roles assignment
- [ ] Staff schedule management
- [ ] Active/Inactive status

#### 9.2 Leave Management
- [ ] Request leave
- [ ] Leave approval workflow
- [ ] Leave balance tracking
- [ ] Leave calendar view

#### 9.3 PT Management
- [ ] PT assignment to appointments
- [ ] PT schedule
- [ ] PT replacement
- [ ] PT performance tracking
- [ ] PT commission calculation

---

### 10. 📊 Reports & Analytics
#### 10.1 Dashboard
- [ ] Daily summary widgets
- [ ] Revenue charts
- [ ] Appointment statistics
- [ ] Patient statistics
- [ ] Real-time updates

#### 10.2 Financial Reports
- [ ] Profit & Loss statement
- [ ] Revenue by service
- [ ] Revenue by branch
- [ ] Revenue by period
- [ ] Export to Excel/PDF

#### 10.3 Operational Reports
- [ ] Appointment reports
- [ ] Queue time analysis
- [ ] Staff performance
- [ ] Service utilization
- [ ] Patient demographics

---

### 11. 🏢 Multi-Branch Operations
#### 11.1 Branch Management
- [ ] Create new branch
- [ ] Edit branch details
- [ ] Set branch working hours
- [ ] Branch-specific settings

#### 11.2 Branch Scope Testing
- [ ] Data isolation between branches
- [ ] Cross-branch patient access
- [ ] Cross-branch reporting
- [ ] Branch switching (for Admin)

#### 11.3 Branch-specific Features
- [ ] Branch-specific pricing
- [ ] Branch-specific services
- [ ] Branch inventory
- [ ] Branch staff assignment

---

### 12. 🌐 Public Features
#### 12.1 Online Booking
- [ ] Access booking page
- [ ] Select service
- [ ] Select date/time
- [ ] Enter patient information
- [ ] Booking confirmation
- [ ] Booking notification to clinic

#### 12.2 Queue Display
- [ ] Public URL works
- [ ] No authentication required
- [ ] Auto-refresh
- [ ] Mobile responsive

---

### 13. 🔧 System Administration
#### 13.1 User Management
- [ ] Create users
- [ ] Assign roles
- [ ] Reset passwords
- [ ] Activate/Deactivate users
- [ ] User activity logs

#### 13.2 System Settings
- [ ] Clinic information
- [ ] Working hours
- [ ] Holiday settings
- [ ] Notification settings
- [ ] Email configuration

#### 13.3 Audit & Logs
- [ ] Login logs
- [ ] Activity logs
- [ ] Error logs
- [ ] Data change logs
- [ ] Log retention

---

## 🚦 Performance Testing

### Response Time Requirements
- [ ] Login: < 2 seconds
- [ ] Page load: < 3 seconds
- [ ] Search results: < 2 seconds
- [ ] Report generation: < 10 seconds
- [ ] API responses: < 1 second

### Load Testing
- [ ] 10 concurrent users
- [ ] 50 concurrent users
- [ ] 100 concurrent users
- [ ] Database query optimization
- [ ] Image/file upload performance

---

## 🔒 Security Testing

### Authentication Security
- [ ] SQL injection prevention
- [ ] XSS prevention
- [ ] CSRF protection active
- [ ] Session security
- [ ] Password encryption

### Data Security
- [ ] HTTPS enforcement
- [ ] Sensitive data encryption
- [ ] File upload validation
- [ ] API authentication
- [ ] Branch data isolation

---

## 📱 Browser & Device Testing

### Browser Compatibility
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)

### Device Testing
- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

---

## 🐛 Known Issues & Bugs

### Critical Issues
| Issue | Module | Status | Notes |
|-------|--------|--------|-------|
| | | | |

### Minor Issues
| Issue | Module | Status | Notes |
|-------|--------|--------|-------|
| | | | |

---

## ✅ Pre-Production Checklist

### Environment Setup
- [ ] Change APP_ENV to production
- [ ] Change APP_DEBUG to false
- [ ] Generate new APP_KEY
- [ ] Update database credentials
- [ ] Configure mail server
- [ ] Setup SSL certificate
- [ ] Configure backup system

### Data Migration
- [ ] Backup current database
- [ ] Migrate production data
- [ ] Verify data integrity
- [ ] Test with production data

### Final Verification
- [ ] All critical features tested
- [ ] No blocking bugs
- [ ] Performance acceptable
- [ ] Security measures in place
- [ ] User training completed
- [ ] Documentation ready
- [ ] Support system ready

---

## 📝 Test Results Summary

**Test Date:** _______________
**Tested By:** _______________
**Environment:** _______________

### Overall Results
- Total Tests: _______________
- Passed: _______________
- Failed: _______________
- Blocked: _______________
- Skip: _______________

### Module Results
| Module | Total | Pass | Fail | Notes |
|--------|-------|------|------|-------|
| Authentication | | | | |
| Patient Management | | | | |
| Appointments | | | | |
| Billing | | | | |
| Courses | | | | |
| Commission | | | | |
| Stock | | | | |
| CRM | | | | |
| Reports | | | | |
| Multi-branch | | | | |

### Sign-off
- [ ] QA Team Approval
- [ ] Development Team Approval
- [ ] Business Owner Approval
- [ ] Ready for Production

---

## 📞 Support Contacts

**Development Team:**
- Name: _______________
- Phone: _______________
- Email: _______________

**System Admin:**
- Name: _______________
- Phone: _______________
- Email: _______________

**Emergency Contact:**
- Name: _______________
- Phone: _______________
- Email: _______________

---

*This checklist must be completed and approved before production deployment*