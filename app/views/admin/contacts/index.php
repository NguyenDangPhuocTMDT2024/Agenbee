<?php
$data = [
    'title' => 'Danh sách liên hệ',
    'userInfo' => $userInfo,
    'activeMenu' => 'Contacts'
];
layout('admin-header', $data);
layout('admin-sidebar', $data);
$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
?>
<style>
    .btn-toggle {
        display: inline-flex;
        vertical-align: middle;
    }

    .btn-toggle .btn {
        border-radius: 0;
        padding: .375rem .6rem;
        min-width: 68px;
    }

    .btn-toggle .btn:first-child {
        border-top-left-radius: 25%;
        border-bottom-left-radius: 25%;
    }

    .btn-toggle .btn:last-child {
        border-top-right-radius: 25%;
        border-bottom-right-radius: 25%;
    }

    /* Disabled button styling - show as selected/active state */
    .btn-toggle .btn:disabled {
        pointer-events: none;
        cursor: not-allowed;
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
    }

    .btn-toggle .btn-outline-success:disabled {
        background-color: #0fdc4d !important;
        border-color: #032848 !important;
    }

    .btn-toggle .btn-outline-warning:disabled {
        background-color: #fd7e14 !important;
        border-color: #fd7e14 !important;
    }

    .btn-toggle .btn-outline-danger:disabled {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .btn-toggle .btn:disabled:hover {
        color: #fff !important;
    }
</style>
<main class="admin-main mt-3 mb-3 ms-3 me-3 flex-grow-1 bg-white">
    <div class="row mt-3 mb-3 justify-content-center">
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
                <th>ID</th>
                <th>Tên liên hệ</th>
                <th>Số điện thoại</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
                <th>Trạng thái</th>
                <th>Chi tiết</th>
                <th>Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contactList as $contact): ?>
                <tr>
                    <td><?php echo $contact['id']; ?></td>
                    <td><?php echo $contact['name']; ?></td>
                    <td><?php echo $contact['phone']; ?></td>
                    <td><?php echo $contact['created_at']; ?></td>
                    <td><?php echo $contact['updated_at']; ?></td>
                    <td>
                        <form method="post" enctype="multipart/form-data" class="btn-group btn-toggle" role="group" aria-label="Contact status">
                            <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                            <button type="submit" class="btn btn-outline-success" name="status" value="new" <?php echo ($contact['status'] === 'new') ? 'disabled' : ''; ?>>New</button>
                            <button type="submit" class="btn btn-outline-warning" name="status" value="contacted" <?php echo ($contact['status'] === 'contacted') ? 'disabled' : ''; ?>>Contacted</button>
                            <button type="submit" class="btn btn-outline-danger" name="status" value="closed" <?php echo ($contact['status'] === 'closed') ? 'disabled' : ''; ?>>Closed</button>
                        </form>
                    </td>
                    <td>
                        <a href="<?php echo _HOST_URL . '/admin/contact/detail?contact_id=' . $contact['id']; ?>" class="btn btn-info btn-sm">Chi tiết</a>
                    </td>
                    <td>
                        <a href="<?php echo _HOST_URL . '/admin/contact/delete?contact_id=' . $contact['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa liên hệ này không?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php
layout('admin-footer');
?>