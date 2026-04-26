<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");

$user_id = $_SESSION['user']['id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user_data = mysqli_fetch_assoc($result);

$_SESSION['user'] = $user_data;

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_count = count(array_filter(array_keys($cart_items), 'is_numeric'));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thay đổi mật khẩu - K2 GEAR</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ÉP FOOTER XUỐNG ĐÁY */
        body { background-color: #f4f6f8; font-family: Arial, sans-serif; display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        .main-footer { margin-top: auto; width: 100%; }
        
        .profile-wrapper { flex: 1; width: 100%; max-width: 1200px; margin: 30px auto; display: flex; gap: 30px; padding: 0 15px; box-sizing: border-box; }
        
        /* SIDEBAR */
        .profile-sidebar { width: 250px; flex-shrink: 0; }
        .user-brief { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #ddd;}
        .user-brief-avatar { width: 50px; height: 50px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #aaa; }
        .profile-menu { list-style: none; padding: 0; margin: 0; }
        .profile-menu li { margin-bottom: 15px; }
        .profile-menu a { display: block; color: #555; text-decoration: none; font-size: 15px; font-weight: 500;}
        .profile-menu a:hover, .profile-menu a.active { color: #ce0707; font-weight: bold; }
        
        /* NỘI DUNG CHÍNH (FORM ĐỔI MẬT KHẨU) */
        .profile-content { flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 30px 40px; }
        
        .pwd-notice { font-size: 14px; color: #555; margin-bottom: 30px; }
        .pwd-form-group { margin-bottom: 20px; max-width: 500px; }
        .pwd-form-group label { display: block; font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #333; }
        .pwd-form-group label span { color: #ce0707; }
        .pwd-form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; outline: none; font-size: 14px; box-sizing: border-box; transition: 0.3s; }
        .pwd-form-control:focus { border-color: #0f8b4d; box-shadow: 0 0 5px rgba(15, 139, 77, 0.2); }
        
        /* Nút Đặt lại mật khẩu màu xanh lá giống mẫu */
        .btn-change-pwd { background: #0f8b4d; color: white; border: none; padding: 12px 25px; border-radius: 4px; font-weight: bold; font-size: 15px; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn-change-pwd:hover { background: #0c6e3d; }
    </style>
</head>
<body>

    <header class="main-header">
        <div class="header-content">
            <div class="logo"><a href="main.php" style="text-decoration: none;"><span class="k2">K2</span> <span class="gear">GEAR</span></a></div>
            <form action="category.php" method="GET" class="main-search"><input type="text" name="search" placeholder="Tìm kiếm..."><button type="submit"><i class="fas fa-search"></i></button></form>
            
            <div class="header-actions">
                <div class="account-wrapper" style="position: relative; display: flex; align-items: center;">
                    <a href="#" class="account-icon" id="accountToggleBtn" style="display: flex; align-items: center; text-decoration: none;">
                        <i class="fas fa-user-circle" style="color: #3b66cc; font-size: 24px;"></i>
                        <span style="font-size: 13px; font-weight: bold; color: #fff; margin-left: 8px;">
                            <?php $n = explode(' ', $user_data['name']); echo end($n); ?>
                        </span>
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
                <a href="cart.php" class="cart-icon" style="margin-left: 15px;"><i class="fas fa-shopping-cart"></i><span class="cart-count"><?php echo $cart_count; ?></span></a>
            </div>
        </div>
    </header>

    <nav class="subheader">
        <div class="subheader-content">
            <a href="main.php">Trang chủ</a> <a href="services.php">Dịch vụ</a> <a href="#">Thu cũ đổi mới</a> <a href="#">Tra cứu bảo hành</a>
        </div>
    </nav>

    <main class="profile-wrapper">
        <aside class="profile-sidebar">
            <div class="user-brief">
                <div class="user-brief-avatar"><i class="far fa-user"></i></div>
                <div class="user-brief-info">
                    <h4 style="margin:0 0 5px 0;"><?php echo htmlspecialchars($user_data['name']); ?></h4>
                    <a href="profile.php" style="font-size:12px; color:#888; text-decoration: none;"><i class="fas fa-pencil-alt"></i> Sửa Hồ Sơ</a>
                </div>
            </div>
            <ul class="profile-menu">
                <li><a href="notification.php"><i class="far fa-bell" style="width: 25px; color:#888;"></i> Thông Báo</a></li>
                <li><a href="profile.php" class="active"><i class="far fa-user" style="width: 25px;"></i> Tài Khoản Của Tôi</a></li>
                <li><a href="orders.php"><i class="fas fa-clipboard-list" style="width: 25px; color:#888;"></i> Đơn Mua</a></li>
                <li><a href="#"><i class="fas fa-map-marker-alt" style="width: 25px; color:#888;"></i> Sổ Địa Chỉ</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt" style="width: 25px; color:#888;"></i> Đăng Xuất</a></li>
            </ul>
        </aside>

        <section class="profile-content">
            <div class="pwd-notice">Để đảm bảo tính bảo mật vui lòng đặt mật khẩu với ít nhất 8 kí tự</div>
            
            <?php 
            if(isset($_SESSION['pwd_error'])) { echo '<p style="color:#ce0707; font-weight:bold; font-size:14px; margin-bottom:20px;"><i class="fas fa-exclamation-triangle"></i> '.$_SESSION['pwd_error'].'</p>'; unset($_SESSION['pwd_error']); } 
            if(isset($_SESSION['pwd_success'])) { echo '<p style="color:#0f8b4d; font-weight:bold; font-size:14px; margin-bottom:20px;"><i class="fas fa-check-circle"></i> '.$_SESSION['pwd_success'].'</p>'; unset($_SESSION['pwd_success']); } 
            ?>

            <form action="update_password.php" method="POST">
                <div class="pwd-form-group">
                    <label>Mật khẩu cũ <span>*</span></label>
                    <input type="password" name="old_password" class="pwd-form-control" required>
                </div>

                <div class="pwd-form-group">
                    <label>Mật khẩu mới <span>*</span></label>
                    <input type="password" name="new_password" class="pwd-form-control" minlength="8" required>
                </div>

                <div class="pwd-form-group">
                    <label>Xác nhận lại mật khẩu <span>*</span></label>
                    <input type="password" name="confirm_password" class="pwd-form-control" minlength="8" required>
                </div>

                <button type="submit" class="btn-change-pwd">Đặt lại mật khẩu</button>
            </form>
        </section>
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
        const btn = document.getElementById('accountToggleBtn');
        const popup = document.getElementById('accountPopup');
        if(btn && popup) {
            btn.addEventListener('click', e => { e.preventDefault(); e.stopPropagation(); popup.style.display = popup.style.display === 'block' ? 'none' : 'block'; });
            document.addEventListener('click', e => { if(!popup.contains(e.target) && e.target !== btn) popup.style.display = 'none'; });
        }
    </script>
</body>
</html>