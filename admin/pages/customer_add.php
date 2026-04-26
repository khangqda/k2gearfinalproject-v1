<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Thêm khách hàng mới</h1>
        <p class="page-subtitle">Tạo tài khoản thủ công cho khách hàng của K2 GEAR.</p>
    </div>
</div>

<div class="table-container" style="padding: 24px; background: white; border-radius: 12px; max-width: 800px;">
    <form action="modules/customer/add.php" method="POST">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="filter-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">HỌ VÀ TÊN *</label>
                <input type="text" name="name" class="form-select" style="width: 100%;" required placeholder="VD: Nguyễn Văn A">
            </div>

            <div class="filter-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ĐỊA CHỈ EMAIL *</label>
                <input type="email" name="email" class="form-select" style="width: 100%;" required placeholder="VD: email@example.com">
            </div>

            <div class="filter-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">SỐ ĐIỆN THOẠI</label>
                <input type="text" name="phone" class="form-select" style="width: 100%;" placeholder="VD: 0901234567">
            </div>

            <div class="filter-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">MẬT KHẨU *</label>
                <input type="password" name="password" class="form-select" style="width: 100%;" required placeholder="Mật khẩu đăng nhập">
            </div>
        </div>

        <div class="filter-group" style="margin-bottom: 20px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ĐỊA CHỈ GIAO HÀNG</label>
            <textarea name="address" class="form-select" rows="3" style="width: 100%; resize: vertical;" placeholder="Nhập địa chỉ nhà, tên đường, phường/xã..."></textarea>
        </div>

        <div class="filter-group" style="margin-bottom: 24px; max-width: 50%;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TRẠNG THÁI</label>
            <select name="status" class="form-select" style="width: 100%;">
                <option value="1">Đang hoạt động (Active)</option>
                <option value="0">Tạm khóa tài khoản</option>
            </select>
        </div>

        <div style="display: flex; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
            <button type="submit" name="submit_add_customer" class="btn btn-primary">
                <?= get_admin_icon('plus') ?> Lưu khách hàng
            </button>
            <a href="?view=customers" class="btn" style="background: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">Hủy bỏ</a>
        </div>
    </form>
</div>
