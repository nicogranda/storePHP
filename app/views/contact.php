<?php if (!defined('ACCESS_GRANTED')) die('Acceso denegado'); ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<section class="hero"></section>

<h1 class='principal'>Agencia de Marketing</h1>

<section id="container" style="padding:20px 0;">

<form id="contact-form" action="index.php?page=contact&action=mail" method="post" novalidate>

    <h2 style="padding: 20px;font-weight:500;text-align: center; font-size:24px">Contacto</h2>
    
    <label for="name">Nombre:</label>
    <input type="text" id="name" name="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
    <small class="error-message" id="name-error">Este campo es obligatorio.</small>

    <label for="email">Correo Electrónico:</label>
    <input type="email" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
    <small class="error-message" id="email-error">Introduce un correo válido.</small>

    <label for="message">Mensaje:</label>
    <textarea id="message" name="message" rows="4"><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
    <small class="error-message" id="message-error">Este campo es obligatorio.</small>

    <div class="recaptcha-wrapper">
        <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA_SITE_KEY ?>"></div>
    </div>

    <button type="submit">Enviar</button>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
        <p class="error"><?= htmlspecialchars($_GET['message']) ?></p>
    <?php endif; ?>

    <?php 
        if (isset($_SESSION['status'])) {
            echo "<p class='status-message'>" . $_SESSION['status'] . "</p>";
            unset($_SESSION['status']);
        }
    ?>
    
</form>
</section>

<script>
document.getElementById('contact-form').addEventListener('submit', function(event) {
    let valid = true;

    const name = document.getElementById('name');
    const nameError = document.getElementById('name-error');
    if (!name.value.trim()) {
        nameError.style.display = 'block';
        valid = false;
    } else {
        nameError.style.display = 'none';
    }

    const email = document.getElementById('email');
    const emailError = document.getElementById('email-error');
    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!email.value.trim() || !emailPattern.test(email.value.trim())) {
        emailError.style.display = 'block';
        valid = false;
    } else {
        emailError.style.display = 'none';
    }

    const message = document.getElementById('message');
    const messageError = document.getElementById('message-error');
    if (!message.value.trim()) {
        messageError.style.display = 'block';
        valid = false;
    } else {
        messageError.style.display = 'none';
    }

    if (!valid) {
        event.preventDefault();
    }
});
</script>

<style>
form {
    max-width: 400px;
    margin: auto;
}
label {
    display: block;
    margin-bottom: 8px;
}
input, textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
small.error-message {
    display: none;
    color: red;
    font-size: 0.8em;
    margin-bottom: 10px;
}
.error {
    color: red;
    text-align: center;
    font-weight: bold;
}
.status-message {
    color: green;
    text-align: center;
}
button {
    padding: 10px 15px;
    background-color: var(--color-primary, #F15A24);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
}
/* button:hover {
    background-color: #d54a1a;
} */
.recaptcha-wrapper {
    width: 100%;
    overflow: hidden;
    position: relative;
    padding-top: 78px;
}
.g-recaptcha {
    transform: scale(1);
    transform-origin: top left;
    position: absolute;
    top: 0;
    left: 0;
}
</style>

