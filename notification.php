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

$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'all';

// Xử lý khi user bấm "Đánh dấu đã đọc tất cả"
if (isset($_GET['action']) && $_GET['action'] == 'read_all') {
    mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE user_id = $user_id");
    header("Location: notification.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo của tôi - K2 GEAR</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f8; font-family: Arial, sans-serif; }
        .profile-wrapper { max-width: 1200px; margin: 30px auto; display: flex; gap: 30px; padding: 0 15px;}
        
        .profile-sidebar { width: 250px; flex-shrink: 0; }
        .user-brief { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #ddd;}
        .user-brief-avatar { width: 50px; height: 50px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #aaa; }
        .profile-menu { list-style: none; padding: 0; margin: 0; }
        .profile-menu li { margin-bottom: 15px; }
        .profile-menu a { display: block; color: #555; text-decoration: none; font-size: 15px; font-weight: 500;}
        .profile-menu a:hover, .profile-menu a.active { color: #ce0707; font-weight: bold; }
        
        .profile-content { flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 0; overflow: hidden; }
        .content-header { padding: 25px 30px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .content-header h3 { margin: 0; font-size: 18px; color: #333; }
        .mark-all-read { color: #888; font-size: 13px; text-decoration: none; transition: 0.2s; cursor: pointer; }
        .mark-all-read:hover { color: #ce0707; }

        .noti-tabs { display: flex; background: #fafafa; border-bottom: 1px solid #eee; }
        .tab-item { padding: 15px 25px; color: #555; text-decoration: none; font-size: 14px; font-weight: bold; border-bottom: 2px solid transparent; }
        .tab-item:hover { color: #ce0707; }
        .tab-item.active { color: #ce0707; border-bottom-color: #ce0707; background: #fff; }

        .noti-list { display: flex; flex-direction: column; }
        .noti-item { display: flex; padding: 20px 30px; border-bottom: 1px solid #f5f5f5; transition: 0.2s; text-decoration: none; align-items: flex-start; }
        .noti-item:hover { background-color: #fcfcfc; }
        .noti-item.unread { background-color: #fff9fa; } 
        
        .noti-icon { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; flex-shrink: 0; margin-right: 20px; }
        
        .noti-body { flex: 1; }
        .noti-title { font-size: 15px; color: #333; margin: 0 0 5px 0; }
        .noti-item.unread .noti-title { font-weight: bold; color: #ce0707; }
        
        .noti-desc { font-size: 14px; color: #666; margin: 0 0 8px 0; line-height: 1.5; }
        .noti-date { font-size: 12px; color: #aaa; display: flex; align-items: center; }
        .noti-date i { margin-right: 5px; }

        .btn-view-detail { padding: 6px 15px; border: 1px solid #ddd; background: #fff; color: #555; border-radius: 4px; font-size: 12px; text-decoration: none; margin-top: 10px; display: inline-block; }
        .btn-view-detail:hover { border-color: #ce0707; color: #ce0707; }
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
                    <a href="profile.php" style="font-size:12px; color:#888; text-decoration: none;"><i class="fas fa-pencil-alt"></i> Sửa Hồ Sơ</a>
                </div>
            </div>
            <ul class="profile-menu">
                <li><a href="notification.php" class="active"><i class="far fa-bell" style="width: 25px;"></i> Thông Báo</a></li>
                <li><a href="profile.php"><i class="far fa-user" style="width: 25px; color:#888;"></i> Tài Khoản Của Tôi</a></li>
                <li><a href="order.php"><i class="fas fa-clipboard-list" style="width: 25px; color:#888;"></i> Đơn Mua</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt" style="width: 25px; color:#888;"></i> Đăng Xuất</a></li>
            </ul>
        </aside>

        <section class="profile-content">
            
            <div class="content-header">
                <h3>Thông báo của tôi</h3>
                <a href="notification.php?action=read_all" class="mark-all-read"><i class="fas fa-check-double"></i> Đánh dấu đã đọc tất cả</a>
            </div>

            <div class="noti-tabs">
                <a href="notification.php?tab=all" class="tab-item <?php echo ($current_tab == 'all') ? 'active' : ''; ?>">Tất cả</a>
                <a href="notification.php?tab=order" class="tab-item <?php echo ($current_tab == 'order') ? 'active' : ''; ?>">Cập nhật đơn hàng</a>
                <a href="notification.php?tab=voucher" class="tab-item <?php echo ($current_tab == 'voucher') ? 'active' : ''; ?>">Khuyến mãi</a>
                <a href="notification.php?tab=system" class="tab-item <?php echo ($current_tab == 'system') ? 'active' : ''; ?>">Hệ thống</a>
            </div>

            <div class="noti-list">
                <?php
                // Lọc theo tab
                $where_clause = "user_id = $user_id";
                if ($current_tab == 'order') $where_clause .= " AND type = 'order'";
                elseif ($current_tab == 'voucher') $where_clause .= " AND type = 'voucher'";
                elseif ($current_tab == 'system') $where_clause .= " AND type = 'system'";

                $sql_noti = "SELECT * FROM notifications WHERE $where_clause ORDER BY created_at DESC";
                $res_noti = mysqli_query($conn, $sql_noti);

                if ($res_noti && mysqli_num_rows($res_noti) > 0) {
                    while ($noti = mysqli_fetch_assoc($res_noti)) {
                        
                        // Cài đặt icon và màu sắc tự động theo 'type'
                        $icon = 'fas fa-bell';
                        $color = '#888';
                        if ($noti['type'] == 'order') { $icon = 'fas fa-box-open'; $color = '#26aa99'; }
                        elseif ($noti['type'] == 'voucher') { $icon = 'fas fa-ticket-alt'; $color = '#ee4d2d'; }
                        elseif ($noti['type'] == 'system') { $icon = 'fas fa-bullhorn'; $color = '#3b66cc'; }

                        $unread_class = ($noti['is_read'] == 0) ? 'unread' : '';
                        
                        // Định dạng lại ngày giờ cho đẹp
                        $formatted_date = date('H:i d/m/Y', strtotime($noti['created_at']));

                        // ========================================================
                        // THÊM LOGIC "ĐỌC" NỘI DUNG ĐỂ CHỈ ĐÚNG TAB ĐÍCH
                        // ========================================================
                        $target_tab = 'all'; // Mặc định là tab Tất cả
                        $content_str = mb_strtolower($noti['content'], 'UTF-8'); // Chuyển hết về chữ thường để dễ tìm
                        
                        if (strpos($content_str, 'chờ xử lý') !== false) { $target_tab = 'wait'; }
                        elseif (strpos($content_str, 'đã xác nhận') !== false) { $target_tab = 'confirmed'; }
                        elseif (strpos($content_str, 'đang giao') !== false) { $target_tab = 'shipping'; }
                        elseif (strpos($content_str, 'đã giao') !== false || strpos($content_str, 'hoàn thành') !== false) { $target_tab = 'success'; }
                        elseif (strpos($content_str, 'đã hủy') !== false) { $target_tab = 'cancelled'; }

                        // Gắn tab đích vào đường link
                        $target_link = ($noti['type'] == 'order') ? "order.php?tab=" . $target_tab : "#";
                        // ========================================================
                ?>
                    
                    <a href="<?php echo $target_link; ?>" class="noti-item <?php echo $unread_class; ?>">
                        <div class="noti-icon" style="background-color: <?php echo $color; ?>;">
                            <i class="<?php echo $icon; ?>"></i>
                        </div>
                        <div class="noti-body">
                            <h4 class="noti-title"><?php echo $noti['title']; ?></h4>
                            <p class="noti-desc"><?php echo $noti['content']; ?></p>
                            <span class="noti-date"><i class="far fa-clock"></i> <?php echo $formatted_date; ?></span>
                            
                            <?php if($noti['type'] == 'order'): ?>
                                <div><span class="btn-view-detail">Xem chi tiết đơn hàng</span></div>
                            <?php endif; ?>
                        </div>
                    </a>

                <?php 
                    }
                } else { 
                ?>
                    <div style="text-align: center; padding: 60px 0;">
                        <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/5fafbb923393a712b96488590b8f781f.png" width="100" style="opacity: 0.5;">
                        <p style="color: #888; margin-top: 20px;">Không có thông báo nào ở mục này.</p>
                    </div>
                <?php } ?>
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

</body>
</html>