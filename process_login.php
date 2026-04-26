<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if ($user = mysqli_fetch_assoc($result)) {
        // Kiểm tra mật khẩu mã hóa
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user; // Lưu thông tin người dùng vào Session
            header("Location: main.php"); // Đăng nhập xong bay về trang chủ
            exit();
        }
    }
    
    $_SESSION['error'] = "Email hoặc mật khẩu không chính xác!";
    header("Location: login.php");
}