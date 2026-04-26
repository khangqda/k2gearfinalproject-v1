<?php
session_start();

// 1. KẾT NỐI CƠ SỞ DỮ LIỆU
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");

// 2. LẤY ID TỪ URL VÀ TRUY VẤN DỮ LIỆU
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;

if ($product_id > 0) {
    $sql = "SELECT p.*, b.name AS brand_name, c.name AS cat_name, c.slug AS cat_slug 
            FROM products p 
            LEFT JOIN brands b ON p.brand_id = b.id 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = $product_id AND p.status = 1";
            
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    }
}

if (!$product) {
    die("<h2 style='text-align:center; margin-top:50px;'>Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.</h2><div style='text-align:center;'><a href='main.php'>Quay về trang chủ</a></div>");
}

$price = $product['price'];
$sale_price = $product['sale_price'];
$giam_gia = 0;

if ($price > 0 && $price > $sale_price) {
    $giam_gia = round((($price - $sale_price) / $price) * 100);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - K2 GEAR</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* CSS CƠ BẢN CỦA TRANG CHI TIẾT */
        .product-detail-container { max-width: 1200px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .breadcrumb { font-size: 13px; color: #777; margin-bottom: 25px; }
        .breadcrumb a { color: #3b66cc; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        .product-top-section { display: flex; gap: 40px; align-items: flex-start; padding-bottom: 30px; }
        .product-gallery { flex: 1; max-width: 500px; position: relative; text-align: center; }
        .product-gallery .main-img { width: 100%; max-width: 450px; height: auto; object-fit: contain; }
        .gallery-nav { position: absolute; top: 50%; transform: translateY(-50%); width: 100%; display: flex; justify-content: space-between; padding: 0 10px; box-sizing: border-box; }
        .gallery-nav button { background: rgba(0,0,0,0.1); border: none; font-size: 20px; padding: 10px 15px; cursor: pointer; border-radius: 50%; }
        .gallery-nav button:hover { background: rgba(0,0,0,0.3); color: white; }

        .product-info { flex: 1.5; }
        .product-title { font-size: 24px; font-weight: bold; color: #333; margin: 0 0 15px 0; line-height: 1.4; }
        .product-meta { font-size: 14px; color: #555; margin-bottom: 20px; display: flex; gap: 20px; align-items: center; }
        .product-meta span strong { color: #3b66cc; }
        .compare-link { color: #28a745; text-decoration: none; font-weight: bold; display: flex; align-items: center; gap: 5px; }

        .product-price-box { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; }
        .detail-new-price { font-size: 32px; font-weight: bold; color: #ce0707; }
        .detail-old-price { font-size: 18px; color: #999; text-decoration: line-through; }
        .detail-discount { background: #ce0707; color: white; padding: 4px 10px; border-radius: 4px; font-size: 14px; font-weight: bold; }

        .action-buttons { display: flex; gap: 15px; margin-top: 30px; }
        .btn-buy-now { flex: 2; background: #ce0707; color: white; border: none; padding: 15px; font-size: 18px; font-weight: bold; border-radius: 4px; cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .btn-buy-now span { font-size: 12px; font-weight: normal; margin-top: 5px; }
        .btn-buy-now:hover { background: #a30505; }
        .btn-add-cart { flex: 1; background: #fff; color: #3b66cc; border: 2px solid #3b66cc; padding: 15px; font-size: 16px; font-weight: bold; border-radius: 4px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-add-cart:hover { background: #f0f4ff; }

        /* TOAST THÔNG BÁO */
        .toast-container { position: fixed; top: 100px; right: 20px; z-index: 99999; }
        .toast-msg { background-color: #28a745; color: white; padding: 15px 25px; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 12px; font-weight: bold; font-size: 15px; transform: translateX(150%); transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        .toast-msg.show { transform: translateX(0); }

        /* CSS KHU VỰC ĐÁNH GIÁ (LAYOUT GIỐNG MEMORYZONE) */
        .section-heading { font-size: 20px; text-transform: uppercase; border-bottom: 2px solid #eee; padding-bottom: 10px; margin: 40px 0 20px 0; color: #333; }
        .review-box { display: flex; border: 1px solid #ddd; padding: 30px; border-radius: 8px; align-items: center; gap: 30px; }
        .review-left { flex: 1; text-align: center; border-right: 1px solid #eee; padding-right: 20px; }
        .review-left h4 { font-size: 20px; color: #ce0707; margin: 0 0 10px 0; }
        .review-left p { color: #666; font-size: 14px; margin: 0; }
        .review-middle { flex: 2; display: flex; flex-direction: column; gap: 8px; }
        .star-row { display: flex; align-items: center; gap: 10px; font-size: 14px; color: #555; }
        .star-row .star-icon { color: #ccc; width: 15px; } /* Mặc định sao xám vì chưa có đánh giá */
        .progress-bar { flex: 1; height: 8px; background: #eee; border-radius: 4px; overflow: hidden; }
        .progress-fill { height: 100%; background: #ffcc00; width: 0%; } /* Width 0% vì chưa có rating */
        .review-right { flex: 1; text-align: center; }
        .review-right p { font-size: 14px; color: #555; margin-bottom: 15px; }
        .btn-write-review { background: #ce0707; color: white; border: none; padding: 12px 20px; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .btn-write-review:hover { background: #a30505; }
        .review-list-empty { background: #fdfdfd; border: 1px solid #eee; padding: 20px; text-align: center; border-radius: 8px; margin-top: 20px; color: #888; font-size: 14px; }
    </style>
</head>
<body style="background-color: #f0f2f5;">

    <div class="toast-container">
        <?php if(isset($_SESSION['toast_message'])): ?>
            <div class="toast-msg show" id="toastMsg">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['toast_message']; ?>
            </div>
            <?php unset($_SESSION['toast_message']); ?>
        <?php endif; ?>
    </div>

    <header class="main-header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="logo">
                    <a href="main.php" style="text-decoration: none;"><span class="k2">K2</span> <span class="gear">GEAR</span></a>
                </div>
                <div class="header-category-btn" id="stickyCategoryBtn">
                    <i class="fas fa-bars"></i> Danh mục
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
                
                <div class="cart-wrapper" style="position: relative;">
                    <?php
                    $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                    $total_cart_amount = 0;
                    $valid_ids = array_filter(array_keys($cart_items), 'is_numeric');
                    ?>
                    <a href="#" class="cart-icon" id="cartToggleBtn">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?php echo count($valid_ids); ?></span>
                    </a>
                    <div class="cart-popup" id="cartPopup">
                        <h4 class="cart-popup-title" style="color: #333; margin: 0 0 10px 0; padding-bottom: 10px; border-bottom: 1px solid #eee; text-align: left;">GIỎ HÀNG CỦA BẠN</h4>
                        <div class="cart-items" style="max-height: 300px; overflow-y: auto;">
                            <?php
                            if (empty($valid_ids)) {
                                echo '<p style="text-align: center; padding: 20px; color: #777;">Giỏ hàng trống</p>';
                            } else {
                                $id_list = implode(',', $valid_ids);
                                $sql_cart = "SELECT id, name, thumbnail, price, sale_price FROM products WHERE id IN ($id_list)";
                                $result_cart = mysqli_query($conn, $sql_cart);
                                if($result_cart && mysqli_num_rows($result_cart) > 0) {
                                    while ($row_cart = mysqli_fetch_assoc($result_cart)) {
                                        $qty = $cart_items[$row_cart['id']];
                                        $current_price = ($row_cart['sale_price'] > 0) ? $row_cart['sale_price'] : $row_cart['price'];
                                        $total_cart_amount += $current_price * $qty;
                                        echo '
                                        <div class="cart-item" style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #eee;">
                                            <img src="'.$row_cart['thumbnail'].'" alt="sp" style="width: 50px; height: 50px; object-fit: contain; margin-right: 15px; border: 1px solid #f0f0f0;" onerror="this.onerror=null; this.src=\'https://via.placeholder.com/50x50/f4f4f4/cccccc?text=SP\'">
                                            <div class="cart-item-info" style="flex: 1; text-align: left;">
                                                <p style="margin: 0; font-size: 13px; font-weight: bold; color: #333; line-height: 1.3;">'.$row_cart['name'].'</p>
                                                <p style="margin: 5px 0 0 0; color: #ce0707; font-weight: bold;">'.number_format($current_price, 0, ',', '.').' ₫ <span style="color: #888; font-size: 12px; font-weight: normal;">x '.$qty.'</span></p>
                                            </div>
                                        </div>';
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div class="cart-popup-footer" style="margin-top: 15px;">
                            <div style="display: flex; justify-content: space-between; font-weight: bold; margin-bottom: 15px; color:#333;">
                                <span>Tổng tiền:</span> <span style="color: #ce0707; font-size: 16px;"><?php echo number_format($total_cart_amount, 0, ',', '.'); ?> ₫</span>
                            </div>
                            <a href="cart.php" style="display: block; width: 100%; background: #ce0707; color: white; text-align: center; padding: 10px 0; font-weight: bold; border-radius: 4px; text-decoration: none;">XEM GIỎ HÀNG</a>
                        </div>
                    </div>
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

    <main class="product-detail-container">
        
        <div class="breadcrumb">
            <a href="main.php">Trang chủ</a> <i class="fas fa-chevron-right" style="font-size: 10px; margin: 0 5px;"></i> 
            <a href="category.php?id=<?php echo $product['cat_slug'] ?? ''; ?>"><?php echo $product['cat_name'] ?? 'Danh mục'; ?></a> <i class="fas fa-chevron-right" style="font-size: 10px; margin: 0 5px;"></i> 
            <strong><?php echo $product['name']; ?></strong>
        </div>

        <div class="product-top-section">
            <div class="product-gallery">
                <img src="<?php echo $product['thumbnail']; ?>" alt="<?php echo $product['name']; ?>" class="main-img" onerror="this.onerror=null; this.src='https://via.placeholder.com/400x400/f4f4f4/cccccc?text=K2+GEAR'">
                <div class="gallery-nav">
                    <button><i class="fas fa-chevron-left"></i></button>
                    <button><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <div class="product-info">
                <h1 class="product-title"><?php echo $product['name']; ?></h1>
                <div class="product-meta">
                    <span>Thương hiệu: <strong><?php echo $product['brand_name'] ?? 'Đang cập nhật'; ?></strong></span>
                    <span>|</span><span>Mã SP: SP-<?php echo $product['id']; ?></span><span>|</span>
                    
                </div>

                <div class="product-price-box">
                    <span class="detail-new-price"><?php echo number_format($sale_price, 0, ',', '.'); ?> ₫</span>
                    <?php if ($giam_gia > 0) { ?>
                        <span class="detail-old-price"><?php echo number_format($price, 0, ',', '.'); ?> ₫</span>
                        <span class="detail-discount">-<?php echo $giam_gia; ?>%</span>
                    <?php } ?>
                </div>

                <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; border: 1px dashed #ddd;">
                    <?php 
                    if (!empty($product['description'])) { echo $product['description']; } 
                    else {
                        echo "<ul style='margin: 0; padding-left: 20px; color: #444; font-size: 14px; line-height: 1.8;'>
                            <li>Sản phẩm chính hãng, phân phối bởi K2 GEAR.</li>
                            <li>Bảo hành theo tiêu chuẩn của nhà sản xuất.</li>
                            <li>Cam kết 1 đổi 1 trong 7 ngày nếu lỗi phần cứng.</li>
                        </ul>";
                    }
                    ?>
                </div>

                <div class="action-buttons">
    <a href="cart.php?action=buynow&id=<?php echo $product['id']; ?>" 
       onclick="return checkLogin();" 
       class="btn-buy-now" style="text-decoration: none;">
        MUA NGAY <span>Giao hàng tận nơi miễn phí</span>
    </a>
    
    <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" 
       onclick="return checkLogin();" 
       class="btn-add-cart" style="text-decoration: none;">
        <i class="fas fa-cart-plus"></i> THÊM VÀO GIỎ
    </a>
</div>
            </div>
        </div>
        
        <h3 class="section-heading">ĐÁNH GIÁ SẢN PHẨM</h3>
        <div class="review-box">
            <div class="review-left">
                <h4>Chưa có đánh giá</h4>
                <p>Hãy là người đầu tiên</p>
            </div>
            
            <div class="review-middle">
                <?php 
                // Vòng lặp in ra 5 thanh trình độ (Từ 5 sao xuống 1 sao)
                for ($i = 5; $i >= 1; $i--) { 
                    echo '
                    <div class="star-row">
                        <span>'.$i.' <i class="fas fa-star star-icon"></i></span>
                        <div class="progress-bar"><div class="progress-fill"></div></div>
                        <span>0%</span>
                    </div>';
                } 
                ?>
            </div>

            <div class="review-right">
                <p>Chia sẻ nhận xét về sản phẩm</p>
                <button class="btn-write-review">Viết đánh giá của bạn</button>
            </div>
        </div>
        
        <div style="margin-top: 20px; display: flex; align-items: center; gap: 10px; font-size: 14px;">
            <label>Chọn xem đánh giá:</label>
            <select style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;"><option>Tất cả</option></select>
            <select style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px;"><option>Tất cả ★</option></select>
        </div>
        <div class="review-list-empty">Chưa có đánh giá nào cho sản phẩm này.</div>


        <h3 class="section-heading" style="margin-top: 60px;">SẢN PHẨM LIÊN QUAN</h3>
        <div class="product-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px;">
            <?php
            // Lấy 5 sản phẩm CÙNG DANH MỤC nhưng KHÁC SẢN PHẨM HIỆN TẠI
            $cat_id_current = $product['category_id'];
            $sql_related = "SELECT p.*, b.slug AS brand_slug 
                            FROM products p 
                            LEFT JOIN brands b ON p.brand_id = b.id 
                            WHERE p.category_id = $cat_id_current AND p.id != $product_id AND p.status = 1 
                            LIMIT 5";
            
            $result_related = mysqli_query($conn, $sql_related);

            if ($result_related && mysqli_num_rows($result_related) > 0) {
                while($row_rel = mysqli_fetch_assoc($result_related)) {
                    $giam_gia_rel = round((($row_rel['price'] - $row_rel['sale_price']) / $row_rel['price']) * 100);
                    echo '
                    <div class="product-card" style="background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 15px; position: relative; transition: 0.3s;">
                        <div class="discount-badge" style="position: absolute; top: 10px; left: 10px; background: #ce0707; color: #fff; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; z-index: 2;">-'.$giam_gia_rel.'%</div>
                        <a href="product_detail.php?id='.$row_rel['id'].'" style="text-decoration: none; color: inherit; display: block;">
                            <img src="'.$row_rel['thumbnail'].'" alt="'.$row_rel['name'].'" style="width: 100%; height: 180px; object-fit: contain; margin-bottom: 15px;" onerror="this.onerror=null; this.src=\'https://via.placeholder.com/200x200/f4f4f4/cccccc?text=K2+GEAR\'">
                            <h3 style="font-size: 14px; margin: 0 0 10px 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 36px; line-height: 1.3;">'.$row_rel['name'].'</h3>
                        </a>
                        <div class="product-price">
                            <span style="display: block; color: #ce0707; font-weight: bold; font-size: 16px;">'.number_format($row_rel['sale_price'], 0, ',', '.').' ₫</span>
                            <span style="display: block; color: #999; text-decoration: line-through; font-size: 13px;">'.number_format($row_rel['price'], 0, ',', '.').' ₫</span>
                        </div>
                    </div>';
                }
            } else {
                echo "<p style='grid-column: span 5;'>Chưa có sản phẩm liên quan nào trong danh mục này.</p>";
            }
            ?>
        </div>

    </main>

    <footer class="main-footer" style="margin-top: 50px;">
        <div class="footer-container">
            <div class="footer-column">
                <h3 class="footer-logo"><span class="k2">K2</span> <span class="gear" style="color: white;">GEAR</span></h3>
                <p class="footer-desc">K2 GEAR tự hào là đơn vị chuyên cung cấp linh kiện máy tính chính hãng, uy tín. Chúng tôi mang đến cho bạn đa dạng sự lựa chọn để nâng cấp sức mạnh cho hệ thống của mình.</p>
                <div class="footer-contact">
                    <p><i class="fas fa-map-marker-alt"></i> TP. Cao Lãnh, Việt Nam</p>
                    <p><i class="fas fa-phone-alt"></i> Hotline: 1900 xxxx</p>
                    <p><i class="fas fa-envelope"></i> Email: hotro@k2gear.com</p>
                </div>
            </div>

            <div class="footer-column">
                <h4 class="footer-title">LINH KIỆN NỔI BẬT</h4>
                <ul class="footer-links">
                    <li><a href="#">Card màn hình (VGA)</a></li>
                    <li><a href="#">Vi xử lý (CPU)</a></li>
                    <li><a href="#">Bo mạch chủ (Mainboard)</a></li>
                    <li><a href="#">RAM PC & Laptop</a></li>
                    <li><a href="#">Ổ cứng SSD & HDD</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4 class="footer-title">HỖ TRỢ KHÁCH HÀNG</h4>
                <ul class="footer-links">
                    <li><a href="#">Chính sách bảo hành</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Bảo mật thông tin</a></li>
                    <li><a href="#">Hướng dẫn mua hàng</a></li>
                    <li><a href="#">Hướng dẫn thanh toán</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4 class="footer-title">KẾT NỐI VỚI CHÚNG TÔI</h4>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
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
        const toastMsg = document.getElementById('toastMsg');
        if (toastMsg) {
            setTimeout(() => { toastMsg.classList.remove('show'); }, 3000); 
        }

        const cartToggleBtn = document.getElementById('cartToggleBtn');
        const cartPopup = document.getElementById('cartPopup');

        if(cartToggleBtn && cartPopup) {
            cartToggleBtn.addEventListener('click', function(e) {
                e.preventDefault(); e.stopPropagation();
                cartPopup.classList.toggle('active'); 
            });
            document.addEventListener('click', function(e) {
                if (!cartPopup.contains(e.target) && e.target !== cartToggleBtn) {
                    cartPopup.classList.remove('active');
                }
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

function checkLogin() {
        // Lấy trạng thái đăng nhập từ PHP truyền xuống JS
        var isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
        
        if (!isLoggedIn) {
            // Hiện thông báo và đá văng sang trang đăng nhập
            alert("Vui lòng đăng nhập để mua hàng hoặc thêm vào giỏ nhé!");
            window.location.href = "login.php";
            return false; // Chặn không cho form thêm giỏ hàng chạy tiếp
        }
        return true; // Đã đăng nhập thì cho phép chạy bình thường
    }
    </script>
</body>
</html>