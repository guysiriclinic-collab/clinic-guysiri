@extends('layouts.app')

@section('title', 'รายชื่อลูกค้า - GCMS')

@push('styles')
<style>
    /* MINIMAL PATIENT LIST - Clean & Simple */

    /* Gradient Header - Match other pages */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0284c7, #0ea5e9);
    }

    /* Search Section */
    .min-search-section {
        background: #ffffff;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
        border: 1px solid #e2e8f0;
    }

    .min-search-input {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 0.875rem;
        width: 100%;
        transition: all 0.2s ease;
        color: #334155;
    }

    .min-search-input::placeholder {
        color: #94a3b8;
    }

    .min-search-input:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.1);
        outline: none;
    }

    /* Patient Cards - Mobile */
    .patient-card {
        background: #ffffff;
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .patient-card:last-child {
        border-bottom: none;
    }

    .patient-card:hover {
        background: #f8fafc;
    }

    .patient-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 500;
        flex-shrink: 0;
    }

    .patient-avatar.male {
        background: #e0f2fe;
        color: #0369a1;
    }

    .patient-avatar.female {
        background: #fce7f3;
        color: #be185d;
    }

    .patient-avatar.other {
        background: #f1f5f9;
        color: #475569;
    }

    /* Call Button */
    .btn-call {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-call:hover {
        background: #d1fae5;
        color: #065f46;
    }

    /* Action Buttons */
    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .btn-icon:hover {
        transform: translateY(-1px);
    }

    .btn-icon.btn-view {
        color: #0ea5e9;
        border-color: #bae6fd;
    }

    .btn-icon.btn-view:hover {
        background: #f0f9ff;
    }

    .btn-icon.btn-edit {
        color: #f59e0b;
        border-color: #fed7aa;
    }

    .btn-icon.btn-edit:hover {
        background: #fef3c7;
    }

    .btn-icon.btn-delete {
        color: #ef4444;
        border-color: #fecaca;
    }

    .btn-icon.btn-delete:hover {
        background: #fee2e2;
    }

    /* Table */
    .table-clean {
        width: 100%;
        border-collapse: collapse;
    }

    .table-clean thead tr th {
        padding: 12px 16px;
        font-size: 0.75rem;
        font-weight: 500;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
        background: #f8fafc;
    }

    .table-clean tbody tr {
        transition: all 0.15s ease;
    }

    .table-clean tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-clean tbody tr td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.875rem;
        color: #334155;
    }

    /* Filter Select */
    .filter-select {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 0.8rem;
        color: #475569;
        background: #ffffff;
    }

    .filter-select:focus {
        border-color: #0ea5e9;
        outline: none;
    }

    /* Badge */
    .badge-gender {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .min-page-header {
            padding: 16px 0;
            margin: -16px -16px 16px -16px;
        }

        .patient-card {
            padding: 12px;
        }

        .patient-avatar {
            width: 36px;
            height: 36px;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header - Match other pages -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-gradient-primary text-white rounded-3 p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="mb-2"><i class="bi bi-people me-2"></i>จัดการข้อมูลลูกค้า</h2>
                        <p class="mb-0 opacity-90">ระบบจัดการข้อมูลลูกค้า ประวัติการรักษา และข้อมูลสุขภาพ</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="{{ route('patients.create') }}" class="btn btn-light">
                            <i class="bi bi-plus-circle me-2"></i>ลูกค้าใหม่
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section - Clean & Simple -->
    <div class="min-search-section">
        <form method="GET" action="{{ route('patients.index') }}">
            <!-- Search Bar -->
            <div class="row g-2 mb-3">
                <div class="col-12 col-lg-6">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                        <input type="text"
                               name="search"
                               class="min-search-input"
                               style="padding-left: 36px;"
                               placeholder="ค้นหาชื่อ, เบอร์โทรศัพท์..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <button type="submit" class="btn w-100" style="background: #0ea5e9; color: white; padding: 10px; border-radius: 6px; font-size: 0.875rem;">
                        <i class="bi bi-search me-1"></i>ค้นหา
                    </button>
                </div>
                <div class="col-6 col-lg-2">
                    <a href="{{ route('patients.index') }}" class="btn w-100" style="background: #f1f5f9; color: #64748b; padding: 10px; border-radius: 6px; font-size: 0.875rem;">
                        <i class="bi bi-arrow-clockwise me-1"></i>รีเซ็ต
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <select name="filter" class="form-select filter-select" onchange="this.form.submit()">
                        <option value="">ลูกค้าทั้งหมด</option>
                        <option value="course" {{ request('filter') == 'course' ? 'selected' : '' }}>ลูกค้าคอร์ส</option>
                        <option value="normal" {{ request('filter') == 'normal' ? 'selected' : '' }}>ลูกค้าทั่วไป</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="gender" class="form-select filter-select" onchange="this.form.submit()">
                        <option value="">เพศทั้งหมด</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ชาย</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>หญิง</option>
                        <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>อื่นๆ</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="age_range" class="form-select filter-select" onchange="this.form.submit()">
                        <option value="">ทุกช่วงอายุ</option>
                        <option value="0-20" {{ request('age_range') == '0-20' ? 'selected' : '' }}>0-20 ปี</option>
                        <option value="21-40" {{ request('age_range') == '21-40' ? 'selected' : '' }}>21-40 ปี</option>
                        <option value="41-60" {{ request('age_range') == '41-60' ? 'selected' : '' }}>41-60 ปี</option>
                        <option value="60+" {{ request('age_range') == '60+' ? 'selected' : '' }}>60+ ปี</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="sort" class="form-select filter-select" onchange="this.form.submit()">
                        <option value="">เรียงตาม</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>ชื่อ (ก-ฮ)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>ชื่อ (ฮ-ก)</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>ล่าสุด</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>เก่าสุด</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters -->
            @if(request('search') || request('gender') || request('filter') || request('age_range') || request('sort'))
                <div class="mt-2 pt-2" style="border-top: 1px solid #f1f5f9;">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <small style="color: #64748b;">ฟิลเตอร์:</small>
                        @if(request('search'))
                            <span class="badge" style="background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 4px; font-size: 0.7rem;">
                                {{ request('search') }}
                            </span>
                        @endif
                        @if(request('filter'))
                            <span class="badge" style="background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 4px; font-size: 0.7rem;">
                                {{ request('filter') == 'course' ? 'คอร์ส' : 'ทั่วไป' }}
                            </span>
                        @endif
                        @if(request('gender'))
                            <span class="badge" style="background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 4px; font-size: 0.7rem;">
                                {{ request('gender') == 'male' ? 'ชาย' : (request('gender') == 'female' ? 'หญิง' : 'อื่นๆ') }}
                            </span>
                        @endif
                        @if(request('age_range'))
                            <span class="badge" style="background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 4px; font-size: 0.7rem;">
                                {{ request('age_range') }} ปี
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </form>
    </div>

    <!-- Mobile Card View (Clean Design) -->
    <div class="d-block d-md-none">
        @forelse($patients as $patient)
            <div class="patient-card">
                <div class="d-flex gap-3">
                    <!-- Avatar -->
                    <div class="patient-avatar {{ $patient->gender ?? 'other' }}">
                        {{ mb_substr($patient->name, 0, 1) }}
                    </div>

                    <!-- Patient Info -->
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1 fw-bold" style="color: #1e293b; font-size: 0.9rem;">{{ $patient->name }}</h6>
                                <div class="d-flex gap-2 flex-wrap">
                                    <small style="color: #0369a1;">
                                        HN: {{ $patient->hn }}
                                    </small>
                                    <small style="color: #64748b;">
                                        {{ $patient->gender == 'male' ? 'ชาย' : ($patient->gender == 'female' ? 'หญิง' : 'อื่นๆ') }}
                                    </small>
                                    @if($patient->age)
                                        <small style="color: #64748b;">
                                            {{ $patient->age }} ปี
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <!-- Icon Actions (Mobile) -->
                            <div class="d-flex gap-1">
                                <a href="{{ route('patients.show', $patient->id) }}" class="btn-icon btn-view" title="ดูข้อมูล">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('patients.edit', $patient->id) }}" class="btn-icon btn-edit" title="แก้ไข">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Phone with Call Button -->
                        <div>
                            <a href="tel:{{ $patient->phone }}" class="btn-call">
                                <i class="bi bi-telephone-fill"></i>
                                <span>{{ $patient->phone }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0" style="border: 1px solid #e2e8f0;">
                <div class="card-body text-center py-4">
                    <i class="bi bi-person-x fs-2" style="color: #cbd5e1;"></i>
                    <p class="mt-2 mb-0" style="color: #64748b; font-size: 0.875rem;">ไม่พบข้อมูลลูกค้า</p>
                </div>
            </div>
        @endforelse

        <!-- Mobile Pagination -->
        @if($patients->hasPages())
            <div class="card shadow-sm mt-3">
                <div class="card-body p-2">
                    {{ $patients->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Desktop Table View (Clean Professional Design) -->
    <div class="d-none d-md-block">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-clean mb-0">
                        <thead>
                            <tr>
                                <th width="100">HN</th>
                                <th>ชื่อลูกค้า</th>
                                <th width="140">เบอร์โทรศัพท์</th>
                                <th width="80" class="text-center">อายุ</th>
                                <th width="100" class="text-center">เพศ</th>
                                <th width="150">สาขา</th>
                                <th width="120" class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $patient)
                                <tr>
                                    <td>
                                        <span class="fw-medium" style="color: #0369a1;">{{ $patient->hn }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="patient-avatar {{ $patient->gender ?? 'other' }}">
                                                {{ mb_substr($patient->name, 0, 1) }}
                                            </div>
                                            <div class="fw-medium" style="color: #1e293b;">{{ $patient->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="tel:{{ $patient->phone }}" class="text-decoration-none" style="color: #334155;">
                                            <i class="bi bi-telephone" style="color: #10b981;"></i> {{ $patient->phone }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if($patient->age)
                                            <span style="color: #475569;">{{ $patient->age }} ปี</span>
                                        @else
                                            <span style="color: #94a3b8;">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($patient->gender == 'male')
                                            <span class="badge badge-gender" style="background: #e0f2fe; color: #0369a1;">
                                                ชาย
                                            </span>
                                        @elseif($patient->gender == 'female')
                                            <span class="badge badge-gender" style="background: #fce7f3; color: #be185d;">
                                                หญิง
                                            </span>
                                        @else
                                            <span class="badge badge-gender" style="background: #f1f5f9; color: #475569;">
                                                อื่นๆ
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="color: #475569;">{{ $patient->firstVisitBranch->name ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('patients.show', $patient->id) }}"
                                               class="btn-icon btn-view"
                                               title="ดูข้อมูล">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('patients.edit', $patient->id) }}"
                                               class="btn-icon btn-edit"
                                               title="แก้ไข">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn-icon btn-delete"
                                                    title="ลบ"
                                                    onclick="if(confirm('ต้องการลบข้อมูลลูกค้านี้?')) { document.getElementById('delete-form-{{ $patient->id }}').submit(); }">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $patient->id }}"
                                                  method="POST"
                                                  action="{{ route('patients.destroy', $patient->id) }}"
                                                  class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-person-x fs-2" style="color: #cbd5e1;"></i>
                                        <p class="mt-2 mb-0" style="color: #64748b; font-size: 0.875rem;">ไม่พบข้อมูลลูกค้า</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Desktop Pagination -->
            @if($patients->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            แสดง {{ $patients->firstItem() }} ถึง {{ $patients->lastItem() }} จาก {{ $patients->total() }} รายการ
                        </div>
                        {{ $patients->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
