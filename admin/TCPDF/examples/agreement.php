<?php
$client='VINOW APP, S.L.';
$client_representative='Alvaro Gimenez';
$client_address='Donostia  San Sebastián, 20004, Gipuzkoa, España';
$NIF='B72871403';
$balance='7.000,00';
$DUNS='10-086-1837';
$address='Peachtree Corners, 30092, Georgia, Estados Unidos';
$agreement_date='20 de diciembre de 2023';

// include class
require ('fpdf/fpdf.php');
// create document
$pdf = new FPDF();
$pdf->AddPage();

// config document
$pdf->SetTitle('Agreement');
$pdf->SetAuthor('Ikusa');
$pdf->SetCreator('FPDF Maker');

// add title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Contrato de Desarrollo Web'), 0, 0, 'C');
$pdf->Ln();

// add text
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 7, utf8_decode('REUNIDOS	(1) IKUSA LLC, con domicilio social en '.$address.', con DUNS '.$DUNS.' actúa representada en este acto por Nicolás Granda Bauza,
en su condición de Manager, en adelante EL DESARROLLADOR;  y '.$client_representative.' en representacion de '.$client.', con NIF '.$NIF.', y con domicilio en '.$client_address.', en lo sucesivo EL CLIENTE, actuando en su propio nombre y derecho, teniendo ambos la capacidad para firmar y quedar obligados por este contrato y EXPONEN:								.'), 0, 1);
$pdf->Ln();
$pdf->MultiCell(0, 7, utf8_decode('1. Que el desarrollador se dedica diseñar y producir páginas web.
2. Que el cliente está interesado en que le realicen el diseño y desarrollo de una página web.
3. Que el cliente está interesado en contratar los servicios del desarrollador, de acuerdo con las condiciones establecidas en este contrato.
4. Se reconocen la capacidad legal suficiente para celebrar este contrato de desarrollo de página web.'), 0, 1);
$pdf->Ln();
$pdf->MultiCell(0, 7, utf8_decode('CLÁUSULAS
Objeto del contrato
5.El desarrollador se obliga a diseñar y desarrollar una página web para el cliente.

Contenido del servicio
6. El desarrollador realizará el proyecto con la colaboración activa del cliente para incorporar, según sus instrucciones, los contenidos del sitio web y facilitarse mutuamente cualquier documentación necesaria, tanto en soporte físico como digital
    a) E-commerce (no en CMS), donde el usuario se autentifique para hacer la compra. 
    b) Cuatro 4 roles en el Log Up/Log In: Comprador, Vendedor, Productor y Administrador. 
    c) Cada rol tiene ciertos atributos para agregar, editar (modificar) y/o eliminar, de su base datos. 
    d) Un catálogo al inicio que envía, a la ficha técnica, y la capacidad de dar comprar, para hacer un pedido. 
    c) Pedido que el comprador puede manejar y recibirá el Vendedor en su E-mail/WhatsApp, para procesar con poco tiempo de respuesta. 
    d) Con un buscador sobre la base datos propias. 
    e) Idioma: Castellano. 
    f) 200 productos estimados en la presentación del primer mes. 
    g) No se contempla pasarela de pago (aún)
    h) Fotografías: serán proporcionadas  por el cliente.		
    i) Sonido: No contemplado .							
    j) Logotipos : Todas las páginas llevarán en el encabezado el logotipo del cliente.
El desarrollador alojará el sitio web en su servidor de acceso a Internet y se encargará de las correspondientes actualizaciones, seguimiento del número de acceso a dicho sitio, elaboración de reportes de uso para el cliente, mantenimiento de la base de datos de usuarios de Internet que elaboran órdenes de pedido de productos y servicios del Cliente.

Se excluyen del contrato los siguientes servicios:
* Adaptación del sitio web a circunstancias especiales del cliente o a nuevas necesidades surgidas con posterioridad con el uso del sitio web.
* La inclusión de nuevos apartados o ampliaciones sustanciales del sitio web. Se deberá acordar la dimensión de estas ampliaciones o nuevas creaciones.
* La corrección de errores que se pudieran imputar a una manipulación del sitio web que se haya realizado por personal no autorizado por la parte.
* Los gastos de desplazamiento.

Plazo de ejecución
7. En cuando al plazo de ejecución:
El cliente se obliga a proporcionar toda la información que el desarrollador requiera para el diseño de la página web, a mas tardar dentro del plazo de 5 dias transcurridos desde la firma del contrato. 
El desarrollador se obliga a entregar una versión preliminar o beta del sitio web, a más tardar en el plazo de 10 dias desde la entrega de la información necesaria para su diseño por parte del cliente.

Precio y forma de pago
8. PAGOS: El precio que el cliente pagará al desarrollador será el siguiente:
Por el diseño y desarrollo de la página web '.$balance.'Euros, impuestos no incluidos.
La cuenta Bancaria del DESARRROLLADOR es la siguiente
    Titular: Ikusa
    BIC: TRWIBEB1XXX
    IBAN: BE82 9678 2700 3168

El pago se hará de la siguiente forma:
 a) 25 % a la firma del contrato, que se podrá pagar mediante transferencia bancaria: 20 de diciembre de 2023.
 b) 25 % con la instalacion en el hosting de la empresa: 30 de diciembre de 2023.
 c) 50% en el momento de la activacion, despues de varias pruebas a satisfaccion del cliente: 15 de enero de 2023.


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
12. En cumplimiento de la normativa de Protección de Datos de Carácter Personal, las partes se informan sore la posibilidad de incorporar los datos personales facilitados en el presente documento a un fichero creado por ellas mismas con la finalidad del mantenimiento y gestión de la relación comercial.
Asimismo, los titulares de dichos datos podrán ejercer losderechos de acceso, oposición, rectificación y cancelación en las direcciones de los contratantes.
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
Cualquiera de los firmantes podrá resolver o poner fin al contrato en el supuesto de que concurriesen cualquiera de las las causas determinadas como inclumplimiento.
En el caso de resolución o terminación por incumplimiento, deberá mediar requerimiento previo o preaviso a la parte que incumplido, 
por la otra parte, para que en el improrrogable plazo de 30 días desde la recepción de dicha notificación, 
solucione la situación que originó la causa de finalización del contrato. Si pasado dicho plazo, la parte que incumple no remedia tal situación, el contrato quedará resuelto o terminado de forma inmediata y automática.

Firma Contrato
17. Las partes recibirán un e-mail cuando este contrato haya sido firmado y formalizado por las mismas, sirviendo como prueba de su completa validez legal.
Y como prueba de lo convenido ambos firman el presente contrato por duplicado.


En San Sebastian, '.$agreement_date.'

El desarrollador                                                              El Cliente
'), 0, 1);
$pdf->Ln();


// add image
//$pdf->Image('images/products/logotype.png', null, null, 180);

// output file
$pdf->Output('', 'fpdf-complete.pdf');
?>