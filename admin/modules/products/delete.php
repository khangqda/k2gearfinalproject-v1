<?php
session_start();
require_once('../../../config/database.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Tùy chọn: Bạn có thể viết thêm code SELECT để lấy tên file ảnh và dùng hàm unlink() để xóa file ảnh vật lý trên server cho nhẹ ổ cứng.

        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        header("Location: ../../index.php?view=products&msg=deleted");
        exit();
    } catch (PDOException $e) {
        die("Lỗi Database khi xóa sản phẩm: " . $e->getMessage());
    }
} else {
    header("Location: ../../index.php?view=products");
    exit();
}
?>
