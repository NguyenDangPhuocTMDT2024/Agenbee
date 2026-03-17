<?php
$data = [
    'title' => 'Đặt lại mật khẩu'
];
layout('auth-header', $data);
layout('auth-sidebar');
$validLink = filterData('get');
if(empty($validLink['token'])){
    setSessionFlash('msg', 'Đường link không hợp lệ hoặc đã hết hạn. Vui lòng gửi lại yêu cầu đặt lại mật khẩu!');
    setSessionFlash('msg_type', 'danger');
    redirect('/forgot');
}else{
    $token = $validLink['token'];
    $checkToken = $userModel->getUserByForgotToken($token);
    if(empty($checkToken)){
        setSessionFlash('msg', 'Đường link không hợp lệ hoặc đã hết hạn. Vui lòng gửi lại yêu cầu đặt lại mật khẩu!');
        setSessionFlash('msg_type', 'danger');
        redirect('/forgot');
    }
}

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');

?>

<div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
    <form method="POST" action="" enctype="multipart/form-data">
        <h2 class="text-center mb-4 text-uppercase">Đặt lại mật khẩu</h2>
        <?php 
            if(!empty($msg)) {
                echo showMsg($msg, $msgType);
            }
        ?>
        <!-- Password input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input name="password" type="password" id="form3Example3" class="form-control form-control-lg"
                placeholder="Nhập mật khẩu mới" />
        </div>
        <div class = "errors">
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'password');
            }
        ?>
        </div>

        <!-- Confirm Password input -->
        <div data-mdb-input-init class="form-outline mb-3">
            <input name="confirm_password" type="password" id="form3Example4" class="form-control form-control-lg"
                placeholder="Nhập lại mật khẩu" />
        </div>
        <div class = "errors">
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'confirm_password');
            }
        ?>
        </div>

        <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                style="padding-left: 2.5rem; padding-right: 2.5rem;">Xác nhận</button>
        </div>

    </form>
</div>
<?php
layout('auth-footer');
?>