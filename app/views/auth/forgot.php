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

<div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
    <form method="POST" action="" enctype="multipart/form-data">
        <h2 class="text-center mb-4 text-uppercase">Quên mật khẩu</h2>
        <?php 
            if($msg) {
                echo showMsg($msg, $msg_type);
            }
        ?>
        <!-- Email input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input name="email" type="text" id="form3Example3" class="form-control form-control-lg"
                placeholder="Nhập email khôi phục" />
        </div>
        <div class = "errors">
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'email');
            }
        ?>
        </div>

        <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                style="padding-left: 2.5rem; padding-right: 2.5rem;">Gửi yêu cầu</button>
        </div>

    </form>
</div>
<?php
layout('auth-footer');
?>
