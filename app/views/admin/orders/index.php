<?php
$data = [
    'title' => 'Orders',
    'userInfo' => $userInfo,
    'activeMenu' => 'Orders'
];
layout('admin-header', $data);
layout('admin-sidebar', $data);

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');

$orderStatus = [
    'pending' => '<i class="bi bi-check-circle-fill text-success"></i>',
    'confirming' => '<i class="bi bi-hourglass-split text-warning"></i>',
    'processing' => '<i class="bi bi-hourglass-split text-success"></i>',
    'completed' => '<i class="bi bi-check-circle-fill text-success"></i>',
    'cancelled' => '<i class="bi bi-x-circle-fill text-danger"></i>'
];
?>
<main class="admin-main">
    <div class="container mt-4">
        <div class="row mb-3 justify-content-center">
            <div class="col-md-3">
                <select name="category" id="statusFilter" class="form-select" id="packageType">
                    <option value="all">Chọn loại</option>
                    <?php foreach ($orderStatus as $statusKey => $statusLabel): ?>
                        <option value="<?php echo $statusKey; ?>" <?php echo (isset($currentFilter) && $currentFilter === $statusKey) ? 'selected' : ''; ?>>
                            <?php echo $statusLabel . ' ' . ucfirst($statusKey); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Nhập thông tin tìm kiếm...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-75">Tìm kiếm</button>
            </div>
        </div>
        <?php
        if (!empty($msg) && !empty($msgType)) {
            echo showMsg($msg, $msgType);
        }
        ?>
        <table class="table table-hover text-center w-80">
            <thead class="table-light table-bordered">
                <tr>
                    <th>STT</th>
                    <th>Tên khách hàng</th>
                    <th>Thành tiền</th>
                    <th>Chi tiết</th>
                    <th>Trạng thái</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orderList as $order): ?>
                <tr>
                    <td><?php echo (!empty($order['id'])) ? $order['id']:''; ?></td>
                    <td><?php echo (!empty($order['user_name'])) ? $order['user_name']:'';?></td>
                    <td><?php echo (!empty($order['total_price'])) ? number_format($order['total_price']): 0;?><sup>đ</sup></td>
                    <td><a href="<?php echo _HOST_URL ?>/admin/order/detail?id=<?php echo (!empty($order['id'])) ? $order['id']:''; ?>" class="btn btn-success btn-sm"><i class="bi bi-pencil-square"></i></a></td>
                    <td>
                        <?php 
                            $status = (!empty($order['status'])) ? $order['status'] : '';
                            echo (!empty($orderStatus[$status])) ? $orderStatus[$status] : '';
                        ?>
                    </td>
                    <td>
                        <a href="<?php echo _HOST_URL ?>/admin/order/delete?id=<?php echo (!empty($order['id'])) ? $order['id']:''; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.getElementById('statusFilter');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                if (this.value) {
                    const url = new URL(window.location);
                    url.searchParams.set('filter', this.value);
                    window.location.href = url.toString();
                }
            });
        }
    });
</script>
<?php
layout('admin-footer');
?>