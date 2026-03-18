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
    <div class="container mt-4 mb-3">
        <div class="row">
            <!-- LEFT: PROFILE CARD -->
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <img src="https://via.placeholder.com/120"
                            class="rounded-circle mb-3 shadow"
                            style="width:120px; height:120px; object-fit:cover;">
                        <h5 class="fw-bold">Nguyễn Văn A</h5>
                        <p class="text-muted mb-2">Shop Owner</p>
                        <span class="badge bg-success mb-3">Active</span>
                        <div class="d-grid">
                            <button class="btn btn-primary">
                                <i class="bi bi-pencil-square"></i> Chỉnh sửa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- RIGHT: DETAILS -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3 fw-bold">Thông tin cá nhân</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted">Họ tên</label>
                                <div class="fw-semibold">Nguyễn Văn A</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted">Email</label>
                                <div class="fw-semibold">vana@gmail.com</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted">Số điện thoại</label>
                                <div class="fw-semibold">0123456789</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted">Ngày tham gia</label>
                                <div class="fw-semibold">01/01/2025</div>
                            </div>
                        </div>
                        <hr>
                        <h6 class="fw-bold mb-3">Thông tin shop</h6>
                        <div class="mb-2">
                            <label class="text-muted">Tên shop</label>
                            <div class="fw-semibold">Shop ABC</div>
                        </div>
                        <div class="mb-2">
                            <label class="text-muted">Nền tảng</label>
                            <div>
                                <span class="badge bg-danger">Shopee</span>
                                <span class="badge bg-dark">TikTok Shop</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted">Tiến độ setup</label>
                            <div class="progress mt-1" style="height:8px;">
                                <div class="progress-bar bg-success" style="width:70%"></div>
                            </div>
                            <small class="text-muted">Hoàn thành 70%</small>
                        </div>
                        <hr>
                        <h6 class="fw-bold mb-3">Hoạt động gần đây</h6>
                        <ul class="list-group">
                            <li class="list-group-item">✔ Đặt gói Setup Shopee Basic</li>
                            <li class="list-group-item">✔ Thanh toán đơn hàng #123</li>
                            <li class="list-group-item">⏳ Đang setup TikTok Shop</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
layout('admin-footer');
?>