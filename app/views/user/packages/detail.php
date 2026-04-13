<?php
$data = [
    'title' => 'Chi tiết gói dịch vụ',
];
if (isset($user)) {
    $data['user'] = $user;
}
if (isset($cartItemCount)) {
    $data['cartItemCount'] = $cartItemCount;
}
layout('sidebar', $data);
layout('header', $data);

$pkg = $package ?? [];
$packageAddons = $addons ?? [];
?>
<style>
    .detail-page {
        background:
            radial-gradient(circle at top left, rgba(244, 196, 48, 0.12), transparent 28%),
            linear-gradient(180deg, #fffdf8 0%, #fff8e8 100%);
        border-radius: 24px;
    }

    .detail-hero {
        display: grid;
        grid-template-columns: 0.9fr 1.1fr;
        gap: 28px;
        align-items: start;
        padding: 24px;
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff 1%, #fff9ea 100%);
        border: 1px solid rgba(201, 154, 17, 0.16);
        box-shadow: 0 16px 40px rgba(31, 24, 9, 0.1);
    }

    .detail-hero-img {
        padding: 12px;
        border-radius: 20px;
        background: rgba(244, 196, 48, 0.08);
        border: 1px solid rgba(201, 154, 17, 0.12);
        overflow: hidden;
        box-shadow: inset 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .detail-hero-img img {
        width: 100%;
        height: auto;
        border-radius: 16px;
        display: block;
        background: #f5f1e6;
    }

    .detail-hero-copy h1 {
        font-size: clamp(1.8rem, 3vw, 3rem);
        font-weight: 800;
        color: #1b1b1b;
        line-height: 1.1;
        margin-bottom: 14px;
    }

    .detail-short-desc {
        font-size: 1.08rem;
        color: #656565;
        line-height: 1.65;
        margin-bottom: 16px;
    }

    .detail-price-row {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 20px;
    }

    .detail-price {
        font-size: 2.4rem;
        font-weight: 800;
        color: #9b7500;
        line-height: 1;
    }

    .detail-price-unit {
        font-size: 0.95rem;
        color: #6f6f6f;
    }

    .detail-cta-group {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .btn-detail-add,
    .btn-detail-consult {
        border-radius: 999px;
        font-weight: 700;
        padding: 0.75rem 1.3rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-detail-add {
        background: linear-gradient(135deg, #f4c430 0%, #e9b913 100%);
        color: #111111;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    .btn-detail-add:hover {
        background: linear-gradient(135deg, #ffd451 0%, #f4c430 100%);
        transform: translateY(-1px);
    }

    .btn-detail-consult {
        background: rgba(17, 17, 17, 0.92);
        color: #ffd451;
        border: 1px solid rgba(244, 196, 48, 0.3);
    }

    .btn-detail-consult:hover {
        background: #111111;
        color: #ffffff;
        transform: translateY(-1px);
    }

    .detail-meta {
        display: flex;
        gap: 16px;
        padding-top: 16px;
        border-top: 1px solid rgba(201, 154, 17, 0.12);
        margin-top: 16px;
        font-size: 0.92rem;
        color: #6f6f6f;
    }

    .detail-meta-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .detail-meta-label {
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-size: 0.78rem;
        font-weight: 700;
        color: #9b7500;
    }

    .detail-content-wrap {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 28px;
        margin-top: 28px;
    }

    .detail-description-col {
        display: flex;
        flex-direction: column;
    }

    .detail-addons-col {
        display: flex;
        flex-direction: column;
    }

    .detail-section-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #1b1b1b;
        margin-bottom: 16px;
    }

    .detail-description {
        padding: 20px;
        border-radius: 20px;
        background: linear-gradient(180deg, #ffffff 0%, #fff8ee 100%);
        border: 1px solid rgba(201, 154, 17, 0.14);
        color: #3f3f3f;
        line-height: 1.8;
        box-shadow: 0 12px 28px rgba(31, 24, 9, 0.08);
        flex: 1;
    }

    .detail-addons-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 28px rgba(31, 24, 9, 0.08);
        background: #ffffff;
        border: 1px solid rgba(201, 154, 17, 0.14);
    }

    .detail-addons-table thead {
        background: linear-gradient(135deg, rgba(17, 17, 17, 0.94) 0%, rgba(36, 28, 12, 0.92) 100%);
        color: #fff8de;
    }

    .detail-addons-table th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 700;
        font-size: 0.92rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .detail-addons-table tbody tr {
        border-top: 1px solid rgba(201, 154, 17, 0.12);
        transition: background 0.2s ease;
    }

    .detail-addons-table tbody tr:hover {
        background: rgba(244, 196, 48, 0.08);
    }

    .detail-addons-table td {
        padding: 14px 16px;
        color: #3f3f3f;
        font-size: 0.95rem;
    }

    .detail-addon-type-badge {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        background: rgba(17, 17, 17, 0.88);
        color: #ffd451;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .detail-addon-price-col {
        color: #9b7500;
        font-weight: 700;
    }

    .detail-addon-qty-col {
        background: rgba(244, 196, 48, 0.12);
        border: 1px solid rgba(201, 154, 17, 0.3);
        border-radius: 8px;
        padding: 0.3rem 0.8rem;
        display: inline-block;
        color: #9b7500;
        font-weight: 700;
        text-align: center;
        min-width: 50px;
    }

    @media (max-width: 1199.98px) {
        .detail-content-wrap {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    @media (max-width: 991.98px) {
        .detail-hero {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    @media (max-width: 575.98px) {
        .detail-page {
            border-radius: 18px;
        }

        .detail-hero {
            padding: 16px;
            border-radius: 18px;
        }

        .detail-hero-copy h1 {
            font-size: 1.4rem;
        }

        .detail-price {
            font-size: 2rem;
        }

        .detail-addons-table th,
        .detail-addons-table td {
            padding: 10px 12px;
            font-size: 0.82rem;
        }
    }
</style>

<main class="detail-page px-3 px-md-4 py-4 flex-grow-1">
    <?php if (!empty($pkg)): ?>
        <!-- Section 1: Hero & Main Info -->
        <section class="detail-hero">
            <div class="detail-hero-img">
                <img src="<?php echo !empty($pkg['avatar']) ? _HOST_URL_PUBLIC . '/' . $pkg['avatar'] : _HOST_URL_PUBLIC . '/img/service_picture.jpg'; ?>" alt="<?php echo htmlspecialchars($pkg['name'] ?? ''); ?>">
            </div>
            <div class="detail-hero-copy">
                <h1><?php echo htmlspecialchars($pkg['name'] ?? 'Gói dịch vụ'); ?></h1>
                <p class="detail-short-desc"><?php echo htmlspecialchars($pkg['short_description'] ?? ''); ?></p>

                <div class="detail-price-row">
                    <span class="detail-price"><?php echo (!empty($pkg['price'])) ? number_format($pkg['price']) : '0'; ?></span>
                    <span class="detail-price-unit">đ<?php if (!empty($pkg['unit'])): ?> / <?php echo $pkg['unit'] === 'package' ? 'gói' : ($pkg['unit'] === 'product' ? 'sản phẩm' : $pkg['unit']); ?><?php endif; ?></span>
                </div>

                <div class="detail-cta-group">
                    <a href="<?php echo _HOST_URL ?>/cart/add?package_id=<?php echo htmlspecialchars($pkg['id']); ?>" class="btn btn-detail-add">Thêm vào giỏ hàng</a>
                    <a href="<?php echo _HOST_URL ?>/contact" class="btn btn-detail-consult">Tư vấn thêm</a>
                </div>

                <div class="detail-meta">
                    <div class="detail-meta-item">
                        <span class="detail-meta-label">Loại</span>
                        <span>Gói combo</span>
                    </div>
                    <div class="detail-meta-item">
                        <span class="detail-meta-label">Hỗ trợ</span>
                        <span>24/7</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Description & Addons (Side by Side) -->
        <div class="detail-content-wrap">
            <div class="detail-description-col">
                <h2 class="detail-section-title">Chi tiết dịch vụ</h2>
                <div class="detail-description">
                    <?php echo nl2br(htmlspecialchars($pkg['long_description'] ?? 'Không có mô tả chi tiết.')); ?>
                </div>
            </div>

            <?php if (!empty($packageAddons)): ?>
                <div class="detail-addons-col">
                    <h2 class="detail-section-title">Bao gồm</h2>
                    <table class="detail-addons-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Dịch vụ</th>
                                <th>Loại</th>
                                <th>SL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter = 1; ?>
                            <?php foreach ($packageAddons as $addon): ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo htmlspecialchars($addon['addon_name'] ?? $addon['name'] ?? ''); ?></td>
                                    <td><span class="detail-addon-type-badge"><?php echo htmlspecialchars($addon['type'] ?? 'Add-on'); ?></span></td>
                                    <td><span class="detail-addon-qty-col"><?php echo isset($addon['quantity']) ? htmlspecialchars($addon['quantity']) : '1'; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 40px 20px; color: #999;">
            <h2>Gói dịch vụ không tồn tại</h2>
            <p><a href="<?php echo _HOST_URL ?>/package" style="color: #9b7500; text-decoration: none; font-weight: 700;">Quay lại danh sách gói</a></p>
        </div>
    <?php endif; ?>
</main>
<?php
layout('footer');
?>