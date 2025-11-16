<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirige a login si no hay sesión activa
if (empty($_SESSION['user_id'])) {
    // '../' sube un nivel desde admin/ a la raíz del proyecto
    header('Location: ../index.php?page=admin&action=auth');
    exit();
}
