<?php
if (!isset($_GET['id'])) {
    echo "Không tìm thấy mã đơn hàng!";
    exit;
}
$id = (int)$_GET['id'];

// Lấy thông tin đơn hàng và khách hàng
$sql = "SELECT o.*, u.name as customer_name, u.email, u.phone 
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id 
        WHERE o.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    echo "Đơn hàng không tồn tại!";
    exit;
}

$order_id_fmt = "#OD-" . str_pad($order['id'], 5, '0', STR_PAD_LEFT);
$total_fmt = number_format($order['total'], 0, ',', '.');
$date_fmt = date('H:i - d/m/Y', strtotime($order['created_at']));
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Chi tiết đơn hàng <?= $order_id_fmt ?></h1>
        <p class="page-subtitle">Ngày đặt: <?= $date_fmt ?></p>
    </div>
    <div>
        <a href="?view=order_edit&id=<?= $order['id'] ?>" class="btn btn-primary">
            <?= get_admin_icon('edit') ?> Cập nhật trạng thái
        </a>
        <a href="?view=orders" class="btn" style="background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; margin-left: 8px;">Quay lại</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <div class="table-container" style="padding: 24px; background: white; border-radius: 12px;">
        <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px;">Sản phẩm đã đặt</h3>

        <table class="admin-table">
            <thead>
            <tr>
                <th>SẢN PHẨM</th>
                <th style="text-align: right;">TỔNG CỘNG</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <strong>Sản phẩm thuộc đơn <?= $order_id_fmt ?></strong><br>
                    <span style="font-size: 12px; color: #6b7280;">Theo yêu cầu đặt hàng của khách</span>
                </td>
                <td style="text-align: right;"><strong><?= $total_fmt ?> ₫</strong></td>
            </tr>
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
            <span style="font-weight: 600;">Tổng thanh toán:</span>
            <strong style="color: #059669; font-size: 18px;"><?= $total_fmt ?> ₫</strong>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="table-container" style="padding: 24px; background: white; border-radius: 12px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px;">Khách hàng</h3>
            <p><strong>Tên:</strong> <?= htmlspecialchars($order['customer_name'] ?? 'Khách vãng lai') ?></p>
            <p style="margin-top: 8px;"><strong>Email:</strong> <?= htmlspecialchars($order['email'] ?? 'N/A') ?></p>
            <p style="margin-top: 8px;"><strong>SĐT:</strong> <?= htmlspecialchars($order['phone'] ?? 'N/A') ?></p>
        </div>

        <div class="table-container" style="padding: 24px; background: white; border-radius: 12px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px;">Giao hàng & Thanh toán</h3>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['shipping_address'] ?? 'Chưa cung cấp') ?></p>
            <p style="margin-top: 8px;"><strong>Phương thức:</strong> <?= htmlspecialchars(strtoupper($order['payment_method'])) ?></p>
            <p style="margin-top: 8px;"><strong>Trạng thái:</strong>
                <span style="font-weight: bold; color: #2563eb;"><?= strtoupper($order['status']) ?></span>
            </p>
            <?php if(!empty($order['note'])): ?>
                <div style="margin-top: 16px; padding: 12px; background: #fffbeb; border-left: 4px solid #f59e0b; font-size: 13px;">
                    <strong>Ghi chú:</strong> <?= htmlspecialchars($order['note']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>