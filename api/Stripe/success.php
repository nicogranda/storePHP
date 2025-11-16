<section class='container'  style="margin-top:70px;margin-bottom:o;">
<?php
$orderId = $_SESSION['order_id'] ?? null;
echo "<h1>¡Gracias por tu compra!</h1>";
echo "<p>Tu pago ha sido procesado exitosamente.</p>";
echo "<a href='/'>Regresar a la página principal</a>";
echo "<p>Order #: " . $orderId . "</p>";
?>
</section>

