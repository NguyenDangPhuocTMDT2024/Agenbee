<?php
$data = [
    'title' => 'Điền thông tin xác nhận đơn hàng',
];
if (isset($user)) {
    $data['user'] = $user;
}
if (isset($cartItemCount)) {
    $data['cartItemCount'] = $cartItemCount;
}
layout('sidebar', $data);
layout('header', $data);

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');

$orderList = isset($orders) && is_array($orders) ? $orders : [];
$pickedOrderId = isset($selectedOrderId) ? (string) $selectedOrderId : '';
?>

<style>
    .setup-page {
        background:
            radial-gradient(circle at top left, rgba(244, 196, 48, 0.15), transparent 28%),
            linear-gradient(180deg, #fffdf8 0%, #fff7e4 100%);
        border-radius: 28px;
    }

    .setup-hero {
        padding: 22px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(17, 17, 17, 0.95) 0%, rgba(36, 28, 12, 0.96) 100%);
        color: #fff8de;
        margin-bottom: 20px;
    }

    .setup-hero p {
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        font-size: 0.75rem;
        color: #f4c430;
        font-weight: 700;
    }

    .setup-hero h1 {
        margin: 6px 0 10px;
        font-size: clamp(1.4rem, 2.8vw, 2.2rem);
        font-weight: 800;
    }

    .setup-hero span {
        color: rgba(255, 248, 222, 0.82);
        font-size: 0.95rem;
    }

    .setup-form {
        display: grid;
        gap: 18px;
    }

    .setup-section {
        border-radius: 22px;
        border: 1px solid rgba(201, 154, 17, 0.14);
        background: linear-gradient(180deg, #ffffff 0%, #fff8ea 100%);
        box-shadow: 0 14px 30px rgba(31, 24, 9, 0.08);
        padding: 18px;
    }

    .setup-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 14px;
    }

    .setup-section-head h2 {
        margin: 0;
        font-size: 1.2rem;
        color: #1b1b1b;
        font-weight: 800;
    }

    .setup-note {
        font-size: 0.85rem;
        color: #6d6d6d;
    }

    .setup-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .setup-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .setup-field label {
        font-weight: 700;
        font-size: 0.9rem;
        color: #3c3c3c;
    }

    .setup-input,
    .setup-textarea,
    .setup-select {
        border-radius: 12px;
        border: 1px solid rgba(201, 154, 17, 0.18);
        padding: 0.72rem 0.9rem;
        background: #fffef8;
        font-size: 0.95rem;
    }

    .setup-input:focus,
    .setup-textarea:focus,
    .setup-select:focus {
        outline: none;
        border-color: rgba(244, 196, 48, 0.95);
        box-shadow: 0 0 0 0.18rem rgba(244, 196, 48, 0.13);
        background: #ffffff;
    }

    .setup-textarea {
        min-height: 110px;
        resize: vertical;
    }

    .setup-product-card {
        border: 1px solid rgba(201, 154, 17, 0.16);
        border-radius: 16px;
        background: #ffffff;
        padding: 14px;
        margin-bottom: 12px;
    }

    .setup-product-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .setup-product-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #1b1b1b;
    }

    .setup-product-actions {
        display: flex;
        gap: 8px;
    }

    .setup-btn {
        border: 1px solid rgba(201, 154, 17, 0.25);
        border-radius: 999px;
        background: rgba(244, 196, 48, 0.12);
        color: #1b1b1b;
        font-weight: 700;
        padding: 0.45rem 0.85rem;
        font-size: 0.85rem;
        cursor: pointer;
    }

    .setup-btn:hover {
        background: rgba(244, 196, 48, 0.22);
    }

    .setup-btn-danger {
        border-color: rgba(211, 47, 47, 0.26);
        background: rgba(211, 47, 47, 0.1);
        color: #a32121;
    }

    .setup-btn-danger:hover {
        background: rgba(211, 47, 47, 0.16);
    }

    .setup-file-hint {
        font-size: 0.8rem;
        color: #777;
        margin-top: 4px;
    }

    .setup-submit-row {
        display: flex;
        justify-content: flex-end;
        margin-top: 2px;
    }

    .setup-submit-btn {
        border: none;
        border-radius: 999px;
        background: linear-gradient(135deg, #f4c430 0%, #e7b20f 100%);
        color: #111;
        font-weight: 800;
        padding: 0.8rem 1.35rem;
    }

    .setup-submit-btn:hover {
        background: linear-gradient(135deg, #ffd451 0%, #f1c228 100%);
    }

    @media (max-width: 991.98px) {
        .setup-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<main class="setup-page px-3 px-md-4 py-4 flex-grow-1">
    <section class="setup-hero">
        <p>Setup thông tin shop</p>
        <h1>Điền thông tin để triển khai đơn hàng</h1>
        <span>Thông tin này phục vụ cho 3 bảng: setup_info, shop_product, product_files. Mỗi sản phẩm có thể tải lên nhiều ảnh.</span>
    </section>

    <?php if (!empty($msg)): ?>
        <div class="mb-3"><?php echo showMsg($msg, $msgType); ?></div>
    <?php endif; ?>

    <form class="setup-form" method="post" action="" enctype="multipart/form-data">
        <section class="setup-section">
            <div class="setup-section-head">
                <h2>1. Thông tin setup chung</h2>
                <span class="setup-note">Dữ liệu dự kiến lưu vào setup_info</span>
            </div>

            <div class="setup-grid">
                <div class="setup-field">
                    <label for="order_id">Đơn hàng cần setup</label>
                    <select class="setup-select" id="order_id" name="order_id" required>
                        <option value="">Chọn đơn hàng</option>
                        <?php foreach ($orderList as $ord): ?>
                            <option value="<?php echo htmlspecialchars($ord['id'] ?? ''); ?>" <?php echo ($pickedOrderId !== '' && $pickedOrderId === (string) ($ord['id'] ?? '')) ? 'selected' : ''; ?>>
                                #<?php echo htmlspecialchars($ord['id'] ?? ''); ?> - <?php echo number_format((float) ($ord['total_price'] ?? 0), 0, ',', '.'); ?>đ
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="setup-field">
                    <label for="primary_color">Màu chủ đạo</label>
                    <input class="setup-input" id="primary_color" name="primary_color" placeholder="Ví dụ: #f4c430 hoặc vàng đất">
                </div>

                <div class="setup-field" style="grid-column: 1 / -1;">
                    <label for="notes">Ghi chú setup</label>
                    <textarea class="setup-textarea" id="notes" name="notes" placeholder="Mô tả yêu cầu setup tổng quát cho shop..."></textarea>
                </div>
            </div>
        </section>

        <section class="setup-section">
            <div class="setup-section-head">
                <h2>2. Danh sách sản phẩm</h2>
                <div class="setup-product-actions">
                    <button type="button" class="setup-btn" id="addProductBtn"><i class="bi bi-plus-circle me-1"></i>Thêm sản phẩm</button>
                </div>
            </div>
            <span class="setup-note">Mỗi block tương ứng 1 dòng trong shop_product; ảnh của block đó sẽ lưu product_files.</span>

            <div id="productsContainer">
                <div class="setup-product-card" data-product-index="0">
                    <div class="setup-product-head">
                        <h3 class="setup-product-title">Sản phẩm #1</h3>
                        <div class="setup-product-actions">
                            <button type="button" class="setup-btn setup-btn-danger remove-product-btn" disabled>Xóa</button>
                        </div>
                    </div>

                    <div class="setup-grid">
                        <div class="setup-field">
                            <label>Tên sản phẩm</label>
                            <input class="setup-input" name="products[0][name]" placeholder="Nhập tên sản phẩm" required>
                        </div>
                        <div class="setup-field">
                            <label>Main keyword</label>
                            <input class="setup-input" name="products[0][main_keyword]" placeholder="Từ khóa chính" required>
                        </div>
                        <div class="setup-field">
                            <label>Brand</label>
                            <input class="setup-input" name="products[0][brand]" placeholder="Thương hiệu">
                        </div>
                        <div class="setup-field">
                            <label>Model</label>
                            <input class="setup-input" name="products[0][model]" placeholder="Model sản phẩm">
                        </div>
                        <div class="setup-field">
                            <label>Giá bán</label>
                            <input type="number" min="0" step="0.01" class="setup-input" name="products[0][price]" placeholder="Giá bán" required>
                        </div>
                        <div class="setup-field">
                            <label>Tồn kho</label>
                            <input type="number" min="0" step="1" class="setup-input" name="products[0][stock]" placeholder="Số lượng tồn" required>
                        </div>
                        <div class="setup-field" style="grid-column: 1 / -1;">
                            <label>Mô tả sản phẩm</label>
                            <textarea class="setup-textarea" name="products[0][description]" placeholder="Mô tả ngắn cho sản phẩm"></textarea>
                        </div>
                        <div class="setup-field" style="grid-column: 1 / -1;">
                            <label>Ảnh sản phẩm (nhiều ảnh)</label>
                            <input type="file" class="setup-input" name="products[0][files][]" accept="image/*" multiple>
                            <span class="setup-file-hint">Có thể chọn nhiều ảnh cho cùng 1 sản phẩm.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="setup-submit-row">
            <button type="submit" class="setup-submit-btn">Lưu thông tin setup</button>
        </div>
    </form>
</main>

<script>
    (function () {
        const productsContainer = document.getElementById('productsContainer');
        const addProductBtn = document.getElementById('addProductBtn');

        function createProductCard(index) {
            const card = document.createElement('div');
            card.className = 'setup-product-card';
            card.setAttribute('data-product-index', String(index));

            card.innerHTML = `
                <div class="setup-product-head">
                    <h3 class="setup-product-title">Sản phẩm #${index + 1}</h3>
                    <div class="setup-product-actions">
                        <button type="button" class="setup-btn setup-btn-danger remove-product-btn">Xóa</button>
                    </div>
                </div>
                <div class="setup-grid">
                    <div class="setup-field">
                        <label>Tên sản phẩm</label>
                        <input class="setup-input" name="products[${index}][name]" placeholder="Nhập tên sản phẩm" required>
                    </div>
                    <div class="setup-field">
                        <label>Main keyword</label>
                        <input class="setup-input" name="products[${index}][main_keyword]" placeholder="Từ khóa chính" required>
                    </div>
                    <div class="setup-field">
                        <label>Brand</label>
                        <input class="setup-input" name="products[${index}][brand]" placeholder="Thương hiệu">
                    </div>
                    <div class="setup-field">
                        <label>Model</label>
                        <input class="setup-input" name="products[${index}][model]" placeholder="Model sản phẩm">
                    </div>
                    <div class="setup-field">
                        <label>Giá bán</label>
                        <input type="number" min="0" step="0.01" class="setup-input" name="products[${index}][price]" placeholder="Giá bán" required>
                    </div>
                    <div class="setup-field">
                        <label>Tồn kho</label>
                        <input type="number" min="0" step="1" class="setup-input" name="products[${index}][stock]" placeholder="Số lượng tồn" required>
                    </div>
                    <div class="setup-field" style="grid-column: 1 / -1;">
                        <label>Mô tả sản phẩm</label>
                        <textarea class="setup-textarea" name="products[${index}][description]" placeholder="Mô tả ngắn cho sản phẩm"></textarea>
                    </div>
                    <div class="setup-field" style="grid-column: 1 / -1;">
                        <label>Ảnh sản phẩm (nhiều ảnh)</label>
                        <input type="file" class="setup-input" name="products[${index}][files][]" accept="image/*" multiple>
                        <span class="setup-file-hint">Có thể chọn nhiều ảnh cho cùng 1 sản phẩm.</span>
                    </div>
                </div>
            `;

            return card;
        }

        function refreshProductHeaders() {
            const cards = productsContainer.querySelectorAll('.setup-product-card');
            cards.forEach((card, idx) => {
                card.setAttribute('data-product-index', String(idx));
                const title = card.querySelector('.setup-product-title');
                if (title) {
                    title.textContent = `Sản phẩm #${idx + 1}`;
                }

                card.querySelectorAll('input, textarea, select').forEach((field) => {
                    const currentName = field.getAttribute('name');
                    if (!currentName) {
                        return;
                    }
                    field.setAttribute('name', currentName.replace(/products\[\d+\]/, `products[${idx}]`));
                });

                const removeBtn = card.querySelector('.remove-product-btn');
                if (removeBtn) {
                    removeBtn.disabled = cards.length === 1;
                }
            });
        }

        addProductBtn.addEventListener('click', function () {
            const nextIndex = productsContainer.querySelectorAll('.setup-product-card').length;
            const card = createProductCard(nextIndex);
            productsContainer.appendChild(card);
            refreshProductHeaders();
        });

        productsContainer.addEventListener('click', function (event) {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }
            if (!target.classList.contains('remove-product-btn')) {
                return;
            }
            const cards = productsContainer.querySelectorAll('.setup-product-card');
            if (cards.length <= 1) {
                return;
            }
            const card = target.closest('.setup-product-card');
            if (card) {
                card.remove();
                refreshProductHeaders();
            }
        });

        refreshProductHeaders();
    })();
</script>



<?php 
layout('footer');
?>