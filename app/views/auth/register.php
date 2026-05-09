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

<div class="auth-form-wrapper">
    <form method="POST" action="" enctype="multipart/form-data">
        <h2>Đăng ký</h2>
        <?php 
            if($msg) {
                echo showMsg($msg, $msgType);
            }
        ?>
        
        <!-- Name input -->
        <div class="form-outline mb-4">
            <input name="name" type="text" id="name" class="form-control"
                placeholder="Nhập tên của bạn" 
                value="<?php echo showOldData($oldData, 'name'); ?>"
            />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'name');
        } ?>

        <!-- Email input -->
        <div class="form-outline mb-4">
            <input name="email" type="text" id="email" class="form-control"
                placeholder="Nhập email của bạn" 
                value="<?php echo showOldData($oldData, 'email'); ?>"
            />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'email');
        } ?>

        <!-- Phone input -->
        <div class="form-outline mb-4">
            <input name="phone" type="text" id="phone" class="form-control"
                placeholder="Nhập số điện thoại của bạn"
                value="<?php echo showOldData($oldData, 'phone'); ?>"
            />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'phone');
        } ?>

        <!-- Password input -->
        <div class="form-outline mb-4">
            <input name="password" type="password" id="password" class="form-control"
                placeholder="Nhập mật khẩu" />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'password');
        } ?>

        <!-- Password confirmation input -->
        <div class="form-outline mb-4">
            <input name="password_confirmation" type="password" id="password_confirmation" class="form-control"
                placeholder="Nhập lại mật khẩu" />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'password_confirmation');
        } ?>

        <div class="text-center">
            <button type="submit" class="btn btn-warning">Đăng ký</button>
            <p class="mt-3 mb-0">Bạn đã có tài khoản? 
                <a href="<?php echo _HOST_URL?>/login" class="link-danger">Đăng nhập</a>
            </p>
        </div>
    </form>
</div>

<?php
layout('auth-footer');
?>