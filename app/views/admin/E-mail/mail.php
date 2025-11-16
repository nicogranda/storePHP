<?php
// Fuente y estilo base
$font_family = "Montserrat, sans-serif";
$font_size = "12px";

// Convertir saltos de línea en <br>
$farewell_message = nl2br(htmlspecialchars($farewell_message, ENT_QUOTES, 'UTF-8'));

// Contenido del primer script
$head_message = "
<div class='container' style='text-align: center;'>
    <img src='https://ikusa.net/images/logo.png' alt='Ikusa' width='200px'>
</div>";

$body_message = "
<table>
    <tr>
        <td colspan='5' style='padding:0 0 0 15px;'>
            <p style='font-size:16px;'>$greeting_message</p>
            <p style='font-size:16px;'>$farewell_message</p>
            <br><br>
        </td>
    </tr>
    <tr>
        <td colspan='5' style='padding:0 0 0 15px;'>
            <p style='font-size:16px; color: #626262;'><b>Nicolás Granda</b><br>
            <span style='font-size:12px;'>WhatsApp: +34 (600) 142663</span></p>
        </td>
    </tr>
</table>";

$foot_message = "
<table style='text-align:center; width:100%;'>
    <tr align='center' height='50' style='font-family:Verdana, Geneva, sans-serif;'>
        <td style='background-color:#E10B76; text-align:center; width:33%;'>
            <a href='https://ikusa.net/portfolio.php?id=201' style='color:#fff; text-decoration:none;'>Multimedia</a>
        </td>
        <td style='background-color:#10C3F3; text-align:center; width:33%;'>
            <a href='https://ikusa.net/portfolio.php?id=201' style='color:#fff; text-decoration:none;'>Web Site</a>
        </td>
        <td style='background-color:#3ED53E; text-align:center; width:33%;'>
            <a href='https://ikusa.net/portfolio.php?id=301' style='color:#fff; text-decoration:none;'>Marketing</a>
        </td>
    </tr>
</table>
<br>
<div id='proteccion_de_datos' class='rrss' style='text-align:left; font-size:12px; color:gray;'>
    <b>Información básica sobre protección de datos</b><br>
    <b>Ikusa, LLC</b>, como responsable del diseño, le informa que sus datos son recabados con la finalidad de:
    Gestión de los datos de contacto para las comunicaciones de la empresa.
    La base jurídica para el tratamiento es el interés legítimo del responsable.
    Sus datos no se cederán a terceros salvo obligación legal.
    Cualquier persona tiene derecho a solicitar el acceso, rectificación, supresión,
    limitación del tratamiento, oposición o derecho a la portabilidad de sus datos personales,
    escribiéndonos a la dirección de nuestras oficinas, o enviando un correo electrónico a 
    <a href='mailto:contact@gikusa.net'>contact@ikusa.net</a>.
    Puede obtener información adicional en el apartado de PROTECCIÓN DE DATOS de nuestra página web: 
    <a href='https://ikusa.net'>https://ikusa.net</a>
</div>
<center>
    <div style='padding: 20px 0;'>
        <a href='https://facebook.com/ikusa.creativestudio'><img src='https://ikusa.net/images/rrss/facebook.png' width='7%' height='auto'></a>
        <a href='https://instagram.com/ikusa.creativestudio'><img src='https://ikusa.net/images/rrss/instagram.png' width='7%' height='auto'></a>
        <a href='https://linkedin.com/company/ikusacreativestudio/?viewAsMember=true'><img src='https://ikusa.net/images/rrss/linkedIn.png' width='7%' height='auto'></a>
        <a href='https://api.whatsapp.com/send?phone=+14709837444'><img src='https://ikusa.net/images/rrss/youtube.png' width='7%' height='auto'></a>
    </div>
</center>";

// ----------------------------
// Envolviendo en el estilo BidMyCar
// ----------------------------
$body  = "<html><body>";
$body .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";
$body .= "<tr><td height='30'></td></tr>"; 
$body .= "<tr><td>";
   
$body .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' 
          style='max-width:650px; background-color:#fff; font-family:".$font_family."; font-size:".$font_size."; padding:20px;'>";

$body .= "<tr><td>";
$body .= $head_message;
$body .= $body_message;
$body .= $foot_message;
$body .= "</td></tr>";

$body .= "</table>";
$body .= "</td></tr>";
$body .= "<tr><td height='30'></td></tr>"; 
$body .= "</table>";
$body .= "</body></html>";
?>
