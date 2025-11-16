<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartCount = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cartCount = array_sum($_SESSION['cart']);
}
?>

<div class="mini-cart-container">
    <i class="fa-solid fa-cart-shopping" id="cart-icon"></i>
    <span id="cart-count" class="cart-count">
        <?= $cartCount ?>
    </span>

    <div id="cart-dropdown" class="cart-dropdown">
        <?php include "app/views/components/MiniCartDropdown.php"; ?>
    </div>
</div>

<!-- Modal de confirmación -->
<?php //include MiniCartModal.php;?>

<script>
document.getElementById('cart-icon').addEventListener('click', () => {
    document.getElementById('cart-dropdown').classList.toggle('open');
});

function closeCartModal() {
    document.getElementById('cart-modal').style.display = 'none';
}
</script>

<style>
.mini-cart-container {
    position: relative;
    display: inline-block;
}

.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: red;
    color: white;
    font-size: 12px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cart-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 30px;
    background: white;
    border: 1px solid #ddd;
    min-width: 250px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.cart-dropdown.open {
    display: block;
}

/*  */
/* .cart-modal {
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
    max-width: 300px;
    width: 90%;
}

.cart-close-btn {
    position: absolute;
    top: -10px;
    right: -10px;
    background-color: var(--color-primary);
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
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    transition: transform 0.2s;
}

.cart-close-btn:hover {
    transform: scale(1.1);
} */

</style>
