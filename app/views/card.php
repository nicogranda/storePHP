<?php
// Evita errores si $product no está definido
if (!isset($product)) {
    echo "<p style='color:red;'>Producto no encontrado.</p>";
    return;
}
?>

<section class="product-card">
    <h1><?= htmlspecialchars($product['name']); ?></h1>
    <img src="uploads/<?= htmlspecialchars($product['image_url']); ?>" 
         alt="<?= htmlspecialchars($product['name']); ?>" 
         style="max-width:400px;">
    <p class="price">$<?= number_format($product['price'], 2); ?></p>
    <p class="description"><?= htmlspecialchars($product['description'] ?? 'No hay descripción'); ?></p>

    <!-- Botón de carrito -->
    <?php 
    $cart_file = "app/views/components/CartButton.php";
    if (file_exists($cart_file)) {
        include $cart_file;
    }
    ?>
</section>
