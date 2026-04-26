<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "linhkienmaytinh");
mysqli_set_charset($conn, "utf8mb4");

$msg = "";
$valid_token = false;

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $current_time = date("Y-m-d H:i:s");

    // Kiểm tra token có tồn tại và còn hạn không
    $sql = "SELECT id FROM users WHERE reset_token = '$token' AND reset_token_expire >= '$current_time'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $valid_token = true;
        $user = mysqli_fetch_assoc($result);

        // Xử lý khi khách bấm nút Đổi mật khẩu
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                // Mã hóa mật khẩu mới
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Cập nhật pass và xóa token đi cho an toàn
                mysqli_query($conn, "UPDATE users SET password = '$hashed_password', reset_token = NULL, reset_token_expire = NULL WHERE id = {$user['id']}");
                
                $_SESSION['success'] = "Mật khẩu đã được khôi phục thành công. Vui lòng đăng nhập lại!";
                header("Location: login.php");
                exit();
            } else {
                $msg = "<p style='color: red; text-align: center;'>Mật khẩu xác nhận không khớp!</p>";
            }
        }
    } else {
        $msg = "<p style='color: red; text-align: center; font-weight: bold;'>Đường link khôi phục không hợp lệ hoặc đã hết hạn!</p>";
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu - K2 GEAR</title>
    <link rel="stylesheet" href="main.css">
    <style>
        .reset-box { max-width: 400px; margin: 100px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-family: Arial, sans-serif;}
        .form-control { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { width: 100%; padding: 12px; background: #3b66cc; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body style="background: #f4f6f8;">
    <div class="reset-box">
        <h2 style="text-align: center; color: #333; margin-top:0;">ĐẶT LẠI MẬT KHẨU</h2>
        <?php echo $msg; ?>
        
        <?php if ($valid_token): ?>
            <form action="" method="POST">
                <label style="font-size: 14px; font-weight: bold; color: #555;">Mật khẩu mới</label>
                <input type="password" name="new_password" class="form-control" required minlength="8">
                
                <label style="font-size: 14px; font-weight: bold; color: #555;">Xác nhận mật khẩu mới</label>
                <input type="password" name="confirm_password" class="form-control" required minlength="8">
                
                <button type="submit" class="btn-submit">Cập nhật mật khẩu</button>
            </form>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="login.php" style="color: #666; text-decoration: none; font-size: 14px;">Quay lại Đăng nhập</a>
        </div>
    </div>
</body>
</html>