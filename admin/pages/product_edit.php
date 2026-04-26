<?php
// Lấy ID sản phẩm từ URL
if (!isset($_GET['id'])) {
    echo "Không tìm thấy sản phẩm!";
    exit;
}
$id = $_GET['id'];

// Lấy thông tin sản phẩm cũ từ Database
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Sản phẩm không tồn tại!";
    exit;
}
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Chỉnh sửa sản phẩm</h1>
        <p class="page-subtitle">Cập nhật thông tin phần cứng.</p>
    </div>
</div>

<div class="table-container" style="padding: 24px; background: white; border-radius: 12px;">
    <form action="modules/products/edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <input type="hidden" name="old_thumbnail" value="<?= $product['thumbnail'] ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div>
                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TÊN SẢN PHẨM *</label>
                    <input type="text" name="name" class="form-select" style="width: 100%;" required value="<?= htmlspecialchars($product['name']) ?>">
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ĐƯỜNG DẪN (SLUG) *</label>
                    <input type="text" name="slug" class="form-select" style="width: 100%;" required value="<?= htmlspecialchars($product['slug']) ?>">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="filter-group">
                        <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">GIÁ GỐC (VNĐ) *</label>
                        <input type="number" name="price" class="form-select" style="width: 100%;" required value="<?= $product['price'] ?>">
                    </div>
                    <div class="filter-group">
                        <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">GIÁ KHUYẾN MÃI (VNĐ)</label>
                        <input type="number" name="sale_price" class="form-select" style="width: 100%;" value="<?= $product['sale_price'] ?>">
                    </div>
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">SỐ LƯỢNG KHO *</label>
                    <input type="number" name="stock" class="form-select" style="width: 100%;" required value="<?= $product['stock'] ?>">
                </div>
            </div>

            <div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="filter-group">
                        <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">DANH MỤC *</label>
                        <select name="category_id" class="form-select" style="width: 100%;" required>
                            <?php
                            $cats = $pdo->query("SELECT id, name FROM categories")->fetchAll();
                            foreach ($cats as $c) {
                                $selected = ($c['id'] == $product['category_id']) ? 'selected' : '';
                                echo "<option value='{$c['id']}' {$selected}>{$c['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">THƯƠNG HIỆU *</label>
                        <select name="brand_id" class="form-select" style="width: 100%;" required>
                            <?php
                            $brands = $pdo->query("SELECT id, name FROM brands")->fetchAll();
                            foreach ($brands as $b) {
                                $selected = ($b['id'] == $product['brand_id']) ? 'selected' : '';
                                echo "<option value='{$b['id']}' {$selected}>{$b['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ẢNH ĐẠI DIỆN MỚI</label>
    
                    <input type="hidden" name="old_thumbnail" value="<?= htmlspecialchars($product['thumbnail']) ?>">
    
                    <input type="text" name="thumbnail" class="form-select" style="width: 100%; padding: 6px;" placeholder="VD: https://link-anh-vga.jpg">
    
                    <small style="color: #6b7280; margin-top: 4px; display: block;">* Bỏ trống nếu không muốn đổi ảnh. Ảnh hiện tại:</small>
                    <img src="<?= htmlspecialchars($product['thumbnail']) ?>" alt="Curent Image" style="height: 60px; border-radius: 4px; margin-top: 8px; border: 1px solid #e5e7eb;">
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TRẠNG THÁI</label>
                    <select name="status" class="form-select" style="width: 100%;">
                        <option value="1" <?= $product['status'] == 1 ? 'selected' : '' ?>>Hiển thị</option>
                        <option value="0" <?= $product['status'] == 0 ? 'selected' : '' ?>>Ẩn</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="filter-group" style="margin-bottom: 24px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">MÔ TẢ SẢN PHẨM</label>
            <textarea name="description" class="form-select" rows="4" style="width: 100%; resize: vertical;"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div style="display: flex; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
            <button type="submit" name="submit_edit_product" class="btn btn-primary">
                Lưu thay đổi
            </button>
            <a href="?view=products" class="btn" style="background: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">Hủy bỏ</a>
        </div>
    </form>
</div>
