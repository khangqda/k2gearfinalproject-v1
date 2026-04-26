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
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi - K2 GEAR</title>
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
        
        .profile-content { flex: 1; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 30px; }
        
        .orders-tabs { display: flex; border-bottom: 1px solid #eee; margin-bottom: 30px; margin-top: 20px; overflow-x: auto;}
        .tab-item { padding: 10px 20px; color: #555; text-decoration: none; font-size: 15px; font-weight: bold; position: relative; white-space: nowrap; }
        .tab-item:hover { color: #ce0707; }
        .tab-item.active { color: #ce0707; }
        .tab-item.active::after { content: ''; position: absolute; bottom: -1px; left: 0; width: 100%; height: 2px; background-color: #ce0707; }
        
        .empty-orders { text-align: center; padding: 50px 0; color: #666; font-size: 14px; }

        .order-card-custom { border: 1px solid #eee; margin-bottom: 25px; padding: 20px; border-radius: 6px; }
        .shopee-tracker { padding: 30px 40px; border-bottom: 1px solid #f5f5f5; margin-bottom: 15px; background: #fff; }
        .shopee-progress-bar { display: flex; justify-content: space-between; position: relative; width: 100%; }
        .shopee-progress-bar::before { content: ""; position: absolute; top: 20px; left: 10%; right: 10%; height: 4px; background: #e0e0e0; z-index: 1; }
        .progress-fill { position: absolute; top: 20px; left: 10%; height: 4px; background: #26aa99; z-index: 2; transition: width 0.5s ease; }
        .step-node { position: relative; z-index: 3; text-align: center; flex: 1; }
        .step-icon-circle { width: 40px; height: 40px; background: #fff; border: 4px solid #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #bbb; font-size: 16px; }
        .step-text { margin-top: 8px; font-size: 12px; color: #888; }
        .step-node.active .step-icon-circle { border-color: #26aa99; color: #26aa99; }
        .step-node.active .step-text { color: #26aa99; font-weight: bold; }

        .cancelled-banner { background: #fff1f1; border: 1px dashed #ffb8b8; color: #ce0707; padding: 15px; text-align: center; font-weight: bold; margin-bottom: 15px; border-radius: 4px;}

        .prod-item-row { display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #f5f5f5; }
        .prod-img-box { width: 80px; height: 80px; border: 1px solid #eee; object-fit: contain; background: #fff; border-radius: 4px; }
        .prod-info-box { flex: 1; padding-left: 15px; }
        .prod-name-text { font-size: 16px; color: #333; margin-bottom: 4px; font-weight: bold;}
        .prod-price-text { text-align: right; color: #ce0707; font-size: 15px; font-weight: bold;}
        
        .order-summary-box { text-align: right; padding-top: 20px; margin-top: 10px; }
        .price-large { color: #ce0707; font-size: 24px; font-weight: bold; margin-left: 10px; }
        .btn-group-custom { display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px; }
        .btn-buy-back { background: #ce0707; color: #fff; padding: 10px 30px; border: none; border-radius: 4px; text-decoration: none; font-size: 14px; text-align: center; font-weight: bold;}
        .btn-contact-white { border: 1px solid #ddd; background: #fff; color: #555; padding: 10px 30px; border-radius: 4px; text-decoration: none; font-size: 14px; text-align: center; font-weight: bold;}
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
                                <strong style="color: #333; font-size: 14px; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo $_SESSION['user']['name']; ?></strong>
                                <span style="font-size: 12px; color: #666;"><?php echo $_SESSION['user']['email']; ?></span>
                            </div>
                            <a href="profile.php" style="display: block; padding: 10px 15px; color: #333; text-decoration: none; font-size: 13px; transition: 0.2s;"><i class="fas fa-id-card" style="width: 25px; color:#555;"></i> Tài khoản của tôi</a>
                            <a href="order.php" style="display: block; padding: 10px 15px; color: #333; text-decoration: none; font-size: 13px; transition: 0.2s;"><i class="fas fa-box" style="width: 25px; color:#555;"></i> Đơn hàng của tôi</a>
                            <a href="logout.php" style="display: block; padding: 10px 15px; color: #ce0707; text-decoration: none; font-size: 13px; border-top: 1px solid #eee; font-weight: bold; background: #fffafb;"><i class="fas fa-sign-out-alt" style="width: 25px;"></i> Đăng xuất</a>
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
                    <a href="profile.php" style="font-size:12px; color:#888; text-decoration: none;"><i class="fas fa-pencil-alt"></i> Sửa Hồ Sơ</a>
                </div>
            </div>
            <ul class="profile-menu">
                <li><a href="notification.php"><i class="far fa-bell" style="width: 25px; color:#888;"></i> Thông Báo</a></li>
                <li><a href="profile.php"><i class="far fa-user" style="width: 25px; color:#888;"></i> Tài Khoản Của Tôi</a></li>
                <li><a href="order.php" class="active"><i class="fas fa-clipboard-list" style="width: 25px;"></i> Đơn Mua</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt" style="width: 25px; color:#888;"></i> Đăng Xuất</a></li>
            </ul>
        </aside>

        <section class="profile-content">
            <h3 style="margin-top: 0; font-size: 18px; color: #333;">Đơn hàng của tôi</h3>
            
            <div class="orders-tabs">
                <a href="order.php?tab=all" class="tab-item <?php echo ($current_tab == 'all') ? 'active' : ''; ?>">Tất cả</a>
                <a href="order.php?tab=wait" class="tab-item <?php echo ($current_tab == 'wait') ? 'active' : ''; ?>">Chờ xử lý</a>
                <a href="order.php?tab=confirmed" class="tab-item <?php echo ($current_tab == 'confirmed') ? 'active' : ''; ?>">Đã xác nhận</a>
                <a href="order.php?tab=shipping" class="tab-item <?php echo ($current_tab == 'shipping') ? 'active' : ''; ?>">Đang giao</a>
                <a href="order.php?tab=success" class="tab-item <?php echo ($current_tab == 'success') ? 'active' : ''; ?>">Đã giao</a>
                <a href="order.php?tab=cancelled" class="tab-item <?php echo ($current_tab == 'cancelled') ? 'active' : ''; ?>">Đã hủy</a>
            </div>

            <div class="orders-list">
                <?php
                $where = "user_id = $user_id";
                if($current_tab == 'wait') $where .= " AND (status = 'Chờ xử lý' OR status = 'PENDING' OR status = 'pending')";
                elseif($current_tab == 'confirmed') $where .= " AND (status = 'Đã xác nhận' OR status = 'CONFIRMED' OR status = 'confirmed')";
                elseif($current_tab == 'shipping') $where .= " AND (status = 'Đang giao hàng' OR status = 'SHIPPING' OR status = 'shipping')";
                elseif($current_tab == 'success') $where .= " AND (status = 'Đã giao (Hoàn thành)' OR status = 'COMPLETED' OR status = 'completed')";
                elseif($current_tab == 'cancelled') $where .= " AND (status = 'Đã hủy' OR status = 'CANCELLED' OR status = 'cancelled')";

                $sql_orders = "SELECT * FROM orders WHERE $where ORDER BY id DESC";
                $res_orders = mysqli_query($conn, $sql_orders);

                if($res_orders && mysqli_num_rows($res_orders) > 0) {
                    while($order = mysqli_fetch_assoc($res_orders)) {
                        
                        // CHUẨN HÓA TRẠNG THÁI TỰ ĐỘNG (Bao lỗi rỗng dữ liệu)
                        $raw_status = strtolower(trim($order['status']));
                        
                        // Set mặc định luôn luôn là Chờ xử lý (step 0)
                        $display_status = 'Chờ xử lý'; 
                        $step = 0;

                        if ($raw_status == 'confirmed' || $raw_status == 'đã xác nhận') { $display_status = 'Đã xác nhận'; $step = 1; }
                        elseif ($raw_status == 'shipping' || $raw_status == 'đang giao hàng' || $raw_status == 'đang giao') { $display_status = 'Đang giao hàng'; $step = 2; }
                        elseif ($raw_status == 'delivered' || $raw_status == 'completed' || $raw_status == 'đã giao (hoàn thành)' || $raw_status == 'đã giao') { $display_status = 'Đã giao (Hoàn thành)'; $step = 3; }
                        elseif ($raw_status == 'cancelled' || $raw_status == 'đã hủy') { $display_status = 'Đã hủy'; $step = -1; }

                        // Màu trạng thái trên Header (Hủy thì đỏ, còn lại xanh ngọc)
                        $status_color = ($display_status == 'Đã hủy') ? '#ce0707' : '#26aa99';
                        $status_icon = ($display_status == 'Đã hủy') ? 'fa-times-circle' : 'fa-truck';
                ?>
                    <div class="order-card-custom">
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 10px;">
                            <span style="font-size: 15px;">Mã đơn hàng: <b style="color: #333;">#<?php echo $order['id']; ?></b></span>
                            <span style="color: <?php echo $status_color; ?>; font-weight: bold; text-transform: uppercase;"><i class="fas <?php echo $status_icon; ?>" style="margin-right: 5px;"></i><?php echo $display_status; ?></span>
                        </div>

                        <?php if($display_status == 'Đã hủy'): ?>
                            <div class="cancelled-banner">
                                <i class="fas fa-exclamation-triangle"></i> Đơn hàng này đã bị hủy. Cảm ơn bạn đã quan tâm đến K2 GEAR.
                            </div>
                        <?php else: 
                            $line_w = ($step >= 0) ? (($step / 3) * 80 . "%") : "0%";
                        ?>
                            <div class="shopee-tracker">
                                <div class="shopee-progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $line_w; ?>;"></div>
                                    <div class="step-node <?php echo ($step >= 0) ? 'active' : ''; ?>">
                                        <div class="step-icon-circle"><i class="fas fa-clipboard-list"></i></div>
                                        <div class="step-text">Chờ xử lý</div>
                                    </div>
                                    <div class="step-node <?php echo ($step >= 1) ? 'active' : ''; ?>">
                                        <div class="step-icon-circle"><i class="fas fa-box"></i></div>
                                        <div class="step-text">Đã xác nhận</div>
                                    </div>
                                    <div class="step-node <?php echo ($step >= 2) ? 'active' : ''; ?>">
                                        <div class="step-icon-circle"><i class="fas fa-shipping-fast"></i></div>
                                        <div class="step-text">Đang giao</div>
                                    </div>
                                    <div class="step-node <?php echo ($step >= 3) ? 'active' : ''; ?>">
                                        <div class="step-icon-circle"><i class="fas fa-star"></i></div>
                                        <div class="step-text">Đã giao</div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div>
                            <?php
                            // BÙM! ĐÃ SỬA LẠI THÀNH CỘT THUMBNAIL
                            $sql_items = "SELECT od.*, p.name, p.thumbnail FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = " . $order['id'];
                            $res_items = mysqli_query($conn, $sql_items);
                            
                            if($res_items && mysqli_num_rows($res_items) > 0) {
                                while($item = mysqli_fetch_assoc($res_items)) { ?>
                                    <div class="prod-item-row">
                                        <img src="<?php echo $item['thumbnail']; ?>" class="prod-img-box" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        <div class="prod-info-box">
                                            <div class="prod-name-text"><?php echo $item['name']; ?></div>
                                            <div style="color: #888; font-size: 13px;">Phân loại hàng: Linh kiện PC</div>
                                            <div style="margin-top: 5px; font-size: 14px; font-weight: bold;">x<?php echo $item['quantity']; ?></div>
                                        </div>
                                        <div class="prod-price-text"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</div>
                                    </div>
                                <?php }
                            } else {
                                echo '<div style="text-align: center; padding: 15px; color: #888; font-style: italic; background: #fafafa; border: 1px dashed #ddd;">Chưa có dữ liệu chi tiết sản phẩm.</div>';
                            } ?>
                        </div>

                        <div class="order-summary-box">
                            <div style="margin-bottom: 15px;">
                                <i class="fas fa-shield-alt" style="color:#ce0707; margin-right: 5px;"></i> 
                                <span style="font-size: 15px; color: #555;">Thành tiền: </span>
                                <span class="price-large"><?php echo number_format($order['total'], 0, ',', '.'); ?>đ</span>
                            </div>
                            <div class="btn-group-custom">
                                <a href="main.php" class="btn-buy-back">Mua Lại</a>
                                <a href="contact.php" class="btn-contact-white">Liên Hệ Người Bán</a>
                            </div>
                        </div>
                    </div>
                <?php 
                    } 
                } else { ?>
                    <div class="empty-orders">
                        <img src="https://content.pancake.vn/1.1/s700x700/fwebp90/2c/28/00/c7/a1872d7e2947ec4dd0b5ea08e1bb21ab077948ad0a1af501c50a38f7.png" width="100" style="margin-bottom: 20px;">
                        <p>Chưa có đơn hàng nào.</p>
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