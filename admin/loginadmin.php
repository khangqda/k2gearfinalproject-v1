<?php
session_start();

// Nếu đã đăng nhập rồi thì đá thẳng vào trang quản trị, không cho ở lại trang login
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Giả sử file config của bạn nằm ở thư mục gốc (lùi 1 cấp)
require_once '../config/database.php';

$error = '';

if (isset($_POST['btn_login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ Email và Mật khẩu!";
    } else {
        try {
            // Chỉ cho phép tài khoản có role là 'admin' đăng nhập
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND role = 'admin' LIMIT 1");
            $stmt->execute([':email' => $email]);
            $admin = $stmt->fetch();

            // Kiểm tra mật khẩu (Sử dụng password_verify vì bạn đang dùng password_hash ở phần khách hàng)
            if ($admin && password_verify($password, $admin['password'])) {
                // Đăng nhập thành công -> Lưu session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];

                header("Location: index.php");
                exit();
            } else {
                $error = "Email hoặc mật khẩu không chính xác, hoặc bạn không có quyền truy cập!";
            }
        } catch (PDOException $e) {
            $error = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - K2 GEAR</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-wrapper { background: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .logo-area { text-align: center; margin-bottom: 30px; }
        .logo-area h1 { color: #1e40af; font-size: 28px; font-weight: 800; letter-spacing: 1px; }
        .logo-area p { color: #6b7280; font-size: 14px; margin-top: 5px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #d1d5db; border-radius: 6px; outline: none; transition: 0.2s; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .btn-login { width: 100%; background: #3b82f6; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: 600; font-size: 15px; cursor: pointer; transition: 0.2s; }
        .btn-login:hover { background: #2563eb; }
        .error-msg { background: #fee2e2; color: #ef4444; padding: 10px; border-radius: 6px; font-size: 13px; margin-bottom: 20px; text-align: center; border: 1px solid #fca5a5; }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="logo-area">
        <h1>K2 GEAR</h1>
        <p>Hệ thống Quản trị Nội dung (CMS)</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="error-msg"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>ĐỊA CHỈ EMAIL</label>
            <input type="email" name="email" class="form-control" placeholder="admin@k2gear.com" required autofocus>
        </div>

        <div class="form-group">
            <label>MẬT KHẨU</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" name="btn_login" class="btn-login">Đăng nhập hệ thống</button>
    </form>
</div>

</body>
</html>