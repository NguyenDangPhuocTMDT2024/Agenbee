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
        $userModel->updateActiveTokenByID($data);
        $msg = 'Kích hoạt tài khoản thành công!';
    } else {
        $msg = 'Đường link không hợp lệ hoặc đã hết hạn!';
    }
} else {
    $msg = 'Đường link không hợp lệ hoặc đã hết hạn!';
}
?>

<div class="auth-form-wrapper">
    <div class="text-center">
        <h2><?php echo $msg; ?></h2>
        <?php if ($msg === 'Kích hoạt tài khoản thành công!'): ?>
            <div class="mt-4 pt-2">
                <p class="mb-0">Quay lại 
                    <a href="<?php echo _HOST_URL; ?>/login" class="link-success">đăng nhập</a>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
layout('auth-footer');
?>