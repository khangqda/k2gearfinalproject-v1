<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Thêm danh mục mới</h1>
        <p class="page-subtitle">Tạo nhóm phân loại mới cho sản phẩm (VD: Màn hình, Bàn phím...).</p>
    </div>
</div>

<div class="table-container" style="padding: 24px; background: white; border-radius: 12px; max-width: 600px;">
    <form action="modules/category/add.php" method="POST">

        <div class="filter-group" style="margin-bottom: 20px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TÊN DANH MỤC *</label>
            <input type="text" name="name" class="form-select" style="width: 100%;" required placeholder="VD: Bàn phím cơ">
        </div>

        <div class="filter-group" style="margin-bottom: 20px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ĐƯỜNG DẪN (SLUG) *</label>
            <input type="text" name="slug" class="form-select" style="width: 100%;" required placeholder="VD: ban-phim-co">
            <small style="color: #6b7280; margin-top: 4px; display: block;">Đường dẫn URL thân thiện, viết liền không dấu.</small>
        </div>

        <div class="filter-group" style="margin-bottom: 24px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TRẠNG THÁI</label>
            <select name="status" class="form-select" style="width: 100%;">
                <option value="1">Hiển thị (Active)</option>
                <option value="0">Ẩn (Khóa)</option>
            </select>
        </div>

        <div style="display: flex; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
            <button type="submit" name="submit_add_category" class="btn btn-primary">
                <?= get_admin_icon('plus') ?> Lưu danh mục
            </button>
            <a href="?view=categories" class="btn" style="background: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">Hủy bỏ</a>
        </div>
    </form>
</div>