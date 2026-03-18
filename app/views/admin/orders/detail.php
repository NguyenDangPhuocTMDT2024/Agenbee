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
    <div class="container mt-4">
        <!-- Header -->
        <div class="mb-3">
            <h4 class="fw-bold">Chi tiết đơn hàng #123</h4>
            <p class="text-muted mb-0">Khách hàng: Nguyễn Văn A</p>
        </div>
        <!-- Table -->
        <table class="table table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên gói</th>
                    <th>Giá</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <!-- ===== PACKAGE 1 ===== -->
                <tr data-bs-toggle="collapse" data-bs-target="#package1" style="cursor:pointer;">
                    <td>1</td>
                    <td class="text-start">Setup Shopee Basic</td>
                    <td class="text-danger fw-bold">500,000đ</td>
                    <td><span class="badge bg-warning text-dark">Đang xử lý</span></td>
                    <td><i class="bi bi-chevron-down"></i></td>
                </tr>
                <tr id="package1" class="collapse">
                    <td colspan="5" class="p-0">
                        <div class="bg-light border-top p-3">
                            <div class="row align-items-center">

                                <div class="col-md-2 text-center">
                                    <img src="https://via.placeholder.com/100"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height:100px;">
                                </div>
                                <div class="col-md-7 text-start">
                                    <h6 class="fw-bold mb-1">Setup Shopee Basic</h6>
                                    <p class="text-muted small mb-2">
                                        Setup gian hàng Shopee cơ bản, tối ưu mô tả sản phẩm.
                                    </p>
                                    <div class="small">
                                        <b>Setup info:</b><br>
                                        - Đã tạo shop<br>
                                        - Đã upload 10 sản phẩm<br>
                                        - Đang setup banner
                                    </div>
                                    <div class="progress mt-2" style="height:6px;">
                                        <div class="progress-bar bg-success" style="width: 60%"></div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="fw-bold text-danger mb-2">500,000đ</div>
                                    <span class="badge bg-warning text-dark">⏳ Đang làm</span>
                                </div>

                            </div>
                        </div>
                    </td>
                </tr>
                <!-- ===== PACKAGE 2 ===== -->
                <tr data-bs-toggle="collapse" data-bs-target="#package2" style="cursor:pointer;">
                    <td>2</td>
                    <td class="text-start">Setup TikTok Shop Pro</td>
                    <td class="text-danger fw-bold">1,200,000đ</td>
                    <td><span class="badge bg-success">Hoàn thành</span></td>
                    <td><i class="bi bi-chevron-down"></i></td>
                </tr>
                <tr id="package2" class="collapse">
                    <td colspan="5" class="p-0">
                        <div class="bg-light border-top p-3">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <img src="https://via.placeholder.com/100"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height:100px;">
                                </div>
                                <div class="col-md-7 text-start">
                                    <h6 class="fw-bold mb-1">Setup TikTok Shop Pro</h6>
                                    <p class="text-muted small mb-2">
                                        Setup gian hàng TikTok Shop + tối ưu video bán hàng.
                                    </p>
                                    <div class="small">
                                        <b>Setup info:</b><br>
                                        - Đã tạo shop<br>
                                        - Đã upload sản phẩm<br>
                                        - Đã chạy quảng cáo
                                    </div>
                                    <div class="progress mt-2" style="height:6px;">
                                        <div class="progress-bar bg-success" style="width: 100%"></div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="fw-bold text-danger mb-2">1,200,000đ</div>
                                    <span class="badge bg-success">✔ Hoàn thành</span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="2" class="text-end">Tổng tiền:</td>
                    <td class="text-danger">1,700,000đ</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</main>
<?php
layout('admin-footer');
?>