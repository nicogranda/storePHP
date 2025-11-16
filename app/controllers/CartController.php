<?php
namespace App\Controllers;

require_once "app/models/Cart.php";
require_once "app/models/admin/Product.php";

use App\Models\Admin\Product;
use App\Models\Cart;

class CartController
{
// Agrega un producto al carrito
public function add()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity   = intval($_POST['quantity'] ?? 1);
    $variant_id = $_POST['variant_id'] ?? null;

    $productModel = new Product();
    $product = $productModel->getProductWithVariants($product_id);

    if (!$product) {
        $_SESSION['cart_message'] = 'Producto no encontrado';
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }

    // Asignar la primera variante por defecto si no se pasó
    if (!$variant_id && !empty($product['variants']) && isset($product['variants'][0]['id'])) {
        $variant_id = intval($product['variants'][0]['id']);
    }

    if (!$variant_id) {
        $_SESSION['cart_message'] = 'No hay variante seleccionada';
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }

    // Buscar la variante seleccionada
    $selectedVariant = null;
    foreach ($product['variants'] as $variant) {
        if (intval($variant['id']) === intval($variant_id)) {
            $selectedVariant = $variant;
            break;
        }
    }

    if (!$selectedVariant) {
        $_SESSION['cart_message'] = 'Variante no encontrada';
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }

    // Añadir al carrito usando la lógica de Cart
    $result = Cart::add($product_id, $quantity, $variant_id);

    // Actualizar cart_dropdown con la info limpia desde el modelo
    $_SESSION['cart_dropdown'] = Cart::getItems();

    // Guardar mensaje modal
    $_SESSION['item_message'] = [
        'message' => $result['message'] ?? 'Producto agregado al carrito',
        'product' => [
            'name'    => $product['name'],
            'variant' => $selectedVariant['attributes'] ?? [],
            'price'   => $selectedVariant['price'],
            'qty'     => $quantity
        ]
    ];

    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
} 

    // Elimina un producto del carrito
    public function remove()
    {
        // ✅ Start session safely
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // ✅ Get the product ID from GET parameter (sanitize input)
        $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
        if ($product_id > 0) {
            // ✅ Remove the product from the cart (model logic)
            Cart::remove($product_id);
    
            // ✅ Update the session dropdown
            $_SESSION['cart_dropdown'] = Cart::getItems();
        }
    
        // ✅ Redirect back to the previous page
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=cart';
        header("Location: $referer");
        exit;
    }
    

    // Limpia todo el carrito
    public function clear()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        Cart::clear();

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Muestra el dropdown del carrito
    public function showDropdown()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $cartItems = Cart::getItems(); // ahora ya vienen con name, price, qty y variants
        require "app/views/components/cartDropdown.php";
    }

      // Muestra el dropdown del carrito
      public function show()
      {
          if (session_status() === PHP_SESSION_NONE) session_start();
      
          $cartItems = Cart::getItems(); // ahora ya vienen con name, price, qty, width, height, weight
      
        //   var_dump($cartItems);
        //   exit;
          // 🧮 Obtener resumen de dimensiones
          $summary = Cart::getDimensionsSummary();
      
          // 🔹 Guardar en sesión para usar en USPS o cualquier otro script
          $_SESSION['cart_summary'] = [
              'total_weight' => $summary['total_weight'],
              'total_length' => $summary['total_length'],
              'max_width' => $summary['max_width'],
              'max_height' => $summary['max_height'],
          ];
      
          require "app/views/cart.php";
      }      
      
    // Devuelve el número total de items
    public function count()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return Cart::count();
    }
}
