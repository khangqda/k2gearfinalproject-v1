<?php 
session_start();
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh"); 
mysqli_set_charset($conn, "utf8mb4"); 

// Lấy thông tin giỏ hàng
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_cart_amount = 0;

// ==========================================
// 1. TẠO MẢNG DỮ LIỆU DỊCH VỤ (GIỮ NGUYÊN ẢNH VÀ GIÁ CỦA BẠN)
// ==========================================
$services = [
    [
        'name' => 'Dịch vụ thay công tắc (switch) bàn phím cơ - Không có hotswap',
        'price' => 200000,
        'old_price' => 0,
        'img' => 'https://bizweb.dktcdn.net/thumb/medium/100/329/122/products/dich-vu-lap-dat-nang-cap-pc-dd4999f2-4092-45ad-921a-4024618269e9.jpg?v=1723277542897',
        'discount' => ''
    ],
    [
        'name' => 'Dịch vụ thay đèn led bàn phím',
        'price' => 150000,
        'old_price' => 0,
        'img' => 'https://bizweb.dktcdn.net/thumb/medium/100/329/122/products/dich-vu-lap-dat-nang-cap-pc-dd4999f2-4092-45ad-921a-4024618269e9.jpg?v=1723277542897',
        'discount' => ''
    ],
    [
        'name' => 'Dịch vụ Lắp đặt, Nâng cấp PC',
        'price' => 50000,
        'old_price' => 0,
        'img' => 'https://bizweb.dktcdn.net/thumb/medium/100/329/122/products/dich-vu-lap-dat-nang-cap-pc-dd4999f2-4092-45ad-921a-4024618269e9.jpg?v=1723277542897',
        'discount' => ''
    ],
    [
        'name' => 'Dịch vụ cài đặt Windows và phần mềm cơ bản',
        'price' => 99000,
        'old_price' => 150000,
        'img' => 'https://bizweb.dktcdn.net/thumb/medium/100/329/122/products/dich-vu-cai-dat-5cbd6715-0320-46c1-8a42-b3d41d72d328.jpg?v=1758623082270',
        'discount' => '-34%'
    ],
    [
        'name' => 'Dịch vụ tháo công tắc (Switch) bàn phím cơ - Có hotswap',
        'price' => 100000,
        'old_price' => 0,
        'img' => 'https://bizweb.dktcdn.net/thumb/medium/100/329/122/products/dich-vu-lap-dat-nang-cap-pc-dd4999f2-4092-45ad-921a-4024618269e9.jpg?v=1723277542897',
        'discount' => ''
    ]
];

// ==========================================
// 2. BẮT SỰ KIỆN SẮP XẾP TỪ URL
// ==========================================
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'new';

if ($sort == 'name_asc') {
    usort($services, function($a, $b) { return strcmp($a['name'], $b['name']); });
} elseif ($sort == 'name_desc') {
    usort($services, function($a, $b) { return strcmp($b['name'], $a['name']); });
} elseif ($sort == 'price_asc') {
    usort($services, function($a, $b) { return $a['price'] - $b['price']; });
} elseif ($sort == 'price_desc') {
    usort($services, function($a, $b) { return $b['price'] - $a['price']; });
}
// Nếu $sort == 'new' thì giữ nguyên mảng gốc
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ thu phí - K2 GEAR</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ÉP FOOTER XUỐNG ĐÁY */
        body { display: flex; flex-direction: column; min-height: 100vh; margin: 0; background-color: #f4f6f8; font-family: Arial, sans-serif; }
        .main-footer { margin-top: auto; width: 100%; }
        
        /* CSS CHO TRANG DỊCH VỤ */
        .page-container { flex: 1; max-width: 1200px; margin: 0 auto; width: 100%; padding: 20px 15px; box-sizing: border-box; background: #fff; margin-top: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        
        .breadcrumb { font-size: 13px; color: #888; margin-bottom: 25px; }
        .breadcrumb a { color: #888; text-decoration: none; }
        .breadcrumb a:hover { color: #ce0707; }
        .breadcrumb span { color: #333; font-weight: bold; }

        .filter-pills { display: flex; gap: 12px; margin-bottom: 25px; }
        .btn-pill { padding: 8px 18px; border: 1px solid #ddd; background: #fff; border-radius: 20px; font-size: 14px; color: #333; cursor: pointer; transition: 0.2s; }
        .btn-pill:hover { border-color: #ce0707; color: #ce0707; }

        .page-title { font-size: 24px; color: #333; margin: 0 0 20px 0; font-weight: normal;}

        .sort-bar { display: flex; gap: 25px; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 30px; font-size: 14px; }
        .sort-bar span { color: #333; font-weight: bold; }
        .sort-bar a { color: #666; text-decoration: none; transition: 0.2s; padding-bottom: 5px;}
        .sort-bar a:hover, .sort-bar a.active { color: #ce0707; font-weight: bold; border-bottom: 2px solid #ce0707;}

        /* Lưới hiển thị dịch vụ (Giống sản phẩm) */
        .service-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .service-card { border: 1px solid #eee; border-radius: 8px; padding: 15px; background: #fff; transition: 0.3s; position: relative; display: flex; flex-direction: column;}
        .service-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #ddd; transform: translateY(-3px); }
        .service-img { width: 100%; height: 180px; object-fit: contain; margin-bottom: 15px; }
        .service-name { font-size: 14px; color: #333; margin: 0 0 10px 0; line-height: 1.4; flex: 1; }
        .service-name:hover { color: #ce0707; }
        
        .price-box { display: flex; align-items: center; gap: 10px; margin-top: auto; }
        .price-new { color: #ce0707; font-weight: bold; font-size: 16px; }
        .price-old { color: #999; text-decoration: line-through; font-size: 13px; }
        .discount-tag { position: absolute; top: 10px; left: 10px; background: #ce0707; color: #fff; padding: 3px 8px; font-size: 12px; font-weight: bold; border-radius: 3px; }
    </style>
</head>
<body>

    <header class="main-header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="logo"><a href="main.php" style="text-decoration:none;"><span class="k2">K2</span> <span class="gear">GEAR</span></a></div>
            </div>
            <form action="category.php" method="GET" class="main-search"><input type="text" name="search" placeholder="Bạn cần tìm linh kiện gì?"><button type="submit"><i class="fas fa-search"></i></button></form>
            <div class="header-actions">
                <div class="account-wrapper" style="position: relative; display: flex; align-items: center;">
                    <a href="#" class="account-icon" id="accountToggleBtn" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                        <?php if(isset($_SESSION['user'])): ?>
                            <i class="fas fa-user-circle" style="color: #3b66cc; font-size: 24px;"></i>
                            <span style="font-size: 13px; font-weight: bold; color: #fff; margin-left: 8px;">
                                <?php $n = explode(' ', $_SESSION['user']['name']); echo end($n); ?>
                            </span>
                        <?php else: ?>
                            <i class="far fa-user"></i>
                        <?php endif; ?>
                    </a>
                    
                    <div class="account-popup" id="accountPopup" style="display: none; position: absolute; top: 150%; right: -20px; background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 4px; width: 200px; z-index: 1000; padding: 0; border: 1px solid #eee; text-align: left;">
                        <?php if(isset($_SESSION['user'])): ?>
                            <div style="padding: 15px; border-bottom: 1px solid #eee; background: #f8f9fa;">
                                <strong style="color: #333; font-size: 14px; display: block;"><?php echo $_SESSION['user']['name']; ?></strong>
                                <span style="font-size: 12px; color: #666;"><?php echo $_SESSION['user']['email']; ?></span>
                            </div>
                            <a href="profile.php" style="display: block; padding: 10px 15px; color: #333; text-decoration: none; font-size: 13px;"><i class="fas fa-id-card" style="width: 25px;"></i> Tài khoản của tôi</a>
                            <a href="orders.php" style="display: block; padding: 10px 15px; color: #333; text-decoration: none; font-size: 13px;"><i class="fas fa-box" style="width: 25px;"></i> Đơn hàng của tôi</a>
                            <a href="logout.php" style="display: block; padding: 10px 15px; color: #ce0707; text-decoration: none; font-size: 13px; border-top: 1px solid #eee; font-weight: bold;"><i class="fas fa-sign-out-alt" style="width: 25px;"></i> Đăng xuất</a>
                        <?php else: ?>
                            <a href="login.php" style="display: block; padding: 12px 15px; color: #333; text-decoration: none; font-size: 14px; font-weight: bold; border-bottom: 1px solid #eee;"><i class="fas fa-sign-in-alt" style="width: 25px; color:#3b66cc;"></i> Đăng nhập</a>
                            <a href="register.php" style="display: block; padding: 12px 15px; color: #333; text-decoration: none; font-size: 14px; font-weight: bold;"><i class="fas fa-user-plus" style="width: 25px; color:#ce0707;"></i> Đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="cart-wrapper" style="position: relative; margin-left: 15px;">
                    <a href="cart.php" class="cart-icon"><i class="fas fa-shopping-cart"></i><span class="cart-count"><?php echo count(array_filter(array_keys($cart_items), 'is_numeric')); ?></span></a>
                </div>
            </div>
        </div>
    </header>

    <nav class="subheader">
        <div class="subheader-content">
            <a href="main.php">Trang chủ</a>
            <a href="services.php">Dịch vụ</a>
            <a href="#">Thu cũ đổi mới</a>
            <a href="#">Tra cứu bảo hành</a>
        </div>
    </nav>

    <main class="page-container">
        
        <div class="breadcrumb">
            <a href="main.php">Trang chủ</a> / <span>Dịch vụ thu phí</span>
        </div>

        <h1 class="page-title">Dịch vụ thu phí</h1>

        <div class="sort-bar">
            <span>Sắp xếp:</span>
            <a href="services.php?sort=name_asc" class="<?php echo ($sort == 'name_asc') ? 'active' : ''; ?>">Tên A &rarr; Z</a>
            <a href="services.php?sort=name_desc" class="<?php echo ($sort == 'name_desc') ? 'active' : ''; ?>">Tên Z &rarr; A</a>
            <a href="services.php?sort=price_asc" class="<?php echo ($sort == 'price_asc') ? 'active' : ''; ?>">Giá tăng dần</a>
            <a href="services.php?sort=price_desc" class="<?php echo ($sort == 'price_desc') ? 'active' : ''; ?>">Giá giảm dần</a>
            <a href="services.php?sort=new" class="<?php echo ($sort == 'new') ? 'active' : ''; ?>">Hàng mới</a>
        </div>

        <div class="service-grid">
            <?php foreach ($services as $svc): ?>
                <div class="service-card">
                    <?php if(!empty($svc['discount'])): ?>
                        <div class="discount-tag"><?php echo $svc['discount']; ?></div>
                    <?php endif; ?>

                    <a href="#" style="text-decoration: none; display: flex; flex-direction: column; height: 100%;">
                        <img src="<?php echo $svc['img']; ?>" class="service-img" alt="<?php echo $svc['name']; ?>">
                        <h3 class="service-name"><?php echo $svc['name']; ?></h3>
                        <div class="price-box">
                            <span class="price-new"><?php echo number_format($svc['price'], 0, ',', '.'); ?> ₫</span>
                            <?php if($svc['old_price'] > 0): ?>
                                <span class="price-old"><?php echo number_format($svc['old_price'], 0, ',', '.'); ?> ₫</span>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="main-footer">
    <div class="footer-container">
        <div class="footer-column">
            <h3 class="footer-logo"><span class="k2">K2</span> <span class="gear" style="color: white;">GEAR</span></h3>
            <p class="footer-desc">K2 GEAR tự hào là đơn vị chuyên cung cấp linh kiện máy tính chính hãng, uy tín. Chúng tôi mang đến cho bạn đa dạng sự lựa chọn để nâng cấp sức mạnh cho hệ thống của mình.</p>
            <div class="footer-contact">
                <p><i class="fas fa-map-marker-alt"></i> TP. Cao Lãnh, Đồng Tháp, Việt Nam</p>
                <p><i class="fas fa-phone-alt"></i> Hotline: 1900 6969</p>
                <p><i class="fas fa-envelope"></i> Email: hotro@k2gear.com</p>
            </div>
        </div>

        <div class="footer-column">
            <h4 class="footer-title">LINH KIỆN NỔI BẬT</h4>
            <ul class="footer-links">
                <li><a href="category.php?id=vga">Card màn hình (VGA)</a></li>
                <li><a href="category.php?id=cpu">Vi xử lý (CPU)</a></li>
                <li><a href="category.php?id=mainboard">Bo mạch chủ (Mainboard)</a></li>
                <li><a href="category.php?id=ram">RAM PC & Laptop</a></li>
                <li><a href="category.php?id=o-cung">Ổ cứng SSD & HDD</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h4 class="footer-title">HỖ TRỢ KHÁCH HÀNG</h4>
            <ul class="footer-links">
                <li><a href="post_detail.php?id=chinh-sach-bao-hanh">Chính sách bảo hành</a></li>
                <li><a href="post_detail.php?id=chinh-sach-doi-tra">Chính sách đổi trả</a></li>
                <li><a href="post_detail.php?id=bao-mat-thong-tin">Bảo mật thông tin</a></li>
                <li><a href="post_detail.php?id=huong-dan-mua-hang">Hướng dẫn mua hàng</a></li>
                <li><a href="post_detail.php?id=huong-dan-thanh-toan">Hướng dẫn thanh toán</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h4 class="footer-title">KẾT NỐI VỚI CHÚNG TÔI</h4>
            <div class="social-icons">
                <a href="https://facebook.com/K2Gear" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://youtube.com/@K2Gear" target="_blank"><i class="fab fa-youtube"></i></a>
                <a href="https://tiktok.com/@k2gear" target="_blank"><i class="fab fa-tiktok"></i></a>
                <a href="https://instagram.com/k2gear.official" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="payment-methods">
                <p>Hỗ trợ thanh toán:</p>
                <img src="https://bizweb.dktcdn.net/100/329/122/themes/1038963/assets/payment.png?1776445287786" alt="Payment Methods" style="border-radius: 4px;">
            </div>
        </div>

    </div>
    
    <div class="footer-bottom">
        <p>&copy; 2026 K2 GEAR. Cửa hàng linh kiện máy tính. All rights reserved.</p>
    </div>
</footer>

    <script>
        const accountToggleBtn = document.getElementById('accountToggleBtn');
        const accountPopup = document.getElementById('accountPopup');
        if(accountToggleBtn && accountPopup) {
            accountToggleBtn.addEventListener('click', function(e) {
                e.preventDefault(); e.stopPropagation();
                accountPopup.style.display = accountPopup.style.display === 'block' ? 'none' : 'block';
            });
            document.addEventListener('click', function(e) {
                if (!accountPopup.contains(e.target) && e.target !== accountToggleBtn) accountPopup.style.display = 'none';
            });
        }
    </script>
</body>
</html>