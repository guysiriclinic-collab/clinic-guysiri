@extends('layouts.app')

@section('title', 'จัดการแพ็คเกจคอร์ส - GCMS')

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--theme-ocean-600), var(--theme-sky-500));
    }
    .stat-card {
        border-left: 4px solid;
        transition: transform 0.2s;
        border-radius: 12px;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.1);
    }
    .stat-card.purple { border-left-color: var(--theme-ocean-600); }
    .stat-card.green { border-left-color: var(--theme-green-500); }
    .stat-card.blue { border-left-color: var(--theme-sky-500); }

    .table thead tr {
        background: linear-gradient(90deg, rgba(14, 165, 233, 0.1), rgba(37, 99, 235, 0.1));
        border-bottom: 2px solid var(--theme-ocean-600);
    }

    .table tbody tr:hover {
        background-color: rgba(14, 165, 233, 0.05);
    }

    .btn-outline-primary:hover {
        background: var(--theme-ocean-600);
        border-color: var(--theme-ocean-600);
    }

    .card {
        border-radius: 12px;
    }

    .badge {
        padding: 0.375rem 0.625rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-gradient-primary text-white rounded-3 p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="mb-2"><i class="bi bi-box-seam me-2"></i>จัดการแพ็คเกจคอร์ส</h2>
                        <p class="mb-0 opacity-90">จัดการแพ็คเกจคอร์สทั้งหมดของคลินิก</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#packageModal" onclick="resetForm()">
                            <i class="bi bi-plus-circle me-2"></i>เพิ่มแพ็คเกจ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card stat-card purple h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">แพ็คเกจทั้งหมด</h6>
                            <h3 class="mb-0 text-primary">{{ $packages->total() }}</h3>
                        </div>
                        <div class="text-primary opacity-25">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card stat-card green h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">เปิดใช้งาน</h6>
                            <h3 class="mb-0 text-success">{{ $packages->where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="text-success opacity-25">
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Packages Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>รายการแพ็คเกจคอร์ส</h5>
                <div class="input-group" style="width: 250px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="ค้นหาแพ็คเกจ...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>รหัส</th>
                            <th>ชื่อแพ็คเกจ</th>
                            <th>บริการหลัก</th>
                            <th class="text-center">จำนวนครั้ง</th>
                            <th class="text-end">ราคา (บาท)</th>
                            <th class="text-center">ค่ามือ/ครั้ง</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $package->code ?? '-' }}</span></td>
                            <td>
                                <div class="fw-medium">{{ $package->name }}</div>
                                @if($package->description)
                                <small class="text-muted">{{ Str::limit($package->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background: var(--theme-sky-100); color: var(--theme-navy-600);">
                                    {{ $package->service->name ?? 'ไม่ระบุ' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="badge bg-primary mb-1">{{ $package->total_sessions }} ครั้ง</span>
                                    @if($package->paid_sessions ?? 0 || $package->bonus_sessions ?? 0)
                                        <small class="text-muted">
                                            จ่าย: {{ $package->paid_sessions ?? $package->total_sessions }}
                                            @if($package->bonus_sessions ?? 0)
                                                + แถม: {{ $package->bonus_sessions }}
                                            @endif
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end fw-medium">{{ number_format($package->price, 0) }}</td>
                            <td class="text-center">
                                @if($package->df_amount)
                                <span class="badge bg-warning text-dark">฿{{ number_format($package->df_amount, 0) }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($package->is_active)
                                    <span class="badge bg-success">ใช้งาน</span>
                                @else
                                    <span class="badge bg-secondary">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                        onclick="editPackage({{ json_encode($package) }})"
                                        data-bs-toggle="modal" data-bs-target="#packageModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete('{{ $package->id }}', '{{ $package->name }}')"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2" style="color: var(--theme-sky-300);"></i>
                                ไม่พบข้อมูลแพ็คเกจคอร์ส
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($packages->hasPages())
        <div class="card-footer bg-white">
            {{ $packages->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="packageForm" method="POST" action="/course-packages" autocomplete="off">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="packageModalLabel">
                        <i class="bi bi-box-seam me-2"></i>
                        <span id="modalTitle">เพิ่มแพ็คเกจคอร์ส</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label">ชื่อแพ็คเกจ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="code" class="form-label">รหัส</label>
                            <div class="input-group">
                                <span class="input-group-text">CGSR</span>
                                <input type="text" class="form-control" id="code_number" name="code_number" placeholder="001">
                            </div>
                            <input type="hidden" id="code" name="code">
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">รายละเอียด</label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="service_id" class="form-label">บริการหลัก <span class="text-danger">*</span></label>
                            <select class="form-select" id="service_id" name="service_id" required>
                                <option value="">-- เลือกบริการหลัก --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="price" class="form-label">ราคา (บาท) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                        </div>

                        <div class="col-md-4">
                            <label for="paid_sessions" class="form-label">จำนวนครั้งที่จ่าย <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="paid_sessions" name="paid_sessions" min="1" required onchange="updateTotalSessions()">
                        </div>

                        <div class="col-md-4">
                            <label for="bonus_sessions" class="form-label">จำนวนครั้งแถม (Bonus)</label>
                            <input type="number" class="form-control" id="bonus_sessions" name="bonus_sessions" min="0" value="0" onchange="updateTotalSessions()">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">รวมทั้งหมด</label>
                            <div class="form-control bg-light" id="total_display">0 ครั้ง</div>
                        </div>

                        <div class="col-md-6">
                            <label for="commission_rate" class="form-label">ค่าคอมมิชชั่นคนขาย (%)</label>
                            <input type="number" class="form-control" id="commission_rate" name="commission_rate" step="0.01" min="0" max="100">
                        </div>

                        <div class="col-md-6">
                            <label for="per_session_commission_rate" class="form-label">ค่ามือต่อครั้ง (บาท)</label>
                            <input type="number" class="form-control" id="per_session_commission_rate" name="per_session_commission_rate" step="0.01" min="0">
                            <small class="text-muted">จำนวนเงินที่ผู้ทำหัตถการได้รับต่อครั้ง</small>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">เปิดใช้งาน</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    ยืนยันการลบ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>คุณต้องการลบแพ็คเกจ <strong id="deletePackageName"></strong> ใช่หรือไม่?</p>
                <p class="text-muted small">การดำเนินการนี้ไม่สามารถย้อนกลับได้</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> ลบ
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function resetForm() {
        document.getElementById('packageForm').reset();
        document.getElementById('packageForm').action = "/course-packages";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('modalTitle').textContent = 'เพิ่มแพ็คเกจคอร์ส';
        document.getElementById('is_active').checked = true;
        document.getElementById('total_display').textContent = '0 ครั้ง';
        document.getElementById('code_number').value = '';
    }

    // Combine CGSR prefix with number before submit
    document.getElementById('packageForm').addEventListener('submit', function(e) {
        const codeNumber = document.getElementById('code_number').value.trim();
        if (codeNumber) {
            document.getElementById('code').value = 'CGSR' + codeNumber;
        } else {
            document.getElementById('code').value = '';
        }
    });

    function editPackage(package) {
        document.getElementById('packageForm').action = "{{ url('course-packages') }}/" + package.id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('modalTitle').textContent = 'แก้ไขแพ็คเกจคอร์ส';

        document.getElementById('name').value = package.name || '';
        // Extract number from CGSR code (e.g., CGSR001 -> 001)
        let codeNumber = package.code || '';
        if (codeNumber.startsWith('CGSR')) {
            codeNumber = codeNumber.substring(4);
        }
        document.getElementById('code_number').value = codeNumber;
        document.getElementById('description').value = package.description || '';
        document.getElementById('service_id').value = package.service_id || '';
        document.getElementById('price').value = package.price || '';

        // Set paid and bonus sessions
        const paidSessions = package.paid_sessions || package.total_sessions || 0;
        const bonusSessions = package.bonus_sessions || 0;
        document.getElementById('paid_sessions').value = paidSessions;
        document.getElementById('bonus_sessions').value = bonusSessions;
        updateTotalSessions();

        document.getElementById('commission_rate').value = package.commission_rate || '';
        document.getElementById('per_session_commission_rate').value = package.df_amount || package.per_session_commission_rate || '';
        document.getElementById('is_active').checked = package.is_active;
    }

    function updateTotalSessions() {
        const paid = parseInt(document.getElementById('paid_sessions').value) || 0;
        const bonus = parseInt(document.getElementById('bonus_sessions').value) || 0;
        const total = paid + bonus;
        document.getElementById('total_display').textContent = total + ' ครั้ง';
    }

    function confirmDelete(id, name) {
        document.getElementById('deleteForm').action = "{{ url('course-packages') }}/" + id;
        document.getElementById('deletePackageName').textContent = name;
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endpush
