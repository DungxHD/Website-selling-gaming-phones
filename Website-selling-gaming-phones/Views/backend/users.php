<?php $users = $data['users'] ?? []; ?>
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
        <div class="admin-top">
            <div>
                <span class="eyebrow">Tài khoản</span>
                <h1>Quản lý người dùng</h1>
                <p class="admin-page-note">Xem nhanh thông tin tài khoản và khóa hoặc mở khóa người dùng bằng các thao tác cơ bản.</p>
            </div>
            <button class="btn btn-soft" type="button"><i class="fa-solid fa-user-lock"></i> Khóa / mở tài khoản</button>
        </div>

        <?php if (!empty($data['flash'])): ?>
            <div class="flash flash-<?= e($data['flash']['type']) ?>"><?= e($data['flash']['message']) ?></div>
        <?php endif; ?>

        <section class="admin-card">
            <div class="admin-card-head">
                <h2>Danh sách người dùng từ cơ sở dữ liệu</h2>
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
                                    <div><strong><?= e($user['name']) ?></strong><small><?= e($user['username']) ?></small></div>
                                </div>
                            </td>
                            <td><?= e($user['contact']) ?></td>
                            <td><span class="role-pill <?= e($user['role']) ?>"><?= e($user['role']) ?></span></td>
                            <td><span class="status-pill <?= $isActive ? 'active' : 'locked' ?>"><?= $isActive ? 'Đang hoạt động' : 'Đã bị khóa' ?></span></td>
                            <td>
                                <form method="post" action="index.php?page=admin_users&action=admin_user_toggle" class="inline-order-form">
                                    <input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
                                    <button type="submit" class="btn btn-soft btn-sm">
                                        <i class="fa-solid <?= $isActive ? 'fa-lock' : 'fa-unlock' ?>"></i>
                                        <?= $isActive ? 'Khóa' : 'Mở khóa' ?>
                                    </button>
                                </form>
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
