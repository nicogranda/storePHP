<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class MailController {
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            // Verificar si se recibió el ID del mensaje
            if (!isset($_POST['message_id'])) {
                echo "Falta el ID del mensaje.";
                return;
            }
            
            $mailerId = 'Ikusa';

            $mailerTo = $_POST['mailerTo'];
            $mailerFrom = 'contact@ikusa.net';
            $mailerToToo = 'ikusa.ads@gmail.com';
            $mailerReplay = $mailerFrom;
            $subject = $_POST['message_id'].' | '.$_POST['subject'];
        
            $business = $_POST['business_name'];
            $representative = $_POST['representative'];
            $message = $_POST['message'] ?? "";  // Si 'message' no viene en POST, usa ""
            $message_html = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
            
            if($_POST['message_id'] == 'Information') {
                $greeting_message = "Hola <b>".$representative."</b>, con la presente solicitamos informacion sobre:<br>".$message_html;  
                $farewell_message = "Agradeciendo su acostumbrada receceptividad, nos despedimos";
             }
             
             if($_POST['message_id'] == 'Final Artwork') {
                $greeting_message =  "Hola <b>".$representative."</b>, con la presente hacemos entrega de ".$_POST['message_id']. " sobre <b>".$_POST['campaign']."</b>";
                $farewell_message = "Agradeciendo su confianza en nuestras propuestas de diseños, nos despedimos";
             }
             
            if($_POST['message_id'] == 'Document Transmittal') {
                $greeting_message =  "Hola <b>".$representative."</b>, con la presente adjuntamos lo indicado en el asunto, sobre <b>".$_POST['campaign']."</b><br>";
                $farewell_message = "Agradeciendo su confianza en nuestras propuestas, nos despedimos";
             }

            $signatory = isset($_SESSION['user_name']) 
                ? htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') 
                : "Nombre por defecto";

            $signatory_position = "";
            
            // Incluir configuraci贸n de correo
            require_once '../../app/config/email.php';

            // Generar el contenido del correo            //ob_start();
            include '../../app/views/admin/E-mail/mail.php';

            // Enviar el correo
            require_once '../../app/libraries/inc_phpmailer.php';

            // Mostrar aviso de 茅xito
            include '../../app/views/admin/E-mail/notice.php';
            
        } else {
             include '../../app/views/admin/E-mail/create.php';
        }
    }
}
