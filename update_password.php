<?php
session_start();

if (!isset($_SESSION['user'])) { 
    header("Location: login.php"); 
    exit(); 
}

$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user']['id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Kiểm tra mật khẩu mới và xác nhận có khớp không
    if ($new_password !== $confirm_password) {
        $_SESSION['pwd_error'] = "Mật khẩu xác nhận không khớp!";
        header("Location: change_password.php");
        exit();
    }

    // 2. Lấy mật khẩu cũ (đã mã hóa) từ CSDL ra để đối chiếu
    $sql = "SELECT password FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // 3. So sánh mật khẩu khách vừa nhập với mật khẩu trong CSDL
    if (password_verify($old_password, $user['password'])) {
        
        // Băm (mã hóa) mật khẩu mới
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Lưu mật khẩu mới vào CSDL
        $update_sql = "UPDATE users SET password = '$new_hashed_password' WHERE id = $user_id";
        if (mysqli_query($conn, $update_sql)) {
            $_SESSION['pwd_success'] = "Đổi mật khẩu thành công!";
        } else {
            $_SESSION['pwd_error'] = "Có lỗi xảy ra, vui lòng thử lại!";
        }

    } else {
        $_SESSION['pwd_error'] = "Mật khẩu cũ không chính xác!";
    }

    // Trả về trang đổi mật khẩu để hiển thị thông báo
    header("Location: change_password.php");
    exit();
}
?>