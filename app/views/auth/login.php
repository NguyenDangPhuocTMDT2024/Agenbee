<?php
$data = [
    'title' => 'Đăng nhập'
];
layout('auth-header', $data);
layout('auth-sidebar');

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
?>

<div class="auth-form-wrapper">
    <form method="POST" action="" enctype="multipart/form-data">
        <h2>Đăng nhập</h2>
        <?php 
            if(!empty($msg)) {
                echo showMsg($msg, $msgType);
            }
        ?>
        
        <!-- Email input -->
        <div class="form-outline mb-4">
            <input name="email" type="text" id="email" class="form-control"
                placeholder="Nhập email của bạn" />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'email');
        } ?>

        <!-- Password input -->
        <div class="form-outline mb-3">
            <input name="password" type="password" id="password" class="form-control"
                placeholder="Nhập mật khẩu" />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'password');
        } ?>

        <div class="mb-4">
            <a href="<?php echo _HOST_URL; ?>/forgot" class="text-body">
                <small>Quên mật khẩu?</small>
            </a>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-warning">Đăng nhập</button>
            <p class="mt-3 mb-0">Bạn chưa có tài khoản? 
                <a href="<?php echo _HOST_URL; ?>/register" class="link-danger">Đăng ký</a>
            </p>
        </div>
    </form>
</div>

<?php
layout('auth-footer');
?>