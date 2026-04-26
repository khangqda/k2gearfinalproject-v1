<?php
// 1. Kiểm tra ID truyền vào
if (!isset($_GET['id'])) {
    echo "Không tìm thấy thông tin khách hàng!";
    exit;
}
$id = $_GET['id'];

// 2. Lấy dữ liệu khách hàng cũ
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'customer'");
$stmt->execute([$id]);
$customer = $stmt->fetch();

if (!$customer) {
    echo "Khách hàng không tồn tại hoặc đã bị xóa!";
    exit;
}
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Cập nhật thông tin khách hàng</h1>
        <p class="page-subtitle">Chỉnh sửa hồ sơ và trạng thái tài khoản của người dùng.</p>
    </div>
</div>

<div class="table-container" style="padding: 24px; background: white; border-radius: 12px; max-width: 800px;">
    <form action="modules/customer/edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $customer['id'] ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="filter-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">HỌ VÀ TÊN *</label>
                <input type="text" name="name" class="form-select" style="width: 100%;" required value="<?= htmlspecialchars($customer['name']) ?>">
            </div>

            <div class="filter-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ĐỊA CHỈ EMAIL *</label>
                <input type="email" name="email" class="form-select" style="width: 100%;" required value="<?= htmlspecialchars($customer['email']) ?>">
            </div>

            <div class="filter-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">SỐ ĐIỆN THOẠI</label>
                <input type="text" name="phone" class="form-select" style="width: 100%;" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
            </div>

            <div class="filter-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">MẬT KHẨU MỚI</label>
                <input type="password" name="password" class="form-select" style="width: 100%;" placeholder="Bỏ trống để giữ mật khẩu cũ">
                <small style="color: #6b7280; margin-top: 4px; display: block;">Chỉ nhập khi muốn đổi mật khẩu cho khách.</small>
            </div>
        </div>

        <div class="filter-group" style="margin-bottom: 20px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ĐỊA CHỈ GIAO HÀNG</label>
            <textarea name="address" class="form-select" rows="3" style="width: 100%; resize: vertical;"><?= htmlspecialchars($customer['address'] ?? '') ?></textarea>
        </div>

        <div class="filter-group" style="margin-bottom: 24px; max-width: 50%;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TRẠNG THÁI</label>
            <select name="status" class="form-select" style="width: 100%;">
                <option value="1" <?= ($customer['status'] ?? 1) == 1 ? 'selected' : '' ?>>Đang hoạt động (Active)</option>
                <option value="0" <?= ($customer['status'] ?? 1) == 0 ? 'selected' : '' ?>>Tạm khóa tài khoản</option>
            </select>
        </div>

        <div style="display: flex; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
            <button type="submit" name="submit_edit_customer" class="btn btn-primary">
                Lưu thay đổi
            </button>
            <a href="?view=customers" class="btn" style="background: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">Hủy bỏ</a>
        </div>
    </form>
</div>
