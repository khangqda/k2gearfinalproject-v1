<?php
session_start();

// 1. KẾT NỐI CSDL
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['cart']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: main.php");
    exit();
}

// 2. LẤY THÔNG TIN TỪ FORM
$user_id = $_SESSION['user']['id'];
$fullname = mysqli_real_escape_string($conn, $_POST['fullname'] ?? '');
$email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
$province = mysqli_real_escape_string($conn, $_POST['province'] ?? '');
$district = mysqli_real_escape_string($conn, $_POST['district'] ?? '');
$ward = mysqli_real_escape_string($conn, $_POST['ward'] ?? '');
$address_detail = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
$note = mysqli_real_escape_string($conn, $_POST['note'] ?? '');
$payment_method = mysqli_real_escape_string($conn, $_POST['payment_method'] ?? 'cod');

$shipping_address = "$address_detail, $ward, $district, $province";

// 3. TÍNH TỔNG TIỀN TỪ GIỎ HÀNG
$cart_items = $_SESSION['cart'];
$total_amount = 0;
$id_list = implode(',', array_keys($cart_items));
$sql_price = "SELECT id, price, sale_price FROM products WHERE id IN ($id_list)";
$res_price = mysqli_query($conn, $sql_price);

while ($row = mysqli_fetch_assoc($res_price)) {
    $qty = $cart_items[$row['id']];
    $current_price = ($row['sale_price'] > 0) ? $row['sale_price'] : $row['price'];
    $total_amount += $current_price * $qty;
}

$discount_amount = 0;
if (isset($_SESSION['coupon'])) {
    $cp = $_SESSION['coupon'];
    $discount_amount = ($cp['type'] == 'percent') ? ($total_amount * $cp['value']) / 100 : $cp['value'];
    if ($discount_amount > $total_amount) $discount_amount = $total_amount;
    mysqli_query($conn, "UPDATE coupons SET used = used + 1 WHERE code = '{$cp['code']}'");
}

$final_total = $total_amount - $discount_amount;

// ==============================================================
// ĐÂY LÀ CHỖ QUYẾT ĐỊNH: Ép cứng trạng thái mặc định là "Chờ xử lý"
// ==============================================================
$status = 'pending'; 

// 4. LƯU VÀO BẢNG orders
$sql_order = "INSERT INTO orders (user_id, total, payment_method, status, shipping_address, note) 
              VALUES ('$user_id', '$final_total', '$payment_method', '$status', '$shipping_address', '$note')";

if (mysqli_query($conn, $sql_order)) {
    $order_id = mysqli_insert_id($conn); 
    
    // 5. LƯU VÀO BẢNG order_details (Để file order.php có ảnh và tên sản phẩm để hiện)
    foreach ($cart_items as $product_id => $quantity) {
        $sql_get_price = "SELECT price, sale_price FROM products WHERE id = $product_id";
        $res_p = mysqli_query($conn, $sql_get_price);
        if ($row_p = mysqli_fetch_assoc($res_p)) {
            $buy_price = ($row_p['sale_price'] > 0) ? $row_p['sale_price'] : $row_p['price'];
            
            $sql_detail = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                           VALUES ('$order_id', '$product_id', '$quantity', '$buy_price')";
            mysqli_query($conn, $sql_detail);
        }
    }

    // Xóa giỏ hàng
    unset($_SESSION['cart']);
    unset($_SESSION['coupon']);
    // --- THÊM: GỬI THÔNG BÁO CHO KHÁCH HÀNG ---
    $noti_title = "Đặt hàng thành công";
    $noti_content = "Đơn hàng <b>#$order_id</b> của bạn đã được tiếp nhận và đang ở trạng thái <b>Chờ xử lý</b>.";
    $sql_noti = "INSERT INTO notifications (user_id, type, title, content) 
                 VALUES ('$user_id', 'order', '$noti_title', '$noti_content')";
    mysqli_query($conn, $sql_noti);
    // -----------------------------------------

} else {
    die("Lỗi khi tạo đơn hàng: " . mysqli_error($conn));
}

$order_code = "K2G" . str_pad($order_id, 5, '0', STR_PAD_LEFT); 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công - K2 GEAR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f4ff; margin: 0; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .success-card { background: white; padding: 50px 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); text-align: center; max-width: 500px; width: 90%; }
        .icon-circle { width: 80px; height: 80px; background: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; }
        .icon-circle i { font-size: 40px; color: #28a745; }
        h1 { color: #333; margin: 0 0 10px 0; font-size: 24px; }
        p { color: #666; font-size: 15px; margin: 0 0 20px 0; line-height: 1.6; }
        .order-info { background: #f8f9fa; border: 1px dashed #ddd; padding: 20px; border-radius: 8px; text-align: left; margin-bottom: 30px; }
        .order-info div { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .order-info div:last-child { margin-bottom: 0; }
        .order-info b { color: #333; }
        .btn-home { display: inline-block; background: #3b66cc; color: white; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: bold; transition: 0.3s; }
        .btn-home:hover { background: #2a4c99; }
    </style>
</head>
<body>

    <div class="success-card">
        <div class="icon-circle">
            <i class="fas fa-check"></i>
        </div>
        <h1>ĐẶT HÀNG THÀNH CÔNG!</h1>
        <p>Cảm ơn <b><?php echo htmlspecialchars($fullname); ?></b> đã tin tưởng mua sắm. Đơn hàng của bạn đang được chúng tôi xử lý.</p>
        
        <div class="order-info">
            <div><span>Mã đơn hàng:</span> <b style="color: #ce0707;">#<?php echo $order_code; ?></b></div>
            <div><span>Tổng thanh toán:</span> <b style="color: #ce0707; font-size: 16px;"><?php echo number_format($final_total, 0, ',', '.'); ?> ₫</b></div>
            <div><span>Trạng thái:</span> <b style="color: #26aa99;"><?php echo $status; ?></b></div>
        </div>

        <a href="order.php" class="btn-home">THEO DÕI ĐƠN HÀNG</a>
    </div>

</body>
</html>