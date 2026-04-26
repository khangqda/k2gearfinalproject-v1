<?php 
session_start(); // Phải có dòng này ở trên cùng để gọi giỏ hàng
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh"); 
mysqli_set_charset($conn, "utf8mb4"); 

// 1. Lấy các tham số từ URL
$slug_danhmuc = isset($_GET['id']) ? $_GET['id'] : '';
$slug_brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$cat_id = 0;
$cat_name = "Kết quả tìm kiếm";
$brand_id = 0;

if ($slug_danhmuc != '') {
    $sql_cat = "SELECT id, name FROM categories WHERE slug = '$slug_danhmuc'";
    $res_cat = mysqli_query($conn, $sql_cat);
    if ($row_cat = mysqli_fetch_assoc($res_cat)) {
        $cat_id = $row_cat['id'];
        $cat_name = $row_cat['name'];
    }
}

if ($slug_brand != '') {
    $sql_b = "SELECT id FROM brands WHERE slug = '$slug_brand'";
    $res_b = mysqli_query($conn, $sql_b);
    if ($row_b = mysqli_fetch_assoc($res_b)) {
        $brand_id = $row_b['id'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K2 GEAR - <?php echo $cat_name; ?></title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="main-header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="logo">
                    <a href="main.php" style="text-decoration: none;"><span class="k2">K2</span> <span class="gear">GEAR</span></a>
                </div>
                
                <div style="position: relative;">
                    <div class="header-category-btn" id="stickyCategoryBtn">
                        <i class="fas fa-bars"></i> Danh mục
                    </div>

                    <div class="dropdown-category-menu" id="dropdownMenu">
                        <ul class="dropdown-list">
                            <li class="has-mega-menu">
                                <a href="category.php?id=chuot">
                                    <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-mouse"></i> Chuột</span>
                                    <i class="fas fa-chevron-right arrow-icon"></i>
                                </a>
                                <div class="mega-menu-panel">
                                    <div class="mega-row">
                                        <div class="mega-col">
                                            <h4>Thương hiệu</h4>
                                            <a href="category.php?id=chuot&brand=logitech">Logitech</a>
                                            <a href="category.php?id=chuot&brand=asus">Asus ROG</a>
                                            <a href="category.php?id=chuot&brand=corsair">Corsair</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="has-mega-menu">
                                <a href="category.php?id=ban-phim">
                                    <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-keyboard"></i> Bàn phím</span>
                                    <i class="fas fa-chevron-right arrow-icon"></i>
                                </a>
                                <div class="mega-menu-panel">
                                    <div class="mega-row">
                                        <div class="mega-col">
                                            <h4>Thương hiệu</h4>
                                            <a href="category.php?id=ban-phim&brand=logitech">Logitech</a>
                                            <a href="category.php?id=ban-phim&brand=asus">Asus</a>
                                            <a href="category.php?id=ban-phim&brand=corsair">Corsair</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="has-mega-menu">
                                <a href="category.php?id=man-hinh">
                                    <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-desktop"></i> Màn hình</span>
                                    <i class="fas fa-chevron-right arrow-icon"></i>
                                </a>
                                <div class="mega-menu-panel">
                                    <div class="mega-row">
                                        <div class="mega-col">
                                            <h4>Thương hiệu</h4>
                                            <a href="category.php?id=man-hinh&brand=msi">MSI</a>
                                            <a href="category.php?id=man-hinh&brand=asus">Asus</a>
                                            <a href="category.php?id=man-hinh&brand=lg">LG</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="has-mega-menu">
                                <a href="category.php?id=cpu">
                                    <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-microchip"></i> CPU - Vi xử lý</span>
                                    <i class="fas fa-chevron-right arrow-icon"></i>
                                </a>
                                <div class="mega-menu-panel">
                                    <div class="mega-row">
                                        <div class="mega-col">
                                            <h4>Dòng CPU</h4>
                                            <a href="category.php?id=cpu&brand=intel">Intel Core</a>
                                            <a href="category.php?id=cpu&brand=amd">AMD Ryzen</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <form action="category.php" method="GET" class="main-search">
                <input type="text" name="search" placeholder="Bạn cần tìm linh kiện gì?" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
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
                                echo '<p style="text-align: center; padding: 20px; color: #777;">Giỏ hàng của bạn đang trống</p>';
                            } else {
                                $id_list = implode(',', $valid_ids);
                                $sql_cart = "SELECT id, name, thumbnail, price, sale_price FROM products WHERE id IN ($id_list)";
                                $result_cart = mysqli_query($conn, $sql_cart);

                                if($result_cart && mysqli_num_rows($result_cart) > 0) {
                                    while ($row_cart = mysqli_fetch_assoc($result_cart)) {
                                        $p_id = $row_cart['id'];
                                        $qty = $cart_items[$p_id];
                                        $current_price = ($row_cart['sale_price'] > 0) ? $row_cart['sale_price'] : $row_cart['price'];
                                        $subtotal = $current_price * $qty;
                                        $total_cart_amount += $subtotal;

                                        echo '
                                        <div class="cart-item" style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #eee;">
                                            <img src="'.$row_cart['thumbnail'].'" alt="sp" style="width: 50px; height: 50px; object-fit: contain; margin-right: 15px; border: 1px solid #f0f0f0;" onerror="this.onerror=null; this.src=\'https://via.placeholder.com/50x50/f4f4f4/cccccc?text=SP\'">
                                            <div class="cart-item-info" style="flex: 1; text-align: left;">
                                                <p class="cart-item-name" style="margin: 0; font-size: 13px; font-weight: bold; color: #333; line-height: 1.3;">'.$row_cart['name'].'</p>
                                                <p class="cart-item-price" style="margin: 5px 0 0 0; color: #ce0707; font-weight: bold;">'.number_format($current_price, 0, ',', '.').' ₫ <span style="color: #888; font-size: 12px; font-weight: normal;">x '.$qty.'</span></p>
                                            </div>
                                        </div>';
                                    }
                                }
                            }
                            ?>
                        </div>
                        
                        <div class="cart-popup-footer" style="margin-top: 15px;">
                            <div class="cart-total" style="display: flex; justify-content: space-between; font-weight: bold; margin-bottom: 15px; color:#333;">
                                <span>Tổng tiền:</span>
                                <span class="total-price" style="color: #ce0707; font-size: 16px;"><?php echo number_format($total_cart_amount, 0, ',', '.'); ?> ₫</span>
                            </div>
                            <a href="cart.php" class="btn-checkout" style="display: block; width: 100%; background: #ce0707; color: white; text-align: center; padding: 10px 0; font-weight: bold; border-radius: 4px; text-decoration: none;">XEM GIỎ HÀNG</a>
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

    <main class="category-page" style="background-color: #fff; padding-bottom: 50px; padding-top: 20px;">
        <div class="container" style="flex-direction: column;">
            <div class="breadcrumb">
                <a href="main.php">Trang chủ</a> <i class="fas fa-chevron-right"></i> <span><?php echo $cat_name; ?></span>
            </div>

            <div class="category-header-sort">
                <h1 class="cat-page-title">
                    <?php echo $cat_name; ?> 
                    <?php if($slug_brand) echo " - Hãng " . strtoupper($slug_brand); ?>
                </h1>
            </div>

            <div class="product-grid grid-5-cols">
            <?php
            $sql_sp = "SELECT * FROM products WHERE status = 1";
            if ($cat_id > 0) { $sql_sp .= " AND category_id = $cat_id"; }
            if ($brand_id > 0) { $sql_sp .= " AND brand_id = $brand_id"; }
            if ($search_query != '') { $sql_sp .= " AND name LIKE '%$search_query%'"; }

            $result_sp = mysqli_query($conn, $sql_sp);

            if ($result_sp && mysqli_num_rows($result_sp) > 0) {
                while($row = mysqli_fetch_assoc($result_sp)) {
                    $giam_gia = round((($row['price'] - $row['sale_price']) / $row['price']) * 100);
                    echo '
                    <div class="product-card">
                        <div class="discount-badge">-'.$giam_gia.'%</div>
                        
                        <a href="product_detail.php?id='.$row['id'].'" style="text-decoration: none; color: inherit; display: block;">
                            <img src="'.$row['thumbnail'].'" alt="'.$row['name'].'" class="product-img" onerror="this.onerror=null; this.src=\'https://via.placeholder.com/300x300/f4f4f4/cccccc?text=K2+GEAR\'">
                            <h3 class="product-name">'.$row['name'].'</h3>
                        </a>
                        
                        <div class="product-price">
                            <span class="new-price">'.number_format($row['sale_price'], 0, ',', '.').' ₫</span>
                            <span class="old-price">'.number_format($row['price'], 0, ',', '.').' ₫</span>
                        </div>
                    </div>';
                }
            } else {
                echo "<p style='grid-column: span 5; text-align: center; padding: 50px;'>Không tìm thấy sản phẩm nào phù hợp!</p>";
            }
            ?>
            </div>
        </div>
    </main>

    <script>
        // ================= XỬ LÝ DROPDOWN DANH MỤC =================
        const stickyCategoryBtn = document.getElementById('stickyCategoryBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if(stickyCategoryBtn && dropdownMenu) {
            stickyCategoryBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('active');
            });

            document.addEventListener('click', function(e) {
                if (!dropdownMenu.contains(e.target) && e.target !== stickyCategoryBtn) {
                    dropdownMenu.classList.remove('active');
                }
            });
        }

        // ================= HIỆN POPUP GIỎ HÀNG =================
        const cartToggleBtn = document.getElementById('cartToggleBtn');
        const cartPopup = document.getElementById('cartPopup');

        if(cartToggleBtn && cartPopup) {
            cartToggleBtn.addEventListener('click', function(e) {
                e.preventDefault(); 
                e.stopPropagation();
                cartPopup.classList.toggle('active'); 
            });

            document.addEventListener('click', function(e) {
                if (!cartPopup.contains(e.target) && e.target !== cartToggleBtn) {
                    cartPopup.classList.remove('active');
                }
            });
        }

        // ================= LẤY MENU LINK =================
        const menuLinks = document.querySelectorAll('.dropdown-category-menu a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const targetUrl = this.getAttribute('href');
                if (targetUrl && targetUrl !== '#') {
                    window.location.href = targetUrl;
                }
            });
        });

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