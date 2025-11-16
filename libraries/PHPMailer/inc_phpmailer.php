<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Rutas absolutas para los archivos PHPMailer
require_once __DIR__ . '/src/Exception.php';
require_once __DIR__ . '/src/PHPMailer.php';
require_once __DIR__ . '/src/SMTP.php';

$mail = new PHPMailer(true);

try {

    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = $host;
    $mail->SMTPAuth   = $SMTPAuth;
    $mail->Username   = $Username;
    $mail->Password   = $Password;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = $Port;

    // Destinatarios
    $mail->setFrom($mailerFrom, $mailerId);

    if(empty($mailerTo)){
        throw new Exception("No se ha definido un destinatario (\$mailerTo)");
    }
    $mail->addAddress($mailerTo);  
    if(!empty($mailerToToo)) $mail->addAddress($mailerToToo);

    $mail->addReplyTo($mailerReplay);
    $mail->addCC($mailerFrom);

    // Archivos adjuntos
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    if (isset($_FILES['attachment']) && !empty($_FILES['attachment']['name'][0])) {
        foreach ($_FILES['attachment']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['attachment']['name'][$key]);
            $targetFilePath = $uploadDir . $fileName;
            if (move_uploaded_file($tmpName, $targetFilePath)) {
                $mail->addAttachment($targetFilePath);
            }
        }
    }

    // Formato del correo
    $mail->isHTML(true);
    $body = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $body);
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->send();
    $_SESSION['status'] = "Envío exitoso";
    echo $messageSent ?? "Correo enviado";

} catch (Exception $e) {
    echo $messageError ?? "Mensaje no enviado";
    echo " Mailer Error: {$mail->ErrorInfo}";
}
