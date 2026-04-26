<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - K2 GEAR</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .auth-page { background-color: #f4f6f8; padding: 60px 15px; min-height: 60vh;}
        .auth-container { max-width: 550px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .auth-title { text-align: center; font-size: 24px; color: #333; margin-bottom: 10px; text-transform: uppercase; font-weight: bold;}
        .auth-subtitle { text-align: center; font-size: 14px; color: #666; margin-bottom: 30px; }
        .auth-subtitle a { color: #333; text-decoration: underline; font-weight: bold;}
        .auth-subtitle a:hover { color: #3b66cc; }
        
        .section-label { text-align: center; font-size: 16px; color: #333; text-transform: uppercase; margin-bottom: 25px; letter-spacing: 1px;}
        
        .auth-form-group { margin-bottom: 20px; }
        .auth-form-group label { display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #333; }
        .auth-form-group label span { color: #ce0707; }
        .auth-form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; outline: none; font-size: 14px; box-sizing: border-box; }
        .auth-form-control:focus { border-color: #f2c94c; box-shadow: 0 0 5px rgba(242,201,76,0.3); }
        
        /* Nút vàng chuẩn mẫu */
        .auth-btn { width: 100%; padding: 14px; background: #f2c94c; color: #333; border: none; border-radius: 20px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 10px;}
        .auth-btn:hover { background: #e0b83b; }
        
        .auth-divider { text-align: center; margin: 30px 0 20px; position: relative; }
        .auth-divider::before { content: ''; position: absolute; left: 0; top: 50%; width: 25%; height: 1px; background: #ddd; }
        .auth-divider::after { content: ''; position: absolute; right: 0; top: 50%; width: 25%; height: 1px; background: #ddd; }
        .auth-divider span { background: #fff; padding: 0 10px; color: #888; font-size: 13px; }
        
        .social-login { display: flex; gap: 15px; justify-content: center; }
        .btn-social { flex: 1; padding: 10px; border: none; border-radius: 4px; color: white; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 14px;}
        .btn-fb { background: #3b5998; }
        .btn-fb:hover { background: #2d4373; }
        .btn-gg { background: #ea4335; }
        .btn-gg:hover { background: #c23321; }
    </style>
</head>
<body>

    <header class="main-header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="logo">
                    <a href="main.php" style="text-decoration: none;"><span class="k2">K2</span> <span class="gear">GEAR</span></a>
                </div>
            </div>

            <form action="category.php" method="GET" class="main-search">
                <input type="text" name="search" placeholder="Bạn cần tìm linh kiện gì?">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            
            <div class="header-actions">
                <div class="account-wrapper" style="position: relative; display: flex; align-items: center;">
                    <a href="#" class="account-icon" id="accountToggleBtn" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                        <?php if(isset($_SESSION['user'])): ?>
                            <i class="fas fa-user-circle" style="color: #3b66cc; font-size: 24px;"></i>
                            <span style="font-size: 13px; font-weight: bold; color: #fff; margin-left: 8px; white-space: nowrap;">
                                <?php 
                                    // Cắt lấy chữ cuối cùng trong tên (VD: Khang Tô Phú -> Phú)
                                    $name_parts = explode(' ', trim($_SESSION['user']['name']));
                                    echo end($name_parts); 
                                ?>
                            </span>
                        <?php else: ?>
                            <i class="far fa-user"></i>
                        <?php endif; ?>
                    </a>
                    
                    <div class="account-popup" id="accountPopup" style="display: none; position: absolute; top: 150%; right: -20px; background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 4px; width: 200px; z-index: 1000; padding: 0; border: 1px solid #eee; overflow: hidden;">
                        
                        <?php if(isset($_SESSION['user'])): ?>
                            <div style="padding: 15px; border-bottom: 1px solid #eee; background: #f8f9fa;">
                                <strong style="color: #333; font-size: 14px; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo $_SESSION['user']['name']; ?>
                                </strong>
                                <span style="font-size: 12px; color: #666;"><?php echo $_SESSION['user']['email']; ?></span>
                            </div>
                            <a href="profile.php" style="display: block; padding: 10px 15px; color: #333; text-decoration: none; font-size: 13px; transition: 0.2s;"><i class="fas fa-id-card" style="width: 25px; color:#555;"></i> Tài khoản của tôi</a>
                            <a href="order.php" style="display: block; padding: 10px 15px; color: #333; text-decoration: none; font-size: 13px; transition: 0.2s;"><i class="fas fa-box" style="width: 25px; color:#555;"></i> Đơn hàng của tôi</a>
                            <a href="logout.php" style="display: block; padding: 10px 15px; color: #ce0707; text-decoration: none; font-size: 13px; border-top: 1px solid #eee; font-weight: bold; background: #fffafb;"><i class="fas fa-sign-out-alt" style="width: 25px;"></i> Đăng xuất</a>
                        
                        <?php else: ?>
                            <a href="login.php" style="display: block; padding: 12px 15px; color: #333; text-decoration: none; font-size: 14px; font-weight: bold; border-bottom: 1px solid #eee;"><i class="fas fa-sign-in-alt" style="width: 25px; color:#3b66cc;"></i> Đăng nhập</a>
                            <a href="register.php" style="display: block; padding: 12px 15px; color: #333; text-decoration: none; font-size: 14px; font-weight: bold;"><i class="fas fa-user-plus" style="width: 25px; color:#ce0707;"></i> Đăng ký</a>
                        <?php endif; ?>

                    </div>
                </div>

                <div class="cart-wrapper" style="position: relative; margin-left: 15px;">
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i><span class="cart-count"><?php echo count(array_filter(array_keys($cart_items), 'is_numeric')); ?></span>
                    </a>
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

    <main class="auth-page">
        <div class="auth-container">
            <h1 class="auth-title">ĐĂNG KÝ TÀI KHOẢN</h1>
            <p class="auth-subtitle">Bạn đã có tài khoản ? Đăng nhập <a href="login.php">tại đây</a></p>

            <h3 class="section-label">THÔNG TIN CÁ NHÂN</h3>

            <?php if(isset($_SESSION['error'])) { echo '<p style="color:#ce0707; font-size:14px; text-align:center; font-weight:bold;"><i class="fas fa-exclamation-triangle"></i> '.$_SESSION['error'].'</p>'; unset($_SESSION['error']); } ?>

            <form action="process_register.php" method="POST">
                <div class="auth-form-group">
                    <label>Họ <span>*</span></label>
                    <input type="text" name="ho" class="auth-form-control" placeholder="Họ" required>
                </div>

                <div class="auth-form-group">
                    <label>Tên <span>*</span></label>
                    <input type="text" name="ten" class="auth-form-control" placeholder="Tên" required>
                </div>

                <div class="auth-form-group">
                    <label>Số điện thoại <span>*</span></label>
                    <input type="text" name="phone" class="auth-form-control" placeholder="Số điện thoại" required>
                </div>

                <div class="auth-form-group">
                    <label>Email <span>*</span></label>
                    <input type="email" name="email" class="auth-form-control" placeholder="Email" required>
                </div>

                <div class="auth-form-group">
                    <label>Mật khẩu <span>*</span></label>
                    <input type="password" name="password" class="auth-form-control" placeholder="Mật khẩu" required>
                </div>

                <button type="submit" class="auth-btn">Đăng ký</button>
            </form>

            <div class="auth-divider">
                <span>Hoặc đăng nhập bằng</span>
            </div>

            <div class="social-login">
                <button class="btn-social btn-fb"><i class="fab fa-facebook-f"></i> Facebook</button>
                <button class="btn-social btn-gg"><i class="fab fa-google"></i> Google</button>
            </div>
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
                e.preventDefault(); 
                e.stopPropagation();
                accountPopup.style.display = accountPopup.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                if (!accountPopup.contains(e.target) && e.target !== accountToggleBtn) {
                    accountPopup.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>