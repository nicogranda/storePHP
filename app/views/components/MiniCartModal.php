<!-- Modal de confirmación -->
<?php if (isset($_SESSION['item_message'])): 
    $msg = $_SESSION['item_message'];
    unset($_SESSION['item_message']);
    $product = $msg['product'] ?? null;
?>
<div id="cart-modal" class="cart-modal">
    <div class="cart-modal-content">
        <button class="cart-close-btn" onclick="closeCartModal()">×y</button>
        <p><?= htmlspecialchars($msg['message']); ?></p>

        <?php if (is_array($product) && !empty($product['name'])): ?>
            <img 
                src="uploads/<?= htmlspecialchars($product['variants'][0]['image_url'] ?? 'default.png'); ?>" 
                alt="<?= htmlspecialchars($product['name']).htmlspecialchars($product['variants'][0]['image_url']); ?>" 
                width="100"
            />
            <p><?= htmlspecialchars($product['name']); ?></p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<script>


function closeCartModal() {
    document.getElementById('cart-modal').style.display = 'none';
}
</script>

<style>


/*  */
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
    max-width: 300px;
    width: 90%;
}

/* Botón de cierre estilo círculo en esquina */
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
}

</style>
