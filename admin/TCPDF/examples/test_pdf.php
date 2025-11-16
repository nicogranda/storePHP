<?php
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 * @group header
 * @group footer
 * @group page
 * @group pdf
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Nicolás Granda');
$pdf->setTitle('Agreement');
$pdf->setSubject('Español');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->setFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<EOD

<h1 style='text-align:center;'>Contrato de Desarrollo Web</h1>
<p>REUNIDOS (1) <b>IKUSA LLC</b>, con domicilio social en Peachtree Corners, 30092, Georgia, Estados
Unidos, con DUNS 10-086-1837 actúa representada en este acto por Nicolás Granda Bauza,
en su condición de Manager, en adelante EL DESARROLLADOR; y Alvaro Gimenez en
representacion de VINOW APP, S.L., con NIF B72871403, y con domicilio en Donostia San
Sebastián, 20004, Gipuzkoa, España, en lo sucesivo EL CLIENTE, actuando en su propio nombre y
derecho, teniendo ambos la capacidad para firmar y quedar obligados por este contrato y
EXPONEN:</p>
<ol style="list-style-type: decimal; padding-left: 20px;">
    <li>Que el desarrollador se dedica a diseñar y producir páginas web.</li>
    <li>Que el cliente está interesado en que le realicen el diseño y desarrollo de una página web.</li>
    <li>Que el cliente está interesado en contratar los servicios del desarrollador, de acuerdo con las
    condiciones establecidas en este contrato.</li>
    <li>Se reconocen la capacidad legal suficiente para celebrar este contrato de desarrollo de página web.</li>
</ol>

<h2>CLÁUSULAS</h2>
<h3>Objeto del contrato</h3>
<p>5.El desarrollador se obliga a diseñar y desarrollar una página web para el cliente.
Contenido del servicio
<p>6. El desarrollador realizará el proyecto con la colaboración activa del cliente para incorporar, según
sus instrucciones, los contenidos del sitio web y facilitarse mutuamente cualquier documentación
necesaria, tanto en soporte físico como digital
<ol style="list-style-type: lower-alpha; padding-left: 20px;">
    <li>E-commerce (no en CMS), donde el usuario se autentifique para hacer la compra.</li>
    <li>Cuatro 4 roles en el Log Up/Log In: Comprador, Vendedor, Productor y Administrador.</li>
    <li>Cada rol tiene ciertos atributos para agregar, editar (modificar) y/o eliminar, de su base de datos.</li>
    <li>Un catálogo al inicio que envía a la ficha técnica, y la capacidad de dar comprar, para hacer un pedido.</li>
    <li>Pedido que el comprador puede manejar y recibirá el Vendedor en su E-mail/WhatsApp, para procesar con poco tiempo de respuesta.</li>
    <li>Con un buscador sobre la base de datos propias.</li>
    <li>Idioma: Castellano.</li>
    <li>200 productos estimados en la presentación del primer mes.</li>
    <li>No se contempla pasarela de pago (aún).</li>
    <li>Fotografías: serán proporcionadas por el cliente.</li>
    <li>Sonido: No contemplado.</li>
    <li>Logotipos: Todas las páginas llevarán en el encabezado el logotipo del cliente.</li>
</ol>

<p>El desarrollador alojará el sitio web en su servidor de acceso a Internet y se encargará de las
correspondientes actualizaciones, seguimiento del número de acceso a dicho sitio, elaboración de
reportes de uso para el cliente, mantenimiento de la base de datos de usuarios de Internet que
elaboran órdenes de pedido de productos y servicios del Cliente.
Se excluyen del contrato los siguientes servicios:</p>
<ul style="list-style-type: disc; padding-left: 20px;">
    <li>Adaptación del sitio web a circunstancias especiales del cliente o a nuevas necesidades surgidas con posterioridad con el uso del sitio web.</li>
    <li>La inclusión de nuevos apartados o ampliaciones sustanciales del sitio web. Se deberá acordar la dimensión de estas ampliaciones o nuevas creaciones.</li>
    <li>La corrección de errores que se pudieran imputar a una manipulación del sitio web que se haya realizado por personal no autorizado por la parte.</li>
    <li>Los gastos de desplazamiento.</li>
</ul>

<h3>Plazo de ejecución</h3>
<p>7. En cuando al plazo de ejecución:</p>
El cliente se obliga a proporcionar toda la información que el desarrollador requiera para el diseño
de la página web, a mas tardar dentro del plazo de 5 dias transcurridos desde la firma del contrato.
El desarrollador se obliga a entregar una versión preliminar o beta del sitio web, a más tardar en el
plazo de 10 dias desde la entrega de la información necesaria para su diseño por parte del cliente.</p>
<h3>Precio y forma de pago</h3>
<p>8. PAGOS: El precio que el cliente pagará al desarrollador será el siguiente:
Por el diseño y desarrollo de la página web 7.000,00Euros, impuestos no incluidos.</p>
<p>La cuenta Bancaria del DESARRROLLADOR es la siguiente<p>
<p>Titular: Ikusa<p>
<p>BIC: TRWIBEB1XXX<p>
<p>IBAN: BE82 9678 2700 3168</p>
<p>El pago se hará de la siguiente forma:</p>
<ol style="padding-left: 20px;">
    <li>25 % a la firma del contrato, que se podrá pagar mediante transferencia bancaria: 20 de diciembre de 2023.</li>
    <li>25 % con la instalación en el hosting de la empresa: 30 de diciembre de 2023.</li>
    <li>50 % en el momento de la activación, después de varias pruebas a satisfacción del cliente: 15 de enero de 2023.</li>
</ol>
<p>9. El desarrollador podrá revisar los precios de sus servicios una vez al año, teniendo que
comunicárselo al cliente, al menos con un plazo de 30 días de antelación a la aplicación de las
nuevas tarifas.
Si el cliente no hiciera uso efectivo de la totalidad de los servicios incluidos en el presente contrato,
no podrá solicitar al desarrollador la devolución de parte del precio.
Incumplimiento</p>
<p>10. Si el cliente no abona el precio acordado en plazo o no comunica en tiempo y forma los
contenidos a publicar, el desarrollador considerará resuelto el contrato, sin reintegrar al cliente
cualquier cantidad adelantada y retirando cualquier contenido publicado en el sitio web si el
incumplimiento no es subsanado, reservándose otro tipo de acciones legales que
pudieran proceder.</p>
<h3>Confidencialidad</h3>
<p>11. Ambas partes se comprometen a guardar la máxima reserva y secreto sobre la información y
documentos que mutuamente se proporcionen como consecuencia de la ejecución del presente
contrato, comprometiéndose a no divulgarlos, así como a no publicarlos ni, directa o indirectamente,
ponerlos a disposición de terceros, sin el previo consentimiento expreso de la otra parte.
Ambas partes podrán transmitir los documentos y/o información proporcionados a su personal y
colaboradores en la medida en que ello sea imprescindible para la ejecución del presente contrato.
En tal caso, ambas partes informarán a su personal y colaboradores de las obligaciones de
confidencialidad establecidas en el present contrato, realizando a tal efecto cuantas
advertencias y suscribiendo con su personal cantos documentos sean necesarios al objeto de
garantizar el cumplimiento de tales obligaciones.
El incumplimiento de las obligaciones de confidencialidad establecidas en el presente contrato
facultará a la otra parte para reclamar la indemnización de cantos daños y perjuicios se cause por
razón de tal incumplimiento, así como la restitución de todos los gastos, incluidos, en su caso,
aquellos que pudieran devengarse como consecuencia de la eventual interposición de acciones
legales en defensa de sus derechos.</p>
<h3>Protección de datos personales</h3>
<p>12. En cumplimiento de la normativa de Protección de Datos de Carácter Personal, las partes se
informan sore la posibilidad de incorporar los datos personales facilitados en el presente documento
a un fichero creado por ellas mismas con la finalidad del mantenimiento y gestión de la relación
comercial.
Asimismo, los titulares de dichos datos podrán ejercer losderechos de acceso, oposición,
rectificación y cancelación en las direcciones de los contratantes.
Para la correcta ejecución del presente contrato las partes se obligan a suscribir el correspondiente 
contrato de acceso a datos por terceros, en el cual el desarrollador será la encargado del
tratamiento de los datos v el cliente el responsable de los mismos.</p>
<h3>Propiedad intelectual</h3>
<p>13. El cliente garantiza que pose la titularidad o, las obligatorias licencias y autorizaciones, sobre
los derechos de propiedad intelectual derivados de aquellos contenidos e informaciones que hayan
sido aportados por el cliente. Será exclusiva responsabilidad del cliente el obtener cuantas licencias
y autorizaciones sean necesarias para garantizar la integridad de los derechos de propiedad
intelectual y/o industrial derivados de tales contenidos cuya titularidad sea ostentada por terceros.
El cliente se compromete a asumir cualquier responsabilidad por reclamaciones dirigidas contra el
desarrollador por infracción de derechos de propiedad intelectual y/o industrial portal concepto,
asumiendo cuantos gastos, costes e indemnizaciones se deriven contra ella con motivo de dicha
reclamación.
La empresa desarrolladora cede todos los derechos de explotación económica al cliente, derivados
de los contenidos y sus modificaciones, cuando se incorporen a la página web y una vez
terminados los trabajos de creación y desarrollo que se realicen sobre los mismos. No se incluyen
los derechos derivados de la programación, del código fuente y sistemas
informáticos propiedad de la empresa desarrolladora que se han incluido en la página web, sobre
los que el cliente sólo tendrá una licencia de uso mientras el presente contrato esté vigente, que no
podrá compartir con terceros.</p>
<h3>Responsabilidades</h3>
<p>14. El desarrollador no se hace responsable:</p>
<ol style="list-style-type: lower-alpha; padding-left: 20px;">
    <li>De errores, daños o defectos producidos en la página web por uso, manipulación o mantenimiento por personal no autorizado, o por un uso negligente del cliente.</li>
    <li>De errores, daños o defectos en el sitio web por incompatibilidades con otros soportes informáticos, servidores o navegadores que tengan características diferentes a las acordadas.</li>
    <li>De los daños y perjuicios producidos por la presencia de virus u otros elementos que pudieran producir alteraciones en los sistemas informáticos, documentos electrónicos o ficheros del cliente.</li>
</ol>

<h3>Garantía</h3>
<p>15. El página o sitio web una vez publicado tendrá una garantía indefinida de funcionamiento a
partir de la fecha de entrega
del proyecto, siempre y cuando su código se mantenga íntegro y sin modificación alguna, de
acuerdo a la copia del mismo
entregada en soporte físico al cliente al finalizar dicho proyecto, y siempre que se mantenga
idéntico el servicio contratado a
un tercero en materia de hosting, si es el caso.</p>

<h2>Acciones</h2>
<h3>Duración del contrato</h3>
<p>16. El presente contrato se considera finalizado en lo que se refiere a con la realización del
proyecto, entrega y publicación
del sitio web por parte de la empresa desarrolladora y con el pago del precio por parte del cliente.
Cualquiera de los firmantes podrá resolver o poner fin al contrato en el supuesto de que
concurriesen cualquiera de las las causas determinadas como inclumplimiento.
En el caso de resolución o terminación por incumplimiento, deberá mediar requerimiento previo o
preaviso a la parte que incumplido,
por la otra parte, para que en el improrrogable plazo de 30 días desde la recepción de dicha
notificación,
solucione la situación que originó la causa de finalización del contrato. Si pasado dicho plazo, la
parte que incumple no remedia tal situación, el contrato quedará resuelto o terminado de forma
inmediata y automática.
<h2>Firma Contrato</h2>
<p>17. Las partes recibirán un e-mail cuando este contrato haya sido firmado y formalizado por las
mismas, sirviendo como prueba de su completa validez legal.
Y como prueba de lo convenido ambos firman el presente contrato por duplicado.</p>
<p>En San Sebastian, 20 de diciembre de 2023</p>
<p>El desarrollador El Cliente</p>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
