<?php
session_start();
require_once('../../../config/database.php');

if (isset($_POST['submit_edit_order'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $shipping_address = trim($_POST['shipping_address']);
    $note = trim($_POST['note']);

    try {
        // Cập nhật trạng thái đơn hàng
        $sql = "UPDATE orders SET status = :status, shipping_address = :shipping_address, note = :note WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':shipping_address' => $shipping_address,
            ':note' => $note,
            ':id' => $id
        ]);

        // =====================================================================
        // THÊM MỚI: TỰ ĐỘNG GỬI THÔNG BÁO CHO KHÁCH HÀNG
        // =====================================================================
        
        // 1. Lấy user_id của đơn hàng đang sửa
        $stmt_user = $pdo->prepare("SELECT user_id FROM orders WHERE id = ?");
        $stmt_user->execute([$id]);
        $order_info = $stmt_user->fetch();

        if ($order_info) {
            $target_user_id = $order_info['user_id'];
            
            // 2. Dịch trạng thái tiếng Anh (từ form) sang tiếng Việt cho thông báo thân thiện
            $status_vi = $status; 
            if ($status == 'pending') $status_vi = 'Chờ xử lý';
            elseif ($status == 'confirmed') $status_vi = 'Đã xác nhận';
            elseif ($status == 'shipping') $status_vi = 'Đang giao hàng';
            elseif ($status == 'delivered') $status_vi = 'Đã giao (Hoàn thành)';
            elseif ($status == 'cancelled') $status_vi = 'Đã hủy';

            // 3. Bắn thông báo vào bảng notifications
            $noti_title = "Cập nhật đơn hàng #$id";
            $noti_content = "Đơn hàng của bạn đã được chuyển sang trạng thái: <b>$status_vi</b>.";
            
            $stmt_noti = $pdo->prepare("INSERT INTO notifications (user_id, type, title, content) VALUES (?, 'order', ?, ?)");
            $stmt_noti->execute([$target_user_id, $noti_title, $noti_content]);
        }
        // =====================================================================

        header("Location: ../../index.php?view=orders&msg=updated");
        exit();
    } catch (PDOException $e) {
        die("Lỗi Database: " . $e->getMessage());
    }
} else {
    header("Location: ../../index.php?view=orders");
    exit();
}
?>