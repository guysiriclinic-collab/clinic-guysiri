<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    try {
        switch ($_POST['action']) {
            case 'get_users':
                $users = \App\Models\User::with(['role', 'branch'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                echo json_encode(['success' => true, 'users' => $users]);
                break;

            case 'get_user':
                $user = \App\Models\User::with(['role', 'branch'])->findOrFail($_POST['user_id']);
                echo json_encode(['success' => true, 'user' => $user]);
                break;

            case 'create_user':
                $data = [
                    'username' => $_POST['username'],
                    'password' => Hash::make($_POST['password']),
                    'name' => $_POST['name'],
                    'email' => $_POST['email'] ?? null,
                    'role_id' => $_POST['role_id'],
                    'branch_id' => $_POST['branch_id'],
                    'phone' => $_POST['phone'] ?? null,
                    'is_active' => isset($_POST['is_active']) ? 1 : 0,
                ];

                $user = \App\Models\User::create($data);
                echo json_encode(['success' => true, 'message' => 'สร้างผู้ใช้สำเร็จ', 'user' => $user]);
                break;

            case 'update_user':
                $user = \App\Models\User::findOrFail($_POST['user_id']);

                $data = [
                    'username' => $_POST['username'],
                    'name' => $_POST['name'],
                    'email' => $_POST['email'] ?? null,
                    'role_id' => $_POST['role_id'],
                    'branch_id' => $_POST['branch_id'],
                    'phone' => $_POST['phone'] ?? null,
                    'is_active' => isset($_POST['is_active']) ? 1 : 0,
                ];

                // Update password only if provided
                if (!empty($_POST['password'])) {
                    $data['password'] = Hash::make($_POST['password']);
                }

                $user->update($data);
                echo json_encode(['success' => true, 'message' => 'อัปเดตผู้ใช้สำเร็จ']);
                break;

            case 'delete_user':
                $user = \App\Models\User::findOrFail($_POST['user_id']);

                // Prevent deleting admin
                if ($user->username === 'admin') {
                    echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบ Admin ได้']);
                    exit;
                }

                $user->delete();
                echo json_encode(['success' => true, 'message' => 'ลบผู้ใช้สำเร็จ']);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// Get roles and branches for dropdowns
$roles = \App\Models\Role::all();
$branches = \App\Models\Branch::all();

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin - จัดการผู้ใช้งาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container-custom {
            max-width: 1400px;
            margin: 0 auto;
        }
        .header-card {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .main-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .btn-custom {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }
        .table-custom {
            border-radius: 10px;
            overflow: hidden;
        }
        .badge-custom {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
        }
        .modal-content {
            border-radius: 15px;
            border: none;
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e5e7eb;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <!-- Header -->
        <div class="header-card text-center">
            <h1 class="mb-2"><i class="bi bi-shield-lock-fill me-2"></i>Super Admin Control Panel</h1>
            <p class="mb-0 opacity-75">ระบบจัดการผู้ใช้งานระดับสูงสุด - เพิ่ม/ลบ/แก้ไข Admin และพนักงานทั้งหมด</p>
        </div>

        <!-- Main Content -->
        <div class="main-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="bi bi-people-fill me-2"></i>จัดการผู้ใช้งานทั้งหมด</h3>
                <button class="btn btn-primary btn-custom" onclick="showAddModal()">
                    <i class="bi bi-plus-lg me-1"></i>เพิ่มผู้ใช้ใหม่
                </button>
            </div>

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table table-hover table-custom" id="usersTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">ชื่อผู้ใช้</th>
                            <th width="20%">ชื่อ-นามสกุล</th>
                            <th width="15%">อีเมล</th>
                            <th width="12%">Role</th>
                            <th width="12%">สาขา</th>
                            <th width="10%">สถานะ</th>
                            <th width="11%" class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">เพิ่มผู้ใช้ใหม่</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="user_id" name="user_id">
                        <input type="hidden" id="form_action" name="action" value="create_user">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ชื่อผู้ใช้ (Username) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">รหัสผ่าน <span class="text-danger" id="password_required">*</span></label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small class="text-muted" id="password_hint" style="display:none;">เว้นว่างไว้หากไม่ต้องการเปลี่ยน</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">อีเมล</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" id="role_id" name="role_id" required>
                                    <option value="">-- เลือก Role --</option>
                                    <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role->id ?>"><?= $role->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">สาขา <span class="text-danger">*</span></label>
                                <select class="form-select" id="branch_id" name="branch_id" required>
                                    <option value="">-- เลือกสาขา --</option>
                                    <?php foreach ($branches as $branch): ?>
                                    <option value="<?= $branch->id ?>"><?= $branch->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">เบอร์โทร</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">เปิดใช้งาน</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-custom" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary btn-custom" onclick="saveUser()">
                        <i class="bi bi-save me-1"></i>บันทึก
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let userModal;

        $(document).ready(function() {
            userModal = new bootstrap.Modal($('#userModal'));
            loadUsers();
        });

        // Load all users
        function loadUsers() {
            $.post('', { action: 'get_users' }, function(response) {
                if (response.success) {
                    renderUsers(response.users);
                } else {
                    showError('เกิดข้อผิดพลาด: ' + response.message);
                }
            });
        }

        // Render users table
        function renderUsers(users) {
            const tbody = $('#usersTable tbody');
            tbody.empty();

            if (users.length === 0) {
                tbody.append('<tr><td colspan="8" class="text-center text-muted py-4">ไม่มีข้อมูล</td></tr>');
                return;
            }

            users.forEach((user, index) => {
                const statusBadge = user.is_active == 1
                    ? '<span class="badge badge-custom bg-success">ใช้งาน</span>'
                    : '<span class="badge badge-custom bg-danger">ปิดใช้งาน</span>';

                const roleName = user.role ? user.role.name : '-';
                const branchName = user.branch ? user.branch.name : '-';

                const isAdmin = user.username === 'admin';
                const deleteBtn = isAdmin
                    ? '<button class="btn btn-sm btn-secondary" disabled title="ไม่สามารถลบ Admin"><i class="bi bi-trash"></i></button>'
                    : `<button class="btn btn-sm btn-danger" onclick="deleteUser('${user.id}', '${user.username}')" title="ลบ"><i class="bi bi-trash"></i></button>`;

                tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td><strong>${user.username}</strong></td>
                        <td>${user.name}</td>
                        <td>${user.email || '-'}</td>
                        <td><span class="badge badge-custom bg-primary">${roleName}</span></td>
                        <td>${branchName}</td>
                        <td>${statusBadge}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" onclick="editUser('${user.id}')" title="แก้ไข">
                                <i class="bi bi-pencil"></i>
                            </button>
                            ${deleteBtn}
                        </td>
                    </tr>
                `);
            });
        }

        // Show add modal
        function showAddModal() {
            $('#modalTitle').text('เพิ่มผู้ใช้ใหม่');
            $('#userForm')[0].reset();
            $('#user_id').val('');
            $('#form_action').val('create_user');
            $('#password').prop('required', true);
            $('#password_required').show();
            $('#password_hint').hide();
            $('#is_active').prop('checked', true);
            userModal.show();
        }

        // Edit user
        function editUser(userId) {
            $.post('', { action: 'get_user', user_id: userId }, function(response) {
                if (response.success) {
                    const user = response.user;
                    $('#modalTitle').text('แก้ไขผู้ใช้');
                    $('#user_id').val(user.id);
                    $('#form_action').val('update_user');
                    $('#username').val(user.username);
                    $('#name').val(user.name);
                    $('#email').val(user.email);
                    $('#role_id').val(user.role_id);
                    $('#branch_id').val(user.branch_id);
                    $('#phone').val(user.phone);
                    $('#is_active').prop('checked', user.is_active == 1);
                    $('#password').val('').prop('required', false);
                    $('#password_required').hide();
                    $('#password_hint').show();
                    userModal.show();
                } else {
                    showError('เกิดข้อผิดพลาด: ' + response.message);
                }
            });
        }

        // Save user (create or update)
        function saveUser() {
            const formData = $('#userForm').serialize();

            $.post('', formData, function(response) {
                if (response.success) {
                    alert(response.message);
                    userModal.hide();
                    loadUsers();
                } else {
                    showError('เกิดข้อผิดพลาด: ' + response.message);
                }
            });
        }

        // Delete user
        function deleteUser(userId, username) {
            if (!confirm(`ต้องการลบผู้ใช้ "${username}" ใช่หรือไม่?\n\n⚠️ การลบจะไม่สามารถกู้คืนได้!`)) {
                return;
            }

            $.post('', { action: 'delete_user', user_id: userId }, function(response) {
                if (response.success) {
                    alert(response.message);
                    loadUsers();
                } else {
                    showError('เกิดข้อผิดพลาด: ' + response.message);
                }
            });
        }

        // Show error
        function showError(message) {
            alert(message);
        }
    </script>
</body>
</html>
