<?php
if (!isset($_GET['id'])) {
    echo "Không tìm thấy mã đơn hàng!";
    exit;
}
$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    echo "Đơn hàng không tồn tại!";
    exit;
}
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Cập nhật đơn hàng #OD-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></h1>
    </div>
</div>

<div class="table-container" style="padding: 24px; background: white; border-radius: 12px; max-width: 600px;">
    <form action="modules/order/edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $order['id'] ?>">

        <div class="filter-group" style="margin-bottom: 16px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TRẠNG THÁI ĐƠN HÀNG *</label>
            <select name="status" class="form-select" style="width: 100%;" required>
                <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                <option value="confirmed" <?= $order['status'] == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                <option value="shipping" <?= $order['status'] == 'shipping' ? 'selected' : '' ?>>Đang giao hàng</option>
                <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>Đã giao</option>
                <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
            </select>
        </div>

        <div class="filter-group" style="margin-bottom: 16px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ĐỊA CHỈ GIAO HÀNG</label>
            <textarea name="shipping_address" class="form-select" rows="3" style="width: 100%; resize: vertical;"><?= htmlspecialchars($order['shipping_address']) ?></textarea>
        </div>

        <div class="filter-group" style="margin-bottom: 24px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">GHI CHÚ ĐƠN HÀNG</label>
            <textarea name="note" class="form-select" rows="2" style="width: 100%; resize: vertical;"><?= htmlspecialchars($order['note']) ?></textarea>
        </div>

        <div style="display: flex; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
            <button type="submit" name="submit_edit_order" class="btn btn-primary">Lưu thay đổi</button>
            <a href="?view=orders" class="btn" style="background: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">Hủy bỏ</a>
        </div>
    </form>
</div>
