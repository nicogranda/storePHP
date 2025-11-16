<?php include "app/views/components/CartItemsSnippet.php"; ?>

<?php if (!empty($_SESSION['cart_dropdown'])): ?>
    <button class="mini-cart-btn-submit" onclick="goToCart()">View Cart</button>
<?php endif; ?>

<script>
function goToCart() {
    window.location.href = 'index.php?page=cart&action=show';
}
function removeFromCart(id){
    fetch(`index.php?page=cart&action=remove&id=${id}`).then(()=>location.reload());
}
</script>
