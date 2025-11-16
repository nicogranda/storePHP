<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
     $mail->isSMTP();                             //Send using SMTP
   $mail->Host       = $host;                   //Set the SMTP server to send through
   $mail->SMTPAuth   = $SMTPAuth;               //Enable SMTP authentication
   $mail->Username   = $Username;               //SMTP username
   $mail->Password   = $Password;                //SMTP password
   $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
   $mail->Port       = $Port ;                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
   
    //Recipients
    $mail->setFrom($mailerFrom, $idMailer);
    $mail->addAddress($mailerTo);     //Add a recipient
    $mail->addAddress($mailerToToo);  //Name is optional
    $mail->addReplyTo($mailerReplay, 'Information');
    
    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = $_POST['subject'];
    $mail->Body    = $message;
    $mail->AltBody = 'Este es el cuerpo en texto plano para clientes que no soportan HTML';

    // Directorio donde se guardarán los archivos subidos
    $uploadDir = 'uploads/';

    // Asegúrate de que el directorio de subida exista y sea escribible
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Procesar los archivos subidos
    if (isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
        foreach ($_FILES['archivos']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['archivos']['name'][$key]);
            $targetFilePath = $uploadDir . $fileName;

            // Mover el archivo subido a la ubicación deseada
            if (move_uploaded_file($tmpName, $targetFilePath)) {
                // Adjuntar el archivo al correo
                $mail->addAttachment($targetFilePath);
            }
        }
    }

   // Encoding settings
    $mail->CharSet = 'UTF-8';                             // Set the CharSet to UTF-8
    $mail->Encoding = 'base64';                           // Set the Encoding to base64

    // Enviar el correo
    $mail->send();
    echo 'El mensaje ha sido enviado correctamente';
} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Error de PHPMailer: {$mail->ErrorInfo}";
}
?>
