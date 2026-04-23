<?php
$data = [
	'title' => 'Chi tiết Đơn hàng',
];
if (isset($user)) {
	$data['user'] = $user;
}
if (isset($cartItemCount)) {
	$data['cartItemCount'] = $cartItemCount;
}
layout('sidebar', $data);
layout('header', $data);

$detail = isset($order) && is_array($order) ? $order : [];
$orderItems = isset($orderItems) && is_array($orderItems) ? $orderItems : [];
$taskList = isset($orderTasks) && is_array($orderTasks) ? $orderTasks : [];
$statusKey = isset($detail['status']) ? strtolower(trim($detail['status'])) : 'pending';

$statusMap = [
	'pending' => ['label' => 'Chờ thanh toán'],
	'processing' => ['label' => 'Đang xử lý'],
	'completed' => ['label' => 'Hoàn thành'],
	'cancelled' => ['label' => 'Đã hủy']
];

$statusInfo = isset($statusMap[$statusKey]) ? $statusMap[$statusKey] : $statusMap['pending'];

$totalTasks = isset($detail['total_tasks']) ? (int) $detail['total_tasks'] : count($taskList);
$doneTasks = isset($detail['done_tasks']) ? (int) $detail['done_tasks'] : 0;
if ($doneTasks < 0) {
	$doneTasks = 0;
}
if ($totalTasks > 0 && $doneTasks > $totalTasks) {
	$doneTasks = $totalTasks;
}
$progress = $totalTasks > 0 ? (int) round(($doneTasks / $totalTasks) * 100) : 0;

$formatDate = function ($dateValue) {
	if (empty($dateValue)) {
		return '---';
	}
	$ts = strtotime($dateValue);
	return $ts ? date('d/m/Y', $ts) : '---';
};
?>

<style>
	.order-detail-page {
		background:
			radial-gradient(circle at top left, rgba(244, 196, 48, 0.14), transparent 26%),
			linear-gradient(180deg, #fffdf8 0%, #fff7e4 100%);
		border-radius: 28px;
	}

	.order-detail-head {
		padding: 22px;
		border-radius: 24px;
		background: linear-gradient(135deg, rgba(17, 17, 17, 0.95) 0%, rgba(36, 28, 12, 0.96) 100%);
		color: #fff8de;
		margin-bottom: 20px;
	}

	.order-detail-kicker {
		margin: 0;
		text-transform: uppercase;
		letter-spacing: 0.16em;
		font-size: 0.75rem;
		color: #f4c430;
		font-weight: 700;
	}

	.order-detail-head h1 {
		margin: 8px 0 10px;
		font-size: clamp(1.4rem, 2.5vw, 2.1rem);
		font-weight: 800;
	}

	.order-detail-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 22px;
	}

	.order-detail-card {
		padding: 22px;
		border-radius: 22px;
		border: 1px solid rgba(201, 154, 17, 0.14);
		background: linear-gradient(180deg, #ffffff 0%, #fff8ea 100%);
		box-shadow: 0 14px 30px rgba(31, 24, 9, 0.08);
	}

	.order-detail-card h2 {
		margin: 0 0 14px;
		font-size: 1.25rem;
		font-weight: 800;
		color: #1b1b1b;
	}

	.order-meta {
		display: grid;
		gap: 10px;
	}

	.order-meta-item {
		display: grid;
		grid-template-columns: 140px minmax(0, 1fr);
		gap: 10px;
		border-radius: 12px;
		padding: 10px 12px;
		background: rgba(255, 249, 234, 0.72);
		border: 1px solid rgba(201, 154, 17, 0.1);
	}

	.order-meta-key {
		color: #7a6b43;
		font-weight: 700;
		font-size: 0.92rem;
	}

	.order-meta-value {
		color: #252525;
		font-weight: 600;
		word-break: break-word;
	}

	.order-price {
		color: #9b7500;
		font-size: 1.45rem;
		font-weight: 800;
	}

	.order-progress-track {
		height: 10px;
		border-radius: 999px;
		background: #ececec;
		overflow: hidden;
	}

	.order-progress-fill {
		height: 100%;
		border-radius: 999px;
		background: linear-gradient(90deg, #f4c430 0%, #9b7500 100%);
	}

	.order-progress-label {
		margin-top: 8px;
		color: #666;
		font-size: 0.9rem;
		font-weight: 600;
	}

	.order-detail-actions {
		margin-top: 16px;
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}

	.order-items-table-wrap {
		margin-top: 14px;
		border-radius: 14px;
		overflow: hidden;
		border: 1px solid rgba(201, 154, 17, 0.14);
		background: #ffffff;
	}

	.order-items-table {
		width: 100%;
		border-collapse: collapse;
	}

	.order-items-table thead {
		background: rgba(17, 17, 17, 0.94);
		color: #ffe9a2;
	}

	.order-items-table th,
	.order-items-table td {
		padding: 11px 12px;
		border-bottom: 1px solid rgba(201, 154, 17, 0.1);
		text-align: left;
		font-size: 0.9rem;
		vertical-align: middle;
	}

	.order-items-table th {
		text-transform: uppercase;
		letter-spacing: 0.04em;
		font-size: 0.76rem;
	}

	.order-item-name {
		font-weight: 700;
		color: #1b1b1b;
	}

	.order-item-badge {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 0.2rem 0.6rem;
		border-radius: 999px;
		font-size: 0.72rem;
		font-weight: 700;
		border: 1px solid transparent;
	}

	.order-item-badge.combo {
		background: rgba(41, 98, 255, 0.12);
		color: #1f4fcf;
		border-color: rgba(41, 98, 255, 0.24);
	}

	.order-item-badge.addon {
		background: rgba(56, 142, 60, 0.14);
		color: #2e7d32;
		border-color: rgba(56, 142, 60, 0.25);
	}

	.order-item-badge.other {
		background: rgba(255, 183, 3, 0.14);
		color: #996a00;
		border-color: rgba(255, 183, 3, 0.32);
	}

	.order-item-qty {
		font-weight: 700;
		color: #9b7500;
	}

	.order-items-empty {
		margin-top: 14px;
		padding: 12px;
		border-radius: 12px;
		background: rgba(17, 17, 17, 0.04);
		border: 1px dashed rgba(17, 17, 17, 0.2);
		color: #666;
		font-size: 0.92rem;
	}

	.order-tasks-table-wrap {
		border-radius: 16px;
		overflow: hidden;
		border: 1px solid rgba(201, 154, 17, 0.14);
		background: #ffffff;
	}

	.order-tasks-table {
		width: 100%;
		border-collapse: collapse;
	}

	.order-tasks-table thead {
		background: rgba(17, 17, 17, 0.94);
		color: #ffe9a2;
	}

	.order-tasks-table th,
	.order-tasks-table td {
		padding: 12px 13px;
		border-bottom: 1px solid rgba(201, 154, 17, 0.1);
		text-align: left;
		font-size: 0.92rem;
		vertical-align: middle;
	}

	.order-tasks-table th {
		text-transform: uppercase;
		letter-spacing: 0.04em;
		font-size: 0.78rem;
	}

	.task-status-icon {
		font-size: 1.1rem;
		vertical-align: middle;
		margin-right: 6px;
	}

	.task-done {
		color: #2e7d32;
		font-weight: 700;
	}

	.task-pending {
		color: #9e9e9e;
		font-weight: 700;
	}

	.order-task-empty {
		padding: 18px;
		color: #6a6a6a;
		text-align: center;
	}

	.order-back-link {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		text-decoration: none;
		color: #111111;
		border: 1px solid rgba(201, 154, 17, 0.28);
		border-radius: 999px;
		padding: 0.5rem 0.9rem;
		background: rgba(244, 196, 48, 0.12);
		font-weight: 700;
	}

	.order-back-link:hover {
		color: #111111;
		background: rgba(244, 196, 48, 0.2);
	}

	.order-payment-row {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		flex-wrap: wrap;
	}

	.order-payment-status {
		display: grid;
		gap: 4px;
	}

	.order-pay-btn {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		font-weight: 700;
		border-radius: 999px;
		padding: 0.52rem 0.95rem;
	}

	@media (max-width: 991.98px) {
		.order-detail-grid {
			grid-template-columns: 1fr;
		}
	}

	@media (max-width: 575.98px) {

		.order-detail-card,
		.order-detail-head {
			border-radius: 18px;
			padding: 18px;
		}

		.order-meta-item {
			grid-template-columns: 1fr;
			gap: 6px;
		}

		.order-payment-row {
			align-items: flex-start;
		}
	}
</style>

<main class="order-detail-page px-3 px-md-4 py-4 flex-grow-1">
	<section class="order-detail-head">
		<p class="order-detail-kicker">Theo dõi đơn hàng</p>
		<h1>Chi tiết đơn #<?php echo htmlspecialchars($detail['id'] ?? '---'); ?></h1>
		<div class="order-progress-track">
			<div class="order-progress-fill" style="width: <?php echo $progress; ?>%;"></div>
		</div>
		<div class="order-progress-label"><?php echo htmlspecialchars($statusInfo['label']); ?> - <?php echo $progress; ?>% (<?php echo $doneTasks; ?>/<?php echo $totalTasks; ?> task)</div>
	</section>

	<section class="order-detail-grid">
		<article class="order-detail-card">
			<h2>Thông tin đơn hàng</h2>
			<div class="order-meta">
				<div class="order-meta-item">
					<span class="order-meta-key">Mã đơn</span>
					<span class="order-meta-value">#<?php echo htmlspecialchars($detail['id'] ?? '---'); ?></span>
				</div>
				<div class="order-meta-item">
					<span class="order-meta-key">Ngày tạo</span>
					<span class="order-meta-value"><?php echo htmlspecialchars($formatDate($detail['created_at'] ?? null)); ?></span>
				</div>
			</div>

			<h2 class="mt-4">Gói trong đơn hàng</h2>
			<?php if (!empty($orderItems)): ?>
				<div class="order-items-table-wrap">
					<table class="order-items-table">
						<thead>
							<tr>
								<th>Gói</th>
								<th>Loại</th>
								<th>Số lượng</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($orderItems as $item): ?>
								<?php
								$cate = strtolower(trim((string) ($item['category_name'] ?? '')));
								$badgeClass = 'other';
								$badgeLabel = !empty($item['category_name']) ? $item['category_name'] : 'Khác';
								if ($cate === 'combo') {
									$badgeClass = 'combo';
									$badgeLabel = 'Combo';
								} elseif ($cate === 'add-on' || $cate === 'addon') {
									$badgeClass = 'addon';
									$badgeLabel = 'Add-on';
								}
								?>
								<tr>
									<td class="order-item-name"><?php echo htmlspecialchars($item['package_name'] ?? ('Gói #' . ($item['package_id'] ?? ''))); ?></td>
									<td><span class="order-item-badge <?php echo htmlspecialchars($badgeClass); ?>"><?php echo htmlspecialchars($badgeLabel); ?></span></td>
									<td class="order-item-qty">x<?php echo (int) ($item['quantity'] ?? 0); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<div class="order-items-empty">Đơn hàng này chưa có gói nào trong order_items.</div>
			<?php endif; ?>
		</article>

		<article class="order-detail-card">
			<h2>Danh sách task thực hiện</h2>
			<?php $counter = 1; ?>
			<?php if (!empty($taskList)): ?>
				<div class="order-tasks-table-wrap">
					<table class="order-tasks-table">
						<thead>
							<tr>
								<th>Số thứ tự</th>
								<th>Tên Task</th>
								<th>Trạng thái</th>
								<th>Cập nhật</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($taskList as $task): ?>
								<?php $isDone = isset($task['status']) && (int) $task['status'] === 1; ?>
								<tr>
									<td>#<?php echo $counter++; ?></td>
									<td><?php echo htmlspecialchars($task['package_name'] ?? '---'); ?></td>
									<td>
										<?php if ($isDone): ?>
											<span class="task-done"><i class="bi bi-check-circle-fill task-status-icon"></i>Đã hoàn thành</span>
										<?php else: ?>
											<span class="task-pending"><i class="bi bi-circle task-status-icon"></i>Chưa hoàn thành</span>
										<?php endif; ?>
									</td>
									<td><?php echo htmlspecialchars($formatDate($task['updated_at'] ?? null)); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>
				<div class="order-task-empty">Đơn hàng này chưa có task nào.</div>
			<?php endif; ?>

			<h2 class="mt-4">Thanh toán & trạng thái</h2>
			<div class="order-meta">
				<div class="order-meta-item">
					<span class="order-meta-key">Tổng thanh toán</span>
					<span class="order-meta-value order-price"><?php echo number_format((float) ($detail['total_price'] ?? 0), 0, ',', '.'); ?>đ</span>
				</div>
				<div class="order-meta-item order-payment-row">
					<div class="order-payment-status">
						<span class="order-meta-key">Trạng thái</span>
						<span class="order-meta-value"><?php echo htmlspecialchars($statusInfo['label']); ?></span>
					</div>
					<?php if ($statusKey === 'pending'): ?>
						<a class="btn btn-warning btn-sm shadow-sm order-pay-btn" href="<?php echo _HOST_URL; ?>/checkout?order_id=<?php echo urlencode($detail['id'] ?? ''); ?>">
							<i class="bi bi-credit-card-2-front"></i>
							Thanh toán ngay
						</a>
					<?php endif; ?>
				</div>
				<div class="order-meta-item">
					<span class="order-meta-key">Mốc xử lý</span>
					<span class="order-meta-value">Hệ thống sẽ cập nhật tự động theo tiến độ triển khai gói.</span>
				</div>
			</div>

			<div class="order-detail-actions">
				<a class="order-back-link" href="<?php echo _HOST_URL; ?>/order"><i class="bi bi-arrow-left"></i> Quay lại danh sách</a>
			</div>
		</article>
	</section>
	<section class="order-detail-card mt-4">
		<h2>Thông tin setup</h2>
		<div style="width:100%;height:700px;" data-zite-id="cv6hqcr4yk" data-zite-embed-type="standard" data-zite-inherit-parameters data-zite-parameters='{"order_id":"<?php echo urlencode($detail['id'] ?? ''); ?>"}'></div>
		<script src="https://server.fillout.com/embed/v2-zite/"></script>
	</section>
</main>

<?php
layout('footer');
?>