<?php
// Variables necesarias
$operation_id = $_GET['id'] ?? 0;
$total = $_GET['amount'] ?? 10;
$email = $_GET['business_email'] ?? 'nicgranda@gmail.com';
?>
<style>
#paymentModal { z-index: 1000; }
#stripeModal { z-index: 1100; }
.btn-Checkout, .buy-btn {
    background: var(--color-primary); 
    color: black; 
    border: none; 
    padding: 10px 20px; 
    border-radius:5px; c
    ursor:pointer; 
    text-decoration:none;
}
.modal-overlay {
    display: none; position: fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.5); justify-content:center; align-items:center;
}
.modal-content {
    position: relative; background:white; padding:20px; border-radius:8px; max-width:500px; width:90%;
}
.modal-close {
    position: absolute; 
    top:10px; 
    right:10px; 
    background:var(--color-primary); 
    color:black; 
    border:none; 
    border-radius:50%; 
    width:30px; 
    height:30px; 
    cursor:pointer;
}
#offlinePaymentFields { margin-top:10px; margin-left:40px; color:gray; font-size:12px; display:none; }
#bankFields { display:none; margin-top:10px; }
#stripeIframe { width:100%; height:400px; border:none; }
</style>

<!-- Botón para abrir PaymentModal -->
<a href="#" id="payButton" class="buy-btn">Pay</a>

<!-- Payment Modal -->
<section id="paymentModal" class="modal-overlay">
    <div class="modal-content">
        <button class="modal-close" id="closePaymentModal">&times;</button>
        <h2>Choice a Payment Method</h2>

        <form id="paymentForm" method="POST" action="index.php?page=stripe&action=checkout">
            <input type="hidden" id="operation_id" name="operation_id" value="<?= $operation_id ?>">
            <input type="hidden" id="operation_email" name="email" value="<?= $email ?>">

            <label><input type="radio" name="payment_method" value="stripe" checked> Tarjeta de Débito/Crédito</label><br>
            <label><input type="radio" name="payment_method" value="cash"> Cash</label><br>
            <label><input type="radio" name="payment_method" value="bank"> Transfer/Zelle</label>

            <div id="offlinePaymentFields">
                <label>Monto a abonar (€):<br>
                    <input type="number" step="0.01" name="amount_partial" placeholder="<?= number_format($total,2,'.',''); ?>">
                </label>
                <div id="bankFields">
                    <label>Nombre Banco:<br><input type="text" name="bank_name"></label><br>
                    <label>Número de Transacción:<br><input type="text" name="transaction_number"></label><br>
                    <label>Fecha:<br><input type="date" name="payment_date" value="<?= date('Y-m-d'); ?>"></label>
                </div>
            </div>

            <div style="margin-top:15px; text-align:right;">
                <button type="button" id="checkoutBtn" class="btn-Checkout">Checkout</button>
            </div>
        </form>
    </div>
</section>

<!-- Stripe Modal -->
<section id="stripeModal" class="modal-overlay">
    <div class="modal-content">
        <button class="modal-close" id="closeStripeModal">&times;</button>
        <iframe id="stripeIframe"></iframe>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentModal = document.getElementById('paymentModal');
    const stripeModal = document.getElementById('stripeModal');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const offlineFields = document.getElementById('offlinePaymentFields');
    const bankFields = document.getElementById('bankFields');

    const operationInput = document.getElementById('operation_id');
    const emailInput = document.getElementById('operation_email');

    // Abrir PaymentModal
    document.getElementById('payButton').addEventListener('click', e => {
        e.preventDefault();
        paymentModal.style.display = 'flex';
    });

    // Cerrar PaymentModal
    document.getElementById('closePaymentModal').addEventListener('click', () => {
        paymentModal.style.display = 'none';
    });

    // Cerrar Stripe Modal
    document.getElementById('closeStripeModal').addEventListener('click', () => {
        stripeModal.style.display = 'none';
        document.getElementById('stripeIframe').src = '';
        paymentModal.style.display = 'flex';
    });

    // Mostrar/ocultar campos offline
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if(this.value === 'stripe'){
                offlineFields.style.display = 'none';
            } else {
                offlineFields.style.display = 'block';
                bankFields.style.display = (this.value === 'bank') ? 'block' : 'none';
            }
        });
    });

    // Cerrar modales al hacer click fuera
    window.addEventListener('click', function(event){
        if(event.target === paymentModal) paymentModal.style.display = 'none';
        if(event.target === stripeModal){
            stripeModal.style.display = 'none';
            document.getElementById('stripeIframe').src = '';
            paymentModal.style.display = 'flex';
        }
    });

    // Función de Checkout
    checkoutBtn.addEventListener('click', function(){
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        if(method === 'stripe'){
            const total = <?= $total ?>;
            const operation = operationInput.value;
            const email = emailInput.value;
            const iframe = document.getElementById('stripeIframe');

            // Validación simple
            if(!total || isNaN(parseFloat(total))){
                alert("Monto inválido");
                return;
            }

            iframe.src = `app/libraries/Stripe/index.php?amount=${total}&operation=${operation}&email=${email}`;
            stripeModal.style.display = 'flex';
            paymentModal.style.display = 'none';
        } else {
            // Pago offline: submit al backend
            document.getElementById('paymentForm').submit();
        }
    });
});
</script>