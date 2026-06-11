<?php
// =========================================================
// MODEL USER (CHỈ HƯỚNG DẪN)
// =========================================================
// Nhiệm vụ của User model trong MVC:
// - Làm việc với bảng `users` trong database.
// - Không nhận $_GET/$_POST trực tiếp (việc đó thuộc Controller).
//
// Gợi ý các hàm bạn nên tạo:
// - findByUsername($username): lấy user theo username
// - create($data): tạo user mới (password_hash)
// - verifyLogin($username, $password): xác thực đăng nhập (password_verify)
// - updatePassword($userId, $newPassword)
// - getAll(): lấy danh sách user cho admin
// - toggleStatus($userId): khóa/mở tài khoản (cột is_active)
//
// Quy tắc:
// - Trả dữ liệu dạng mảng (fetch/fetchAll)
// - Không echo HTML trong model

