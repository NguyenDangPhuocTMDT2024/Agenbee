<?php
$data = [
    'title' => 'Tạo tài khoản người dùng',
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
                        <h5 class="mb-3">Thông tin người dùng</h5>
                        <?php
                        if (!empty($msg) && !empty($msgType)) {
                            echo showMsg($msg, $msgType);
                        }
                        ?>
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="name" class="form-control" value = "<?php if(!empty($oldData['name'])) echo $oldData['name'];?>">
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'name');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" name="email" class="form-control" value = "<?php if(!empty($oldData['email'])) echo $oldData['email'];?>">
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'email');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value = "<?php if(!empty($oldData['phone'])) echo $oldData['phone'];?>">
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'phone');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control">
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'password');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" class="form-control">
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'password_confirmation');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Vai trò</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="role" value= "admin">
                                <label class="form-check-label">Admin</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="role" value= "user" checked>
                                <label class="form-check-label">User</label>
                            </div>
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'role');
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Trạng thái</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value= "1" checked>
                                <label class="form-check-label">Đã kích hoạt</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value= "0">
                                <label class="form-check-label">Chưa kích hoạt</label>
                            </div>
                            <?php
                            if (!empty($errors)) {
                                echo showErrors($errors, 'status');
                            }
                            ?>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success w-50">+ Thêm người dùng</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<?php
layout('admin-footer');
?>