@extends('layouts.app')

@section('title', 'จัดการบริการ - GCMS')

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
                        <h2 class="mb-2"><i class="bi bi-clipboard2-pulse me-2"></i>จัดการบริการ</h2>
                        <p class="mb-0 opacity-90">จัดการรายการบริการทั้งหมดของคลินิก</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#serviceModal" onclick="openCreateModal()">
                            <i class="bi bi-plus-circle me-2"></i>เพิ่มบริการ
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
                            <h6 class="text-muted mb-2">บริการทั้งหมด</h6>
                            <h3 class="mb-0 text-primary">{{ $services->total() }}</h3>
                        </div>
                        <div class="text-primary opacity-25">
                            <i class="bi bi-clipboard2-pulse fs-1"></i>
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
                            <h3 class="mb-0 text-success">{{ $services->where('is_active', true)->count() }}</h3>
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

    <!-- Services Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>รายการบริการ</h5>
                <div class="input-group" style="width: 250px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="ค้นหาบริการ...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>รหัส</th>
                            <th>ชื่อบริการ</th>
                            <th>หมวดหมู่</th>
                            <th class="text-end">ราคา (บาท)</th>
                            <th class="text-center">ค่ามือ (บาท)</th>
                            <th class="text-center">ระยะเวลา</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $service->code ?? '-' }}</span></td>
                            <td>
                                <div class="fw-medium">{{ $service->name }}</div>
                                @if($service->description)
                                <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $service->serviceCategory->name ?? '-' }}</td>
                            <td class="text-end fw-medium">{{ number_format($service->default_price, 0) }}</td>
                            <td class="text-center">
                                @if($service->default_df_rate)
                                <span class="badge bg-warning text-dark">฿{{ number_format($service->default_df_rate, 0) }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $service->default_duration_minutes ?? '-' }} นาที</td>
                            <td class="text-center">
                                @if($service->is_active)
                                <span class="badge bg-success">เปิด</span>
                                @else
                                <span class="badge bg-secondary">ปิด</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="openEditModal('{{ $service->id }}')" title="แก้ไข">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ $service->id }}', '{{ $service->name }}')" title="ลบ">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                ไม่พบรายการบริการ
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($services->hasPages())
        <div class="card-footer bg-white">
            {{ $services->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Service Modal (Create/Edit) -->
<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="serviceForm" autocomplete="off">
                @csrf
                <input type="hidden" id="serviceId" name="service_id">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="modalTitle"><i class="bi bi-plus-circle me-2"></i>เพิ่มบริการใหม่</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">ชื่อบริการ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">รหัสบริการ</label>
                            <div class="input-group">
                                <span class="input-group-text">GSR</span>
                                <input type="text" class="form-control" id="code_number" name="code_number" placeholder="001">
                            </div>
                            <input type="hidden" id="code" name="code">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">หมวดหมู่ <span class="text-danger">*</span></label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">-- เลือกหมวดหมู่ --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ราคา (บาท) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="default_price" name="default_price" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ระยะเวลา (นาที)</label>
                            <input type="number" class="form-control" id="default_duration_minutes" name="default_duration_minutes" min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ค่ามือ PT (บาท)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="default_df_rate" name="default_df_rate" step="0.01" min="0" placeholder="เช่น 30">
                                <span class="input-group-text">บาท</span>
                            </div>
                            <small class="text-muted">จำนวนเงินที่ PT ได้รับต่อครั้ง</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">รายละเอียด</label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
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
                        <i class="bi bi-check-circle me-2"></i>บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>ยืนยันการลบ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>คุณต้องการลบบริการ "<strong id="deleteServiceName"></strong>" ใช่หรือไม่?</p>
                <p class="text-muted mb-0">การดำเนินการนี้ไม่สามารถย้อนกลับได้</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="bi bi-trash me-2"></i>ลบ
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    let deleteServiceId = null;

    // Open create modal
    window.openCreateModal = function() {
        document.getElementById('serviceForm').reset();
        document.getElementById('serviceId').value = '';
        document.getElementById('code_number').value = '';
        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-plus-circle me-2"></i>เพิ่มบริการใหม่';
        document.getElementById('is_active').checked = true;
    };

    // Open edit modal
    window.openEditModal = function(id) {
        fetch(`/services/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('serviceId').value = data.id;
            document.getElementById('name').value = data.name;
            // Extract number from GSR code (e.g., GSR001 -> 001)
            let codeNumber = data.code || '';
            if (codeNumber.startsWith('GSR')) {
                codeNumber = codeNumber.substring(3);
            }
            document.getElementById('code_number').value = codeNumber;
            document.getElementById('category_id').value = data.category_id || '';
            document.getElementById('default_price').value = data.default_price;
            document.getElementById('default_duration_minutes').value = data.default_duration_minutes || '';
            document.getElementById('default_df_rate').value = data.default_df_rate || '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('is_active').checked = data.is_active;

            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i>แก้ไขบริการ';
            serviceModal.show();
        })
        .catch(error => {
            showAlert('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'danger');
        });
    };

    // Form submit
    document.getElementById('serviceForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const serviceId = document.getElementById('serviceId').value;
        const formData = new FormData(this);

        // Combine GSR prefix with number
        const codeNumber = document.getElementById('code_number').value.trim();
        if (codeNumber) {
            formData.set('code', 'GSR' + codeNumber);
        } else {
            formData.set('code', '');
        }

        let url = '/services';
        let method = 'POST';

        if (serviceId) {
            url = `/services/${serviceId}`;
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                serviceModal.hide();
                showAlert(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('เกิดข้อผิดพลาด', 'danger');
            }
        })
        .catch(error => {
            if (error.errors) {
                const messages = Object.values(error.errors).flat().join('<br>');
                showAlert(messages, 'danger');
            } else {
                showAlert('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'danger');
            }
        });
    });

    // Confirm delete
    window.confirmDelete = function(id, name) {
        deleteServiceId = id;
        document.getElementById('deleteServiceName').textContent = name;
        deleteModal.show();
    };

    // Delete service
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!deleteServiceId) return;

        fetch(`/services/${deleteServiceId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                deleteModal.hide();
                showAlert(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('เกิดข้อผิดพลาดในการลบ', 'danger');
            }
        })
        .catch(error => {
            showAlert('เกิดข้อผิดพลาดในการลบข้อมูล', 'danger');
        });
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Show alert
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
});
</script>
@endpush
@endsection
