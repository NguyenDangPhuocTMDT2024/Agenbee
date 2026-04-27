<?php
$data = [
    'title' => 'Chi tiết đơn hàng',
    'userInfo' => $userInfo
];
layout('admin-header', $data);
layout('admin-sidebar');

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');

$orderInfo = isset($orderInfo) && is_array($orderInfo) ? $orderInfo : [];
$orderItems = isset($orderItems) && is_array($orderItems) ? $orderItems : [];
$orderTasks = isset($orderTasks) && is_array($orderTasks) ? $orderTasks : [];

$statusTextMap = [
    'pending' => 'Chờ thanh toán',
    'confirming' => 'Đang xác nhận thanh toán',
    'processing' => 'Đang xử lý',
    'completed' => 'Hoàn thành',
    'cancelled' => 'Đã hủy'
];

$statusClassMap = [
    'pending' => 'bg-warning text-dark',
    'confirming' => 'bg-info text-dark',
    'processing' => 'bg-primary',
    'completed' => 'bg-success',
    'cancelled' => 'bg-danger'
];

$statusKey = isset($orderInfo['status']) ? (string) $orderInfo['status'] : 'pending';
$statusLabel = isset($statusTextMap[$statusKey]) ? $statusTextMap[$statusKey] : 'Không xác định';
$statusClass = isset($statusClassMap[$statusKey]) ? $statusClassMap[$statusKey] : 'bg-secondary';

$totalTasks = isset($orderInfo['total_tasks']) ? (int) $orderInfo['total_tasks'] : count($orderTasks);
$doneTasks = isset($orderInfo['done_tasks']) ? (int) $orderInfo['done_tasks'] : 0;
if ($doneTasks > $totalTasks) {
    $doneTasks = $totalTasks;
}
$progressPercent = $totalTasks > 0 ? (int) round(($doneTasks / $totalTasks) * 100) : 0;

$orderId = isset($orderInfo['id']) ? (string) $orderInfo['id'] : '';
$paymentProof = isset($orderInfo['payment_proof']) ? (string) $orderInfo['payment_proof'] : '';

$subtotal = 0;
foreach ($orderItems as $item) {
    $subtotal += ((int) ($item['quantity'] ?? 0)) * ((float) ($item['package_price'] ?? 0));
}
?>

<style>
    .admin-order-detail-card {
        border: 1px solid #edf0f4;
        border-radius: 12px;
    }

    .admin-order-kpi {
        border: 1px dashed #d8dde6;
        border-radius: 10px;
        padding: 12px;
        background: #fafbfd;
    }

    .admin-task-row {
        border: 1px solid #eef1f5;
        border-radius: 10px;
        padding: 12px 14px;
        background: #ffffff;
    }

    .admin-task-row.is-done {
        background: #f5fff7;
        border-color: #d4f0dc;
    }

    .admin-task-row+.admin-task-row {
        margin-top: 10px;
    }

    .admin-task-name {
        font-weight: 600;
    }

    .admin-task-meta {
        color: #6c757d;
        font-size: 0.86rem;
    }

    .admin-payment-proof {
        max-width: 100%;
        border: 1px solid #e6e9ef;
        border-radius: 10px;
        padding: 6px;
        background: #fff;
    }
</style>

<main class="admin-main">
    <div class="container mt-4 mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1">Chi tiết đơn hàng #<?php echo htmlspecialchars($orderId); ?></h4>
                <p class="text-muted mb-0">
                    Khách hàng: <?php echo htmlspecialchars($orderInfo['user_name'] ?? ('User #' . ($orderInfo['user_id'] ?? 'N/A'))); ?>
                </p>
            </div>
            <a href="<?php echo _HOST_URL; ?>/admin/order" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        <?php
        if (!empty($msg) && !empty($msgType)) {
            echo showMsg($msg, $msgType);
        }
        ?>

        <div class="row g-3 mb-1">
            <div class="col-12">
                <div class="card shadow-sm admin-order-detail-card">
                    <div class="card-body">
                        <form method="POST" action="<?php echo _HOST_URL; ?>/admin/order/detail" class="row g-2 align-items-end">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderId); ?>">
                            <div class="col-lg-8 col-md-7">
                                <label for="order_status" class="form-label fw-semibold mb-1">Trạng thái đơn hàng</label>
                                <select class="form-select" id="order_status" name="order_status" required>
                                    <option value="pending" <?php echo $statusKey === 'pending' ? 'selected' : ''; ?>>Chờ thanh toán</option>
                                    <option value="confirming" <?php echo $statusKey === 'confirming' ? 'selected' : ''; ?>>Đang xác nhận thanh toán</option>
                                    <option value="processing" <?php echo $statusKey === 'processing' ? 'selected' : ''; ?>>Đang xử lý</option>
                                    <option value="completed" <?php echo $statusKey === 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                                    <option value="cancelled" <?php echo $statusKey === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-5 d-grid">
                                <button type="submit" class="btn btn-warning text-dark fw-semibold">
                                    <i class="bi bi-arrow-repeat"></i> Cập nhật trạng thái đơn
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm admin-order-detail-card mb-3">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Danh sách gói trong đơn</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Gói dịch vụ</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-end">Đơn giá</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($orderItems)): ?>
                                        <?php foreach ($orderItems as $item): ?>
                                            <?php $lineTotal = ((int) ($item['quantity'] ?? 0)) * ((float) ($item['package_price'] ?? 0)); ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold"><?php echo htmlspecialchars($item['package_name'] ?? 'N/A'); ?></div>
                                                    <div class="text-muted small"><?php echo htmlspecialchars($item['category_name'] ?? 'N/A'); ?></div>
                                                </td>
                                                <td class="text-center"><?php echo (int) ($item['quantity'] ?? 0); ?></td>
                                                <td class="text-end"><?php echo number_format((float) ($item['package_price'] ?? 0), 0, ',', '.'); ?>đ</td>
                                                <td class="text-end fw-bold text-danger"><?php echo number_format($lineTotal, 0, ',', '.'); ?>đ</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">Đơn hàng chưa có item.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light fw-bold">
                                        <td colspan="3" class="text-end">Tổng tiền</td>
                                        <td class="text-end text-danger"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm admin-order-detail-card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h5 class="fw-bold mb-0">Task triển khai</h5>
                            <span class="text-muted small">Tick vào task để đánh dấu hoàn thành</span>
                        </div>

                        <?php if (!empty($orderTasks)): ?>
                            <form method="POST" action="<?php echo _HOST_URL; ?>/admin/order/detail">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderId); ?>">
                                <?php foreach ($orderTasks as $task): ?>
                                    <?php
                                    $taskId = (int)$task['id'];
                                    $isDone = $task['status'];
                                    ?>
                                    <label class="d-flex align-items-start gap-3 admin-task-row <?php echo $isDone ? 'is-done' : ''; ?>" for="task_<?php echo $taskId; ?>">
                                        <input
                                            class="form-check-input mt-1"
                                            type="checkbox"
                                            id="task_<?php echo $taskId; ?>"
                                            name="task_ids[]"
                                            value="<?php echo $taskId; ?>"
                                            <?php echo $isDone ? 'checked' : ''; ?>>
                                        <div>
                                            <div class="admin-task-name"><?php echo htmlspecialchars($task['package_name'] ?? ('Task #' . $taskId)); ?></div>
                                            <div class="admin-task-meta">
                                                Trạng thái: <?php echo $isDone ? 'Đã hoàn thành' : 'Chưa hoàn thành'; ?>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>

                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check2-square"></i> Lưu tiến độ task
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-secondary mb-0">Đơn hàng này chưa có task nào để cập nhật.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm admin-order-detail-card mb-3">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Tiến độ đơn hàng</h5>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($statusLabel); ?></span>
                            <span class="fw-semibold"><?php echo $progressPercent; ?>%</span>
                        </div>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $progressPercent; ?>%;"></div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="admin-order-kpi text-center">
                                    <div class="small text-muted">Task hoàn thành</div>
                                    <div class="fw-bold fs-5"><?php echo $doneTasks; ?></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="admin-order-kpi text-center">
                                    <div class="small text-muted">Tổng task</div>
                                    <div class="fw-bold fs-5"><?php echo $totalTasks; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm admin-order-detail-card mb-3">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Thông tin đơn</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="text-muted">Mã đơn</span>
                                <span class="fw-semibold">#<?php echo htmlspecialchars($orderId); ?></span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="text-muted">Khách hàng</span>
                                <span class="fw-semibold text-end"><?php echo htmlspecialchars($orderInfo['user_name'] ?? ('User #' . ($orderInfo['user_id'] ?? 'N/A'))); ?></span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="text-muted">Email</span>
                                <span class="fw-semibold text-end"><?php echo htmlspecialchars($orderInfo['user_email'] ?? 'N/A'); ?></span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="text-muted">Số điện thoại</span>
                                <span class="fw-semibold text-end"><?php echo htmlspecialchars($orderInfo['user_phone'] ?? 'N/A'); ?></span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="text-muted">Ngày tạo</span>
                                <span class="fw-semibold text-end"><?php echo htmlspecialchars($orderInfo['created_at'] ?? 'N/A'); ?></span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="text-muted">Cập nhật</span>
                                <span class="fw-semibold text-end"><?php echo htmlspecialchars($orderInfo['updated_at'] ?? 'N/A'); ?></span>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span class="text-muted">Tổng thanh toán</span>
                                <span class="fw-bold text-danger text-end"><?php echo number_format((float) ($orderInfo['total_price'] ?? 0), 0, ',', '.'); ?>đ</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm admin-order-detail-card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Minh chứng thanh toán</h5>
                        <?php if (!empty($paymentProof)): ?>
                            <img class="admin-payment-proof" src="<?php showImg($paymentProof, 'uploads/payment'); ?>" alt="Payment proof">
                        <?php else: ?>
                            <div class="alert alert-light border mb-0 text-muted">Khách hàng chưa nộp minh chứng thanh toán.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <h5 class="fw-bold mb-3">Thông tin setup</h5>
            <div style="width:100%;height:700px;" data-zite-id="cv6hqcr4yk" data-zite-embed-type="standard" data-zite-inherit-parameters data-zite-parameters='{"order_id":"<?php echo $orderId; ?>"}'></div>
            <script src="https://server.fillout.com/embed/v2-zite/"></script>
        </div>
    </div>
</main>
<?php
layout('admin-footer');
?>