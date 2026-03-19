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
    <div class="container mt-4 mb-3">
        <div class="row">
            
            <!-- LEFT: AVATAR + ACTION -->
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <img src="https://via.placeholder.com/120"
                            class="rounded-circle mb-3 shadow"
                            style="width:120px; height:120px; object-fit:cover;">

                        <div class="mb-3">
                            <input type="file" class="form-control">
                        </div>

                        <h5 class="fw-bold">Nguyễn Văn A</h5>
                        <p class="text-muted mb-2">Admin</p>

                        <span class="badge bg-success mb-3">Active</span>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary flex-fill">
                                <i class="bi bi-save"></i> Lưu
                            </button>
                            <a href="#" class="btn btn-secondary flex-fill">
                                Hủy
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: FORM -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3 fw-bold">Chỉnh sửa thông tin</h5>

                        <form>
                            <!-- INFO -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ tên</label>
                                    <input type="text" class="form-control" value="Nguyễn Văn A">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="vana@gmail.com">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" value="0123456789">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Trạng thái</label>
                                    <select class="form-select">
                                        <option selected>Active</option>
                                        <option>Inactive</option>
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

                            <!-- ACTION -->
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Lưu thay đổi
                                </button>
                                <a href="#" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
<?php
layout('admin-footer');
?>