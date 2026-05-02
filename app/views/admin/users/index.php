<?php
$data = [
    'title' => 'Quản lý người dùng',
    'userInfo' => $userInfo,
    'activeMenu' => 'Users'
];
layout('admin-header', $data);
layout('admin-sidebar', $data);

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');

$userRoleList = ['admin', 'user'];
?>
<main class="admin-main">
    <div class="container mt-4">
        <div class="mb-3">
            <a href="<?php echo _HOST_URL ?>/admin/user/create" class="btn btn-success">+ Thêm người dùng</a>
        </div>
        <div class="row mb-3 justify-content-center">
            <div class="col-md-3">
                <select name="role" class="form-select" id="userRole">
                    <option value="all" <?php echo (isset($currentFilter) && $currentFilter === 'all') ? 'selected' : ''; ?>>Tất cả</option>
                    <?php foreach ($userRoleList as $role): ?>
                        <option value="<?php echo $role; ?>" <?php echo (isset($currentFilter) && $currentFilter === $role) ? 'selected' : ''; ?>>
                            <?php echo $role; ?>
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
                            <img src="<?php echo (!empty($user['avatar'])) ? _HOST_URL_PUBLIC . '/uploads/' . $user['avatar'] : _HOST_URL_PUBLIC . '/img/defaultAvatar.png' ?>"
                                class="user-image rounded-circle shadow" style="max-width: 30px;"
                                alt="User Image">
                        </td>
                        <td><?php echo (!empty($user['name'])) ? $user['name'] : '' ?></td>
                        <td><?php echo (!empty($user['email'])) ? $user['email'] : '' ?></td>
                        <td><?php echo (!empty($user['role'])) ? $user['role'] : 'guest' ?></td>
                        <td>
                            <?php
                            if (isset($user['login_token']) && !empty($user['login_token'])) {
                                echo '<i class="bi bi-circle-fill" style="color: #28a745; font-size: 0.8rem;"></i>';
                            } else {
                                echo '<i class="bi bi-circle-fill" style="color: #adb5bd; font-size: 0.8rem;"></i>';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo _HOST_URL ?>/admin/user/profile?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo _HOST_URL ?>/admin/user/delete?id=<?php echo $user['id']; ?>"
                                class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa người dùng <?php echo $user['name']; ?> ?')">🗑</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.getElementById('userRole');
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