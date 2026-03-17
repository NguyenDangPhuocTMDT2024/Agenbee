<?php
$data = [
    'title' => 'Kích hoạt tài khoản'
];
layout('auth-header', $data);
layout('auth-sidebar');

$filteredData = filterData('get');
$msg = '';
if (!empty($filteredData['token'])) {
    $token = $filteredData['token'];
    $checkToken = $userModel->getUserByActiveToken($token);
    if (!empty($checkToken)) {
        $data = [
            'active_token' => null,
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'id' => $checkToken['id']
        ];
        $userModel->updateActiveToken($data['id'], $data['active_token'], $data['status'], $data['updated_at']);
        $msg = 'Kích hoạt tài khoản thành công!';
    } else {
        $msg = 'Đường link không hợp lệ hoặc đã hết hạn!';
    }
} else {
    $msg = 'Đường link không hợp lệ hoặc đã hết hạn!';
}
?>

<div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
    <form>
        <h2 class="text-center mb-4"><?php echo $msg; ?></h2>
        <?php if ($msg === 'Kích hoạt tài khoản thành công!'): ?>
            <div class="text-center text-lg-start mt-4 pt-2">
                <p class="small fw-bold mt-2 pt-1 mb-0">Quay lại <a href="<?php echo _HOST_URL; ?>/login"
                        class="link-success">đăng nhập</a></p>
            </div>
        <?php endif; ?>
    </form>
</div>
<?php
layout('auth-footer');
?>