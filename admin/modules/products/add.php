<?php
// Bật session để thông báo lỗi (nếu cần)
session_start();

// Nhúng file kết nối CSDL.
require_once('../../../config/database.php');

// Kiểm tra xem user có bấm nút Submit không
if (isset($_POST['submit_add_product'])) {

    // 1. Lấy dữ liệu từ Form
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $price = $_POST['price'];
    $sale_price = !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0;
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    $status = $_POST['status'];
    $description = $_POST['description'];

    // 2. Xử lý Ảnh Thumbnail (Đã chuyển sang dùng link URL trực tiếp)
    $thumbnail_path = '';
    // Nếu người dùng có nhập link ảnh vào ô input
    if (!empty($_POST['thumbnail'])) {
        $thumbnail_path = trim($_POST['thumbnail']);
    }

    // 3. Thực thi lệnh INSERT vào Database
    try {
        $sql = "INSERT INTO products (name, slug, thumbnail, category_id, brand_id, price, sale_price, stock, description, status) 
                VALUES (:name, :slug, :thumbnail, :category_id, :brand_id, :price, :sale_price, :stock, :description, :status)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':slug' => $slug,
            ':thumbnail' => $thumbnail_path,
            ':category_id' => $category_id,
            ':brand_id' => $brand_id,
            ':price' => $price,
            ':sale_price' => $sale_price,
            ':stock' => $stock,
            ':description' => $description,
            ':status' => $status
        ]);

        // Thêm thành công, quay về trang danh sách sản phẩm
        header("Location: ../../index.php?view=products&msg=success");
        exit();

    } catch (PDOException $e) {
        die("Lỗi Database khi thêm sản phẩm: " . $e->getMessage());
    }
} else {
    // Nếu ai đó truy cập trực tiếp file này mà không qua Form thì đuổi về
    header("Location: ../../index.php?view=products");
    exit();
}
?>