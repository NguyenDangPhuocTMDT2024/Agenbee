<?php
$data = [
    'title' => 'Giỏ hàng',
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

$cartInfo = isset($cartInfo) && is_array($cartInfo) ? $cartInfo : [];
$cartTotal = 0;
$cartTotalItems = 0;

foreach ($cartInfo as $item) {
    $lineQuantity = isset($item['quantity']) ? (int) $item['quantity'] : 0;
    $linePrice = isset($item['price']) ? (float) $item['price'] : 0;
    $cartTotal += ($linePrice * $lineQuantity);
    $cartTotalItems += $lineQuantity;
}
?>

<main class="px-3 px-md-4 py-4 flex-grow-1 bg-white">
    <div class="container-fluid px-0 cart-page-wrap">
        <?php if (!empty($msg)): ?>
            <div class="mb-3"><?php echo showMsg($msg, $msgType); ?></div>
        <?php endif; ?>

        <div class="cart-head-row">
            <div class="cart-col cart-col-product">
                <span>Sản phẩm</span>
            </div>
            <div class="cart-col cart-col-price">Đơn giá</div>
            <div class="cart-col cart-col-qty">Số lượng</div>
            <div class="cart-col cart-col-total">Số tiền</div>
            <div class="cart-col cart-col-action">Thao tác</div>
        </div>

        <?php if (!empty($cartInfo)): ?>
            <?php foreach ($cartInfo as $item): ?>
                <?php
                    $lineQuantity = isset($item['quantity']) ? (int) $item['quantity'] : 1;
                    $linePrice = isset($item['price']) ? (float) $item['price'] : 0;
                    $lineTotal = $linePrice * $lineQuantity;
                ?>
                <section class="cart-shop-card">
                    <div class="cart-product-row">
                        <div class="cart-col cart-col-product cart-product-info">
                            <div class="cart-thumb" aria-hidden="true">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="cart-item-meta">
                                <p class="cart-item-name mb-1"><?php echo htmlspecialchars($item['name']); ?></p>
                                <span class="cart-item-tag"><?php echo !empty($item['type']) ? htmlspecialchars($item['type']) : 'Gói dịch vụ'; ?></span>
                            </div>
                        </div>

                        <div class="cart-col cart-col-price"><?php echo number_format($linePrice, 0, ',', '.'); ?>đ</div>

                        <div class="cart-col cart-col-qty">
                            <div class="cart-qty-box" role="group" aria-label="Số lượng">
                                <form method="POST" action="" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="package_id" value="<?php echo $item['package_id']; ?>">
                                    <input type="hidden" name="current_quantity" value="<?php echo $lineQuantity; ?>">
                                    <button type="submit" name="action" value="decrease" class="qty-btn <?php echo $lineQuantity <= 1 ? 'text-secondary' : ''; ?>" <?php echo $lineQuantity <= 1 ? 'disabled' : ''; ?>>-</button>
                                    <span class="qty-value"><?php echo $lineQuantity; ?></span>
                                    <button type="submit" name="action" value="increase" class="qty-btn">+</button>
                                </form>
                            </div>
                        </div>

                        <div class="cart-col cart-col-total"><?php echo number_format($lineTotal, 0, ',', '.'); ?>đ</div>

                        <div class="cart-col cart-col-action">
                            <a href="<?php echo _HOST_URL ?>/cart/remove?package_id=<?php echo $item['package_id']; ?>&user_id=<?php echo getSession('user_id'); ?>" class="cart-remove-link">Xóa</a>
                        </div>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="cart-empty-state">
                <i class="bi bi-cart-x"></i>
                <p>Giỏ hàng của bạn đang trống.</p>
                <a href="<?php echo _HOST_URL; ?>/package" class="btn btn-warning btn-sm">Mua ngay</a>
            </div>
        <?php endif; ?>

        <div class="cart-summary-bar">
            <div class="cart-summary-left">Tổng sản phẩm: <?php echo $cartTotalItems; ?></div>
            <div class="cart-summary-right">
                <span>Tổng cộng: <strong><?php echo number_format($cartTotal, 0, ',', '.'); ?>đ</strong></span>
                <a href="<?php echo _HOST_URL; ?>/order/confirm" class="btn btn-warning cart-checkout-btn">Mua hàng</a>
            </div>
        </div>
    </div>
</main>

<?php
layout('footer');
?>