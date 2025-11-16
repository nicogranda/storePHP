<?php
namespace App\Models;

require_once "app/models/admin/Product.php";

use App\Models\Admin\Product;

class Cart
{
    public static function add($product_id, $quantity = 1)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $productModel = new Product();
        $product = $productModel->getById($product_id);

        if (!$product) {
            return [
                'success' => false,
                'message' => 'Producto no encontrado'
            ];
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + $quantity;

        return [
            'success' => true,
            'message' => "Producto '{$product['name']}' añadido al carrito",
            'total_items' => array_sum($_SESSION['cart']),
            'product' => [
                'id' => $product_id,
                'name' => $product['name'],
                'variants' => $product['variants'] ?? [],
                'price' => $product['price'] ?? 0
            ]
        ];
    }

    public static function getItems()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $items = $_SESSION['cart'] ?? [];
        if (empty($items)) return [];

        $productModel = new Product();
        $detailedItems = [];

        foreach ($items as $id => $qty) {
            $product = $productModel->getProductWithVariants($id);
            if (!$product) continue;
        
            $price = $product['variants'][0]['price'] 
                ?? $product['price'] 
                ?? 0;
        
                $variant = $product['variants'][0] ?? [];

                $detailedItems[$id] = [
                    'name' => $product['name'],
                    'variants' => $product['variants'],
                    'price' => $price,
                    'qty' => $qty,
                    'weight' => $variant['weight'] ?? 0,
                    'width'  => $variant['width'] ?? 0,
                    'height' => $variant['height'] ?? 0,
                    'length' => $variant['length'] ?? 0
                ];
                
        }        

        return $detailedItems;
    }

    public static function remove($product_id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public static function clear()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['cart'] = [];
    }

    public static function count()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return array_sum($_SESSION['cart'] ?? []);
    }

    public static function getDimensionsSummary(): array
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $items = self::getItems(); // ya trae width, height, weight por producto
    if (empty($items)) {
        return [
            'total_weight' => 0,
            'total_length' => 0,
            'max_width' => 0,
            'max_height' => 0
        ];
    }

    $total_weight = 0;
    $total_length = 0;
    $max_width = 0;
    $max_height = 0;

    foreach ($items as $item) {
        $qty = $item['qty'] ?? 1;
        $weight = floatval($item['weight'] ?? 0);
        $width  = floatval($item['width'] ?? 0);
        $height = floatval($item['height'] ?? 0);
        $length = floatval($item['length'] ?? 0); // si lo tenés

        // 🧮 Sumar peso total (por cantidad)
        $total_weight += $weight * $qty;

        // 📏 Sumar la altura (apilado)
        $total_length += $height * $qty;

        // 📐 Buscar los máximos (ancho y largo)
        if ($width > $max_width) $max_width = $width;
        if ($length > $max_height) $max_height = $length;
    }

    return [
        'total_weight' => $total_weight,
        'total_length' => $total_length,
        'max_width' => $max_width,
        'max_height' => $max_height
    ];
}

}
