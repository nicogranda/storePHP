<?php
// Definir la variable de la fuente
$font_family = 'Montserrat, Geneva, sans-serif';

// Incluir archivos necesarios
include 'inc_config.php';

// Crear el mensaje del correo
$message  = "<html><body>";
   
$message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";
$message .= "<tr><td style='height:20px;'></td></tr>";
      
$message .= "<tr><td>";
   
$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:650px; background-color:#fff; font-family:$font_family;'>";
    
$message .= "<thead>
              <tr height='30'>
            	<th colspan='4' style='text-align:center;'>
            		<a href='".$url."'><img src='".$url."/images/logo.png' width='200px' height='auto'></a>
            	</th>
              </tr>
        	</thead>";
    
$message .= "<tbody>
      
       <tr>
       <td colspan='4' style='padding:15px; font-family:$font_family;'>

       <p style='font-size:12px;'>
		  Señor(es)<br>
		  <b>".$business_name."</b><br>".
		  $business_representative."<br><br>	
		  Estimado(s) Señor(es)<br>
		  <br>

	 	  Reciban un cordial saludo con la presente, ".$message1.".
	   <br> 
	   <br> 
	   
	   Sin otro particular al cual hacer referencia, seguros de poder servirle, nos despedimos
	   <br>
	   <br>
	   Atentamente 
	   <br>
	   <br>
	   </p>
	   </td>
   
	   <tr align='center' style='font-family:$font_family;'>
			
	  		 <td colspan='4' style='text-align:center; padding:15px; font-family:$font_family;'>
				<p style='text-align:left;'>
				  <b>".$executive_user."</b><br>
				     ".$executive_position."<br>
				     ".$executive_email."<br>
					 <a href='https://wa.me/".$excecutive_cell_phone."' target='_blank'>
                        ".$executive_cell_phone."
                    </a>
					
				</p>
			</td>
		</tr>
		
		<tr>
            <td colspan='4' style='padding:0 0 0 15px; font-family:$font_family;'>
	   		<p style='font-size:10px; color: #626262;'>
			   8735 Dunwoody Place, Ste R,<br>
			   Atlanta, GA 30350<br>
			   United States<br>
			   WhatsApp +1 (470) 9837444<br>
			</p>
		    </td>
		</tr>

		<tr align='center' height='25' style='font-family:$font_family;'>

		<tr align='center' height='50' style='font-family:$font_family;'>
			<td style='background-color:#F33610; text-align:center; width:25%'><a href='https://ikusa.net/portafolio.php?id=103' style='color:#fff; text-decoration:none;'>Branding</a></td>
			<td style='background-color:#E10B76; text-align:center; width:25%'><a href='https://ikusa.net/portafolio/rrss' style='color:#fff; text-decoration:none;'>Social Media</a></td>
			<td style='background-color:#10C3F3; text-align:center; width:25%'><a href='https://ikusa.net/portafolio/paginas-web' style='color:#fff; text-decoration:none;'>Web Site</a></td>
			<td style='background-color:#3ED53E; text-align:center; width:25%'><a href='https://ikusa.net/portafolio/marketing' style='color:#fff; text-decoration:none;'>Marketing</a></td>
		</tr>

		<tr align='center' height='30' style='font-family:$font_family;'>
			<td colspan='4' style='text-align:center; width:25%;padding:15px;'>
				<a href='".$url_fb."'><img src='".$url_fb_logo."' width='7.5%' height='auto'></a>
				<a href='".$url_ig."'><img src='".$url_ig_logo."' width='7.5%' height='auto'></a>
				<a href='".$url_pt."'><img src='".$url_pt_logo."' width='7.5%' height='auto'></a>
				<a href='".$url_li."'><img src='".$url_li_logo."' width='7.5%' height='auto'></a>
			</td>
		</tr>
       
        <tr>
		    <td colspan='4' style='padding:15px; font-family:$font_family;'>
				<p style='font-size:10px; color: #626262;'>
				<b>Información básica sobre protección de datos</b> <br>

				<b>Ikusa LLC</b>, como responsable del diseño,
				le informa que sus datos son recabados con la finalidad de: 
				Gestión de los datos de contacto para las comunicaciones de la empresa.
				La base jurídica para el tratamiento es el interés legítimo del responsable.
				Sus datos no se cederán a terceros salvo obligación legal.
				Cualquier persona tiene derecho a solicitar el acceso, rectificación, supresión, 
				limitación del tratamiento, oposición o derecho a la portabilidad de sus datos personales,
				escribiéndonos a la dirección de nuestras oficinas, o enviando un correo electrónico a 
				<a href='mailto:contact@ikusa.net'>contact@ikusa.net</a>, 
				indicando el derecho que desea ejercer. Puede obtener información adicional en el apartado de 
				PROTECCION DE DATOS de nuestra página web: <a href='https://ikusa.net'>https://ikusa.net</a>
				</p>
			</td>
       </tr>

  
       </tbody>";
    
$message .= "</table>";
   
$message .= "</td></tr>";
$message .= "<tr><td style='height:20px;'></td></tr>";
$message .= "</table>";
   
$message .= "</body></html>";

// Incluir archivos necesarios para enviar el correo
include 'app/views/Email/inc_phpmailer.php';
include 'sent.php';
?>
