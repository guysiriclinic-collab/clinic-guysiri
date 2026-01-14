@extends('layouts.app')

@section('title', 'จัดการผู้ใช้ - GCMS')

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--calm-blue-500, #3b82f6) 0%, var(--calm-blue-600, #2563eb) 100%);
    }
    .stat-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .stat-card.blue { border-left-color: var(--calm-blue-500, #3b82f6); }
    .stat-card.green { border-left-color: #10b981; }
    .stat-card.gray { border-left-color: #6b7280; }
    .btn-primary {
        background-color: var(--calm-blue-500, #3b82f6);
        border-color: var(--calm-blue-500, #3b82f6);
    }
    .btn-primary:hover {
        background-color: var(--calm-blue-600, #2563eb);
        border-color: var(--calm-blue-600, #2563eb);
    }
    .text-primary {
        color: var(--calm-blue-500, #3b82f6) !important;
    }
    .modal-header.bg-gradient-primary {
        background: linear-gradient(135deg, var(--calm-blue-500, #3b82f6) 0%, var(--calm-blue-600, #2563eb) 100%);
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
                        <h2 class="mb-2"><i class="bi bi-people me-2"></i>จัดการผู้ใช้</h2>
                        <p class="mb-0 opacity-90">จัดการบัญชีผู้ใช้งานทั้งหมดในระบบ</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openCreateModal()">
                            <i class="bi bi-plus-circle me-2"></i>เพิ่มผู้ใช้
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card stat-card blue h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">ผู้ใช้ทั้งหมด</h6>
                            <h3 class="mb-0 text-primary">{{ $users->total() }}</h3>
                        </div>
                        <div class="text-primary opacity-25">
                            <i class="bi bi-people fs-1"></i>
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
                            <h3 class="mb-0 text-success">{{ $users->where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="text-success opacity-25">
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card stat-card gray h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">ปิดใช้งาน</h6>
                            <h3 class="mb-0 text-secondary">{{ $users->where('is_active', false)->count() }}</h3>
                        </div>
                        <div class="text-secondary opacity-25">
                            <i class="bi bi-x-circle fs-1"></i>
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

    <!-- Users Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>รายการผู้ใช้</h5>
                <div class="input-group" style="width: 250px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="ค้นหาผู้ใช้...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ชื่อผู้ใช้</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>สาขา</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div class="fw-medium">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary">{{ $user->username }}</span></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role)
                                <span class="badge bg-info">{{ $user->role->name }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($user->branch)
                                {{ $user->branch->name }}
                                @else
                                <span class="text-muted">ทุกสาขา</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($user->is_active)
                                <span class="badge bg-success">เปิดใช้งาน</span>
                                @else
                                <span class="badge bg-secondary">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="openEditModal('{{ $user->id }}')" title="แก้ไข">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')" title="ลบ">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                ไม่พบรายการผู้ใช้
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer bg-white">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- User Modal (Create/Edit) -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="userForm" autocomplete="off">
                @csrf
                <input type="hidden" id="userId" name="user_id">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="modalTitle"><i class="bi bi-plus-circle me-2"></i>เพิ่มผู้ใช้ใหม่</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">รหัสผ่าน <span class="text-danger" id="passwordRequired">*</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted" id="passwordHint" style="display: none;">เว้นว่างหากไม่ต้องการเปลี่ยนรหัสผ่าน</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">บทบาท (Role) <span class="text-danger">*</span></label>
                            <select class="form-select" id="role_id" name="role_id" required>
                                <option value="">-- เลือกบทบาท --</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">สาขา</label>
                            <select class="form-select" id="branch_id" name="branch_id">
                                <option value="">-- ทุกสาขา --</option>
                                @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
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
                <p>คุณต้องการลบผู้ใช้ "<strong id="deleteUserName"></strong>" ใช่หรือไม่?</p>
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
    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    let deleteUserId = null;
    let isEditMode = false;

    // Open create modal
    window.openCreateModal = function() {
        isEditMode = false;
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-plus-circle me-2"></i>เพิ่มผู้ใช้ใหม่';
        document.getElementById('is_active').checked = true;
        document.getElementById('password').required = true;
        document.getElementById('passwordRequired').style.display = '';
        document.getElementById('passwordHint').style.display = 'none';
    };

    // Open edit modal
    window.openEditModal = function(id) {
        isEditMode = true;
        fetch(`{{ url('/users') }}/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('userId').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('username').value = data.username;
            document.getElementById('email').value = data.email;
            document.getElementById('password').value = '';
            document.getElementById('role_id').value = data.role_id || '';
            document.getElementById('branch_id').value = data.branch_id || '';
            document.getElementById('is_active').checked = data.is_active;

            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i>แก้ไขผู้ใช้';
            document.getElementById('password').required = false;
            document.getElementById('passwordRequired').style.display = 'none';
            document.getElementById('passwordHint').style.display = '';

            userModal.show();
        })
        .catch(error => {
            showAlert('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'danger');
        });
    };

    // Form submit
    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const userId = document.getElementById('userId').value;
        const formData = new FormData(this);

        let url = '{{ url('/users') }}';
        let method = 'POST';

        if (userId) {
            url = `{{ url('/users') }}/${userId}`;
            formData.append('_method', 'PUT');
        }

        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>กำลังบันทึก...';

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                userModal.hide();
                showAlert(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                let errorMsg = 'เกิดข้อผิดพลาด';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('<br>');
                } else if (data.message) {
                    errorMsg = data.message;
                }
                showAlert(errorMsg, 'danger');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>บันทึก';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let errorMsg = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
            if (error.errors) {
                errorMsg = Object.values(error.errors).flat().join('<br>');
            } else if (error.message) {
                errorMsg = error.message;
            }
            showAlert(errorMsg, 'danger');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>บันทึก';
        });
    });

    // Confirm delete
    window.confirmDelete = function(id, name) {
        deleteUserId = id;
        document.getElementById('deleteUserName').textContent = name;
        deleteModal.show();
    };

    // Delete user
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!deleteUserId) return;

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>กำลังลบ...';

        const formData = new FormData();
        formData.append('_method', 'DELETE');
        formData.append('_token', '{{ csrf_token() }}');

        fetch(`{{ url('/users') }}/${deleteUserId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                deleteModal.hide();
                showAlert(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(data.message || 'เกิดข้อผิดพลาดในการลบ', 'danger');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash me-2"></i>ลบ';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('เกิดข้อผิดพลาดในการลบข้อมูล', 'danger');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-trash me-2"></i>ลบ';
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
