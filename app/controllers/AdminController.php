<?php

class AdminController extends Controller
{
    private $viewPath = 'admin/';
    private $categoryModel;
    private $userModel;
    private $packageModel;
    private $orderModel;
    private $contactModel;
    private $middleware;
    public function __construct()
    {
        $this->middleware = new Middleware();
        $this->userModel = new User();
        $this->categoryModel = new Category();
        $this->packageModel = new Package();
        $this->orderModel = new Order();
        $this->contactModel = new Contact();
        $this->middleware->adminCheck();
    }
    public function dashboard()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
            'needConfirmOrders' => $this->orderModel->countOrdersByStatus('pending'),
            'currentUsers' => $this->userModel->countOnlineUsers()
        ];
        $this->renderView($this->viewPath . 'dashboard', $data);
    }
    public function showPackage()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
            'packageList' => $this->packageModel->getAllPackages(),
            'packageItemList' => $this->packageModel->getAllPackageItems(),
            'categoryList' => $this->categoryModel->getAllCategories()
        ];
        if(isGet()){
            $filteredData = filterData('get');
            // echo '<pre>';
            // print_r($filteredData);
            // echo '</pre>';
            // die();
            if(!empty($filteredData['filter'])){
                if($filteredData['filter'] === 'all'){
                    $data['packageList'] = $this->packageModel->getAllPackages();
                    $data['currentFilter'] = 'all';
                } else {
                    $condition = "c.name = '" . $filteredData['filter'] . "'";
                    $data['packageList'] = $this->packageModel->getAllPackages($condition);
                    $data['currentFilter'] = $filteredData['filter'];
                }
            } else {
                $data['packageList'] = $this->packageModel->getAllPackages();
            }
        }
        $this->renderView($this->viewPath . 'packages/index', $data);
    }
    public function showPackageCreate()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
            'categoryList' => $this->categoryModel->getAllCategories(),
            'addOnPackageList' => $this->packageModel->getAddonPackages()
        ];
        $this->renderView($this->viewPath . 'packages/create', $data);
    }
    private function isComboCategory($categoryId)
    {
        $categories = $this->categoryModel->getAllCategories();
        foreach ($categories as $category) {
            if ((string)$category['id'] === (string)$categoryId) {
                return strtolower(trim($category['name'])) === 'combo';
            }
        }

        return false;
    }
    public function packageCreate()
    {
        if (isPost()) {
            $filteredData = filterData();
            $errors = [];
            $errors = validatePackage($filteredData);
            $isComboCategory = !empty($filteredData['category']) && $this->isComboCategory($filteredData['category']);
            if ($isComboCategory) {
                $subNumber = 0;
                foreach ($filteredData['items'] ?? [] as $sub) {
                    if (isset($sub['selected']) && $sub['selected'] === 'on') {
                        $subNumber++;
                    }
                }
                if ($subNumber < 2) {
                    $errors['items'] = 'Vui lòng chọn ít nhất 2 gói con';
                }
            }
            if (validateImage($_FILES['avatar']) !== true) {
                $errors['avatar'] = validateImage($_FILES['avatar']);
            } else {
                $avt = uploadImage($_FILES['avatar']);
            }
            if (empty($errors)) {
                $data = [
                    'sku' => $filteredData['sku'],
                    'name' => $filteredData['name'],
                    'avatar' => $avt,
                    'type' => $filteredData['type'],
                    'short_description' => $filteredData['short_description'],
                    'long_description' => $filteredData['long_description'],
                    'price' => $filteredData['price'],
                    'unit' => $filteredData['unit'],
                    'category_id' => $filteredData['category'],
                    'hidden' => $filteredData['hidden'],
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $checkInsert = $this->packageModel->createPackages($data);
                if ($checkInsert) {
                    if ($isComboCategory) {
                        foreach ($filteredData['items'] ?? [] as $addonId => $sub) {
                            if (isset($sub['selected']) && $sub['selected'] === 'on') {
                                $this->packageModel->createPackagesAddon($addonId, $checkInsert, $sub['quantity']);
                            }
                        }
                    }
                    setSessionFlash('msg', 'Tạo gói thành công!');
                    setSessionFlash('msg_type', 'success');
                    redirect('/admin/package');
                } else {
                    setSessionFlash('msg', 'Tạo gói thất bại, vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('old_data', $filteredData);
                    redirect('/admin/package/create');
                }
            } else {
                setSessionFlash('msg', 'Dữ liệu không hợp lệ, vui lòng thử lại!');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                setSessionFlash('old_data', $filteredData);
                redirect('/admin/package/create');
            }
        }
    }
    public function showPackageEdit()
    {
        $userID = getSession('user_id');
        if (isGet()) {
            $filteredData = filterData('get');
            if (!empty($filteredData['id'])) {
                $id = $filteredData['id'];
                $packageInfo = $this->packageModel->getPackageByID($id);
                if (!empty($packageInfo)) {
                    $packageAddons = [];
                    foreach ($this->packageModel->getAddonsByPackageID($id) as $addon) {
                        $packageAddons[$addon['addon_id']] = $addon;
                    }
                    $data = [
                        'userInfo' => $this->userModel->getUserByID($userID),
                        'categoryList' => $this->categoryModel->getAllCategories(),
                        'packageInfo' => $packageInfo,
                        'packageAddons' => $packageAddons,
                        'addOnPackageList' => $this->packageModel->getAddonPackages()
                    ];
                    $this->renderView($this->viewPath . 'packages/edit', $data);
                } else {
                    setSessionFlash('msg', 'Gói không tồn tại!');
                    setSessionFlash('msg_type', 'danger');
                    redirect('/admin/package');
                }
            } else {
                setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại!');
                setSessionFlash('msg_type', 'danger');
                redirect('/admin/package');
            }
        }
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
                    'sku' => $filteredData['sku'],
                    'name' => $filteredData['name'],
                    'avatar' => $avatar,
                    'type' => $filteredData['type'],
                    'short_description' => $filteredData['short_description'],
                    'long_description' => $filteredData['long_description'],
                    'price' => $filteredData['price'],
                    'unit' => $filteredData['unit'],
                    'category_id' => $filteredData['category'],
                    'hidden' => $filteredData['hidden'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $checkUpdate = $this->packageModel->updatePackageByID($data, $id);
                if ($checkUpdate) {
                    $isComboCategory = !empty($filteredData['category']) && $this->isComboCategory($filteredData['category']);
                    if ($isComboCategory) {
                        $this->packageModel->deleteAddonsByPackageID($id);
                        $check = false;
                        foreach ($filteredData['items'] ?? [] as $addonId => $sub) {
                            if (isset($sub['selected']) && $sub['selected'] === 'on') {
                                $check = $this->packageModel->createPackagesAddon($addonId, $id, $sub['quantity']);
                            }
                        }
                        if ($check) {
                            setSessionFlash('msg', 'Cập nhật gói thành công!');
                            setSessionFlash('msg_type', 'success');
                            redirect('/admin/package');
                        } else {
                            setSessionFlash('msg', 'Cập nhật gói thất bại. Vui lòng thử lại!');
                            setSessionFlash('msg_type', 'danger');
                            setSessionFlash('old_data', $filteredData);
                            setSessionFlash('errors', $errors);
                            redirect('/admin/package/edit');
                        }
                    } else {
                        setSessionFlash('msg', 'Cập nhật gói thất bại. Vui lòng thử lại!');
                        setSessionFlash('msg_type', 'danger');
                        setSessionFlash('old_data', $filteredData);
                        setSessionFlash('errors', $errors);
                        redirect('/admin/package/edit');
                    }
                } else {
                    setSessionFlash('msg', 'Cập nhật gói thất bại. Vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                    setSessionFlash('old_data', $filteredData);
                    setSessionFlash('errors', $errors);
                    redirect('/admin/package/edit');
                }
            }
        }
    }
    public function packageDelete()
    {
        if (isGet()) {
            $filteredData = filterData('get');
            if (!empty($filteredData['id'])) {
                $id = $filteredData['id'];
                $packageInfo = $this->packageModel->getPackageByID($id);
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
        if(isGet()){
            $filteredData = filterData('get');
            if(!empty($filteredData['filter'])){
                if($filteredData['filter'] === 'all'){
                    $data['orderList'] = $this->orderModel->getAllOrders();
                    $data['currentFilter'] = 'all';
                } else {
                    $condition = "o.status = '" . $filteredData['filter'] . "'";
                    $data['orderList'] = $this->orderModel->getAllOrders($condition);
                    $data['currentFilter'] = $filteredData['filter'];
                }
            } else {
                $data['orderList'] = $this->orderModel->getAllOrders();
            }
        }
        $this->renderView($this->viewPath . 'orders/index', $data);
    }
    public function showOrderDetail()
    {
        if (isGet()) {
            $filteredData = filterData('get');
            if (!empty($filteredData['id'])) {
                $orderId = $filteredData['id'];
                $checkOrder = $this->orderModel->getOrderById($orderId);
                if (empty($checkOrder)) {
                    setSessionFlash('msg', 'Đơn hàng không tồn tại!');
                    setSessionFlash('msg_type', 'danger');
                    redirect('/admin/order');
                }
                $userID = getSession('user_id');
                $data = [
                    'userInfo' => $this->userModel->getUserByID($userID),
                    'orderInfo' => $checkOrder,
                    'orderItems' => $this->orderModel->getOrderItemsById($orderId),
                    'orderTasks' => $this->orderModel->getOrderTasksById($orderId)
                ];
                $this->renderView($this->viewPath . 'orders/detail', $data);
            } else {
                setSessionFlash('msg', 'Đường dẫn không hợp lệ!');
                setSessionFlash('msg_type', 'danger');
                redirect('/admin/order');
            }
        }
    }
    public function updateOrderTasks()
    {
        if (isPost()) {
            $filteredData = filterData();
            $orderId = $filteredData['order_id'] ?? null;
            if (!$orderId || empty($this->orderModel->getOrderById($orderId))) {
                setSessionFlash('msg', 'Đơn hàng không tồn tại!');
                setSessionFlash('msg_type', 'danger');
                redirect('/admin/order');
            }

            if (isset($filteredData['order_status'])) {
                $status = trim((string)$filteredData['order_status']);
                $allowedStatuses = ['pending', 'confirming', 'processing', 'completed', 'cancelled'];

                if (!in_array($status, $allowedStatuses, true)) {
                    setSessionFlash('msg', 'Trạng thái đơn hàng không hợp lệ!');
                    setSessionFlash('msg_type', 'danger');
                    redirect("/admin/order/detail?id=$orderId");
                }

                $updated = $this->orderModel->updateOrderStatus($orderId, $status);
                if ($updated) {
                    setSessionFlash('msg', 'Cập nhật trạng thái đơn hàng thành công!');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Cập nhật trạng thái đơn hàng thất bại, vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                }

                redirect("/admin/order/detail?id=$orderId");
            }

            $taskIds = $filteredData['task_ids'] ?? [];
            if (empty($taskIds)) {
                setSessionFlash('msg', 'Không có cập nhật nào được thực hiện!');
                setSessionFlash('msg_type', 'danger');
                redirect("/admin/order/detail?id=$orderId");
            } else {
                $clear = $this->orderModel->clearOrderTasksStatus($orderId);
                if ($clear) {
                    $check = false;
                    for ($i = 0; $i < count($taskIds); $i++) {
                        $taskId = $taskIds[$i];
                        $check = $this->orderModel->updateTaskStatusByOrderIdAndTaskId($orderId, $taskId, 1);
                    }
                    if ($check) {
                        setSessionFlash('msg', 'Cập nhật trạng thái công việc thành công!');
                        setSessionFlash('msg_type', 'success');
                    } else {
                        setSessionFlash('msg', 'Cập nhật trạng thái công việc thất bại, vui lòng thử lại!');
                        setSessionFlash('msg_type', 'danger');
                    }
                } else {
                    setSessionFlash('msg', 'Cập nhật trạng thái công việc thất bại, vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                }
                redirect("/admin/order/detail?id=$orderId");
            }
        }
    }
    public function orderDelete()
    {
        if (isGet()) {
            $filteredData = filterData('get');
            $orderId = $filteredData['id'] ?? null;
            if (!$orderId || empty($this->orderModel->getOrderById($orderId))) {
                setSessionFlash('msg', 'Đơn hàng không tồn tại!');
                setSessionFlash('msg_type', 'danger');
                redirect('/admin/order');
            } else {
                $deleted = $this->orderModel->deleteOrderById($orderId);
                if ($deleted) {
                    setSessionFlash('msg', 'Xóa đơn hàng thành công!');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Xóa đơn hàng thất bại, vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                }
                redirect('/admin/order');
            }
        }
    }
    public function showContact()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
            'contactList' => $this->contactModel->getAllContacts()
        ];
        $this->renderView($this->viewPath . 'contacts/index', $data);
    }
    public function updateContactStatus()
    {
        if(isPost()){
            $filteredData = filterData('post');
            $contactId = $filteredData['contact_id'] ?? null;
            $newStatus = $filteredData['status'] ?? null;
            if (!$contactId || empty($this->contactModel->getContactByID($contactId))) {
                setSessionFlash('msg', 'Liên hệ không tồn tại!');
                setSessionFlash('msg_type', 'danger');
                redirect('/admin/contact');
            } else {
                $allowedStatuses = ['new', 'contacted', 'closed'];
                if (!in_array($newStatus, $allowedStatuses, true)) {
                    setSessionFlash('msg', 'Trạng thái liên hệ không hợp lệ!');
                    setSessionFlash('msg_type', 'danger');
                    redirect('/admin/contact');
                }
                $updated = $this->contactModel->updateContactStatusById($contactId, $newStatus);
                if ($updated) {
                    setSessionFlash('msg', 'Cập nhật trạng thái liên hệ thành công!');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Cập nhật trạng thái liên hệ thất bại, vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                }
                redirect('/admin/contact');
            } 
        }
    }
    public function contactDelete()
    {
        if(isGet()){
            $filteredData = filterData('get');
            $contactId = $filteredData['contact_id'] ?? null;
            if (!$contactId || empty($this->contactModel->getContactByID($contactId))) {
                setSessionFlash('msg', 'Liên hệ không tồn tại!');
                setSessionFlash('msg_type', 'danger');
                redirect('/admin/contact');
            } else {
                $deleted = $this->contactModel->deleteContactById($contactId);
                if ($deleted) {
                    setSessionFlash('msg', 'Xóa liên hệ thành công!');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Xóa liên hệ thất bại, vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                }
                redirect('/admin/contact');
            } 
        }
    }
    public function showContactDetail()
    {
        if (isGet()) {
            $filteredData = filterData('get');
            if (!empty($filteredData['contact_id'])) {
                $contactId = $filteredData['contact_id'];
                $contactInfo = $this->contactModel->getContactByID($contactId);
                if (!empty($contactInfo)) {
                    $userID = getSession('user_id');
                    $data = [
                        'userInfo' => $this->userModel->getUserByID($userID),
                        'contactInfo' => $contactInfo
                    ];
                    $this->renderView($this->viewPath . 'contacts/detail', $data);
                } else {
                    setSessionFlash('msg', 'Liên hệ không tồn tại!');
                    setSessionFlash('msg_type', 'danger');
                    redirect('/admin/contact');
                }
            } else {
                setSessionFlash('msg', 'Đường dẫn không hợp lệ!');
                setSessionFlash('msg_type', 'danger');
                redirect('/admin/contact');
            }
        }
    }
    public function showUser()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID)
        ];
        if(isGet()){
            $filteredData = filterData('get');
            if(!empty($filteredData['filter'])){
                if($filteredData['filter'] === 'all'){
                    $data['userList'] = $this->userModel->getAllUsers();
                    $data['currentFilter'] = 'all';
                } else {
                    $condition = "role = '" . $filteredData['filter'] . "'";
                    $data['userList'] = $this->userModel->getAllUsers($condition);
                    $data['currentFilter'] = $filteredData['filter'];
                }
            } else {
                $data['userList'] = $this->userModel->getAllUsers();
            }
        }
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
                    'password' => $password,
                    'role' => isset($filteredData['role']) ? $filteredData['role'] : null,
                    'status' => isset($filteredData['status']) ? $filteredData['status'] : null,
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
                    $data['userOrders'] = $this->orderModel->getOrdersByUserId($id);
                    $this->renderView($this->viewPath . 'users/profile', $data);
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
    public function updateUserStatus()
    {
        if(isGet()){
            $filteredData = filterData('get');
            $statusMapping = [
                'banned' => 2,
                'active' => 1, 
                'inactive' => 0
            ];
            if (isset($filteredData['user_id']) && isset($filteredData['status'])) {
                $id = $filteredData['user_id'];
                $status = $statusMapping[$filteredData['status']];
                $checkUser = $this->userModel->getUserByID($id);
                if (!empty($checkUser)) {
                    $updated = $this->userModel->updateUserStatusByID($id, $status);
                    if ($updated) {
                        setSessionFlash('msg', 'Cập nhật trạng thái người dùng thành công!');
                        setSessionFlash('msg_type', 'success');
                    } else {
                        setSessionFlash('msg', 'Cập nhật trạng thái người dùng thất bại, vui lòng thử lại!');
                        setSessionFlash('msg_type', 'danger');
                    }
                    redirect('/admin/user');
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

    public function showProfile()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
        ];
        $this->renderView($this->viewPath . 'profile', $data);
    }

    public function updateProfile()
    {
        if (isPost()) {
            $filteredData = filterData();
            $errors = [];
            $userID = getSession('user_id');
            $currentUser = $this->userModel->getUserByID($userID);
            //validate name
            $name = trim($filteredData['name']);
            if (validateName($name) !== true) {
                $errors['name'] = validateName($name);
            }
            //validate phone
            $phone = trim($filteredData['phone']);
            if (validatePhone($phone) !== true) {
                $errors['phone'] = validatePhone($phone);
            }
            $email = $filteredData['email'];
            if(validateEmail($email) !== true) {
                $errors['email'] = validateEmail($email);
            } else {
                //check email in db
                if ($email !== $currentUser['email']) {
                    $checkMail = $this->userModel->countUsersByEmail($email);
                    if ($checkMail > 0) {
                        $errors['email'] = 'Email đã tồn tại';
                    }
                }
            }
            if (empty($errors)) {
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                ];
                $checkUpdate = $this->userModel->updateUserByID($data, $userID);
                if ($checkUpdate) {
                    setSessionFlash('msg', 'Cập nhật thông tin thành công!');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Cập nhật thông tin thất bại. Vui lòng thử lại!');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Dữ liệu không hợp lệ. Vui lòng sửa các lỗi bên dưới!');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                setSessionFlash('old_data', $filteredData);
            }
        }
        redirect('/admin/profile');
    }
}
