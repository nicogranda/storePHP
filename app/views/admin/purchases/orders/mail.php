<?php
$head_message = "
<html>
<body>
<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>
    <tr><td style='height:30px;'></td></tr>
    <tr><td>
        <table align='center' width='80%' border='0' cellpadding='20' cellspacing='0' style='background-color: white;'>
            <tr>
                <td>
                <thead>
                    <tr height='30'>
                    <th colspan='5' style='text-align:center;padding:20px;'>
                        <a href=''><img src='https://ikusa.net/images/logo.png' alt='Ikusa' width='200px'></a>
                    </th>
                    </tr>
                </thead>";
    
$body_message = "
            <tbody>
            
            
            <tr>
                <td colspan='5' style='padding:0 0 0 15px;'>
                    <p style='font-size:16;'>
		                Señor(es)<br>
		                <b>".$business['name']."</b>
		                <br><br><br>	
		                Estimado(s) Señor(es)<br><br>

	 	                Reciban un cordial saludo con la presente, a través de la cual solicitamos lo indicado:
	 	            </p>
	 	        </td>
            </tr>    
      
            <tr style='padding:20px; text-align:center;'>
                <th width='50%' style='background-color:orangered; color:white;'>Goods/Service</th>
                <th width='10%' style='background-color:orangered; color:white;'>Quantity</th>
                <th width='10%' style='background-color:orangered; color:white;'>Unit Value (€)</th>
                <th width='10%' style='background-color:orangered; color:white;'>Unit</th>
                <th width='20%' style='background-color:orangered; color:white;'>Total (€)</th>
            </tr>";

        $total = 0;
      
       //Colocar aca el foreach
       foreach ($operation_details as &$operation_detail) {
$body_message .= "
            <tr style='padding:10px; text-align:center;'>
                <td style='text-align:left;'>{$operation_detail['product_name']}</td>
                <td style='text-align:right;'>{$operation_detail['quantity']}</td>
                <td style='text-align:right;'>{$operation_detail['unit_value']}</td>
                <td style='text-align:right;'>{$operation_detail['unit']}</td>
                <td style='text-align:right;'></td>
            </tr>";
       }
$body_message .= "
            <tr style='text-align:center;'>
                <td>Total</td>
                <td style='text-align:right;'></td>
                <td style='text-align:right;'></td>
                <td style='text-align:right;'></td>
                <td style='text-align:right;'></td>
            </tr>";


$foot_message = "
            <tr>
        	   <td colspan='5' style='padding:0 0 0 15px;'>
                    <p style='font-size:16;'>
    			        Sin otro particular al cual hacer referencia, seguros de poder servirle, nos despedimos.<BR>
    			         Atentamente<BR> 
    			</td>
    		</tr>
		

    		<tr>
                <td colspan='5' style='padding:0 0 0 15px;'>
    	   		<p style='font-size:16; color: #626262;'>
    			   8735 Dunwoody Place, Ste R,<br>
    			   Atlanta, GA 30350<br>
    			   United States<br>
    			   WhatsApp +1 (470) 9837444<br>
    			</p>
    		    </td>
    		</tr>
    		
<tr height='50px'>
    <td colspan='5' style='width:90%; height:55px; text-align:center;'>
        <a href='https://ikusa.net' style='color:#fff; text-decoration:none; background-color:#E10B76; display:inline-block; width:33%; padding:15px 0;'>
            Diseño Gráfico
        </a>
        <a href='https://ikusa.net' style='color:#fff; text-decoration:none; background-color:#10C3F3; display:inline-block; width:33%; padding:15px 0;'>
            Web Site
        </a>
        <a href='https://ikusa.net' style='color:#fff; text-decoration:none; background-color:#3ED53E; display:inline-block; width:33%; padding:15px 0;'>
            Marketing
        </a>
    </td>
</tr>


    	<tr align='center' height='30'>
    	    <td colspan='5' style='text-align:center; width:25%;padding:15px;'>
        		<a href='https://facebook.com/ikusa.creativestudio'><img src='https://ikusa.net/images/rrss/facebook.png' width='50px' height='auto'></a>
        		<a href='https://instragram.com/ikusa.creativestudio'><img src='https://ikusa.net/images/rrss/instagram.png' width='50px' height='auto'></a>
        	    <a href='https://linkedin.com/company/ikusacreativestudio/?viewAsMember=true'><img src='https://ikusa.net/images/rrss/linkedIn.png' width='50px' height='auto'></a>
        		<a href='https://api.whatsapp.com/send?phone=+14709837444'><img src='https://ikusa.net/images/rrss/youtube.png' width='50px' height='auto'></a>
            </td>
        </tr> 
        
            <tr>
    		    <td colspan='5' style='padding:15px;'>
    				<p style='font-size:12; color: #626262;'>
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
        
        </tbody>
    
        </table>
    </td></tr>
    
    <tr><td style='height:30px;'></td></tr>
</table>
</body>
</html>";
$xx = '
     <section class="rrss-footer">
    <a href="https://facebook.com/<?php echo $user_facebook; ?>" target="_blank" title="facebook.com/<?php echo $user_facebook; ?>">
        <i class="fab fa-facebook" style="color: black; font-size:14px;"></i>
    </a>
    <a href="https://instagram.com/<?php echo $user_instagram; ?>" target="_blank" title="instagram.com/<?php echo $user_instagram; ?>">
        <i class="fab fa-instagram" style="color: white; height: 35vw;"></i>
    </a>
    <a href="https://youtube.com/<?php echo $user_youtube; ?>" target="_blank" title="youtube.com/<?php echo $user_youtube; ?>">
        <i class="fab fa-youtube" style="color: white; height: 35vw;"></i>
    </a>
    <a href="https://tiktok.com/@<?php echo $user_tiktok; ?>" target="_blank" title="tiktok.com/@<?php echo $user_tiktok; ?>">
        <i class="fab fa-tiktok" style="color: white; height: 35vw;"></i>
    </a>
    <a href="https://linkedin.com/company/ikusacreativestudio" target="_blank" title="linkedin.com/company/ikusacreativestudio">
        <i class="fab fa-linkedin" style="color: white; height: 35vw;"></i>
    </a>
    <a href="https://x.com/ikusa_ads" target="_blank" title="x.com/ikusa_ads">
        <i class="fab fa-x-twitter" style="color: white; height: 35vw;"></i>
    </a>
</section>
';

$message = $head_message.$body_message.$foot_message.$xx;
?>   