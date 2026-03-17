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

<div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
    <form method="POST" action="" enctype="multipart/form-data">
        <h2 class="text-center mb-4 text-uppercase">Đăng nhập</h2>
        <?php 
            if(!empty($msg)) {
                echo showMsg($msg, $msgType);
            }
        ?>
        <!-- Email input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input name="email" type="text" id="form3Example3" class="form-control form-control-lg"
                placeholder="Nhập email của bạn" />
        </div>
        <div class = "errors">
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'email');
            }
        ?>
        </div>

        <!-- Password input -->
        <div data-mdb-input-init class="form-outline mb-3">
            <input name="password" type="password" id="form3Example4" class="form-control form-control-lg"
                placeholder="Nhập mật khẩu" />
        </div>
        <div class = "errors">
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'password');
            }
        ?>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="<?php echo _HOST_URL; ?>/forgot" class="text-body">Quên mật khẩu?</a>
        </div>

        <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng nhập</button>
            <p class="small fw-bold mt-2 pt-1 mb-0">Bạn chưa có tài khoản? <a href="<?php echo _HOST_URL; ?>/register"
                    class="link-danger">Đăng ký</a></p>
        </div>
    </form>
</div>
<?php
layout('auth-footer');
?>