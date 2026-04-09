<?php

class UserController extends Controller
{
    private $userModel;
    private $packageModel;
    private $cartModel;
    public function __construct()
    {
        $this->userModel = new User();
        $this->packageModel = new Package();
        if(isLoginStrict($this->userModel)) {
            $userId = getSession('user_id');
            $this->cartModel = new Cart($userId);
        } else {
            $this->cartModel = new Cart();
        }
    }
    public function home()
    {
        if(isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data = [
                'user' => $this->userModel->getUserById($id),
                'cartItemCount' => $this->cartModel->countItemsInCart($id)
            ];
            $this->renderView('user/home', $data);
        } else {
            $this->renderView('user/home');
        }
    }
    public function showPackage()
    {
        if(isGet()){
            $filteredData = filterData('get');
            $filter = isset($filteredData['filter']) ? trim($filteredData['filter']) : '';
            $order = isset($filteredData['order']) ? trim($filteredData['order']) : '';
            $condition = '';
            if (!empty($filter)) {
                $condition .= " AND LOWER(TRIM(p.type)) = '" . strtolower($filter) . "'";
            }
            if ($order === 'price_asc') {
                $orderBy = 'p.price ASC';
            } elseif ($order === 'price_desc') {
                $orderBy = 'p.price DESC';
            } else {
                $orderBy = 'p.id ASC'; // default sắp xếp theo id
            }
            $data = [
                'combos' => $this->packageModel->getComboPackages(),
                'addons' => $this->packageModel->getAddonPackages($condition, $orderBy),
                'addonTypes' => $this->packageModel->getAllAddonType(),
                'choseType' => $filter,
                'order' => $order
            ];
            if(isLoginStrict($this->userModel)) {
                $id = getSession('user_id');
                $data['user'] = $this->userModel->getUserById($id);
                $data['cartItemCount'] = $this->cartModel->countItemsInCart($id);
                $this->renderView('user/packages/index', $data);
            } else {
                $this->renderView('user/packages/index', $data);
            }
        }    
    }
    public function addToCart()
    {
        if(isGet()) {
            $filteredData = filterData('get');
            $packageId = isset($filteredData['package_id']) ? trim($filteredData['package_id']) : '';
            $quantity = isset($filteredData['quantity']) ? (int) $filteredData['quantity'] : 1;
            if(isLoginStrict($this->userModel)) {
                $userId = getSession('user_id');
                // Thêm sản phẩm vào giỏ hàng của người dùng
                $check = $this->cartModel->addCart($userId, $packageId, $quantity);
                if ($check) {
                    setSessionFlash('msg', 'Sản phẩm đã được thêm vào giỏ hàng');
                    setSessionFlash('msg_type', 'success');
                    redirect('/package');
                } else {
                    setSessionFlash('msg', 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng');
                setSessionFlash('msg_type', 'danger');
                redirect('/login');
                exit();
            }
        }
    }
    //show thông tin liên hệ
    public function showContact()
    {
        if(isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data = [
                'user' => $this->userModel->getUserById($id),
                'cartItemCount' => $this->cartModel->countItemsInCart($id)
            ];
            $this->renderView('user/contact', $data);
        } else {
            $this->renderView('user/contact');
        }    
    }
}