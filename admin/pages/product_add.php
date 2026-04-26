<div class="page-header">
    <div class="page-title-wrapper">
        <h1 class="page-title">Thêm sản phẩm mới</h1>
        <p class="page-subtitle">Nhập thông tin chi tiết cho phần cứng mới vào hệ thống.</p>
    </div>
</div>

<div class="table-container" style="padding: 24px; background: white; border-radius: 12px;">
    <form action="modules/products/add.php" method="POST" enctype="multipart/form-data">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div>
                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TÊN SẢN PHẨM *</label>
                    <input type="text" name="name" class="form-select" style="width: 100%;" required placeholder="VD: Intel Core i9-14900K">
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ĐƯỜNG DẪN (SLUG) *</label>
                    <input type="text" name="slug" class="form-select" style="width: 100%;" required placeholder="VD: intel-core-i9-14900k">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="filter-group">
                        <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">GIÁ GỐC (VNĐ) *</label>
                        <input type="number" name="price" class="form-select" style="width: 100%;" required placeholder="VD: 15000000">
                    </div>
                    <div class="filter-group">
                        <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">GIÁ KHUYẾN MÃI (VNĐ)</label>
                        <input type="number" name="sale_price" class="form-select" style="width: 100%;" placeholder="VD: 14500000 (Để trống nếu không giảm)">
                    </div>
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">SỐ LƯỢNG KHO *</label>
                    <input type="number" name="stock" class="form-select" style="width: 100%;" required value="10">
                </div>
            </div>

            <div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div class="filter-group">
                        <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">DANH MỤC *</label>
                        <select name="category_id" class="form-select" style="width: 100%;" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php
                            $cats = $pdo->query("SELECT id, name FROM categories")->fetchAll();
                            foreach ($cats as $c) {
                                echo "<option value='{$c['id']}'>{$c['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">THƯƠNG HIỆU *</label>
                        <select name="brand_id" class="form-select" style="width: 100%;" required>
                            <option value="">-- Chọn hãng --</option>
                            <?php
                            $brands = $pdo->query("SELECT id, name FROM brands")->fetchAll();
                            foreach ($brands as $b) {
                                echo "<option value='{$b['id']}'>{$b['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">ẢNH ĐẠI DIỆN MỚI</label>
                    <input type="text" name="thumbnail" class="form-select" style="width: 100%; padding: 6px;" placeholder="VD: https://link-anh-vga.jpg">
                    <img src="<?= htmlspecialchars($product['thumbnail']) ?>" alt="Curent Image" style="height: 60px; border-radius: 4px; margin-top: 8px; border: 1px solid #e5e7eb;">
                </div>

                <div class="filter-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">TRẠNG THÁI</label>
                    <select name="status" class="form-select" style="width: 100%;">
                        <option value="1">Hiển thị</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="filter-group" style="margin-bottom: 24px;">
            <label style="font-weight: 600; font-size: 13px; margin-bottom: 8px; display: block;">MÔ TẢ SẢN PHẨM</label>
            <textarea name="description" class="form-select" rows="4" style="width: 100%; resize: vertical;" placeholder="Nhập mô tả chi tiết phần cứng..."></textarea>
        </div>

        <div style="display: flex; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
            <button type="submit" name="submit_add_product" class="btn btn-primary">
                <?= get_admin_icon('plus') ?> Lưu sản phẩm
            </button>
            <a href="?view=products" class="btn" style="background: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">Hủy bỏ</a>
        </div>
    </form>
</div>