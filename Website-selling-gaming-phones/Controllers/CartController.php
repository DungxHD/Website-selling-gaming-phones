<?php
// =========================================================
// CART CONTROLLER (CHỈ HƯỚNG DẪN)
// =========================================================
// Nhiệm vụ của CartController trong MVC:
// 1) Nhận thao tác giỏ hàng từ người dùng (POST)
//    - thêm sản phẩm vào giỏ
//    - cập nhật số lượng
//    - xóa sản phẩm
//    - làm trống giỏ
// 2) Lưu giỏ hàng vào session:
//    - Ví dụ cấu trúc: $_SESSION['cart'][product_id] = quantity
// 3) Khi hiển thị trang giỏ hàng:
//    - Gọi Product model để lấy thông tin sản phẩm theo id trong giỏ
//    - Tính tổng tiền, tổng số lượng
// 4) Trả dữ liệu sang view:
//    - Views/frontend/cart.php
//
// Gợi ý các hàm bạn nên tạo:
// - index(): hiển thị giỏ hàng
// - add(): thêm sản phẩm
// - update(): cập nhật số lượng
// - remove(): xóa 1 sản phẩm
// - clear(): xóa toàn bộ giỏ
//
// Lưu ý:
// - Luôn kiểm tra tồn kho (quantity) trước khi thêm/cập nhật.

