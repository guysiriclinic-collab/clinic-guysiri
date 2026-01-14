@extends('layouts.app')

@section('title', 'รายงาน - GCMS')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #0284c7, #0ea5e9);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
    }
    .report-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        height: 100%;
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        display: block;
    }
    .report-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #3b82f6;
    }
    .report-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }
    .report-icon.blue { background: #dbeafe; color: #2563eb; }
    .report-icon.green { background: #dcfce7; color: #16a34a; }
    .report-icon.orange { background: #fed7aa; color: #ea580c; }
    .report-icon.purple { background: #e9d5ff; color: #7c3aed; }
    .report-icon.cyan { background: #cffafe; color: #0891b2; }
    .report-icon.pink { background: #fce7f3; color: #db2777; }
    .report-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    .report-desc {
        font-size: 0.875rem;
        color: #64748b;
        line-height: 1.5;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1"><i class="bi bi-bar-chart-line me-2"></i>รายงาน</h4>
            <p class="mb-0 opacity-75">เลือกรายงานที่ต้องการดู</p>
        </div>
        <a href="{{ route('reports.dashboard') }}" class="btn btn-light">
            <i class="bi bi-speedometer2 me-1"></i>Dashboard
        </a>
    </div>

    <!-- Financial Reports -->
    <div class="section-title">
        <i class="bi bi-cash-coin text-success"></i>
        รายงานการเงิน
    </div>
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('reports.pnl') }}" class="report-card">
                <div class="report-icon green">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="report-title">รายงานกำไรขาดทุน (P&L)</div>
                <div class="report-desc">ดูรายได้ ค่าใช้จ่าย และกำไรสุทธิ แยกตามช่วงเวลาและสาขา</div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('invoices.index') }}" class="report-card">
                <div class="report-icon blue">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="report-title">รายงานใบเสร็จ/การขาย</div>
                <div class="report-desc">ดูรายการใบเสร็จทั้งหมด สถานะการชำระเงิน และยอดขายรายวัน</div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('expenses.index') }}" class="report-card">
                <div class="report-icon orange">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="report-title">รายงานค่าใช้จ่าย</div>
                <div class="report-desc">บันทึกและติดตามค่าใช้จ่ายประจำวันของคลินิก</div>
            </a>
        </div>
    </div>

    <!-- Operations Reports -->
    <div class="section-title">
        <i class="bi bi-clipboard-data text-primary"></i>
        รายงานการดำเนินงาน
    </div>
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('patients.index') }}" class="report-card">
                <div class="report-icon cyan">
                    <i class="bi bi-people"></i>
                </div>
                <div class="report-title">รายงานลูกค้า</div>
                <div class="report-desc">ข้อมูลลูกค้าทั้งหมด ลูกค้าใหม่ และสถิติการมาใช้บริการ</div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('appointments.index') }}" class="report-card">
                <div class="report-icon purple">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="report-title">รายงานการนัดหมาย</div>
                <div class="report-desc">ดูสถิติการนัดหมาย อัตราการมาตามนัด และการยกเลิก</div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('queue.index') }}" class="report-card">
                <div class="report-icon pink">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="report-title">รายงานคิว</div>
                <div class="report-desc">สถิติการรอคิว เวลาเฉลี่ยในการให้บริการ</div>
            </a>
        </div>
    </div>

    <!-- Inventory & Staff Reports -->
    <div class="section-title">
        <i class="bi bi-box-seam text-warning"></i>
        รายงานสต็อกและพนักงาน
    </div>
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('stock.index') }}" class="report-card">
                <div class="report-icon orange">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="report-title">รายงานสต็อกสินค้า</div>
                <div class="report-desc">ดูจำนวนคงเหลือ สินค้าใกล้หมด และประวัติการเคลื่อนไหว</div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('commissions.index') }}" class="report-card">
                <div class="report-icon green">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="report-title">รายงานค่าคอมมิชชัน</div>
                <div class="report-desc">ดูค่าคอมมิชชันของพนักงานแยกตามช่วงเวลา</div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('df-payments.index') }}" class="report-card">
                <div class="report-icon blue">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="report-title">รายงานค่ามือ (DF)</div>
                <div class="report-desc">ดูค่ามือของ PT/หมอ แยกตามการทำหัตถการ</div>
            </a>
        </div>
    </div>

    <!-- Course Reports -->
    <div class="section-title">
        <i class="bi bi-journal-bookmark text-info"></i>
        รายงานคอร์ส
    </div>
    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('course-purchases.index') }}" class="report-card">
                <div class="report-icon cyan">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div class="report-title">รายงานการซื้อคอร์ส</div>
                <div class="report-desc">ดูรายการซื้อคอร์ส สถานะการใช้งาน และคอร์สที่ใกล้หมดอายุ</div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('course-packages.index') }}" class="report-card">
                <div class="report-icon purple">
                    <i class="bi bi-box2-heart"></i>
                </div>
                <div class="report-title">รายงานแพ็คเกจคอร์ส</div>
                <div class="report-desc">ดูแพ็คเกจคอร์สทั้งหมด ยอดขาย และความนิยม</div>
            </a>
        </div>
    </div>
</div>
@endsection
