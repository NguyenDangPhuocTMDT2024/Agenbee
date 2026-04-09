<?php
$data = [
    'title' => 'Gói dịch vụ',
];
if (isset($user)) {
    $data['user'] = $user;
}
if(isset($cartItemCount)) {
    $data['cartItemCount'] = $cartItemCount;
}
layout('sidebar', $data);
layout('header', $data);

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
?>
<style>
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

    .addon-card {
        height: 100%;
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

    .filter-btn-active {
        background-color: #ffc107;
        color: white;
        border-color: #ffc107;
    }

    .filter-btn-active:hover {
        background-color: #e0a800;
        color: white;
        border-color: #e0a800;
    }
</style>
<main class="px-3 px-md-4 py-4 flex-grow-1 bg-white">
    <section class="container-fluid px-0">
        <div class="text-center mb-5">
            <?php
            if (!empty($msg)) {
                echo showMsg($msg, $msgType);
            }
            ?>
            <h1 class="fs-2  fw-semibold mt-3 mb-0">Choose the Perfect Package for You</h1>
        </div>
        <div class="row g-4 justify-content-center">
            <?php foreach ($combos as $combo): ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <article class="card h-100 shadow-sm border-1 bg-white">
                        <div class="card-body p-4 p-lg-5 d-flex flex-column">
                            <header class="combo-card-header">
                                <h2 class="h1 fw-semibold mb-1 combo-card-title"><?php echo htmlspecialchars($combo['name']); ?></h2>
                                <p class="mb-0 text-body-secondary combo-card-summary"><?php echo htmlspecialchars($combo['short_description']); ?></p>
                            </header>

                            <div class="d-flex align-items-baseline flex-wrap gap-2 mt-4 mb-4">
                                <span class="fs-3 fw-bold mb-0 text-break"><?php echo (!empty($combo['price'])) ? number_format($combo['price']) : '0' ?><sup class="fs-6">đ</sup></span>
                                <span class="text-body-secondary">/gói</span>
                            </div>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($combo['id']); ?>">
                                <button type="submit" class="btn btn-primary rounded-pill">Chọn gói</button>
                            </form>

                            <hr class="my-4">

                            <h3 class="h2 fw-semibold mb-3">Mô tả</h3>
                            <div class="mb-0 mt-1">
                                <?php echo htmlspecialchars($combo['long_description']); ?>
                            </div>
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
                        <a type="button" class="btn rounded-0 rounded-pill <?php echo empty($choseType) ? 'filter-btn-active' : 'btn-outline-secondary'; ?>" href="<?php echo _HOST_URL ?>/package">
                            All
                        </a>
                        <?php foreach ($addonTypes as $type): ?>
                            <a type="button" class="btn rounded-0 rounded-pill <?php echo ($type['type'] === $choseType) ? 'filter-btn-active' : 'btn-outline-secondary'; ?>" href="<?php echo _HOST_URL ?>/package?filter=<?php echo urlencode($type['type']) ?>">
                                <?php echo htmlspecialchars($type['type']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <select name="order" id="addon-sort" class="form-select w-auto">
                        <option value="">Sắp xếp theo</option>
                        <option value="price_asc" <?php echo (isset($order) && $order === 'price_asc') ? 'selected' : ''; ?>>Giá thấp đến cao</option>
                        <option value="price_desc" <?php echo (isset($order) && $order === 'price_desc') ? 'selected' : ''; ?>>Giá cao đến thấp</option>
                    </select>
                </div>
                <div class="row g-3 g-md-4">
                    <?php foreach ($addons as $item): ?>
                        <div class="col-12 col-sm-6 col-lg-4 col-xxl-3">
                            <article class="card addon-card border-0 shadow-sm">
                                <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($item['type']); ?></span>
                                <img src="<?php echo !empty($item['avatar']) ? _HOST_URL_PUBLIC . '/uploads/' . $item['avatar'] : _HOST_URL_PUBLIC . '/img/' . 'service_picture.jpg'; ?>" class="card-img-top addon-thumb" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <div class="card-body p-3 d-flex flex-column">
                                    <h3 class="h5 mb-2"><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <div class="fs-4 fw-bold mb-2">
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
                                    <a href="<?php echo _HOST_URL ?>/cart/add?package_id=<?php echo $item['id']; ?>" type="button" class="mt-auto btn btn-outline-primary rounded-0 rounded-pill">Thêm vào giỏ hàng</a>
                                </div>
                            </article>
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