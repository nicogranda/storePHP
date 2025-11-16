<!-- <a href="#" id="payButton" class="buy-btn">Pay</a> -->
<?php 
$operation_id = $_GET['id'] ?? 0;
$total = $_GET['amount'] ?? 10;
$email = $_GET['business_email'] ?? 'nicgranda@gmail.com';

        include 'app/views/components/PaymentModal.php'; 
        ?>
<script>
    // function openStripeWindow() {
    //     // window.open('app/libraries/Stripe/index.php', '_blank', 'width=600,height=700');
    // }
    // function openStripeModal(total) {
    //     var email = document.getElementById("operation_email").value;
    //     var operation = document.getElementById("operation_id").value;
    //     var modal = document.getElementById("stripeModal");
    //     var iframe = document.getElementById("stripeIframe");
    //     iframe.src = `app/libraries/stripe/index.php?amount=${total}&operation=${operation}&email=${email}`; // Pasa el monto a Stripe
    //     // Asegura que 'total' esté en el formato correcto
    //     if (!total || isNaN(parseFloat(total))) {
    //         alert("El monto no es válido");
    //         return;
    //     }

    //     // Muestra el modal
    //     modal.style.display = "block";
    // }

    // // Cerrar modal al hacer clic en el botón de cerrar
    // document.getElementById("closeModal").addEventListener("click", function () {
    //     document.getElementById("stripeModal").style.display = "none";
    //     document.getElementById("stripeIframe").src = ''; // Limpia el iframe
    // });

    // // Cerrar el modal al hacer clic fuera de él
    // window.onclick = function (event) {
    //     const modal = document.getElementById('stripeModal');
    //     if (event.target === modal) {
    //         const iframe = document.getElementById('stripeIframe');
    //         iframe.src = ''; 
    //         modal.style.display = 'none';
    //     }
    // };

    // window.onclick = function (event) {
    //     const modal = document.getElementById('stripeModal');
    //     if (event.target === modal) {
    //         const iframe = document.getElementById('stripeIframe');
    //         iframe.src = ''; 
    //         modal.style.display = 'none';
    //     }
    // };
</script>