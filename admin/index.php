<?php
// 1. Khởi tạo session
session_start();

// --- BẢO MẬT: KIỂM TRA ĐĂNG NHẬP ---
// Nếu chưa có session (chưa đăng nhập), lập tức chuyển hướng về trang login
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginadmin.php");
    exit();
}

// 2. Gọi file cấu hình
require_once '../config/database.php';
require_once 'includes/icons.php';

// Lưu ý: Mình đã xóa phần fix cứng $_SESSION['admin_name'] = 'Khoa'
// vì dữ liệu này giờ đã được tạo tự động khi bạn đăng nhập thành công ở login.php

// 3. LOGIC ĐỊNH TUYẾN (ROUTER): Lấy trang cần xem từ URL, mặc định là 'dashboard'
$view = $_GET['view'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K2 GEAR - Quản trị</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="app-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content-wrapper">
        <?php include 'includes/header.php'; ?>

        <main class="page-content">
            <?php
            // Kiểm tra xem file có tồn tại trong thư mục pages không
            $file_path = "pages/{$view}.php";
            if (file_exists($file_path)) {
                include $file_path; // Nếu có thì nhúng vào
            } else {
                // Nếu trang chưa được xây dựng thì báo lỗi
                echo "<div style='text-align: center; padding: 50px;'>
                        <h2 style='color: #6b7280;'>Tính năng đang được phát triển...</h2>
                      </div>";
            }
            ?>
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.querySelector('.admin-sidebar').classList.toggle('show');
    }
</script>
</body>
</html>