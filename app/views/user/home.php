<?php
$data = [
    'title' => 'Trang chủ',
];
if (isset($user)) {
    $data['user'] = $user;
}
if (isset($cartItemCount)) {
    $data['cartItemCount'] = $cartItemCount;
}
layout('sidebar', $data);
layout('header', $data);

$featuredCombos = array_slice($combos ?? [], 0, 3);
?>
<main class="home-page flex-grow-1 px-3 px-md-4 py-4">
    <section class="home-hero">
        <div class="home-hero-copy">
            <p class="home-kicker mb-2">Agenbee service platform</p>
            <h1 class="home-title mb-3">Giải pháp setup shop & vận hành TMĐT đơn giản, hiệu quả, dễ scale.</h1>
            <p class="home-subtitle mb-4">Chọn gói phù hợp với giai đoạn kinh doanh. Agenbee giúp bạn thiết lập shop, tối ưu sản phẩm và hỗ trợ vận hành thực tế từ A–Z.</p>
            <div class="home-cta-group">
                <a href="<?php echo _HOST_URL ?>/package" class="btn btn-home-primary">Khám phá gói</a>
                <a href="<?php echo _HOST_URL ?>/contact" class="btn btn-home-secondary">Liên hệ tư vấn</a>
            </div>
            <div class="home-metrics">
                <div class="home-metric">
                    <strong><?php echo count($combos ?? []); ?>+ gói dịch vụ</strong>
                    <span>Linh hoạt theo từng giai đoạn</span>
                </div>
                <div class="home-metric">
                    <strong>24/7 hỗ trợ</strong>
                    <span>Luôn có người đồng hành</span>
                </div>
                <div class="home-metric">
                    <strong>Setup nhanh</strong>
                    <span>Đốt cháy giai đoạn khởi đầu</span>
                </div>
            </div>
        </div>
        <div class="home-hero-visual">
            <div class="home-cover-card">
                <img src="<?php echo _HOST_URL_PUBLIC ?>/img/cover agenbee.png" alt="Agenbee cover" class="home-cover-image">
            </div>
        </div>
    </section>

    <section class="home-features mt-4">
        <div class="home-section-head mb-3">
            <p class="home-section-label mb-1">Điểm mạnh</p>
            <h2 class="home-section-title mb-0">Những gì bạn nhận được</h2>
        </div>
        <div class="row g-3 g-lg-4">
            <div class="col-12 col-md-4">
                <article class="home-feature-card">
                    <div class="home-feature-icon"><i class="bi bi-grid-1x2-fill"></i></div>
                    <h3 class="mb-2">Quản lý gói rõ ràng</h3>
                    <p class="mb-0">Mỗi gói được cấu trúc từ các add-on cụ thể, hiển thị rõ nội dung, giá và phạm vi để bạn dễ lựa chọn và kiểm soát.</p>
                </article>
            </div>
            <div class="col-12 col-md-4">
                <article class="home-feature-card">
                    <div class="home-feature-icon"><i class="bi bi-speedometer2"></i></div>
                    <h3 class="mb-2">Tập trung vào vận hành thực tế</h3>
                    <p class="mb-0">Không chỉ setup, Agenbee giúp bạn tối ưu sản phẩm, nội dung và quy trình để shop có thể bán hàng ngay.</p>
                </article>
            </div>
            <div class="col-12 col-md-4">
                <article class="home-feature-card">
                    <div class="home-feature-icon"><i class="bi bi-palette2"></i></div>
                    <h3 class="mb-2">Đồng bộ hình ảnh & nội dung</h3>
                    <p class="mb-0">Từ banner, poster đến mô tả sản phẩm – mọi thứ được thiết kế nhất quán để tăng độ tin cậy và chuyển đổi.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="home-packages mt-4 mt-lg-5">
        <div class="home-section-head mb-3 d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div>
                <p class="home-section-label mb-1">Gợi ý nhanh</p>
                <h2 class="home-section-title mb-0">Một số gói nổi bật</h2>
            </div>
        </div>
        <div class="row g-3 g-lg-4 justify-content-center">
            <?php foreach ($featuredCombos as $combo): ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <article class="home-package-card h-100">
                        <div class="home-package-badge">Popular</div>
                        <div class="home-package-body">
                            <h3 class="home-package-title mb-2"><?php echo htmlspecialchars($combo['name']); ?></h3>
                            <p class="home-package-desc mb-3"><?php echo htmlspecialchars($combo['short_description']); ?></p>
                            <div class="home-package-price mb-4">
                                <span><?php echo (!empty($combo['price'])) ? number_format($combo['price']) : '0'; ?></span>
                                <small>đ / gói</small>
                            </div>
                            <div class="d-flex gap-2 flex-wrap justify-content-center">
                                <a href="<?php echo _HOST_URL ?>/package/detail?id=<?php echo htmlspecialchars($combo['id']); ?>" class="btn btn-home-primary">Chi tiết</a>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-4 text-center">
            <a href="<?php echo _HOST_URL ?>/package" class="home-section-link">Xem tất cả</a>
        </div>
    </section>

    <section class="home-callout mt-4 mt-lg-5">
        <div>
            <p class="home-section-label mb-1">Bắt đầu ngay</p>
            <h2 class="mb-2">Bạn chọn gói – phần còn lại để Agenbee lo.</h2>
            <p class="mb-0">Từ setup đến vận hành, chúng tôi đồng hành để giúp bạn bán hàng hiệu quả hơn.</p>
        </div>
        <a href="<?php echo _HOST_URL ?>/contact" class="btn btn-home-primary">Liên hệ tư vấn</a>
    </section>
</main>
<?php
layout('footer');
?>