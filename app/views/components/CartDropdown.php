<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$cartItems = $_SESSION['cart_dropdown'] ?? [];$total = 0;
foreach ($cartItems as $item) {
    $price = !empty($item['variants'][0]['price']) 
        ? (float)$item['variants'][0]['price'] 
        : (float)($item['price'] ?? 0);
    $total += $price * $item['qty'];
}

?>

<div class="cart-dropdown-inner">
    <?php if (!empty($cartItems)): ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>X</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $id => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td>$<?= number_format( $item['price'], 2) ?></td>

                        <td>$<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                        <td><button onclick="removeFromCart('<?= $id ?>')">X</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- <?php //include "app/views/components/PaymentButton.php"; ?> -->
        <button class="btn-submit" onclick="goToCheckout()">Pay</button>

        <script>
        function goToCheckout() {
            // Pasamos el total por query string
            const total = <?= json_encode($total) ?>; 
             window.location.href = `index.php?page=cart&action=show`;
            // window.location.href = `index.php?page=stripe&action=checkout&amount=${total}`;
        }
        </script>

    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>



<style>
.cart-container { position: relative; display: inline-block; }
.cart-count { position: absolute; top:-8px; right:-8px; background:red; color:white; width:18px; height:18px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; }
.cart-dropdown { display:none; position:absolute; right:0; top:30px; background:white; border:1px solid #ddd; min-width:350px; z-index:100; box-shadow:0 2px 8px rgba(0,0,0,0.2); padding:10px; }
.cart-dropdown.open { display:block; }
.cart-dropdown table { width:100%; border-collapse:collapse; text-align:center; }
.cart-dropdown th, .cart-dropdown td { padding:5px; border-bottom:1px solid #eee; }
.buy-btn { width:100%; padding:8px; margin-top:5px; background:#333; color:white; border:none; cursor:pointer; border-radius:5px; }

.cart-modal {
    position: fixed;
    inset: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0,0,0,0.4);
}

.cart-modal-content {
    position: relative;
    background: white;
    padding: 20px;
    border-radius: 6px;
    text-align: center;
}

.cart-close-btn {
    position: absolute;
    top: -10px;
    right: -10px;
    background-color: var(--color-primary, #333);
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    font-size: 18px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>

<script>
document.getElementById('cart-icon').addEventListener('mouseover', () => {
    document.getElementById('cart-dropdown').classList.add('open');
});
document.getElementById('cart-container').addEventListener('mouseleave', () => {
    document.getElementById('cart-dropdown').classList.remove('open');
});

function removeFromCart(id){
    fetch(`index.php?page=cart&action=remove&id=${id}`).then(()=>location.reload());
}

function closeCartModal(){
    document.getElementById('cart-modal').style.display = 'none';
}

</script>
<script>
const cartIcon = document.getElementById('cart-icon');
const cartDropdown = document.getElementById('cart-dropdown');
const cartContainer = document.getElementById('cart-container');

// Abrir dropdown al hacer hover o click (según tu preferencia)
cartIcon.addEventListener('click', () => {
    cartDropdown.classList.toggle('open');
});

// Función para cerrar dropdown
function closeCartDropdown() {
    cartDropdown.classList.remove('open');
}

// Cerrar si se hace click fuera del dropdown
document.addEventListener('click', (e) => {
    if (!cartContainer.contains(e.target)) {
        closeCartDropdown();
    }
});

// Mantener abierto al interactuar dentro del dropdown
cartDropdown.addEventListener('click', (e) => {
    e.stopPropagation();
});
</script>
