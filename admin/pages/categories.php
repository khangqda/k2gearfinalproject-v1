<?php
/**
 * TRANG QUẢN LÝ DANH MỤC - K2 GEAR
 */

// 1. CẤU HÌNH PHÂN TRANG
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    // Đếm tổng số danh mục
    $count_stmt = $pdo->query("SELECT COUNT(*) FROM categories");
    $total_categories = $count_stmt->fetchColumn();
    $total_pages = ceil($total_categories / $limit);

    // 2. LẤY DỮ LIỆU DANH MỤC VÀ ĐẾM SỐ SẢN PHẨM THUỘC DANH MỤC ĐÓ
    $sql = "SELECT c.*, COUNT(p.id) as product_count 
            FROM categories c 
            LEFT JOIN products p ON c.id = p.category_id 
            GROUP BY c.id 
            ORDER BY c.id DESC 
            LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}

$start_item = ($total_categories > 0) ? $offset + 1 : 0;
$end_item = min($offset + $limit, $total_categories);
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Quản lý danh mục</h1>
        <p class="page-subtitle">Tổ chức các nhóm sản phẩm (CPU, RAM, VGA...) một cách khoa học.</p>
    </div>
    <a href="?view=category_add" class="btn btn-primary">
        <?= get_admin_icon('plus') ?> Thêm danh mục
    </a>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
        <tr>
            <th>TÊN DANH MỤC</th>
            <th>URL (SLUG)</th>
            <th>SỐ SẢN PHẨM</th>
            <th>TRẠNG THÁI</th>
            <th>THAO TÁC</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($categories)): ?>
            <tr><td colspan="5" style="text-align: center; padding: 40px; color: #6b7280;">Chưa có danh mục nào.</td></tr>
        <?php else: ?>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="background: #eff6ff; padding: 8px; border-radius: 6px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </div>
                            <strong><?= htmlspecialchars($cat['name']) ?></strong>
                        </div>
                    </td>
                    <td><code style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px; color: #ef4444;"><?= htmlspecialchars($cat['slug']) ?></code></td>
                    <td>
                        <strong><?= $cat['product_count'] ?></strong> <span class="text-muted" style="font-size: 12px;">sản phẩm</span>
                    </td>
                    <td>
                        <?php if($cat['status'] == 1): ?>
                            <span class="badge badge-status-confirmed">Hiển thị</span>
                        <?php else: ?>
                            <span class="badge badge-status-pending">Đang ẩn</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="?view=category_edit&id=<?= $cat['id'] ?>" class="btn-icon" title="Chỉnh sửa">
                                <?= get_admin_icon('edit') ?>
                            </a>
                            <a href="modules/category/delete.php?id=<?= $cat['id'] ?>" class="btn-icon text-danger" title="Xóa" onclick="return confirm('Cảnh báo: Xóa danh mục này có thể ảnh hưởng đến các sản phẩm thuộc danh mục. Bạn chắc chắn chứ?')">
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
        <span class="pagination-info">Đang hiển thị <strong><?= $start_item ?> - <?= $end_item ?></strong> trên <strong><?= $total_categories ?></strong> danh mục</span>
        <div class="pagination-controls">
            <?php if ($page > 1): ?>
                <a href="?view=categories&page=<?= $page - 1 ?>" class="btn-prev">
                    <?= get_admin_icon('prev_page', 'icon-sm') ?>
                </a>
            <?php else: ?>
                <button class="btn-prev disabled">
                    <?= get_admin_icon('prev_page', 'icon-sm') ?>
                </button>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?view=categories&page=<?= $i ?>" class="btn-page <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?view=categories&page=<?= $page + 1 ?>" class="btn-next">
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
