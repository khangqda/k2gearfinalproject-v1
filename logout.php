
<?php
session_start();

// Xóa thông tin user đang đăng nhập
if(isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

// Chuyển hướng người dùng về lại trang chủ
header("Location: main.php");
exit();
?>