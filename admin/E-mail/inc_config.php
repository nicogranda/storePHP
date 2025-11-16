<?php

    //Destinatario
    $mailerTo='cadenapanamericana@gmail.';
    $mailerToToo='ikusa.ads@gmail.com';
    $url='https://ikusa.net';
    
    $mailerFrom = 'contact@ikusa.net'; 
   
    $subject=$_POST['subject'];
    $message_id=$_POST['message_id'];
    $executive=$_POST['excecutive'];
    $business_representative = $_POST['representative'];
    //$business_email = $POST['mailerTo'];
    $business_name = $_POST['business'];
    
    $message0 = "
Señor(es)<br>
<b>".$business_name."</b><br>".
$business_representative."<br><br>	
Estimado(s) Señor(es)<br>
<br><BR>Recibe un cordial saludo con la presente,";
	
	$message1 = "
<br> <br> 
Sin otro particular al cual hacer referencia, seguros de poder servirle, nos despedimos
<br>
<br>
Atentamente 
<br>
<br>";	  
		  
    if ($message_id=="11") {$message_body = $message0.'a través de la cual queremos dar gracias por su preferencia y para hacer entrega del presupuesto indicado en el asunto'.$message1;}
    if ($message_id=="12") {$message_body = $message0.'a través de la cual queremos dar gracias por su preferencia y para hacer entrega del diseño indicado en el asunto'.$message1;}
    if ($message_id=="13") {$message_body = $message0.'a través de la cual adjuntamos el arte para impresión, segun lo indicado en el asunto'.$message1;}
    if ($message_id=="14") {$message_body = $message0.'a través de la cual queremos dar gracias por su preferencia y para hacer entrega de la factura indicada en el asunto'.$message1;}
    if ($message_id=="22") {$message_body = $message0.'a través de la cual queremos dar gracias por su preferencia y para hacer entrega del acuse de recibo indicado en el asunto'.$message1;}
    if ($message_id=="30") {$message_body = $_POST['message'];}
    if ($message_id=="31") {$message_body = $message0.'a través de la cual queremos solicitar presupuesto de los productos indicados:<br>'.$_POST['message'].$message1;}
    if ($message_id=="32") {$message_body = $message0.'a través de la cual, según lo conversado, que '.$_POST['message'].$message1;}
   

    $url_fb='https://www.facebook.com/ikusa.creativestudio';
    $url_ig='https://www.instagram.com/ikusa.creativestudio/';
    $url_pt='https://www.pinterest.com/ikusacreativestudio/';
    $url_li='https://www.linkedin.com/company/ikusacreativestudio/';

    $url_fb_logo=$url.'/images/rrss/facebook.png';
    $url_ig_logo=$url.'/images/rrss/instagram.png';
    $url_pt_logo=$url.'/images/rrss/pinterest.png';
    $url_li_logo=$url.'/images/rrss/linkedIn.png';
    
    //$archivos = array();
    //$archivos = $_POST['archivos'];

    //Emisor
    $logotype='ikusa';
    $idMailer='Ikusa LLC';
    $mailerFrom= $_POST['senderEmail'];

    
    include 'addressee.php';// Aca se toman los datos del destinatario
   
    $mailerReplay='contact@ikusa.net';
    
    /*
    //Datos del Servidor SMTP (Google en este caso)
    $host   = 'smtp.gmail.com';                           //Set the SMTP server to send through
    $SMTPAuth   = true;                                   //Enable SMTP authentication
    $Username   = 'ikusa.creativestudio@gmail.com';               //SMTP username
    $Password   = 'lgdzlbjgcqunnxvx';                      //SMTP password
    //$SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $Port       = 465;      
    */
    
    $host = 'ikusa.net'; // Servidor SMTP de GoDaddy or Set the SMTP server to send through
    $SMTPAuth   = true;                   //Enable SMTP authentication
    $Username   = 'ot2ryobi838h';         //SMTP username
    $Password   = 'Ga872680481$';         //SMTP password
    $SMTPSecure = 'ssl';                  //SSL o Enable implicit TLS encryption
    $Port       = '465';  

    $messageSent ='Mensaje Enviado';
    $messageError='Mensaje no Enviado';

?>