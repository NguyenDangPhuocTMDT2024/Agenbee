<?php
$data = [
	'title' => 'Đơn hàng của tôi',
];
if (isset($user)) {
	$data['user'] = $user;
}
if (isset($cartItemCount)) {
	$data['cartItemCount'] = $cartItemCount;
}
layout('sidebar', $data);
layout('header', $data);

$orderList = isset($orders) && is_array($orders) ? $orders : [];
$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');

$statusMeta = [
	'pending' => ['label' => 'Chờ thanh toán', 'class' => 'is-pending'],
	'processing' => ['label' => 'Đang xử lý', 'class' => 'is-processing'],
	'completed' => ['label' => 'Hoàn thành', 'class' => 'is-completed'],
	'cancelled' => ['label' => 'Đã hủy', 'class' => 'is-cancelled'],
	'confirming' => ['label' => 'Đang xác nhận', 'class' => 'is-processing'],
];

$formatDate = function ($dateValue) {
	if (empty($dateValue)) {
		return '---';
	}
	$ts = strtotime($dateValue);
	return $ts ? date('d/m/Y', $ts) : '---';
};
?>

<style>
	.orders-page {
		background:
			radial-gradient(circle at top left, rgba(244, 196, 48, 0.14), transparent 26%),
			linear-gradient(180deg, #fffdf8 0%, #fff7e4 100%);
		border-radius: 28px;
	}

	.orders-head {
		padding: 20px;
		border-radius: 24px;
		background: linear-gradient(135deg, rgba(17, 17, 17, 0.95) 0%, rgba(36, 28, 12, 0.96) 100%);
		color: #fff8de;
		margin-bottom: 20px;
	}

	.orders-head p {
		margin: 0;
		text-transform: uppercase;
		letter-spacing: 0.16em;
		font-size: 0.75rem;
		color: #f4c430;
		font-weight: 700;
	}

	.orders-head h1 {
		margin: 6px 0 0;
		font-size: clamp(1.4rem, 2.5vw, 2rem);
		font-weight: 800;
	}

	.orders-table-wrap {
		border-radius: 24px;
		border: 1px solid rgba(201, 154, 17, 0.14);
		background: linear-gradient(180deg, #ffffff 0%, #fff8ea 100%);
		box-shadow: 0 14px 30px rgba(31, 24, 9, 0.08);
		overflow: hidden;
	}

	.orders-table {
		width: 100%;
		border-collapse: collapse;
	}

	.orders-table thead {
		background: rgba(17, 17, 17, 0.94);
		color: #ffe9a2;
	}

	.orders-table th,
	.orders-table td {
		padding: 13px 14px;
		border-bottom: 1px solid rgba(201, 154, 17, 0.1);
		text-align: left;
		vertical-align: middle;
		font-size: 0.95rem;
	}

	.orders-table th {
		text-transform: uppercase;
		letter-spacing: 0.04em;
		font-size: 0.8rem;
		font-weight: 700;
	}

	.orders-table tbody tr:hover {
		background: rgba(244, 196, 48, 0.06);
	}

	.orders-id {
		font-weight: 800;
		color: #1b1b1b;
	}

	.orders-money {
		color: #9b7500;
		font-weight: 700;
		white-space: nowrap;
	}

	.orders-status-pill {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border-radius: 999px;
		padding: 0.28rem 0.7rem;
		font-size: 0.78rem;
		font-weight: 700;
		border: 1px solid transparent;
	}

	.is-pending .orders-status-pill {
		background: rgba(255, 183, 3, 0.14);
		border-color: rgba(255, 183, 3, 0.3);
		color: #996a00;
	}

	.is-processing .orders-status-pill {
		background: rgba(41, 98, 255, 0.12);
		border-color: rgba(41, 98, 255, 0.24);
		color: #1f4fcf;
	}

	.is-completed .orders-status-pill {
		background: rgba(56, 142, 60, 0.14);
		border-color: rgba(56, 142, 60, 0.24);
		color: #256d2a;
	}

	.is-cancelled .orders-status-pill {
		background: rgba(211, 47, 47, 0.12);
		border-color: rgba(211, 47, 47, 0.22);
		color: #a32121;
	}

	.orders-progress {
		width: 170px;
	}

	.orders-progress-track {
		height: 8px;
		border-radius: 999px;
		background: #ececec;
		overflow: hidden;
	}

	.orders-progress-fill {
		height: 100%;
		border-radius: 999px;
		background: linear-gradient(90deg, #f4c430 0%, #9b7500 100%);
	}

	.is-cancelled .orders-progress-fill {
		background: linear-gradient(90deg, #ef5350 0%, #c62828 100%);
	}

	.orders-progress-text {
		margin-top: 6px;
		font-size: 0.78rem;
		color: #6c6c6c;
		font-weight: 600;
	}

	.orders-detail-link {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		text-decoration: none;
		font-weight: 700;
		color: #1b1b1b;
		border: 1px solid rgba(201, 154, 17, 0.28);
		border-radius: 999px;
		padding: 0.42rem 0.75rem;
		background: rgba(244, 196, 48, 0.11);
		white-space: nowrap;
	}

	.orders-detail-link:hover {
		background: rgba(244, 196, 48, 0.2);
		color: #111111;
	}

	.orders-empty {
		padding: 34px 18px;
		text-align: center;
		color: #646464;
	}

	@media (max-width: 991.98px) {
		.orders-table-wrap {
			overflow-x: auto;
		}

		.orders-table {
			min-width: 860px;
		}
	}
</style>

<main class="orders-page px-3 px-md-4 py-4 flex-grow-1">
	<section class="orders-head">
		<p>Lịch sử đơn hàng</p>
		<h1>Danh sách đơn hàng của bạn</h1>
	</section>

	<?php if (!empty($msg)): ?>
		<div class="mb-3"><?php echo showMsg($msg, $msgType); ?></div>
	<?php endif; ?>

	<section class="orders-table-wrap">
		<?php if (!empty($orderList)): ?>
			<table class="orders-table">
				<thead>
					<tr>
						<th>Mã đơn</th>
						<th>Tổng tiền</th>
						<th>Ngày tạo đơn</th>
						<th>Trạng thái</th>
						<th>Tiến độ</th>
						<th>Chi tiết</th>
						<th>Hủy đơn</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orderList as $item): ?>
						<?php if($item['status'] === 'cancelled') continue; ?>
						<?php
						$statusKey = isset($item['status']) ? strtolower(trim($item['status'])) : 'pending';
						$meta = isset($statusMeta[$statusKey]) ? $statusMeta[$statusKey] : $statusMeta['pending'];
						$rowClass = $meta['class'];
						$totalTasks = isset($item['total_tasks']) ? (int) $item['total_tasks'] : 0;
						$doneTasks = isset($item['done_tasks']) ? (int) $item['done_tasks'] : 0;
						if ($doneTasks < 0) {
							$doneTasks = 0;
						}
						if ($totalTasks > 0 && $doneTasks > $totalTasks) {
							$doneTasks = $totalTasks;
						}
						$progress = $totalTasks > 0 ? (int) round(($doneTasks / $totalTasks) * 100) : 0;
						?>
						<tr class="<?php echo htmlspecialchars($rowClass); ?>">
							<td class="orders-id">#<?php echo htmlspecialchars($item['id'] ?? '---'); ?></td>
							<td class="orders-money"><?php echo number_format((float) ($item['total_price'] ?? 0), 0, ',', '.'); ?>đ</td>
							<td><?php echo htmlspecialchars($formatDate($item['created_at'] ?? null)); ?></td>
							<td><span class="orders-status-pill"><?php echo htmlspecialchars($meta['label']); ?></span></td>
							<td>    
								<div class="orders-progress">
									<div class="orders-progress-track">
										<div class="orders-progress-fill" style="width: <?php echo $progress; ?>%;"></div>
									</div>
									<div class="orders-progress-text"><?php echo $progress; ?>% (<?php echo $doneTasks; ?>/<?php echo $totalTasks; ?> task)</div>
								</div>
							</td>
							<td>
								<a class="orders-detail-link" href="<?php echo _HOST_URL; ?>/order/detail?id=<?php echo urlencode((string) ($item['id'] ?? '')); ?>">
									<i class="bi bi-eye-fill"></i>
									Xem
								</a>
							</td>
							<td>
								<button class="orders-detail-link" onclick="if(confirm('Bạn có chắc muốn hủy đơn hàng này?')) { window.location.href = '<?php echo _HOST_URL; ?>/order/cancel?id=<?php echo urlencode((string) ($item['id'] ?? '')); ?>'; }" <?php echo ($statusKey !== 'pending') ? 'disabled' : ''; ?>>
									<i class="bi bi-x-lg"></i>
									Hủy đơn
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<div class="orders-empty">
				Bạn chưa có đơn hàng nào. Hãy chọn gói dịch vụ để bắt đầu.
			</div>
		<?php endif; ?>
	</section>
</main>

<?php
layout('footer');
?>
