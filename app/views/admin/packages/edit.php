<?php
$data = [
    'title' => 'Packages',
    'userInfo' => $userInfo
];
$getData = filterData('get');
if (!empty($getData['id'])) {
    $id = $getData['id'];
    $packageInfo = $packageModel->getPackagesByID($id);
    if (empty($packageInfo)) {
        setSessionFlash('msg', 'Gói không tồn tại!');
        setSessionFlash('msg_type', 'danger');
        redirect('/admin/package');
    }
}else{
    setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại!');
    setSessionFlash('msg_type', 'danger');
    redirect('/admin/package');
}
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
                        <input type="hidden" name="id" value="<?php echo $packageInfo['id']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Tên gói</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $packageInfo['name']; ?>">
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'name');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ảnh đại diện</label>
                            <input type="file" name="avatar" class="form-control">
                            <input type="hidden" name="old_avatar" value="<?php echo $packageInfo['avatar']; ?>">
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'avatar');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control"><?php echo $packageInfo['description']; ?></textarea>
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'description');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá</label>
                            <input type="number" name="price" class="form-control" value="<?php echo $packageInfo['price']; ?>">
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
                                <?php foreach ($categoryList as $cate):
                                    if ($cate['id'] == $packageInfo['category_id']): ?>
                                        <option value="<?php echo $cate['id']; ?>" selected><?php echo $cate['name']; ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo $cate['id']; ?>"><?php echo $cate['name']; ?></option>
                                <?php
                                    endif;
                                endforeach;
                                ?>
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
                                <input class="form-check-input" type="radio" name="hidden" value="0" <?php if ($packageInfo['hidden'] == 0) echo 'checked' ?>>
                                <label class="form-check-label">Hiển thị</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="hidden" value="1" <?php if ($packageInfo['hidden'] == 1) echo 'checked' ?>>
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
                        <button type="submit" class="btn btn-success w-50">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>
<?php
layout('admin-footer');
?>