<?php
class Order extends Database {
    private $tableName = 'orders';
    private $orderItems = 'order_items';
    private $orderTasks = 'order_tasks';
    private $packageTable = 'packages';
    private $categoryTable = 'categories';
    private $userTable = 'users';
    
    public function __construct() {
        parent::__construct();
    }
    public function getOrdersByUserId($userId)
    {
        $sql = "SELECT o.*, 
                    COALESCE(task_stats.total_tasks, 0) AS total_tasks,
                    COALESCE(task_stats.done_tasks, 0) AS done_tasks
                FROM " . $this->tableName . " o
                LEFT JOIN (
                    SELECT order_id,
                           COUNT(*) AS total_tasks,
                           SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS done_tasks
                    FROM " . $this->orderTasks . "
                    GROUP BY order_id
                ) task_stats ON task_stats.order_id = o.id
                WHERE o.user_id = :user_id
                ORDER BY o.created_at DESC, o.id DESC";
        $params = [
            'user_id' => $userId,
        ];
        return $this->getAll($sql, $params);
    }

    public function countOrdersByStatus($status)
    {
        $sql = "SELECT COUNT(*) as count FROM " . $this->tableName . " WHERE status = :status";
        $params = [
            'status' => $status,
        ];
        $result = $this->getOne($sql, $params);
        return $result ? $result['count'] : 0;
    }
    
    public function getOrderById($orderId)
    {
        $sql = "SELECT o.*, 
                    u.name AS user_name,
                    u.email AS user_email,
                    u.phone AS user_phone,
                    COALESCE(task_stats.total_tasks, 0) AS total_tasks,
                    COALESCE(task_stats.done_tasks, 0) AS done_tasks
                FROM " . $this->tableName . " o
                LEFT JOIN " . $this->userTable . " u ON u.id = o.user_id
                LEFT JOIN (
                    SELECT order_id,
                           COUNT(*) AS total_tasks,
                           SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS done_tasks
                    FROM " . $this->orderTasks . "
                    GROUP BY order_id
                ) task_stats ON task_stats.order_id = o.id
                WHERE o.id = :id";
        $params = [
            'id' => $orderId,
        ];
        return $this->getOne($sql, $params);
    }

    public function getOrderByIdAndUserId($orderId, $userId)
    {
        $sql = "SELECT o.*, 
                    COALESCE(task_stats.total_tasks, 0) AS total_tasks,
                    COALESCE(task_stats.done_tasks, 0) AS done_tasks
                FROM " . $this->tableName . " o
                LEFT JOIN (
                    SELECT order_id,
                           COUNT(*) AS total_tasks,
                           SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS done_tasks
                    FROM " . $this->orderTasks . "
                    GROUP BY order_id
                ) task_stats ON task_stats.order_id = o.id
                WHERE o.id = :id AND o.user_id = :user_id
                LIMIT 1";
        $params = [
            'id' => $orderId,
            'user_id' => $userId,
        ];
        return $this->getOne($sql, $params);
    }

    public function getOrderItemsById($orderId)
    {
        $sql = "SELECT oi.*, 
                    p.name AS package_name,
                    p.price AS package_price,
                    p.unit AS package_unit,
                    p.type AS package_type,
                    p.avatar AS package_avatar,
                    c.name AS category_name
                FROM " . $this->orderItems . " oi
                LEFT JOIN " . $this->packageTable . " p ON p.id = oi.package_id
                LEFT JOIN " . $this->categoryTable . " c ON c.id = p.category_id
                WHERE oi.order_id = :id
                ORDER BY oi.id ASC";
        $params = [
            'id' => $orderId,
        ];
        return $this->getAll($sql, $params);
    }

    public function getOrderTasksById($orderId)
    {
        $sql = "SELECT ot.*, p.name AS package_name FROM " . $this->orderTasks . " ot JOIN " . $this->packageTable . " p ON p.id = ot.task_id WHERE ot.order_id = :id ORDER BY ot.id ASC";
        $params = [
            'id' => $orderId,
        ];
        return $this->getAll($sql, $params);
    }

    public function createOrder($data)
    {
        $sql = "INSERT INTO " . $this->tableName . " (user_id, total_price, status, created_at) VALUES (:user_id, :total_price, :status, :created_at)";
        $params = [
            'user_id' => isset($data['user_id']) ? $data['user_id'] : null,
            'total_price' => isset($data['total_price']) ? $data['total_price'] : 0,
            'status' => isset($data['status']) ? $data['status'] : 'pending',
            'created_at' => isset($data['created_at']) ? $data['created_at'] : null,
        ];
        return $this->insert($sql, $params);
    }
    
    public function createOrderItem($data)  
    {
        $sql = "INSERT INTO " . $this->orderItems . " (order_id, package_id, quantity, created_at) VALUES (:order_id, :package_id, :quantity, :created_at)";
        $params = [
            'order_id' => isset($data['order_id']) ? $data['order_id'] : null,
            'package_id' => isset($data['package_id']) ? $data['package_id'] : null,
            'quantity' => isset($data['quantity']) ? $data['quantity'] : 1,
            'created_at' => isset($data['created_at']) ? $data['created_at'] : null,
        ];
        return $this->insert($sql, $params);
    }

    public function createOrderTask($orderId, $taskId)
    {
        $checkSql = "SELECT id FROM " . $this->orderTasks . " WHERE order_id = :order_id AND task_id = :task_id LIMIT 1";
        $check = $this->getOne($checkSql, ['order_id' => $orderId, 'task_id' => $taskId]);
        if (!empty($check)) {
            return true;
        }

        $sql = "INSERT INTO " . $this->orderTasks . " (order_id, task_id, status, created_at) VALUES (:order_id, :task_id, :status, :created_at)";
        $params = [
            'order_id' => $orderId,
            'task_id' => $taskId,
            'status' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        return $this->insert($sql, $params);
    }
    
    public function deleteOrderItemById($id)
    {
        $sql = "DELETE FROM " . $this->orderItems . " WHERE id = :id";
        $params = [
            'id' => $id,
        ];
        return $this->delete($sql, $params);
    }

    public function deleteOrderById($id)
    {
        $deleteItemsSql = "DELETE FROM " . $this->orderItems . " WHERE order_id = :order_id";
        $deletedItems = $this->delete($deleteItemsSql, ['order_id' => $id]);

        $deleteTasksSql = "DELETE FROM " . $this->orderTasks . " WHERE order_id = :order_id";
        $deletedTasks = $this->delete($deleteTasksSql, ['order_id' => $id]);

        $deleteOrderSql = "DELETE FROM " . $this->tableName . " WHERE id = :id";
        $deletedOrder = $this->delete($deleteOrderSql, ['id' => $id]);

        return $deletedItems && $deletedTasks && $deletedOrder;
    }

    public function updateOrderStatus($orderId, $status)
    {
        $sql = "UPDATE " . $this->tableName . " SET status = :status, updated_at = :updated_at WHERE id = :id";
        $params = [
            'id' => $orderId,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        return $this->update($sql, $params);
    }

    public function updateOrderPaymentByOrderId($orderId, $data)
    {
        $sql = "UPDATE " . $this->tableName . " SET payment_proof = :payment_proof, status = :status, updated_at = :updated_at WHERE id = :id";
        $params = [
            'id' => $orderId,
            'payment_proof' => isset($data['payment_proof']) ? $data['payment_proof'] : null,
            'status' => isset($data['status']) ? $data['status'] : null,
            'updated_at' => isset($data['updated_at']) ? $data['updated_at'] : null,
        ];
        return $this->update($sql, $params);
    }

    public function clearOrderTasksStatus($orderId)
    {
        $sql = "UPDATE " . $this->orderTasks . " SET status = 0, updated_at = :updated_at WHERE order_id = :order_id";
        $params = [
            'order_id' => $orderId,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        return $this->update($sql, $params);
    }

    public function updateTaskStatusByOrderIdAndTaskId($orderId, $taskId, $status)
    {
        $sql = "UPDATE " . $this->orderTasks . " SET status = :status, updated_at = :updated_at WHERE order_id = :order_id AND id = :id";
        $params = [
            'order_id' => $orderId,
            'id' => $taskId,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        return $this->update($sql, $params);
    }

    public function getAllOrders($condition = '')
    {
        $sql = "SELECT o.*, u.name as user_name FROM " . $this->tableName . 
                " o LEFT JOIN " . $this->userTable . " u ON o.user_id = u.id";
        if (!empty($condition)) {
            $sql .= " WHERE " . $condition;
        }
        return $this->getAll($sql);
    }
}