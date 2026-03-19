<?php

class AdminController extends Controller
{
    private $viewPath = 'admin/';
    private $categoryModel;
    private $userModel;
    private $packageModel;
    private $orderModel;
    private $middleware;
    public function __construct()
    {
        $this->middleware = new Middleware();
        $this->userModel = new User();
        $this->categoryModel = new Category();
        $this->packageModel = new Package();
        $this->orderModel = new Order();
        $this->middleware->adminCheck();
    }
    public function dashboard()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
            // 'orderModel' => $this->orderModel,
            // 'taskModel' => $this->taskModel
        ];
        $this->renderView($this->viewPath . 'dashboard', $data);
    }
    public function showPackage()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
            'packageList' => $this->packageModel->getAllPackages(),
            'categoryList' => $this->categoryModel->getAllCategories()
        ];
        $this->renderView($this->viewPath . 'packages/index', $data);
    }
    public function showPackageCreate()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
            'categoryList' => $this->categoryModel->getAllCategories()
        ];
        $this->renderView($this->viewPath . 'packages/create', $data);
    }
    public function packageCreate()
    {
        if (isPost()) {
            $filteredData = filterData();
            $errors = [];
            $errors = validatePackage($filteredData);
            if (validateImage($_FILES['avatar']) !== true) {
                $errors['avatar'] = validateImage($_FILES['avatar']);
            } else {
                $avt = uploadImage($_FILES['avatar']);
            }
            if (empty($errors)) {
                $data = [
                    'name' => $filteredData['name'],
                    'avatar' => $avt,
                    'description' => $filteredData['description'],
                    'price' => $filteredData['price'],
                    'category' => $filteredData['category'],
                    'hidden' => $filteredData['hidden'],
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $checkInsert = $this->packageModel->createPackages($data);
                if ($checkInsert) {
                    setSessionFlash('msg', 'Tạo gói thành công!');
                    setSessionFlash('msg_type', 'success');
                    redirect('/admin/package/create');
                } else {
                    setSessionFlash('msg', 'Tạo gói thất bại, vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Dữ liệu không hợp lệ, vui lòng thử lại!');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                redirect('/admin/package/create');
            }
        }
    }
    public function showPackageEdit()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
            'categoryList' => $this->categoryModel->getAllCategories(),
            'packageModel' => $this->packageModel
        ];
        $this->renderView($this->viewPath . 'packages/edit', $data);
    }
    public function packageEdit()
    {
        if (isPost()) {
            $filteredData = filterData();
            $errors = [];
            $errors = validatePackage($filteredData);
            $avatar = $filteredData['old_avatar'];
            if (!empty($_FILES['avatar']['name'])) {
                $image = validateImage($_FILES['avatar']);
                if ($image === true) {
                    $old_avt = $avatar;
                    $del = removeUploadImg($old_avt);
                    if ($del) {
                        $avatar = uploadImage($_FILES['avatar'], 'uploads/packages');
                    }
                } else {
                    $errors['avatar'] = $image;
                }
            }
            if (empty($errors)) {
                $id = $filteredData['id'];
                $data = [
                    'name' => $filteredData['name'],
                    'avatar' => $avatar,
                    'description' => $filteredData['description'],
                    'price' => $filteredData['price'],
                    'category' => $filteredData['category'],
                    'hidden' => $filteredData['hidden'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $checkUpdate = $this->packageModel->updatePackageByID($data, $id);
                if ($checkUpdate) {
                    setSessionFlash('msg', 'Cập nhật gói thành công!');
                    setSessionFlash('msg_type', 'success');
                    redirect('/admin/package/edit');
                } else {
                    setSessionFlash('msg', 'Cập nhật gói thất bại. Vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('old_data', $filteredData);
                }
            } else {
                setSessionFlash('msg', 'Cập nhật gói thất bại. Vui lòng thử lại!');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('old_data', $filteredData);
                setSessionFlash('errors', $errors);
            }
        }
    }
    public function packageDelete()
    {
        if (isGet()) {
            $filteredData = filterData('get');
            if (!empty($filteredData['id'])) {
                $id = $filteredData['id'];
                $packageInfo = $this->packageModel->getPackagesByID($id);
                if (!empty($packageInfo)) {
                    $checkDelete = $this->packageModel->deletePackageByID($packageInfo['id']);
                    if ($checkDelete) {
                        $delImg = removeUploadImg($packageInfo['avatar']);
                        if ($delImg) {
                            setSessionFlash('msg', 'Xóa gói thành công!');
                            setSessionFlash('msg_type', 'success');
                            redirect('/admin/package');
                        } else {
                            setSessionFlash('msg', 'Xóa gói thành công, nhưng không xóa được ảnh!');
                            setSessionFlash('msg_type', 'warning');
                            redirect('/admin/package');
                        }
                    } else {
                        setSessionFlash('msg', 'Xóa không thành công, vui lòng thử lại!');
                        setSessionFlash('msg_type', 'danger');
                        redirect('/admin/package');
                    }
                } else {
                    setSessionFlash('msg', 'Gói không tồn tại!');
                    setSessionFlash('msg_type', 'danger');
                    redirect('/admin/package');
                }
            } else {
                setSessionFlash('msg', 'Gói không tồn tại!');
                setSessionFlash('msg_type', 'danger');
                redirect('/admin/package');
            }
        }
    }
    public function showCategoryCreate()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
            // 'categoryList' => $this->categoryModel->getAllCategories(),
            // 'packageModel' => $this->packageModel
        ];
        $this->renderView($this->viewPath . 'packages/category_create', $data);
    }
    public function categoryCreate()
    {
        if (isPost()) {
            $filteredData = filterData();
            $errors = [];
            $errors = validateCategory($filteredData);
            if (empty($errors)) {
                $data = [
                    'name' => $filteredData['name'],
                    'description' => $filteredData['description'],
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $checkInsert = $this->categoryModel->createCategory($data);
                if ($checkInsert) {
                    setSessionFlash('msg', 'Thêm danh mục thành công!');
                    setSessionFlash('msg_type', 'success');
                    redirect('/admin/package');
                } else {
                    setSessionFlash('msg', 'Dữ liệu không hợp lệ, vui lòng sửa lại!');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Dữ liệu không hợp lệ, vui lòng sửa lại!');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                setSessionFlash('old_data', $filteredData);
            }
        }
        redirect('/admin/package/category_create');
    }
    public function showOrder()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
            'orderList' => $this->orderModel->getAllOrders()
        ];
        $this->renderView($this->viewPath . 'orders/index', $data);
    }
    public function showOrderDetail()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
        ];
        $this->renderView($this->viewPath . 'orders/detail', $data);
    }
    // public function showProfile()
    // {
    //     $userID = getSession('user_id');
    //     $data = [
    //         'userInfo' => $this->userModel->getUserByID($userID),
    //     ];
    //     $this->renderView($this->viewPath . 'users/profile', $data);
    // }
    public function showUser()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
            'userList' => $this->userModel->getAllUsers()
        ];
        $this->renderView($this->viewPath . 'users/index', $data);
    }
    public function showUserCreate()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
        ];
        $this->renderView($this->viewPath . 'users/create', $data);
    }
    public function userCreate()
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
            if (empty($errors)) {
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => $filteredData['role'],
                    'status' => $filteredData['status'],
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                if (!$filteredData['status']) {
                    $token = generateToken();
                    $data['active_token'] = $token;
                    $insertStatus = $this->userModel->createUser($data);
                    if ($insertStatus) {
                        $mailTo = $email;
                        $subject = 'Kích hoạt tài khoản';
                        $linkActive = _HOST_URL . "/activate?token=$token";
                        $content = "Chào $name,<br> Vui lòng click vào link sau để kích hoạt tài khoản: <a href='$linkActive'>$linkActive</a>";
                        if (sendMail($mailTo, $subject, $content)) {
                            setSessionFlash('msg', 'Tạo tài khoản thành công. Vui lòng kiểm tra email để kích hoạt tài khoản!');
                            setSessionFlash('msg_type', 'success');
                            redirect('/admin/user/create');
                        } else {
                            setSessionFlash('msg', 'Tạo tài khoản thành công nhưng gửi mail thất bại. Vui lòng liên hệ admin!');
                            setSessionFlash('msg_type', 'warning');
                        }
                    } else {
                        setSessionFlash('msg', 'Tạo tài khoản thất bại. Vui lòng thử lại!');
                        setSessionFlash('msg_type', 'danger');
                    }
                } else {
                    $data['active_token'] = null;
                    $insertStatus = $this->userModel->createUser($data);
                    if ($insertStatus) {
                        setSessionFlash('msg', 'Tạo tài khoản thành công!');
                        setSessionFlash('msg_type', 'success');
                        redirect('/admin/user');
                    } else {
                        setSessionFlash('msg', 'Tạo tài khoản thất bại. Vui lòng thử lại!');
                        setSessionFlash('msg_type', 'danger');
                    }
                }
            } else {
                //set error message
                setSessionFlash('msg', 'Dữ liệu không hợp lệ. Vui lòng sửa các lỗi bên dưới!');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                setSessionFlash('old_data', $filteredData);
            }
        }
        redirect('/admin/user/create');
    }
    public function showUserProfile()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
        ];
        if (isGet()) {
            $filteredData = filterData('get');
            if (isset($filteredData['id'])) {
                $id = $filteredData['id'];
                $checkUser = $this->userModel->getUserByID($id);
                if (!empty($checkUser)) {
                    $data['userProfile'] = $checkUser;
                } else {
                    setSessionFlash('msg', 'Người dùng không tồn tại!!!');
                    setSessionFlash('msg_type', 'danger');
                    redirect('/admin/user');
                }
            } else {
                setSessionFlash('msg', 'Đường dẫn không hợp lệ!');
                setSessionFlash('msg_type', 'danger');
                redirect('/admin/user');
            }
        }
        $this->renderView($this->viewPath . 'users/profile', $data);
    }
    public function userDelete()
    {
        if (isGet()) {
            $filteredData = filterData();
            if (isset($filteredData['id'])) {
                $id = $filteredData['id'];
                $checkUser = $this->userModel->getUserByID($id);
                if (!empty($checkUser)) {
                    $checkDelete = $this->userModel->deleteUserByID($id);
                    if ($checkDelete) {
                        if (empty($checkUser['avatar'])) {
                            setSessionFlash('msg', 'Xóa người dùng thành công!');
                            setSessionFlash('msg_type', 'success');
                            redirect('/admin/user');
                        } else {
                            $delImg = removeUploadImg($checkUser['avatar']);
                            if ($delImg) {
                                setSessionFlash('msg', 'Xóa người dùng thành công!');
                                setSessionFlash('msg_type', 'success');
                                redirect('/admin/user');
                            } else {
                                setSessionFlash('msg', 'Xóa người dùng thành công, nhưng không xóa được ảnh!');
                                setSessionFlash('msg_type', 'warning');
                                redirect('/admin/user');
                            }
                        }
                    }
                } else {
                    setSessionFlash('msg', 'Người dùng không tồn tại!!!');
                    setSessionFlash('msg_type', 'danger');
                    redirect('/admin/user');
                }
            } else {
                setSessionFlash('msg', 'Đường dẫn không hợp lệ!');
                setSessionFlash('msg_type', 'danger');
                redirect('/admin/user');
            }
        }
    }
    public function showUserEdit(){
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
        ];
        $this->renderView($this->viewPath . 'users/edit', $data);
    }
    public function userEdit(){

    }
}
