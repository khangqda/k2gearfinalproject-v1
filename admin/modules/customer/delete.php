<?php
session_start();
require_once('../../../config/database.php');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // Kiểm tra xem khách hàng có đơn hàng nào không (tùy vào cấu trúc DB của Khoa)
        // Thông thường, nếu có đơn hàng, người ta sẽ CẤM xóa để giữ lịch sử kế toán, chỉ chuyển status = 0 (Khóa tài khoản).
        // Tuy nhiên, ở đây mình viết code DELETE cứng theo yêu cầu cơ bản.

        $sql = "DELETE FROM users WHERE id = :id AND role = 'customer'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        header("Location: ../../index.php?view=customers&msg=deleted");
        exit();

    } catch (PDOException $e) {
        // Lỗi này thường xảy ra nếu vướng Khóa ngoại (Foreign Key) bên bảng orders
        die("Không thể xóa: Khách hàng này đang có lịch sử mua hàng trong hệ thống. Hãy tạm khóa tài khoản thay vì xóa vĩnh viễn! Chi tiết lỗi: " . $e->getMessage());
    }
} else {
    header("Location: ../../index.php?view=customers");
    exit();
}
?>
