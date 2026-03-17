<?php
$data = [
    'title' => 'Đăng ký tài khoản'
];
layout('auth-header', $data);
layout('auth-sidebar');

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
$oldData = getSessionFlash('old_data');
?>

<div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
    <form method="POST" action="" enctype="multipart/form-data">
        <h2 class="text-center mb-4 text-uppercase">Đăng ký</h2>
        <?php 
            echo showMsg($msg, $msgType);
        ?>
        <!-- Name input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input name="name" type="text" id="form3Example1" class="form-control form-control-lg"
                placeholder="Nhập tên của bạn" 
                value="<?php echo showOldData($oldData, 'name'); ?>"
            />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'name');
            }
        ?>
        <!-- Email input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input name="email" type="text" id="form3Example3" class="form-control form-control-lg"
                placeholder="Nhập email của bạn" 
                value="<?php echo showOldData($oldData, 'email'); ?>"
            />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'email');
            }
        ?>
        <!-- Phone input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input name="phone" type="text" id="form3Example2" class="form-control form-control-lg"
                placeholder="Nhập số điện thoại của bạn"
                value="<?php echo showOldData($oldData, 'phone'); ?>"
            />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'phone');
            }
        ?>
        <!-- Password input -->
        <div data-mdb-input-init class="form-outline mb-3">
            <input name="password" type="password" id="form3Example4" class="form-control form-control-lg"
                placeholder="Nhập mật khẩu" />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'password');
            }
        ?>
        <!-- Password confirmation input -->
        <div data-mdb-input-init class="form-outline mb-3">
            <input name="password_confirmation" type="password" id="form3Example5" class="form-control form-control-lg"
                placeholder="Nhập lại mật khẩu" />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'password_confirmation');
            }
        ?>

        <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                style="padding-left: 2.5rem; padding-right: 2.5rem;">Đăng ký</button>
            <p class="small fw-bold mt-2 pt-1 mb-0">Bạn đã có tài khoản? <a href="<?php echo _HOST_URL?>/login"
                    class="link-danger">Đăng nhập</a></p>
        </div>

    </form>
</div>
<?php
layout('auth-footer');
?>