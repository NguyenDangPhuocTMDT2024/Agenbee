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
    public function showCart()
    {
        if(isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data = [
                'user' => $this->userModel->getUserById($id),
                'cartItemCount' => $this->cartModel->countItemsInCart($id),
                'cartInfo' => $this->cartModel->getCartInfoByUserId($id)
            ];
            $this->renderView('user/cart', $data);
        } else {
            $this->renderView('user/home');
        }
    }
    public function updateCart()
    {
        if(isPost()) {
            $filteredData = filterData('post');
            $packageId = isset($filteredData['package_id']) ? trim($filteredData['package_id']) : '';
            $action = isset($filteredData['action']) ? trim($filteredData['action']) : '';
            $currentQuantity = isset($filteredData['current_quantity']) ? (int) $filteredData['current_quantity'] : 1;
            if(isLoginStrict($this->userModel)) {
                $userId = getSession('user_id');
                $quantityChange = 0;
                if ($action === 'increase') {
                    $quantityChange = 1;
                } elseif ($action === 'decrease') {
                    $quantityChange = -1;
                }
                if ($quantityChange !== 0) {
                    $quantityChange = $currentQuantity + $quantityChange;
                    $check = $this->cartModel->updateCartItem($userId, $packageId, $quantityChange);
                    if ($check) {
                        setSessionFlash('msg', 'Cập nhật giỏ hàng thành công');
                        setSessionFlash('msg_type', 'success');
                    } else {
                        setSessionFlash('msg', 'Có lỗi xảy ra khi cập nhật giỏ hàng');
                        setSessionFlash('msg_type', 'danger');
                    }
                }
                redirect('/cart'); 
            } else {
                setSessionFlash('msg', 'Vui lòng đăng nhập để cập nhật giỏ hàng');
                setSessionFlash('msg_type', 'danger');
                redirect('/login');
                exit();
            }
        }
    }
    public function removeCartItem()
    {
        if(isGet()) {
            $filteredData = filterData('get');
            $packageId = isset($filteredData['package_id']) ? trim($filteredData['package_id']) : '';
            if(isLoginStrict($this->userModel)) {
                $userId = getSession('user_id');
                $check = $this->cartModel->removeCartItem($userId, $packageId);
                if ($check) {
                    setSessionFlash('msg', 'Sản phẩm đã được xóa khỏi giỏ hàng');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng');
                    setSessionFlash('msg_type', 'danger');
                }
                redirect('/cart');
            } else {
                setSessionFlash('msg', 'Vui lòng đăng nhập để xóa sản phẩm khỏi giỏ hàng');
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