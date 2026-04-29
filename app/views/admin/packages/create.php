<?php
$data = [
    'title' => 'Packages',
    'userInfo' => $userInfo,
    'activeMenu' => 'Packages',
];
layout('admin-header', $data);
layout('admin-sidebar', $data);

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
$oldData = getSessionFlash('old_data');
?>
<style>
    .package-items-table .item-name {
        font-size: 1rem;
        font-weight: 600;
    }

    .package-items-table .item-qty-input {
        max-width: 80px;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    #chooseSubPackage {
        display: flex;
        flex-direction: column;
        height: 100%;
        max-height: 600px;
        overflow-y: auto;
        padding-right: 8px;
    }

    #chooseSubPackage .package-items-table {
        flex: 1;
        overflow-y: auto;
    }

    #chooseSubPackage::-webkit-scrollbar {
        width: 8px;
    }

    #chooseSubPackage::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #chooseSubPackage::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    #chooseSubPackage::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
<main class="container mt-4 mb-4">
    <form method="post" enctype="multipart/form-data" class="w-8">
        <div class="row justify-content-center ms-1 me-1">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <h5 class="mb-3">Thông tin gói</h5>
                            <?php
                            if (!empty($msg) && !empty($msgType)) {
                                echo showMsg($msg, $msgType);
                            }
                            ?>
                            <div class="mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku" class="form-control" value="<?php if (!empty($oldData['sku'])) echo $oldData['sku']; ?>">
                                <?php
                                if (!empty($errors)) {
                                    echo showErrors($errors, 'sku');
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tên gói</label>
                                <input type="text" name="name" class="form-control" value="<?php if (!empty($oldData['name'])) echo $oldData['name']; ?>">
                                <?php
                                if (!empty($errors)) {
                                    echo showErrors($errors, 'name');
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" name="avatar" class="form-control">
                                <?php
                                if (!empty($errors)) {
                                    echo showErrors($errors, 'avatar');
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại gói</label>
                                <select name="type" class="form-select">
                                    <option value="null">Chọn danh mục</option>
                                    <option value="Setup">Setup</option>
                                    <option value="Product">Product</option>
                                    <option value="Layout">Layout</option>
                                    <option value="Design">Design</option>
                                    <option value="Content">Content</option>
                                    <option value="Restart">Restart</option>
                                    <option value="Support">Support</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả ngắn</label>
                                <textarea name="short_description" class="form-control"><?php if (!empty($oldData['short_description'])) echo $oldData['short_description']; ?></textarea>
                                <?php
                                if (!empty($errors)) {
                                    echo showErrors($errors, 'short_description');
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả dài</label>
                                <textarea name="long_description" class="form-control"><?php if (!empty($oldData['long_description'])) echo $oldData['long_description']; ?></textarea>
                                <?php
                                if (!empty($errors)) {
                                    echo showErrors($errors, 'long_description');
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giá</label>
                                <input type="number" name="price" class="form-control" value="<?php if (!empty($oldData['price'])) echo $oldData['price']; ?>">
                                <?php
                                if (!empty($errors)) {
                                    echo showErrors($errors, 'price');
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Đơn vị</label><br>
                                <input type="radio" class="form-check-input" name="unit" value="package" <?php echo (!empty($oldData['unit']) && $oldData['unit'] === 'package') ? 'checked' : ''; ?>> Gói
                                <input type="radio" class="form-check-input" name="unit" value="product" <?php echo (!empty($oldData['unit']) && $oldData['unit'] === 'product') ? 'checked' : ''; ?>> Sản phẩm
                                <input type="radio" class="form-check-input" name="unit" value="item" <?php echo (!empty($oldData['unit']) && $oldData['unit'] === 'item') ? 'checked' : ''; ?>> Cái
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại gói</label>
                                <select name="category" class="form-select" id="packageType" onchange="toggleChooseSubPackage()">
                                    <option value="">Chọn loại</option>
                                    <?php foreach ($categoryList as $cate): ?>
                                        <option value="<?php echo $cate['id']; ?>" <?php echo (!empty($oldData['category']) && (string)$oldData['category'] === (string)$cate['id']) ? 'selected' : ''; ?>><?php echo $cate['name']; ?></option>
                                    <?php endforeach; ?>
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
                                    <input class="form-check-input" type="radio" name="hidden" value="0" checked>
                                    <label class="form-check-label">Hiển thị</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="hidden" value="1">
                                    <label class="form-check-label">Ẩn</label>
                                </div>
                                <?php
                                if (!empty($errors)) {
                                    echo showErrors($errors, 'hidden');
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4 ms-auto" id="chooseSubPackage">
                            <h5 class="mb-3">Các gói con</h5>
                            <?php
                            if (!empty($errors) && isset($errors['items'])) {
                                echo showErrors($errors, 'items');
                            }
                            ?>
                            <table class="table package-items-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Chọn</th>
                                        <th scope="col">Tên gói con</th>
                                        <th scope="col">Số lượng</th>
                                    </tr>
                                </thead>
                                <?php foreach ($addOnPackageList as $item): ?>
                                    <tbody id="itemTableBody">
                                        <tr>
                                            <td><input type="checkbox" name="items[<?php echo $item['id']; ?>][selected]" class="form-check-input"></td>
                                            <td class="item-name"><?php echo $item['name']; ?></td>
                                            <td><input type="number" name="items[<?php echo $item['id']; ?>][quantity]" class="form-control form-control-sm item-qty-input" value="1" min="1"></td>
                                        </tr>
                                    </tbody>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-success w-50">+ Thêm gói</button>
                </div>
            </div>
        </div>
    </form>
</main>
<script>
    //khi chọn combo thì hiện ra bảng chọn gói con tương ứng
    const packageType = document.getElementById('packageType');
    const chooseSubPackage = document.getElementById('chooseSubPackage');

    function toggleChooseSubPackage() {
        if (!packageType || !chooseSubPackage) return;

        const selectedCategory = packageType.options[packageType.selectedIndex];
        const selectedCategoryName = selectedCategory ? selectedCategory.textContent.trim().toLowerCase() : '';

        if (selectedCategoryName === 'combo') {
            chooseSubPackage.style.display = 'block';
        } else {
            chooseSubPackage.style.display = 'none';
        }
    }

    if (packageType) {
        packageType.addEventListener('change', toggleChooseSubPackage);
        window.addEventListener('pageshow', toggleChooseSubPackage);
        toggleChooseSubPackage();
        setTimeout(toggleChooseSubPackage, 0);
        setTimeout(toggleChooseSubPackage, 150);
    }
</script>
<?php
layout('admin-footer');
?>