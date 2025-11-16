<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$config = require 'config.php';
$email = $_POST['email'];
$total = $_POST['amount'];
// $_SESSION['email'] = $email;
// $_SESSION['amount'] = $total;


$_SESSION['email'] = $_POST['email'] ?? '';
$_SESSION['username'] = $_POST['username'] ?? '';
$_SESSION['address'] = $_POST['address'] ?? '';
$_SESSION['zipcode'] = $_POST['zipcode'] ?? '';
$_SESSION['city'] = $_POST['city'] ?? '';
$_SESSION['state'] = $_POST['state'] ?? '';
$_SESSION['amount'] = $_POST['amount'] ?? 0;
$_SESSION['coupon_code'] = $_POST['coupon_code'] ?? '';
$_SESSION['discount_amount'] = $_POST['discount_amount'] ?? 0;
$_SESSION['shipping_price'] = $_POST['shipping_price'] ?? 0;


?>

<section class="body-container">
<form id="payment-form" class="form-container">
    <h2 class="form-title">Checkout</h2>
    <div>
        <label>Amount:</label>
        <span id="amount"><?= number_format($total, 2, ',', '.') ?> €</span>
    </div>
    <div>
        <label>Card</label>
        <div id="card-element" class="card-input"></div>
    </div>
    <button id="submit" class="btn-submit">Pagar</button>
    <div id="card-errors" class="error-message"></div>
</form>

<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const stripe = Stripe("<?= $config['stripe']['public_key'] ?>");
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const form = document.getElementById('payment-form');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        document.getElementById('card-errors').textContent = '';

        try {
            // Crear método de pago
            const { error, paymentMethod } = await stripe.createPaymentMethod({
                type: 'card',
                card: card,
                billing_details: { email: <?= json_encode($email) ?> }
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                return;
            }

            // Enviar al backend
            const response = await fetch('api/Stripe/process_payment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    payment_method: paymentMethod.id,
                    amount: <?= json_encode($total * 100) ?>,
                    email: <?= json_encode($email) ?>
                })
            });

            const result = await response.json();

            if (result.clientSecret) {
                const { error: confirmError, paymentIntent } = await stripe.confirmCardPayment(result.clientSecret);

                if (confirmError) {
                    document.getElementById('card-errors').textContent = confirmError.message;
                } else if (paymentIntent.status === 'succeeded') {

                    // Después de confirmar pago exitoso
                     window.location.href = 'index.php?page=stripe&action=success';

                } else {
                    alert('Pago incompleto: ' + paymentIntent.status);
                }
            } else if (result.status === 'error') {
                alert('Error: ' + result.message);
            } else {
                alert('Hubo un problema con el pago.');
            }

        } catch (err) {
            console.error('Fetch error:', err);
            alert('Hubo un problema con el pago.');
        }
    });
});
</script>
</section>

