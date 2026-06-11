<?php
// =========================================================
// AUTH CONTROLLER (CHỈ HƯỚNG DẪN)
// =========================================================
// Nhiệm vụ của AuthController trong MVC:
// 1) Nhận dữ liệu từ form đăng nhập/đăng ký (POST)
// 2) Validate dữ liệu (không rỗng, độ dài mật khẩu, định dạng email/sđt...)
// 3) Gọi User model để:
//    - kiểm tra tài khoản + mật khẩu
//    - tạo tài khoản mới
//    - đổi / quên mật khẩu
// 4) Lưu session:
//    - $_SESSION['user'] khi đăng nhập user
//    - $_SESSION['admin'] khi đăng nhập admin (nếu có)
// 5) Điều hướng về trang phù hợp (redirect) và hiển thị thông báo (flash)
//
// Gợi ý các hàm bạn nên tạo:
// - login(): xử lý đăng nhập user
// - register(): xử lý đăng ký
// - logout(): đăng xuất
// - forgotPassword(): đổi mật khẩu khi quên (dựa theo contact)
// - changePassword(): đổi mật khẩu khi đã đăng nhập
//
// Lưu ý bảo mật cơ bản (khi làm thật):
// - Mật khẩu nên lưu bằng password_hash()
// - Kiểm tra password bằng password_verify()
// - Không lưu mật khẩu dạng plain text

