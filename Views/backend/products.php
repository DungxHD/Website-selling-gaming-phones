<?php
$products = $data['products'] ?? [];
$editingProduct = $data['editingProduct'] ?? null;
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
                <span class="eyebrow">CRUD</span>
                <h1>Quản lý sản phẩm</h1>
            </div>
            <a class="btn btn-primary" href="index.php?page=admin_products"><i class="fa-solid fa-plus"></i> Thêm sản phẩm mới</a>
        </div>

        <?php if (!empty($data['flash'])): ?>
            <div class="flash flash-<?= e($data['flash']['type']) ?>"><?= e($data['flash']['message']) ?></div>
        <?php endif; ?>

        <section class="admin-card admin-form-card">
            <h2><?= $editingProduct ? 'Sửa sản phẩm' : 'Thêm sản phẩm' ?></h2>
            <form class="admin-product-form" method="post" action="index.php?page=admin_products&action=admin_product_save">
                <input type="hidden" name="id" value="<?= (int)($editingProduct['id'] ?? 0) ?>">
                <label>Tên sản phẩm<input class="form-control" type="text" name="name" value="<?= e($editingProduct['name'] ?? '') ?>" required></label>
                <label>Hãng<input class="form-control" type="text" name="brand" value="<?= e($editingProduct['brand'] ?? '') ?>" required></label>
                <label>Giá<input class="form-control" type="number" name="price" min="0" value="<?= (int)($editingProduct['price'] ?? 0) ?>" required></label>
                <label>Số lượng<input class="form-control" type="number" name="quantity" min="0" value="<?= (int)($editingProduct['quantity'] ?? 0) ?>" required></label>
                <label>Tình trạng
                    <select class="form-select" name="condition">
                        <option value="new" <?= ($editingProduct['condition'] ?? 'new') === 'new' ? 'selected' : '' ?>>Máy mới</option>
                        <option value="used" <?= ($editingProduct['condition'] ?? '') === 'used' ? 'selected' : '' ?>>Like New</option>
                    </select>
                </label>
                <label>Đánh giá<input class="form-control" type="number" name="rating" min="1" max="5" value="<?= (int)($editingProduct['rating'] ?? 5) ?>"></label>
                <label>CPU<input class="form-control" type="text" name="cpu" value="<?= e($editingProduct['cpu'] ?? '') ?>"></label>
                <label>Màn hình<input class="form-control" type="text" name="screen" value="<?= e($editingProduct['screen'] ?? '') ?>"></label>
                <label>Pin<input class="form-control" type="text" name="battery" value="<?= e($editingProduct['battery'] ?? '') ?>"></label>
                <label>Sạc<input class="form-control" type="text" name="charger" value="<?= e($editingProduct['charger'] ?? '') ?>"></label>
                <label class="wide-field">Ảnh URL<input class="form-control" type="text" name="image" value="<?= e($editingProduct['image'] ?? '') ?>" required></label>
                <label class="wide-field">Mô tả<textarea class="form-control admin-textarea" name="description" rows="3" required><?= e($editingProduct['description'] ?? '') ?></textarea></label>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Lưu sản phẩm</button>
            </form>
        </section>

        <section class="admin-card">
            <div class="admin-card-head">
                <h2>Danh sách sản phẩm từ cơ sở dữ liệu</h2>
                <span><?= count($products) ?> dòng</span>
            </div>
            <div class="admin-table-wrap table-responsive">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Hãng</th>
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
                            <td><?= e(format_vnd($product['price'])) ?></td>
                            <td><span class="stock-pill"><?= (int)$product['quantity'] ?></span></td>
                            <td><?= e(condition_label($product['condition'])) ?></td>
                            <td>
                                <div class="admin-actions admin-actions-inline">
                                    <a class="icon-action" href="index.php?page=admin_products&edit=<?= (int)$product['id'] ?>" title="Sửa"><i class="fa-solid fa-pen"></i></a>
                                    <form method="post" action="index.php?page=admin_products&action=admin_product_delete" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                                        <button type="submit" class="icon-action danger" title="Xóa"><i class="fa-solid fa-trash"></i></button>
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
