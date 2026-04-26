<?php
session_start();
require_once('../../../config/database.php');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // 1. Lấy trạng thái hiện tại của sản phẩm
        $stmt = $pdo->prepare("SELECT status FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if ($product) {
            // 2. Đảo ngược trạng thái (đang 1 thì thành 0, đang 0 thì thành 1)
            $new_status = $product['status'] == 1 ? 0 : 1;

            // 3. Cập nhật lại vào Database
            $update_stmt = $pdo->prepare("UPDATE products SET status = ? WHERE id = ?");
            $update_stmt->execute([$new_status, $id]);
        }

        // 4. Lấy lại tham số URL cũ để quay về đúng cái trang đang xem
        $redirect_url = "../../index.php?view=products";
        if (isset($_GET['cat']) && $_GET['cat'] > 0) {
            $redirect_url .= "&cat=" . $_GET['cat'];
        }
        if (isset($_GET['page']) && $_GET['page'] > 1) {
            $redirect_url .= "&page=" . $_GET['page'];
        }

        header("Location: " . $redirect_url);
        exit();

    } catch (PDOException $e) {
        die("Lỗi Database: " . $e->getMessage());
    }
} else {
    header("Location: ../../index.php?view=products");
    exit();
}
?>
