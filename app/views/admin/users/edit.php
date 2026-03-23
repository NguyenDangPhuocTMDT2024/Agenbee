<?php
$data = [
    'title' => 'Chỉnh sửa người dùng',
    'userInfo' => $userInfo
];
layout('admin-header', $data);
layout('admin-sidebar');

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
$oldData = getSessionFlash('old_data');
?>
<main class="admin-main">
    <form method="post" action="" enctype="multipart/form-data" class="container mt-4 mb-3">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <label class="avatar-upload mb-3">
                            <img id="avatarPreview" src="<?php showImg($userProfile['avatar']); ?>" class="shadow">
                            <div class="overlay">
                                <i class="bi bi-camera"></i>
                            </div>
                            <input type="file" name="avatar" id="avatarInput" hidden>
                            <input type="hidden" name="oldAvatar" value="<?php echo (!empty($userProfile['avatar'])) ?  $userProfile['avatar'] : null; ?>">
                        </label>

                        <h5 class="fw-bold"><?php echo (!empty($userProfile['name'])) ?  $userProfile['name'] : ''; ?></h5>
                        <p class="text-muted mb-2"><?php echo (!empty($userProfile['role'])) ?  $userProfile['role'] : ''; ?></p>
                        <?php
                        if (isset($userProfile['status'])):
                            if ($userProfile['status']):
                        ?>
                                <span class="badge bg-success mb-3">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger mb-3">Inactive</span>
                        <?php
                            endif;
                        endif;
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3 fw-bold">Chỉnh sửa thông tin</h5>
                        <!-- INFO -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ tên</label>
                                <input name="name" type="text" class="form-control" value="<?php echo (!empty($userProfile['name'])) ?  $userProfile['name'] : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input name="email" type="text" class="form-control" value="<?php echo (!empty($userProfile['email'])) ?  $userProfile['email'] : '@'; ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input name="phone" type="text" class="form-control" value="<?php echo (!empty($userProfile['phone'])) ?  $userProfile['phone'] : 0; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option <? if($userProfile['status']) echo 'selected' ?> value="1">Active</option>
                                    <option <? if(!$userProfile['status']) echo 'selected' ?> value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select class="form-select">
                                    <option selected>Admin</option>
                                    <option>User</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày tham gia</label>
                                <input type="text" class="form-control" value="2026-03-01" disabled>
                            </div>
                        </div>
                        <hr>
                        <!-- SHOP INFO -->
                        <h6 class="fw-bold mb-3">Thông tin shop</h6>
                        <div class="mb-3">
                            <label class="form-label">Tên shop</label>
                            <input type="text" class="form-control" value="Shop ABC">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nền tảng</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Shopee</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">TikTok Shop</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tiến độ setup (%)</label>
                            <input type="number" class="form-control" value="70" min="0" max="100">
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Lưu thay đổi
                            </button>
                            <a href="#" class="btn btn-secondary">Hủy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>
<script>
    previewImage('avatarInput', 'avatarPreview');
</script>
<?php
layout('admin-footer');
?>