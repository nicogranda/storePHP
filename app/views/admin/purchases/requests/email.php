<?php
$message_head = "
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
                        <a href='" . BASE_URL . "'><img src='" . BASE_URL . "/images/logo.png' alt='Ikusa' width='200px'></a>
                    </th>
                    </tr>
                </thead>";


$message_body = "
            <tbody>
            <tr>
                <td colspan='5' style='text-align:right;font-size:24px;'>
                   
                </td>
            </tr>
            
            <tr>
                <td colspan='5' style='padding:0 0 0 15px;width:90%;'>
                    <p style='font-size:16px;width:90%;''>
		                Señor(es)<br>
		                <b>".$supplier['name']."</b><br>".
		                "Estimado(s) Señor(es)<br><br>

	 	                Reciban un cordial saludo con la presente, a través de la cual solicitamos cotización de los productos indicados a continuación:
	 	            </p>
	 	        </td>
            </tr>    
      
            <tr style='padding:20px; text-align:center;'>
                <th width='40%' style='background-color:orangered; color:white;'>Productos/Servicios</th>
                <th width='10%' style='background-color:orangered; color:white;'>Cantidad</th>
                <th width='20%' style='background-color:orangered; color:white;'>Precio Unitario</th>
                <th width='10%' style='background-color:orangered; color:white;'>Unidad</th>
                <th width='20%' style='background-color:orangered; color:white;'>Total</th>
            </tr>";

$total = 0;

foreach ($RFQ_details as $RFQ_detail) {
    $message_body .= "
            <tr style='padding:10px; text-align:center;'>
                <td style='text-align:left;'>".$RFQ_detail['supplies_name']."</td>
                <td style='text-align:right;'>".$RFQ_detail['quantity']."</td>
                <td style='text-align:right;'></td>
                
                <td style='text-align:right;'></td>
            </tr>";
}


$message_body .= "
            <tr>
        	   <td colspan='5' style='padding:0 0 0 15px;'>
                    <p style='font-size:16px;;width:90%;'>
    			        Sin otro particular al cual hacer referencia, seguros de contar con su acostumbrada receptividad, nos despedimos.<br><br>
    			         Atentamente<br> 
    			</td>
    		</tr>
		

    		<tr>
                <td colspan='5' style='padding:20px 0 0 15px;'>
    	   		<p style='font-size:16px; color: #626262;'>
    	   		   <b>Nicolás Granda</b><br>
    			   Calle General Freire,5 Piso 2-A <br>
    			   Irún, Guipuzkoa 20303<br>
    			   España<br>
    			   WhatsApp +34 607 20 24 66<br>
    			</p>
    		    </td>
    		</tr>";
$message_foot = "    		
<tr height='50px'>
    <td colspan='5' style='width:100%; height:55px; text-align:center;'>
        <a href='https://ikusa.net' style='color:#fff; text-decoration:none; background-color:#E10B76; display:inline-block; width:32%; padding:15px 0;'>
            Diseño Gráfico
        </a>
        <a href='https://ikusa.net' style='color:#fff; text-decoration:none; background-color:#10C3F3; display:inline-block; width:32%; padding:15px 0;'>
            Web Site
        </a>
        <a href='https://ikusa.net' style='color:#fff; text-decoration:none; background-color:#3ED53E; display:inline-block; width:32%; padding:15px 0;'>
            Marketing
        </a>
    </td>
</tr>


    	<tr align='center' height='30' style='font-family:".$font_family.";'>
    	    <td colspan='5' style='text-align:center; width:25%;padding:15px;'>
        		<a href='https://facebook.com/ikusa.creativestudio'><img src='https://ikusa.net/images/rrss/facebook.png' width='50px' height='auto'></a>
        		<a href='https://instragram.com/ikusa.creativestudio'><img src='https://ikusa.net/images/rrss/instagram.png' width='50px' height='auto'></a>
        	    <a href='https://linkedin.com/company/ikusacreativestudio/?viewAsMember=true'><img src='https://ikusa.net/images/rrss/linkedIn.png' width='50px' height='auto'></a>
        		<a href='https://api.whatsapp.com/send?phone=+14709837444'><img src='https://ikusa.net/images/rrss/youtube.png' width='50px' height='auto'></a>
            </td>
        </tr> 
        
            <tr>
    		    <td colspan='5' style='padding:15px;'>
    				<p style='font-size:12px; color: #626262;'>
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


$body = $message_head.$message_body.$message_foot; // Corrected this line
