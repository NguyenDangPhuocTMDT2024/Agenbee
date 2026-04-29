<?php
$data = [
    'title' => 'Thông tin người dùng',
    'activeMenu' => 'Users',
    'userInfo' => $userInfo
];
layout('admin-header', $data);
layout('admin-sidebar', $data);

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');

$orderCount = !empty($userOrders) ? count($userOrders) : 0;
?>
<main class="admin-main">
    <div class="container mt-4 mb-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-header bg-body-tertiary border-0">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h4 class="mb-1 fw-bold">Thông tin tài khoản</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-4 bg-body-secondary p-4 text-center">
                                <img
                                    src="<?php echo (!empty($userProfile['avatar'])) ? _HOST_URL_PUBLIC . '/uploads/' . $userProfile['avatar'] : _HOST_URL_PUBLIC . '/img/defaultAvatar.png'; ?>"
                                    alt="Avatar"
                                    class="rounded-circle shadow mb-3"
                                    style="width:140px; height:140px; object-fit:cover;">

                                <h5 class="fw-bold mb-1"><?php echo !empty($userProfile['name']) ? $userProfile['name'] : ''; ?></h5>
                                <p class="text-muted mb-2"><?php echo !empty($userProfile['email']) ? $userProfile['email'] : ''; ?></p>

                                <?php if (isset($userProfile['status']) && (int)$userProfile['status'] === 1): ?>
                                    <span class="badge bg-success fs-6 px-3 py-2">Đã active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary fs-6 px-3 py-2">Chưa active</span>
                                <?php endif; ?>

                                <div class="mt-4 text-start">
                                    <div class="small text-muted mb-1">Vai trò</div>
                                    <div class="fw-semibold text-capitalize"><?php echo !empty($userProfile['role']) ? $userProfile['role'] : ''; ?></div>
                                </div>
                            </div>

                            <div class="col-md-8 p-4">
                                <h5 class="fw-bold mb-3">Thông tin cần thiết</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Họ tên</div>
                                            <div class="fw-semibold"><?php echo !empty($userProfile['name']) ? $userProfile['name'] : ''; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Email</div>
                                            <div class="fw-semibold"><?php echo !empty($userProfile['email']) ? $userProfile['email'] : ''; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Số điện thoại</div>
                                            <div class="fw-semibold text-capitalize"><?php echo !empty($userProfile['phone']) ? $userProfile['phone'] : ''; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Vai trò</div>
                                            <div class="fw-semibold text-capitalize"><?php echo !empty($userProfile['role']) ? $userProfile['role'] : ''; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Ngày tạo</div>
                                            <div class="fw-semibold"><?php echo !empty($userProfile['created_at']) ? $userProfile['created_at'] : ''; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100 bg-white">
                                            <div class="text-muted small mb-1">Cập nhật gần nhất</div>
                                            <div class="fw-semibold"><?php echo !empty($userProfile['updated_at']) ? $userProfile['updated_at'] : ''; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 bg-white">
                                            <div class="text-muted small mb-1">Trạng thái tài khoản</div>
                                            <div class="btn-group btn-group-sm flex-wrap" role="group" aria-label="Quick status actions">
                                                <a href="<?php echo _HOST_URL . '/admin/user/profile/change?user_id=' . $userProfile['id'] . '&status=inactive'; ?>"
                                                   class="btn <?php echo (isset($userProfile['status']) && (int)$userProfile['status'] === 0) ? 'btn-secondary' : 'btn-outline-secondary'; ?>">
                                                    Inactive
                                                </a>
                                                <a href="<?php echo _HOST_URL . '/admin/user/profile/change?user_id=' . $userProfile['id'] . '&status=active'; ?>"
                                                   class="btn <?php echo (isset($userProfile['status']) && (int)$userProfile['status'] === 1) ? 'btn-success' : 'btn-outline-success'; ?>">
                                                    Active
                                                </a>
                                                <a href="<?php echo _HOST_URL . '/admin/user/profile/change?user_id=' . $userProfile['id'] . '&status=banned'; ?>"
                                                   class="btn <?php echo (isset($userProfile['status']) && (int)$userProfile['status'] === 2) ? 'btn-danger' : 'btn-outline-danger'; ?>">
                                                    Banned
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 bg-white">
                                            <div class="text-muted small mb-1">Số đơn hàng</div>
                                            <div class="fw-semibold text-capitalize"><?php echo $orderCount; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main> 
<?php
layout('admin-footer');
?>