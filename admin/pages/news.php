<?php
/**
 * TRANG QUẢN LÝ TIN TỨC (NEWS) - K2 GEAR
 */

// 1. PHÂN TRANG (PAGINATION)
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    // Đếm tổng số tin tức
    $count_stmt = $pdo->query("SELECT COUNT(*) FROM posts");
    $total_news = $count_stmt->fetchColumn();
    $total_pages = ceil($total_news / $limit);

    // 2. LẤY DỮ LIỆU TIN TỨC (Sử dụng bảng posts như trong file SQL của bạn)
    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $news_list = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}

$start_item = ($total_news > 0) ? $offset + 1 : 0;
$end_item = min($offset + $limit, $total_news);
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Quản lý tin tức</h1>
        <p class="page-subtitle">Cập nhật những bài viết công nghệ mới nhất cho K2 Gear.</p>
    </div>
    <button class="btn btn-primary">
        <?= get_admin_icon('plus') ?> Thêm bài viết
    </button>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
        <tr>
            <th>BÀI VIẾT</th>
            <th>MÔ TẢ NGẮN</th>
            <th>NGÀY ĐĂNG</th>
            <th>TRẠNG THÁI</th>
            <th>THAO TÁC</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($news_list)): ?>
            <tr><td colspan="5" style="text-align: center; padding: 40px; color: #6b7280;">Chưa có bài viết nào trong hệ thống.</td></tr>
        <?php else: ?>
            <?php foreach ($news_list as $item): ?>
                <tr>
                    <td style="max-width: 280px;">
                        <div class="product-info">
                            <img src="<?= htmlspecialchars($item['thumbnail'] ?? 'assets/images/default-news.png') ?>"
                                 class="product-img" style="border-radius: 6px; width: 60px; height: 40px; object-fit: cover;">
                            <div class="product-meta">
                                <strong style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.2;">
                                    <?= htmlspecialchars($item['title']) ?>
                                </strong>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="max-width: 250px; font-size: 13px; color: #6b7280; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <?= htmlspecialchars($item['summary'] ?? 'Không có mô tả ngắn...') ?>
                        </div>
                    </td>
                    <td><span class="text-muted"><?= date('d/m/Y', strtotime($item['created_at'])) ?></span></td>
                    <td>
                        <?php if(($item['status'] ?? 'published') == 'published'): ?>
                            <span class="badge badge-status-confirmed">Công khai</span>
                        <?php else: ?>
                            <span class="badge badge-status-pending">Bản nháp</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" title="Xem bài"><?= get_admin_icon('eye') ?></button>
                            <button class="btn-icon" title="Sửa bài"><?= get_admin_icon('edit') ?></button>
                            <button class="btn-icon text-danger" title="Xóa bài" onclick="return confirm('Bạn muốn xóa bài viết này?')">
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
        <span class="pagination-info">Đang xem <strong><?= $start_item ?> - <?= $end_item ?></strong> trong <strong><?= $total_news ?></strong> bài</span>
        <div class="pagination-controls">
            <?php if ($page > 1): ?>
                <a href="?view=news&page=<?= $page - 1 ?>" class="btn-prev"><?= get_admin_icon('prev_page', 'icon-sm') ?></a>
            <?php else: ?>
                <button class="btn-prev disabled"><?= get_admin_icon('prev_page', 'icon-sm') ?></button>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?view=news&page=<?= $i ?>" class="btn-page <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?view=news&page=<?= $page + 1 ?>" class="btn-next"><?= get_admin_icon('next_page', 'icon-sm') ?></a>
            <?php else: ?>
                <button class="btn-next disabled"><?= get_admin_icon('next_page', 'icon-sm') ?></button>
            <?php endif; ?>
        </div>
    </div>
</div>