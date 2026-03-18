<?php
$data = [
    'title' => 'Packages',
    'userInfo' => $userInfo
];
layout('admin-header', $data);
layout('admin-sidebar');

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
?>
<main class="container mt-4 mb-4">
    <form method="post" enctype="multipart/form-data" class="w-8">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="mb-3">Thông tin gói</h5>
                        <?php
                        if (!empty($msg) && !empty($msgType)) {
                            echo showMsg($msg, $msgType);
                        }
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Tên gói</label>
                            <input type="text" name="name" class="form-control" value = "<?php if(!empty($oldData['name'])) echo $oldData['name'];?>">
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'name');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ảnh đại diện</label>
                            <input type="file" name="avatar" class="form-control">
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'avatar');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" value = "<?php if(!empty($oldData['description'])) echo $oldData['description'];?>"></textarea>
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'description');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá</label>
                            <input type="number" name="price" class="form-control" value = "<?php if(!empty($oldData['price'])) echo $oldData['price'];?>">
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'price');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loại gói</label>
                            <select name="category" class="form-select" id="packageType">
                                <option value="">Chọn loại</option>
                                <?php foreach ($categoryList as $cate): ?>
                                    <option value="<?php echo $cate['id'];?>"><?php echo $cate['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'category');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Trạng thái</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hidden" value= "0" checked>
                                <label class="form-check-label">Hiển thị</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hidden" value= "1">
                                <label class="form-check-label">Ẩn</label>
                            </div>
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'hidden');
                            }
                            ?>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success w-50">+ Thêm gói</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>
<?php
layout('admin-footer');
?>