<?php
namespace App\Models;

class Portfolio
{
    protected $table = 'products';
    protected $db; // genérico para cualquier conexión MySQLi

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Obtener todos los productos con sus variantes y atributos
     */
    public function getAll($lang)
    {
        $sql = "
            SELECT 
                p.id AS product_id,
                p.language,
                p.name,
                p.sku AS product_sku,
                p.description,
                p.unit,
                p.category_id,
                p.vat_rate,
                v.id AS variant_id,
                v.sku AS variant_sku,
                v.price,
                v.stock,
                v.weight,
                v.image_url AS image_url,
                v.is_active,
                pa.id AS attribute_id,
                pa.attribute,
                pa.attribute_value
            FROM products p
            LEFT JOIN product_variants v ON v.product_id = p.id
            LEFT JOIN variant_attributes pa ON pa.variant_id = v.id
            LEFT JOIN categories c ON c.id = p.category_id
            WHERE p.language = ? 
              AND c.language = ?
            ORDER BY p.id, v.id
        ";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("ss", $lang, $lang);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $products;
    }

    /**
     * Obtener un producto por ID con sus variantes y atributos
     */
    public function getById($product_id)
    {
        $sql = "
            SELECT 
                p.id AS product_id,
                p.language,
                p.name,
                p.sku AS product_sku,
                p.description,
                p.unit,
                p.category_id,
                p.vat_rate,
                v.id AS variant_id,
                v.sku AS variant_sku,
                v.price,
                v.stock,
                v.weight,
                v.image_url AS image_url,
                v.is_active,
                pa.id AS attribute_id,
                pa.attribute,
                pa.attribute_value
            FROM products p
            LEFT JOIN product_variants v ON v.product_id = p.id
            LEFT JOIN variant_attributes pa ON pa.variant_id = v.id
            WHERE p.id = ?
            ORDER BY v.id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        $stmt->close();
        return $product ?: null;
    }

    /**
     * Obtener productos por categoría con variantes y atributos
     */
    public function getByCategory($lang, $category)
    {
        $sql = "
            SELECT 
                p.id AS product_id,
                p.language,
                p.name,
                p.sku AS product_sku,
                p.description,
                p.unit,
                p.category_id,
                p.vat_rate,
                v.id AS variant_id,
                v.sku AS variant_sku,
                v.price,
                v.stock,
                v.weight,
                v.image_url AS image_url,
                v.is_active,
                pa.id AS attribute_id,
                pa.attribute,
                pa.attribute_value,
                c.name AS category_name
            FROM products p
            LEFT JOIN product_variants v ON v.product_id = p.id
            LEFT JOIN variant_attributes pa ON pa.variant_id = v.id
            LEFT JOIN categories c ON c.id = p.category_id
            WHERE p.language = ?
              AND c.language = ?
              AND c.name = ?
            ORDER BY p.id, v.id
        ";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("sss", $lang, $lang, $category);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $products;
    }
}
