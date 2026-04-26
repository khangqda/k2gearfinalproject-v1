<?php
session_start();
require_once('../../../config/database.php');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // Bắt đầu một Transaction để đảm bảo an toàn dữ liệu
        // Nếu xóa chi tiết thành công mà xóa đơn hàng lỗi thì nó sẽ không xóa gì cả
        $pdo->beginTransaction();

        // 1. Xóa tất cả chi tiết của đơn hàng này trong bảng order_details trước
        // Đây là bước bắt buộc vì order_details có khóa ngoại trỏ về orders
        $sql_details = "DELETE FROM order_details WHERE order_id = :id";
        $stmt_details = $pdo->prepare($sql_details);
        $stmt_details->execute([':id' => $id]);

        // 2. Sau khi dọn dẹp xong bảng con, chúng ta mới xóa bảng mẹ (orders)
        $sql_order = "DELETE FROM orders WHERE id = :id";
        $stmt_order = $pdo->prepare($sql_order);
        $stmt_order->execute([':id' => $id]);

        // Hoàn tất việc xóa
        $pdo->commit();

        header("Location: ../../index.php?view=orders&msg=deleted");
        exit();

    } catch (PDOException $e) {
        // Nếu có lỗi, hủy bỏ mọi thay đổi đã thực hiện trong try
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Không thể xóa đơn hàng. Lỗi Database: " . $e->getMessage());
    }
} else {
    header("Location: ../../index.php?view=orders");
    exit();
}
?>