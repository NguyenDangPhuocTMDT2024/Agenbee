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
            $id = $filteredData['id'];
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
                            setSessionFlash('msg', 'Xóa không thành công, vui lòng thử lại!');
                            setSessionFlash('msg_type', 'danger');
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
        if(isPost()){
            $filteredData = filterData();
            $errors = [];
            $errors = validateCategory($filteredData);
            if(empty($errors)){
                $data = [
                    'name' => $filteredData['name'],
                    'description' => $filteredData['description'],
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $checkInsert = $this->categoryModel->createCategory($data);
                if($checkInsert){
                    setSessionFlash('msg','Thêm danh mục thành công!');
                    setSessionFlash('msg_type','success');
                    redirect('/admin/package');
                } else{
                    setSessionFlash('msg','Dữ liệu không hợp lệ, vui lòng sửa lại!');
                    setSessionFlash('msg_type','danger');
                }
            } else{
                setSessionFlash('msg','Dữ liệu không hợp lệ, vui lòng sửa lại!');
                setSessionFlash('msg_type','danger');
                setSessionFlash('errors', $errors);
                setSessionFlash('old_data',$filteredData);
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
    public function showDetail()
    {
        $userID = getSession('user_id');
        $data = [
            'userInfo' => $this->userModel->getUserByID($userID),
        ];
        $this->renderView($this->viewPath . 'orders/detail', $data);
    }
}
