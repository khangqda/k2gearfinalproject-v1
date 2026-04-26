<?php
session_start();

// Lùi 2 cấp để gọi file kết nối Database của dự án
require_once('../../../config/database.php');

if (isset($_POST['submit_add_category'])) {

    // Lấy dữ liệu từ Form gửi lên
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    $status = $_POST['status'];

    try {
        // Lệnh SQL chèn dữ liệu mới vào bảng categories
        $sql = "INSERT INTO categories (name, slug, status) 
                VALUES (:name, :slug, :status)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':slug' => $slug,
            ':status' => $status
        ]);

        // Thêm thành công, quay về trang danh sách danh mục
        header("Location: ../../index.php?view=categories&msg=success");
        exit();

    } catch (PDOException $e) {
        // Bắt lỗi nếu có (VD: trùng slug nếu bạn có set UNIQUE trong Database)
        die("Lỗi Database khi thêm danh mục: " . $e->getMessage());
    }
} else {
    // Truy cập trái phép thì đẩy về trang danh sách
    header("Location: ../../index.php?view=categories");
    exit();
}
?>
