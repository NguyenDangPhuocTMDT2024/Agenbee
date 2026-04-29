<?php
$data = [
    'title' => 'Packages',
    'userInfo' => $userInfo,
    'activeMenu' => 'Packages'
];
layout('admin-header', $data);
layout('admin-sidebar', $data);

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
?>
<main class="admin-main">
    <div class="container mt-4">
        <div class="mb-3">
            <a href="<?php echo _HOST_URL ?>/admin/package/create" class="btn btn-success">+ Thêm mới gói</a>
            <a href="<?php echo _HOST_URL ?>/admin/package/category_create" class="btn btn-success">+ Thêm danh mục mới</a>
        </div>
        <div class="row mb-3 justify-content-center">
            <div class="col-md-3">
                <select name="category" id="packageCategory" class="form-select">
                    <option value="all" <?php echo (isset($currentFilter) && $currentFilter === 'all') ? 'selected' : ''; ?>>Tất cả</option>
                    <?php foreach ($categoryList as $cate): ?>
                        <option value="<?php echo $cate['id']; ?>" <?php echo (isset($currentFilter) && $currentFilter === $cate['id']) ? 'selected' : ''; ?>>
                            <?php echo $cate['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Nhập thông tin tìm kiếm...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-75">Tìm kiếm</button>
            </div>
        </div>
        <?php
        if (!empty($msg) && !empty($msgType)) {
            echo showMsg($msg, $msgType);
        }
        ?>
        <table class="table table-hover text-center w-80">
            <thead class="table-light table-bordered">
                <tr>
                    <th>SKU</th>
                    <th>Tên gói</th>
                    <th>Giá</th>
                    <th>Loại gói</th>
                    <th>Trạng thái</th>
                    <th>Sửa</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packageList as $package): ?>
                    <tr class="package-row" data-bs-target="#package<?php echo $package['id']; ?>" style="cursor:pointer;">
                        <td><?php echo (!empty($package['sku'])) ? $package['sku'] : '' ?></td>
                        <td><?php echo (!empty($package['name'])) ? $package['name'] : '' ?></td>
                        <td><?php echo (!empty($package['price'])) ? number_format($package['price']) : '0' ?><sup>đ</sup></td>
                        <td><?php echo (!empty($package['category_name'])) ? $package['category_name'] : '' ?></td>
                        <td>
                            <?php
                            if (isset($package['hidden'])) {
                                if ($package['hidden'] === 1) {
                                    echo '<i class = "bi bi-eye-slash"></i>';
                                } else {
                                    echo '<i class = "bi bi-eye"></i>';
                                }
                            } ?>
                        </td>
                        <td>
                            <a href="<?php echo _HOST_URL ?>/admin/package/edit?id=<?php echo $package['id']; ?>" class="btn btn-warning btn-sm package-action" onclick="event.stopPropagation();">✏</a>
                        </td>
                        <td>
                            <a href="<?php echo _HOST_URL ?>/admin/package/delete?id=<?php echo $package['id']; ?>"
                                class="btn btn-danger btn-sm package-action" onclick="event.stopPropagation(); return confirm('Bạn có chắc muốn xóa gói <?php echo $package['name']; ?> ?')">🗑</a>
                        </td>
                    </tr>
                    <tr id="package<?php echo $package['id']; ?>" class="collapse">
                        <td colspan="7" class="p-0">
                            <div class="bg-light border-top p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        <img
                                            src="<?php echo _HOST_URL_PUBLIC; ?>/uploads/<?php echo $package['avatar'] ?>"
                                            class="img-fluid rounded shadow-sm"
                                            style="max-height:100px; object-fit:cover;">
                                    </div>
                                    <div class="<?php if($package['category_name'] == 'Add-on') { echo 'col-md-7'; } else { echo 'col-md-4'; } ?> text-start">
                                        <h6 class="mb-1 fw-bold">
                                            <?php echo $package['name']; ?>
                                        </h6>
                                        <p class="mb-1 text-muted small">
                                            <?php echo $package['short_description'] ?? 'Không có mô tả'; ?>
                                        </p>
                                        <span class="badge bg-primary">
                                            <?php echo $package['category_name']; ?>
                                        </span>
                                    </div>
                                    <?php if($package['category_name'] == 'Combo'): ?>
                                    <div class="col-md-3">
                                        <h6>Bao gồm:</h6>
                                        <div>
                                            <?php 
                                            foreach ($packageItemList as $item): 
                                                if($item['combo_id'] == $package['id']):
                                            ?>
                                                <p class="mb-1">
                                                    <?php echo $item['addon_name']; ?> (x<?php echo $item['quantity']; ?>)
                                                </p>
                                            <?php 
                                                endif; 
                                            endforeach; 
                                            ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="col-md-3 text-end">
                                        <div class="fw-bold text-danger mb-2">
                                            <?php echo number_format($package['price']); ?>đ
                                        </div>
                                        <?php if ($package['hidden'] == 1): ?>
                                            <span class="badge bg-secondary">Đang ẩn</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Đang hiển thị</span>
                                        <?php endif; ?>
                                    </div>

                                </div>

                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</main>
<script>
    document.querySelectorAll('.package-row').forEach(function(row) {
        row.addEventListener('click', function(event) {
            if (event.target.closest('.package-action')) {
                return;
            }

            const targetSelector = this.getAttribute('data-bs-target');
            const collapseElement = document.querySelector(targetSelector);

            if (!collapseElement || typeof bootstrap === 'undefined') {
                return;
            }

            const collapse = bootstrap.Collapse.getOrCreateInstance(collapseElement, {
                toggle: false
            });
            collapse.toggle();
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.getElementById('packageCategory');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                if (this.value) {
                    const url = new URL(window.location);
                    url.searchParams.set('filter', this.value);
                    window.location.href = url.toString();
                }
            });
        }
    });
</script>
<?php
layout('admin-footer');
?>