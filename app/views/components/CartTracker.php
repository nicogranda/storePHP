<?php
require_once __DIR__ . "/../../models/Cart.php"; // Ajusta según tu carpeta
use App\Models\Cart;

// Obtener los artículos del carrito actual
$cartItems = Cart::getItems();

// Si no hay carrito, evitar renderizar JS innecesario
if (empty($cartItems)) return;

// Si tienes acceso a datos del producto, puedes enriquecer el JSON
// Por ahora dejamos id y cantidad
?>
<script>
window.dataLayer = window.dataLayer || [];

(function() {
    const cartItems = <?= json_encode($cartItems) ?>;

    // Emitimos un evento para GTM
    window.dataLayer.push({
        event: 'cart_updated',
        cart_items: Object.entries(cartItems).map(([id, qty]) => ({
            id,
            quantity: qty
        })),
        total_items: Object.values(cartItems).reduce((a, b) => a + b, 0)
    });
})();
</script>
