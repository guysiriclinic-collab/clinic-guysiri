@extends('layouts.app')

@section('title', 'แดชบอร์ด - GCMS')

@push('styles')
<style>
    /* MINIMAL DASHBOARD - Clean & Simple */

    /* Header */
    .dashboard-header {
        background: linear-gradient(135deg, #0284c7, #0ea5e9);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        color: white;
        margin-bottom: 1.25rem;
    }

    .dashboard-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .dashboard-header p {
        font-size: 0.8rem;
        opacity: 0.9;
        margin: 0;
    }

    /* KPI Cards */
    .kpi-card {
        background: #fff;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        height: 100%;
    }

    .kpi-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        margin-bottom: 0.75rem;
    }

    .kpi-icon.primary { background: #e0f2fe; color: #0369a1; }
    .kpi-icon.success { background: #dcfce7; color: #166534; }
    .kpi-icon.info { background: #dbeafe; color: #1e40af; }
    .kpi-icon.warning { background: #fef3c7; color: #92400e; }

    .kpi-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .kpi-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
    }

    .kpi-change {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 10px;
        display: inline-block;
        margin-top: 0.5rem;
    }

    .kpi-change.positive { background: #dcfce7; color: #166534; }
    .kpi-change.negative { background: #fee2e2; color: #dc2626; }

    /* Section Card */
    .section-card {
        background: #fff;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        height: 100%;
    }

    .section-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.75rem;
    }

    /* Queue Items */
    .queue-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
        background: #f8fafc;
        border-radius: 6px;
        margin-bottom: 0.5rem;
        border-left: 3px solid;
    }

    .queue-item:last-child { margin-bottom: 0; }
    .queue-item.waiting { border-left-color: #f59e0b; }
    .queue-item.in-progress { border-left-color: #0ea5e9; }
    .queue-item.completed { border-left-color: #10b981; }

    .queue-item h6 {
        font-size: 0.8rem;
        font-weight: 600;
        color: #334155;
        margin: 0;
    }

    .queue-item small {
        font-size: 0.7rem;
        color: #64748b;
    }

    .queue-badge {
        font-size: 0.65rem;
        padding: 2px 6px;
        border-radius: 4px;
    }

    /* Quick Actions */
    .quick-action {
        display: block;
        text-align: center;
        padding: 0.75rem 0.5rem;
        background: #f8fafc;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
    }

    .quick-action:hover {
        background: #e0f2fe;
        border-color: #bae6fd;
        text-decoration: none;
    }

    .quick-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: #0ea5e9;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-size: 0.875rem;
    }

    .quick-action small {
        font-size: 0.7rem;
        font-weight: 500;
        color: #475569;
    }

    /* Branch Selector */
    .branch-card {
        background: #f8fafc;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        margin-bottom: 1rem;
    }

    .branch-card .form-select {
        font-size: 0.8rem;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
    }

    .branch-card .btn {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1rem;
        }

        .dashboard-header h2 {
            font-size: 1.1rem;
        }

        .kpi-value {
            font-size: 1.25rem;
        }

        .kpi-card {
            padding: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="dashboard-header">
        <h2>สวัสดี, {{ Auth::user()->name ?? 'Admin' }}</h2>
        <p>{{ now()->locale('th')->isoFormat('วันdddd ที่ D MMMM YYYY') }}</p>
    </div>

    {{-- Branch Switcher --}}
    @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isAreaManager()))
    <div class="branch-card">
        <div class="row align-items-center">
            <div class="col-md-4 mb-2 mb-md-0">
                <small style="color: #64748b;">สาขา: <strong style="color: #1e293b;">{{ auth()->user()->getCurrentBranch()->name ?? 'ไม่ได้เลือก' }}</strong></small>
            </div>
            <div class="col-md-8">
                <div class="alert alert-info mb-0 py-2">
                    <i class="bi bi-building me-2"></i>สาขา: <strong>{{ auth()->user()->branch->name ?? 'ไม่ระบุสาขา' }}</strong>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- KPI Cards -->
    <div class="row g-2 mb-3">
        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon primary">
                    <i class="bi bi-cash"></i>
                </div>
                <div class="kpi-value">฿{{ number_format($todayRevenue, 0) }}</div>
                <div class="kpi-label">รายได้วันนี้</div>
                @if($revenueChange >= 0)
                <span class="kpi-change positive"><i class="bi bi-arrow-up"></i> {{ number_format(abs($revenueChange), 0) }}%</span>
                @else
                <span class="kpi-change negative"><i class="bi bi-arrow-down"></i> {{ number_format(abs($revenueChange), 0) }}%</span>
                @endif
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon success">
                    <i class="bi bi-people"></i>
                </div>
                <div class="kpi-value">{{ $todayPatients }}</div>
                <div class="kpi-label">นัดหมายวันนี้</div>
                @if($patientsChange >= 0)
                <span class="kpi-change positive"><i class="bi bi-arrow-up"></i> {{ number_format(abs($patientsChange), 0) }}%</span>
                @else
                <span class="kpi-change negative"><i class="bi bi-arrow-down"></i> {{ number_format(abs($patientsChange), 0) }}%</span>
                @endif
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon info">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="kpi-value">{{ $waitingQueue }}</div>
                <div class="kpi-label">คิวรอ</div>
                @if($queueChange >= 0)
                <span class="kpi-change positive"><i class="bi bi-arrow-up"></i> {{ number_format(abs($queueChange), 0) }}%</span>
                @else
                <span class="kpi-change negative"><i class="bi bi-arrow-down"></i> {{ number_format(abs($queueChange), 0) }}%</span>
                @endif
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-icon warning">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div class="kpi-value">{{ $todayNewPatients }}</div>
                <div class="kpi-label">ลูกค้าใหม่วันนี้</div>
                @if($newPatientsChange >= 0)
                <span class="kpi-change positive"><i class="bi bi-arrow-up"></i> {{ number_format(abs($newPatientsChange), 0) }}%</span>
                @else
                <span class="kpi-change negative"><i class="bi bi-arrow-down"></i> {{ number_format(abs($newPatientsChange), 0) }}%</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Patient Classification & Queue Status -->
    <div class="row g-2">
        <!-- Patient Classification -->
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-title"><i class="bi bi-people-fill me-2"></i>จำแนกประเภทลูกค้า</div>

                <div class="queue-item" style="border-left-color: #10b981;">
                    <div>
                        <h6>ลูกค้าใหม่</h6>
                        <small>{{ $todayNewPatients }} คน</small>
                    </div>
                    <span class="queue-badge" style="background: #dcfce7; color: #166534;">
                        @if($newPatientsChange >= 0)
                            <i class="bi bi-arrow-up"></i> {{ number_format(abs($newPatientsChange), 0) }}%
                        @else
                            <i class="bi bi-arrow-down"></i> {{ number_format(abs($newPatientsChange), 0) }}%
                        @endif
                    </span>
                </div>

                <div class="queue-item" style="border-left-color: #f59e0b;">
                    <div>
                        <h6>ลูกค้าคอร์ส</h6>
                        <small>{{ $todayCoursePatients }} คน</small>
                    </div>
                    <span class="queue-badge" style="background: #fef3c7; color: #92400e;">
                        @if($coursePatientsChange >= 0)
                            <i class="bi bi-arrow-up"></i> {{ number_format(abs($coursePatientsChange), 0) }}%
                        @else
                            <i class="bi bi-arrow-down"></i> {{ number_format(abs($coursePatientsChange), 0) }}%
                        @endif
                    </span>
                </div>

                <div class="queue-item" style="border-left-color: #0ea5e9;">
                    <div>
                        <h6>ลูกค้าเก่า</h6>
                        <small>{{ $todayOldPatients }} คน</small>
                    </div>
                    <span class="queue-badge" style="background: #dbeafe; color: #1e40af;">
                        @if($oldPatientsChange >= 0)
                            <i class="bi bi-arrow-up"></i> {{ number_format(abs($oldPatientsChange), 0) }}%
                        @else
                            <i class="bi bi-arrow-down"></i> {{ number_format(abs($oldPatientsChange), 0) }}%
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Queue Status -->
        <div class="col-lg-6">
            <div class="section-card">
                <div class="section-title"><i class="bi bi-list-ol me-2"></i>สถานะคิว</div>

                <div class="queue-item waiting">
                    <div>
                        <h6>รอตรวจ</h6>
                        <small>{{ $queueWaiting }} คน</small>
                    </div>
                    <span class="queue-badge" style="background: #fef3c7; color: #92400e;">รอ</span>
                </div>

                <div class="queue-item in-progress">
                    <div>
                        <h6>กำลังตรวจ</h6>
                        <small>{{ $queueInProgress }} คน</small>
                    </div>
                    <span class="queue-badge" style="background: #dbeafe; color: #1e40af;">ดำเนินการ</span>
                </div>

                <div class="queue-item completed">
                    <div>
                        <h6>เสร็จแล้ว</h6>
                        <small>{{ $queueCompleted }} คน</small>
                    </div>
                    <span class="queue-badge" style="background: #dcfce7; color: #166534;">เสร็จ</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('switchBranchBtn')?.addEventListener('click', function() {
    const branchId = document.getElementById('branchSelector').value;
    const button = this;

    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    fetch('{{ route('branch.switch') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ branch_id: branchId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('เกิดข้อผิดพลาด');
            button.disabled = false;
            button.innerHTML = '<i class="bi bi-arrow-repeat"></i>';
        }
    })
    .catch(error => {
        alert('เกิดข้อผิดพลาด');
        button.disabled = false;
        button.innerHTML = '<i class="bi bi-arrow-repeat"></i>';
    });
});
</script>
@endpush
