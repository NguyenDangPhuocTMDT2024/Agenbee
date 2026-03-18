<?php
$data = [
    'title' => 'Users',
    'userInfo' => $userInfo
];
layout('admin-header', $data);
layout('admin-sidebar');

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
?>
<main class="admin-main">
    <div class="container mt-4">
        <div class="mb-3">
            <a href="<?php echo _HOST_URL ?>/admin/user/create" class="btn btn-success">+ Thêm người dùng</a>
        </div>
        <div class="row mb-3 justify-content-center">
            <div class="col-md-3">
                <select name="role" class="form-select" id="userRole">
                    <option value="">Chọn loại</option>
                    <?php foreach ($userList as $user): ?>
                        <option value="<?php echo $user['role']; ?>"><?php echo $cate['role']; ?></option>
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
                    <th>Ảnh đại diện</th>
                    <th>Tên người dùng</th>
                    <th>Địa chỉ mail</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Chi tiết</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userList as $user): ?>
                    <tr>
                        <td>
                            <img src="<?php echo (!empty($user['avatar'])) ? _HOST_URL_PUBLIC .'/uploads/'.$user['avatar']: _HOST_URL_PUBLIC . '/img/defaultAvatar.png' ?>" 
                            class="user-image rounded-circle shadow" style="max-width: 30px;"
                            alt="User Image">
                        </td>
                        <td><?php echo (!empty($user['name'])) ? $user['name'] : '' ?></td>
                        <td><?php echo (!empty($user['email'])) ? $user['email'] : '' ?></td>
                        <td><?php echo (!empty($user['role'])) ? $user['role'] : 'guest' ?></td>
                        <td>
                            <?php
                            if (isset($user['status'])) {
                                if ($user['status'] === 1) {
                                    echo '<i class = "bi bi-eye"></i>';
                                } else {
                                    echo '<i class = "bi bi-eye-slash"></i>';
                                }
                            } ?>
                        </td>
                        <td>
                            <a href="<?php echo _HOST_URL ?>/admin/user/profile?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo _HOST_URL ?>/admin/user/delete?id=<?php echo $user['id']; ?>"
                                class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa người dùng <?php echo $package['name']; ?> ?')">🗑</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</main>
<?php
layout('admin-footer');
?>