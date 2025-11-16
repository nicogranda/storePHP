<!-- CartButton -->
<form action="index.php?page=cart&action=add" method="POST" class="cart-form">
    <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>" />
    <button 
        type="submit" 
        class="add-to-cart-btn"
        data-product-name="<?= htmlspecialchars($product['name']); ?>"
        data-product-image="<?= htmlspecialchars($product['image_url']); ?>"
    >
         Add to Cart
    </button>
</form>

<style>
    .add-to-cart-btn {
        display: block;
        margin: 0 auto; 
        background-color: transparent;
        border: 2px solid black;
        border-radius: 15px;
        padding: 8px 18px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .add-to-cart-btn:hover {
        background-color: var(--color-primary) !important;
        color: white;
        transform: scale(1.05);
    }

    .add-to-cart-btn:active {
        transform: scale(0.97);
        opacity: 0.85;
    }

    .cart-form {
        display: inline-block;
        margin: 0;
    }
</style>

<script>
(() => {
    const form = document.currentScript.previousElementSibling.previousElementSibling; 
    const button = form.querySelector('.add-to-cart-btn');
    
    button.addEventListener('click', e => {
    });
})();

</script>
