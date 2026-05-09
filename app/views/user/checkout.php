<?php
$data = [
	'title' => 'Thanh toán',
];
if (isset($user)) {
	$data['user'] = $user;
}
if (isset($cartItemCount)) {
	$data['cartItemCount'] = $cartItemCount;
}
layout('sidebar', $data);
layout('header', $data);

$orderId = isset($orderId) ? trim((string) $orderId) : '';

$packagesOrders = isset($orderItems) && is_array($orderItems) ? $orderItems : [];

$subtotal = 0;
foreach ($packagesOrders as $item) {
	$subtotal += ((int) $item['quantity']) * ((float) $item['package_price']);
}
$discount = 0;
$grandTotal = max(0, $subtotal - $discount);
?>

<style>
	.checkout-page {
		background:
			radial-gradient(circle at top left, rgba(244, 196, 48, 0.14), transparent 28%),
			linear-gradient(180deg, #fffdf8 0%, #fff7e4 100%);
		border-radius: 28px;
	}

	.checkout-hero {
		padding: 22px;
		border-radius: 24px;
		background: linear-gradient(135deg, rgba(17, 17, 17, 0.96) 0%, rgba(36, 28, 12, 0.96) 100%);
		color: #fff8de;
		margin-bottom: 20px;
		box-shadow: 0 16px 34px rgba(31, 24, 9, 0.08);
	}

	.checkout-kicker {
		margin: 0 0 8px;
		text-transform: uppercase;
		letter-spacing: 0.16em;
		font-size: 0.75rem;
		color: #f4c430;
		font-weight: 700;
	}

	.checkout-hero h1 {
		margin: 0;
		font-size: clamp(1.5rem, 2.5vw, 2.2rem);
		font-weight: 800;
	}

	.checkout-hero p {
		margin: 10px 0 0;
		color: rgba(255, 248, 222, 0.86);
		max-width: 760px;
		line-height: 1.7;
	}

	.checkout-grid {
		display: grid;
		grid-template-columns: minmax(0, 1.45fr) minmax(320px, 0.85fr);
		gap: 22px;
		align-items: start;
	}

	.checkout-card {
		border-radius: 22px;
		border: 1px solid rgba(201, 154, 17, 0.14);
		background: linear-gradient(180deg, #ffffff 0%, #fff8ea 100%);
		box-shadow: 0 14px 30px rgba(31, 24, 9, 0.08);
		padding: 22px;
	}

	.checkout-card h2 {
		margin: 0 0 14px;
		font-size: 1.2rem;
		font-weight: 800;
		color: #1b1b1b;
	}

	.checkout-subtext {
		margin: -6px 0 14px;
		color: #676767;
		font-size: 0.94rem;
	}

	.checkout-table-wrap {
		border-radius: 16px;
		overflow: hidden;
		border: 1px solid rgba(201, 154, 17, 0.14);
		background: #ffffff;
	}

	.checkout-table {
		width: 100%;
		border-collapse: collapse;
	}

	.checkout-table thead {
		background: linear-gradient(135deg, rgba(17, 17, 17, 0.94) 0%, rgba(36, 28, 12, 0.92) 100%);
		color: #fff8de;
	}

	.checkout-table th,
	.checkout-table td {
		padding: 12px 14px;
		border-bottom: 1px solid rgba(201, 154, 17, 0.1);
		font-size: 0.92rem;
		vertical-align: middle;
		text-align: left;
	}

	.checkout-table th {
		text-transform: uppercase;
		letter-spacing: 0.04em;
		font-size: 0.76rem;
	}

	.checkout-item-name {
		font-weight: 700;
		color: #1b1b1b;
	}

	.checkout-item-meta {
		color: #7a7a7a;
		font-size: 0.85rem;
		margin-top: 4px;
	}

	.checkout-badge {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 0.24rem 0.65rem;
		border-radius: 999px;
		font-size: 0.72rem;
		font-weight: 700;
	}

	.checkout-badge.combo {
		background: rgba(41, 98, 255, 0.12);
		color: #1f4fcf;
	}

	.checkout-badge.addon {
		background: rgba(56, 142, 60, 0.14);
		color: #2e7d32;
	}

	.checkout-summary {
		display: grid;
		gap: 14px;
	}

	.checkout-summary-box {
		border-radius: 16px;
		padding: 14px 16px;
		background: rgba(255, 249, 234, 0.78);
		border: 1px solid rgba(201, 154, 17, 0.12);
		display: flex;
		justify-content: space-between;
		gap: 12px;
		align-items: center;
	}

	.checkout-summary-label {
		color: #6a5a2c;
		font-weight: 700;
	}

	.checkout-summary-value {
		color: #1b1b1b;
		font-weight: 800;
	}

	.checkout-total {
		border-radius: 18px;
		background: linear-gradient(135deg, rgba(17, 17, 17, 0.96) 0%, rgba(36, 28, 12, 0.96) 100%);
		color: #fff8de;
		padding: 18px;
	}

	.checkout-total .checkout-summary-label,
	.checkout-total .checkout-summary-value {
		color: inherit;
	}

	.checkout-payment-list {
		display: grid;
		gap: 10px;
		margin-top: 14px;
	}

	.checkout-payment-option {
		display: flex;
		gap: 10px;
		align-items: center;
		border-radius: 14px;
		padding: 11px 12px;
		border: 1px solid rgba(201, 154, 17, 0.14);
		background: #ffffff;
	}

	.checkout-payment-option input {
		accent-color: #f4c430;
	}

	.checkout-btn-confirm {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		width: 100%;
		padding: 0.95rem 1.2rem;
		border-radius: 999px;
		border: none;
		font-weight: 800;
		color: #111111;
		background: linear-gradient(135deg, #f4c430 0%, #e9b913 100%);
		box-shadow: 0 10px 24px rgba(201, 154, 17, 0.2);
		text-decoration: none;
		transition: transform 0.2s ease, box-shadow 0.2s ease;
	}

	.checkout-btn-confirm:hover {
		transform: translateY(-1px);
		box-shadow: 0 14px 28px rgba(201, 154, 17, 0.25);
		color: #111111;
	}

	.checkout-btn-back {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		width: 100%;
		padding: 0.82rem 1.1rem;
		border-radius: 999px;
		border: 1px solid rgba(201, 154, 17, 0.34);
		font-weight: 700;
		color: #5b480f;
		background: linear-gradient(135deg, #fff9e6 0%, #ffefbf 100%);
		box-shadow: 0 8px 20px rgba(201, 154, 17, 0.14);
		text-decoration: none;
		transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
	}

	.checkout-btn-back:hover {
		transform: translateY(-1px);
		color: #3f3005;
		background: linear-gradient(135deg, #fff0c8 0%, #ffe39d 100%);
		box-shadow: 0 12px 24px rgba(201, 154, 17, 0.2);
	}

	#paymentDetails {
		margin-top: 14px;
	}

	.payment-proof-form {
		display: grid;
		gap: 14px;
		padding: 18px;
		border-radius: 18px;
		border: 1px solid rgba(201, 154, 17, 0.14);
		background: linear-gradient(180deg, #fffdf6 0%, #fff7e3 100%);
		box-shadow: 0 16px 30px rgba(31, 24, 9, 0.08);
	}

	.payment-proof-qr {
		width: 100%;
		max-width: 300px;
		margin: 0 auto;
		display: block;
		border-radius: 18px;
		background: #ffffff;
		padding: 10px;
		box-shadow: 0 8px 20px rgba(31, 24, 9, 0.08);
	}

	.payment-proof-note {
		margin: 0;
		text-align: center;
		color: #6a5a2c;
		font-size: 0.92rem;
		line-height: 1.6;
	}

	.payment-proof-upload {
		display: grid;
		gap: 10px;
		padding: 14px;
		border-radius: 16px;
		border: 1px dashed rgba(201, 154, 17, 0.35);
		background: rgba(255, 255, 255, 0.85);
	}

	.payment-proof-upload-label {
		display: grid;
		gap: 4px;
	}

	.payment-proof-upload-title {
		font-weight: 800;
		color: #1b1b1b;
	}

	.payment-proof-upload-help {
		color: #77633a;
		font-size: 0.88rem;
	}

	.payment-proof-input {
		width: 100%;
		padding: 0.85rem 1rem;
		border-radius: 14px;
		border: 1px solid rgba(201, 154, 17, 0.24);
		background: #fffdf8;
		color: #4d3f1a;
		font-size: 0.95rem;
	}

	.payment-proof-input::file-selector-button {
		margin-right: 12px;
		padding: 0.65rem 1rem;
		border: none;
		border-radius: 999px;
		background: linear-gradient(135deg, #f4c430 0%, #e9b913 100%);
		color: #111111;
		font-weight: 800;
		cursor: pointer;
	}

	.payment-proof-input:hover::file-selector-button {
		filter: brightness(1.02);
	}

	.payment-proof-input:focus {
		outline: none;
		border-color: rgba(244, 196, 48, 0.8);
		box-shadow: 0 0 0 4px rgba(244, 196, 48, 0.18);
	}

	.checkout-note {
		color: #767676;
		font-size: 0.88rem;
		line-height: 1.6;
		margin-top: 12px;
	}

	@media (max-width: 991.98px) {
		.checkout-grid {
			grid-template-columns: 1fr;
		}
	}

	@media (max-width: 767.98px) {
		.checkout-card,
		.checkout-hero {
			padding: 16px;
			border-radius: 18px;
		}

		.checkout-table th,
		.checkout-table td {
			padding: 10px 12px;
		}

		.checkout-btn-back,
		.checkout-btn-confirm {
			font-size: 0.94rem;
		}
	}
</style>

<main class="checkout-page px-3 px-md-4 py-4 flex-grow-1">
	<section class="checkout-hero">
		<p class="checkout-kicker">Checkout</p>
		<h1>Xác nhận đơn hàng và tiến hành thanh toán</h1>
		<p>
			Vui lòng kiểm tra lại thông tin đơn hàng của bạn trước khi xác nhận thanh toán. Đảm bảo rằng tất cả các gói dịch vụ đã chọn đều chính xác và tổng số tiền thanh toán là hợp lý. Nếu có bất kỳ thắc mắc nào, đừng ngần ngại liên hệ với chúng tôi để được hỗ trợ.
		</p>
	</section>

	<div class="checkout-grid">
		<section class="checkout-card">
			<h2>Danh sách gói trong đơn</h2>
			<p class="checkout-subtext">
				Mã đơn: <strong><?php echo !empty($orderId) ? htmlspecialchars($orderId) : 'MOCK-CHK-2026'; ?></strong>
			</p>

			<div class="checkout-table-wrap">
				<table class="checkout-table">
					<thead>
						<tr>
							<th>Gói</th>
							<th>SL</th>
							<th>Đơn giá</th>
							<th>Thành tiền</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($packagesOrders as $item): ?>
							<?php
							$lineTotal = ((int) $item['quantity']) * ((float) $item['package_price']);
							?>
							<tr>
								<td>
									<div class="checkout-item-name"><?php echo htmlspecialchars($item['package_name']); ?></div>
									<div class="checkout-item-meta"><?php echo htmlspecialchars($item['category_name']); ?></div>
								</td>
								<td><?php echo (int) $item['quantity']; ?></td>
								<td><?php echo number_format((float) $item['package_price'], 0, ',', '.'); ?>đ</td>
								<td><strong><?php echo number_format($lineTotal, 0, ',', '.'); ?>đ</strong></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</section>

		<aside class="checkout-summary">
			<section class="checkout-card">
				<h2>Thông tin thanh toán</h2>

				<div class="checkout-summary-box">
					<span class="checkout-summary-label">Tổng tạm tính</span>
					<span class="checkout-summary-value"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</span>
				</div>
				<div class="checkout-summary-box">
					<span class="checkout-summary-label">Giảm giá</span>
					<span class="checkout-summary-value">-<?php echo number_format($discount, 0, ',', '.'); ?>đ</span>
				</div>
				<div class="checkout-summary-box checkout-total">
					<span class="checkout-summary-label">Tổng thanh toán</span>
					<span class="checkout-summary-value"><?php echo number_format($grandTotal, 0, ',', '.'); ?>đ</span>
				</div>

				<div class="mt-3">
					<button type="button" class="checkout-btn-confirm" id="confirmPayment" onclick="showPayment()">
						<i class="bi bi-shield-check"></i>
						Xác nhận thanh toán
					</button>
					<div id="paymentDetails"></div>
				</div>

				<p class="checkout-note">
					Đơn hàng sẽ được xử lý và xác nhận sau khi bạn thực hiện thanh toán. Vui lòng đảm bảo rằng bạn đã hoàn tất thanh toán và gửi minh chứng thanh toán nếu chọn phương thức chuyển khoản để chúng tôi có thể nhanh chóng xác nhận đơn hàng của bạn.
				</p>
				<?php if (isset($backToCart) && $backToCart === true): ?>
					<div class="mt-3">
						<a class="checkout-btn-back" href="<?php echo _HOST_URL; ?>/back-to-cart?order_id=<?php echo isset($orderId) ? urlencode($orderId) : ''; ?>"><i class="bi bi-arrow-left"></i> Quay lại giỏ hàng</a>
					</div>
				<?php endif; ?>
			</section>
		</aside>
	</div>
</main>

<script>
	const grandTotal = <?php echo json_encode((float) $grandTotal); ?>;
	const orderId = <?php echo json_encode($orderId); ?>;

	function showPayment() {
		const paymentBlock = document.querySelector('#paymentDetails');
		if (!paymentBlock || paymentBlock.dataset.rendered === '1') {
			return;
		}

		const newForm = document.createElement('form');
		newForm.method = 'POST';
		newForm.enctype = 'multipart/form-data';
		newForm.className = 'payment-proof-form';
		newForm.innerHTML = `
			<img class="payment-proof-qr" src="https://img.vietqr.io/image/970426-80003031591-compact2.png?amount=${encodeURIComponent(grandTotal)}&addInfo=${encodeURIComponent('Thanh toan don hang #' + orderId)}&accountName=Nguyen+Dang+Phuoc" alt="QR Code Thanh Toán">
			<p class="payment-proof-note">Sau khi thanh toán vui lòng gửi minh chứng thanh toán về cho chúng tôi.</p>
			<div class="payment-proof-upload">
				<div class="payment-proof-upload-label">
					<span class="payment-proof-upload-title">Nộp minh chứng thanh toán</span>
					<span class="payment-proof-upload-help">Chọn ảnh rõ nét của hóa đơn hoặc ảnh chuyển khoản.</span>
				</div>
				<input class="payment-proof-input" type="file" name="payment_proof" accept="image/*" required>
			</div>
			<input type="hidden" name="order_id" value="${orderId}">
			<button type="submit" class="checkout-btn-confirm">
				<i class="bi bi-upload"></i>
				Gửi minh chứng thanh toán
			</button>
		`;
		paymentBlock.dataset.rendered = '1';
		paymentBlock.append(newForm);
	}
</script>
<?php layout('footer'); ?>
