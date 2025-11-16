<?php
namespace App\Models;

use mysqli;
use Exception;
use App\Models\QuoteDetail; // Necesario si usás updateOperationDetailsByOperationId()

class Model
{
    protected $mysqli;
    protected $table;

    public function __construct()
    {
        global $mysqli; // Usa la conexión definida en connection.php
        $this->mysqli = $mysqli;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    /* =====================
       CRUD BÁSICO
    ===================== */

    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $result = $this->mysqli->query($sql);

        if (!$result) {
            throw new Exception('Error en la consulta: ' . $this->mysqli->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getByCategory($lang, $category)
    {
        // 1️⃣ Obtener la categoría por su nombre
        $sql = "SELECT id FROM categories WHERE name = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('s', $category);
        $stmt->execute();
        $categoryResult = $stmt->get_result();
    
        if ($categoryResult->num_rows === 0) {
            return []; // no existe la categoría
        }
    
        $categoryData = $categoryResult->fetch_assoc();
        $category_id = $categoryData['id'];
    
        // 2️⃣ Obtener productos de esa categoría
        $sql = "SELECT * FROM {$this->table} WHERE category_id = ?";
        $stmtProducts = $this->mysqli->prepare($sql);
        $stmtProducts->bind_param('i', $category_id);
        $stmtProducts->execute();
        $productsResult = $stmtProducts->get_result();
    
        $products = $productsResult->fetch_all(MYSQLI_ASSOC);
    
        // 3️⃣ (Opcional) Obtener variantes de cada producto
        foreach ($products as &$product) {
            $stmtVar = $this->mysqli->prepare("SELECT * FROM product_variants WHERE product_id = ?");
            $stmtVar->bind_param('i', $product['id']);
            $stmtVar->execute();
            $variants = $stmtVar->get_result()->fetch_all(MYSQLI_ASSOC);
            $product['variants'] = $variants;
        }
    
        return $products;
    }
    


    public function create($data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $stmt = $this->mysqli->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        if (!$stmt) {
            throw new Exception("Error en prepare(): " . $this->mysqli->error);
        }

        // Tipos dinámicos
        $types = '';
        $params = [];
        foreach ($data as $value) {
            if (is_int($value)) $types .= 'i';
            elseif (is_float($value)) $types .= 'd';
            else $types .= 's';
            $params[] = $value;
        }

        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new Exception("Error en execute(): " . $stmt->error);
        }

        return $this->mysqli->insert_id;
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function delete($id)
    {
        $stmt = $this->mysqli->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /* =====================
       MÉTODOS AUXILIARES
    ===================== */

    public function getByAlias($alias)
    {
        $sql = "SELECT * FROM {$this->table} WHERE alias = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $alias);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getUserByUsername($username)
    {
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getProductWithVariants($id)
    {
        // Producto
        $stmt = $this->mysqli->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if (!$product) return null;

        // Variantes
        $stmtVar = $this->mysqli->prepare("SELECT * FROM product_variants WHERE product_id = ?");
        $stmtVar->bind_param('i', $id);
        $stmtVar->execute();
        $variants = $stmtVar->get_result()->fetch_all(MYSQLI_ASSOC);

        $product['variants'] = $variants;
        return $product;
    }

    public function getTotal()
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table}";
        $result = $this->mysqli->query($sql);

        if (!$result) {
            throw new \Exception("Error al contar registros: " . $this->mysqli->error);
        }

        return $result->fetch_assoc()['total'] ?? 0;
    }

    // public function getProductsWithVariantsByCategory($category)
    // {
    //     // 🔍 Obtener todos los productos de la categoría
    //     $stmt = $this->mysqli->prepare("SELECT * FROM {$this->table} WHERE category = ?");
    //     $stmt->bind_param('s', $category);
    //     $stmt->execute();
    //     $productsResult = $stmt->get_result();

    //     $products = [];

    //     while ($product = $productsResult->fetch_assoc()) {
    //         $productId = $product['id'];

    //         // 🧬 Obtener las variantes de cada producto
    //         $stmtVar = $this->mysqli->prepare("SELECT * FROM product_variants WHERE product_id = ?");
    //         $stmtVar->bind_param('i', $productId);
    //         $stmtVar->execute();
    //         $variants = $stmtVar->get_result()->fetch_all(MYSQLI_ASSOC);

    //         $product['variants'] = $variants;
    //         $products[] = $product;
    //     }

    //     return $products;
    // }

    public function getAllPaginated($limit, $offset)
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC LIMIT ? OFFSET ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function searchByName($name)
    {
        $sql = "SELECT * FROM {$this->table} WHERE name LIKE ? ORDER BY name ASC";
        $stmt = $this->mysqli->prepare($sql);
        $searchTerm = "%{$name}%";
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function searchByDateRange($startDate, $endDate, $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE created_at BETWEEN ? AND ? ORDER BY created_at DESC";

        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT ? OFFSET ?";
        }

        $stmt = $this->mysqli->prepare($sql);
        if ($limit !== null && $offset !== null) {
            $stmt->bind_param("ssii", $startDate, $endDate, $limit, $offset);
        } else {
            $stmt->bind_param("ss", $startDate, $endDate);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalByDateRange($startDate, $endDate)
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table} WHERE created_at BETWEEN ? AND ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'] ?? 0;
    }

    public function updateById($id, $data)
    {
        $columns = array_keys($data);
        $values = array_values($data);

        $setClause = implode(' = ?, ', $columns) . ' = ?';
        $sql = "UPDATE {$this->table} SET $setClause, updated_at = NOW() WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);

        // Detecta tipos dinámicamente
        $types = '';
        foreach ($values as $val) {
            if (is_int($val)) $types .= 'i';
            elseif (is_float($val)) $types .= 'd';
            else $types .= 's';
        }
        $types .= 'i';

        $values[] = $id;
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    public function updateOperationByBusinessId($id, $businessId)
    {
        $sql = "UPDATE {$this->table} SET business_id = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('ii', $businessId, $id);
        return $stmt->execute();
    }

    public function updateOperationDetailsByOperationId($operationId, $operationDetailData)
    {
        $this->mysqli->begin_transaction();

        try {
            $operationDetails = new QuoteDetail();

            foreach ($operationDetailData as $detailId => $data) {
                $operationDetails->updateById($detailId, $data);
            }

            $this->mysqli->commit();
            return true;
        } catch (Exception $e) {
            $this->mysqli->rollback();
            return false;
        }
    }
}
