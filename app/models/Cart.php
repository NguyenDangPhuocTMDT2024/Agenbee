<?php

class Cart extends Database
{
    private $tableName = 'carts';
    private $cartItemTable = 'cart_items';
    public function __construct($userId = null)
    {
        parent::__construct();
        if ($userId) {
            $sql = "SELECT * FROM $this->tableName WHERE user_id = :user_id";
            $param = ['user_id' => $userId];
            $cart = $this->getOne($sql, $param);
            if (!$cart) {
                // Nếu chưa có giỏ hàng, tạo mới
                $insertSql = "INSERT INTO $this->tableName (user_id) VALUES (:user_id)";
                $this->insert($insertSql, ['user_id' => $userId]);
            }
        }
    }
    public function countItemsInCart($userId)
    {
        $sql = "SELECT * FROM $this->cartItemTable WHERE cart_id = (SELECT id FROM $this->tableName WHERE user_id = :user_id)";
        $param = ['user_id' => $userId];
        return $this->count($sql, $param);
    }
    public function getCartInfoByUserId($userId)
    {
        $sql = "SELECT * FROM $this->tableName
                JOIN $this->cartItemTable ON $this->tableName.id = $this->cartItemTable.cart_id
                JOIN `packages` ON $this->cartItemTable.package_id = `packages`.id
                WHERE user_id = :user_id";
        $param = ['user_id' => $userId];
        return $this->getOne($sql, $param);
    }
    public function addCart($userId, $packageId, $quantity)
    {
        // Lấy thông tin giỏ hàng của người dùng
        $sql = "SELECT * FROM $this->tableName WHERE user_id = :user_id";
        $param = ['user_id' => $userId];
        $cart = $this->getOne($sql, $param);
        if ($cart) {
            $cartId = $cart['id'];
            // Kiểm tra nếu sản phẩm đã tồn tại trong giỏ hàng
            $checkSql = "SELECT * FROM $this->cartItemTable WHERE cart_id = :cart_id AND package_id = :package_id";
            $checkParam = ['cart_id' => $cartId, 'package_id' => $packageId];
            $existingItem = $this->getOne($checkSql, $checkParam);
            $currentDate = date('Y-m-d H:i:s');
            if ($existingItem) {
                // Nếu đã tồn tại, cập nhật số lượng
                $newQuantity = $existingItem['quantity'] + $quantity;
                $updateSql = "UPDATE $this->cartItemTable SET quantity = :quantity, updated_at = :updated_at WHERE id = :id";
                $updateParam = ['quantity' => $newQuantity, 'updated_at' => $currentDate, 'id' => $existingItem['id']];
                $updateItems = $this->update($updateSql, $updateParam);

                $sqlUpdateCart = "UPDATE $this->tableName SET updated_at = :updated_at WHERE id = :id";
                $updateCart = $this->update($sqlUpdateCart, ['updated_at' => $currentDate, 'id' => $cartId]);
                return $updateItems && $updateCart;
            } else {
                // Nếu chưa tồn tại, thêm mới vào giỏ hàng
                $insertSql = "INSERT INTO $this->cartItemTable (cart_id, package_id, quantity, created_at) VALUES (:cart_id, :package_id, :quantity, :created_at)";
                $insertParam = ['cart_id' => $cartId, 'package_id' => $packageId, 'quantity' => $quantity, 'created_at' => $currentDate];
                $updateItems = $this->insert($insertSql, $insertParam);

                $updateCart = "UPDATE $this->tableName SET updated_at = :updated_at WHERE id = :id";
                $updateCart = $this->update($updateCart, ['updated_at' => $currentDate, 'id' => $cartId]);
                return $updateItems && $updateCart;
            }
        }
        return false;        
    }
    //public function deleteFromCart($userId, $packageId)
    //public function clearCart($userId)
    //public function updateCartItem($userId, $packageId, $quantity)
}