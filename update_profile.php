<?php
session_start();

// Nếu chưa đăng nhập thì không cho vào file này
if (!isset($_SESSION['user'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Kết nối CSDL
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user']['id'];

    // 1. Nhận dữ liệu khách hàng vừa gõ vào Form
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));

    // 2. Lệnh UPDATE để ghi đè dữ liệu mới vào CSDL
    $sql = "UPDATE users SET name='$name', phone='$phone', address='$address' WHERE id=$user_id";
    
    if (mysqli_query($conn, $sql)) {
        // 3. Cập nhật lại Session ngay lập tức để Header hiển thị tên mới
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;
        $_SESSION['user']['address'] = $address;

        // Bắn tín hiệu thành công về trang profile
        $_SESSION['msg'] = "Cập nhật thông tin thành công!";
    } else {
        $_SESSION['msg'] = "Lỗi hệ thống, vui lòng thử lại!";
    }

    // 4. Xử lý xong thì "đá" khách về lại trang Hồ sơ
    header("Location: profile.php");
    exit();
}
?>