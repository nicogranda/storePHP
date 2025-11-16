<?php
namespace App\Models;

class Order
{
    protected $tableOrders = 'orders';
    protected $tableDetails = 'order_details';
    protected $db; // generic DB connection

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Create an order and its details
     *
     * @param array $orderData ['user_id', 'total', 'shipping_address', 'shipping_zip', 'shipping_cost', 'discount_code', 'discount_amount']
     * @param array $items Array of items with keys: ['variant_id', 'qty', 'price']
     * @return int Order ID
     * @throws \Exception
     */
    public function createOrder(array $orderData, array $items): int
    {
        $this->db->begin_transaction();

        try {
            // Insert main order
            $stmt = $this->db->prepare("
                INSERT INTO {$this->tableOrders} 
                (user_id, order_date, total, shipping_address, shipping_zip, shipping_cost, discount_code, discount_amount, status)
                VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, 'pending')
            ");

            $userId = $orderData['user_id'] ?? null;
            $total = $orderData['total'] ?? 0;
            $address = $orderData['shipping_address'] ?? '';
            $zip = $orderData['shipping_zip'] ?? '';
            $shippingCost = $orderData['shipping_cost'] ?? 0;
            $discountCode = $orderData['discount_code'] ?? '';
            $discountAmount = $orderData['discount_amount'] ?? 0;

            $stmt->bind_param(
                "idssdss",
                $userId,
                $total,
                $address,
                $zip,
                $shippingCost,
                $discountCode,
                $discountAmount
            );

            $stmt->execute();
            $orderId = $stmt->insert_id;
            $stmt->close();

            // Insert order details
            $stmtDetail = $this->db->prepare("
                INSERT INTO {$this->tableDetails} 
                (order_id, variant_id, quantity, price, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");

            foreach ($items as $item) {
                // Validate required fields
                if (empty($item['variant_id'])) {
                    throw new \Exception("Missing variant_id for a product in the cart");
                }

                $variantId = (int)$item['variant_id'];
                $qty = (int)($item['qty'] ?? 1);
                $price = (float)($item['price'] ?? 0.0);

                $stmtDetail->bind_param("iiid", $orderId, $variantId, $qty, $price);
                $stmtDetail->execute();
            }

            $stmtDetail->close();

            $this->db->commit();
            return $orderId;

        } catch (\Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Get order details by order ID
     *
     * @param int $orderId
     * @return array
     */
    public function getOrderDetails(int $orderId): array
    {
        $sql = "
            SELECT od.*, o.user_id, o.total, o.shipping_address, o.shipping_zip, o.shipping_cost, o.discount_code, o.discount_amount, o.status
            FROM {$this->tableDetails} od
            JOIN {$this->tableOrders} o ON od.order_id = o.id
            WHERE od.order_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $details;
    }

    /**
 * Obtener todas las órdenes paginadas
 */
public function getAllPaginated(int $limit, int $offset): array
{
    $sql = "
    SELECT 
        o.id AS order_id,
        o.user_id,
        o.total,
        o.status,
        o.order_date,
        o.shipping_address,
        o.shipping_zip,
        o.shipping_cost,
        o.discount_code,
        o.discount_amount,
        u.email AS user_email
    FROM {$this->tableOrders} o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.order_date DESC
    LIMIT ? OFFSET ?
";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $orders;
}


/**
 * Obtener total de órdenes (opcionalmente filtradas por búsqueda)
 */
public function getTotal(string $search = ''): int
{
    $sql = "SELECT COUNT(*) AS total FROM {$this->tableOrders}";

    if (!empty($search)) {
        $sql .= " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $search);
    } else {
        $stmt = $this->db->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return (int)$row['total'];
}

/**
 * Buscar orden por ID
 */
public function searchById(int $id): array
{
    $sql = "
        SELECT o.id AS order_id,
               o.user_id,
               o.total,
               o.status,
               o.order_date,
               o.discount_code,
               o.discount_amount,
               u.email AS user_email
        FROM {$this->tableOrders} o
        JOIN users u ON o.user_id = u.id
        WHERE o.id = ?
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();

    return $order ?: [];
}

}
