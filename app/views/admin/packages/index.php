<?php
$data = [
    'title' => 'Packages',
    'userInfo' => $userInfo
];
layout('admin-header', $data);
layout('admin-sidebar');

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
?>
<main class="admin-main">
    <div class="container mt-4">
        <div class="mb-3">
            <a href="<?php echo _HOST_URL ?>/admin/package/create" class="btn btn-success">+ Thêm mới gói</a>
            <a href="<?php echo _HOST_URL ?>/admin/package/category_create" class="btn btn-success">+ Thêm danh mục mới</a>
        </div>
        <div class="row mb-3 justify-content-center">
            <div class="col-md-3">
                <select name="category" class="form-select" id="packageType">
                    <option value="">Chọn loại</option>
                    <?php foreach ($categoryList as $cate): ?>
                        <option value="<?php echo $cate['id']; ?>"><?php echo $cate['name']; ?></option>
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
                    <th>Tên gói</th>
                    <th>Giá</th>
                    <th>Loại gói</th>
                    <th>Trạng thái</th>
                    <th>Sửa</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packageList as $package): ?>
                    <tr>
                        <td><?php echo (!empty($package['id'])) ? $package['id'] : '' ?></td>
                        <td><?php echo (!empty($package['name'])) ? $package['name'] : '' ?></td>
                        <td><?php echo (!empty($package['price'])) ? $package['price'] : '0' ?><sup>đ</sup></td>
                        <td><?php echo (!empty($package['category_name'])) ? $package['category_name'] : '' ?></td>
                        <td>
                            <?php
                            if (isset($package['hidden'])) {
                                if ($package['hidden'] === 1) {
                                    echo '<i class = "bi bi-eye-slash"></i>';
                                } else {
                                    echo '<i class = "bi bi-eye"></i>';
                                }
                            } ?>
                        </td>
                        <td>
                            <a href="<?php echo _HOST_URL ?>/admin/package/edit?id=<?php echo $package['id']; ?>" class="btn btn-warning btn-sm">✏</a>
                        </td>
                        <td>
                            <a href="<?php echo _HOST_URL ?>/admin/package/delete?id=<?php echo $package['id']; ?>"
                                class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa gói <?php echo $package['name']; ?> ?')">🗑</a>
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