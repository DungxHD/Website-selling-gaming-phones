<?php
$users    = $data['users']    ?? [];
$editUser = $data['editUser'] ?? null;
$mode     = $data['mode']     ?? 'list'; // 'list', 'add', 'edit'

// Xác định tiêu đề và URL action của form
$isEditing = in_array($mode, ['add', 'edit'], true);
$formTitle = $mode === 'edit' ? 'Sửa tài khoản' : 'Thêm tài khoản mới';
$formAction = $mode === 'edit'
    ? 'index.php?page=admin_user_update&id=' . (int)($editUser['id'] ?? 0)
    : 'index.php?page=admin_user_add';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý người dùng</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
<div class="admin-layout">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>
    <main class="admin-main">

        <!-- ========== HEADER TRANG ========== -->
        <div class="admin-top">
            <div>
                <span class="eyebrow">Tài khoản</span>
                <h1>Quản lý người dùng</h1>
                <p class="admin-page-note">Quản lý thông tin, phân quyền và khóa/mở khóa tài khoản.</p>
            </div>
            <?php if (!$isEditing): ?>
                <a href="index.php?page=admin_user_add" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Thêm tài khoản
                </a>
            <?php else: ?>
                <a href="index.php?page=admin_users" class="btn btn-soft">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($data['flash'])): ?>
            <div class="flash flash-<?= e($data['flash']['type']) ?>"><?= e($data['flash']['message']) ?></div>
        <?php endif; ?>

        <!-- ========== FORM THÊM / SỬA (chỉ hiện khi mode = add/edit) ========== -->
        <?php if ($isEditing): ?>
        <section class="admin-card admin-form-card">
            <div class="admin-card-head">
                <h2><i class="fa-solid <?= $mode === 'edit' ? 'fa-pen' : 'fa-user-plus' ?>"></i> <?= e($formTitle) ?></h2>
            </div>
            <form method="post" action="<?= e($formAction) ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="username"
                               value="<?= e($editUser['username'] ?? '') ?>"
                               required <?= $mode === 'edit' ? 'readonly' : '' ?>>
                        <?php if ($mode === 'edit'): ?>
                            <small class="text-muted">Không thể đổi tên đăng nhập khi sửa.</small>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            Mật khẩu
                            <?php if ($mode === 'edit'): ?>
                                <small class="text-muted">(Để trống nếu không đổi)</small>
                            <?php else: ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <input class="form-control" type="password" name="password"
                               <?= $mode === 'add' ? 'required' : '' ?>>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tên hiển thị <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="name"
                               value="<?= e($editUser['name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Liên hệ (SĐT/Email)</label>
                        <input class="form-control" type="text" name="contact"
                               value="<?= e($editUser['contact'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Vai trò</label>
                        <select class="form-select" name="role">
                            <option value="user"  <?= ($editUser['role'] ?? 'user') === 'user'  ? 'selected' : '' ?>>Người dùng (User)</option>
                            <option value="admin" <?= ($editUser['role'] ?? '')      === 'admin' ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="is_active">
                            <option value="1" <?= ((int)($editUser['is_active'] ?? 1) === 1) ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= ((int)($editUser['is_active'] ?? 0) === 0) ? 'selected' : '' ?>>Khóa</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2 justify-content-end">
                    <a href="index.php?page=admin_users" class="btn btn-secondary">
                        <i class="fa-solid fa-xmark"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> <?= $mode === 'add' ? 'Thêm mới' : 'Cập nhật' ?>
                    </button>
                </div>
            </form>
        </section>
        <?php endif; ?>

        <!-- ========== DANH SÁCH USER ========== -->
        <section class="admin-card">
            <div class="admin-card-head">
                <h2>Danh sách người dùng</h2>
                <span><?= count($users) ?> tài khoản</span>
            </div>
            <div class="admin-table-wrap table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Liên hệ</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <?php $isActive = (int)($user['is_active'] ?? 1) === 1; ?>
                            <tr>
                                <td>#<?= (int)$user['id'] ?></td>
                                <td>
                                    <div class="user-cell">
                                        <span><?= strtoupper(substr((string)$user['name'], 0, 1)) ?></span>
                                        <div>
                                            <strong><?= e($user['name']) ?></strong>
                                            <small>@<?= e($user['username']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= e($user['contact']) ?></td>
                                <td><span class="role-pill <?= e($user['role']) ?>"><?= e($user['role']) ?></span></td>
                                <td>
                                    <span class="status-pill <?= $isActive ? 'active' : 'locked' ?>">
                                        <?= $isActive ? 'Hoạt động' : 'Đã khóa' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-actions admin-actions-inline">
                                        <!-- Nút Sửa -->
                                        <a class="icon-action"
                                           href="index.php?page=admin_user_update&id=<?= (int)$user['id'] ?>"
                                           title="Sửa">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <!-- Nút Toggle (Khóa/Mở) -->
                                        <form method="post" action="index.php?page=admin_users" class="inline-order-form">
                                            <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
                                            <input type="hidden" name="action" value="toggle">
                                            <button type="submit" class="icon-action"
                                                    title="<?= $isActive ? 'Khóa' : 'Mở khóa' ?>">
                                                <i class="fa-solid <?= $isActive ? 'fa-lock' : 'fa-unlock' ?>"></i>
                                            </button>
                                        </form>
                                        <!-- Nút Xóa -->
                                        <form method="post" action="index.php?page=admin_users"
                                              class="inline-order-form"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản này?');">
                                            <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="icon-action danger" title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>