<?php
/**
 * TRANG QUẢN LÝ KHÁCH HÀNG - K2 GEAR
 * Cập nhật: Bổ sung Tìm kiếm & Thêm/Sửa/Xóa
 */

// 1. CẤU HÌNH PHÂN TRANG & TÌM KIẾM
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Bắt từ khóa tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_condition = "";

// Nếu có tìm kiếm, thêm điều kiện WHERE cho Tên, Email hoặc Số điện thoại
if (!empty($search)) {
    $search_condition = " AND (u.name LIKE :search OR u.email LIKE :search OR u.phone LIKE :search) ";
}

try {
    // 2. ĐẾM TỔNG SỐ KHÁCH HÀNG (Có tính điều kiện tìm kiếm)
    $count_sql = "SELECT COUNT(*) FROM users u WHERE u.role = 'customer'" . $search_condition;
    $count_stmt = $pdo->prepare($count_sql);
    if (!empty($search)) {
        $count_stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
    $count_stmt->execute();
    $total_customers = $count_stmt->fetchColumn();
    $total_pages = ceil($total_customers / $limit);

    // 3. LẤY DỮ LIỆU KHÁCH HÀNG VÀ TÍNH TỔNG ĐƠN HÀNG, TỔNG CHI TIÊU
    $sql = "SELECT u.id, u.name, u.email, u.phone, u.created_at, 
                   COUNT(o.id) as total_orders, 
                   SUM(o.total) as total_spent
            FROM users u
            LEFT JOIN orders o ON u.id = o.user_id 
            WHERE u.role = 'customer' " . $search_condition . "
            GROUP BY u.id
            ORDER BY u.created_at DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $customers = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Lỗi hệ thống: " . $e->getMessage());
}

$start_item = ($total_customers > 0) ? $offset + 1 : 0;
$end_item = min($offset + $limit, $total_customers);
?>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Quản lý khách hàng</h1>
        <p class="page-subtitle">Xem và quản lý thông tin khách hàng của bạn.</p>
    </div>
    <a href="?view=customer_add" class="btn btn-primary">
        <?= get_admin_icon('plus') ?> Thêm khách hàng
    </a>
</div>

<div class="table-container">
    <div style="padding: 16px 24px; border-bottom: 1px solid #e5e7eb; background-color: #e5e7eb; display: flex; align-items: center;">
        <form method="GET" action="index.php" style="display: flex; gap: 8px; width: 100%; max-width: 500px;">
            <input type="hidden" name="view" value="customers">

            <div class="search-container" style="flex: 1; background: #ffffff; border: 1px solid #d1d5db; display: flex; align-items: center; padding: 0 12px; border-radius: 6px;">
                <svg class="icon text-muted" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" style="width: 16px; height: 16px;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="search" class="search-input" placeholder="Tìm tên, email, số điện thoại..." value="<?= htmlspecialchars($search) ?>" style="border: none; outline: none; padding: 8px; width: 100%; background: transparent;">
            </div>

            <button type="submit" class="btn" style="background: white; border: 1px solid #d1d5db;">Tìm kiếm</button>

            <?php if(!empty($search)): ?>
                <a href="?view=customers" class="btn text-danger" style="background: #fee2e2; border: 1px solid #fca5a5;">Hủy lọc</a>
            <?php endif; ?>
        </form>
    </div>

    <table class="admin-table">
        <thead>
        <tr>
            <th>KHÁCH HÀNG</th>
            <th>TỔNG ĐƠN HÀNG</th>
            <th>TỔNG CHI TIÊU</th>
            <th>NGÀY THAM GIA</th>
            <th>THAO TÁC</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($customers)): ?>
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: #6b7280;">Không tìm thấy khách hàng nào phù hợp.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($customers as $row): ?>
                <?php
                $total_spent = $row['total_spent'] ? $row['total_spent'] : 0;
                $spent_fmt = number_format($total_spent, 0, ',', '.');
                $date_fmt = date('d/m/Y', strtotime($row['created_at']));

                // Tạo avatar tự động từ tên khách hàng
                $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($row['name']) . "&background=random&color=fff&rounded=true&size=100";
                ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="<?= $avatar_url ?>" alt="Avatar" class="product-img" style="border-radius: 50%; width: 40px; height: 40px;">
                            <div class="product-meta">
                                <strong><?= htmlspecialchars($row['name']) ?></strong>
                                <span><?= htmlspecialchars($row['email']) ?></span>
                                <?php if(!empty($row['phone'])): ?>
                                    <span style="font-size: 11px; color: #6b7280;">📞 <?= htmlspecialchars($row['phone']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><strong><?= $row['total_orders'] ?></strong></td>
                    <td><strong style="color: #059669;"><?= $spent_fmt ?> ₫</strong></td>
                    <td><span class="text-muted"><?= $date_fmt ?></span></td>
                    <td>
                        <div class="action-buttons">
                            <a href="?view=customer_edit&id=<?= $row['id'] ?>" class="btn-icon" title="Chỉnh sửa">
                                <?= get_admin_icon('edit') ?>
                            </a>
                            <a href="modules/customer/delete.php?id=<?= $row['id'] ?>" class="btn-icon text-danger" title="Xóa khách hàng" onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này? Mọi dữ liệu liên quan có thể bị ảnh hưởng.')">
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
        <span class="pagination-info">Đang hiển thị <strong><?= $start_item ?> - <?= $end_item ?></strong> trên <strong><?= $total_customers ?></strong> khách hàng</span>

        <div class="pagination-controls">
            <?php $search_param = !empty($search) ? "&search=".urlencode($search) : ""; ?>

            <?php if ($page > 1): ?>
                <a href="?view=customers<?= $search_param ?>&page=<?= $page - 1 ?>" class="btn-prev">
                    <?= get_admin_icon('prev_page', 'icon-sm') ?>
                </a>
            <?php else: ?>
                <button class="btn-prev disabled">
                    <?= get_admin_icon('prev_page', 'icon-sm') ?>
                </button>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?view=customers<?= $search_param ?>&page=<?= $i ?>" class="btn-page <?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?view=customers<?= $search_param ?>&page=<?= $page + 1 ?>" class="btn-next">
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

