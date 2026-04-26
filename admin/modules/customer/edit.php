<?php
session_start();
require_once('../../../config/database.php');

if (isset($_POST['submit_edit_customer'])) {

    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $status = $_POST['status'];
    $password = $_POST['password'];

    try {
        // 1. Kiểm tra xem Email mới có bị trùng với user KHÁC không
        $check_stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_stmt->execute([$email, $id]);
        if ($check_stmt->rowCount() > 0) {
            die("Lỗi: Email này đã được một tài khoản khác sử dụng!");
        }

        // 2. Xử lý câu lệnh UPDATE dựa trên việc có nhập pass mới hay không
        if (!empty($password)) {
            // Có đổi mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name = :name, email = :email, phone = :phone, address = :address, status = :status, password = :password WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':password', $hashed_password);
        } else {
            // Bỏ trống -> Không cập nhật cột password
            $sql = "UPDATE users SET name = :name, email = :email, phone = :phone, address = :address, status = :status WHERE id = :id";
            $stmt = $pdo->prepare($sql);
        }

        // Bind các tham số chung
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id);

        $stmt->execute();

        // Thành công -> Về trang danh sách
        header("Location: ../../index.php?view=customers&msg=updated");
        exit();

    } catch (PDOException $e) {
        die("Lỗi Database: " . $e->getMessage());
    }
} else {
    header("Location: ../../index.php?view=customers");
    exit();
}
?>
