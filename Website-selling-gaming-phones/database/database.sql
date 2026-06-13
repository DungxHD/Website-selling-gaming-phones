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
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `brand` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ram` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `rom` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `price` int NOT NULL,
  `cpu` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `screen` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `battery` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `charger` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `camera` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'default.jpg',
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `quantity` int DEFAULT '10',
  `sales` int DEFAULT '0',
  `condition` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'new',
  `rating` int DEFAULT '5',
  `screen_ratio` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `screen_tech` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `screen_resolution` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `screen_glass` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `design_material` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `dimensions` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `weight` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `cam_rear_count` tinyint DEFAULT NULL,
  `cam_rear_features` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `cam_rear_video` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `cam_front_specs` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `cam_front_video` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `cam_front_features` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `os` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `cpu_speed` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gpu` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `network` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `sim` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `wifi` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bluetooth` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `port_charging` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `port_audio` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gps` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `charging_tech` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `memory_card` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `security` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `water_resistance` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `extra_features` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table gaming_phone_db.products: ~35 rows (approximately)
INSERT INTO `products` (`id`, `name`, `brand`, `ram`, `rom`, `price`, `cpu`, `screen`, `battery`, `charger`, `camera`, `image`, `description`, `quantity`, `sales`, `condition`, `rating`, `screen_ratio`, `screen_tech`, `screen_resolution`, `screen_glass`, `design_material`, `dimensions`, `weight`, `cam_rear_count`, `cam_rear_features`, `cam_rear_video`, `cam_front_specs`, `cam_front_video`, `cam_front_features`, `os`, `cpu_speed`, `gpu`, `network`, `sim`, `wifi`, `bluetooth`, `port_charging`, `port_audio`, `gps`, `charging_tech`, `memory_card`, `security`, `water_resistance`, `extra_features`) VALUES
	(1, 'ASUS ROG Phone 8 Pro', 'ASUS', '16GB', '512GB', 28990000, 'Snapdragon 8 Gen 3', '165Hz LTPO AMOLED', '5500 mAh', '65W', '50MP + 32MP + 13MP', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Ông vua Gaming Phone thế hệ mới, tản nhiệt buồng hơi siêu khủng.', 10, 150, 'new', 5, '88.2%', 'LTPO AMOLED, 1B colors, 2500 nits', '1080 x 2400 pixels', 'Corning Gorilla Glass Victus 2', 'Kính/Nhôm', '163.8 x 76.8 x 8.9 mm', '225 g', 3, 'OIS, 3x optical zoom, Gimbal OIS', '8K@24fps, 4K@30/60fps', '32 MP, f/2.5 (wide)', '1080p@30fps', 'Panorama, HDR', 'Android 14, ROG UI', 'Octa-core 3.3 GHz', 'Adreno 750', '5G / LTE / HSPA', '2 Nano SIM', 'Wi-Fi 7, tri-band', '5.3, A2DP, LE, aptX', '2 x USB Type-C (3.1 & 2.0)', '3.5mm', 'GPS (L1+L5), GLONASS, BDS', '65W dây, 15W không dây', 'Không', 'Vân tay (dưới màn hình)', 'IP68', 'AniMe Vision LED, Nút Trigger cảm ứng'),
	(2, 'ASUS ROG Phone 8', 'ASUS', '12GB', '256GB', 24990000, 'Snapdragon 8 Gen 3', '165Hz LTPO AMOLED', '5500 mAh', '65W', '50MP + 32MP + 13MP', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Thiết kế gọn gàng hơn, chống nước IP68 đầu tiên của dòng ROG.', 10, 120, 'new', 5, '88.2%', 'LTPO AMOLED', '1080 x 2400 pixels', 'Corning Gorilla Glass Victus 2', 'Kính/Nhôm', '163.8 x 76.8 x 8.9 mm', '225 g', 3, 'Gimbal OIS, Telephoto', '8K@24fps', '32 MP', '1080p@30fps', 'HDR', 'Android 14, ROG UI', '3.3 GHz', 'Adreno 750', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.3', '2 x USB Type-C', '3.5mm', 'GPS (L1+L5)', '65W dây, 15W không dây', 'Không', 'Vân tay quang học', 'IP68', 'Aura RGB logo, Nút Trigger'),
	(3, 'ASUS ROG Phone 7 Ultimate', 'ASUS', '16GB', '512GB', 23500000, 'Snapdragon 8 Gen 2', '165Hz AMOLED', '6000 mAh', '65W', '50MP + 13MP + 5MP', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Phiên bản cao cấp có khe mở tản nhiệt tự động AeroActive Portal.', 10, 85, 'new', 5, '82.2%', 'AMOLED, 1B colors', '1080 x 2448 pixels', 'Gorilla Glass Victus', 'Kính/Nhôm', '173 x 77 x 10.3 mm', '239 g', 3, 'PDAF, LED flash', '8K@24fps', '32 MP', '1080p@30fps', 'HDR', 'Android 13, ROG UI', '3.2 GHz', 'Adreno 740', '5G', '2 Nano SIM', 'Wi-Fi 6e/7', '5.3', '2 x USB Type-C', '3.5mm', 'GPS (L1+L5)', '65W dây', 'Không', 'Vân tay (dưới màn hình)', 'IP54', 'AeroActive Portal, ROG Vision'),
	(4, 'ASUS ROG Phone 7', 'ASUS', '12GB', '256GB', 18990000, 'Snapdragon 8 Gen 2', '165Hz AMOLED', '6000 mAh', '65W', '50MP + 13MP + 5MP', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Cân bằng hoàn hảo giữa hiệu năng và nhiệt độ.', 10, 200, 'new', 4, '82.2%', 'AMOLED', '1080 x 2448 pixels', 'Gorilla Glass Victus', 'Kính/Nhôm', '173 x 77 x 10.3 mm', '239 g', 3, 'PDAF, LED flash', '8K@24fps', '32 MP', '1080p@30fps', 'HDR', 'Android 13', '3.2 GHz', 'Adreno 740', '5G', '2 Nano SIM', 'Wi-Fi 6e/7', '5.3', '2 x USB Type-C', '3.5mm', 'GPS', '65W dây', 'Không', 'Vân tay quang học', 'IP54', 'Logo RGB, Trigger siêu âm'),
	(5, 'ASUS ROG Phone 6D Ultimate', 'ASUS', '16GB', '512GB', 15500000, 'Dimensity 9000+', '165Hz AMOLED', '6000 mAh', '65W', '50MP + 13MP + 5MP', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Siêu phẩm chạy chip Dimensity mát mẻ cho game thủ.', 10, 50, 'used', 4, '82.2%', 'AMOLED', '1080 x 2448 pixels', 'Gorilla Glass Victus', 'Kính/Nhôm', '173 x 77 x 10.4 mm', '247 g', 3, 'PDAF', '8K@24fps', '12 MP', '1080p@30fps', 'HDR', 'Android 12', '3.35 GHz', 'Mali-G710 MC10', '5G', '2 Nano SIM', 'Wi-Fi 6e', '5.3', '2 x USB Type-C', '3.5mm', 'GPS', '65W dây', 'Không', 'Vân tay quang học', 'IPX4', 'AeroActive Portal, ROG Vision'),
	(6, 'ASUS ROG Phone 6 Pro', 'ASUS', '18GB', '512GB', 14500000, 'Snapdragon 8+ Gen 1', '165Hz AMOLED', '6000 mAh', '65W', '50MP + 13MP + 5MP', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Màn hình phụ ROG Vision tùy chỉnh cực chất ở mặt lưng.', 10, 90, 'used', 4, '82.2%', 'AMOLED', '1080 x 2448 pixels', 'Gorilla Glass Victus', 'Kính/Nhôm', '173 x 77 x 10.3 mm', '239 g', 3, 'PDAF', '8K@24fps', '12 MP', '1080p@30fps', 'HDR', 'Android 12', '3.19 GHz', 'Adreno 730', '5G', '2 Nano SIM', 'Wi-Fi 6e', '5.2', '2 x USB Type-C', '3.5mm', 'GPS', '65W dây', 'Không', 'Vân tay quang học', 'IPX4', 'Màn hình phụ sau lưng'),
	(7, 'ASUS ROG Phone 6', 'ASUS', '12GB', '128GB', 11500000, 'Snapdragon 8+ Gen 1', '165Hz AMOLED', '6000 mAh', '65W', '50MP + 13MP + 5MP', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500', 'Mẫu máy quốc dân của dòng ROG thế hệ 6.', 10, 300, 'used', 4, '82.2%', 'AMOLED', '1080 x 2448 pixels', 'Gorilla Glass Victus', 'Kính/Nhôm', '173 x 77 x 10.3 mm', '239 g', 3, 'PDAF', '8K@24fps', '12 MP', '1080p@30fps', 'HDR', 'Android 12', '3.19 GHz', 'Adreno 730', '5G', '2 Nano SIM', 'Wi-Fi 6e', '5.2', '2 x USB Type-C', '3.5mm', 'GPS', '65W dây', 'Không', 'Vân tay quang học', 'IPX4', 'Led RGB'),
	(8, 'RedMagic 9 Pro+', 'RedMagic', '16GB', '512GB', 22500000, 'Snapdragon 8 Gen 3', '120Hz UDC Full', '5500 mAh', '165W', '50MP + 50MP + 2MP', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Sạc siêu tốc 165W, mặt lưng phẳng hoàn toàn không lồi camera.', 10, 80, 'new', 5, '89.1%', 'AMOLED UDC', '1116 x 2480 pixels', 'Gorilla Glass 5', 'Kính/Nhôm', '164 x 76.4 x 8.9 mm', '229 g', 3, 'OIS', '8K@30fps', '16 MP', '1080p@30/60fps', 'HDR', 'Android 14, Redmagic OS', '3.3 GHz', 'Adreno 750', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.3', 'USB Type-C 3.2', '3.5mm', 'GPS (L1+L5)', '165W dây', 'Không', 'Vân tay quang học', 'Không', 'Quạt tản nhiệt 22000 RPM, Lưng phẳng'),
	(9, 'RedMagic 9 Pro', 'RedMagic', '12GB', '256GB', 18500000, 'Snapdragon 8 Gen 3', '120Hz UDC Full', '6500 mAh', '80W', '50MP + 50MP + 2MP', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Pin quái vật 6500mAh, cày game 2 ngày không cần sạc.', 10, 320, 'new', 5, '89.1%', 'AMOLED UDC', '1116 x 2480 pixels', 'Gorilla Glass 5', 'Kính/Nhôm', '164 x 76.4 x 8.9 mm', '229 g', 3, 'OIS', '8K@30fps', '16 MP', '1080p@30/60fps', 'HDR', 'Android 14, Redmagic OS', '3.3 GHz', 'Adreno 750', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.3', 'USB Type-C 3.2', '3.5mm', 'GPS', '80W dây', 'Không', 'Vân tay quang học', 'Không', 'Quạt tản nhiệt vật lý'),
	(10, 'RedMagic 8S Pro', 'RedMagic', '12GB', '256GB', 16500000, 'Snapdragon 8 Gen 2 (OC)', '120Hz UDC Full', '6000 mAh', '80W', '50MP + 8MP + 2MP', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Phiên bản ép xung chip cực mạnh, quạt tản nhiệt RGB vật lý.', 10, 150, 'new', 5, '89.1%', 'AMOLED', '1116 x 2480 pixels', 'Gorilla Glass 5', 'Kính/Nhôm', '164 x 76.4 x 8.9 mm', '228 g', 3, 'PDAF', '8K@30fps', '16 MP', '1080p@30fps', 'HDR', 'Android 13', '3.36 GHz', 'Adreno 740', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.3', 'USB Type-C', '3.5mm', 'GPS', '80W', 'Không', 'Vân tay quang học', 'Không', 'Quạt tản nhiệt'),
	(11, 'RedMagic 8 Pro', 'RedMagic', '12GB', '256GB', 14500000, 'Snapdragon 8 Gen 2', '120Hz UDC Full', '6000 mAh', '80W', '50MP + 8MP + 2MP', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Thiết kế vuông vức nam tính, camera ẩn dưới màn hình vô khuyết.', 10, 250, 'used', 4, '89.1%', 'AMOLED', '1116 x 2480 pixels', 'Gorilla Glass 5', 'Kính/Nhôm', '164 x 76.4 x 8.9 mm', '228 g', 3, 'PDAF', '8K@30fps', '16 MP', '1080p@30fps', 'HDR', 'Android 13', '3.2 GHz', 'Adreno 740', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.3', 'USB Type-C', '3.5mm', 'GPS', '80W', 'Không', 'Vân tay quang học', 'Không', 'Trigger cảm ứng'),
	(12, 'RedMagic 7S Pro', 'RedMagic', '12GB', '256GB', 12000000, 'Snapdragon 8+ Gen 1', '120Hz UDC Full', '5000 mAh', '135W', '64MP + 8MP + 2MP', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Mẫu máy đầu tiên giấu camera dưới màn hình của RedMagic.', 10, 110, 'used', 4, '87.1%', 'AMOLED', '1080 x 2400 pixels', 'Gorilla Glass 5', 'Kính/Nhôm', '166.3 x 77.1 x 10 mm', '235 g', 3, 'PDAF', '8K@30fps', '16 MP', '1080p@30fps', 'HDR', 'Android 12', '3.19 GHz', 'Adreno 730', '5G', '2 Nano SIM', 'Wi-Fi 6e', '5.2', 'USB Type-C', '3.5mm', 'GPS', '135W', 'Không', 'Vân tay quang học', 'Không', 'Quạt tản nhiệt'),
	(13, 'RedMagic 7 Pro', 'RedMagic', '12GB', '128GB', 10500000, 'Snapdragon 8 Gen 1', '120Hz UDC Full', '5000 mAh', '135W', '64MP + 8MP + 2MP', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Lưng trong suốt lộ linh kiện cực kỳ hầm hố.', 10, 90, 'used', 4, '87.1%', 'AMOLED', '1080 x 2400 pixels', 'Gorilla Glass 5', 'Kính/Nhôm', '166.3 x 77.1 x 10 mm', '235 g', 3, 'PDAF', '8K@30fps', '16 MP', '1080p@30fps', 'HDR', 'Android 12', '3.0 GHz', 'Adreno 730', '5G', '2 Nano SIM', 'Wi-Fi 6e', '5.2', 'USB Type-C', '3.5mm', 'GPS', '135W', 'Không', 'Vân tay quang học', 'Không', 'Quạt tản nhiệt'),
	(14, 'RedMagic 7', 'RedMagic', '8GB', '128GB', 8500000, 'Snapdragon 8 Gen 1', '165Hz AMOLED', '4500 mAh', '120W', '64MP + 8MP + 2MP', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500', 'Giá cực rẻ để trải nghiệm màn hình 165Hz siêu mượt.', 10, 180, 'used', 4, '83.6%', 'AMOLED', '1080 x 2400 pixels', 'Gorilla Glass 5', 'Kính/Nhôm', '170.5 x 78.3 x 9.5 mm', '215 g', 3, 'PDAF', '8K@30fps', '8 MP', '1080p@30fps', 'HDR', 'Android 12', '3.0 GHz', 'Adreno 730', '5G', '2 Nano SIM', 'Wi-Fi 6e', '5.2', 'USB Type-C', '3.5mm', 'GPS', '120W', 'Không', 'Vân tay quang học', 'Không', 'Quạt tản nhiệt'),
	(15, 'iQOO 12 Pro', 'iQOO', '16GB', '512GB', 18500000, 'Snapdragon 8 Gen 3', '144Hz 2K E7', '5100 mAh', '120W', '50MP + 50MP + 64MP', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Màn hình sáng nhất thế giới game, thiết kế BMW sang trọng.', 10, 60, 'new', 5, '89.4%', 'AMOLED E7', '1440 x 3200 pixels', 'Glass', 'Kính/Nhôm/Da', '164.6 x 75.4 x 8.6 mm', '210 g', 3, 'OIS, 3x optical', '8K@30fps', '16 MP', '1080p@30fps', 'HDR', 'Android 14', '3.3 GHz', 'Adreno 750', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.4', 'USB Type-C 2.0', 'Không', 'GPS', '120W dây, 50W không dây', 'Không', 'Vân tay siêu âm', 'IP68', 'Chip Q1 độc quyền'),
	(16, 'iQOO 12', 'iQOO', '12GB', '256GB', 15500000, 'Snapdragon 8 Gen 3', '144Hz 1.5K', '5000 mAh', '120W', '50MP + 50MP + 64MP', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Chip độc quyền hỗ trợ nội suy khung hình game lên 144FPS.', 10, 210, 'new', 5, '89.4%', 'AMOLED', '1260 x 2800 pixels', 'Glass', 'Kính/Nhôm', '163.2 x 75.9 x 8.1 mm', '203 g', 3, 'OIS', '8K@30fps', '16 MP', '1080p@30fps', 'HDR', 'Android 14', '3.3 GHz', 'Adreno 750', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.4', 'USB Type-C', 'Không', 'GPS', '120W', 'Không', 'Vân tay quang học', 'IP64', 'Chip nội suy Q1'),
	(17, 'iQOO Neo 9 Pro', 'iQOO', '12GB', '256GB', 11500000, 'Dimensity 9300', '144Hz 1.5K', '5160 mAh', '120W', '50MP + 50MP', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Quái vật tầm trung với sức mạnh không thua gì Flagship.', 10, 340, 'new', 5, '89.7%', 'AMOLED', '1260 x 2800 pixels', 'Glass', 'Nhựa/Kính/Da', '163.5 x 75.7 x 8 mm', '190 g', 2, 'OIS', '4K@60fps', '16 MP', '1080p@30fps', 'HDR', 'Android 14', '3.25 GHz', 'Immortalis-G720 MC12', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.4', 'USB Type-C', 'Không', 'GPS', '120W', 'Không', 'Vân tay quang học', 'Không', 'Tản nhiệt buồng hơi 6K'),
	(18, 'iQOO Neo 9', 'iQOO', '12GB', '256GB', 9500000, 'Snapdragon 8 Gen 2', '144Hz 1.5K', '5160 mAh', '120W', '50MP + 8MP', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Ông hoàng doanh số phân khúc 9 triệu đồng.', 10, 450, 'new', 5, '89.7%', 'AMOLED', '1260 x 2800 pixels', 'Glass', 'Nhựa/Kính', '163.5 x 75.7 x 8 mm', '190 g', 2, 'OIS', '4K@60fps', '16 MP', '1080p@30fps', 'HDR', 'Android 14', '3.2 GHz', 'Adreno 740', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.3', 'USB Type-C', 'Không', 'GPS', '120W', 'Không', 'Vân tay quang học', 'Không', 'Rung tuyến tính'),
	(19, 'iQOO 11S', 'iQOO', '12GB', '256GB', 12500000, 'Snapdragon 8 Gen 2', '144Hz 2K E6', '4700 mAh', '200W', '50MP + 13MP + 8MP', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Sạc 200W siêu thần tốc, chỉ 10 phút đầy pin.', 10, 120, 'used', 4, '87.3%', 'AMOLED E6', '1440 x 3200 pixels', 'Glass', 'Kính/Nhôm', '164.9 x 77.1 x 8.4 mm', '206 g', 3, 'OIS', '8K@30fps', '16 MP', '1080p@30fps', 'HDR', 'Android 13', '3.2 GHz', 'Adreno 740', '5G', '2 Nano SIM', 'Wi-Fi 6e', '5.3', 'USB Type-C', 'Không', 'GPS', '200W', 'Không', 'Vân tay quang học', 'Không', 'Chip V2 độc quyền'),
	(20, 'iQOO 11 Pro', 'iQOO', '12GB', '256GB', 11500000, 'Snapdragon 8 Gen 2', '144Hz 2K E6', '4700 mAh', '200W', '50MP + 50MP + 13MP', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Camera chụp siêu nét kết hợp với hiệu năng gaming đỉnh cao.', 10, 80, 'used', 4, '89.4%', 'AMOLED E6', '1440 x 3200 pixels', 'Glass', 'Kính/Nhôm/Da', '164.8 x 75.3 x 8.9 mm', '210 g', 3, 'OIS', '8K@30fps', '16 MP', '1080p@30fps', 'HDR', 'Android 13', '3.2 GHz', 'Adreno 740', '5G', '2 Nano SIM', 'Wi-Fi 6e', '5.3', 'USB Type-C', 'Không', 'GPS', '200W dây, 50W không dây', 'Không', 'Vân tay siêu âm', 'Không', 'Sạc 100% trong 10p'),
	(21, 'iQOO 10 Pro', 'iQOO', '8GB', '256GB', 8500000, 'Snapdragon 8+ Gen 1', '120Hz 2K E5', '4700 mAh', '200W', '50MP + 50MP + 14.6MP', 'https://images.unsplash.com/photo-1565630916779-e303be97b6f5?w=500', 'Lựa chọn giá rẻ trải nghiệm sạc 200W đẳng cấp.', 10, 150, 'used', 4, '89.4%', 'AMOLED E5', '1440 x 3200 pixels', 'Glass', 'Kính/Nhôm', '165 x 75.2 x 9.1 mm', '215 g', 3, 'Gimbal OIS', '8K@30fps', '16 MP', '1080p@30fps', 'HDR', 'Android 12', '3.19 GHz', 'Adreno 730', '5G', '2 Nano SIM', 'Wi-Fi 6e', '5.3', 'USB Type-C', 'Không', 'GPS', '200W', 'Không', 'Vân tay siêu âm', 'Không', 'Chip V1+'),
	(22, 'Realme GT7 Pro Racing', 'Realme', '16GB', '512GB', 16500000, 'Snapdragon 8 Gen 4', '144Hz 1.5K OLED', '6000 mAh', '120W', '50MP + 50MP + 8MP', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Flagship Killer thế hệ mới, tản nhiệt băng làm mát siêu tốc.', 10, 90, 'new', 5, '92.1%', 'OLED', '1260 x 2800 pixels', 'Glass', 'Nhôm/Kính', '162 x 75 x 8 mm', '200 g', 3, 'OIS', '8K@30fps', '32 MP', '4K@30fps', 'HDR', 'Android 14', '4.0 GHz', 'Adreno NextGen', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.4', 'USB Type-C', 'Không', 'GPS', '120W', 'Không', 'Vân tay quang học', 'IP68', 'Tản nhiệt Ice Core'),
	(23, 'Realme GT5 Pro', 'Realme', '12GB', '256GB', 13500000, 'Snapdragon 8 Gen 3', '144Hz 1.5K AMOLED', '5400 mAh', '100W', '50MP + 50MP + 8MP', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Sức mạnh vượt trội, camera tele chụp đêm xuất sắc.', 10, 210, 'new', 5, '89.3%', 'AMOLED', '1264 x 2780 pixels', 'Glass', 'Da/Nhôm/Kính', '161.7 x 75.1 x 9.2 mm', '218 g', 3, 'OIS', '8K@24fps', '32 MP', '4K@30fps', 'HDR', 'Android 14', '3.3 GHz', 'Adreno 750', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.4', 'USB Type-C', 'Không', 'GPS', '100W dây, 50W không dây', 'Không', 'Vân tay quang học', 'IP64', 'Cử chỉ vẩy tay'),
	(24, 'Realme GT Neo 6', 'Realme', '12GB', '256GB', 8500000, 'Snapdragon 8s Gen 3', '120Hz 1.5K LTPO', '5500 mAh', '120W', '50MP + 8MP', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Cực phẩm tầm trung, sạc nhanh và chip mới nhất.', 10, 450, 'new', 4, '90.1%', 'LTPO AMOLED', '1264 x 2780 pixels', 'Gorilla Glass Victus 2', 'Nhựa/Kính', '162 x 75.1 x 8.6 mm', '191 g', 2, 'OIS', '4K@60fps', '32 MP', '4K@30fps', 'HDR', 'Android 14', '3.0 GHz', 'Adreno 735', '5G', '2 Nano SIM', 'Wi-Fi 6e', '5.4', 'USB Type-C', 'Không', 'GPS', '120W', 'Không', 'Vân tay quang học', 'IP65', 'Màn sáng 6000 nits'),
	(25, 'Realme GT Neo 5', 'Realme', '16GB', '1TB', 9500000, 'Snapdragon 8+ Gen 1', '144Hz 1.5K AMOLED', '4600 mAh', '240W', '50MP + 8MP + 2MP', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Led RGB mặt lưng độc đáo, sạc 240W đứng đầu thế giới.', 10, 310, 'new', 5, '87.9%', 'AMOLED', '1240 x 2772 pixels', 'Glass', 'Nhựa/Kính', '163.9 x 75.8 x 8.9 mm', '199 g', 3, 'OIS', '4K@60fps', '16 MP', '1080p@30fps', 'HDR', 'Android 13', '3.19 GHz', 'Adreno 730', '5G', '2 Nano SIM', 'Wi-Fi 6', '5.3', 'USB Type-C', 'Không', 'GPS', '240W', 'Không', 'Vân tay quang học', 'Không', 'Cửa sổ LED Halo'),
	(26, 'Realme GT3', 'Realme', '12GB', '256GB', 10500000, 'Snapdragon 8+ Gen 1', '144Hz 1.5K AMOLED', '4600 mAh', '240W', '50MP + 8MP + 2MP', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Phiên bản quốc tế của GT Neo 5, chạy ROM Global ổn định.', 10, 110, 'used', 4, '87.9%', 'AMOLED', '1240 x 2772 pixels', 'Glass', 'Nhựa/Kính', '163.9 x 75.8 x 8.9 mm', '199 g', 3, 'OIS', '4K@60fps', '16 MP', '1080p@30fps', 'HDR', 'Android 13', '3.19 GHz', 'Adreno 730', '5G', '2 Nano SIM', 'Wi-Fi 6', '5.3', 'USB Type-C', 'Không', 'GPS', '240W', 'Không', 'Vân tay quang học', 'Không', 'Bản quốc tế'),
	(27, 'Realme GT Neo2 5G', 'Realme', '8GB', '256GB', 5500000, 'Snapdragon 870 5G (7 nm)', '120Hz E4 AMOLED', '5000 mAh', '65W', '64MP + 8MP + 2MP', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Huyền thoại quốc dân, chip Snap 870 mát mẻ cày game bao mượt. (MÁY CỦA CHỦ TỊCH LƯƠNG DŨNG)', 10, 850, 'used', 5, '85.7%', 'AMOLED E4, HDR10+, 1300 nits', '1080 x 2400 pixels', 'Corning Gorilla Glass 5', 'Khung nhựa, Mặt lưng kính (hoặc nhựa nhám)', '162.9 x 75.8 x 8.6 mm', '199.8 g', 3, 'PDAF, LED flash, HDR', '4K@30/60fps, 1080p@30fps', '16 MP, f/2.5 (wide)', '1080p@30fps', 'HDR, Panorama', 'Android 11 (Up to Android 13), Realme UI 4.0', 'Octa-core 3.2 GHz Kryo 585', 'Adreno 650', '5G / LTE / HSPA', '2 Nano SIM', 'Wi-Fi 802.11 a/b/g/n/ac/6, dual-band', '5.2, A2DP, LE, aptX HD', 'USB Type-C 2.0', 'Không (bỏ Jack 3.5mm)', 'GPS, GLONASS, BDS, GALILEO', '65W SuperDart, 100% trong 36p', 'Không', 'Vân tay quang học (dưới màn hình)', 'Không', 'Tản nhiệt kim cương thép không gỉ 3D 8 lớp siêu mát'),
	(28, 'Realme GT2 Pro', 'Realme', '8GB', '256GB', 7500000, 'Snapdragon 8 Gen 1', '120Hz 2K AMOLED', '5000 mAh', '65W', '50MP + 50MP + 3MP', 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=500', 'Mặt lưng thiết kế vân giấy bảo vệ môi trường, màn hình 2K siêu nét.', 10, 140, 'used', 4, '88.6%', 'AMOLED LTPO2', '1440 x 3216 pixels', 'Gorilla Glass Victus', 'Nhôm/Polymer sinh học', '163.2 x 74.7 x 8.2 mm', '189 g', 3, 'OIS', '8K@24fps', '32 MP', '1080p@30fps', 'HDR', 'Android 12', '3.0 GHz', 'Adreno 730', '5G', '2 Nano SIM', 'Wi-Fi 6', '5.2', 'USB Type-C', 'Không', 'GPS', '65W', 'Không', 'Vân tay quang học', 'Không', 'Thiết kế vân giấy'),
	(29, 'Black Shark 5 Pro', 'Xiaomi', '12GB', '256GB', 11500000, 'Snapdragon 8 Gen 1', '144Hz OLED', '4650 mAh', '120W', '108MP + 13MP + 5MP', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Phím trigger vật lý độc quyền, cảm giác bắn súng như tay cầm thật.', 10, 220, 'new', 5, '85.7%', 'OLED', '1080 x 2400 pixels', 'Glass', 'Kính/Nhôm', '163.9 x 76.5 x 9.5 mm', '220 g', 3, 'PDAF', '4K@60fps', '16 MP', '1080p@30fps', 'HDR', 'Android 12', '3.0 GHz', 'Adreno 730', '5G', '2 Nano SIM', 'Wi-Fi 6', '5.2', 'USB Type-C', 'Không', 'GPS', '120W', 'Không', 'Vân tay (cạnh bên)', 'Không', 'Nút Trigger vật lý pop-up'),
	(30, 'Black Shark 5', 'Xiaomi', '8GB', '128GB', 8500000, 'Snapdragon 870', '144Hz AMOLED', '4650 mAh', '120W', '64MP + 13MP + 2MP', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Hiệu năng mát mẻ, trigger vật lý giá rẻ cho game thủ.', 10, 280, 'new', 4, '85.9%', 'AMOLED', '1080 x 2400 pixels', 'Glass', 'Kính/Nhôm', '163.8 x 76.3 x 10 mm', '218 g', 3, 'PDAF', '4K@60fps', '16 MP', '1080p@30fps', 'HDR', 'Android 12', '3.2 GHz', 'Adreno 650', '5G', '2 Nano SIM', 'Wi-Fi 6', '5.2', 'USB Type-C', 'Không', 'GPS', '120W', 'Không', 'Vân tay (cạnh bên)', 'Không', 'Nút Trigger vật lý'),
	(31, 'Xiaomi 14 Pro', 'Xiaomi', '16GB', '512GB', 19500000, 'Snapdragon 8 Gen 3', '120Hz 2K LTPO', '4880 mAh', '120W', '50MP + 50MP + 50MP', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Cân bằng hoàn hảo giữa chơi game và nhiếp ảnh Leica.', 10, 150, 'new', 5, '89.5%', 'LTPO AMOLED', '1440 x 3200 pixels', 'Xiaomi Longjing Glass', 'Kính/Nhôm/Titanium', '161.4 x 75.3 x 8.5 mm', '223 g', 3, 'Leica lens, OIS', '8K@24fps', '32 MP', '4K@30/60fps', 'HDR', 'HyperOS', '3.3 GHz', 'Adreno 750', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.4', 'USB Type-C 3.2', 'Không', 'GPS', '120W dây, 50W không dây', 'Không', 'Vân tay quang học', 'IP68', 'Camera Leica khẩu độ kép'),
	(32, 'Xiaomi 14', 'Xiaomi', '12GB', '256GB', 16500000, 'Snapdragon 8 Gen 3', '120Hz 1.5K LTPO', '4610 mAh', '90W', '50MP + 50MP + 50MP', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Kích thước nhỏ gọn nhưng mang sức mạnh của mãnh thú.', 10, 340, 'new', 5, '89.3%', 'LTPO OLED', '1200 x 2670 pixels', 'Gorilla Glass Victus', 'Kính/Nhôm', '152.8 x 71.5 x 8.2 mm', '188 g', 3, 'Leica lens, OIS', '8K@24fps', '32 MP', '4K@30/60fps', 'HDR', 'HyperOS', '3.3 GHz', 'Adreno 750', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.4', 'USB Type-C 3.2', 'Không', 'GPS', '90W dây, 50W không dây', 'Không', 'Vân tay quang học', 'IP68', 'Siêu gọn nhẹ'),
	(33, 'Redmi K70 Pro', 'Xiaomi', '12GB', '256GB', 11500000, 'Snapdragon 8 Gen 3', '120Hz 2K OLED', '5000 mAh', '120W', '50MP + 50MP + 12MP', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Vô địch tầm giá 11 triệu với chip Snap 8 Gen 3.', 10, 420, 'new', 5, '89.0%', 'OLED', '1440 x 3200 pixels', 'Glass', 'Kính/Nhôm', '160.9 x 75 x 8.2 mm', '209 g', 3, 'OIS', '8K@24fps', '16 MP', '1080p@30/60fps', 'HDR', 'HyperOS', '3.3 GHz', 'Adreno 750', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.4', 'USB Type-C', 'Không', 'GPS', '120W', 'Không', 'Vân tay quang học', 'Không', 'Màn sáng 4000 nits'),
	(34, 'Redmi K70', 'Xiaomi', '12GB', '256GB', 8500000, 'Snapdragon 8 Gen 2', '120Hz 2K OLED', '5000 mAh', '120W', '50MP + 8MP + 2MP', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Mẫu máy phá đảo doanh số nhờ mức giá không tưởng.', 10, 650, 'new', 5, '89.0%', 'OLED', '1440 x 3200 pixels', 'Glass', 'Kính/Nhôm', '160.9 x 75 x 8.2 mm', '209 g', 3, 'OIS', '8K@24fps', '16 MP', '1080p@30/60fps', 'HDR', 'HyperOS', '3.2 GHz', 'Adreno 740', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.3', 'USB Type-C', 'Không', 'GPS', '120W', 'Không', 'Vân tay quang học', 'Không', 'Tản nhiệt xịn'),
	(35, 'Poco F6 Pro', 'Xiaomi', '12GB', '256GB', 12500000, 'Snapdragon 8 Gen 2', '120Hz 2K OLED', '5000 mAh', '120W', '50MP + 8MP + 2MP', 'https://images.unsplash.com/photo-1616348436168-de43ad0db179?w=500', 'Bản quốc tế của K70, phần mềm tối ưu không trễ thông báo.', 10, 210, 'used', 4, '89.0%', 'OLED', '1440 x 3200 pixels', 'Glass', 'Kính/Nhôm', '160.9 x 75 x 8.2 mm', '209 g', 3, 'OIS', '8K@24fps', '16 MP', '1080p@30/60fps', 'HDR', 'HyperOS Global', '3.2 GHz', 'Adreno 740', '5G', '2 Nano SIM', 'Wi-Fi 7', '5.3', 'USB Type-C', 'Không', 'GPS', '120W', 'Không', 'Vân tay quang học', 'Không', 'Bản ROM Global');

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