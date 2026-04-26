-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 26, 2026 lúc 10:57 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `linhkienmaytinh`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`) VALUES
(1, 'ASUS', 'asus'),
(2, 'Gigabyte', 'gigabyte'),
(3, 'MSI', 'msi'),
(4, 'Intel', 'intel'),
(5, 'AMD', 'amd'),
(6, 'Corsair', 'corsair'),
(7, 'Kingston', 'kingston'),
(8, 'Logitech', 'logitech'),
(9, 'Samsung', 'samsung'),
(10, 'Western Digital', 'wd'),
(11, 'Seagate', 'seagate'),
(12, 'Sandisk', 'sandisk'),
(13, 'Xigmatek', 'xigmatek'),
(14, 'NZXT', 'nzxt'),
(15, 'Razer', 'razer');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `status`) VALUES
(1, 'Mainboard', 'mainboard', 1),
(2, 'CPU - Vi xử lý', 'cpu', 1),
(3, 'Card Màn Hình (VGA)', 'vga', 1),
(4, 'RAM PC - Laptop', 'ram', 1),
(5, 'Ổ cứng SSD/HDD', 'o-cung', 1),
(6, 'Màn Hình', 'man-hinh', 1),
(7, 'Chuột Gaming & Văn phòng', 'chuot', 1),
(8, 'Bàn Phím Cơ', 'ban-phim', 1),
(9, 'Ổ cứng SSD - HDD', 'o-cung', 1),
(10, 'RAM PC - Laptop', 'ram', 1),
(11, 'Thẻ nhớ - USB', 'usb', 1),
(12, 'Vỏ máy tính (Case)', 'case', 1),
(13, 'Nguồn máy tính (PSU)', 'psu', 1),
(14, 'Thiết bị mạng', 'thiet-bi-mang', 1),
(15, 'Phụ kiện Laptop', 'phu-kien-laptop', 1),
(16, 'Dây cáp & Đầu chuyển', 'cap-ket-noi', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `type` enum('percent','fixed') DEFAULT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `max_usage` int(11) DEFAULT NULL,
  `used` int(11) DEFAULT 0,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `max_usage`, `used`, `expiry_date`) VALUES
(1, 'K2GEAR100', 'percent', 100.00, 100, 1, '2026-04-30'),
(2, 'K2GEAR8', 'percent', 8.00, 100, 0, '2026-04-14'),
(3, 'K2GEAR10', 'percent', 10.00, 100, 0, '2026-04-14'),
(4, 'K2SCREEN', 'fixed', 350000.00, 50, 0, '2026-04-14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL COMMENT 'order, voucher, system',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0 COMMENT '0: chưa đọc, 1: đã đọc',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `content`, `is_read`, `created_at`) VALUES
(1, 2, 'order', 'Cập nhật đơn hàng #5', 'Đơn hàng của bạn đã được chuyển sang trạng thái: <b>Đang giao hàng</b>.', 1, '2026-04-26 09:50:41'),
(2, 2, 'order', 'Cập nhật đơn hàng #5', 'Đơn hàng của bạn đã được chuyển sang trạng thái: <b>Đã giao (Hoàn thành)</b>.', 1, '2026-04-26 09:51:11'),
(3, 2, 'order', 'Cập nhật đơn hàng #5', 'Đơn hàng của bạn đã được chuyển sang trạng thái: <b>Chờ xử lý</b>.', 0, '2026-04-26 09:52:10'),
(4, 2, 'order', 'Cập nhật đơn hàng #3', 'Đơn hàng của bạn đã được chuyển sang trạng thái: <b>Đang giao hàng</b>.', 0, '2026-04-26 09:52:23'),
(5, 2, 'order', 'Cập nhật đơn hàng #5', 'Đơn hàng của bạn đã được chuyển sang trạng thái: <b>Đã xác nhận</b>.', 0, '2026-04-26 09:52:39'),
(6, 2, 'order', 'Cập nhật đơn hàng #5', 'Đơn hàng của bạn đã được chuyển sang trạng thái: <b>Đã giao (Hoàn thành)</b>.', 0, '2026-04-26 09:52:54'),
(7, 2, 'order', 'Cập nhật đơn hàng #5', 'Đơn hàng của bạn đã được chuyển sang trạng thái: <b>Đã hủy</b>.', 0, '2026-04-26 09:53:08'),
(8, 2, 'order', 'Đặt hàng thành công', 'Đơn hàng <b>#6</b> của bạn đã được tiếp nhận và đang ở trạng thái <b>Chờ xử lý</b>.', 0, '2026-04-26 14:48:07'),
(9, 2, 'order', 'Cập nhật đơn hàng #6', 'Đơn hàng của bạn đã được chuyển sang trạng thái: <b>Chờ xử lý</b>.', 0, '2026-04-26 15:01:55'),
(10, 2, 'order', 'Cập nhật đơn hàng #6', 'Đơn hàng của bạn đã được chuyển sang trạng thái: <b>Đã xác nhận</b>.', 0, '2026-04-26 15:03:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','shipping','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT 'COD',
  `shipping_address` varchar(50) DEFAULT NULL,
  `note` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `created_at`, `payment_method`, `shipping_address`, `note`) VALUES
(3, 2, 2950000.00, 'shipping', '2026-04-23 09:25:02', 'cod', '139/1, Xã Tân Phú Đông, Thành phố Sa Đéc, Tỉnh Đồn', ''),
(5, 2, 0.00, 'cancelled', '2026-04-23 09:54:21', 'cod', '139/1 Quốc Lộ 80, Xã Tân Phú Đông, Thành phố Sa Đé', ''),
(6, 2, 3500000.00, 'confirmed', '2026-04-26 07:48:07', 'cod', '139/1 Quốc Lộ 80, Xã Tân Phú Đông, Thành phố Sa Đé', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `price`, `quantity`) VALUES
(1, 3, 22, 2950000.00, 1),
(5, 5, 1, 3990000.00, 1),
(6, 5, 4, 3500000.00, 1),
(7, 5, 8, 8900000.00, 1),
(8, 5, 7, 6900000.00, 1),
(9, 6, 4, 3500000.00, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `status` enum('pending','paid','failed') DEFAULT 'pending',
  `transaction_code` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `thumbnail` varchar(500) DEFAULT 'https://via.placeholder.com/200x200?text=No+Image',
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `thumbnail`, `category_id`, `brand_id`, `price`, `sale_price`, `stock`, `description`, `status`, `created_at`) VALUES
(1, 'Mainboard ASUS ROG STRIX B550-A GAMING', 'main-asus-rog-strix-b550a', 'https://dlcdnwebimgs.asus.com/gain/E11F9021-A684-4848-98D6-6DB2841EDE5D/w717/h525/fwebp', 1, 1, 4500000.00, 3990000.00, 15, 'Mainboard chuẩn ATX, socket AM4 hỗ trợ Ryzen cực tốt, tản nhiệt VRM xịn xò.', 1, '2026-03-30 08:00:46'),
(2, 'Mainboard GIGABYTE B650M AORUS ELITE AX', 'main-gigabyte-b650m-aorus', 'https://product.hstatic.net/200000722513/product/0824_323f00144e1f16897bdc9ffeb7419920_16fcea04fa8e470380ebd6373045048b_9ef8f9a4db084adea8cc93e3a74663ea_master.jpg', 1, 2, 5200000.00, 4850000.00, 10, 'Mainboard socket AM5 mới nhất, hỗ trợ RAM DDR5.', 1, '2026-03-30 08:00:46'),
(3, 'Mainboard MSI MAG B760M MORTAR WIFI', 'main-msi-mag-b760m', 'https://product.hstatic.net/200000837185/product/mainboard-msi-mag-b760m-mortar-wifi-ddr4-_-socket-intel-lga-1700-9mwcp_732fab73347941bd8f922898e26699f2_grande.jpg', 1, 3, 4800000.00, 4500000.00, 8, 'Bo mạch chủ Intel thế hệ 13/14, tích hợp Wifi 6E.', 1, '2026-03-30 08:00:46'),
(4, 'CPU AMD Ryzen 5 5600X (6 Nhân / 12 Luồng)', 'cpu-amd-ryzen-5-5600x', 'https://product.hstatic.net/200000722513/product/3-7ghz-boost-4-6ghz-6-nhan-12-luong-1_064ea02033974b0fae49158951cc74dd_b68071833a2a4ca797c7c330d6cf8412_master.png', 2, 5, 4200000.00, 3500000.00, 20, 'CPU quốc dân cho anh em game thủ, hiệu năng vượt trội trong tầm giá.', 1, '2026-03-30 08:00:46'),
(5, 'CPU Intel Core i5 13400F', 'cpu-intel-core-i5-13400f', 'https://product.hstatic.net/200000722513/product/box-t4-i5f-13th-right-1080x1080pixels_1b54165ec2cc4ff1a0b964ffa582cfed_cc992b94f1524dabad02ce131c54fdcd_master.png', 2, 4, 5500000.00, 5200000.00, 12, '10 nhân 16 luồng, chiến mượt mọi tựa game AAA.', 1, '2026-03-30 08:00:46'),
(6, 'VGA ASUS Dual GeForce RTX 4060 OC 8GB', 'vga-asus-rtx-4060', 'https://bizweb.dktcdn.net/100/329/122/products/vga-asus-dual-geforce-rtx-4060-oc-edition-8gb-gddr6-dual-rtx4060-o8g.jpg?v=1743638689987', 3, 1, 8900000.00, 8200000.00, 5, 'Card đồ họa mát mẻ, công nghệ DLSS 3 siêu mượt.', 1, '2026-03-30 08:00:46'),
(7, 'VGA GIGABYTE Radeon RX 7600 GAMING OC 8G', 'vga-gigabyte-rx-7600', 'https://bizweb.dktcdn.net/thumb/grande/100/329/122/products/vga-gigabyte-radeon-rx-7600-gaming-oc-8g-gddr6-gv-r76gaming-oc-8gd-08.jpg?v=1740047635913', 3, 2, 7500000.00, 6900000.00, 7, 'Lựa chọn p/p tuyệt vời từ đội đỏ AMD.', 1, '2026-03-30 08:00:46'),
(8, 'RAM Corsair Vengeance LPX 16GB (1x16GB) DDR4 3200MHz', 'ram-corsair-16gb-3200', 'https://bizweb.dktcdn.net/thumb/grande/100/329/122/products/ram-pc-corsair-vengeance-lpx-8gb-3200mhz-ddr4-cmk16gx4m2e3200c16-8-26cb337e-4122-4bb7-bc55-54f38f4f086a-3b650c5f-51f8-4890-8951-95206ff25145.jpg?v=1758522711710', 4, 6, 1100000.00, 8900000.00, 50, 'RAM tản nhiệt nhôm đen, bus cao ổn định.', 1, '2026-03-30 08:00:46'),
(9, 'RAM Kingston Fury Beast 8GB DDR4 3200MHz', 'ram-kingston-8gb-3200', 'https://phucuongpc.vn/uploads/screenshot_1740366562.png', 4, 7, 700000.00, 4900000.00, 45, 'Phù hợp để cắm Dual Channel, giá hạt dẻ.', 1, '2026-03-30 08:00:46'),
(10, 'Màn hình ASUS TUF Gaming VG27AQ 2K', 'man-hinh-asus-vg27aq', 'https://product.hstatic.net/200000722513/product/asus_vg27aq_gearvn_c4eb253c0ed04dbfad9ef3ab49fd0e8c_ad7a29d0cdbc4ce99bd19176ef02f74c_master.jpg', 6, 1, 8500000.00, 7500000.00, 5, 'Màn hình 2K 165Hz IPS cực nét', 1, '2026-03-30 08:34:50'),
(11, 'Màn hình MSI Optix G241 24 inch 144Hz', 'man-hinh-msi-g241', 'https://lh3.googleusercontent.com/RdDGYgZ-MRUHJKU1SxbQ-Cu6kr0MpNFzOU5MRpRz_oWig-l3cjP2sxUC66IcADg3s2dzDb4xhHlPLCcAp7-aGAqWadksKYoW=w500-rw', 6, 3, 4500000.00, 3800000.00, 10, 'Màn hình quốc dân cho game thủ eSports', 1, '2026-03-30 08:34:50'),
(12, 'Chuột Không Dây Logitech G304 LightSpeed', 'chuot-logitech-g304', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcTkIIHPcIpBxqGz7lmkH1MTK5zrYPUa0Hh7mcFRqWAK-bYwbEHjsOAl17h3TC1-Cikq2Ck981aHkcX5a6SHpxtuoeK4fxVoQYwtC84qLA7pzrBSfg09gGpqX28-PbNmJvkxW79ualg&usqp=CAc', 7, 8, 1000000.00, 790000.00, 30, 'Chuột gaming không dây quốc dân', 1, '2026-03-30 08:34:50'),
(13, 'Chuột Corsair Harpoon RGB Pro', 'chuot-corsair-harpoon', 'https://product.hstatic.net/200000722513/product/ming-corsair-harpoon-pro-rgb-1_-_copy_8fd71a20c88e4d5ba2231e87ca404bdf_1a947ad7ab5d4436ae3c8f2b29f7324e_master.png', 7, 6, 650000.00, 490000.00, 20, 'Nhỏ gọn, led RGB siêu đẹp', 1, '2026-03-30 08:34:50'),
(14, 'Bàn phím cơ Logitech G Pro X', 'phim-logitech-g-pro-x', 'https://product.hstatic.net/200000722513/product/thumbphim_9fb12e4f19d94b31aeb8cc81546d86df_b2aa143d682b4850a8f2abe30706a659_master.png', 8, 8, 3500000.00, 2800000.00, 15, 'Bàn phím cơ TKL cho dân chuyên nghiệp', 1, '2026-03-30 08:34:50'),
(15, 'Bàn phím cơ ASUS ROG Strix Scope', 'phim-asus-rog', 'https://dlcdnwebimgs.asus.com/gain/4E1F439C-2DF4-46D0-A3E6-79BE8E02E338/w717/h525/fwebp', 8, 1, 3200000.00, 2990000.00, 10, 'Phím cơ chuẩn gaming, Switch độc quyền', 1, '2026-03-30 08:34:50'),
(16, 'SSD Samsung 980 PRO 1TB M.2 NVMe', 'ssd-samsung-980-pro-1tb', 'https://product.hstatic.net/200000722513/product/ng-980-pro-1tb-m-2-nvme-mz-v8p1t0bw-4_c4ff261dd6374342857f7516ca984e65_aead263e46a047cb99989f3ca4a65117_master.jpg', 9, 9, 2500000.00, 2250000.00, 20, 'Tốc độ đọc ghi cực cao, bảo hành 5 năm.', 1, '2026-03-30 10:02:45'),
(17, 'HDD Seagate Barracuda 2TB 7200RPM', 'hdd-seagate-2tb', 'https://product.hstatic.net/200000722513/product/hdd_seagate_baracuda_2tb_gearvn00_28582504c8d24597908c3a73effefa7a_e147c85ec46148acbdc7c7f8a729b68c_master.jpg', 9, 11, 1500000.00, 1350000.00, 15, 'Lưu trữ dữ liệu an toàn, dung lượng lớn.', 1, '2026-03-30 10:02:45'),
(18, 'RAM Corsair Vengeance RGB RS 16GB (2x8GB) 3200MHz', 'ram-corsair-16gb-rgb', 'https://bizweb.dktcdn.net/thumb/grande/100/329/122/products/ram-pc-corsair-vengeance-rgb-rs-16gb-3200mhz-ddr4-2x8gb-cmg16gx4m2e3200c16-2-0971d69c-bdac-4945-9dd6-f9a96c558a7f-0e595b5f-9d97-4e0e-8d17-eb01535aba24-bbc1f436-be40-4387-aa65-79ed07c3f405.png?v=1', 10, 6, 1800000.00, 1650000.00, 25, 'Led RGB đồng bộ cực đẹp.', 1, '2026-03-30 10:02:45'),
(19, 'USB 3.0 Sandisk Ultra Flair 64GB', 'usb-sandisk-64gb', 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/u/s/usb-3-0-sandisk-cz73-ultra-flair-64gb_1.png', 11, 12, 250000.00, 190000.00, 100, 'Vỏ kim loại bền bỉ, tốc độ cao.', 1, '2026-03-30 10:02:45'),
(20, 'Vỏ Case Xigmatek Aquarius Plus Black', 'case-xigmatek-aquarius', 'https://product.hstatic.net/200000722513/product/s-case-xigmatek-aquarius-plus-black-8_a87ee72690874f23bb6b4fa64bbdbbbb_b34d2ef587b54bcba88995fd6f185ae0_master.png', 12, 13, 1600000.00, 1450000.00, 10, 'Thiết kế bể cá kính cường lực.', 1, '2026-03-30 10:02:45'),
(21, 'Vỏ Case NZXT H5 Flow White', 'case-nzxt-h5-flow', 'https://product.hstatic.net/200000722513/product/1666637882-h5-flow-top-with-gpu-white_80e57b6fa39d49d2b03ca25892810dfa_961e47c77ec84697a8bbdd9e5af6f515_master.png', 12, 14, 2300000.00, 2100000.00, 0, 'Tối ưu luồng khí, thiết kế tối giản.', 1, '2026-03-30 10:02:45'),
(22, 'Nguồn Corsair RM850e 850W 80 Plus Gold', 'psu-corsair-rm850e', 'https://bizweb.dktcdn.net/thumb/1024x1024/100/329/122/products/nguon-may-tinh-corsair-rm850e-atx-3-1-cybenetics-gold-850w-80-plus-gold-cp-9020296-na-07.jpg?v=1749215752677', 13, 6, 3200000.00, 2950000.00, 10, 'Nguồn chuẩn Gold, full modular.', 1, '2026-03-31 14:22:28'),
(25, 'Card màn hình GIGABYTE GeForce RTX 3050 WINDFORCE OC V2 8G', 'vga-gigabyte-rtx-3050', 'https://cdn.hstatic.net/products/200000722513/gearvn-card-man-hinh-gigabyte-geforce-rtx-3050-windforce-oc-v2-8g-1_46f8826f266842bbb624e976c8d5b854_master.png', 3, 2, 8490000.00, 8390000.00, 20, 'Lùa mấy con gà', 0, '2026-04-23 04:00:25'),
(26, 'Bộ vi xử lý Intel Core i3 14100 / Turbo up to 4.7GHz / 4 Nhân 8 Luồng / 12MB / LGA 1700 (Tray)', 'intel-core-i3-14100', 'https://cdn.hstatic.net/products/200000722513/bo-vi-xu-ly-intel-core-i3-14100-1-6_2f9d83d4c0cd409f845525d3aeb7addc_master.jpg', 2, 4, 4490000.00, 4290000.00, 32, 'abc', 1, '2026-04-23 04:19:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','staff','customer') DEFAULT 'customer',
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `status`, `created_at`, `reset_token`, `reset_token_expire`) VALUES
(1, 'Tô Phú Khang', 'khang@k2gear.com', '$2y$10$e1t2dRfiwCsyL3obRWJa2u8ZejaabXhmjZ2Ynzwx2b4ME1CVZcgla', '0812655955', 'Sa Đéc, Đồng Tháp', 'admin', 1, '2026-04-16 14:37:04', NULL, NULL),
(2, 'Tô Khang', 'khangphut64a@gmail.com', '$2y$10$wjc9H.uklgf/.kyK894EM.uRBtcmK843KO1Chfe6zRlVdgxySI916', '0812655955', '139/1 Quốc Lộ 80', 'customer', 1, '2026-04-18 09:49:41', NULL, NULL),
(3, 'Phạm Công Đăng Khoa', '0023413436@student.dthu.edu.vn', '$2y$10$wNpUxZfnEPqhAeCp4o/sR.vbNymaMz8AmT7vwngyG5WdXhxhiTs76', '0777788730', 'Cao Lãnh, Đồng Tháp', 'admin', 1, '2026-04-22 10:03:05', NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
