<?php
/**
 * TRANG QUẢN LÝ ĐƠN HÀNG - K2 GEAR
 * File này sử dụng biến $pdo từ database.php và hàm get_admin_icon() từ icons.php
 */

// 1. CẤU HÌNH PHÂN TRANG (PAGINATION) ĐỘNG
$limit = 5; // Số đơn hàng hiển thị trên mỗi trang
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// 2. ĐẾM TỔNG SỐ ĐƠN HÀNG ĐỂ TÍNH SỐ TRANG
try {
    $count_stmt = $pdo->query("SELECT COUNT(*) FROM orders");
    $total_orders = $count_stmt->fetchColumn();
    $total_pages = ceil($total_orders / $limit);

    // 3. LẤY DỮ LIỆU ĐƠN HÀNG VÀ TÊN KHÁCH HÀNG (JOIN) THEO TRANG
    $sql = "SELECT o.*, u.name as customer_name 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC 
            LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Lỗi hệ thống: " . $e->getMessage());
}

// Tính toán con số hiển thị (Ví dụ: 1 - 5 trên 7)
$start_item = ($total_orders > 0) ? $offset + 1 : 0;
$end_item = min($offset + $limit, $total_orders);
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Quản lý đơn hàng</h1>
        <p class="page-subtitle">Xem, xử lý và theo dõi các đơn hàng khách hàng đã đặt.</p>
    </div>
    <a href="?view=order_add" class="btn btn-primary">
        <?= get_admin_icon('plus') ?> Tạo đơn hàng mới
    </a>
</div>

<div class="top-controls">
    <div class="filter-card">
        <div class="filter-group">
            <label>TRẠNG THÁI ĐƠN</label>
            <select class="form-select">
                <option>Tất cả trạng thái</option>
                <option>Chờ xử lý</option>
                <option>Đã xác nhận</option>
                <option>Đang giao</option>
                <option>Đã hủy</option>
            </select>
        </div>
        <div class="filter-group">
            <label>P.T THANH TOÁN</label>
            <select class="form-select">
                <option>Tất cả phương thức</option>
                <option>MoMo</option>
                <option>COD</option>
                <option>Chuyển khoản</option>
            </select>
        </div>
        <div class="filter-badges">
    <span class="badge badge-status-pending">Chờ xử lý</span>
    <span class="badge badge-status-confirmed">Đã xác nhận</span>
    <span class="badge badge-status-shipping">Đang giao</span>
    <span class="badge badge-status-delivered">Đã giao</span>
    <span class="badge badge-status-cancelled">Đã hủy</span>
</div>
    </div>

    <div class="summary-card">
        <p class="summary-title">Doanh thu tháng này</p>
        <h2 class="summary-value">1,248,390.000</h2>
        <p class="summary-trend trend-up">↗ +12.4% so với tháng trước</p>
    </div>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
        <tr>
            <th>MÃ ĐƠN</th>
            <th>KHÁCH HÀNG</th>
            <th>NGÀY ĐẶT</th>
            <th>TỔNG TIỀN</th>
            <th>P.T THANH TOÁN</th>
            <th>TRẠNG THÁI</th>
            <th>THAO TÁC</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($orders)): ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">Không tìm thấy đơn hàng nào.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($orders as $row): ?>
                <?php
                $order_id_fmt = "#OD-" . str_pad($row['id'], 5, '0', STR_PAD_LEFT);

                $total_fmt = number_format($row['total'], 0, ',', '.');
                $date_fmt = date('H:i - d/m/Y', strtotime($row['created_at']));

                // Logic màu sắc trạng thái
                // Khởi tạo mặc định để tránh lỗi rỗng
                // Khởi tạo mặc định
                $status_class = '';
                $status_text = '';

                switch ($row['status']) {
                    case 'pending':
                        $status_class = 'badge-status-pending';
                        $status_text = 'Chờ xử lý';
                        break;
                    case 'confirmed': /* Dùng lại chữ confirmed cho Đã xác nhận */
                        $status_class = 'badge-status-confirmed';
                        $status_text = 'Đã xác nhận';
                        break;
                    case 'shipping':
                        $status_class = 'badge-status-shipping';
                        $status_text = 'Đang giao hàng';
                        break;
                    case 'delivered': /* Dùng lại chữ delivered cho Đã giao */
                        $status_class = 'badge-status-delivered';
                        $status_text = 'Đã giao';
                        break;
                    case 'cancelled':
                        $status_class = 'badge-status-cancelled';
                        $status_text = 'Đã hủy';
                        break;
                    default:
                        $status_class = 'badge-status-cancelled';
                        $status_text = 'Chưa rõ';
                }
                ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <div class="product-meta">
                                <strong><?= htmlspecialchars($order_id_fmt) ?></strong>
                                <span>#<?= $row['id'] ?></span>
                            </div>
                        </div>
                    </td>
                    <td><span class="visibility-status"><?= htmlspecialchars($row['customer_name'] ?? 'N/A') ?></span></td>
                    <td><span class="text-muted"><?= htmlspecialchars($date_fmt) ?></span></td>
                    <td><strong class="text-success"><?= $total_fmt ?> ₫</strong></td>

                    <td><span class="badge badge-blue"><?= htmlspecialchars($row['payment_method'] ?? 'COD') ?></span></td>

                    <td><span class="badge <?= $status_class ?>"><?= htmlspecialchars($status_text) ?></span></td>
                    <td>
                        <div class="action-buttons">
                            <a href="?view=order_detail&id=<?= $row['id'] ?>" class="btn-icon" title="Xem chi tiết">
                                <?= get_admin_icon('eye') ?>
                            </a>
                            <a href="?view=order_edit&id=<?= $row['id'] ?>" class="btn-icon" title="Sửa trạng thái">
                                <?= get_admin_icon('edit') ?>
                            </a>
                            <a href="modules/order/delete.php?id=<?= $row['id'] ?>" class="btn-icon text-danger" title="Xóa/Hủy đơn" onclick="return confirm('Xóa đơn hàng này?')">
                                <?= get_admin_icon('trash') ?>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination-wrapper">
        <span class="pagination-info">Đang hiển thị <strong><?= $start_item ?> - <?= $end_item ?></strong> trên <strong><?= $total_orders ?></strong> đơn hàng</span>

        <div class="pagination-controls">
            <?php if ($page > 1): ?>
                <a href="?view=orders&page=<?= $page - 1 ?>" class="btn-prev">
                    <?= get_admin_icon('prev_page', 'icon-sm') ?>
                </a>
            <?php else: ?>
                <button class="btn-prev disabled">
                    <?= get_admin_icon('prev_page', 'icon-sm') ?>
                </button>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?view=orders&page=<?= $i ?>" class="btn-page <?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?view=orders&page=<?= $page + 1 ?>" class="btn-next">
                    <?= get_admin_icon('next_page', 'icon-sm') ?>
                </a>
            <?php else: ?>
                <button class="btn-next disabled">
                    <?= get_admin_icon('next_page', 'icon-sm') ?>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

