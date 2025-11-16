<?php
// views/components/OrderNotification.php

// Variables esperadas: $cartItems, $username, $address, $city, $state, $zipcode, 
// $amount, $discountAmount, $shippingPrice, $couponCode

// 1️⃣ Generar tabla HTML con los items del carrito
$itemsHtml = '';
if (!empty($cartItems)) {
    $itemsHtml .= "<table style='width:100%; border-collapse: collapse; margin-top: 10px;'>";
    $itemsHtml .= "<thead>
        <tr>
            <th style='border-bottom:1px solid #ddd; text-align:left; padding:6px;'>Producto</th>
            <th style='border-bottom:1px solid #ddd; text-align:center; padding:6px;'>Cantidad</th>
            <th style='border-bottom:1px solid #ddd; text-align:right; padding:6px;'>Precio</th>
            <th style='border-bottom:1px solid #ddd; text-align:right; padding:6px;'>Subtotal</th>
        </tr>
    </thead><tbody>";

    foreach ($cartItems as $item) {
        $name  = htmlspecialchars($item['name']);
        $qty   = (int)$item['qty'];
        $unitPrice = floatval($item['price'] ?? 0);
        $subtotal = $qty * $unitPrice;
        $priceFormatted = number_format($unitPrice, 2, ',', '.');
        $subtotalFormatted = number_format($subtotal, 2, ',', '.');

        $itemsHtml .= "<tr>
            <td style='border-bottom:1px solid #eee; padding:6px;'>$name</td>
            <td style='border-bottom:1px solid #eee; text-align:center; padding:6px;'>$qty</td>
            <td style='border-bottom:1px solid #eee; text-align:right; padding:6px;'>$$priceFormatted</td>
            <td style='border-bottom:1px solid #eee; text-align:right; padding:6px;'>$$subtotalFormatted</td>
        </tr>";
    }

    $itemsHtml .= "</tbody></table>";
} else {
    $itemsHtml = "<p><em>No se encontraron productos en tu carrito.</em></p>";
}

// 2️⃣ Calcular total final
$totalFinal = max(0, $amount - $discountAmount + $shippingPrice);

// 3️⃣ Preparar cuerpo del correo
$subject = 'Confirmación de tu compra ✔️';

$body = "
<p>Hola <strong>$username</strong>,</p>
<p>Hemos recibido tu pago de <strong>$" . number_format($totalFinal, 2, ',', '.') . "</strong>.</p>
<p>Tu pedido será enviado a:</p>
<p>$address<br>$city, $state $zipcode</p>
";

// Mensaje de cupón si aplica
if ($couponCode && $discountAmount > 0) {
    $body .= "<p>Se aplicó el cupón <strong>$couponCode</strong> con un descuento de <strong>$" . number_format($discountAmount, 2, ',', '.') . "</strong>.</p>";
}

// Mensaje de shipping si aplica
if ($shippingPrice > 0) {
    $body .= "<p>Se ha agregado el costo de envío: <strong>€" . number_format($shippingPrice, 2, ',', '.') . "</strong>.</p>";
}

$body .= "<p>Detalle de tu pedido:</p>
$itemsHtml
<p style='margin-top:15px;'>¡Gracias por tu compra!</p>";
?>
