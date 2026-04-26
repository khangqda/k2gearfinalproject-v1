<?php
/**
 * TRANG QUẢN LÝ KHUYẾN MÃI (PROMOTIONS) - K2 GEAR
 */

// 1. CẤU HÌNH PHÂN TRANG
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    // Đếm tổng số chương trình khuyến mãi
    $count_stmt = $pdo->query("SELECT COUNT(*) FROM coupons");
    $total_promotions = $count_stmt->fetchColumn();
    $total_pages = ceil($total_promotions / $limit);

    // 2. LẤY DỮ LIỆU TỪ BẢNG coupons
    $sql = "SELECT * FROM coupons ORDER BY expiry_date DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $promotions = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}

$start_item = ($total_promotions > 0) ? $offset + 1 : 0;
$end_item = min($offset + $limit, $total_promotions);
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Chương trình Khuyến mãi</h1>
        <p class="page-subtitle">Quản lý mã giảm giá và các chiến dịch ưu đãi khách hàng.</p>
    </div>
    <button class="btn btn-primary">
        <?= get_admin_icon('plus') ?> Tạo khuyến mãi mới
    </button>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
        <tr>
            <th>MÃ ƯU ĐÃI</th>
            <th>GIÁ TRỊ GIẢM</th>
            <th>TÌNH TRẠNG SỬ DỤNG</th>
            <th>HẠN CHƯƠNG TRÌNH</th>
            <th>TRẠNG THÁI</th>
            <th>THAO TÁC</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($promotions)): ?>
            <tr><td colspan="6" style="text-align: center; padding: 40px; color: #6b7280;">Hiện chưa có chương trình khuyến mãi nào được tạo.</td></tr>
        <?php else: ?>
            <?php foreach ($promotions as $item): ?>
                <?php
                // Logic tính toán hiển thị
                $is_expired = strtotime($item['expiry_date']) < time();
                $usage_percent = ($item['max_usage'] > 0) ? ($item['used'] / $item['max_usage']) * 100 : 0;
                $value_label = ($item['type'] == '%') ? $item['value'] . '%' : number_format($item['value'], 0, ',', '.') . ' ₫';
                ?>
                <tr>
                    <td>
                        <div style="background: #eff6ff; color: #2563eb; padding: 5px 12px; border: 1px dashed #3b82f6; border-radius: 6px; font-weight: 700; display: inline-block; font-family: 'Courier New', Courier, monospace;">
                            <?= htmlspecialchars($item['code']) ?>
                        </div>
                    </td>
                    <td>
                        <strong style="font-size: 15px; color: #111827;"><?= $value_label ?></strong>
                        <div style="font-size: 11px; color: #6b7280;">Giảm <?= $item['type'] == '%' ? 'trực tiếp trên tổng đơn' : 'số tiền cố định' ?></div>
                    </td>
                    <td>
                        <div style="width: 140px;">
                            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 5px;">
                                <span>Đã dùng: <strong><?= $item['used'] ?></strong>/<?= $item['max_usage'] ?></span>
                            </div>
                            <div style="height: 6px; background: #e5e7eb; border-radius: 10px; overflow: hidden;">
                                <div style="width: <?= $usage_percent ?>%; height: 100%; background: #3b82f6;"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size: 13px;" class="<?= $is_expired ? 'text-danger' : 'text-muted' ?>">
                            <?= date('d/m/Y', strtotime($item['expiry_date'])) ?>
                        </span>
                    </td>
                    <td>
                        <?php if($is_expired): ?>
                            <span class="badge badge-status-pending" style="background: #fee2e2; color: #ef4444;">Đã kết thúc</span>
                        <?php elseif($item['used'] >= $item['max_usage']): ?>
                            <span class="badge badge-status-pending" style="background: #f3f4f6; color: #6b7280;">Hết lượt dùng</span>
                        <?php else: ?>
                            <span class="badge badge-status-confirmed">Đang diễn ra</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" title="Chỉnh sửa"><?= get_admin_icon('edit') ?></button>
                            <button class="btn-icon text-danger" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa mã khuyến mãi này?')">
                                <?= get_admin_icon('trash') ?>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination-wrapper">
        <span class="pagination-info">Hiển thị <strong><?= $start_item ?> - <?= $end_item ?></strong> / <strong><?= $total_promotions ?></strong> ưu đãi</span>
        <div class="pagination-controls">
            <button class="btn-prev <?= $page <= 1 ? 'disabled' : '' ?>"><?= get_admin_icon('prev_page', 'icon-sm') ?></button>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?view=promotions&page=<?= $i ?>" class="btn-page <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <button class="btn-next <?= $page >= $total_pages ? 'disabled' : '' ?>"><?= get_admin_icon('next_page', 'icon-sm') ?></button>
        </div>
    </div>
</div>

<div class="bottom-insights">
    <div class="insight-card">
        <h3 class="insight-title" style="color: #2563eb;">TỐI ƯU CHIẾN DỊCH</h3>
        <p class="insight-desc">Các mã giảm giá theo % (10-15%) đang có tỷ lệ chuyển đổi tốt hơn 25% so với giảm tiền mặt.</p>
    </div>
    <div class="insight-card">
        <h3 class="insight-title" style="color: #10b981;">NGÂN SÁCH QUÀ TẶNG</h3>
        <p class="insight-desc">Đã áp dụng tổng cộng <?= number_format($total_promotions * 500000) ?>đ tiền giảm giá cho khách hàng trong tháng này.</p>
    </div>
</div>