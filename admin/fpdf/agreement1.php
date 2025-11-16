<?php
require('fpdf.php');

// Conexión a la base de datos
include '../../../app/config/connection.php';

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
$quote_id = $_GET['id'];

$pdf = new FPDF('P', 'mm', 'A4'); // Mantén la codificación en UTF-8
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Logo
$logoPath = 'https://ikusa.net/images/logo.png';
$pdf->Image($logoPath, 120, 5, 30);

// Texto "Quote"
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 5, utf8_decode('Agreement'), 0, 1, 'L');

//Business
$x = 120 + 30 + 10; // 120 es la posición inicial de la imagen, 30 es el ancho de la imagen y 10 el espacio de separación
$y = 5; // Mantienes la misma altura que la imagen


// Establece la posición del texto
$pdf->SetFont('Arial', 'B', 8); // Cambia 'Arial' por la fuente que estés usando
$pdf->SetXY($x, $y);
$pdf->Cell(0, 4, "Ikusa LLC", 0, 1);
$pdf->SetFont('Arial', '', 8); // Volver al estilo normal
$pdf->SetX($x);
$pdf->Cell(0, 4, "8735 Dunwoody Place, Ste R", 0, 1);
$pdf->SetX($x);
$pdf->Cell(0, 4, "Atlanta, GA 30350", 0, 1);
$pdf->SetX($x);
$pdf->Cell(0, 4, "United States", 0, 1);
$pdf->SetX($x);
$pdf->Cell(0, 4, "https://ikusa.net", 0, 1);
$pdf->SetX($x);
$pdf->Cell(0, 4, "DUNS: 100861837", 0, 1);
// Formatea el ID con ceros a la izquierda
$pdf->SetTextColor(255, 0, 0);
$pdf->SetFont('Times', 'B', 16);


//Number
$formatted_quote_id = str_pad($quote_id, 9, '0', STR_PAD_LEFT);
$pdf->Cell(30, 5, utf8_decode($formatted_quote_id), 0, 1, 'L');

$pdf->SetTextColor(0, 0, 0);

// Añadir información del cliente
$quotes = $mysqli->query("SELECT * FROM quotes WHERE id = '$quote_id'");
if ($quotes && $row = $quotes->fetch_assoc()) {
    $client_id = $row['business_id'];
    $created_at = $row['created_at'];
    $clients = $mysqli->query("SELECT * FROM clients WHERE id = '$client_id'");

    if ($clients && $client = $clients->fetch_assoc()) {
$pdf->SetFont('Arial', '', 10);

//Date
$formatted_date = date("m-d-Y", strtotime($created_at));
$pdf->Cell(30, 5, utf8_decode($formatted_date), 0, 1, 'L');

//Business
$x = 120 + 30 + 10; // 120 es la posición inicial de la imagen, 30 es el ancho de la imagen y 10 el espacio de separación
$y = 5; // Mantienes la misma altura que la imagen


$pdf->SetFont('Arial', '', 10); // Cambia 'Arial' por la fuente que estés usando
$pdf->Ln(10);
$pdf->Cell(20, 5, utf8_decode("Client: "), 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, utf8_decode($client['name']), 0, 1);

// Muestra los detalles de la dirección, alineados debajo del nombre
$pdf->SetX(30); // Ajusta X para alinear con el nombre del cliente
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, utf8_decode($client['address']), 0, 1);

$pdf->SetX(30); // Ajusta X nuevamente para las siguientes líneas
$pdf->Cell(0, 5, utf8_decode($client['city'] . ', ' . $client['state'] . ', ' . $client['zip_code']), 0, 1);

$pdf->SetX(30); // Alinea la última celda también
$pdf->Cell(0, 5, utf8_decode($client['country']), 0, 1);

    } else {
        $pdf->Cell(0, 5, utf8_decode("Client information not found."), 0, 1);
    }
}
//$formatted_date = date("F d, Y", strtotime($created_at));
//$pdf->Cell(30, 5, utf8_decode($formatted_date), 0, 1, 'L');


$pdf->SetTextColor(0, 0, 0);

$total = $subtotal = $vat = 0;

$quotes_details = $mysqli->query("SELECT * FROM quotes_details WHERE quote_id = '$quote_id'");
while ($row = $quotes_details->fetch_assoc()) {
    $product_id = $row['product_id'];
    $note = $row['note'];
    $unit_value = $row['unit_value'];
    $quantity = $row['quantity'];
    $discount = $row['discount'];
    $vat_rate = $row['vat_rate'];

    // Calcular balance
    $balance = $quantity * $unit_value * (1 - $discount / 100) * (1 + $vat_rate / 100);
    $subtotal += $balance;

$products = $mysqli->query("SELECT * FROM products WHERE id = '$product_id'");
while ($product = $products->fetch_assoc()) {
    // Nombre en negritas
    $pdf->SetFont('Arial', 'B', 10); // Cambia a negritas
    $pdf->Cell(50, 5, utf8_decode($product["name"]), '', 0);

    // Resto de las columnas
    $pdf->SetFont('Arial', '', 10); // Regresa a texto normal
    $pdf->Cell(20, 5, $quantity, '', 0, 'R');
    $pdf->Cell(35, 5, number_format($unit_value, 2), '', 0, 'R');
    $pdf->Cell(15, 5, utf8_decode($product["unit"]), '', 0, 'R');
    $pdf->Cell(25, 5, number_format($discount, 2), '', 0, 'R');
    $pdf->Cell(20, 5, number_format($vat_rate, 2), '', 0, 'R');
    $pdf->Cell(30, 5, number_format($balance, 2), '', 1, 'R');

    // Nota debajo de "name"
    $pdf->SetX(10); // Mueve el cursor a la posición inicial de "name"
    $pdf->MultiCell(50, 5, utf8_decode($note), ''); // Nota sin bordes, ajustada solo a la columna de "name"

    // Línea de separación
    $pdf->Cell(195, 0, '', 'T', 1); // Línea horizontal que abarca todo el ancho
}


}
// add text
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 7, utf8_decode('REUNIDOS	(1) IKUSA, con domicilio social en Atlanta Georgia, actúa representada en este acto por  Nicolás Granda Bauza en su condición de administrador, en adelante el desarrollador;  y '.$client.' y con domicilio en Italia, actuando en su propio nombre y derecho, en adelante el cliente	Ambos tienen capacidad para firmar y quedar obligados por este contrato y EXPONEN								.'), 0, 1);
$pdf->Ln();
$pdf->MultiCell(0, 7, utf8_decode('1. Que el desarrollador se dedica diseñar y producir páginas web.
2. Que el cliente está interesado en que le realicen el diseño y desarrollo de una página web.
3. Que el cliente se halla interesado en contratar los servicios del desarrollador, de acuerdo con las condiciones establecidas en este contrato.
4.Se reconocen la capacidad legal suficiente para celebrar este contrato de desarrollo de página web.'), 0, 1);
$pdf->Ln();
$pdf->MultiCell(0, 7, utf8_decode('CLÁUSULAS
Objeto del contrato
5.El desarrollador se obliga a diseñar y desarrollar una página web para el cliente.
Contenido del servicio
6. El desarrollador realizará el proyecto con la colaboración activa del cliente para incorporar, según sus instrucciones, los contenidos del sitio web y facilitarse mutuamente cualquier documentación necesaria, tanto en soporte físico como digital
El desarrollador se reserva el derecho, previa comunicación y acuerdo mutuo, a ampliar el plazo de ejecución o modificar la fecha de publicación or cuestiones técnicas, si el cliente solicitase una modificación esencial del proyecto acordado
El cliente declara que el material proporcionado es legal y que no infringe los derechos de terceras personas.
El sitio web que desarrollará deberá tener. como mínimo, las siguientes características:
a) cuatro paginas (home, nosotros, productos, contactanos).						
b) Interface gráfica: Las páginas deben contener texto, imágenes, animaciones y sonido.
c) Fotografías: Las fotografías a ser utilizadas en las páginas no deben exceder de dos por cada una de ellas y serán proporcionadas  por el cliente.		
d) Sonido: La calidad del sonido será Estereo 44Kh .							
e) Texto:								
Descripcion de las fotos que son deportivas.								
f) Logotipos : Todas las páginas llevarán en el encabezado el logotipo del cliente.
El desarrollador alojará el sitio web en su servidor de acceso a Internet y se encargará de las correspondientes
actualizaciones, seguimiento del número de acceso a dicho sitio, elaboración de reportes de us para el cliente, mantenimiento de la base de datos de usuarios de Internet que elaboran órdenes de pedido de productos y servicios del Cliente.
Se excluyen del contrato los siguientes servicios:
* Adaptación del sitio web a circunstancias especiales del cliente o a nuevas necesidades surgidas con posterioridad con el uso del sitio web.
* La inclusión de nuevos apartados o ampliaciones sustanciales del sitio web. Se deberá acordar la dimensión de estas ampliaciones o nuevas creaciones.

La corrección de errores que se pudieran imputar a una manipulación del sitio web que se haya realizado por personal no autorizado por la parte.
Los gastos de desplazamiento.
Plazo de ejecución
7. En cuando al plazo de ejecución:
El cliente se obliga a proporcionar toda la información que el desarrollador requiera para el diseño de la página web, a mas tardar dentro del plazo de 5 dias transcurridos desed la firma del contrato. El desarrollador se obliga a entregar una versión preliminar o beta del sitio web, a más tardar en el plazo de 10 dias desde la entrega de la información necesaria para su diseño por parte del cliente.
Precio y forma de pago
8. El precio que el cliente pagará al desarrollador será el siguiente:
Por el diseño y desarrollo de la página web 1.432 € impuestos no incluidos.
El pago se hará de la siguiente forma:
• El 50 % a la firma del contrato, que se podrá pagar mediante transferencia bancaria.
El n° de cuenta bancaria corriente será el que acuerden los firmantes.
El resto, impuestos incluidos, se pagará mediante transferencia bancaria en el plazo de 15 dias.
El n° de cuenta bancaria corriente será el que acuerden los firmantes.
9. El desarrollador podrá revisar los precios de sus servicios una vez al año, teniendo que comunicárselo al cliente, al menos con un plazo de 30 días de antelación a la aplicación de las nuevas tarifas.
Si el cliente no hiciera uso efectivo de la totalidad de los servicios incluidos en el presente contrato, no podrá solicitar al desarrollador la devolución de parte del precio.
Incumplimiento
10. Si el cliente no abona el precio acordado en plazo o no comunica en tiempo y forma los contenidos a publicar, el desarrollador considerará resuelto el contrato, sin reintegrar al cliente cualquier cantidad adelantada y retirando cualquier contenido publicado en el sitio web si el incumplimiento no es subsanado, reservándose otro tipo de acciones legales que
pudieran proceder.
Confidencialidad
11. Ambas partes se comprometen a guardar la máxima reserva y secreto sobre la información y documentos que mutuamente se proporcionen como consecuencia de la ejecución del presente contrato, comprometiéndose a no divulgarlos, así como a no publicarlos ni, directa o indirectamente, ponerlos a disposición de terceros, sin el previo consentimiento expreso de la otra parte.
Ambas partes podrán transmitir los documentos y/o información proporcionados a su personal y colaboradores en la medida en que ello sea imprescindible para la ejecución del presente contrato. En tal caso, ambas partes informarán a su personal y colaboradores de las obligaciones de confidencialidad establecidas en el present contrato, realizando a tal efecto cuantas
advertencias y suscribiendo con su personal cantos documentos sean necesarios al objeto de garantizar el cumplimiento de tales obligaciones.
El incumplimiento de las obligaciones de confidencialidad establecidas en el presente contrato facultará a la otra parte para reclamar la indemnización de cantos daños y perjuicios se cause por razón de tal incumplimiento, así como la restitución de todos los gastos, incluidos, en su caso, aquellos que pudieran devengarse como consecuencia de la eventual interposición de acciones legales en defensa de sus derechos.
Protección de datos personales
12. En cumplimiento de la normativa de Protección de Datos de Carácter Personal, las partes se informan sore la posibilidad de incorporar los datos personales facilitados en el presente documento a un fichero creado por ellas mismas con la finalidad del mantenimiento y gestión de la relación comercial. Asimismo, los titulares de dichos datos podrán ejercer los
derechos de acceso, oposición, rectificación y cancelación en las direcciones de los contratantes.
Para la correcta ejecución del presente contrato las partes se obligan a suscribir el correspondiente contrato de acceso a datos por terceros, en el cual el desarrollador será la encargado del tratamiento de los datos v el cliente el responsable de los mismos.
Propiedad intelectual
13. El cliente garantiza que pose la titularidad o, las obligatorias licencias y autorizaciones, sobre los derechos de propiedad intelectual derivados de aquellos contenidos e informaciones que hayan sido aportados por el cliente. Será exclusiva responsabilidad del cliente el obtener cuantas licencias y autorizaciones sean necesarias para garantizar la integridad de los derechos de propiedad intelectual y/o industrial derivados de tales contenidos cuya titularidad sea ostentada por terceros.
El cliente se compromete a asumir cualquier responsabilidad por reclamaciones dirigidas contra el desarrollador por infracción de derechos de propiedad intelectual y/o industrial portal concepto, asumiendo cuantos gastos, costes e indemnizaciones se deriven contra ella con motivo de dicha reclamación.
La empresa desarrolladora cede todos los derechos de explotación económica al cliente, derivados de los contenidos y sus modificaciones, cuando se incorporen a la página web y una vez terminados los trabajos de creación y desarrollo que se realicen sobre los mismos. No se incluyen los derechos derivados de la programación, del código fuente y sistemas
informáticos propiedad de la empresa desarrolladora que se han incluido en la página web, sobre los que el cliente sólo tendrá una licencia de uso mientras el presente contrato esté vigente, que no podrá compartir con terceros.
Responsabilidades
14. El desarrollador no se hace responsable:
a. De errores, daños o defectos producidos en la página web por uso, manipulación o mantenimiento por personal no autorizado, o por un uso negligente del cliente.
b. De errores, daños o defectos en el sitio web por incompatibilidades con otros soportes informáticos, servidores o navegadores que tengan características diferentes a las acordadas.
c. De los daños y perjuicios producidos por la presencia de virus u otros elementos que pudieran producir alteraciones en los sistemas informáticos, documentos electrónicos o ficheros del cliente.
Garantía
15. El página o sitio web una vez publicado tendrá una garantía indefinida de funcionamiento a partir de la fecha de entrega
del proyecto, siempre y cuando su código se mantenga íntegro y sin modificación alguna, de acuerdo a la copia del mismo
entregada en soporte físico al cliente al finalizar dicho proyecto, y siempre que se mantenga idéntico el servicio contratado a
un tercero en materia de hosting, si es el caso.
Acciones
Duración del contrato
16. El presente contrato se considera finalizado en lo que se refiere a con la realización del proyecto, entrega y publicación
del sitio web por parte de la empresa desarrolladora y con el pago del precio por parte del cliente.
Cualquiera de los firmantes podrá resolver o poner fin al contrato en el supuesto de que concurriesen cualquiera de las
En el caso de resolución o terminación por incumplimiento, deberá mediar requerimiento previo o preaviso a la parte q incumplido, por la otra parte, para que en el improrrogable plazo de 30 días desde la recepción de dicha notificación, solucione la situación que originó la causa de finalización del contrato. Si pasado dicho plazo, la parte que incumple no remedia tal situación, el contrato quedará resuelto o terminado de forma inmediata y automática.
Firma electrónica
17. Al usar la funcionalidad de -sign para los contratos electrónicos creados en la plataforma de Rocket Lawyer, las partes
acuerdan que este contrato es la copia original y que les vincula legalmente. Las partes recibirán un e-mail cuando este
contrato haya sido firmado y formalizado por las mismas, sirviendo como prueba de su completa validez legal.
Y como prueba de lo convenido ambos firman el presente contrato por duplicado.

En San Sebastian, a 03 de febrero de 2023

El desarrollador        El Cliente
'), 0, 1);
$pdf->Ln();

// Salida del PDF
$pdf->Output('I', 'quote_' . $quote_id . '.pdf');
?>
