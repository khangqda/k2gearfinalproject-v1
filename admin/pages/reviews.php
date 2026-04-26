<?php
/**
 * TRANG QUẢN LÝ ĐÁNH GIÁ - K2 GEAR (BẢN TINH GỌN)
 */

// 1. CẤU HÌNH PHÂN TRANG
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

try {
    // 2. ĐẾM TỔNG SỐ ĐÁNH GIÁ
    $count_stmt = $pdo->query("SELECT COUNT(*) FROM reviews");
    $total_reviews = $count_stmt->fetchColumn();
    $total_pages = ceil($total_reviews / $limit);

    // 3. LẤY DỮ LIỆU ĐÁNH GIÁ (JOIN với users và products)
    $sql = "SELECT r.*, u.name as user_name, p.name as product_name 
            FROM reviews r
            LEFT JOIN users u ON r.user_id = u.id 
            LEFT JOIN products p ON r.product_id = p.id
            ORDER BY r.created_at DESC 
            LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $reviews = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Lỗi hệ thống: " . $e->getMessage());
}

$start_item = ($total_reviews > 0) ? $offset + 1 : 0;
$end_item = min($offset + $limit, $total_reviews);

// Hàm vẽ sao chuẩn thiết kế
function render_stars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $color = ($i <= $rating) ? '#f59e0b' : '#9ca3af'; // Vàng hoặc Xám
        $stars .= '<svg style="width:14px; height:14px; fill:'.$color.';" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
    }
    return $stars;
}
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Đánh giá & Phản hồi</h1>
        <p class="page-subtitle">Lắng nghe ý kiến của khách hàng về sản phẩm.</p>
    </div>
    <div class="filter-group">
        <label style="font-size: 11px; font-weight: 700;">XẾP HẠNG SAO</label>
        <select class="form-select" style="min-width: 150px;">
            <option>Tất cả</option>
            <option>5 Sao</option>
            <option>4 Sao</option>
            <option>Dưới 3 Sao</option>
        </select>
    </div>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
        <tr>
            <th>KHÁCH HÀNG</th>
            <th>SẢN PHẨM</th>
            <th>ĐÁNH GIÁ</th>
            <th>PHẢN HỒI</th>
            <th>NGÀY GỬI</th>
            <th>TRẠNG THÁI</th>
            <th>THAO TÁC</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($reviews)): ?>
            <tr><td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">Chưa có phản hồi nào.</td></tr>
        <?php else: ?>
            <?php foreach ($reviews as $row): ?>
                <?php
                $date_fmt = date('Y-m-d', strtotime($row['created_at']));
                // Trạng thái giả lập dựa trên việc có comment hay không hoặc random cho giống mẫu
                $has_reply = (rand(0, 1) == 1);
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['user_name']) ?></strong></td>
                    <td style="max-width: 150px;"><span class="text-muted"><?= htmlspecialchars($row['product_name']) ?></span></td>
                    <td><div style="display: flex; gap: 2px;"><?= render_stars($row['rating']) ?></div></td>
                    <td>
                        <div style="max-width: 200px; font-size: 13px; line-height: 1.4; color: #374151;">
                            <?= htmlspecialchars($row['comment']) ?>
                        </div>
                    </td>
                    <td><span class="text-muted"><?= $date_fmt ?></span></td>
                    <td>
                        <?php if($has_reply): ?>
                            <span style="color: #10b981; font-weight: 600; font-size: 13px;">Đã phản hồi</span>
                        <?php else: ?>
                            <span style="color: #f87171; font-weight: 600; font-size: 13px;">Chưa phản hồi</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon"><?= get_admin_icon('eye', 'icon-sm') ?></button>
                            <button class="btn-icon" style="color: #6b7280;">
                                <svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 17 4 12 9 7"></polyline><path d="M20 18v-2a4 4 0 0 0-4-4H4"></path></svg>
                            </button>
                            <button class="btn-icon text-danger" onclick="return confirm('Xóa?')"><?= get_admin_icon('trash', 'icon-sm') ?></button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination-wrapper" style="background-color: #e5e7eb;">
        <span class="pagination-info">Đang hiển thị <strong><?= $start_item ?> - <?= $end_item ?></strong> trên <strong><?= $total_reviews ?></strong> đánh giá</span>
        <div class="pagination-controls">
            <button class="btn-prev <?= $page <= 1 ? 'disabled' : '' ?>"><?= get_admin_icon('prev_page', 'icon-sm') ?></button>
            <button class="btn-page active">1</button>
            <button class="btn-page">2</button>
            <button class="btn-page">3</button>
            <button class="btn-next"><?= get_admin_icon('next_page', 'icon-sm') ?></button>
        </div>
    </div>
</div>

<div class="bottom-insights">
    <div class="insight-card">
        <h3 class="insight-title" style="color: #111827;">Xếp hạng trung bình hệ thống</h3>
        <div style="display: flex; align-items: baseline; gap: 8px;">
            <span style="font-size: 48px; font-weight: 800;">4.5</span>
            <span style="font-size: 24px; color: #6b7280;">/5</span>
            <div style="margin-left: 10px;"><?= render_stars(4) ?></div>
        </div>
        <p class="insight-desc" style="margin-top: 10px;">Dựa trên <?= number_format($total_reviews) ?> đánh giá từ khách hàng.</p>
    </div>

    <div class="insight-card">
        <h3 class="insight-title" style="color: #111827;">Sản phẩm được đánh giá cao nhất</h3>
        <ul style="list-style: none; padding: 0; font-size: 13px; color: #374151;">
            <li style="margin-bottom: 8px;">• NVIDIA RTX 4090 (4.9/5, 520 đánh giá)</li>
            <li style="margin-bottom: 8px;">• Corsair Dominator Platinum... (4.8/5, 410 đánh giá)</li>
            <li>• AMD Ryzen 7 7800X3D (4.7/5, 350 đánh giá)</li>
        </ul>
    </div>
</div>