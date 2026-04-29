<?php
$data = [
    'title' => 'Thông tin cá nhân',
    'userInfo' => $userInfo,
    'activeMenu' => 'Dashboard'
];
layout('admin-header', $data);
layout('admin-sidebar', $data);
$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
$oldData = getSessionFlash('old_data');
$errors = getSessionFlash('errors');
?>

<main class="admin-main">
    <div class="container mt-4 mb-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <form method="post" enctype="multipart/form-data" class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-header bg-body-tertiary border-0">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h4 class="mb-1 fw-bold">Thông tin tài khoản quản trị</h4>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2 me-1"></i> Xác nhận chỉnh sửa
                            </button>
                        </div>
                    </div>
                    <?php
                    if (!empty($msg) && !empty($msgType)) {
                        echo showMsg($msg, $msgType);
                    }
                    ?>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-4 bg-body-secondary p-4 text-center">
                                <img
                                    src="<?php echo (!empty($userInfo['avatar'])) ? _HOST_URL_PUBLIC . '/uploads/' . $userInfo['avatar'] : _HOST_URL_PUBLIC . '/img/defaultAvatar.png'; ?>"
                                    alt="Avatar"
                                    class="rounded-circle shadow mb-3"
                                    style="width:140px; height:140px; object-fit:cover;">

                                <h5 class="fw-bold mb-1"><?php echo !empty($userInfo['name']) ? $userInfo['name'] : ''; ?></h5>
                                <p class="text-muted mb-2"><?php echo !empty($userInfo['email']) ? $userInfo['email'] : ''; ?></p>

                                <?php if (isset($userInfo['status']) && (int)$userInfo['status'] === 1): ?>
                                    <span class="badge bg-success fs-6 px-3 py-2">Đã active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary fs-6 px-3 py-2">Chưa active</span>
                                <?php endif; ?>

                                <div class="mt-4 text-start">
                                    <div class="small text-muted mb-1">Vai trò</div>
                                    <div class="fw-semibold text-capitalize"><?php echo !empty($userInfo['role']) ? $userInfo['role'] : ''; ?></div>
                                </div>
                            </div>

                            <div class="col-md-8 p-4">
                                <h5 class="fw-bold mb-3">Thông tin cần thiết</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Họ tên</div>
                                            <input type="text" name="name" class="form-control" value="<?php echo !empty($userInfo['name']) ? $userInfo['name'] : ''; ?>">
                                            <?php if (!empty($errors['name'])): ?>
                                                <?php echo showErrors($errors, 'name'); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Email</div>
                                            <input type="email" name="email" class="form-control" value="<?php echo !empty($userInfo['email']) ? $userInfo['email'] : ''; ?>">
                                            <?php if (!empty($errors['email'])): ?>
                                                <?php echo showErrors($errors, 'email'); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Số điện thoại</div>
                                            <input type="text" name="phone" class="form-control" value="<?php echo !empty($userInfo['phone']) ? $userInfo['phone'] : ''; ?>">
                                            <?php if (!empty($errors['phone'])): ?>
                                                <?php echo showErrors($errors, 'phone'); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Vai trò</div>
                                            <div class="fw-semibold text-capitalize"><?php echo !empty($userInfo['role']) ? $userInfo['role'] : ''; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Ngày tạo</div>
                                            <div class="fw-semibold"><?php echo !empty($userInfo['created_at']) ? $userInfo['created_at'] : ''; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Cập nhật gần nhất</div>
                                            <div class="fw-semibold"><?php echo !empty($userInfo['updated_at']) ? $userInfo['updated_at'] : ''; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="border rounded-3 p-3 bg-white">
                                            <div class="text-muted small mb-1">Trạng thái tài khoản</div>
                                            <div>
                                                <?php if (isset($userInfo['status']) && (int)$userInfo['status'] === 1): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php layout('admin-footer'); ?>