<?php

class AuthController extends Controller
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }
    //login
    public function showLogin()
    {
        $this->renderView('auth/login');
    }
    public function login()
    {
        if (isPost()) {
            $filteredData = filterData();
            $errors = [];
            $email = trim($filteredData['email']);
            //validate email
            if (validateEmail($email) !== true) {
                $errors['email'] = validateEmail($email);
            }
            //validate password
            $password = trim($filteredData['password']);
            if (validatePassword($password) !== true) {
                $errors['password'] = validatePassword($password);
            }
            //check login
            if (empty($errors)) {
                //check email in db
                $checkMail = $this->userModel->getUserByEmail($email);
                if (!empty($checkMail)) {
                    //check password
                    $checkStatus = password_verify($password, $checkMail['password']);
                    //create token
                    if ($checkStatus) {
                        if ($checkMail['status'] == 0) {
                            setSessionFlash('msg', 'Tài khoản chưa được kích hoạt. Vui lòng kiểm tra email để kích hoạt tài khoản!');
                            setSessionFlash('msg_type', 'warning');
                            redirect('/login');
                        } else {
                            $token = generateToken();
                            $data = [
                                'user_id' => $checkMail['id'],
                                'token' => $token,
                                'created_at' => date('Y-m-d H:i:s'),
                            ];
                            //insert token create login session
                            $insertStatus = $this->userModel->createLoginSession($data['user_id'], $data['token'], $data['created_at']);
                            if ($insertStatus) {
                                setSession('user_id', $checkMail['id']);
                                setSession('token_login', $token); //gan token vao session de xac thuc dang nhap
                                setSession('user_role', $checkMail['role']); // Lưu role vào session
                                //redirect to home
                                loginRedirect($checkMail);
                            } else {
                                setSessionFlash('msg', 'Đăng nhập thất bại. Vui lòng thử lại!');
                                setSessionFlash('msg_type', 'danger');
                            }
                        }
                    } else {
                        setSessionFlash('msg', 'Email hoặc mật khẩu không đúng');
                        setSessionFlash('msg_type', 'danger');
                    }
                } else {
                    setSessionFlash('msg', 'Email hoặc mật khẩu không đúng');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                //set error message
                setSessionFlash('msg', 'Dữ liệu không hợp lệ. Vui lòng sửa các lỗi bên dưới!');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                setSessionFlash('old_data', $filteredData);
            }
        }
        redirect('/login');
    }
    //register
    public function showRegister()
    {
        $this->renderView('auth/register');
    }
    public function register()
    {
        if (isPost()) {
            $filteredData = filterData();
            $errors = [];
            //validate name
            $name = trim($filteredData['name']);
            if (validateName($name) !== true) {
                $errors['name'] = validateName($name);
            }
            //validate email
            $email = trim($filteredData['email']);
            if (validateEmail($email) !== true) {
                $errors['email'] = validateEmail($email);
            } else {
                //check email in db
                $checkMail = $this->userModel->countUsersByEmail($email);
                if ($checkMail > 0) {
                    $errors['email'] = 'Email đã tồn tại';
                }
            }
            //validate phone
            $phone = trim($filteredData['phone']);
            if (validatePhone($phone) !== true) {
                $errors['phone'] = validatePhone($phone);
            }
            //validate password
            $password = trim($filteredData['password']);
            if (validatePassword($password) !== true) {
                $errors['password'] = validatePassword($password);
            }
            //validate password confirmation
            $passwordConfirmation = trim($filteredData['password_confirmation']);
            if ($password !== $passwordConfirmation) {
                $errors['password_confirmation'] = 'Mật khẩu xác nhận không khớp';
            }
            //check register
            if (empty($errors)) {
                $token = generateToken();
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'active_token' => $token,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'create_at' => date('Y-m-d H:i:s'),
                ];
                //insert user
                $insertStatus = $this->userModel->createUser($data['name'], $data['email'], $data['password'], $data['phone'], $data['active_token'], $data['create_at']);
                if ($insertStatus) {
                    //send mail active account
                    $mailTo = $email;
                    $subject = 'Kích hoạt tài khoản';
                    $linkActive = _HOST_URL . "/activate?token=$token";
                    $content = "Chào $name,<br> Vui lòng click vào link sau để kích hoạt tài khoản: <a href='$linkActive'>$linkActive</a>";
                    if(sendMail($mailTo, $subject, $content)) {
                        setSessionFlash('msg', 'Đăng ký thành công. Vui lòng kiểm tra email để kích hoạt tài khoản!');
                        setSessionFlash('msg_type', 'success');
                    } else {
                        setSessionFlash('msg', 'Đăng ký thành công nhưng gửi mail thất bại. Vui lòng liên hệ admin!');
                        setSessionFlash('msg_type', 'warning');
                    }
                } else {
                    setSessionFlash('msg', 'Đăng ký thất bại. Vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                //set error message
                setSessionFlash('msg', 'Dữ liệu không hợp lệ. Vui lòng sửa các lỗi bên dưới!');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                setSessionFlash('old_data', $filteredData);
            }
        }
        redirect('/register');
    }
    //logout
    public function logout()
    {
        $userId = getSession('user_id');
        if ($userId) {
            $data = [
                'user_id' => $userId,
                'token' => null,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->userModel->updateLoginSessionByID($data['user_id'], $data['token'], $data['updated_at']);
        }
        deleteSession('user_id');
        deleteSession('token_login');
        deleteSession('user_role');

        redirect('/login');
    }
    //forgot password
    public function showForgot()
    {
        $this->renderView('auth/forgot');
    }
    public function forgot()
    {
        if (isPost()) {
            $filteredData = filterData();
            $errors = [];
            $recoverEmail = trim($filteredData['email']);
            //validate email
            if (validateEmail($recoverEmail) !== true) {
                $errors['email'] = validateEmail($recoverEmail);
            } else {
                //check email in db
                $checkMail = $this->userModel->getUserByEmail($recoverEmail);
                if (!$checkMail) {
                    $errors['email'] = 'Email không tồn tại';
                }
            }

            if (empty($errors)) {
                //create token
                $token = generateToken();
                $updateStatus = $this->userModel->updateForgotToken($recoverEmail, $token, date('Y-m-d H:i:s'));
                if ($updateStatus) {
                    //send mail reset password
                    $mailTo = $recoverEmail;
                    $subject = 'Yêu cầu đặt lại mật khẩu';
                    $linkReset = _HOST_URL . "/reset?token=$token";
                    $content = "Chào bạn,<br> Vui lòng click vào link sau để đặt lại mật khẩu: <a href='$linkReset'>$linkReset</a>";
                    sendMail($mailTo, $subject, $content);

                    setSessionFlash('msg', 'Yêu cầu đặt lại mật khẩu đã được gửi. Vui lòng kiểm tra email!');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Gửi yêu cầu thất bại. Vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                //set error message
                setSessionFlash('msg', 'Dữ liệu không hợp lệ. Vui lòng sửa các lỗi bên dưới!');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                setSessionFlash('old_data', $filteredData);
            }
            redirect('/forgot');
        }
    }
    //reset password
    public function showReset()
    {
        $data = [
            'userModel' => $this->userModel
        ];
        $this->renderView('auth/reset', $data);
    }
    public function reset()
    {
        if (isPost()) {
            $filteredData = filterData();
            $errors = [];
            $password = trim($filteredData['password']);
            $confirmPassword = trim($filteredData['confirm_password']);
            //validate password
            if (validatePassword($password) !== true) {
                $errors['password'] = validatePassword($password);
            }
            //validate confirm password
            if ($password !== $confirmPassword) {
                $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp';
            }
            //check reset password
            if (empty($errors)) {
                $token = $_GET['token'];
                $checkToken = $this->userModel->getUserByForgotToken($token);
                if (!empty($checkToken)) {
                    $data = [
                        'password' => $password,
                        'forgot_token' => null,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'id' => $checkToken['id']
                    ];
                    $updateStatus = $this->userModel->updatePasswordByID($data['id'], $data['password'], $data['forgot_token'], $data['updated_at']);
                    if ($updateStatus) {
                        setSessionFlash('msg', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập lại!');
                        setSessionFlash('msg_type', 'success');
                        redirect('/login');
                    } else {
                        setSessionFlash('msg', 'Đặt lại mật khẩu thất bại. Vui lòng thử lại!');
                        setSessionFlash('msg_type', 'danger');
                    }
                } else {
                    setSessionFlash('msg', 'Đường link không hợp lệ hoặc đã hết hạn!');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                //set error message
                setSessionFlash('msg', 'Dữ liệu không hợp lệ. Vui lòng sửa các lỗi bên dưới!');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
            }
            redirect('/reset?token=' . $_GET['token']);
        }
    }
    //active account
    public function showActive()
    {
        $data = [
            'userModel' => $this->userModel
        ];
        $this->renderView('auth/active', $data);
    }
}
