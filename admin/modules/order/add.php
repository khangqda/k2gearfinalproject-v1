<?php
session_start();

// Lùi 2 cấp để lấy file kết nối Database
require_once('../../../config/database.php');

if (isset($_POST['submit_add_order'])) {

    // Thu thập dữ liệu từ Form
    $user_id = (int)$_POST['user_id'];
    $total = (float)$_POST['total'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];
    $shipping_address = trim($_POST['shipping_address']);
    $note = trim($_POST['note']);

    try {
        // Tùy thuộc vào Database của bạn, cột ngày tạo có thể là created_at hoặc order_date
        $sql = "INSERT INTO orders (user_id, total, payment_method, status, shipping_address, note) 
                VALUES (:user_id, :total, :payment_method, :status, :shipping_address, :note)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':total' => $total,
            ':payment_method' => $payment_method,
            ':status' => $status,
            ':shipping_address' => $shipping_address,
            ':note' => $note
        ]);

        // Thêm thành công, quay về trang danh sách đơn hàng
        header("Location: ../../index.php?view=orders&msg=success");
        exit();

    } catch (PDOException $e) {
        die("Lỗi Database khi tạo đơn hàng: " . $e->getMessage());
    }
} else {
    // Nếu truy cập file trực tiếp mà không qua Form thì đuổi về trang Đơn hàng
    header("Location: ../../index.php?view=orders");
    exit();
}
?>