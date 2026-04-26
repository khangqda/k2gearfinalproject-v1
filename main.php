<?php 
session_start(); // Phải có dòng này để lấy dữ liệu giỏ hàng
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh"); 
mysqli_set_charset($conn, "utf8mb4"); 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K2 GEAR</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="main-header">
        <div class="header-content">
            
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="logo">
                    <span class="k2">K2</span> <span class="gear">GEAR</span>
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
                                            <a href="category.php?id=chuot&brand=razer">Razer</a>
                                            <a href="category.php?id=chuot&brand=logitech">Logitech</a>
                                            <a href="category.php?id=chuot&brand=asus">Asus ROG</a>
                                            <a href="category.php?id=chuot&brand=corsair">Corsair</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Kiểu kết nối</h4>
                                            <a href="category.php?id=chuot&type=khong-day">Chuột Không dây / Bluetooth</a>
                                            <a href="category.php?id=chuot&type=co-day">Chuột Có dây</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Nhu cầu sử dụng</h4>
                                            <a href="category.php?id=chuot&usage=gaming">Chuột Gaming / Esport</a>
                                            <a href="category.php?id=chuot&usage=van-phong">Chuột Văn phòng / Cơ bản</a>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="has-mega-menu">
                                <a href="category.php?id=ban-phim">
                                    <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-keyboard"></i> Bàn phím</span>
                                    <i class="fas fa-chevron-right arrow-icon"></i>
                                </a>
                                <div class="mega-menu-panel mega-4-cols">
                                    <div class="mega-row">
                                        <div class="mega-col">
                                            <h4>Thương hiệu</h4>
                                            <a href="category.php?id=ban-phim&brand=razer">Razer</a>
                                            <a href="category.php?id=ban-phim&brand=logitech">Logitech</a>
                                            <a href="category.php?id=ban-phim&brand=corsair">Corsair</a>
                                            <a href="category.php?id=ban-phim&brand=akko">AKKO</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Loại bàn phím</h4>
                                            <a href="category.php?id=ban-phim&type=co">Bàn phím Cơ</a>
                                            <a href="category.php?id=ban-phim&type=gia-co">Bàn phím Giả cơ</a>
                                            <a href="category.php?id=ban-phim&type=van-phong">Bàn phím Văn phòng</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Kích thước (Layout)</h4>
                                            <a href="category.php?id=ban-phim&layout=fullsize">Fullsize (104-108 phím)</a>
                                            <a href="category.php?id=ban-phim&layout=tkl">Tenkeyless (TKL 87 phím)</a>
                                            <a href="category.php?id=ban-phim&layout=mini">Mini / Compact (60% - 75%)</a>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="has-mega-menu">
                                <a href="category.php?id=man-hinh">
                                    <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-desktop"></i> Màn hình - Loa</span>
                                    <i class="fas fa-chevron-right arrow-icon"></i>
                                </a>
                                <div class="mega-menu-panel mega-4-cols">
                                    <div class="mega-row">
                                        <div class="mega-col">
                                            <h4>Thương hiệu</h4>
                                            <a href="category.php?id=man-hinh&brand=lg">LG</a>
                                            <a href="category.php?id=man-hinh&brand=asus">Asus</a>
                                            <a href="category.php?id=man-hinh&brand=dell">Dell</a>
                                            <a href="category.php?id=man-hinh&brand=samsung">Samsung</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Độ phân giải</h4>
                                            <a href="category.php?id=man-hinh&res=fhd">Full HD (1920x1080)</a>
                                            <a href="category.php?id=man-hinh&res=2k">2K (2560x1440)</a>
                                            <a href="category.php?id=man-hinh&res=4k">4K (3840x2160)</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Nhu cầu sử dụng</h4>
                                            <a href="category.php?id=man-hinh&usage=gaming">Gaming (144Hz - 360Hz)</a>
                                            <a href="category.php?id=man-hinh&usage=do-hoa">Đồ họa (Màu chuẩn)</a>
                                            <a href="category.php?id=man-hinh&usage=van-phong">Văn phòng (60Hz - 75Hz)</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Âm thanh</h4>
                                            <a href="category.php?id=loa">Loa máy tính</a>
                                            <a href="category.php?id=tai-nghe">Tai nghe Gaming</a>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="has-mega-menu">
                                <a href="category.php?id=o-cung">
                                    <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-hdd"></i> Ổ cứng</span>
                                    <i class="fas fa-chevron-right arrow-icon"></i>
                                </a>
                                <div class="mega-menu-panel">
                                    <div class="mega-row">
                                        <div class="mega-col">
                                            <h4>Thương hiệu</h4>
                                            <a href="category.php?id=o-cung&brand=samsung">Samsung</a>
                                            <a href="category.php?id=o-cung&brand=wd">Western Digital (WD)</a>
                                            <a href="category.php?id=o-cung&brand=kingston">Kingston</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Chuẩn kết nối</h4>
                                            <a href="category.php?id=o-cung&type=ssd-nvme">SSD NVMe (PCIe)</a>
                                            <a href="category.php?id=o-cung&type=ssd-sata">SSD SATA III (2.5")</a>
                                            <a href="category.php?id=o-cung&type=hdd">HDD (Ổ cứng cơ)</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Dung lượng</h4>
                                            <a href="category.php?id=o-cung&cap=256gb">250GB - 256GB</a>
                                            <a href="category.php?id=o-cung&cap=512gb">500GB - 512GB</a>
                                            <a href="category.php?id=o-cung&cap=1tb">1TB - 2TB</a>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="has-mega-menu">
                                <a href="category.php?id=ram">
                                    <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-memory"></i> RAM PC - Laptop</span>
                                    <i class="fas fa-chevron-right arrow-icon"></i> 
                                </a>
                                <div class="mega-menu-panel">
                                    <div class="mega-row">
                                        <div class="mega-col">
                                            <h4>Thương hiệu</h4>
                                            <a href="category.php?id=ram&brand=corsair">Corsair</a>
                                            <a href="category.php?id=ram&brand=kingston">Kingston</a>
                                            <a href="category.php?id=ram&brand=gskill">G.Skill</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Loại RAM</h4>
                                            <a href="category.php?id=ram&type=ddr4">RAM DDR4</a>
                                            <a href="category.php?id=ram&type=ddr5">RAM DDR5</a>
                                            <a href="category.php?id=ram&type=laptop">RAM Laptop (SO-DIMM)</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Dung lượng</h4>
                                            <a href="category.php?id=ram&cap=8gb">8GB</a>
                                            <a href="category.php?id=ram&cap=16gb">16GB (8GB x2)</a>
                                            <a href="category.php?id=ram&cap=32gb">32GB (16GB x2)</a>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="has-mega-menu">
                                <a href="category.php?id=vga">
                                    <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-video"></i> Card màn hình</span>
                                    <i class="fas fa-chevron-right arrow-icon"></i>
                                </a>
                                <div class="mega-menu-panel">
                                    <div class="mega-row">
                                        <div class="mega-col">
                                            <h4>Thương hiệu</h4>
                                            <a href="category.php?id=vga&brand=asus">ASUS</a>
                                            <a href="category.php?id=vga&brand=msi">MSI</a>
                                            <a href="category.php?id=vga&brand=gigabyte">Gigabyte</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Chip đồ họa NVIDIA</h4>
                                            <a href="category.php?id=vga&chip=rtx-4000">GeForce RTX 40 Series</a>
                                            <a href="category.php?id=vga&chip=rtx-3000">GeForce RTX 30 Series</a>
                                            <a href="category.php?id=vga&chip=gtx">GeForce GTX Series</a>
                                        </div>
                                        <div class="mega-col">
                                            <h4>Chip đồ họa AMD</h4>
                                            <a href="category.php?id=vga&chip=rx-7000">Radeon RX 7000 Series</a>
                                            <a href="category.php?id=vga&chip=rx-6000">Radeon RX 6000 Series</a>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="has-mega-menu">
                                <a href="category.php?id=cpu"><span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-microchip"></i> CPU - Vi xử lý</span></a>
                            </li>
                            <li class="has-mega-menu">
                                <a href="category.php?id=mainboard"><span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-server"></i> Mainboard - Bo mạch chủ</span></a>
                            </li>
                            <li class="has-mega-menu">
                                <a href="category.php?id=case"><span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-box"></i> Case - Vỏ máy tính</span></a>
                            </li>
                            <li class="has-mega-menu">
                                <a href="category.php?id=linh-kien-khac"><span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-th-large"></i> Linh kiện Khác</span></a>
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
    // Lấy dữ liệu giỏ hàng
    $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $total_cart_amount = 0;
    ?>

    <a href="cart.php" class="cart-icon" id="cartToggleBtn">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-count"><?php echo count($cart_items); ?></span>
    </a>

    <div class="cart-popup" id="cartPopup">
        <h4 class="cart-popup-title">GIỎ HÀNG CỦA BẠN</h4>
        
        <div class="cart-items" style="max-height: 300px; overflow-y: auto;">
            <?php
            if (empty($cart_items)) {
                echo '<p style="text-align: center; padding: 20px; color: #777;">Giỏ hàng của bạn đang trống</p>';
            } else {
                // Có sản phẩm thì lôi từ CSDL ra
                $id_list = implode(',', array_keys($cart_items));
                $sql_cart = "SELECT id, name, thumbnail, price, sale_price FROM products WHERE id IN ($id_list)";
                $result_cart = mysqli_query($conn, $sql_cart);

                while ($row_cart = mysqli_fetch_assoc($result_cart)) {
                    $p_id = $row_cart['id'];
                    $qty = $cart_items[$p_id];
                    
                    $current_price = ($row_cart['sale_price'] > 0) ? $row_cart['sale_price'] : $row_cart['price'];
                    $subtotal = $current_price * $qty;
                    $total_cart_amount += $subtotal; // Cộng dồn tổng tiền

                    echo '
                    <div class="cart-item" style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #eee;">
                        <img src="'.$row_cart['thumbnail'].'" alt="sp" style="width: 50px; height: 50px; object-fit: contain; margin-right: 15px; border: 1px solid #f0f0f0;" onerror="this.onerror=null; this.src=\'https://via.placeholder.com/50x50/f4f4f4/cccccc?text=SP\'">
                        <div class="cart-item-info" style="flex: 1;">
                            <p class="cart-item-name" style="margin: 0; font-size: 13px; font-weight: bold; color: #333; line-height: 1.3;">'.$row_cart['name'].'</p>
                            <p class="cart-item-price" style="margin: 5px 0 0 0; color: #ce0707; font-weight: bold;">'.number_format($current_price, 0, ',', '.').' ₫ <span style="color: #888; font-size: 12px; font-weight: normal;">x '.$qty.'</span></p>
                        </div>
                    </div>';
                }
            }
            ?>
        </div>
        
        <div class="cart-popup-footer" style="margin-top: 15px;">
            <div class="cart-total" style="display: flex; justify-content: space-between; font-weight: bold; margin-bottom: 15px;">
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

    <main class="main-body">
        <div class="container">
            <ul class="category-list">
                    <li class="has-mega-menu">
                        <a href="category.php?id=chuot">
                            <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-mouse"></i> Chuột</span>
                            <i class="fas fa-chevron-right arrow-icon"></i>
                        </a>
                        <div class="mega-menu-panel">
                            <div class="mega-row"> 
                                <div class="mega-col">
                                    <h4>Thương hiệu</h4>
                                    <a href="category.php?id=chuot&brand=razer">Razer</a>
                                    <a href="category.php?id=chuot&brand=logitech">Logitech</a>
                                    <a href="category.php?id=chuot&brand=asus">Asus ROG</a>
                                    <a href="category.php?id=chuot&brand=corsair">Corsair</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Kiểu kết nối</h4>
                                    <a href="category.php?id=chuot&type=khong-day">Chuột Không dây / Bluetooth</a>
                                    <a href="category.php?id=chuot&type=co-day">Chuột Có dây</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Nhu cầu sử dụng</h4>
                                    <a href="category.php?id=chuot&usage=gaming">Chuột Gaming / Esport</a>
                                    <a href="category.php?id=chuot&usage=van-phong">Chuột Văn phòng / Cơ bản</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="has-mega-menu">
                        <a href="category.php?id=ban-phim">
                            <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-keyboard"></i> Bàn phím</span>
                            <i class="fas fa-chevron-right arrow-icon"></i>
                        </a>
                        <div class="mega-menu-panel mega-4-cols">
                            <div class="mega-row">
                                <div class="mega-col">
                                    <h4>Thương hiệu</h4>
                                    <a href="category.php?id=ban-phim&brand=razer">Razer</a>
                                    <a href="category.php?id=ban-phim&brand=logitech">Logitech</a>
                                    <a href="category.php?id=ban-phim&brand=corsair">Corsair</a>
                                    <a href="category.php?id=ban-phim&brand=akko">AKKO</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Loại bàn phím</h4>
                                    <a href="category.php?id=ban-phim&type=co">Bàn phím Cơ</a>
                                    <a href="category.php?id=ban-phim&type=gia-co">Bàn phím Giả cơ</a>
                                    <a href="category.php?id=ban-phim&type=van-phong">Bàn phím Văn phòng</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Kích thước (Layout)</h4>
                                    <a href="category.php?id=ban-phim&layout=fullsize">Fullsize (104-108 phím)</a>
                                    <a href="category.php?id=ban-phim&layout=tkl">Tenkeyless (TKL 87 phím)</a>
                                    <a href="category.php?id=ban-phim&layout=mini">Mini / Compact (60% - 75%)</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="has-mega-menu">
                        <a href="category.php?id=man-hinh">
                            <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-desktop"></i> Màn hình - Loa</span>
                            <i class="fas fa-chevron-right arrow-icon"></i>
                        </a>
                        <div class="mega-menu-panel mega-4-cols">
                            <div class="mega-row">
                                <div class="mega-col">
                                    <h4>Thương hiệu</h4>
                                    <a href="category.php?id=man-hinh&brand=lg">LG</a>
                                    <a href="category.php?id=man-hinh&brand=asus">Asus</a>
                                    <a href="category.php?id=man-hinh&brand=dell">Dell</a>
                                    <a href="category.php?id=man-hinh&brand=samsung">Samsung</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Độ phân giải</h4>
                                    <a href="category.php?id=man-hinh&res=fhd">Full HD (1920x1080)</a>
                                    <a href="category.php?id=man-hinh&res=2k">2K (2560x1440)</a>
                                    <a href="category.php?id=man-hinh&res=4k">4K (3840x2160)</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Nhu cầu sử dụng</h4>
                                    <a href="category.php?id=man-hinh&usage=gaming">Gaming (144Hz - 360Hz)</a>
                                    <a href="category.php?id=man-hinh&usage=do-hoa">Đồ họa (Màu chuẩn)</a>
                                    <a href="category.php?id=man-hinh&usage=van-phong">Văn phòng (60Hz - 75Hz)</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Âm thanh</h4>
                                    <a href="category.php?id=loa">Loa máy tính</a>
                                    <a href="category.php?id=tai-nghe">Tai nghe Gaming</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="has-mega-menu">
                        <a href="category.php?id=o-cung">
                            <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-hdd"></i> Ổ cứng</span>
                            <i class="fas fa-chevron-right arrow-icon"></i>
                        </a>
                        <div class="mega-menu-panel">
                            <div class="mega-row">
                                <div class="mega-col">
                                    <h4>Thương hiệu</h4>
                                    <a href="category.php?id=o-cung&brand=samsung">Samsung</a>
                                    <a href="category.php?id=o-cung&brand=wd">Western Digital (WD)</a>
                                    <a href="category.php?id=o-cung&brand=kingston">Kingston</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Chuẩn kết nối</h4>
                                    <a href="category.php?id=o-cung&type=ssd-nvme">SSD NVMe (PCIe)</a>
                                    <a href="category.php?id=o-cung&type=ssd-sata">SSD SATA III (2.5")</a>
                                    <a href="category.php?id=o-cung&type=hdd">HDD (Ổ cứng cơ)</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Dung lượng</h4>
                                    <a href="category.php?id=o-cung&cap=256gb">250GB - 256GB</a>
                                    <a href="category.php?id=o-cung&cap=512gb">500GB - 512GB</a>
                                    <a href="category.php?id=o-cung&cap=1tb">1TB - 2TB</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="has-mega-menu">
                        <a href="category.php?id=ram">
                            <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-memory"></i> RAM PC - Laptop</span>
                            <i class="fas fa-chevron-right arrow-icon"></i> 
                        </a>
                        <div class="mega-menu-panel">
                            <div class="mega-row">
                                <div class="mega-col">
                                    <h4>Thương hiệu</h4>
                                    <a href="category.php?id=ram&brand=corsair">Corsair</a>
                                    <a href="category.php?id=ram&brand=kingston">Kingston</a>
                                    <a href="category.php?id=ram&brand=gskill">G.Skill</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Loại RAM</h4>
                                    <a href="category.php?id=ram&type=ddr4">RAM DDR4</a>
                                    <a href="category.php?id=ram&type=ddr5">RAM DDR5</a>
                                    <a href="category.php?id=ram&type=laptop">RAM Laptop (SO-DIMM)</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Dung lượng</h4>
                                    <a href="category.php?id=ram&cap=8gb">8GB</a>
                                    <a href="category.php?id=ram&cap=16gb">16GB (8GB x2)</a>
                                    <a href="category.php?id=ram&cap=32gb">32GB (16GB x2)</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="has-mega-menu">
                        <a href="category.php?id=vga">
                            <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-video"></i> Card màn hình</span>
                            <i class="fas fa-chevron-right arrow-icon"></i>
                        </a>
                        <div class="mega-menu-panel">
                            <div class="mega-row">
                                <div class="mega-col">
                                    <h4>Thương hiệu</h4>
                                    <a href="category.php?id=vga&brand=asus">ASUS</a>
                                    <a href="category.php?id=vga&brand=msi">MSI</a>
                                    <a href="category.php?id=vga&brand=gigabyte">Gigabyte</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Chip đồ họa NVIDIA</h4>
                                    <a href="category.php?id=vga&chip=rtx-4000">GeForce RTX 40 Series</a>
                                    <a href="category.php?id=vga&chip=rtx-3000">GeForce RTX 30 Series</a>
                                    <a href="category.php?id=vga&chip=gtx">GeForce GTX Series</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Chip đồ họa AMD</h4>
                                    <a href="category.php?id=vga&chip=rx-7000">Radeon RX 7000 Series</a>
                                    <a href="category.php?id=vga&chip=rx-6000">Radeon RX 6000 Series</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="has-mega-menu">
                        <a href="category.php?id=mainboard">
                            <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-server"></i> Mainboard - Bo mạch chủ</span>
                            <i class="fas fa-chevron-right arrow-icon"></i>
                        </a>
                        <div class="mega-menu-panel">
                            <div class="mega-row">
                                <div class="mega-col">
                                    <h4>Thương hiệu</h4>
                                    <a href="category.php?id=mainboard&brand=asus">ASUS</a>
                                    <a href="category.php?id=mainboard&brand=gigabyte">Gigabyte</a>
                                    <a href="category.php?id=mainboard&brand=msi">MSI</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Chipset Intel</h4>
                                    <a href="category.php?id=mainboard&chipset=z790">Z790 / Z690 (Cao cấp)</a>
                                    <a href="category.php?id=mainboard&chipset=b760">B760 / B660 (Tầm trung)</a>
                                    <a href="category.php?id=mainboard&chipset=h610">H610 (Phổ thông)</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Chipset AMD</h4>
                                    <a href="category.php?id=mainboard&chipset=x670">X670 / X570 (Cao cấp)</a>
                                    <a href="category.php?id=mainboard&chipset=b650">B650 / B550 (Tầm trung)</a>
                                    <a href="category.php?id=mainboard&chipset=a620">A620 / A520 (Phổ thông)</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="has-mega-menu">
                        <a href="category.php?id=case">
                            <span style="display: flex; align-items: center; gap: 15px;"><i class="fas fa-box"></i> Case - Vỏ máy tính</span>
                            <i class="fas fa-chevron-right arrow-icon"></i>
                        </a>
                        <div class="mega-menu-panel">
                            <div class="mega-row">
                                <div class="mega-col">
                                    <h4>Thương hiệu phổ biến</h4>
                                    <a href="category.php?id=case&brand=xigmatek">Xigmatek</a>
                                    <a href="category.php?id=case&brand=montech">Montech</a>
                                    <a href="category.php?id=case&brand=deepcool">Deepcool</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Thương hiệu cao cấp</h4>
                                    <a href="category.php?id=case&brand=corsair">Corsair</a>
                                    <a href="category.php?id=case&brand=nzxt">NZXT</a>
                                    <a href="category.php?id=case&brand=lian-li">Lian Li</a>
                                </div>
                                <div class="mega-col">
                                    <h4>Kích thước Case</h4>
                                    <a href="category.php?id=case&size=atx">Mid Tower (ATX)</a>
                                    <a href="category.php?id=case&size=matx">Micro-ATX (M-ATX)</a>
                                    <a href="category.php?id=case&size=itx">Mini-ITX (Nhỏ gọn)</a>
                                </div>
                            </div>
                        </div>
                    </li>
					<li class="has-mega-menu">
    <a href="category.php?id=linh-kien-khac">
        <span style="display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-th-large"></i> Linh kiện Khác
        </span>
        <i class="fas fa-chevron-right arrow-icon"></i>
    </a>
    
    <div class="mega-menu-panel mega-4-cols">
        <div class="mega-row">
            
            <div class="mega-col">
                <h4>Nguồn máy tính (PSU)</h4>
                <a href="category.php?id=psu">Tất cả Nguồn máy tính</a>
                <a href="category.php?id=psu&brand=corsair">Nguồn Corsair</a>
                <a href="category.php?id=psu&brand=asus">Nguồn ASUS</a>
            </div>
            
            <div class="mega-col">
                <h4>Thiết bị mạng</h4>
                <a href="category.php?id=thiet-bi-mang">Router Wifi / Access Point</a>
                <a href="category.php?id=thiet-bi-mang&brand=tp-link">TP-Link</a>
            </div>
            
            <div class="mega-col">
                <h4>Phụ kiện Laptop</h4>
                <a href="category.php?id=phu-kien-laptop">Sạc / Pin Laptop</a>
                <a href="category.php?id=phu-kien-laptop">Đế tản nhiệt</a>
            </div>

            <div class="mega-col">
                <h4>Dây cáp & Đầu chuyển</h4>
                <a href="category.php?id=cap-ket-noi">Cáp HDMI / DisplayPort</a>
                <a href="category.php?id=cap-ket-noi">Hub USB / Type-C</a>
            </div>

        </div>
    </div>
</li>
					<li class="has-mega-menu">
    <a href="#">
        <span style="display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-handshake"></i> Dịch vụ - Hỗ trợ
        </span>
        <i class="fas fa-chevron-right arrow-icon"></i>
    </a>
    
    <div class="mega-menu-panel">
        <div class="mega-row">
            
            <div class="mega-col">
                <h4>Hỗ trợ mua hàng</h4>
                <a href="#">Hướng dẫn đặt hàng Online</a>
                <a href="#">Chính sách Trả góp 0%</a>
                <a href="#">Phương thức thanh toán</a>
                <a href="#">Chính sách giao hàng siêu tốc</a>
                <a href="#">Kiểm tra tình trạng đơn hàng</a>
            </div>
            
            <div class="mega-col">
                <h4>Bảo hành & Hậu mãi</h4>
                <a href="#">Tra cứu bảo hành linh kiện</a>
                <a href="#">Chính sách bảo hành chính hãng</a>
                <a href="#">Chính sách đổi trả trong 7 ngày</a>
                <a href="#">Hướng dẫn gửi bảo hành</a>
                <a href="#">Hỗ trợ kỹ thuật trực tuyến</a>
            </div>
            
            <div class="mega-col">
                <h4>Dịch vụ đặc quyền</h4>
                <a href="#">Thu cũ đổi mới linh kiện (Trade-in)</a>
                <a href="#">Báo giá khách hàng Doanh nghiệp</a>
                <a href="#">Cung cấp số lượng lớn cho Phòng Net</a>
                <a href="#">Xuất hóa đơn VAT điện tử</a>
            </div>

        </div>
    </div>
</li>
                </ul>
            </aside>

            <section class="ad-content" id="ad-banner-space">
                <div class="main-ad-banner">
                    <img src="https://bizweb.dktcdn.net/100/329/122/themes/1038963/assets/slider1_5.jpg?1776445287786">
                </div>
                <div class="more-ads-grid">
    <div class="ad-card" style="background-color: #f0f4ff; border: 1px dashed #3b71ed; overflow: hidden;">
        <img src="https://bizweb.dktcdn.net/100/329/122/themes/1038963/assets/bottom_banner_1.jpg?1776445287786" style="width: 100%; height: 100%; object-fit: cover; object-position: left center; display: block;">
    </div>
    
    <div class="ad-card" style="background-color: #f0f4ff; border: 1px dashed #3b71ed; overflow: hidden;">
        <img src="https://bizweb.dktcdn.net/100/329/122/themes/1038963/assets/bottom_banner_2.jpg?1776445287786" style="width: 100%; height: 100%; object-fit: cover; object-position: left center; display: block;">
    </div>
    
    <div class="ad-card" style="background-color: #f0f4ff; border: 1px dashed #3b71ed; overflow: hidden;">
        <img src="https://bizweb.dktcdn.net/100/329/122/themes/1038963/assets/bottom_banner_3.jpg?1776445287786" style="width: 100%; height: 100%; object-fit: cover; object-position: left center; display: block;">
    </div>
</div>
            </section>
        </div>
    </main>

	<section class="product-section">
        <div class="section-header">
            <h2 class="section-title">MAIN BÁN CHẠY</h2>
            <div class="filter-tags">
                <a href="#" class="filter-btn active" data-filter="all">Tất cả</a>
                <a href="#" class="filter-btn" data-filter="asus">Asus</a>
                <a href="#" class="filter-btn" data-filter="gigabyte">Gigabyte</a>
                <a href="category.php?id=mainboard" class="view-all">Xem tất cả ></a>
            </div>
        </div>

        <div class="product-grid">
            <?php
            $sql = "SELECT p.*, b.slug AS brand_slug 
                    FROM products p 
                    JOIN brands b ON p.brand_id = b.id 
                    WHERE p.category_id = 1 
                    LIMIT 4";
            
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $giam_gia = round((($row['price'] - $row['sale_price']) / $row['price']) * 100);
                    echo '
                    <div class="product-card" data-category="'.$row['brand_slug'].'">
                        <div class="discount-badge">-'.$giam_gia.'%</div>
                        <a href="product_detail.php?id='.$row['id'].'" style="text-decoration: none; color: inherit; display: block;">
                            <img src="'.$row['thumbnail'].'" class="product-img" alt="'.$row['name'].'">
                            <h3 class="product-name">'.$row['name'].'</h3>
                        </a>
                        <div class="product-price">
                            <span class="new-price">'.number_format($row['sale_price'], 0, ',', '.').' ₫</span>
                            <span class="old-price">'.number_format($row['price'], 0, ',', '.').' ₫</span>
                        </div>
                    </div>';
                }
            } else {
                echo "<p>Chưa có sản phẩm nào trong kho!</p>";
            }
            ?>
        </div>
    </section>

    <section class="product-section">
        <div class="section-header">
            <h2 class="section-title">CPU BÁN CHẠY</h2>
            <div class="filter-tags">
                <a href="#" class="filter-btn active" data-filter="all">Tất cả</a>
                <a href="#" class="filter-btn" data-filter="intel">Intel</a>
                <a href="#" class="filter-btn" data-filter="amd">Amd</a>
                <a href="category.php?id=cpu" class="view-all">Xem tất cả ></a>
            </div>
        </div>

        <div class="product-grid">
            <?php
            $sql_cpu = "SELECT p.*, b.slug AS brand_slug 
                        FROM products p 
                        JOIN brands b ON p.brand_id = b.id 
                        WHERE p.category_id = 2 
                        LIMIT 4";
            
            $result_cpu = mysqli_query($conn, $sql_cpu);

            if ($result_cpu && mysqli_num_rows($result_cpu) > 0) {
                while($row = mysqli_fetch_assoc($result_cpu)) {
                    $giam_gia = round((($row['price'] - $row['sale_price']) / $row['price']) * 100);
                    echo '
                    <div class="product-card" data-category="'.$row['brand_slug'].'">
                        <div class="discount-badge">-'.$giam_gia.'%</div>
                        <a href="product_detail.php?id='.$row['id'].'" style="text-decoration: none; color: inherit; display: block;">
                            <img src="'.$row['thumbnail'].'" class="product-img" alt="'.$row['name'].'">
                            <h3 class="product-name">'.$row['name'].'</h3>
                        </a>
                        <div class="product-price">
                            <span class="new-price">'.number_format($row['sale_price'], 0, ',', '.').' ₫</span>
                            <span class="old-price">'.number_format($row['price'], 0, ',', '.').' ₫</span>
                        </div>
                    </div>';
                }
            } else {
                echo "<p>Chưa có CPU nào trong kho!</p>";
            }
            ?>
        </div>
    </section>

    <section class="product-section">
        <div class="section-header">
            <h2 class="section-title">VGA BÁN CHẠY</h2>
            <div class="filter-tags">
                <a href="#" class="filter-btn active" data-filter="all">Tất cả</a>
                <a href="#" class="filter-btn" data-filter="asus">Asus</a>
                <a href="#" class="filter-btn" data-filter="msi">MSI</a>
                <a href="#" class="filter-btn" data-filter="gigabyte">Gigabyte</a>
                <a href="category.php?id=vga" class="view-all">Xem tất cả ></a>
            </div>
        </div>

        <div class="product-grid">
            <?php
            $sql_vga = "SELECT p.*, b.slug AS brand_slug 
                        FROM products p 
                        JOIN brands b ON p.brand_id = b.id 
                        WHERE p.category_id = 3 
                        LIMIT 4";
            
            $result_vga = mysqli_query($conn, $sql_vga);

            if ($result_vga && mysqli_num_rows($result_vga) > 0) {
                while($row = mysqli_fetch_assoc($result_vga)) {
                    $giam_gia = round((($row['price'] - $row['sale_price']) / $row['price']) * 100);
                    echo '
                    <div class="product-card" data-category="'.$row['brand_slug'].'">
                        <div class="discount-badge">-'.$giam_gia.'%</div>
                        <a href="product_detail.php?id='.$row['id'].'" style="text-decoration: none; color: inherit; display: block;">
                            <img src="'.$row['thumbnail'].'" class="product-img" alt="'.$row['name'].'">
                            <h3 class="product-name">'.$row['name'].'</h3>
                        </a>
                        <div class="product-price">
                            <span class="new-price">'.number_format($row['sale_price'], 0, ',', '.').' ₫</span>
                            <span class="old-price">'.number_format($row['price'], 0, ',', '.').' ₫</span>
                        </div>
                    </div>';
                }
            } else {
                echo "<p>Chưa có VGA nào trong kho!</p>";
            }
            ?>
        </div>
    </section>

    <section class="product-section">
        <div class="section-header">
            <h2 class="section-title">CHUỘT BÁN CHẠY</h2>
            <div class="filter-tags">
                <a href="#" class="filter-btn active" data-filter="all">Tất cả</a>
                <a href="#" class="filter-btn" data-filter="logitech">Logitech</a>
                <a href="#" class="filter-btn" data-filter="asus">Asus</a>
                <a href="#" class="filter-btn" data-filter="razer">Razer</a>
                <a href="#" class="filter-btn" data-filter="corsair">Corsair</a>
                <a href="category.php?id=chuot" class="view-all">Xem tất cả ></a>
            </div>
        </div>

        <div class="product-grid">
            <?php
            $sql_chuot = "SELECT p.*, b.slug AS brand_slug 
                          FROM products p 
                          JOIN brands b ON p.brand_id = b.id 
                          WHERE p.category_id = 7 
                          LIMIT 4";
            
            $result_chuot = mysqli_query($conn, $sql_chuot);

            if ($result_chuot && mysqli_num_rows($result_chuot) > 0) {
                while($row = mysqli_fetch_assoc($result_chuot)) {
                    $giam_gia = round((($row['price'] - $row['sale_price']) / $row['price']) * 100);
                    echo '
                    <div class="product-card" data-category="'.$row['brand_slug'].'">
                        <div class="discount-badge">-'.$giam_gia.'%</div>
                        <a href="product_detail.php?id='.$row['id'].'" style="text-decoration: none; color: inherit; display: block;">
                            <img src="'.$row['thumbnail'].'" class="product-img" alt="'.$row['name'].'">
                            <h3 class="product-name">'.$row['name'].'</h3>
                        </a>
                        <div class="product-price">
                            <span class="new-price">'.number_format($row['sale_price'], 0, ',', '.').' ₫</span>
                            <span class="old-price">'.number_format($row['price'], 0, ',', '.').' ₫</span>
                        </div>
                    </div>';
                }
            } else {
                echo "<p>Chưa có Chuột nào trong kho!</p>";
            }
            ?>
        </div>
    </section>

    <section class="product-section">
        <div class="section-header">
            <h2 class="section-title">BÀN PHÍM BÁN CHẠY</h2>
            <div class="filter-tags">
                <a href="#" class="filter-btn active" data-filter="all">Tất cả</a>
                <a href="#" class="filter-btn" data-filter="logitech">Logitech</a>
                <a href="#" class="filter-btn" data-filter="asus">Asus</a>
                <a href="#" class="filter-btn" data-filter="razer">Razer</a>
                <a href="#" class="filter-btn" data-filter="corsair">Corsair</a>
                <a href="category.php?id=ban-phim" class="view-all">Xem tất cả ></a>
            </div>
        </div>

        <div class="product-grid">
            <?php
            $sql_phim = "SELECT p.*, b.slug AS brand_slug 
                         FROM products p 
                         JOIN brands b ON p.brand_id = b.id 
                         WHERE p.category_id = 8 
                         LIMIT 4";
            
            $result_phim = mysqli_query($conn, $sql_phim);

            if ($result_phim && mysqli_num_rows($result_phim) > 0) {
                while($row = mysqli_fetch_assoc($result_phim)) {
                    $giam_gia = round((($row['price'] - $row['sale_price']) / $row['price']) * 100);
                    echo '
                    <div class="product-card" data-category="'.$row['brand_slug'].'">
                        <div class="discount-badge">-'.$giam_gia.'%</div>
                        <a href="product_detail.php?id='.$row['id'].'" style="text-decoration: none; color: inherit; display: block;">
                            <img src="'.$row['thumbnail'].'" class="product-img" alt="'.$row['name'].'">
                            <h3 class="product-name">'.$row['name'].'</h3>
                        </a>
                        <div class="product-price">
                            <span class="new-price">'.number_format($row['sale_price'], 0, ',', '.').' ₫</span>
                            <span class="old-price">'.number_format($row['price'], 0, ',', '.').' ₫</span>
                        </div>
                    </div>';
                }
            } else {
                echo "<p>Chưa có Bàn phím nào trong kho!</p>";
            }
            ?>
        </div>
    </section>

    <section class="product-section">
        <div class="section-header">
            <h2 class="section-title">MÀN HÌNH BÁN CHẠY</h2>
            <div class="filter-tags">
                <a href="#" class="filter-btn active" data-filter="all">Tất cả</a>
                <a href="#" class="filter-btn" data-filter="lenovo">Lenovo</a>
                <a href="#" class="filter-btn" data-filter="asus">Asus</a>
                <a href="#" class="filter-btn" data-filter="dell">Dell</a>
                <a href="#" class="filter-btn" data-filter="samsung">Samsung</a>
                <a href="category.php?id=man-hinh" class="view-all">Xem tất cả ></a>
            </div>
        </div>

        <div class="product-grid">
            <?php
            $sql_man = "SELECT p.*, b.slug AS brand_slug 
                        FROM products p 
                        JOIN brands b ON p.brand_id = b.id 
                        WHERE p.category_id = 6 
                        LIMIT 4";
            
            $result_man = mysqli_query($conn, $sql_man);

            if ($result_man && mysqli_num_rows($result_man) > 0) {
                while($row = mysqli_fetch_assoc($result_man)) {
                    $giam_gia = round((($row['price'] - $row['sale_price']) / $row['price']) * 100);
                    echo '
                    <div class="product-card" data-category="'.$row['brand_slug'].'">
                        <div class="discount-badge">-'.$giam_gia.'%</div>
                        <a href="product_detail.php?id='.$row['id'].'" style="text-decoration: none; color: inherit; display: block;">
                            <img src="'.$row['thumbnail'].'" class="product-img" alt="'.$row['name'].'">
                            <h3 class="product-name">'.$row['name'].'</h3>
                        </a>
                        <div class="product-price">
                            <span class="new-price">'.number_format($row['sale_price'], 0, ',', '.').' ₫</span>
                            <span class="old-price">'.number_format($row['price'], 0, ',', '.').' ₫</span>
                        </div>
                    </div>';
                }
            } else {
                echo "<p>Chưa có Màn hình nào trong kho!</p>";
            }
            ?>
        </div>
    </section>

<section class="news-section">
    <div class="news-container">
        
        <div class="news-column">
            <h2 class="news-title">THÔNG TIN KHUYẾN MÃI</h2>
            <div class="news-list">
                
                <article class="news-item">
                    <div class="news-img-box">
                        <img src="https://bizweb.dktcdn.net/100/329/122/articles/thumblog.jpg?v=1774497151513" alt="Khuyến mãi 1">
                    </div>
                    <div class="news-content">
                        <h3 class="news-heading"><a href="post_detail.php?id=1">MUA SANDISK - NHẬN NGAY VOUCHER PETROLIMEX</a></h3>
                        <span class="news-date">Thứ Năm, 26/03/2026</span>
                        <p class="news-desc">Nếu bạn đang có nhu cầu mua thẻ nhớ, USB hay SSD để nâng cấp lưu trữ...</p>
                    </div>
                </article>

                <article class="news-item">
                    <div class="news-img-box">
                        <img src="https://bizweb.dktcdn.net/100/329/122/articles/600x400-736a6bed-72cd-4ffa-b0a2-1008fa041ad9.jpg?v=1773721816877" alt="Khuyến mãi 2">
                    </div>
                    <div class="news-content">
                        <h3 class="news-heading"><a href="post_detail.php?id=2">[CTKM] POWERED BY MSI | NÂNG CẤP CẤU HÌNH</a></h3>
                        <span class="news-date">Thứ Ba, 17/03/2026</span>
                        <p class="news-desc">Bạn đang build PC mới? Hay đơn giản là muốn nâng cấp dàn máy cho mạnh hơn...</p>
                    </div>
                </article>

            </div>
        </div>

        <div class="news-column">
            <h2 class="news-title">KINH NGHIỆM HAY - MẸO VẶT</h2>
            <div class="news-list">
                
                <article class="news-item">
                    <div class="news-img-box">
                        <img src="https://bizweb.dktcdn.net/100/329/122/articles/so-dimm.png?v=1773644534980" alt="Mẹo vặt 1">
                    </div>
                    <div class="news-content">
                        <h3 class="news-heading"><a href="https://vi.wikipedia.org/wiki/SO-DIMM" target="_blank">SO-DIMM LÀ GÌ? RAM SODIMM VS DIMM KHÁC NHAU THẾ NÀO?</a></h3>
                        <span class="news-date">Thứ Hai, 16/03/2026</span>
                        <p class="news-desc">Khi tìm mua hoặc nâng cấp RAM cho laptop, thuật ngữ SO-DIMM rất phổ biến...</p>
                    </div>
                </article>

                <article class="news-item">
                    <div class="news-img-box">
                        <img src="https://bizweb.dktcdn.net/100/329/122/articles/dimm-la-gi.png?v=1773388697010" alt="Mẹo vặt 2">
                    </div>
                    <div class="news-content">
                        <h3 class="news-heading"><a href="https://www.google.com/search?q=DIMM+la+gi" target="_blank">DIMM LÀ GÌ? CÁC THẾ HỆ DDR TRÊN DIMM VÀ CÁCH CHỌN</a></h3>
                        <span class="news-date">Thứ Bảy, 14/03/2026</span>
                        <p class="news-desc">Khi tìm mua linh kiện cho dàn PC, người dùng thường bắt gặp thuật ngữ DIMM...</p>
                    </div>
                </article>

            </div>
        </div>

    </div>
</section>

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
	// ================= XỬ LÝ LỌC SẢN PHẨM =================
// ================= XỬ LÝ LỌC SẢN PHẨM (HOẠT ĐỘNG ĐỘC LẬP) =================
const filterBtns = document.querySelectorAll('.filter-btn');

filterBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault(); // Ngăn trang bị giật lên trên

        // 1. Tìm cái "nhà" (section) chứa cái nút vừa click
        const currentSection = this.closest('.product-section');

        // 2. Chỉ đổi màu active cho các nút nằm TRONG cùng cái "nhà" đó
        const sectionBtns = currentSection.querySelectorAll('.filter-btn');
        sectionBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        // 3. Lấy nhãn cần lọc (VD: 'asus', 'intel', 'all')
        const filterValue = this.getAttribute('data-filter');

        // 4. CHỈ tìm và lọc các sản phẩm nằm TRONG mục hiện tại
        const sectionProducts = currentSection.querySelectorAll('.product-card');

        sectionProducts.forEach(product => {
            product.classList.remove('fade-in');
            const productCategory = product.getAttribute('data-category');

            if (filterValue === 'all' || productCategory === filterValue) {
                product.classList.remove('hidden'); 
                
                setTimeout(() => {
                    product.classList.add('fade-in');
                }, 10);
            } else {
                product.classList.add('hidden'); 
            }
        });
    });
});

// ================= HIỆN NÚT DANH MỤC KHI CUỘN =================
            const stickyCategoryBtn = document.getElementById('stickyCategoryBtn');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 500) {
                    stickyCategoryBtn.classList.add('show');
                } else {
                    stickyCategoryBtn.classList.remove('show');
                }
            });

            stickyCategoryBtn.addEventListener('click', function() {
            const dropdownMenu = document.getElementById('dropdownMenu');

            stickyCategoryBtn.addEventListener('click', function(e) {
                e.stopPropagation(); 
                dropdownMenu.classList.toggle('active');
            });

            document.addEventListener('click', function(e) {
                if (!dropdownMenu.contains(e.target) && e.target !== stickyCategoryBtn) {
                    dropdownMenu.classList.remove('active');
                }
            });

            window.addEventListener('scroll', function() {
                if (window.scrollY <= 150) {
                    dropdownMenu.classList.remove('active');
                }
            });
            });

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

    // Lấy tất cả các đường link trong Menu Dropdown
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