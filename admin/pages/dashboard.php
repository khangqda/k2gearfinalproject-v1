<?php
/**
 * TRANG BẢNG ĐIỀU KHIỂN (DASHBOARD)
 */

// --- 1. LẤY DỮ LIỆU ĐỘNG TỪ DATABASE CHO 4 THẺ THỐNG KÊ ---
try {
    // 1.1 Tổng sản phẩm (Ví dụ tạm thay cho Doanh thu nếu chưa có bảng Orders)
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $total_products = $stmt->fetchColumn();

    // 1.2 Đếm số Danh mục (Ví dụ tạm thay cho Tổng đơn hàng)
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
    $total_categories = $stmt->fetchColumn();

    // 1.3 Cảnh báo kho (Sản phẩm có số lượng < 10)
    $stmt = $pdo->query("SELECT COUNT(*) FROM products WHERE stock < 10");
    $low_stock_count = $stmt->fetchColumn();

} catch (PDOException $e) {
    // Xử lý lỗi im lặng nếu chưa có bảng
    $total_products = 0; $total_categories = 0; $low_stock_count = 0;
}

// --- 2. CHUẨN BỊ DỮ LIỆU CHO BIỂU ĐỒ (Sẽ truyền cho JavaScript) ---
// Dữ liệu biểu đồ đường (Doanh thu 30 ngày)
$chart_line_labels = ['1', '4', '7', '10', '14', '17', '20', '24', '28', '30'];
$chart_line_data   = [300, 600, 700, 1200, 1400, 2100, 2300, 2600, 3100, 3200];

// Dữ liệu biểu đồ tròn (Danh mục)
$chart_pie_labels = ['GPU', 'CPU', 'RAM', 'Storage'];
$chart_pie_data   = [45, 30, 15, 10];
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Bảng điều khiển Admin</h1>
        <p class="page-subtitle">Chào mừng trở lại, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>!</p>
    </div>
</div>

<div class="dashboard-summary">
    <div class="dash-card dark-card">
        <div class="dash-card-header">
            <span>Tổng Doanh Thu</span>
            <div class="icon-wrap text-success"><?= get_admin_icon('dash_revenue') ?></div>
        </div>
        <h2 class="dash-value">$2,845,120</h2>
        <div class="dash-trend text-success">↑ +15.2% vs last month</div>
    </div>

    <div class="dash-card dark-card">
        <div class="dash-card-header">
            <span>Tổng Sản Phẩm</span>
            <div class="icon-wrap text-success"><?= get_admin_icon('dash_order') ?></div>
        </div>
        <h2 class="dash-value"><?= number_format($total_products) ?></h2>
        <div class="dash-trend text-success">↑ +8.5% vs last month</div>
    </div>

    <div class="dash-card dark-card">
        <div class="dash-card-header">
            <span>Khách Hàng Mới</span>
            <div class="icon-wrap text-success"><?= get_admin_icon('dash_user') ?></div>
        </div>
        <h2 class="dash-value">320</h2>
        <div class="dash-trend text-success">↑ +20.1% vs last month</div>
    </div>

    <div class="dash-card dark-card">
        <div class="dash-card-header">
            <span>Cảnh Báo Kho</span>
            <div class="icon-wrap text-danger"><?= get_admin_icon('dash_alert') ?></div>
        </div>
        <h2 class="dash-value text-danger"><?= number_format($low_stock_count) ?></h2>
        <div class="dash-trend text-danger">Cần nhập hàng ngay</div>
    </div>
</div>

<div class="dashboard-charts">
    <div class="chart-card dark-card">
        <h3>Xu Hướng Doanh Thu (30 Ngày)</h3>
        <div class="canvas-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="chart-card dark-card" style="flex: 0.5;">
        <h3>Doanh Thu Theo Danh Mục</h3>
        <div class="canvas-container">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Biến dữ liệu PHP thành dạng JSON để Javascript đọc được
    const lineLabels = <?= json_encode($chart_line_labels) ?>;
    const lineData   = <?= json_encode($chart_line_data) ?>;
    const pieLabels  = <?= json_encode($chart_pie_labels) ?>;
    const pieData    = <?= json_encode($chart_pie_data) ?>;

    // Vẽ Biểu đồ Đường (Revenue Trend)
    const ctxLine = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: lineLabels,
            datasets: [{
                label: 'Doanh thu ($)',
                data: lineData,
                borderColor: '#8b5cf6', // Màu tím viền
                backgroundColor: 'rgba(139, 92, 246, 0.2)', // Màu tím nhạt tô dưới
                borderWidth: 3,
                tension: 0.4, // Tạo độ cong mềm mại
                fill: true
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#4b5563' }, ticks: { color: '#9ca3af' } },
                x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
            }
        }
    });

    // Vẽ Biểu đồ Tròn (Category Pie)
    const ctxPie = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut', // Hình vành khăn
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: ['#3b82f6', '#a855f7', '#ec4899', '#f97316'], // Xanh, Tím, Hồng, Cam
                borderWidth: 0
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '70%', // Độ mỏng của vòng
            plugins: {
                legend: { position: 'bottom', labels: { color: '#9ca3af' } }
            }
        }
    });
</script>
