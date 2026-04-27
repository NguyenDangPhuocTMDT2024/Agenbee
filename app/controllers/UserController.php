<?php

class UserController extends Controller
{
    private $userModel;
    private $packageModel;
    private $cartModel;
    private $contactModel;
    private $orderModel;
    public function __construct()
    {
        $this->userModel = new User();
        $this->packageModel = new Package();
        $this->contactModel = new Contact();
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
    public function showPackageDetail()
    {
        if (isGet()) {
            $filteredData = filterData('get');
            $packageId = isset($filteredData['id']) ? trim($filteredData['id']) : '';
            $package = $this->packageModel->getPackageById($packageId);
            if (empty($package)) {
                setSessionFlash('msg', 'Không tìm thấy gói dịch vụ');
                setSessionFlash('msg_type', 'danger');
                redirect('/package');
            }
            $data = [
                'package' => $package,
                'addons' => $this->packageModel->getAddonsByPackageID($packageId)
            ];
            if (isLoginStrict($this->userModel)) {
                $id = getSession('user_id');
                $data['user'] = $this->userModel->getUserById($id);
                $data['cartItemCount'] = $this->cartModel->countItemsInCart($id);
                $this->renderView('user/packages/detail', $data);
            } else {
                $this->renderView('user/packages/detail', $data);
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
                    redirect('/package');
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
            $orders = $this->orderModel->getOrdersByUserId($id);
            $data = [
                'user' => $this->userModel->getUserById($id),
                'cartItemCount' => $this->cartModel->countItemsInCart($id),
                'orderCount' => is_array($orders) ? count($orders) : 0,
            ];
            $this->renderView('user/profile', $data);
        } else {
            setSessionFlash('msg', 'Vui lòng đăng nhập để xem trang cá nhân');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
            exit();
        }
    }
    // public function updateProfile()
    // {
    //     if (isPost()) {
    //         $filteredData = filterData('post');
    //         $errors = validateShopInfo($filteredData);
    //         if (validateImage($_FILES['logo']) !== true) {
    //             $errors['logo'] = validateImage($_FILES['logo']);
    //         } else if (!empty($_FILES['logo']['name'])) {
    //             $logo = uploadImage($_FILES['logo']);
    //         }
    //         if (empty($errors)) {
    //             $data = [
    //                 'shop_name' => $filteredData['shop_name'],
    //                 'address' => $filteredData['address'],
    //                 'major' => $filteredData['major'],
    //                 'shop_description' => $filteredData['shop_description'],
    //                 'updated_at' => date('Y-m-d H:i:s')
    //             ];
    //             if (isset($logo)) {
    //                 $data['logo'] = $logo;
    //                 if (!empty($oldLogo)) {
    //                     removeUploadImg($oldLogo);
    //                 }
    //             }
    //             $checkUpdate = $this->shopInfoModel->updateShopInfoByUserId($filteredData['user_id'], $data);
    //             if ($checkUpdate) {
    //                 setSessionFlash('msg', 'Cập nhật thông tin shop thành công');
    //                 setSessionFlash('msg_type', 'success');
    //             } else {
    //                 setSessionFlash('msg', 'Có lỗi xảy ra khi cập nhật thông tin shop, vui lòng thử lại');
    //                 setSessionFlash('msg_type', 'danger');
    //             }
    //         } else {
    //             setSessionFlash('errors', $errors);
    //             setSessionFlash('msg', 'Có lỗi xảy ra khi cập nhật thông tin shop, vui lòng thử lại');
    //             setSessionFlash('msg_type', 'danger');
    //         }
    //     }
    //     redirect('/profile');
    // }
    public function showContact()
    {
        if (isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data['user'] = $this->userModel->getUserById($id);
            $data['cartItemCount'] = $this->cartModel->countItemsInCart($id);
            $this->renderView('user/contact', $data);
        } else {
            $this->renderView('user/contact');
        }
    }
    public function contact()
    {
        if (isPost()) {
            $filteredData = filterData('post');
            $errors = validateContact($filteredData);
            if (empty($errors)) {
                $data = [
                    'name' => $filteredData['name'],
                    'phone' => $filteredData['phone'],
                    'shop_status' => $filteredData['shop_status'],
                    'budget_range' => $filteredData['budget_range'],
                    'status' => 'new',
                    'message' => $filteredData['message'],
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $checkInsert = $this->contactModel->insertContact($data);
                if ($checkInsert) {
                    setSessionFlash('msg', 'Gửi liên hệ thành công, chúng tôi sẽ phản hồi trong thời gian sớm nhất');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Có lỗi xảy ra khi gửi liên hệ, vui lòng thử lại');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('errors', $errors);
                setSessionFlash('msg', 'Có lỗi xảy ra khi gửi liên hệ, vui lòng thử lại');
                setSessionFlash('msg_type', 'danger');
            }
        }
        redirect('/contact');
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
    public function showCheckOut()
    {
        if (!isLoginStrict($this->userModel)) {
            setSessionFlash('msg', 'Vui lòng đăng nhập để thanh toán');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
            exit();
        }

        $userId = getSession('user_id');
        $filteredData = isGet() ? filterData('get') : [];
        if (isset($filteredData['order_id'])) {
            $orderId = trim($filteredData['order_id']);
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
                'orderId' => $orderId,
                'orderItems' => $this->orderModel->getOrderItemsById($orderId),
            ];
            $this->renderView('user/checkout', $data);
        } else {
            //tạo đơn
            $cartInfo = $this->cartModel->getCartInfoByUserId($userId);
            if (empty($cartInfo)) {
                setSessionFlash('msg', 'Giỏ hàng của bạn đang trống, vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán');
                setSessionFlash('msg_type', 'warning');
                redirect('/cart');
                exit();
            } else {
                $totalPrice = 0;
                foreach ($cartInfo as $item) {
                    $totalPrice += ((int) $item['quantity']) * ((float) $item['price']);
                }
                $orderData = [
                    'user_id' => $userId,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $orderId = $this->orderModel->createOrder($orderData);
                if (empty($orderId)) {
                    setSessionFlash('msg', 'Có lỗi xảy ra khi tạo đơn hàng, vui lòng thử lại');
                    setSessionFlash('msg_type', 'danger');
                    redirect('/cart');
                    exit();
                } else {
                    foreach ($cartInfo as $item) {
                        $orderItemData = [
                            'order_id' => $orderId,
                            'package_id' => $item['package_id'],
                            'quantity' => $item['quantity'],
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        $this->orderModel->createOrderItem($orderItemData);
                    }
                    //xóa giỏ hàng
                    $this->cartModel->clearCartByUserId($userId);
                    $data = [
                        'user' => $this->userModel->getUserById($userId),
                        'cartItemCount' => $this->cartModel->countItemsInCart($userId),
                        'backToCart' => true,
                        'orderId' => $orderId,
                        'orderItems' => $this->orderModel->getOrderItemsById($orderId),
                    ];
                    $this->renderView('user/checkout', $data);
                }
            }
        }
    }
    public function checkout()
    {
        if (isPost()) {
            $filteredData = filterData('post');
            $orderId = isset($filteredData['order_id']) ? trim($filteredData['order_id']) : '';
            if (!isLoginStrict($this->userModel)) {
                setSessionFlash('msg', 'Vui lòng đăng nhập để tiếp tục');
                setSessionFlash('msg_type', 'danger');
                redirect('/login');
                exit();
            }
            $userId = getSession('user_id');
            $order = $this->orderModel->getOrderByIdAndUserId($orderId, $userId);
            if (empty($order)) {
                setSessionFlash('msg', 'Không tìm thấy đơn hàng');
                setSessionFlash('msg_type', 'danger');
                redirect('/order');
                exit();
            }
            if ($order['status'] !== 'pending') {
                setSessionFlash('msg', 'Đơn hàng đã được thanh toán hoặc đã hủy, không thể thanh toán lại');
                setSessionFlash('msg_type', 'warning');
                redirect('/order');
                exit();
            }
            if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
                $paymentProof = uploadImage($_FILES['payment_proof'], 'uploads/payment');
                if ($paymentProof !== false) {
                    $data = [
                        'payment_proof' => $paymentProof,
                        'status' => 'confirming',
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $check = $this->orderModel->updateOrderPaymentByOrderId($orderId, $data);
                    if ($check) {
                        $orderItems = $this->orderModel->getOrderItemsById($orderId);
                        foreach ($orderItems as $item) {
                            $this->orderModel->createOrderTask($orderId, $item['package_id']);
                        }
                        setSessionFlash('msg', 'Thanh toán thành công, chúng tôi sẽ xác nhận thanh toán của bạn trong thời gian sớm nhất');
                        setSessionFlash('msg_type', 'success');
                    } else {
                        setSessionFlash('msg', 'Có lỗi xảy ra khi cập nhật thông tin thanh toán, vui lòng thử lại');
                        setSessionFlash('msg_type', 'danger');
                    }
                } else {
                    setSessionFlash('msg', 'Có lỗi xảy ra khi tải lên minh chứng thanh toán, vui lòng thử lại');
                    setSessionFlash('msg_type', 'danger');
                }
            } else if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] !== UPLOAD_ERR_NO_FILE) {
                setSessionFlash('msg', 'Có lỗi xảy ra khi tải lên minh chứng thanh toán, vui lòng thử lại');
                setSessionFlash('msg_type', 'danger');
            }
        } else {
            setSessionFlash('msg', 'Vui lòng tải lên minh chứng thanh toán để hoàn tất quá trình thanh toán');
            setSessionFlash('msg_type', 'warning');
        }
        redirect('/order');
    }
    public function backToCart()
    {
        //hàm xử lý khi người dùng quay lại giỏ hàng sau khi tạo đơn hàng nhưng chưa thanh toán
        if (!isLoginStrict($this->userModel)) {
            setSessionFlash('msg', 'Vui lòng đăng nhập để tiếp tục');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
            exit();
        }
        $userId = getSession('user_id');
        $filteredData = isGet() ? filterData('get') : [];
        $orderId = isset($filteredData['order_id']) ? trim($filteredData['order_id']) : '';
        $order = $this->orderModel->getOrderByIdAndUserId($orderId, $userId);
        if (!empty($order)) {
            $orderItems = $this->orderModel->getOrderItemsById($orderId);
            if (!empty($orderItems)) {
                foreach ($orderItems as $item) {
                    $this->cartModel->addCart($userId, $item['package_id'], $item['quantity']);
                }
                $this->orderModel->deleteOrderById($orderId);
                setSessionFlash('msg', 'Đã thêm lại sản phẩm vào giỏ hàng');
                setSessionFlash('msg_type', 'success');
            } else {
                setSessionFlash('msg', 'Không tìm thấy sản phẩm trong đơn hàng');
                setSessionFlash('msg_type', 'warning');
            }
        } else {
            setSessionFlash('msg', 'Không tìm thấy đơn hàng');
            setSessionFlash('msg_type', 'danger');
        }
        redirect('/cart');
    }
    public function cancelOrder()
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
            if ($order['status'] !== 'pending') {
                setSessionFlash('msg', 'Chỉ có thể hủy đơn hàng ở trạng thái chờ thanh toán');
                setSessionFlash('msg_type', 'warning');
                redirect('/order');
                exit();
            }
            $check = $this->orderModel->updateOrderStatus($orderId, 'cancelled');
            if ($check) {
                setSessionFlash('msg', 'Đã hủy đơn hàng thành công');
                setSessionFlash('msg_type', 'success');
            } else {
                setSessionFlash('msg', 'Có lỗi xảy ra khi hủy đơn hàng, vui lòng thử lại');
                setSessionFlash('msg_type', 'danger');
            }
            redirect('/order');
        }
    }
}
