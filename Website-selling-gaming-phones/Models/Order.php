<?php
// =========================================================
// MODEL ORDER (CHỈ HƯỚNG DẪN)
// =========================================================
// Nhiệm vụ của Order model trong MVC:
// - Làm việc với bảng `orders` và `order_items`.
// - Tạo đơn hàng khi người dùng thanh toán.
//
// Gợi ý dữ liệu cần lưu:
// - orders: id, user_id, customer_name, phone, address, payment_method, status, total_amount, created_at
// - order_items: id, order_id, product_id, product_name, price, quantity, line_total
//
// Gợi ý các hàm bạn nên tạo:
// - create($customerInfo, $cartItems, $userId): tạo đơn + chi tiết đơn (nên dùng transaction)
// - getAll(): lấy danh sách đơn hàng cho admin
// - updateStatus($orderId, $status): cập nhật trạng thái đơn
// - getStats(): thống kê đơn hàng cho dashboard
//
// Lưu ý:
// - Khi tạo đơn, cần trừ tồn kho sản phẩm và tăng số lượng đã bán (sales) nếu bạn có cột đó.

