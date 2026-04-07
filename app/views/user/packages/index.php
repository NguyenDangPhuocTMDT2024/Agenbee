<?php
$data = [
    'title' => 'Gói dịch vụ',
];
if (isset($user)) {
    $data['user'] = $user;
}

$pricingPlans = [
    [
        'name' => 'Basic',
        'subtitle' => 'Best for personal use.',
        'price' => 'Free',
        'isFeatured' => false,
        'button' => 'Get Started',
        'features' => [
            'Access to core features',
            'Limited support',
            'Basic reporting tools',
            'Email support',
            'Communication tools',
            'Reporting and analytics',
        ],
    ],
    [
        'name' => 'Enterprise',
        'subtitle' => 'For large teams & corporations.',
        'price' => '$80',
        'isFeatured' => true,
        'button' => 'Get started',
        'features' => [
            'All features in Basic Plan',
            'Enhanced reporting and analytics',
            'Priority customer support',
            'Integration with key platforms',
            'Monthly updates',
            'Reporting and analytics',
        ],
    ],
    [
        'name' => 'Business',
        'subtitle' => 'Best for business owners.',
        'price' => '$120',
        'isFeatured' => false,
        'button' => 'Get Started',
        'features' => [
            'All features in Enterprise Plan',
            'Custom integrations',
            'Dedicated account manager',
            'Advanced analytics and insights',
            'Communication tools',
            'Reporting and analytics',
        ],
    ],
];

$addonBrands = ['Apple', 'Samsung', 'Honor', 'Lenovo', 'Xiaomi', 'Huawei'];
$addonTrends = ['Khoang 10 - 11 inch', 'Android', 'Tren 7000 mAh', '5G', 'Apple M5'];
$addonPriceRanges = ['Duoi 3 trieu', 'Tu 3 - 8 trieu', 'Tu 8 - 15 trieu', 'Tu 15 - 25 trieu', 'Tren 25 trieu'];

$addonProducts = [
    [
        'name' => 'iPad A16 WiFi 128GB',
        'oldPrice' => '9.790.000d',
        'price' => '9.490.000d',
        'discount' => 'Giam 300.000d',
        'spec1' => 'Chip A16 sieu nhanh',
        'spec2' => 'Man hinh Liquid Retina',
        'badge' => 'Tra gop 0%',
    ],
    [
        'name' => 'Xiaomi Redmi Pad SE 8.7 WiFi 4GB 128GB',
        'oldPrice' => '3.920.000d',
        'price' => '2.590.000d',
        'discount' => 'Giam 1.330.000d',
        'spec1' => '8 nhan manh me',
        'spec2' => 'Tan so quet 90 Hz',
        'badge' => '18 thang bao hanh',
    ],
    [
        'name' => 'Honor Pad X7 WiFi 4GB 128GB',
        'oldPrice' => '3.790.000d',
        'price' => '3.490.000d',
        'discount' => 'Giam 300.000d',
        'spec1' => '7020 mAh',
        'spec2' => 'Snapdragon 680',
        'badge' => 'Doc quyen',
    ],
    [
        'name' => 'Samsung Galaxy Tab A9 WiFi 8GB 128GB',
        'oldPrice' => '4.900.000d',
        'price' => '3.190.000d',
        'discount' => 'Giam 1.710.000d',
        'spec1' => 'Nho gon 8.7 inch',
        'spec2' => 'MediaTek Helio G99',
        'badge' => 'Doc quyen',
    ],
];

layout('sidebar', $data);
layout('header', $data);
?>
<main class="px-3 px-md-4 py-4 flex-grow-1 bg-white">
    <section class="container-fluid px-0">
        <div class="text-center mb-5">
            <h1 class="fs-2  fw-semibold mt-3 mb-0">Choose the Perfect Package for You</h1>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($pricingPlans as $plan): ?>
                <div class="col-4 col-md-6 col-lg-4">
                    <article class="card h-100 shadow-sm border-1 <?php echo $plan['isFeatured'] ? 'bg-dark text-white border-primary' : 'bg-white'; ?>">
                        <div class="card-body p-4 p-lg-5 d-flex flex-column">
                            <header>
                                <h2 class="h1 fw-semibold mb-1"><?php echo htmlspecialchars($plan['name']); ?></h2>
                                <p class="mb-0 <?php echo $plan['isFeatured'] ? 'text-light-emphasis' : 'text-body-secondary'; ?>"><?php echo htmlspecialchars($plan['subtitle']); ?></p>
                            </header>

                            <div class="d-flex align-items-baseline gap-2 mt-4 mb-4">
                                <span class="display-4 fw-bold mb-0"><?php echo htmlspecialchars($plan['price']); ?></span>
                                <?php if ($plan['price'] !== 'Free'): ?>
                                    <span class="<?php echo $plan['isFeatured'] ? 'text-light-emphasis' : 'text-body-secondary'; ?>"></span>
                                <?php endif; ?>
                            </div>

                            <button type="button" class="btn <?php echo $plan['isFeatured'] ? 'btn-primary' : 'btn-dark'; ?> w-100 rounded-pill py-2"><?php echo htmlspecialchars($plan['button']); ?></button>

                            <hr class="my-4 <?php echo $plan['isFeatured'] ? 'border-light-subtle' : ''; ?>">

                            <h3 class="h2 fw-semibold mb-3">What you will get</h3>
                            <ul class="list-unstyled mb-0 mt-1">
                                <?php foreach ($plan['features'] as $feature): ?>
                                    <li class="d-flex align-items-start gap-2 mb-2 <?php echo $plan['isFeatured'] ? 'text-light' : 'text-body-secondary'; ?>">
                                        <i class="bi bi-check-circle"></i>
                                        <span><?php echo htmlspecialchars($feature); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="mt-5">
        <div class="row g-3 g-xl-4">
            <div class="col-12 col-xl-3">
                <div class="card border-0 bg-light-subtle shadow-sm sticky-top" style="top: 86px;">
                    <div class="card-body p-3 p-lg-4">
                        <h2 class="h3 fw-bold mb-4"><i class="bi bi-funnel-fill me-2"></i>Bo loc tim kiem</h2>

                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="h5 mb-0">Hang san xuat</h3>
                                <i class="bi bi-chevron-up"></i>
                            </div>
                            <div class="row g-2">
                                <?php foreach ($addonBrands as $brand): ?>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline-secondary w-100"><?php echo htmlspecialchars($brand); ?></button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <a href="#" class="d-inline-block mt-3 text-decoration-none">Xem them</a>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="h5 mb-0">Muc gia</h3>
                                <i class="bi bi-chevron-up"></i>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" checked id="allPrice">
                                <label class="form-check-label fw-semibold" for="allPrice">Tat ca</label>
                            </div>

                            <?php foreach ($addonPriceRanges as $idx => $priceRange): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="price<?php echo $idx; ?>">
                                    <label class="form-check-label" for="price<?php echo $idx; ?>"><?php echo htmlspecialchars($priceRange); ?></label>
                                </div>
                            <?php endforeach; ?>

                            <p class="mb-2 fw-semibold">Hoac nhap khoang gia phu hop voi ban:</p>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Tu</span>
                                <input type="text" class="form-control" value="2.190.000d">
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Den</span>
                                <input type="text" class="form-control" value="76.390.000d">
                            </div>
                            <input type="range" class="form-range" min="0" max="100" value="45">
                        </div>

                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="h5 mb-0">Man hinh</h3>
                                <i class="bi bi-chevron-up"></i>
                            </div>
                            <button type="button" class="btn btn-outline-secondary w-100 text-start">may-tinh-bang/ipad-a16-wifi</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-9">
                <div class="card border-0 bg-light-subtle shadow-sm mb-3">
                    <div class="card-body py-3 px-3 px-md-4 d-flex flex-wrap align-items-center gap-2">
                        <span class="text-secondary me-1">Xu huong:</span>
                        <?php foreach ($addonTrends as $trend): ?>
                            <a href="#" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-graph-up-arrow me-1"></i><?php echo htmlspecialchars($trend); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3 px-1">
                    <p class="mb-0 fs-5">Tim thay <strong><?php echo count($addonProducts) * 22 + 1; ?></strong> ket qua</p>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-danger">Noi bat</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Gia tang dan</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Gia giam dan</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Tra gop 0%</button>
                    </div>
                </div>

                <div class="row g-3 g-md-4">
                    <?php foreach ($addonProducts as $item): ?>
                        <div class="col-12 col-sm-6 col-lg-4 col-xxl-3">
                            <article class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge rounded-pill text-bg-light"><?php echo htmlspecialchars($item['badge']); ?></span>
                                        <button type="button" class="btn btn-sm btn-light border"><i class="bi bi-copy"></i></button>
                                    </div>

                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-3" style="height: 160px;">
                                        <i class="bi bi-tablet-landscape fs-1 text-secondary"></i>
                                    </div>

                                    <div class="small text-secondary mb-1">
                                        <i class="bi bi-cpu me-1"></i><?php echo htmlspecialchars($item['spec1']); ?>
                                    </div>
                                    <div class="small text-secondary mb-2">
                                        <i class="bi bi-display me-1"></i><?php echo htmlspecialchars($item['spec2']); ?>
                                    </div>

                                    <div class="mt-2 mb-1 text-decoration-line-through text-secondary"><?php echo htmlspecialchars($item['oldPrice']); ?></div>
                                    <div class="fs-3 fw-bold mb-1"><?php echo htmlspecialchars($item['price']); ?></div>
                                    <div class="text-success fw-semibold mb-2"><?php echo htmlspecialchars($item['discount']); ?></div>
                                    <div class="text-body-secondary mb-2">Con 03 ngay 01:04:14</div>

                                    <h3 class="h5 mb-2"><?php echo htmlspecialchars($item['name']); ?></h3>

                                    <div class="d-flex gap-2 mb-2">
                                        <span class="badge text-bg-secondary-subtle border">64 GB</span>
                                        <span class="badge text-bg-secondary-subtle border border-danger text-danger">128 GB</span>
                                    </div>

                                    <p class="small text-body-secondary mb-2">Giam 800.000d khi thanh toan qua the Visa SCB.</p>
                                    <a href="#" class="text-decoration-none fw-semibold mt-auto"><i class="bi bi-plus-circle me-1"></i>Them vao so sanh</a>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
layout('footer');
?>