<?php
session_start();

// 1. KẾT NỐI CSDL
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Nếu giỏ trống thì về trang chủ
if (empty($cart_items)) {
    header("Location: main.php");
    exit();
}

$total_amount = 0;
$shipping_fee = 0; 
$coupon_error = '';

// ================= XỬ LÝ MÃ GIẢM GIÁ =================

if (isset($_GET['remove_coupon'])) {
    unset($_SESSION['coupon']);
    header("Location: checkout.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_coupon'])) {
    $coupon_code = mysqli_real_escape_string($conn, trim($_POST['coupon_code']));
    
    $sql_check = "SELECT * FROM coupons WHERE code = '$coupon_code' AND expiry_date >= CURDATE() AND used < max_usage LIMIT 1";
    $res_check = mysqli_query($conn, $sql_check);
    
    if ($res_check && mysqli_num_rows($res_check) > 0) {
        $_SESSION['coupon'] = mysqli_fetch_assoc($res_check);
        header("Location: checkout.php"); 
        exit();
    } else {
        $coupon_error = "Mã giảm giá không hợp lệ, đã hết hạn hoặc hết lượt!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán đơn hàng - K2 GEAR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #fff; color: #333; }
        * { box-sizing: border-box; }
        
        .checkout-header { text-align: center; padding: 20px 0; border-bottom: 1px solid #eee; }
        .checkout-header .logo { font-size: 28px; font-weight: bold; text-decoration: none; }
        .checkout-header .k2 { color: #3b66cc; }
        .checkout-header .gear { color: #ce0707; }

        .checkout-container { display: flex; max-width: 1000px; margin: 0 auto; min-height: 100vh;}
        
        .checkout-left { flex: 1.5; padding: 30px 40px 30px 0; border-right: 1px solid #eee; }
        .section-title { font-size: 18px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;}
        .section-title a { font-size: 14px; color: #3b66cc; text-decoration: none; font-weight: normal; }
        
        .form-group { margin-bottom: 15px; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; outline: none; }
        .form-control:focus { border-color: #3b66cc; box-shadow: 0 0 5px rgba(59,102,204,0.2); }
        .form-row { display: flex; gap: 15px; }
        .form-row .form-group { flex: 1; }
        textarea.form-control { resize: vertical; min-height: 80px; }

        .info-box { background: #f0f4ff; border: 1px solid #d6e0f5; padding: 15px; border-radius: 4px; font-size: 14px; color: #555; margin-bottom: 25px; }
        
        .payment-methods { border: 1px solid #ddd; border-radius: 4px; overflow: hidden; margin-bottom: 30px;}
        .payment-option { display: flex; align-items: center; padding: 15px; border-bottom: 1px solid #ddd; cursor: pointer; }
        .payment-option:last-child { border-bottom: none; }
        .payment-option input[type="radio"] { margin-right: 15px; cursor: pointer; }
        .payment-option span { flex: 1; font-size: 14px; }
        .payment-option img { height: 24px; object-fit: contain; }

        .checkout-right { flex: 1; padding: 30px 0 30px 40px; background: #fafafa; }
        
        .checkout-item { display: flex; align-items: center; margin-bottom: 15px; }
        .checkout-item-img { position: relative; width: 60px; height: 60px; background: #fff; border: 1px solid #eee; border-radius: 8px; padding: 5px; margin-right: 15px; }
        .checkout-item-img img { width: 100%; height: 100%; object-fit: contain; }
        .qty-badge { position: absolute; top: -5px; right: -5px; background: #777; color: white; font-size: 12px; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; border-radius: 50%; }
        .checkout-item-info { flex: 1; }
        .checkout-item-name { font-size: 14px; color: #333; margin: 0 0 5px 0; }
        .checkout-item-price { font-size: 14px; color: #555; font-weight: bold; text-align: right; }

        .discount-box { display: flex; gap: 10px; margin: 20px 0; padding-top: 20px; border-top: 1px solid #eee; padding-bottom: 20px; border-bottom: 1px solid #eee;}
        .discount-box input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; outline: none; }
        .discount-box button { background: #3b66cc; color: white; border: none; padding: 0 20px; border-radius: 4px; font-weight: bold; cursor: pointer; transition: 0.2s;}
        .discount-box button:hover { background: #2a4c99; }
        .discount-box button.btn-applied { background: #28a745; cursor: default; }

        .summary-line { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; color: #555; }
        .summary-total { display: flex; justify-content: space-between; margin-top: 20px; font-size: 18px; color: #333; font-weight: bold; align-items: center;}
        .summary-total .price { color: #ce0707; font-size: 22px; }

        .checkout-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; }
        .back-link { color: #3b66cc; text-decoration: none; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }
        .btn-submit-order { background: #ce0707; color: white; border: none; padding: 15px 30px; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; }
        .btn-submit-order:hover { background: #a30505; }
    </style>
</head>
<body>

    <header class="checkout-header">
        <a href="main.php" class="logo"><span class="k2">K2</span> <span class="gear">GEAR</span></a>
    </header>

    <div class="checkout-container">
        
        <div class="checkout-left">
            <h3 class="section-title">Thông tin nhận hàng <?php if(isset($_SESSION['user'])): ?>
    <div style="color: #3b66cc; font-weight: bold; font-size: 14px;">
        <i class="fas fa-user-circle"></i> Xin chào, <?php echo $_SESSION['user']['name']; ?>
    </div>
<?php else: ?>
    <a href="login.php" style="color: #3b66cc; text-decoration: none; font-size: 14px;">
        <i class="fas fa-user-circle"></i> Đăng nhập
    </a>
<?php endif; ?></h3>
            
            <form action="process_checkout.php" method="POST" id="checkoutForm">
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" name="fullname" class="form-control" placeholder="Họ và tên" required>
                </div>
                <div class="form-group">
                    <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <select name="province" id="province" class="form-control" required>
                            <option value="">Tỉnh thành ---</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="district" id="district" class="form-control" required>
                            <option value="">Quận huyện ---</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="ward" id="ward" class="form-control" required>
                            <option value="">Phường xã ---</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <input type="text" name="address" class="form-control" placeholder="Số nhà, tên đường" required>
                </div>
                <div class="form-group">
                    <textarea name="note" class="form-control" placeholder="Ghi chú (tùy chọn)"></textarea>
                </div>

                <h3 class="section-title">Vận chuyển</h3>
                <div class="info-box">
                    Vui lòng nhập đầy đủ thông tin địa chỉ để tính phí giao hàng.
                </div>

                <h3 class="section-title">Thanh toán</h3>
                <div class="payment-methods">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cod" checked>
                        <span>Thanh toán khi giao hàng (COD)</span>
                        <i class="fas fa-money-bill-wave" style="color: #28a745; font-size: 24px;"></i>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="bank">
                        <span>Chuyển khoản qua ngân hàng</span>
                        <img src="data:image/png;base64,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" alt="VietQR">
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="payoo">
                        <span>Ví MoMo</span>
                        <img src="data:image/png;base64,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" alt="MoMo" onerror="this.src='https://via.placeholder.com/60x24?text=Payoo'">
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="payoo">
                        <span>Thẻ tín dụng/Ghi nợ</span>
                        <img src="data:image/jpeg;base64,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" alt="Thetindung" onerror="this.src='https://via.placeholder.com/60x24?text=Payoo'">
                    </label>
                </div>
            </form>
        </div>

        <div class="checkout-right">
            <h3 class="section-title">Đơn hàng (<?php echo count($cart_items); ?> sản phẩm)</h3>
            
            <div class="order-items">
                <?php
                $id_list = implode(',', array_keys($cart_items)); 
                $sql = "SELECT id, name, thumbnail, price, sale_price FROM products WHERE id IN ($id_list)";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    $p_id = $row['id'];
                    $qty = $cart_items[$p_id]; 
                    $current_price = ($row['sale_price'] > 0) ? $row['sale_price'] : $row['price'];
                    $subtotal = $current_price * $qty;
                    $total_amount += $subtotal; 

                    echo '
                    <div class="checkout-item">
                        <div class="checkout-item-img">
                            <img src="'.$row['thumbnail'].'" alt="sp" onerror="this.onerror=null; this.src=\'https://via.placeholder.com/60x60/f4f4f4/cccccc?text=SP\'">
                            <span class="qty-badge">'.$qty.'</span>
                        </div>
                        <div class="checkout-item-info">
                            <p class="checkout-item-name">'.$row['name'].'</p>
                        </div>
                        <div class="checkout-item-price">'.number_format($subtotal, 0, ',', '.').' ₫</div>
                    </div>';
                }
                ?>
            </div>

            <form action="checkout.php" method="POST" style="margin: 0;">
                <div class="discount-box">
                    <input type="text" name="coupon_code" placeholder="Nhập mã giảm giá" required 
                           value="<?php echo isset($_SESSION['coupon']) ? $_SESSION['coupon']['code'] : ''; ?>" 
                           <?php echo isset($_SESSION['coupon']) ? 'readonly style="background:#f4f4f4; color:#888;"' : ''; ?>>
                    
                    <?php if(!isset($_SESSION['coupon'])): ?>
                        <button type="submit" name="apply_coupon">Áp dụng</button>
                    <?php else: ?>
                        <button type="button" class="btn-applied" disabled><i class="fas fa-check"></i> Đã áp dụng</button>
                    <?php endif; ?>
                </div>
                <?php if ($coupon_error != '') echo '<p style="color:#ce0707; font-size:13px; margin-top:-10px; margin-bottom: 20px;"><i class="fas fa-exclamation-circle"></i> '.$coupon_error.'</p>'; ?>
            </form>

            <?php
            $discount_amount = 0;
            if (isset($_SESSION['coupon'])) {
                $cp = $_SESSION['coupon'];
                
                if ($cp['type'] == 'percent') {
                    $discount_amount = ($total_amount * $cp['value']) / 100;
                } else {
                    $discount_amount = $cp['value'];
                }

                if ($discount_amount > $total_amount) {
                    $discount_amount = $total_amount;
                }
            }
            
            $final_total = $total_amount + $shipping_fee - $discount_amount;
            ?>

            <div class="summary-box">
                <div class="summary-line">
                    <span>Tạm tính</span>
                    <span><?php echo number_format($total_amount, 0, ',', '.'); ?> ₫</span>
                </div>
                <div class="summary-line">
                    <span>Phí vận chuyển</span>
                    <span>-</span>
                </div>
                
                <?php if (isset($_SESSION['coupon'])): ?>
                <div class="summary-line" style="color: #28a745; font-weight: bold;">
                    <span>
                        Mã giảm giá (<?php echo $_SESSION['coupon']['code']; ?>)
                        <a href="checkout.php?remove_coupon=1" style="color: #ce0707; font-size: 12px; margin-left: 10px; text-decoration: none;" title="Bỏ mã này">[Xóa]</a>
                    </span>
                    <span>-<?php echo number_format($discount_amount, 0, ',', '.'); ?> ₫</span>
                </div>
                <?php endif; ?>
                
                <div class="summary-total">
                    <span>Tổng cộng</span>
                    <span class="price"><?php echo number_format($final_total, 0, ',', '.'); ?> ₫</span>
                </div>
            </div>

            <div class="checkout-actions">
                <a href="cart.php" class="back-link"><i class="fas fa-chevron-left"></i> Quay về giỏ hàng</a>
                <button type="submit" form="checkoutForm" class="btn-submit-order">ĐẶT HÀNG</button>
            </div>
        </div>

    </div>

    <script>
        // Gọi API của Hành chính Việt Nam Open API
        const apiHost = "https://provinces.open-api.vn/api/";
        
        // Hàm load toàn bộ Tỉnh Thành khi mới vào trang
        const loadProvinces = async () => {
            try {
                const response = await fetch(apiHost + "p/");
                const provinces = await response.json();
                
                let html = '<option value="">Tỉnh thành ---</option>';
                provinces.forEach(p => {
                    // Lưu code vào data-code để lấy quận huyện sau
                    html += `<option value="${p.name}" data-code="${p.code}">${p.name}</option>`;
                });
                document.getElementById('province').innerHTML = html;
            } catch (error) {
                console.error("Lỗi tải tỉnh thành:", error);
            }
        };

        // Khi người dùng chọn Tỉnh Thành -> Bắt đầu tải Quận Huyện
        document.getElementById('province').addEventListener('change', async function() {
            const selectedOption = this.options[this.selectedIndex];
            const provinceCode = selectedOption.getAttribute('data-code');
            
            // Xóa rỗng phường xã ngay lập tức
            document.getElementById('ward').innerHTML = '<option value="">Phường xã ---</option>';
            
            if (!provinceCode) {
                document.getElementById('district').innerHTML = '<option value="">Quận huyện ---</option>';
                return;
            }

            try {
                const response = await fetch(apiHost + `p/${provinceCode}?depth=2`);
                const data = await response.json();
                
                let html = '<option value="">Quận huyện ---</option>';
                data.districts.forEach(d => {
                    html += `<option value="${d.name}" data-code="${d.code}">${d.name}</option>`;
                });
                document.getElementById('district').innerHTML = html;
            } catch (error) {
                console.error("Lỗi tải quận huyện:", error);
            }
        });

        // Khi người dùng chọn Quận Huyện -> Bắt đầu tải Phường Xã
        document.getElementById('district').addEventListener('change', async function() {
            const selectedOption = this.options[this.selectedIndex];
            const districtCode = selectedOption.getAttribute('data-code');
            
            if (!districtCode) {
                document.getElementById('ward').innerHTML = '<option value="">Phường xã ---</option>';
                return;
            }

            try {
                const response = await fetch(apiHost + `d/${districtCode}?depth=2`);
                const data = await response.json();
                
                let html = '<option value="">Phường xã ---</option>';
                data.wards.forEach(w => {
                    html += `<option value="${w.name}">${w.name}</option>`;
                });
                document.getElementById('ward').innerHTML = html;
            } catch (error) {
                console.error("Lỗi tải phường xã:", error);
            }
        });

        // Khởi chạy khi load trang
        loadProvinces();
    </script>

</body>
</html>