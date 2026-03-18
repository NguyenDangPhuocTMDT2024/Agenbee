<?php
$data = [
    'title' => 'Orders',
    'userInfo' => $userInfo
];
layout('admin-header', $data);
layout('admin-sidebar');

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
?>
<main class="admin-main">
    <div class="container mt-4">
        <div class="row mb-3 justify-content-center">
            <div class="col-md-3">
                <select name="category" class="form-select" id="packageType">
                    <option value="">Chọn loại</option>
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
                </tr>
            </thead>
            <tbody>
                <?php foreach($orderList as $order): ?>
                <tr>
                    <td><?php echo (!empty($order['id'])) ? $order['id']:''; ?></td>
                    <td><?php echo (!empty($order['name'])) ? $order['name']:'';?></td>
                    <td><?php echo (!empty($order['total_price'])) ? number_format($order['total_price']): 0;?><sup>đ</sup></td>
                    <td><a href="<?php echo _HOST_URL ?>/admin/order/detail?id=<?php echo (!empty($order['id'])) ? $order['id']:''; ?>" class="btn btn-success btn-sm"><i class="bi bi-pencil-square"></i></a></td>
                    <td>
                        <?php if(!empty($order['status']) && $order['status'] === 'completed'): ?>
                            <i class="bi bi-check-circle-fill text-success"></i>
                        <?php elseif(!empty($order['status']) && $order['status'] === 'cancelled'): ?> 
                            <i class="bi bi-x-circle-fill text-danger"></i>
                        <?php elseif(!empty($order['status']) && $order['status'] === 'pending'): ?>
                            <i class="bi bi-hourglass-split text-danger"></i>
                        <?php else: ?>
                            <i class="bi bi-hourglass-split text-success"></i>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</main>
<script>

</script>
<?php
layout('admin-footer');
?>