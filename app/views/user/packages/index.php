<?php
$data = [
    'title' => 'Gói dịch vụ',
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
?>
<style>
    .package-page {
        background:
            radial-gradient(circle at top left, rgba(244, 196, 48, 0.12), transparent 28%),
            linear-gradient(180deg, #fffdf8 0%, #fff8e8 100%);
        border-radius: 24px;
    }

    .package-title {
        color: #1b1b1b;
        font-weight: 800;
    }

    @media (min-width: 992px) {
        .combo-card-header {
            min-height: 215px;
        }

        .combo-card-title {
            min-height: 68px;
            line-height: 1.15;
        }

        .combo-card-summary {
            min-height: 126px;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    }

    .package-combo-card {
        border: 1px solid rgba(201, 154, 17, 0.18);
        border-radius: 22px;
        background: linear-gradient(180deg, #ffffff 0%, #fff8ea 100%);
        box-shadow: 0 14px 34px rgba(31, 24, 9, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .package-combo-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(31, 24, 9, 0.12);
    }

    .package-combo-card .card-body {
        padding: 1.5rem;
    }

    .combo-price {
        color: #9b7500;
    }

    .btn-combo-choose {
        background: linear-gradient(135deg, #f4c430 0%, #e9b913 100%);
        border: 1px solid rgba(0, 0, 0, 0.08);
        color: #111111;
        font-weight: 700;
        border-radius: 999px;
        padding: 0.55rem 1rem;
    }

    .btn-combo-choose:hover {
        background: linear-gradient(135deg, #ffd451 0%, #f4c430 100%);
        color: #111111;
    }

    .combo-desc {
        color: #656565;
        line-height: 1.6;
    }

    .addon-card {
        height: 100%;
        border-radius: 20px;
        border: 1px solid rgba(201, 154, 17, 0.16);
        background: linear-gradient(180deg, #ffffff 0%, #fff8ea 100%);
        box-shadow: 0 12px 30px rgba(31, 24, 9, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }

    .addon-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 36px rgba(31, 24, 9, 0.12);
    }

    .addon-thumb {
        height: 180px;
        object-fit: cover;
    }

    .addon-desc {
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 4.35em;
    }

    .addon-type {
        position: absolute;
        top: 12px;
        right: 12px;
        border-radius: 999px;
        padding: 0.3rem 0.7rem;
        background: rgba(17, 17, 17, 0.92);
        color: #ffd451;
        font-weight: 700;
        z-index: 2;
    }

    .addon-title {
        color: #1b1b1b;
        font-weight: 700;
    }

    .addon-price {
        color: #9b7500;
    }

    .btn-addon {
        border-radius: 999px;
        border-color: rgba(201, 154, 17, 0.5);
        color: #9b7500;
        font-weight: 700;
    }

    .btn-addon:hover {
        background: #f4c430;
        border-color: #f4c430;
        color: #111111;
    }

    .addon-sort {
        border-radius: 999px;
        border-color: rgba(201, 154, 17, 0.4);
        padding-right: 2.2rem;
    }

    .filter-btn-active {
        background: linear-gradient(135deg, #f4c430 0%, #e9b913 100%);
        color: #111111;
        border-color: #f4c430;
        font-weight: 700;
    }

    .filter-btn-active:hover {
        background: linear-gradient(135deg, #ffd451 0%, #f4c430 100%);
        color: #111111;
        border-color: #f4c430;
    }

    .package-filter-btn {
        border-radius: 999px;
        border-color: rgba(201, 154, 17, 0.38);
        color: #6f6f6f;
    }

    .package-filter-btn:hover {
        color: #111111;
        border-color: rgba(201, 154, 17, 0.6);
        background: rgba(244, 196, 48, 0.13);
    }
</style>
<main class="package-page px-3 px-md-4 py-4 flex-grow-1">
    <section class="container-fluid px-0">
        <div class="text-center mb-5">
            <?php
            if (!empty($msg)) {
                echo showMsg($msg, $msgType);
            }
            ?>
            <h1 class="fs-2 fw-semibold mt-3 mb-0 package-title">Choose the Perfect Package for You</h1>
        </div>
        <div class="row g-4 justify-content-center">
            <?php foreach ($combos as $combo): ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <article class="card package-combo-card h-100 border-0">
                        <div class="card-body p-4 p-lg-5 d-flex flex-column">
                            <header class="combo-card-header">
                                <h2 class="h1 fw-semibold mb-1 combo-card-title"><?php echo htmlspecialchars($combo['name']); ?></h2>
                                <p class="mb-0 text-body-secondary combo-card-summary"><?php echo htmlspecialchars($combo['short_description']); ?></p>
                            </header>

                            <div class="d-flex align-items-baseline flex-wrap gap-2 mt-4 mb-4">
                                <span class="fs-3 fw-bold mb-0 text-break combo-price"><?php echo (!empty($combo['price'])) ? number_format($combo['price']) : '0' ?><sup class="fs-6">đ</sup></span>
                                <span class="text-body-secondary">/gói</span>
                            </div>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($combo['id']); ?>">
                                <a href="<?php echo _HOST_URL ?>/package/detail?id=<?php echo htmlspecialchars($combo['id']); ?>" type="button" class="btn btn-combo-choose">Chọn gói</a>
                            </form>

                            <hr class="my-4">
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="mt-5">
        <div class="row g-3 g-xl-4 justify-content-center">
            <div class="col-12 col-xl-9">
                <h3>Các dịch vụ bổ sung</h3>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3 px-1">
                    <div>
                        <a type="button" class="btn package-filter-btn rounded-pill <?php echo empty($choseType) ? 'filter-btn-active' : 'btn-outline-secondary'; ?>" href="<?php echo _HOST_URL ?>/package">
                            All
                        </a>
                        <?php foreach ($addonTypes as $type): ?>
                            <a type="button" class="btn package-filter-btn rounded-pill <?php echo ($type['type'] === $choseType) ? 'filter-btn-active' : 'btn-outline-secondary'; ?>" href="<?php echo _HOST_URL ?>/package?filter=<?php echo urlencode($type['type']) ?>">
                                <?php echo htmlspecialchars($type['type']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <select name="order" id="addon-sort" class="form-select w-auto addon-sort">
                        <option value="">Sắp xếp theo</option>
                        <option value="price_asc" <?php echo (isset($order) && $order === 'price_asc') ? 'selected' : ''; ?>>Giá thấp đến cao</option>
                        <option value="price_desc" <?php echo (isset($order) && $order === 'price_desc') ? 'selected' : ''; ?>>Giá cao đến thấp</option>
                    </select>
                </div>
                <div class="row g-3 g-md-4">
                    <?php foreach ($addons as $item): ?>
                        <div class="col-12 col-sm-6 col-lg-4 col-xxl-3">
                            <a href="<?php echo _HOST_URL ?>/package/detail?id=<?php echo htmlspecialchars($item['id']); ?>" class="text-decoration-none">
                                <article class="card addon-card border-0 shadow-sm position-relative">
                                    <span class="badge addon-type"><?php echo htmlspecialchars($item['type']); ?></span>
                                    <img src="<?php echo !empty($item['avatar']) ? _HOST_URL_PUBLIC . '/uploads/' . $item['avatar'] : _HOST_URL_PUBLIC . '/img/' . 'service_picture.jpg'; ?>" class="card-img-top addon-thumb" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <div class="card-body p-3 d-flex flex-column">
                                        <h3 class="h5 mb-2 addon-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                                        <div class="fs-4 fw-bold mb-2 addon-price">
                                            <?php echo (!empty($item['price'])) ? number_format($item['price']) : '0'; ?><sup class="fs-6">đ</sup>
                                            <span class="text-body-secondary fs-6 fw-normal">/
                                                <?php
                                                if (!empty($item['unit'])) {
                                                    if ($item['unit'] === 'package') {
                                                        echo 'gói';
                                                    } else if ($item['unit'] === 'product') {
                                                        echo 'sản phẩm';
                                                    } else {
                                                        echo 'cái';
                                                    }
                                                }
                                                ?></span>
                                        </div>
                                        <p class="text-body-secondary mb-0 addon-desc"><?php echo htmlspecialchars($item['short_description']); ?></p>
                                        <a href="<?php echo _HOST_URL ?>/cart/add?package_id=<?php echo $item['id']; ?>" type="button" class="mt-auto btn btn-outline-primary btn-addon">Thêm vào giỏ hàng</a>
                                    </div>
                                </article>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.getElementById('addon-sort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                if (this.value) {
                    const url = new URL(window.location);
                    url.searchParams.set('order', this.value);
                    window.location.href = url.toString();
                }
            });
        }
    });
</script>
<?php
layout('footer');
?>