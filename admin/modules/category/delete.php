<?php
session_start();
require_once('../../../config/database.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Lệnh xóa danh mục
        $sql = "DELETE FROM categories WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        header("Location: ../../index.php?view=categories&msg=deleted");
        exit();
    } catch (PDOException $e) {
        // Nếu có lỗi do ràng buộc khóa ngoại (đang có SP thuộc danh mục này), báo lỗi
        die("Không thể xóa danh mục này vì đang có sản phẩm thuộc danh mục. Hãy xóa hoặc đổi danh mục của sản phẩm trước!");
    }
}
?>
