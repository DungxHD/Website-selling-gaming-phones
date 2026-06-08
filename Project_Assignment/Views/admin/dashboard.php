<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Gaming Phone Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        .admin-sidebar {
            background: linear-gradient(180deg, #001a33, #003366);
            color: white;
            padding: 20px 0;
        }
        .admin-sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .admin-sidebar-header h2 {
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-nav ul {
            list-style: none;
        }
        .admin-nav li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            transition: var(--transition);
        }
        .admin-nav li a:hover,
        .admin-nav li a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 3px solid var(--accent-color);
        }
        .admin-content {
            background: var(--bg-color);
            padding: 30px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: var(--bg-card);
            padding: 25px;
            border-radius: 15px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        .stat-icon.blue { background: rgba(0, 102, 204, 0.1); color: var(--primary-color); }
        .stat-icon.green { background: rgba(0, 204, 102, 0.1); color: var(--success-color); }
        .stat-icon.orange { background: rgba(255, 170, 0, 0.1); color: var(--warning-color); }
        .stat-icon.purple { background: rgba(153, 102, 255, 0.1); color: #9966ff; }
        .stat-info h3 {
            font-size: 28px;
            color: var(--text-primary);
            margin-bottom: 5px;
        }
        .stat-info p {
            color: var(--text-secondary);
            font-size: 14px;
        }
        .recent-section {
            background: var(--bg-card);
            padding: 25px;
            border-radius: 15px;
            box-shadow: var(--shadow);
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        .data-table th {
            background: var(--bg-color);
            color: var(--text-primary);
            font-weight: 600;
        }
        .data-table tr:hover {
            background: var(--bg-color);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-badge.success { background: rgba(0, 204, 102, 0.1); color: var(--success-color); }
        .status-badge.warning { background: rgba(255, 170, 0, 0.1); color: var(--warning-color); }
        .status-badge.danger { background: rgba(255, 51, 102, 0.1); color: var(--danger-color); }
        .action-btns {
            display: flex;
            gap: 8px;
        }
        .action-btns button {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: var(--transition);
        }
        .btn-edit { background: var(--primary-color); color: white; }
        .btn-delete { background: var(--danger-color); color: white; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <h2>
                    <i class="fas fa-gamepad" style="color: var(--accent-color);"></i>
                    GAMING ADMIN
                </h2>
            </div>
            
            <nav class="admin-nav">
                <ul>
                    <li><a href="?controller=admin&action=dashboard" class="active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                    <li><a href="?controller=admin&action=products"><i class="fas fa-box"></i> Sản phẩm</a></li>
                    <li><a href="?controller=admin&action=orders"><i class="fas fa-shopping-bag"></i> Đơn hàng</a></li>
                    <li><a href="?controller=admin&action=users"><i class="fas fa-users"></i> Người dùng</a></li>
                    <li><a href="../index.php"><i class="fas fa-home"></i> Về trang chủ</a></li>
                    <li><a href="?controller=user&action=logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <div class="admin-header">
                <div>
                    <h1 style="color: var(--text-primary);">Dashboard</h1>
                    <p style="color: var(--text-secondary);">Tổng quan hệ thống</p>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span style="color: var(--text-secondary);">Xin chào, <strong><?php echo htmlspecialchars($_SESSION['user']['full_name']); ?></strong></span>
                    <div class="theme-toggle" id="themeToggle" style="background: rgba(0,0,0,0.1);">
                        <i class="fas fa-moon"></i>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $totalProducts; ?></h3>
                        <p>Tổng sản phẩm</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $totalOrders; ?></h3>
                        <p>Đơn hàng</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $totalUsers; ?></h3>
                        <p>Người dùng</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo number_format($revenue, 0, ',', '.'); ?>₫</h3>
                        <p>Doanh thu</p>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="recent-section">
                <div class="section-header">
                    <h3 style="color: var(--text-primary);"><i class="fas fa-clock"></i> Sản phẩm mới thêm</h3>
                    <a href="?controller=admin&action=addProduct" class="btn btn-primary" style="font-size: 14px; padding: 8px 20px;">
                        <i class="fas fa-plus"></i> Thêm mới
                    </a>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentProducts)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-light);">
                                Chưa có sản phẩm nào
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($recentProducts as $product): ?>
                            <tr>
                                <td>#<?php echo $product['id']; ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo number_format($product['price'], 0, ',', '.'); ?>₫</td>
                                <td><?php echo $product['stock']; ?></td>
                                <td>
                                    <?php if ($product['stock'] > 10): ?>
                                        <span class="status-badge success">Còn hàng</span>
                                    <?php elseif ($product['stock'] > 0): ?>
                                        <span class="status-badge warning">Sắp hết</span>
                                    <?php else: ?>
                                        <span class="status-badge danger">Hết hàng</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <button class="btn-edit" onclick="window.location.href='?controller=admin&action=editProduct&id=<?php echo $product['id']; ?>'">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-delete" onclick="deleteProduct(<?php echo $product['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
