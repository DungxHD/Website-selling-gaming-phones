-- --------------------------------------------------------
-- Máy chủ:                      127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Phiên bản:           12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for gaming_phone_db
CREATE DATABASE IF NOT EXISTS `gaming_phone_db` /*!40100 DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `gaming_phone_db`;

-- Dumping structure for table gaming_phone_db.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `customer_name` varchar(120) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `payment_method` varchar(20) NOT NULL DEFAULT 'cod',
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `total_amount` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table gaming_phone_db.orders: ~0 rows (approximately)

-- Dumping structure for table gaming_phone_db.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `price` bigint NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `line_total` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table gaming_phone_db.order_items: ~0 rows (approximately)

-- Dumping structure for table gaming_phone_db.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `brand` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `cpu` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `screen` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `battery` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `charger` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT 'default.jpg',
  `description` text COLLATE utf8mb3_unicode_ci,
  `quantity` int DEFAULT '10',
  `sales` int DEFAULT '0',
  `condition` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT 'new',
  `rating` int DEFAULT '5',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table gaming_phone_db.products: ~35 rows (approximately)
INSERT INTO `products` (`id`, `name`, `brand`, `price`, `cpu`, `screen`, `battery`, `charger`, `image`, `description`, `quantity`, `sales`, `condition`, `rating`) VALUES
	(1, 'ASUS ROG Phone 8 Pro', 'ASUS', 28990000, 'Snapdragon 8 Gen 3', '165Hz LTPO AMOLED', '5500 mAh', '65W', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Ông vua Gaming Phone thế hệ mới, tản nhiệt buồng hơi siêu khủng.', 10, 150, 'new', 5),
	(2, 'ASUS ROG Phone 8', 'ASUS', 24990000, 'Snapdragon 8 Gen 3', '165Hz LTPO AMOLED', '5500 mAh', '65W', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Thiết kế gọn gàng hơn, chống nước IP68 đầu tiên của dòng ROG.', 10, 120, 'new', 5),
	(3, 'ASUS ROG Phone 7 Ultimate', 'ASUS', 23500000, 'Snapdragon 8 Gen 2', '165Hz AMOLED', '6000 mAh', '65W', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Phiên bản cao cấp có khe mở tản nhiệt tự động AeroActive Portal.', 10, 85, 'new', 5),
	(4, 'ASUS ROG Phone 7', 'ASUS', 18990000, 'Snapdragon 8 Gen 2', '165Hz AMOLED', '6000 mAh', '65W', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Cân bằng hoàn hảo giữa hiệu năng và nhiệt độ.', 10, 200, 'new', 4),
	(5, 'ASUS ROG Phone 6D Ultimate', 'ASUS', 15500000, 'Dimensity 9000+', '165Hz AMOLED', '6000 mAh', '65W', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Siêu phẩm chạy chip Dimensity mát mẻ cho game thủ.', 10, 50, 'used', 4),
	(6, 'ASUS ROG Phone 6 Pro', 'ASUS', 14500000, 'Snapdragon 8+ Gen 1', '165Hz AMOLED', '6000 mAh', '65W', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Màn hình phụ ROG Vision tùy chỉnh cực chất ở mặt lưng.', 10, 90, 'used', 4),
	(7, 'ASUS ROG Phone 6', 'ASUS', 11500000, 'Snapdragon 8+ Gen 1', '165Hz AMOLED', '6000 mAh', '65W', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Mẫu máy quốc dân của dòng ROG thế hệ 6.', 10, 300, 'used', 4),
	(8, 'RedMagic 9 Pro+', 'RedMagic', 22500000, 'Snapdragon 8 Gen 3', '120Hz UDC Full', '5500 mAh', '165W', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Sạc siêu tốc 165W, mặt lưng phẳng hoàn toàn không lồi camera.', 10, 80, 'new', 5),
	(9, 'RedMagic 9 Pro', 'RedMagic', 18500000, 'Snapdragon 8 Gen 3', '120Hz UDC Full', '6500 mAh', '80W', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Pin quái vật 6500mAh, cày game 2 ngày không cần sạc.', 10, 320, 'new', 5),
	(10, 'RedMagic 8S Pro', 'RedMagic', 16500000, 'Snapdragon 8 Gen 2 (OC)', '120Hz UDC Full', '6000 mAh', '80W', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Phiên bản ép xung chip cực mạnh, quạt tản nhiệt RGB vật lý.', 10, 150, 'new', 5),
	(11, 'RedMagic 8 Pro', 'RedMagic', 14500000, 'Snapdragon 8 Gen 2', '120Hz UDC Full', '6000 mAh', '80W', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Thiết kế vuông vức nam tính, camera ẩn dưới màn hình vô khuyết.', 10, 250, 'used', 4),
	(12, 'RedMagic 7S Pro', 'RedMagic', 12000000, 'Snapdragon 8+ Gen 1', '120Hz UDC Full', '5000 mAh', '135W', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Mẫu máy đầu tiên giấu camera dưới màn hình của RedMagic.', 10, 110, 'used', 4),
	(13, 'RedMagic 7 Pro', 'RedMagic', 10500000, 'Snapdragon 8 Gen 1', '120Hz UDC Full', '5000 mAh', '135W', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Lưng trong suốt lộ linh kiện cực kỳ hầm hố.', 10, 90, 'used', 4),
	(14, 'RedMagic 7', 'RedMagic', 8500000, 'Snapdragon 8 Gen 1', '165Hz AMOLED', '4500 mAh', '120W', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Giá cực rẻ để trải nghiệm màn hình 165Hz siêu mượt.', 10, 180, 'used', 4),
	(15, 'iQOO 12 Pro', 'iQOO', 18500000, 'Snapdragon 8 Gen 3', '144Hz 2K E7', '5100 mAh', '120W', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Màn hình sáng nhất thế giới game, thiết kế BMW sang trọng.', 10, 60, 'new', 5),
	(16, 'iQOO 12', 'iQOO', 15500000, 'Snapdragon 8 Gen 3', '144Hz 1.5K', '5000 mAh', '120W', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Chip độc quyền hỗ trợ nội suy khung hình game lên 144FPS.', 10, 210, 'new', 5),
	(17, 'iQOO Neo 9 Pro', 'iQOO', 11500000, 'Dimensity 9300', '144Hz 1.5K', '5160 mAh', '120W', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Quái vật tầm trung với sức mạnh không thua gì Flagship.', 10, 340, 'new', 5),
	(18, 'iQOO Neo 9', 'iQOO', 9500000, 'Snapdragon 8 Gen 2', '144Hz 1.5K', '5160 mAh', '120W', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Ông hoàng doanh số phân khúc 9 triệu đồng.', 10, 450, 'new', 5),
	(19, 'iQOO 11S', 'iQOO', 12500000, 'Snapdragon 8 Gen 2', '144Hz 2K E6', '4700 mAh', '200W', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Sạc 200W siêu thần tốc, chỉ 10 phút đầy pin.', 10, 120, 'used', 4),
	(20, 'iQOO 11 Pro', 'iQOO', 11500000, 'Snapdragon 8 Gen 2', '144Hz 2K E6', '4700 mAh', '200W', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Camera chụp siêu nét kết hợp với hiệu năng gaming đỉnh cao.', 10, 80, 'used', 4),
	(21, 'iQOO 10 Pro', 'iQOO', 8500000, 'Snapdragon 8+ Gen 1', '120Hz 2K E5', '4700 mAh', '200W', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Lựa chọn giá rẻ trải nghiệm sạc 200W đẳng cấp.', 10, 150, 'used', 4),
	(22, 'Realme GT7 Pro Racing', 'Realme', 16500000, 'Snapdragon 8 Gen 4', '144Hz 1.5K OLED', '6000 mAh', '120W', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Flagship Killer thế hệ mới, tản nhiệt băng làm mát siêu tốc.', 10, 90, 'new', 5),
	(23, 'Realme GT5 Pro', 'Realme', 13500000, 'Snapdragon 8 Gen 3', '144Hz 1.5K AMOLED', '5400 mAh', '100W', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Sức mạnh vượt trội, camera tele chụp đêm xuất sắc.', 10, 210, 'new', 5),
	(24, 'Realme GT Neo 6', 'Realme', 8500000, 'Snapdragon 8s Gen 3', '120Hz 1.5K LTPO', '5500 mAh', '120W', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Cực phẩm tầm trung, sạc nhanh và chip mới nhất.', 10, 450, 'new', 4),
	(25, 'Realme GT Neo 5', 'Realme', 9500000, 'Snapdragon 8+ Gen 1', '144Hz 1.5K AMOLED', '4600 mAh', '240W', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Led RGB mặt lưng độc đáo, sạc 240W đứng đầu thế giới.', 10, 310, 'new', 5),
	(26, 'Realme GT3', 'Realme', 10500000, 'Snapdragon 8+ Gen 1', '144Hz 1.5K AMOLED', '4600 mAh', '240W', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Phiên bản quốc tế của GT Neo 5, chạy ROM Global ổn định.', 10, 110, 'used', 4),
	(27, 'Realme GT Neo2 5G', 'Realme', 5500000, 'Snapdragon 870', '120Hz E4 AMOLED', '5000 mAh', '65W', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Huyền thoại quốc dân, chip Snap 870 mát mẻ cày game bao mượt.', 10, 850, 'used', 5),
	(28, 'Realme GT2 Pro', 'Realme', 7500000, 'Snapdragon 8 Gen 1', '120Hz 2K AMOLED', '5000 mAh', '65W', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Mặt lưng thiết kế vân giấy bảo vệ môi trường, màn hình 2K siêu nét.', 10, 140, 'used', 4),
	(29, 'Black Shark 5 Pro', 'Xiaomi', 11500000, 'Snapdragon 8 Gen 1', '144Hz OLED', '4650 mAh', '120W', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Phím trigger vật lý độc quyền, cảm giác bắn súng như tay cầm thật.', 10, 220, 'new', 5),
	(30, 'Black Shark 5', 'Xiaomi', 8500000, 'Snapdragon 870', '144Hz AMOLED', '4650 mAh', '120W', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Hiệu năng mát mẻ, trigger vật lý giá rẻ cho game thủ.', 10, 280, 'new', 4),
	(31, 'Xiaomi 14 Pro', 'Xiaomi', 19500000, 'Snapdragon 8 Gen 3', '120Hz 2K LTPO', '4880 mAh', '120W', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Cân bằng hoàn hảo giữa chơi game và nhiếp ảnh Leica.', 10, 150, 'new', 5),
	(32, 'Xiaomi 14', 'Xiaomi', 16500000, 'Snapdragon 8 Gen 3', '120Hz 1.5K LTPO', '4610 mAh', '90W', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Kích thước nhỏ gọn nhưng mang sức mạnh của mãnh thú.', 10, 340, 'new', 5),
	(33, 'Redmi K70 Pro', 'Xiaomi', 11500000, 'Snapdragon 8 Gen 3', '120Hz 2K OLED', '5000 mAh', '120W', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Vô địch tầm giá 11 triệu với chip Snap 8 Gen 3.', 10, 420, 'new', 5),
	(34, 'Redmi K70', 'Xiaomi', 8500000, 'Snapdragon 8 Gen 2', '120Hz 2K OLED', '5000 mAh', '120W', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Mẫu máy phá đảo doanh số nhờ mức giá không tưởng.', 10, 650, 'new', 5),
	(35, 'Poco F6 Pro', 'Xiaomi', 12500000, 'Snapdragon 8 Gen 2', '120Hz 2K OLED', '5000 mAh', '120W', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Bản quốc tế của K70, phần mềm tối ưu không trễ thông báo.', 10, 210, 'used', 4);

-- Dumping structure for table gaming_phone_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `contact` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table gaming_phone_db.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `name`, `contact`, `role`, `is_active`) VALUES
	(1, 'admin_fpt', '123456', 'Quản Trị Viên', 'admin@fpt.edu.vn', 'admin', 1),
	(2, 'gaming_thu_99', '123456', 'Lương Dũng', '0987654321', 'user', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
