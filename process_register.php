<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Nhận Họ, Tên từ Form và ghép lại thành Full Name
    $ho = mysqli_real_escape_string($conn, trim($_POST['ho']));
    $ten = mysqli_real_escape_string($conn, trim($_POST['ten']));
    $name = $ho . ' ' . $ten; // Chút nữa nhét cái này vào cột `name`

    // 2. Nhận thêm Số điện thoại
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    
    // 3. Email và Pass
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    // 4. Kiểm tra trùng Email
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = "Email này đã được đăng ký! Vui lòng dùng email khác.";
        header("Location: register.php");
    } else {
        // 5. Thêm lệnh INSERT có chèn thêm cột `phone` 
        $sql = "INSERT INTO users (name, email, password, phone) 
                VALUES ('$name', '$email', '$password', '$phone')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "Tạo tài khoản thành công! Hãy đăng nhập để mua hàng.";
            header("Location: login.php");
        } else {
            $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại.";
            header("Location: register.php");
        }
    }
}
?>