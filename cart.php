<?php
session_start();

// 1. KẾT NỐI CSDL
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// 2. XỬ LÝ LỆNH TRONG GIỎ HÀNG
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($action == 'add' && $id > 0) {
        if (isset($_SESSION['cart'][$id])) { $_SESSION['cart'][$id]++; } else { $_SESSION['cart'][$id] = 1; }
        $_SESSION['toast_message'] = "Thêm vào giỏ hàng thành công!";
        if (isset($_SERVER['HTTP_REFERER'])) { header("Location: " . $_SERVER['HTTP_REFERER']); } else { header("Location: cart.php"); }
        exit();
    }
    elseif ($action == 'buynow' && $id > 0) {
        if (isset($_SESSION['cart'][$id])) { $_SESSION['cart'][$id]++; } else { $_SESSION['cart'][$id] = 1; }
        header("Location: cart.php");
        exit();
    }
    elseif ($action == 'remove' && $id > 0) {
        if (isset($_SESSION['cart'][$id])) { unset($_SESSION['cart'][$id]); }
        header("Location: cart.php");
        exit();
    }
    elseif ($action == 'update' && $id > 0) {
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        if ($type == 'plus') {
            $_SESSION['cart'][$id]++;
        } elseif ($type == 'minus') {
            if ($_SESSION['cart'][$id] > 1) { $_SESSION['cart'][$id]--; } else { unset($_SESSION['cart'][$id]); }
        }
        header("Location: cart.php");
        exit();
    }
}

$cart_items = $_SESSION['cart'];
$total_amount = 0; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - K2 GEAR</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .cart-page-container { max-width: 1200px; margin: 20px auto; padding: 0 15px; }
        .breadcrumb { font-size: 13px; color: #777; margin-bottom: 20px; }
        .breadcrumb a { color: #3b66cc; text-decoration: none; }
        .cart-layout { display: flex; gap: 30px; align-items: flex-start; }
        .cart-left { flex: 2.5; background: #fff; padding: 20px; border-radius: 8px; }
        .cart-right { flex: 1; position: sticky; top: 100px; }
        .cart-item { display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee; }
        .cart-item img { width: 80px; height: 80px; object-fit: contain; margin-right: 20px; }
        .item-info { flex: 1; }
        .item-name { font-weight: bold; font-size: 14px; margin: 0; color: #333; text-decoration: none;}
        .item-name:hover { color: #3b66cc; }
        .item-price { color: #ce0707; font-weight: bold; width: 120px; text-align: right; }
        .item-qty { display: flex; border: 1px solid #ddd; border-radius: 4px; margin: 0 20px; align-items: center;}
        .item-qty a { background: #f9f9f9; padding: 5px 12px; text-decoration: none; color: black; cursor: pointer; font-weight: bold;}
        .item-qty a:hover { background: #eee; }
        .item-qty input { width: 40px; border: none; text-align: center; border-left: 1px solid #ddd; border-right: 1px solid #ddd; font-weight: bold; height: 100%; padding: 5px 0; outline: none;}
        .summary-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-weight: bold; }
        .total-amount { color: #ce0707; font-size: 20px; }
        .btn-checkout-now { width: 100%; background: #ce0707; color: #fff; border: none; padding: 15px; font-weight: bold; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .empty-cart-msg { text-align: center; padding: 40px; color: #777; }

        /* ================= CSS POPUP MÃ GIẢM GIÁ TRƯỢT (DRAWER) ================= */
        .coupon-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9998; }
        .coupon-drawer { position: fixed; top: 0; right: -420px; width: 400px; max-width: 100%; height: 100vh; background: #f0f2f5; z-index: 9999; transition: right 0.3s ease; display: flex; flex-direction: column; box-shadow: -5px 0 15px rgba(0,0,0,0.1); }
        .coupon-drawer.open { right: 0; }
        .coupon-header { display: flex; align-items: center; justify-content: center; padding: 20px; background: #fff; border-bottom: 1px solid #ddd; position: relative; }
        .coupon-header h3 { margin: 0; font-size: 18px; color: #333; }
        .close-drawer-btn { position: absolute; left: 20px; font-size: 20px; color: #666; cursor: pointer; }
        
        .coupon-body { flex: 1; overflow-y: auto; padding: 15px; }
        .coupon-card { background: #fff; border-radius: 8px; display: flex; margin-bottom: 15px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border: 1px solid #eaeaea;}
        .coupon-left { background: #e8f5e9; padding: 10px; display: flex; align-items: center; justify-content: center; width: 90px; border-right: 2px dashed #c8e6c9; }
        .coupon-right { padding: 15px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;}
        .coupon-title { color: #28a745; font-weight: bold; margin: 0 0 5px 0; font-size: 14px; }
        .coupon-desc { font-size: 12px; color: #555; margin: 0 0 5px 0; line-height: 1.4; }
        .coupon-date { font-size: 11px; color: #888; margin: 0 0 10px 0; }
        .coupon-actions { display: flex; justify-content: space-between; align-items: center; }
        
        .btn-copy { background: #28a745; color: white; border: none; padding: 5px 15px; border-radius: 20px; font-size: 12px; cursor: pointer; font-weight: bold; transition: 0.2s;}
        .btn-copy:hover { background: #218838; }
        .btn-copy.copied { background: #6c757d; }
        .btn-condition { color: #3b66cc; font-size: 12px; text-decoration: none; }
        
        .coupon-footer { padding: 15px; background: #fff; border-top: 1px solid #ddd; }
        .btn-close-bottom { width: 100%; padding: 12px; background: #fff; border: 1px solid #28a745; color: #28a745; font-weight: bold; font-size: 15px; border-radius: 4px; cursor: pointer; }
        .btn-close-bottom:hover { background: #f0fdf4; }
    </style>
</head>
<body style="background-color: #f0f2f5;">

    <div class="coupon-overlay" id="couponOverlay"></div>
    <div class="coupon-drawer" id="couponDrawer">
        <div class="coupon-header">
            <i class="fas fa-arrow-left close-drawer-btn" id="closeDrawerIcon"></i>
            <h3>Mã giảm giá</h3>
        </div>
        <div class="coupon-body">
            <?php
            // Lấy các mã chưa hết hạn và chưa dùng hết lượt
            $sql_coupons = "SELECT * FROM coupons WHERE expiry_date >= CURDATE() AND used < max_usage ORDER BY id ASC";
            $res_coupons = mysqli_query($conn, $sql_coupons);
            
            if ($res_coupons && mysqli_num_rows($res_coupons) > 0) {
                while($cp = mysqli_fetch_assoc($res_coupons)) {
                    $end = date("d.m.Y", strtotime($cp['expiry_date']));
                    
                    // Xử lý tiêu đề hiển thị dựa trên type (phần trăm hay tiền mặt)
                    if ($cp['type'] == 'percent') {
                        $title_hienthi = "Giảm " . $cp['value'] . "%";
                    } else {
                        $title_hienthi = "Giảm " . number_format($cp['value'], 0, ',', '.') . "đ";
                    }

                    // Vì CSDL của bạn không có cột mô tả, mình tự động tạo câu mô tả ngầm định
                    $luot_con_lai = $cp['max_usage'] - $cp['used'];
                    $desc_hienthi = "Áp dụng thanh toán. Số lượt dùng còn lại: " . $luot_con_lai;
                    
                    echo '
                    <div class="coupon-card">
                        <div class="coupon-left">
                            <img src="https://bizweb.dktcdn.net/100/329/122/themes/835213/assets/coupon1_value_img.png" alt="Coupon" style="width:100%; max-width:60px;">
                        </div>
                        <div class="coupon-right">
                            <div>
                                <h4 class="coupon-title">'.$title_hienthi.'</h4>
                                <p class="coupon-desc">'.$desc_hienthi.'</p>
                                <p class="coupon-date">Hạn sử dụng: '.$end.'</p>
                            </div>
                            <div class="coupon-actions">
                                <button class="btn-copy" onclick="copyCoupon(this, \''.$cp['code'].'\')">Sao chép</button>
                                <a href="#" class="btn-condition">Điều kiện</a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo "<p style='text-align:center; color:#888; margin-top:30px;'>Hiện chưa có mã giảm giá nào.</p>";
            }
            ?>
        </div>
        <div class="coupon-footer">
            <button class="btn-close-bottom" id="closeDrawerBtn">Quay lại trang giỏ hàng</button>
        </div>
    </div>
    <header class="main-header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="logo"><a href="main.php" style="text-decoration: none;"><span class="k2">K2</span> <span class="gear">GEAR</span></a></div>
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
                <div class="cart-wrapper">
                    <a href="cart.php" class="cart-icon"><i class="fas fa-shopping-cart"></i><span class="cart-count"><?php echo count($cart_items); ?></span></a>
                </div>
            </div>
        </div>
    </header>

    <nav class="subheader">
        <div class="subheader-content">
            <a href="main.php">Trang chủ</a><a href="services.php">Dịch vụ</a><a href="#">Thu cũ đổi mới</a><a href="#">Tra cứu bảo hành</a>
        </div>
    </nav>

    <main class="cart-page-container">
        <div class="breadcrumb"><a href="main.php">Trang chủ</a> <i class="fas fa-chevron-right" style="font-size: 10px;"></i> <strong>Giỏ hàng</strong></div>
        <h2 style="margin-bottom: 20px;">Giỏ hàng</h2>

        <div class="cart-layout">
            <div class="cart-left">
                <?php
                if (empty($cart_items)) {
                    echo '<div class="empty-cart-msg">
                            <i class="fas fa-box-open" style="font-size: 50px; margin-bottom: 15px; color: #ccc;"></i>
                            <h3>Giỏ hàng của bạn đang trống</h3>
                            <a href="main.php" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background: #3b66cc; color: #fff; text-decoration: none; border-radius: 4px;">Tiếp tục mua sắm</a>
                          </div>';
                } else {
                    $id_list = implode(',', array_keys($cart_items)); 
                    $sql = "SELECT * FROM products WHERE id IN ($id_list)";
                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $p_id = $row['id'];
                        $qty = $cart_items[$p_id]; 
                        $current_price = ($row['sale_price'] > 0) ? $row['sale_price'] : $row['price'];
                        $subtotal = $current_price * $qty;
                        $total_amount += $subtotal; 

                        echo '
                        <div class="cart-item">
                            <a href="product_detail.php?id='.$p_id.'"><img src="'.$row['thumbnail'].'" alt="sp" onerror="this.onerror=null; this.src=\'https://via.placeholder.com/80x80/f4f4f4/cccccc?text=K2+GEAR\'"></a>
                            <div class="item-info">
                                <a href="product_detail.php?id='.$p_id.'" class="item-name">'.$row['name'].'</a>
                                <div style="color: #888; font-size: 13px; margin-top: 5px;">Đơn giá: '.number_format($current_price, 0, ',', '.').' ₫</div>
                            </div>
                            <div class="item-qty">
                                <a href="cart.php?action=update&type=minus&id='.$p_id.'">-</a>
                                <input type="text" value="'.$qty.'" readonly>
                                <a href="cart.php?action=update&type=plus&id='.$p_id.'">+</a>
                            </div>
                            <div class="item-price">'.number_format($subtotal, 0, ',', '.').' ₫</div>
                            <div style="margin-left: 15px;">
                                <a href="cart.php?action=remove&id='.$p_id.'" style="color: #ccc; font-size: 20px; text-decoration: none;">×</a>
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>

            <div class="cart-right">
                <div class="summary-card">
                    <div class="summary-row">
                        <span>TỔNG CỘNG</span>
                        <span class="total-amount"><?php echo number_format($total_amount, 0, ',', '.'); ?> ₫</span>
                    </div>
                    
                    <div style="margin-bottom: 20px; font-size: 14px; color: #3b66cc;">
                        <i class="fas fa-ticket-alt"></i> Mã giảm giá
                        <a href="#" id="openCouponBtn" style="float: right; color: #3b66cc; text-decoration: none;">Chọn mã giảm giá ></a>
                    </div>

                    <a href="checkout.php" class="btn-checkout-now" style="display: block; text-align: center; text-decoration: none; box-sizing: border-box;" <?php if(empty($cart_items)) echo 'style="pointer-events:none; background:#ccc;"'; ?>>THANH TOÁN NGAY</a>
                </div>
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
        // ================= XỬ LÝ ẨN/HIỆN KHUNG MÃ GIẢM GIÁ =================
        const openBtn = document.getElementById('openCouponBtn');
        const overlay = document.getElementById('couponOverlay');
        const drawer = document.getElementById('couponDrawer');
        const closeIcon = document.getElementById('closeDrawerIcon');
        const closeBtn = document.getElementById('closeDrawerBtn');

        function openDrawer(e) {
            e.preventDefault();
            overlay.style.display = 'block';
            setTimeout(() => { drawer.classList.add('open'); }, 10);
        }

        function closeDrawer() {
            drawer.classList.remove('open');
            setTimeout(() => { overlay.style.display = 'none'; }, 300); // Đợi CSS animation chạy xong
        }

        if(openBtn) openBtn.addEventListener('click', openDrawer);
        if(overlay) overlay.addEventListener('click', closeDrawer);
        if(closeIcon) closeIcon.addEventListener('click', closeDrawer);
        if(closeBtn) closeBtn.addEventListener('click', closeDrawer);

        // ================= XỬ LÝ CHỨC NĂNG "SAO CHÉP" MÃ =================
        function copyCoupon(btnElement, code) {
            // Lệnh copy vào Clipboard
            navigator.clipboard.writeText(code).then(() => {
                // Đổi nút thành màu xám và chữ "Đã chép"
                const originalText = btnElement.innerText;
                btnElement.innerText = "Đã chép!";
                btnElement.classList.add('copied');

                // 2 giây sau trả lại nút bình thường
                setTimeout(() => {
                    btnElement.innerText = originalText;
                    btnElement.classList.remove('copied');
                }, 2000);
            });
        }

        // XỬ LÝ BẬT/TẮT MENU TÀI KHOẢN
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