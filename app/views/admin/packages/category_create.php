<?php
$data = [
    'title' => 'Category',
    'userInfo' => $userInfo
];
layout('admin-header', $data);
layout('admin-sidebar');

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
$oldData = getSessionFlash('old_data');
?>
<main class="container mt-4 mb-4">
    <form method="post" enctype="multipart/form-data" class="w-8">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="mb-3">Thông tin danh mục</h5>
                        <?php
                        if (!empty($msg) && !empty($msgType)) {
                            echo showMsg($msg, $msgType);
                        }
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Tên danh mục</label>
                            <input type="text" name="name" class="form-control" value = "<?php if(!empty($oldData['name'])) echo $oldData['name'];?>">
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'name');
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
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success w-50">+ Thêm danh mục</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>
<?php
layout('admin-footer');
?>