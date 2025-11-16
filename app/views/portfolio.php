<?php
// Activar reportes de error para desarrollo (no en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<section class="hero" style="margin-top: 70px;">
    <?php 
    $hero_file = "app/views/partials/hero.php";
    if (file_exists($hero_file)) {
        include $hero_file;
    } else {
        echo "<p style='color:red;'>Error: archivo hero.php no encontrado.</p>";
    }
    ?>
</section>
<!-- Email -->
<?php 
$suscription_file = "app/views/components/suscription.php";
if (file_exists($suscription_file)) {
    include $suscription_file;
} else {
    echo "<p style='color:red;'>Error: archivo suscription.php no encontrado.</p>";
}
?>

<div style="margin-top:30px;margin-bottom:0;">
    <h2 class="principal">COLLECION</h2>
</div>

<?php 
$categories_file = "app/views/components/categories.php";
if (file_exists($categories_file)) {
    include $categories_file;
} else {
    echo "<p style='color:red;'>Error: archivo categories.php no encontrado.</p>";
}
?>

<section class="portfolio">
    <?php 
    if (!empty($products) && is_array($products)) {
        foreach ($products as $product): ?>
            <div class="product-item">
            <a href="index.php?page=card&action=show&product_id=<?= urlencode($product['product_id']); ?>">
                    <img src="<?= "uploads/" . htmlspecialchars($product['image_url']); ?>" 
                         title="<?= htmlspecialchars($product['name']); ?>">
            </a>
                <h2 class="product-name"><?= htmlspecialchars($product['name']); ?></h2>

                <div class="price-container">
                    <div class="price-placeholder"></div>
                    <p class="price">$<?= number_format($product['price'], 2); ?></p>
                </div>

                <?php 
                $cart_file = "app/views/components/CartButton.php";
                if (file_exists($cart_file)) {
                    include $cart_file;
                } else {
                    echo "<p style='color:red;'>Error: archivo cartButton.php no encontrado.</p>";
                }
                ?>
            </div>
        <?php endforeach;
    } else {
        echo "<p style='color:red;'>No hay productos disponibles o \$products no esta�� definido.</p>";
    }
    ?>
</section>

<?php 
$about_file = "app/views/about.php";
if (file_exists($about_file)) {
    include $about_file;
} else {
    echo "<p style='color:red;'>Error: archivo about.php no encontrado.</p>";
}
?>
