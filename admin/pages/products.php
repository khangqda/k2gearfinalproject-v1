<?php
/**
 * TRANG QUẢN LÝ SẢN PHẨM - K2 GEAR
 */

// 1. CẤU HÌNH PHÂN TRANG VÀ LỌC DANH MỤC
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Bắt biến cat (category) từ URL nếu người dùng chọn bộ lọc
$category_filter = isset($_GET['cat']) && is_numeric($_GET['cat']) ? (int)$_GET['cat'] : 0;

// Chuẩn bị câu lệnh WHERE động
$where_sql = "";
if ($category_filter > 0) {
    $where_sql = "WHERE p.category_id = :cat_id";
}

try {
    // 2. ĐẾM TỔNG SỐ SẢN PHẨM (Có tính bộ lọc)
    $count_sql = "SELECT COUNT(*) FROM products p " . $where_sql;
    $count_stmt = $pdo->prepare($count_sql);
    if ($category_filter > 0) {
        $count_stmt->bindValue(':cat_id', $category_filter, PDO::PARAM_INT);
    }
    $count_stmt->execute();
    $total_products = $count_stmt->fetchColumn();
    $total_pages = ceil($total_products / $limit);

    // 3. LẤY DỮ LIỆU SẢN PHẨM (Có tính bộ lọc)
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            $where_sql 
            ORDER BY p.id DESC 
            LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    if ($category_filter > 0) {
        $stmt->bindValue(':cat_id', $category_filter, PDO::PARAM_INT);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Lỗi hệ thống: " . $e->getMessage());
}

$start_item = ($total_products > 0) ? $offset + 1 : 0;
$end_item = min($offset + $limit, $total_products);
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Quản lý sản phẩm</h1>
        <p class="page-subtitle">Quản lý và giám sát tài sản tồn kho phần cứng của bạn.</p>
    </div>
    <a href="?view=product_add" class="btn btn-primary">
        <?= get_admin_icon('plus') ?> Thêm sản phẩm
    </a>
</div>

<div class="top-controls">
    <div class="filter-card">
        <div class="filter-group">
            <label>DANH MỤC</label>
            <select class="form-select" onchange="window.location.href='?view=products&cat='+this.value">
                <option value="0">Tất cả danh mục</option>
                <?php
                $cat_stmt = $pdo->query("SELECT id, name FROM categories");
                while ($cat = $cat_stmt->fetch()) {
                    // Giữ trạng thái selected cho danh mục đang chọn
                    $selected = ($category_filter == $cat['id']) ? 'selected' : '';
                    echo "<option value='{$cat['id']}' {$selected}>{$cat['name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="filter-badges">
            <span class="badge badge-green">Còn hàng</span>
            <span class="badge badge-yellow">Sắp hết hàng</span>
            <span class="badge" style="background-color: #fee2e2; color: #ef4444; border: 0px solid #fca5a5;">
    Hết hàng
</span>
        </div>
    </div>

    <div class="summary-card">
        <p class="summary-title">Sản phẩm tìm thấy</p>
        <h2 class="summary-value"><?= $total_products ?> <span style="font-size: 14px; font-weight: 500; color: #6b7280;">Sản phẩm</span></h2>
    </div>
</div>

<div class="table-container">
    <table class="admin-table">
        <thead>
        <tr>
            <th>PHẦN CỨNG</th>
            <th>DANH MỤC</th>
            <th>GIÁ / GIẢM GIÁ</th>
            <th>KHO</th>
            <th>TRẠNG THÁI</th>
            <th>HIỂN THỊ</th>
            <th>THAO TÁC</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($products)): ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">Không tìm thấy sản phẩm nào trong danh mục này.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($products as $row): ?>
                <?php
                $price_fmt = number_format($row['price'], 0, ',', '.');
                $has_sale = ($row['sale_price'] > 0 && $row['sale_price'] < $row['price']);
                $display_price = $has_sale ? number_format($row['sale_price'], 0, ',', '.') : $price_fmt;

                $stock_class = $row['stock'] > 0 ? 'text-success' : 'text-danger';
                if ($row['stock'] >= 10) {
                    $dot_class = 'dot-green';
                } elseif ($row['stock'] > 0) {
                    $dot_class = 'dot-yellow'; // Bạn có thể dùng dot-yellow hoặc dot-orange tùy CSS của bạn
                } else {
                    $dot_class = 'dot-red';
                }
                ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="<?= htmlspecialchars($row['thumbnail'] ?? 'assets/images/no-image.png') ?>" alt="" class="product-img">
                            <div class="product-meta">
                                <strong><?= htmlspecialchars($row['name']) ?></strong>
                                <span>SKU: <?= htmlspecialchars($row['slug']) ?></span>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-blue"><?= htmlspecialchars($row['category_name'] ?? 'N/A') ?></span></td>
                    <td>
                        <div class="price-info">
                            <strong><?= $display_price ?> ₫</strong><br>
                            <?php if ($has_sale): ?>
                                <span class="text-muted" style="text-decoration: line-through; font-size: 11px;"><?= $price_fmt ?> ₫</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><strong class="<?= $stock_class ?>"><?= $row['stock'] ?></strong></td>
                    <td>
                        <div class="status-indicator">
                            <span class="dot <?= $dot_class ?>"></span> 
                            <?php 
                                if ($row['stock'] >= 10) {
                                    echo 'Còn hàng';
                                } elseif ($row['stock'] > 0) {
                                    echo 'Sắp hết hàng';
                                } else {
                                    echo '<span style="color: #ef4444; font-weight: bold;">Hết hàng</span>';
                                }
                            ?>
                        </div>
                    </td>
                    <td>
                        <a href="modules/products/toggle_status.php?id=<?= $row['id'] ?>&cat=<?= $category_filter ?>&page=<?= $page ?>"
                           class="visibility-status" style="text-decoration: none; cursor: pointer; padding: 4px 8px; border-radius: 4px; border: 1px solid #e5e7eb; transition: 0.2s;"
                           onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                            <?= get_admin_icon('eye', 'icon-sm') ?>
                            <span style="font-weight: 500; color: <?= $row['status'] ==1 ? '#10b981' : '#ef4444' ?>;">
                                <?= $row['status'] == 1 ? 'Đang hiện' : 'Đang ẩn' ?>
                            </span>
                        </a>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="?view=product_edit&id=<?= $row['id'] ?>" class="btn-icon" title="Sửa">
                                <?= get_admin_icon('edit') ?>
                            </a>
                            <a href="modules/products/delete.php?id=<?= $row['id'] ?>" class="btn-icon text-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">
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
        <span class="pagination-info">Đang hiển thị <strong><?= $start_item ?> - <?= $end_item ?></strong> trên <strong><?= $total_products ?></strong> sản phẩm</span>

        <div class="pagination-controls">
            <?php if ($page > 1): ?>
                <a href="?view=products&cat=<?= $category_filter ?>&page=<?= $page - 1 ?>" class="btn-prev">
                    <?= get_admin_icon('prev_page', 'icon-sm') ?>
                </a>
            <?php else: ?>
                <button class="btn-prev disabled"><?= get_admin_icon('prev_page', 'icon-sm') ?></button>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?view=products&cat=<?= $category_filter ?>&page=<?= $i ?>" class="btn-page <?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?view=products&cat=<?= $category_filter ?>&page=<?= $page + 1 ?>" class="btn-next">
                    <?= get_admin_icon('next_page', 'icon-sm') ?>
                </a>
            <?php else: ?>
                <button class="btn-next disabled"><?= get_admin_icon('next_page', 'icon-sm') ?></button>
            <?php endif; ?>
        </div>
    </div>
</div>