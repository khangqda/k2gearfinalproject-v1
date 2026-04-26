<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }

$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");

// Lấy dữ liệu mới nhất từ CSDL
$user_id = $_SESSION['user']['id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user_data = mysqli_fetch_assoc($result);

// Cập nhật Session để hiển thị đúng Tên ở Header nếu vừa sửa
$_SESSION['user'] = $user_data;

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_count = count(array_filter(array_keys($cart_items), 'is_numeric'));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tài khoản của tôi - K2 GEAR</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f8; font-family: Arial, sans-serif; }
        .profile-wrapper { max-width: 1200px; margin: 30px auto; display: flex; gap: 30px; padding: 0 15px;}
        
        /* SIDEBAR */
        .profile-sidebar { width: 250px; flex-shrink: 0; }
        .user-brief { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #ddd;}
        .user-brief-avatar { width: 50px; height: 50px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #aaa; }
        .profile-menu { list-style: none; padding: 0; margin: 0; }
        .profile-menu li { margin-bottom: 15px; }
        .profile-menu a { display: block; color: #555; text-decoration: none; font-size: 15px; font-weight: 500;}
        .profile-menu a:hover, .profile-menu a.active { color: #ce0707; font-weight: bold; }
        
        /* NỘI DUNG CHÍNH */
        .profile-content { flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 30px; }
        .profile-grid { display: flex; gap: 40px; margin-top: 20px;}
        
        /* CỘT 1: AVATAR TĨNH */
        .col-avatar { width: 150px; text-align: center; border-right: 1px solid #eee; padding-right: 30px; }
        .big-avatar { width: 100px; height: 100px; background: #f4f4f4; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 40px; color: #ccc; }
        
        /* CỘT 2: FORM THÔNG TIN */
        .col-form { flex: 1.5; padding-right: 40px; border-right: 1px solid #eee; }
        .form-group { margin-bottom: 15px; display: flex; align-items: center;}
        .form-group label { width: 120px; font-size: 14px; color: #555; font-weight: bold;}
        .form-control { flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 20px; font-size: 14px; outline: none; }
        .form-control.readonly { background: #f9f9f9; color: #888; cursor: not-allowed; }
        .btn-save { background: #ce0707; color: white; border: none; padding: 10px 30px; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 20px; margin-left: 120px; }
        .btn-save:hover { background: #a30505; }
        
        /* CỘT 3: BẢO MẬT */
        .col-security { flex: 1; }
        .security-item { display: flex; align-items: center; gap: 10px; margin-bottom: 30px; font-size: 14px; }
        .social-link-item { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; font-size: 14px; color: #3b66cc; cursor: pointer;}
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

    <main class="profile-wrapper">
        <aside class="profile-sidebar">
            <div class="user-brief">
                <div class="user-brief-avatar"><i class="far fa-user"></i></div>
                <div class="user-brief-info">
                    <h4 style="margin:0 0 5px 0;"><?php echo htmlspecialchars($user_data['name']); ?></h4>
                    <span style="font-size:12px; color:#888;">Thành viên</span>
                </div>
            </div>
            <ul class="profile-menu">
                <li><a href="notification.php"><i class="far fa-bell" style="width: 25px; color:#888;"></i> Thông Báo</a></li>
                <li><a href="profile.php" class="active"><i class="far fa-user" style="width: 25px;"></i> Tài Khoản Của Tôi</a></li>
                <li><a href="order.php"><i class="fas fa-clipboard-list" style="width: 25px; color:#888;"></i> Đơn Mua</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt" style="width: 25px; color:#888;"></i> Đăng Xuất</a></li>
            </ul>
        </aside>

        <section class="profile-content">
            <div class="profile-grid">
                
                

                <div class="col-form">
                    <h3 style="margin-top:0; margin-bottom:20px;">Thông tin tài khoản</h3>

                    <?php if(isset($_SESSION['msg'])) { echo '<p style="color:green; font-weight:bold; font-size:14px; margin-bottom:15px;"><i class="fas fa-check-circle"></i> '.$_SESSION['msg'].'</p>'; unset($_SESSION['msg']); } ?>

                    <form action="update_profile.php" method="POST">
                        <div class="form-group">
                            <label>Họ và Tên</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control readonly" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user_data['phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($user_data['address']); ?>">
                        </div>
                        <button type="submit" class="btn-save">LƯU THÔNG TIN</button>
                    </form>
                </div>

                <div class="col-security">
                    <h3 style="margin-top:0;">Bảo mật</h3>
                    <div class="security-item"><i class="fas fa-lock" style="color:#888; font-size:18px;"></i> <a href="change_password.php" style="color:#3b66cc; text-decoration:none; font-weight:bold;">Đổi mật khẩu</a></div>
                    <h3 style="margin-top:30px;">Liên kết tài khoản</h3>
                    <div class="social-link-item"><i class="fab fa-facebook" style="color:#1877F2; font-size:18px;"></i> Liên kết Facebook</div>
                    <div class="social-link-item"><i class="fab fa-google" style="color:#EA4335; font-size:18px;"></i> Liên kết Google</div>
                </div>

            </div>
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