<?php
$products = $data['products'] ?? [];
$editingProduct = $data['editingProduct'] ?? null;
$searchKeyword = $data['searchKeyword'] ?? '';
$selectedBrand = $data['selectedBrand'] ?? '';
$selectedSort = $data['selectedSort'] ?? 'newest';
$brandOptions = $data['brandOptions'] ?? [];

// Tự động xác định URL action và text nút bấm
$actionUrl = $editingProduct ? 'index.php?page=admin_product_update' : 'index.php?page=admin_add_products';
$buttonText = $editingProduct ? 'Cập nhật sản phẩm' : 'Thêm sản phẩm mới';
$shouldOpenModal = !empty($editingProduct);

// Gom mô tả bộ lọc đang chạy để admin dễ kiểm tra dữ liệu đã lọc
$activeFilterNotes = [];
if ($searchKeyword !== '') {
    $activeFilterNotes[] = 'Từ khóa: "' . e($searchKeyword) . '"';
}
if ($selectedBrand !== '') {
    $activeFilterNotes[] = 'Hãng: ' . e($selectedBrand);
}
if ($selectedSort === 'price_asc') {
    $activeFilterNotes[] = 'Sắp xếp: Giá tăng dần';
} elseif ($selectedSort === 'price_desc') {
    $activeFilterNotes[] = 'Sắp xếp: Giá giảm dần';
} elseif ($selectedSort === 'stock_desc') {
    $activeFilterNotes[] = 'Sắp xếp: Tồn kho nhiều';
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
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
                    <h1>Quản lý sản phẩm</h1>
                    <p class="admin-page-note">Thêm, sửa, xóa sản phẩm và quản lý đầy đủ thông số cấu hình.</p>
                </div>
                <button type="button" class="btn btn-primary" onclick="openProductModal()">
                    <i class="fa-solid fa-plus"></i> Thêm sản phẩm mới
                </button>
            </div>

            <?php if (!empty($data['flash'])): ?>
                <script>
                    window.flashData = {
                        type: "<?= e($data['flash']['type']) ?>",
                        message: "<?= e($data['flash']['message']) ?>"
                    };
                </script>
            <?php endif; ?>

            <!-- Modal Thêm/Sửa Sản Phẩm -->
            <div id="productModalOverlay" class="admin-modal-overlay <?= $shouldOpenModal ? 'active active-edit-mode' : '' ?>">
                <div class="admin-modal-content">
                    <button type="button" class="admin-modal-close" onclick="closeProductModal()">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                    <h2 class="mb-4"><?= $editingProduct ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới' ?></h2>

                    <!-- Thêm enctype="multipart/form-data" để upload được file -->
                    <form class="admin-product-form mt-4" method="post" action="<?= e($actionUrl) ?>" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= (int)($editingProduct['id'] ?? 0) ?>">

                        <div class="form-section mb-5">
                            <h4 class="text-primary mb-3"><i class="fa-solid fa-circle-info"></i> 1. Thông tin cơ bản (Bắt buộc)</h4>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" value="<?= e($editingProduct['name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Hãng <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="brand" value="<?= e($editingProduct['brand'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Giá (VNĐ) <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="price" min="0" value="<?= (int)($editingProduct['price'] ?? 0) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Số lượng (Kho) <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="quantity" min="0" value="<?= (int)($editingProduct['quantity'] ?? 0) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tình trạng <span class="text-danger">*</span></label>
                                    <select class="form-select" name="condition" required>
                                        <option value="new" <?= ($editingProduct['condition'] ?? 'new') === 'new' ? 'selected' : '' ?>>Máy mới</option>
                                        <option value="used" <?= ($editingProduct['condition'] ?? '') === 'used' ? 'selected' : '' ?>>Like New</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Bộ nhớ RAM <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="ram" placeholder="VD: 12GB" value="<?= e($editingProduct['ram'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Bộ nhớ ROM <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="rom" placeholder="VD: 256GB" value="<?= e($editingProduct['rom'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Chip xử lý (CPU) <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="cpu" placeholder="VD: Snapdragon 8 Gen 3" value="<?= e($editingProduct['cpu'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Màn hình <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="screen" placeholder="VD: 6.85 AMOLED" value="<?= e($editingProduct['screen'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Dung lượng Pin <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="battery" placeholder="VD: 7500mAh" value="<?= e($editingProduct['battery'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Sạc nhanh <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="charger" placeholder="VD: 120W" value="<?= e($editingProduct['charger'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Camera chính <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="camera" placeholder="VD: 50MP + 50MP" value="<?= e($editingProduct['camera'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Đánh giá sao (1-5) <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="rating" min="1" max="5" value="<?= (int)($editingProduct['rating'] ?? 5) ?>" required>
                                </div>

                                <!-- Phần upload ảnh -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Ảnh sản phẩm <span class="text-danger">*</span></label>

                                    <?php if (!empty($editingProduct['image'])): ?>
                                        <div class="mb-3 p-3 bg-light rounded">
                                            <p class="mb-2 text-muted small">Ảnh hiện tại:</p>
                                            <img src="<?= e($editingProduct['image']) ?>" alt="Ảnh hiện tại" style="max-width: 200px; border-radius: 8px; border: 2px solid #ddd;">
                                            <input type="hidden" name="old_image" value="<?= e($editingProduct['image']) ?>">
                                        </div>
                                    <?php endif; ?>

                                    <input class="form-control mb-2" type="file" name="image" accept="image/*" <?= empty($editingProduct['image']) ? 'required' : '' ?>>
                                    <small class="text-muted d-block mb-2">
                                        <i class="fa-solid fa-circle-info"></i>
                                        Chọn file ảnh từ máy tính (JPG, PNG, GIF, WEBP). Tối đa 5MB.
                                    </small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold">Mô tả ngắn <span class="text-danger">*</span></label>
                                    <textarea class="form-control admin-textarea" name="description" rows="3" required><?= e($editingProduct['description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-4">
                            <h4 class="text-secondary mb-3"><i class="fa-solid fa-list-check"></i> 2. Thông số kỹ thuật chi tiết</h4>
                            <p class="text-muted small mb-3">Toàn bộ các danh mục thông số bên dưới hiện đã được liên kết lưu xuống database. Bỏ trống nếu chưa có dữ liệu.</p>

                            <div class="accordion" id="specsAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                            Màn hình & Thiết kế
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#specsAccordion">
                                        <div class="accordion-body row g-3 bg-light">
                                            <div class="col-md-6"><label class="form-label">Tỷ lệ màn hình</label><input class="form-control form-control-sm" type="text" name="screen_ratio" value="<?= e($editingProduct['screen_ratio'] ?? '') ?>"></div>
                                            <div class="col-md-6"><label class="form-label">Công nghệ màn hình</label><input class="form-control form-control-sm" type="text" name="screen_tech" value="<?= e($editingProduct['screen_tech'] ?? '') ?>"></div>
                                            <div class="col-md-6"><label class="form-label">Độ phân giải chi tiết</label><input class="form-control form-control-sm" type="text" name="screen_resolution" value="<?= e($editingProduct['screen_resolution'] ?? '') ?>"></div>
                                            <div class="col-md-6"><label class="form-label">Kính bảo vệ</label><input class="form-control form-control-sm" type="text" name="screen_glass" value="<?= e($editingProduct['screen_glass'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Vật liệu</label><input class="form-control form-control-sm" type="text" name="design_material" value="<?= e($editingProduct['design_material'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Kích thước</label><input class="form-control form-control-sm" type="text" name="dimensions" value="<?= e($editingProduct['dimensions'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Trọng lượng</label><input class="form-control form-control-sm" type="text" name="weight" value="<?= e($editingProduct['weight'] ?? '') ?>"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                            Máy ảnh (Camera)
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#specsAccordion">
                                        <div class="accordion-body row g-3 bg-light">
                                            <div class="col-12"><strong class="text-primary">Camera Sau</strong></div>
                                            <div class="col-md-4"><label class="form-label">Số ống kính</label><input class="form-control form-control-sm" type="number" name="cam_rear_count" value="<?= e($editingProduct['cam_rear_count'] ?? '') ?>"></div>
                                            <div class="col-md-8"><label class="form-label">Khẩu độ & Tính năng</label><input class="form-control form-control-sm" type="text" name="cam_rear_features" value="<?= e($editingProduct['cam_rear_features'] ?? '') ?>"></div>
                                            <div class="col-md-12"><label class="form-label">Quay phim</label><input class="form-control form-control-sm" type="text" name="cam_rear_video" value="<?= e($editingProduct['cam_rear_video'] ?? '') ?>"></div>

                                            <div class="col-12 mt-3"><strong class="text-primary">Camera Trước</strong></div>
                                            <div class="col-md-4"><label class="form-label">Độ phân giải & Khẩu độ</label><input class="form-control form-control-sm" type="text" name="cam_front_specs" value="<?= e($editingProduct['cam_front_specs'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Quay phim</label><input class="form-control form-control-sm" type="text" name="cam_front_video" value="<?= e($editingProduct['cam_front_video'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Tính năng khác</label><input class="form-control form-control-sm" type="text" name="cam_front_features" value="<?= e($editingProduct['cam_front_features'] ?? '') ?>"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                            Hệ điều hành & Kết nối
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#specsAccordion">
                                        <div class="accordion-body row g-3 bg-light">
                                            <div class="col-md-4"><label class="form-label">Hệ điều hành</label><input class="form-control form-control-sm" type="text" name="os" value="<?= e($editingProduct['os'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Tốc độ xử lý (Xung nhịp)</label><input class="form-control form-control-sm" type="text" name="cpu_speed" value="<?= e($editingProduct['cpu_speed'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Chip đồ họa (GPU)</label><input class="form-control form-control-sm" type="text" name="gpu" value="<?= e($editingProduct['gpu'] ?? '') ?>"></div>
                                            <div class="col-md-3"><label class="form-label">Mạng di động</label><input class="form-control form-control-sm" type="text" name="network" value="<?= e($editingProduct['network'] ?? '') ?>"></div>
                                            <div class="col-md-3"><label class="form-label">Hỗ trợ SIM</label><input class="form-control form-control-sm" type="text" name="sim" value="<?= e($editingProduct['sim'] ?? '') ?>"></div>
                                            <div class="col-md-3"><label class="form-label">Wifi</label><input class="form-control form-control-sm" type="text" name="wifi" value="<?= e($editingProduct['wifi'] ?? '') ?>"></div>
                                            <div class="col-md-3"><label class="form-label">Bluetooth</label><input class="form-control form-control-sm" type="text" name="bluetooth" value="<?= e($editingProduct['bluetooth'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Cổng sạc / Kết nối</label><input class="form-control form-control-sm" type="text" name="port_charging" value="<?= e($editingProduct['port_charging'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Cổng tai nghe</label><input class="form-control form-control-sm" type="text" name="port_audio" value="<?= e($editingProduct['port_audio'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">GPS</label><input class="form-control form-control-sm" type="text" name="gps" value="<?= e($editingProduct['gps'] ?? '') ?>"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                            Tính năng đặc biệt & Pin
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#specsAccordion">
                                        <div class="accordion-body row g-3 bg-light">
                                            <div class="col-md-6"><label class="form-label">Công nghệ sạc nhanh (Chi tiết)</label><input class="form-control form-control-sm" type="text" name="charging_tech" value="<?= e($editingProduct['charging_tech'] ?? '') ?>"></div>
                                            <div class="col-md-6"><label class="form-label">Hỗ trợ thẻ nhớ ngoài</label><input class="form-control form-control-sm" type="text" name="memory_card" value="<?= e($editingProduct['memory_card'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Công nghệ bảo mật</label><input class="form-control form-control-sm" type="text" name="security" value="<?= e($editingProduct['security'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Chống nước/Bụi</label><input class="form-control form-control-sm" type="text" name="water_resistance" value="<?= e($editingProduct['water_resistance'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label">Tính năng khác (Quạt, Triggers...)</label><input class="form-control form-control-sm" type="text" name="extra_features" value="<?= e($editingProduct['extra_features'] ?? '') ?>"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="mb-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fa-solid fa-floppy-disk"></i> <?= e($buttonText) ?>
                        </button>
                    </form>
                </div>
            </div>

            <section class="admin-card">
                <div class="admin-card-head border-bottom pb-3 mb-4">
                    <h2>
                        <i class="fa-solid fa-box-open text-primary me-2"></i>
                        Danh sách sản phẩm
                    </h2>
                    <span class="badge bg-primary rounded-pill"><?= count($products) ?> sản phẩm</span>
                </div>

                <!-- Bộ lọc sản phẩm -->
                <form class="admin-filters" action="index.php" method="get">
                    <input type="hidden" name="page" value="admin_products">

                    <div class="header-search flex-grow-1" style="max-width: 420px; margin: 0;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" name="search" value="<?= e($searchKeyword) ?>" placeholder="Tìm theo tên, hãng, chip...">
                    </div>

                    <select class="form-select w-auto" name="category_filter" id="categoryFilter">
                        <option value="">Tất cả danh mục hãng</option>
                        <?php foreach ($brandOptions as $brand): ?>
                            <option value="<?= e($brand) ?>" <?= $selectedBrand === $brand ? 'selected' : '' ?>>
                                <?= e($brand) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select class="form-select w-auto" name="sort_by" id="sortBy">
                        <option value="newest" <?= $selectedSort === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                        <option value="price_asc" <?= $selectedSort === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                        <option value="price_desc" <?= $selectedSort === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                        <option value="stock_desc" <?= $selectedSort === 'stock_desc' ? 'selected' : '' ?>>Tồn kho nhiều</option>
                    </select>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-filter"></i> Áp dụng
                    </button>

                    <?php if ($searchKeyword !== '' || $selectedBrand !== '' || $selectedSort !== 'newest'): ?>
                        <a href="index.php?page=admin_products" class="btn btn-soft btn-sm">
                            <i class="fa-solid fa-rotate-left"></i> Xóa lọc
                        </a>
                    <?php endif; ?>
                </form>

                <?php if ($activeFilterNotes): ?>
                    <div class="mb-3 text-muted" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-sliders me-1 text-primary"></i>
                        <?= implode(' | ', $activeFilterNotes) ?>
                    </div>
                <?php endif; ?>

                <?php if (!$products): ?>
                    <div class="empty-admin">
                        <i class="fa-solid fa-box-open"></i>
                        <h3 class="mt-3 mb-2">Không tìm thấy sản phẩm phù hợp</h3>
                        <p>Hệ thống chưa có sản phẩm khớp với bộ lọc hiện tại. Bạn có thể xóa bộ lọc hoặc thêm sản phẩm mới.</p>
                    </div>
                <?php else: ?>
                    <div class="admin-table-wrap table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Hãng</th>
                                    <th>Cấu hình nhanh</th>
                                    <th>Giá</th>
                                    <th>Kho</th>
                                    <th>Tình trạng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td>#<?= (int)$product['id'] ?></td>
                                        <td>
                                            <div class="admin-product-cell">
                                                <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
                                                <div>
                                                    <strong><?= e($product['name']) ?></strong>
                                                    <span><?= e($product['cpu']) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= e($product['brand']) ?></td>
                                        <td>
                                            <div style="display: grid; gap: 4px; white-space: normal;">
                                                <span><?= e(($product['ram'] ?? '-') . ' / ' . ($product['rom'] ?? '-')) ?></span>
                                                <span><?= e($product['screen'] ?? '-') ?></span>
                                            </div>
                                        </td>
                                        <td><?= e(format_vnd($product['price'])) ?></td>
                                        <td><span class="stock-pill"><?= (int)$product['quantity'] ?></span></td>
                                        <td><?= e(condition_label($product['condition'])) ?></td>
                                        <td>
                                            <div class="admin-actions admin-actions-inline">
                                                <a class="icon-action" href="index.php?page=admin_products&edit=<?= (int)$product['id'] ?>" title="Sửa">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <form method="post" action="index.php?page=admin_product_delete" class="delete-product-form">
                                                    <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
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

                    <div class="admin-pagination" style="justify-content: space-between;">
                        <span style="width: auto; padding: 0 14px;">Tổng hiển thị: <?= count($products) ?> sản phẩm</span>
                        <span style="width: auto; padding: 0 14px;">Danh mục hãng và sắp xếp đã hoạt động theo bộ lọc phía trên</span>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/admin_ajax.js"></script>
    <script>
        function openProductModal() {
            const modalOverlay = document.getElementById('productModalOverlay');
            if (modalOverlay && !modalOverlay.classList.contains('active-edit-mode')) {
                const modalForm = document.querySelector('.admin-product-form');
                if (modalForm) {
                    modalForm.reset();
                    modalForm.action = 'index.php?page=admin_add_products';

                    const submitBtn = modalForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Thêm sản phẩm mới';
                    }

                    const hiddenId = modalForm.querySelector('input[name="id"]');
                    if (hiddenId) {
                        hiddenId.value = '0';
                    }

                    const oldImageInfo = modalForm.querySelector('input[name="old_image"]')?.parentElement;
                    if (oldImageInfo) {
                        oldImageInfo.style.display = 'none';
                    }

                    const imgInput = modalForm.querySelector('input[name="image"]');
                    if (imgInput) {
                        imgInput.required = true;
                    }
                }
            }

            modalOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeProductModal() {
            const modalOverlay = document.getElementById('productModalOverlay');
            modalOverlay.classList.remove('active');

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('edit')) {
                setTimeout(() => {
                    const returnUrl = new URL(window.location.href);
                    returnUrl.searchParams.delete('edit');
                    window.location.href = returnUrl.toString();
                }, 300);
            } else {
                document.body.style.overflow = '';
            }
        }

        document.getElementById('productModalOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProductModal();
            }
        });

        <?php if ($shouldOpenModal): ?>
            document.body.style.overflow = 'hidden';
        <?php endif; ?>
    </script>
</body>

</html>
