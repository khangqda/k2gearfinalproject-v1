<?php
$admin_name = $_SESSION['admin_name'] ?? 'Khách';

// Lấy tên và vai trò từ Session (đã được tạo ở index.php)
$admin_name = $_SESSION['admin_name'] ?? 'Khách';
$admin_role = $_SESSION['admin_role'] ?? 'CHƯA RÕ';

// --- LOGIC TẠO AVATAR CHỮ KÝ (Ví dụ: Admin User -> AU) ---
$words = explode(' ', $admin_name); // Cắt tên thành các từ
$avatar_initials = '';
foreach ($words as $w) {
    $avatar_initials .= mb_substr($w, 0, 1, 'UTF-8'); // Lấy chữ cái đầu của mỗi từ
}
// Lấy tối đa 2 chữ cái đầu và viết hoa
$avatar_initials = mb_substr(mb_strtoupper($avatar_initials, 'UTF-8'), 0, 2);
?>

<header class="admin-header">
    <div class="search-container">
        <button class="icon-btn mobile-menu-btn" onclick="toggleSidebar()">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <svg class="icon text-muted" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" class="search-input" placeholder="Tìm kiếm trong hệ sinh thái...">
    </div>

    <div class="header-right">

        <button class="icon-btn" title="Thông báo">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="#111827" stroke-width="2">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
        </button>
        <div class="user-profile">
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($admin_name) ?></span>
                <span class="user-role"><?= htmlspecialchars($admin_role) ?></span>
            </div>

            <div class="avatar text-avatar">
                <?= htmlspecialchars($avatar_initials) ?>
            </div>
        </div>
    </div>
</header>