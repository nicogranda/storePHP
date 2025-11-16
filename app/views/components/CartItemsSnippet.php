<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$cartItems = $_SESSION['cart_dropdown'] ?? [];
$total = 0;

foreach ($cartItems as $item) {
    $price = !empty($item['variants'][0]['price']) 
        ? (float)$item['variants'][0]['price'] 
        : (float)($item['price'] ?? 0);
    $total += $price * $item['qty'];
}
?>

<?php if (!empty($cartItems)): ?>
    <table class="cart-table">
        <thead>
            <tr class='cart-header'>
                <th>Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $id => $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['qty'] ?></td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td>$<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                    <td><button class="btn-delete" onclick="removeFromCart('<?= $id ?>')">x</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>
