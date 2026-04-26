<?php
// 1. Kiểm tra ID
if (!isset($_GET['id'])) {
    echo "Không tìm thấy mã danh mục!";
    exit;
}
$id = $_GET['id'];

// 2. Lấy dữ liệu cũ
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    echo "Danh mục không tồn tại!";
    exit;
}
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Chỉnh sửa danh mục</h1>
        <p class="page-subtitle">Cập nhật thông tin phân loại sản phẩm.</p>
    </div>
</div>

<div class="table-container" style="padding: 24px; background: white; border-radius: 12px; max-width: 600px;">
    <form action="modules/category/edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $category['id'] ?>">

        <div class="filter-group" style="margin-bottom: 20px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TÊN DANH MỤC *</label>
            <input type="text" name="name" class="form-select" style="width: 100%;" required value="<?= htmlspecialchars($category['name']) ?>">
        </div>

        <div class="filter-group" style="margin-bottom: 20px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ĐƯỜNG DẪN (SLUG) *</label>
            <input type="text" name="slug" class="form-select" style="width: 100%;" required value="<?= htmlspecialchars($category['slug']) ?>">
        </div>

        <div class="filter-group" style="margin-bottom: 24px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TRẠNG THÁI</label>
            <select name="status" class="form-select" style="width: 100%;">
                <option value="1" <?= $category['status'] == 1 ? 'selected' : '' ?>>Hiển thị (Active)</option>
                <option value="0" <?= $category['status'] == 0 ? 'selected' : '' ?>>Ẩn (Khóa)</option>
            </select>
        </div>

        <div style="display: flex; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
            <button type="submit" name="submit_edit_category" class="btn btn-primary">
                Cập nhật danh mục
            </button>
            <a href="?view=categories" class="btn" style="background: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">Hủy bỏ</a>
        </div>
    </form>
</div>
