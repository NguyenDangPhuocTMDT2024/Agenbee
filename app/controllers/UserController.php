<?php

class UserController extends Controller
{
    private $userModel;
    private $packageModel;
    private $cartModel;
    private $contactModel;
    private $shopInfoModel;
    private $orderModel;
    public function __construct()
    {
        $this->userModel = new User();
        $this->packageModel = new Package();
        $this->contactModel = new Contact();
        $this->shopInfoModel = new ShopInfo();
        $this->orderModel = new Order();
        if (isLoginStrict($this->userModel)) {
            $userId = getSession('user_id');
            $this->cartModel = new Cart($userId);
        } else {
            $this->cartModel = new Cart();
        }
    }
    public function home()
    {
        $data = [
            'combos' => $this->packageModel->getComboPackages()
        ];
        if (isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data['user'] = $this->userModel->getUserById($id);
            $data['cartItemCount'] = $this->cartModel->countItemsInCart($id);
            $this->renderView('user/home', $data);
        } else {
            $this->renderView('user/home');
        }
    }
    public function showPackage()
    {
        if (isGet()) {
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
            if (isLoginStrict($this->userModel)) {
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
        if (isGet()) {
            $filteredData = filterData('get');
            $packageId = isset($filteredData['package_id']) ? trim($filteredData['package_id']) : '';
            $quantity = isset($filteredData['quantity']) ? (int) $filteredData['quantity'] : 1;
            if (isLoginStrict($this->userModel)) {
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
        if (isLoginStrict($this->userModel)) {
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
        if (isPost()) {
            $filteredData = filterData('post');
            $packageId = isset($filteredData['package_id']) ? trim($filteredData['package_id']) : '';
            $action = isset($filteredData['action']) ? trim($filteredData['action']) : '';
            $currentQuantity = isset($filteredData['current_quantity']) ? (int) $filteredData['current_quantity'] : 1;
            if (isLoginStrict($this->userModel)) {
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
        if (isGet()) {
            $filteredData = filterData('get');
            $packageId = isset($filteredData['package_id']) ? trim($filteredData['package_id']) : '';
            if (isLoginStrict($this->userModel)) {
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
    public function showProfile()
    {
        if (isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data = [
                'user' => $this->userModel->getUserById($id),
                'cartItemCount' => $this->cartModel->countItemsInCart($id),
                'shopInfo' => $this->shopInfoModel->getShopInfoById($id)
            ];
            $this->renderView('user/profile/index', $data);
        } else {
            setSessionFlash('msg', 'Vui lòng đăng nhập để xem trang cá nhân');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
            exit();
        }
    }
    public function updateProfile()
    {
        if (isPost()) {
            $filteredData = filterData('post');
            $errors = validateShopInfo($filteredData);
            if (validateImage($_FILES['logo']) !== true) {
                $errors['logo'] = validateImage($_FILES['logo']);
            } else if(!empty($_FILES['logo']['name'])) {
                $logo = uploadImage($_FILES['logo']);
            }
            if (empty($errors)) {
                $data = [
                    'shop_name' => $filteredData['shop_name'],
                    'address' => $filteredData['address'],
                    'major' => $filteredData['major'],
                    'shop_description' => $filteredData['shop_description'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                if(isset($logo)) {  
                    $data['logo'] = $logo;
                    if(!empty($oldLogo)) {
                        removeUploadImg($oldLogo);
                    }
                }
                $checkUpdate = $this->shopInfoModel->updateShopInfoByUserId($filteredData['user_id'], $data);
                if ($checkUpdate) {
                    setSessionFlash('msg', 'Cập nhật thông tin shop thành công');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Có lỗi xảy ra khi cập nhật thông tin shop, vui lòng thử lại');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('errors', $errors);
                setSessionFlash('msg', 'Có lỗi xảy ra khi cập nhật thông tin shop, vui lòng thử lại');
                setSessionFlash('msg_type', 'danger');
            }
        }
        redirect('/profile');
    }
    public function showOrder()
    {
        $id = getSession('user_id');
        $orders = $this->orderModel->getOrdersByUserId($id);
        
        $data = [
            'user' => $this->userModel->getUserById($id),
            'cartItemCount' => $this->cartModel->countItemsInCart($id),
            'orders' => $orders,
        ];
        $this->renderView('user/orders/index', $data);
    }
    public function showOrderDetail()
    {
        if (isGet()) {
            $filteredData = filterData('get');
            $orderId = isset($filteredData['id']) ? trim($filteredData['id']) : '';
            $userId = getSession('user_id');
            $order = $this->orderModel->getOrderByIdAndUserId($orderId, $userId);
            if (empty($order)) {
                setSessionFlash('msg', 'Không tìm thấy đơn hàng');
                setSessionFlash('msg_type', 'danger');
                redirect('/order');
                exit();
            }
            $data = [
                'user' => $this->userModel->getUserById($userId),
                'cartItemCount' => $this->cartModel->countItemsInCart($userId),
                'order' => $order,
                'orderItems' => $this->orderModel->getOrderItemsById($orderId),
                'orderTasks' => $this->orderModel->getOrderTasksById($orderId),
            ];
            $this->renderView('user/orders/detail', $data);
        }
    }

    public function showOrderConfirm()
    {
        if (!isLoginStrict($this->userModel)) {
            setSessionFlash('msg', 'Vui lòng đăng nhập để điền thông tin setup');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
            exit();
        }

        $userId = getSession('user_id');
        $filteredData = filterData('get');
        $selectedOrderId = isset($filteredData['order_id']) ? trim($filteredData['order_id']) : '';

        if ($selectedOrderId === '') {
            $cartItems = $this->cartModel->getCartInfoByUserId($userId);
            if (empty($cartItems)) {
                setSessionFlash('msg', 'Giỏ hàng đang trống, vui lòng thêm gói trước khi thanh toán');
                setSessionFlash('msg_type', 'danger');
                redirect('/cart');
                exit();
            }

            $this->orderModel->conn->beginTransaction();
            try {
                $totalPrice = 0;
                foreach ($cartItems as $item) {
                    $linePrice = isset($item['price']) ? (float) $item['price'] : 0;
                    $lineQty = isset($item['quantity']) ? (int) $item['quantity'] : 0;
                    $totalPrice += ($linePrice * $lineQty);
                }
                $now = date('Y-m-d H:i:s');

                $orderId = $this->orderModel->createOrder([
                    'user_id' => $userId,
                    'total_price' => $totalPrice,
                    'duration' => 14,
                    'status' => 'pending',
                    'created_at' => $now,
                ]);

                if (!$orderId) {
                    throw new Exception('Không thể tạo đơn hàng mới');
                }

                $addonTaskMap = [];

                foreach ($cartItems as $item) {
                    $itemInserted = $this->orderModel->createOrderItem([
                        'order_id' => $orderId,
                        'package_id' => isset($item['package_id']) ? (int) $item['package_id'] : null,
                        'quantity' => isset($item['quantity']) ? (int) $item['quantity'] : 1,
                        'created_at' => $now,
                    ]);

                    if (!$itemInserted) {
                        throw new Exception('Không thể tạo chi tiết đơn hàng');
                    }

                    $categoryName = strtolower(trim((string) ($item['category_name'] ?? '')));
                    $packageId = isset($item['package_id']) ? (int) $item['package_id'] : 0;
                    if ($packageId <= 0) {
                        continue;
                    }

                    if ($categoryName === 'add-on' || $categoryName === 'addon') {
                        $addonTaskMap[$packageId] = true;
                    }

                    if ($categoryName === 'combo') {
                        $comboAddons = $this->packageModel->getAddonsByPackageID($packageId);
                        foreach ($comboAddons as $addon) {
                            $addonId = isset($addon['id']) ? (int) $addon['id'] : 0;
                            if ($addonId > 0) {
                                $addonTaskMap[$addonId] = true;
                            }
                        }
                    }
                }

                foreach (array_keys($addonTaskMap) as $addonTaskId) {
                    $taskInserted = $this->orderModel->createOrderTask($orderId, (int) $addonTaskId);
                    if (!$taskInserted) {
                        throw new Exception('Không thể tạo task cho đơn hàng');
                    }
                }

                $cleared = $this->cartModel->clearCartByUserId($userId);
                if (!$cleared) {
                    throw new Exception('Không thể làm trống giỏ hàng sau khi tạo đơn');
                }

                $this->orderModel->conn->commit();

                setSessionFlash('msg', 'Đã tạo đơn hàng mới, vui lòng điền thông tin setup để bắt đầu triển khai');
                setSessionFlash('msg_type', 'success');
                redirect('/order/confirm?order_id=' . $orderId);
                exit();
            } catch (Exception $e) {
                $this->orderModel->conn->rollBack();
                setSessionFlash('msg', 'Không thể khởi tạo đơn hàng từ giỏ hàng, vui lòng thử lại');
                setSessionFlash('msg_type', 'danger');
                redirect('/cart');
                exit();
            }
        }

        $selectedOrder = $this->orderModel->getOrderByIdAndUserId($selectedOrderId, $userId);
        if (empty($selectedOrder)) {
            setSessionFlash('msg', 'Đơn hàng không hợp lệ');
            setSessionFlash('msg_type', 'danger');
            redirect('/order');
            exit();
        }

        $data = [
            'user' => $this->userModel->getUserById($userId),
            'cartItemCount' => $this->cartModel->countItemsInCart($userId),
            'orders' => $this->orderModel->getOrdersByUserId($userId),
            'selectedOrderId' => $selectedOrderId,
            'selectedOrder' => $selectedOrder,
        ];
        $this->renderView('user/checkout/confirm', $data);
    }
}
