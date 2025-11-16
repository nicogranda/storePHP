<?php
// Incluir configuración de correo
    require_once '../../app/config/email.php';

    // Generar el contenido del correo       
    include '../../app/views/mail.php';
 
    // Enviar el correo
    require_once __DIR__ . '/../../app/libraries/inc_phpmailer.php';
    ?>