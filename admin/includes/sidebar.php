<?php
// Lấy giá trị view hiện tại để bôi màu xanh menu tương ứng
$current_view = $_GET['view'] ?? 'dashboard';
?>
<aside class="admin-sidebar">
    <div class="sidebar-logo">
        <h2>K2 GEAR</h2>
    </div>

    <nav class="sidebar-nav">
        <ul class="menu-list">
            <li>
                <a href="?view=dashboard" class="menu-item <?= $current_view == 'dashboard' ? 'active' : '' ?>">
                    <?= get_admin_icon('Bang_dieu_khien') ?> Bảng điều khiển
                </a>
            </li>
            <li>
                <a href="?view=products" class="menu-item <?= $current_view == 'products' ? 'active' : '' ?>">
                    <?= get_admin_icon('San_pham') ?> Sản phẩm
                </a>
            </li>
            <li>
                <a href="?view=categories" class="menu-item <?= $current_view == 'categories' ? 'active' : '' ?>">
                    <?= get_admin_icon('Danh_muc') ?> Danh mục
                </a>
            </li>
            <li>
                <a href="?view=orders" class="menu-item <?= $current_view == 'orders' ? 'active' : '' ?>">
                    <?= get_admin_icon('Don_hang') ?> Đơn hàng
                </a>
            </li>
            <li>
                <a href="?view=customers" class="menu-item <?= $current_view == 'customers' ? 'active' : '' ?>">
                    <?= get_admin_icon('Khach_hang') ?> Khách hàng
                </a>
            </li>
            <li>
                <a href="?view=employees" class="menu-item <?= $current_view == 'employees' ? 'active' : '' ?>">
                    <?= get_admin_icon('Nhan_vien') ?> Nhân viên
                </a>
            </li>
            <li>
                <a href="?view=promotions" class="menu-item <?= $current_view == 'promotions' ? 'active' : '' ?>">
                    <?= get_admin_icon('Khuyen_mai') ?> Khuyến mãi
                </a>
            </li>
            <li>
                <a href="?view=news" class="menu-item <?= $current_view == 'news' ? 'active' : '' ?>">
                    <?= get_admin_icon('Tin_tuc') ?> Tin tức
                </a>
            </li>
            <li>
                <a href="?view=reviews" class="menu-item <?= $current_view == 'reviews' ? 'active' : '' ?>">
                    <?= get_admin_icon('Danh_gia') ?> Đánh giá
                </a>
            </li>
            <li>
                <a href="?view=settings" class="menu-item <?= $current_view == 'settings' ? 'active' : '' ?>">
                    <?= get_admin_icon('Cai_dat') ?> Cài đặt
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-bottom">
        <a href="?view=support" class="menu-item">
            <?= get_admin_icon('Ho_tro') ?> Hỗ trợ
        </a>
        <a href="logout.php" class="menu-item">
            <?= get_admin_icon('Dang_xuat') ?> Đăng xuất
        </a>
    </div>
</aside>