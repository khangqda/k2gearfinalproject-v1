<?php
session_start();
require_once('../../../config/database.php');

if (isset($_POST['submit_add_customer'])) {

    // Thu thập dữ liệu
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $status = $_POST['status'];

    // BẢO MẬT: Mã hóa mật khẩu trước khi lưu vào Database
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Kiểm tra xem email đã tồn tại chưa (tránh trùng lặp)
        $check_stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->execute([$email]);
        if ($check_stmt->rowCount() > 0) {
            die("Lỗi: Email này đã được sử dụng. Vui lòng quay lại và chọn email khác!");
        }

        // Lệnh INSERT (Mình dùng bảng users dựa theo các bảng trước của bạn)
        $sql = "INSERT INTO users (name, email, phone, password, address, status) 
                VALUES (:name, :email, :phone, :password, :address, :status)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':password' => $password,
            ':address' => $address,
            ':status' => $status
        ]);

        // Thành công -> Quay về trang Khách hàng
        header("Location: ../../index.php?view=customers&msg=success");
        exit();

    } catch (PDOException $e) {
        die("Lỗi Database: " . $e->getMessage());
    }
} else {
    header("Location: ../../index.php?view=customers");
    exit();
}
?>