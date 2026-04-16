<?php
//hàm layout
function layout($layoutName, $data = [])
{
    if (file_exists('app/views/layouts/' . $layoutName . '.php')) {
        extract($data);
        require_once 'app/views/layouts/' . $layoutName . '.php';
    }
}
//thiết lập session
function setSession($key, $value) {
    if (!empty(session_id())) {
        $_SESSION[$key] = $value; 
        return true; 
    }
    return false;
}
//lấy giá trị session
function getSession($key = '') {
    if (!empty(session_id()) && isset($_SESSION[$key])) {
        return $_SESSION[$key]; 
    }
    if(empty($key)) {
        return $_SESSION; 
    }
    return false; 
}
//xóa session
function deleteSession($key = '') {
    if (!empty(session_id()) && isset($_SESSION[$key])) {
        unset($_SESSION[$key]); 
        return true; 
    }
    if(empty($key)) {
        session_destroy(); 
        return true; 
    }
    return false; 
}
//tạo session flash
function setSessionFlash($key, $value) {
    $key = 'flash_' . $key;
    $rel = setSession($key, $value);
    return $rel;
}
//lấy giá trị session flash
function getSessionFlash($key) {
    $key = 'flash_' . $key;
    $rel = getSession($key);
    deleteSession($key);
    return $rel;
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//hàm gửi mail bugggggggggggggggggggggggggggggggggggggggggggggggggg
function sendMail($to, $subject, $message)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output for testing
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = _HOST_MAIL;                     //SMTP username
        $mail->Password   = _APP_PASS;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //STARTTLS uses port 587 with Gmail
        $mail->Port       = 587;
        $mail->Timeout    = 15;                                      //Avoid long hangs on SMTP read/connect
        $mail->SMTPKeepAlive = false;
        $mail->CharSet = 'UTF-8';
        //Recipients
        $mail->setFrom(_HOST_MAIL, 'Agenbee');
        $mail->addAddress($to);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        
        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
//kiểm tra phương thức gửi dữ liệu
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}
//kiểm tra phương thức gửi dữ liệu
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}
//lọc dữ liệu
function filterData($method = '')
{
    $filteredData = [];
    if (empty($method)) {
        if (isPost()) {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $filteredData[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filteredData[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
        if (isGet()) {
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $filteredData[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filteredData[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    } else {
        if ($method == 'get') {
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $filteredData[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filteredData[$key] = filter_var($_GET[$key], FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        } else if ($method == 'post') {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    $key = strip_tags($key);
                    if (is_array($value)) {
                        $filteredData[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $filteredData[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    }
    return $filteredData;
}
//validate name
function validateName($name)
{
    if (empty($name)) {
        return "Tên không được để trống";
    }

    if (strlen($name) < 6) {
        return "Tên phải có ít nhất 6 ký tự";
    }

    if (!preg_match('/^[a-zA-ZÀ-ỹ\s]+$/u', $name)) {
        return "Tên chỉ được chứa chữ cái";
    }

    return true;
}
//validate email
function validateEmail($email)
{
    if (empty($email)) {
        return "Email không được để trống";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Email không hợp lệ";
    }

    return true;
} 
//validate phone
function validatePhone($phone)
{
    if (empty($phone)) {
        return "Số điện thoại không được để trống";
    }

    if (!preg_match('/^(0|\+84)[35789][0-9]{8}$/', $phone)) {
        return "Số điện thoại không hợp lệ";
    }

    return true;
}
//validate password
function validatePassword($password)
{
    if (empty($password)) {
        return "Password không được để trống";
    }

    if (strlen($password) < 8) {
        return "Password phải có ít nhất 8 ký tự";
    }

    if (!preg_match('/[a-z]/', $password)) {
        return "Password phải có chữ thường";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        return "Password phải có chữ hoa";
    }

    if (!preg_match('/[0-9]/', $password)) {
        return "Password phải có ít nhất 1 số";
    }

    return true; // hợp lệ
}
//hàm hiển thị lỗi
function showErrors($errors, $fields){
    if(!empty($errors) && isset($errors[$fields])){
        return "<div class=\"text-danger mb-3 fst-italic\">{$errors[$fields]}</div>";
    }
    return '';
}
//hàm hiển thị thông báo
function showMsg($msg, $msgType = 'success'){
    if(!empty($msg)){
        return "<div class=\"alert alert-{$msgType}\">{$msg}</div>";
    }
    return '';
}
//hàm tạo token
function generateToken($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
//hàm hiển thị dữ liệu cũ
function showOldData($oldData, $field){
    if(!empty($oldData) && isset($oldData[$field])){
        return $oldData[$field];
    }
    return '';
}
//hàm chuyển hướng
function redirect($url){
    $path = _HOST_URL . $url;
    header("Location: $path");
    exit();
}
//hàm kiểm tra đăng nhập (đơn giản - chỉ kiểm tra session)
function isLogin(){
    $tokenLogin = getSession('token_login');
    return !empty($tokenLogin);
}

//hàm kiểm tra đăng nhập kỹ (kiểm tra token trong database)
function isLoginStrict($userModel){
    $checkLogin = false;
    $tokenLogin = getSession('token_login');
    
    if(!empty($tokenLogin) && !empty($userModel)){
        $checkToken = $userModel->getSessionByToken($tokenLogin);
        if(!empty($checkToken)){
            $checkLogin = true;
        }else{
            deleteSession('token_login');
        }
    }
    
    return $checkLogin;
}
//hàm chuyển hướng đăng nhập theo role
function loginRedirect($userData){
    if(!empty($userData)){
        if($userData['role']==='admin'){
            redirect('/admin/');
        }else{
            redirect('/home');
        }
    }
    return false;
}
//validate dữ liệu package
function validatePackage($data){ 
    $errors = [];
    if(empty($data['name'])){
        $errors['name'] = "Tên gói không được để trống";
    }elseif(strlen($data['name']) < 3){
        $errors['name'] = "Tên gói phải ít nhất 3 ký tự";
    }
    if(empty($data['price'])){
        $errors['price'] = "Giá không được để trống";
    }elseif(!is_numeric($data['price']) || $data['price'] < 0){
        $errors['price'] = "Giá phải là số hợp lệ";
    }
    if(empty($data['category'])){
        $errors['category'] = "Vui lòng chọn loại gói";
    }
    if(isset($data['category']) && $data['category'] === 'combo'){
        if(empty($data['items'])){
            $errors['items'] = "Combo phải có ít nhất 1 add-on";
        }
    }
    return $errors;
}
//validate ảnh
function validateImage($file){
    if(empty($file['name'])){
        return true; //không bắt buộc phải có ảnh
    }
    if($file['error'] !== 0){
        return "Upload ảnh thất bại";
    }

    $allowed = ['jpg','jpeg','png','webp'];

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if(!in_array($ext,$allowed)){
        return "Chỉ chấp nhận JPG, PNG, WEBP";
    }

    if($file['size'] > 2*1024*1024){
        return "Ảnh tối đa 2MB";
    }

    return true;
}
function uploadImage($file,$folder='uploads'){
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    //rename và mã hóa name
    $fileName = time().'_'.uniqid().'.'.$ext;
    $path = _PUBLIC_PATH."/$folder/".$fileName;
    move_uploaded_file($file['tmp_name'],$path);
    return $fileName;
}
function removeUploadImg($fileName, $folder='uploads'){
    if(empty($fileName)){
        return false;
    }
    $path = _PUBLIC_PATH.'/'.$folder.'/'.$fileName;
    if(file_exists($path)){
        return unlink($path);
    }
    return false;
}
function showImg($fileName, $folder='uploads'){
    if(!empty($fileName)){
        echo _HOST_URL_PUBLIC.'/'.$folder.'/'.$fileName;
    } else {
        echo '';
    }
}
//validate danh mục
function validateCategory($data){
    $errors = [];
    if(empty($data['name'])){
        $errors['name'] = "Tên gói không được để trống";
    }elseif(strlen($data['name']) < 3){
        $errors['name'] = "Tên gói phải ít nhất 3 ký tự";
    }
    return $errors;
}
function validateContact($data){
    $errors = [];
    if(empty($data['name'])){
        $errors['name'] = "Tên không được để trống";
    } elseif(strlen($data['name']) < 6){
        $errors['name'] = "Tên phải có ít nhất 6 ký tự";
    } elseif (!preg_match('/^[a-zA-ZÀ-ỹ\s]+$/u', $data['name'])) {
        $errors['name'] = "Tên chỉ được chứa chữ cái";
    }

    if(validatePhone($data['phone']) !== true){
        $errors['phone'] = validatePhone($data['phone']);
    }

    return $errors;
}
function validateShopInfo($data){
    $errors = [];
    if(empty($data['shop_name'])){
        $errors['shop_name'] = "Tên shop không được để trống";
    } elseif(strlen($data['shop_name']) < 3){
        $errors['shop_name'] = "Tên shop phải có ít nhất 3 ký tự";
    }

    if(empty($data['address'])){
        $errors['address'] = "Địa chỉ không được để trống";
    } elseif(strlen($data['address']) < 5){
        $errors['address'] = "Địa chỉ phải có ít nhất 5 ký tự";
    }

    if(empty($data['major'])){
        $errors['major'] = "Ngành hàng không được để trống";
    } elseif(strlen($data['major']) < 3){
        $errors['major'] = "Ngành hàng phải có ít nhất 3 ký tự";
    }

    return $errors;
}