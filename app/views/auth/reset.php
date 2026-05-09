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

<div class="auth-form-wrapper">
    <form method="POST" action="" enctype="multipart/form-data">
        <h2>Đặt lại mật khẩu</h2>
        <?php 
            if(!empty($msg)) {
                echo showMsg($msg, $msgType);
            }
        ?>
        
        <!-- Password input -->
        <div class="form-outline mb-4">
            <input name="password" type="password" id="password" class="form-control"
                placeholder="Nhập mật khẩu mới" />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'password');
        } ?>

        <!-- Confirm Password input -->
        <div class="form-outline mb-4">
            <input name="confirm_password" type="password" id="confirm_password" class="form-control"
                placeholder="Nhập lại mật khẩu" />
        </div>
        <?php if(!empty($errors)) { 
            echo showErrors($errors, 'confirm_password');
        } ?>

        <div class="text-center">
            <button type="submit" class="btn btn-warning">Xác nhận</button>
        </div>
    </form>
</div>
<?php
layout('auth-footer');
?>