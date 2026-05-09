<?php
$data = [
    'title' => 'Quên mật khẩu'
];
layout('auth-header', $data);
layout('auth-sidebar');

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
?>

<div class="auth-form-wrapper">
    <form method="POST" action="" enctype="multipart/form-data">
        <h2>Quên mật khẩu</h2>
        <?php 
            if($msg) {
                echo showMsg($msg, $msg_type);
            }
        ?>
        
        <!-- Email input -->
        <div class="form-outline mb-4">
            <input name="email" type="text" id="email" class="form-control"
                placeholder="Nhập email khôi phục" />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'email');
        } ?>

        <div class="text-center">
            <button type="submit" class="btn btn-warning">Gửi yêu cầu</button>
            <p class="mt-3 mb-0">Quay lại 
                <a href="<?php echo _HOST_URL?>/login" class="link-danger">Đăng nhập</a>
            </p>
        </div>
    </form>
</div>

<?php
layout('auth-footer');
?>
