<head>
    <style>

.table-container {
    width: 90%; /* Usa el ancho completo */
    max-width: 100%; /* Previene desbordamiento horizontal */
    overflow-x: auto; /* Activa scroll horizontal solo si es necesario */
}

table {
    width: 100%; /* Ancho completo dentro del contenedor */
    max-width: 100%; /* Asegura que no desborde */
    border-collapse: collapse;
    margin: 0 auto;
    word-wrap: break-word; /* Envuelve contenido largo */
}
        .container {
            text-align: center; /* Centra el contenido del contenedor */
            width: 100%; /* Ajusta el ancho del contenedor */
        }
        
        .container img {
            display: block; /* Necesario para que margin auto funcione correctamente */
            margin: 0 auto; /* Centra la imagen horizontalmente */
            width: 50%; /* Ajusta el ancho de la imagen */
        }
        
        th, td {
            border: 1px solid #ccc; /* Borde alrededor de las celdas */
            padding: 10px; /* Espacio dentro de las celdas */
            /* text-align: right;  Centra el texto dentro de las celdas */
        }
        
        th {
            /* background-color: orangered;  Fondo gris claro para las cabeceras */
            /* color: white; Fuente blanca */
        }
        
        th:first-child {
            width: 60%; /* Ajusta el ancho de la primera columna (Producto) */
        }
        
        th:nth-child(2), td:nth-child(2),
        th:nth-child(3), td:nth-child(3),
        th:nth-child(4), td:nth-child(4),
        th:nth-child(5), td:nth-child(5){
            width: 10%; /* Ajusta el ancho de las demás columnas */
        }
        
        .item input {
            height: 100%;
            width: 100%;
            padding: 0px;
            text-align: right;
            border: none;
        }
    
        .events {
            padding-top: 20px;
        }
      
#stripeModal div {
    width: 90%;
    max-width: 600px;
}

@media (max-width: 768px) {
    #stripeModal div {
        width: 95%;
    }
}

    </style>
</head>

<form name="form1" action="" method="POST">
<section class="table-container">
    

     <table>
    <tr>
        <th colspan="8" style="background-color:white; color:orangered; text-align:right; border: none;">
                <span style="color:gray; text-align:right;">Order:</span>
            <span style="color:orangered; text-align:right;"><?php echo $order['id']; ?></span><br>
            <span style="color:gray; text-align:right;">RFQ:</span>
            <span style="color:orangered; text-align:right;"><?php echo $order['request_id']; ?></span><br>
            <span style="color:gray; text-align:right;"><?php echo $order['created_at']; ?></span>
        </th>
    </tr>            
    <tr>        
        <th colspan='8' style="text-align:left; border: none; font-weight:400;">
            <p style='font-weight:800;'><?php echo $operation['business_name'];?></p>
            <p style='color:gray;'><?php echo $operation['business_email'];?></p>
            <!--<p style='color:gray;'><?php //echo $operation['client_address'];?></p>-->
        </th>
    </tr>
</table>

       <table>        
            <tr>
                <th style="background-color:orangered; color:white;">Goods/Service</th>
                <th style="background-color:orangered; color:white;">Quantity</th>
               
                <th style="background-color:orangered; color:white;">Unit Value (€)</th>
                <th style="background-color:orangered; color:white;">Unit</th>
                <th style="background-color:orangered; color:white;">Discount</th>
                <th style="background-color:orangered; color:white;">VAT Rate</th>
                <th style="background-color:orangered; color:white;">Total (€)</th>
                <th style="background-color:orangered; color:white;"></th>
            </tr>
        </thead>
        <tbody>
            
<div id="stripeModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:1000;">
    <div style="position:relative; margin:5% auto; width:90%; max-width:600px; background:white; padding:20px; border-radius:8px;">
        <button id="closeModal" style="position:absolute; top:10px; right:10px; background:orangered; color:white; border:none; border-radius:50%; width:30px; height:30px; cursor:pointer;">&times;</button>
        <iframe id="stripeIframe" src="" style="width:100%; height:400px; border:none;"></iframe>
    </div>
</div>

<?php

$total = 0;
$vat = 0;
foreach($operation_details as $operation_detail ) {
    
?>
    <tr>
        <td style='text-align:left;'><?php echo $operation_detail['product_name']; ?></td>
        <td class='item'><input type="text" name="quantity[<?php echo $operation_detail['id']; ?>]" value="<?php echo $operation_detail['quantity']; ?>" style='text-align:right;'></td>
        <td class='item'><input type="text" name="unit_value[<?php echo $id; ?>]" value="<?php echo number_format($operation_detail['unit_value'], 2); ?>"  style='text-align:right;'></td>

        <td><?php echo $operation_detail['unit'];?></td>
        <td class='item'><input type="text" name="discount[<?php echo $id; ?>]" value="<?php echo number_format($operation_detail['discount'], 2); ?>"  style='text-align:right;'></td>
        <td class='item'><input type="text" name="vat_rate[<?php echo $id; ?>]" value="<?php echo number_format($operation_detail['vat_rate'], 2); ?>"  style='text-align:right;'></td> 

<?php       
$balance = $operation_detail['quantity'] * $operation_detail['unit_value'] * ( 1 - $operation_detail['discount'] /100 ) * ( 1 + $operation_detail['vat_rate'] /100 );
?>
        <td><?php echo number_format($balance, 2); ?></td>
        <td><input type="checkbox" name="del[]" id="del<?php echo $id;?>" value="<?php echo $id;?>"></td>
        <input type="hidden" name="item[]"  value="<?php echo $id; ?>"> 
    </tr>
    <tr>
        <td colspan="8" class='item'><input type="text" name="note[<?php echo $id; ?>]" value="<?php echo $operation_detail['note'] ?>"  style='text-align:left;'></td>
    </tr>
<?php
            }

?>
    <tr>
        <td colspan="6" style='text-align:left;'>VAT Rate</td>
        <!--<td colspan="1"><input type="text" name="iva" style='text-align:right;'></td>-->
        <td colspan="2"  style='text-align:right;'><?php echo number_format($vat, 2); ?></td>
    </tr>
    
    <tr>
        <td colspan="6" style='text-align:left;'>Total</td>
        <td colspan="2"  style='text-align:right;'><?php echo number_format($total, 2); ?></td>
    </tr>
    
    </tbody>
    </table>
</section>
    
<input type="hidden" name="operation_id"  value="<?php echo  $operation['id'];?>" id="operation_id" >
    <section class="events">   
       
        <input type="button" name="print" value="Print" onclick="downloadPDF()">
        <a href="index.php?page=order&action=mail&id=<?php echo $order['id']; ?>">Mail</a>
        <a href="index.php"> Otro</a>
    </section>    
    <section>
        <?php if(isset($_SESSION['message'])) { echo $_SESSION['message'];} ?>
    </section>
</form>

<script>
function downloadPDF() {
    // Capturamos el valor de budget_id y redirigimos al enlace con ese parámetro
    var quoteId = document.getElementById('quote_id').value;
    var url = 'https://ikusa.net/admin/fpdf/quote.php?id=' + quoteId;
    
    // Abre la URL en una nueva pestaña
    window.open(url, '_blank');
    // Redirige a la URL que genera el PDF
    //window.location.href = url;
}

function openStripeModal(amount) {

    const modal = document.getElementById('stripeModal');
    const iframe = document.getElementById('stripeIframe');
    iframe.src = `https://ikusa.net/stripe/index.php?amount=${amount}`; // Pasa el monto a Stripe
    modal.style.display = 'block';
}



document.getElementById('closeModal').onclick = function () {
    const modal = document.getElementById('stripeModal');
    const iframe = document.getElementById('stripeIframe');
    iframe.src = ''; // Limpia el iframe para evitar que siga cargando en segundo plano
    modal.style.display = 'none';
};

window.onclick = function (event) {
    const modal = document.getElementById('stripeModal');
    if (event.target === modal) {
        const iframe = document.getElementById('stripeIframe');
        iframe.src = ''; 
        modal.style.display = 'none';
    }
};

</script>