<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Tạo đơn hàng mới</h1>
        <p class="page-subtitle">Tạo đơn hàng thủ công cho khách đặt qua điện thoại hoặc mua trực tiếp.</p>
    </div>
</div>

<div class="table-container" style="padding: 24px; background: white; border-radius: 12px; max-width: 800px;">
    <form action="modules/order/add.php" method="POST">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">CHỌN KHÁCH HÀNG *</label>
                    <select name="user_id" class="form-select" style="width: 100%;" required>
                        <option value="">-- Chọn khách hàng --</option>
                        <?php
                        // Tự động load danh sách khách hàng từ DB
                        $users = $pdo->query("SELECT id, name, phone FROM users WHERE role = 'customer' ORDER BY name ASC")->fetchAll();
                        foreach ($users as $u) {
                            $phone = !empty($u['phone']) ? " - " . $u['phone'] : "";
                            echo "<option value='{$u['id']}'>{$u['name']}{$phone}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TỔNG TIỀN (VNĐ) *</label>
                    <input type="number" name="total" class="form-select" style="width: 100%;" required placeholder="VD: 15000000">
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">PHƯƠNG THỨC THANH TOÁN *</label>
                    <select name="payment_method" class="form-select" style="width: 100%;" required>
                        <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                        <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                        <option value="momo">Ví MoMo</option>
                        <option value="credit_card">Thẻ tín dụng / Ghi nợ</option>
                    </select>
                </div>
            </div>

            <div>
                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TRẠNG THÁI ĐƠN HÀNG *</label>
                    <select name="status" class="form-select" style="width: 100%;" required>
                        <option value="pending">Chờ xử lý</option>
                        <option value="confirmed">Đã xác nhận</option>
                        <option value="shipping">Đang giao hàng</option>
                        <option value="delivered">Đã giao</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ĐỊA CHỈ GIAO HÀNG</label>
                    <textarea name="shipping_address" class="form-select" rows="2" style="width: 100%; resize: vertical;" placeholder="Nhập địa chỉ giao hàng cụ thể..."></textarea>
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">GHI CHÚ ĐƠN HÀNG</label>
                    <textarea name="note" class="form-select" rows="2" style="width: 100%; resize: vertical;" placeholder="Ghi chú thêm về đơn hàng hoặc tên sản phẩm..."></textarea>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
            <button type="submit" name="submit_add_order" class="btn btn-primary">
                <?= get_admin_icon('plus') ?> Lưu đơn hàng
            </button>
            <a href="?view=orders" class="btn" style="background: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">Hủy bỏ</a>
        </div>
    </form>
</div>
