<?php session_start(); ?>
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class ContactController {
  public function mail() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
     
             $captchaResponse = $_POST['g-recaptcha-response'];
            
            // Incluir las configuraciones de reCAPTCHA

            if (!$captchaResponse) {
                $_GET['status'] = 'error';
                $_GET['message'] = 'reCAPTCHA no validado';
                include '../app/views/contact.php';
                return;
            }

        
            // Verificar con Google
            $url = "https://www.google.com/recaptcha/api/siteverify";
            $data = [
                'secret' => RECAPTCHA_SECRET_KEY,
                'response' => $captchaResponse,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ];
        
            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                ]
            ];
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $responseData = json_decode($result);
        
            if ($responseData->success) {

                $vat = 0; $total = 0;
                
                //El config
                $mailerId = "Ikusa";
                $mailerTo = $_POST['email'];
            
                $mailerFrom = 'contact@ikusa.net'; // Place the E-mail of Domain
                $mailerToToo = 'ikusa.ads@gmail.com';
                $mailerReplay = $mailerFrom;
                $subject = "Contacto";
                
                //Credenciales para PHPMailer 
                require_once '../app/config/email.php';
                
                //Body:
                $body = $_POST['message'];
                
                 //Send
                 include '../app/libraries/inc_phpmailer.php';
                 
                 //Volver
                  $_GET['status'] = 'success';
            
                header('Content-Type: text/html; charset=utf-8');
                include '../app/views/sent.php';
                return;
                 //header("Location: index.php?page=contact&action=mail");
            }
             
        } else {
         include '../app/views/contact.php';
        }
    }
}