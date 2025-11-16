<style>
p {
    line-height: 0.80; /* Ajusta el espacio entre líneas */
    min-height: 20px; /* Evita que el párrafo sea demasiado pequeño */
    margin: 0; /* Elimina márgenes extra */
    padding: 5px 0; /* Espaciado interno opcional */
}

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

<section id="stripeModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:1000;">
    <div style="position:relative; margin:5% auto; width:90%; max-width:600px; background:white; padding:20px; border-radius:8px;">
        <button id="closeModal" style="position:absolute; top:10px; right:10px; background:orangered; color:white; border:none; border-radius:50%; width:30px; height:30px; cursor:pointer;">&times;</button>
        <iframe id="stripeIframe" src="" style="width:100%; height:400px; border:none;">
        </iframe>
    </div>
</section>

<form name="form1" action="index.php?page=requests&action=update&id=<?php echo $operation['id']; ?>" method="POST">
<section class="table-container">
     <table>
        <tr>
            <th colspan="8" style="background-color:white; color:orangered; text-align:right; border: none;">
                <span style="color:gray; text-align:right;">Request:</span>
                <span style="color:orangered; text-align:right;"><?php echo $operation['id']; ?></span><br>
                <span style="color:gray; text-align:right;font-size:10px;"><?php echo $operation['created_at']; ?></span>
            </th>
        </tr>
        <tr>
            <th colspan='8' style="text-align:left; border: none; font-weight:400;">
                <p><input type="text" name="operation['alias']" value="<?php echo $operation['business_alias'];?>" style='text-align:left;'></p>
                <p style='font-weight:800;'><?php echo $operation['business_name'];?></p>
                <p style='color:gray;'><?php echo $operation['business_email'];?></p>
                <p style='color:gray;'><?php echo $operation['business_address'];?></p>
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
        <tbody>
        <?php foreach ($operation['details'] as &$operation_detail): ?>
        <tr>
           <td style='text-align:left;'><?php echo $operation_detail['product_name']; ?></td>
            <td class='item'>
                <input type="text" name="operation_detail[<?php echo $operation_detail['id']; ?>][quantity]" value="<?php echo $operation_detail['quantity']; ?>" style='text-align:right;'>
            </td>
            <td class='item'>
                <input type="text" name="operation_detail[<?php echo $operation_detail['id']; ?>][unit_value]" value="<?php echo number_format($operation_detail['unit_value'], 2); ?>" style='text-align:right;'>
            </td>
            <td><?php echo $operation_detail['unit']; ?></td>
            <td class='item'>
                <input type="text" name="operation_detail[<?php echo $operation_detail['id']; ?>][discount]" value="<?php echo number_format($operation_detail['discount'], 2); ?>" style='text-align:right;'>
            </td>
            <td class='item'>
                <input type="text" name="operation_detail[<?php echo $operation_detail['id']; ?>][vat_rate]" value="<?php echo number_format($operation_detail['vat_rate'], 2); ?>" style='text-align:right;'>
            </td>
    
            <td>
                <?php
                    echo number_format($operation_detail['balance'], 2);
                ?>
            </td>
        <td>
            <input type="checkbox" name="del[]" id="del<?php echo $operation_detail['id']; ?>" value="<?php echo $operation_detail['id']; ?>">
        </td>
            <input type="hidden" name="item[]" value="<?php echo $operation_detail['id']; ?>"> 
        </tr>
            <tr>
                <td colspan="8" class='item'>
                    <input type="text" name="operation_detail[<?php echo $operation_detail['id']; ?>][note]" value="<?php echo $operation_detail['note']; ?>" style='text-align:left; height:30px;'>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php //unset($operation_detail); ?>

    <tr>
        <td colspan="6" style='text-align:left;'>VAT Rate</td>
        <!--<td colspan="1"><input type="text" name="iva" style='text-align:right;'></td>-->
        <td colspan="2"  style='text-align:right;'><?php echo number_format($operation['vat'], 2); ?></td>
    </tr>
    
    <tr>
        <td colspan="6" style='text-align:left;'>Total</td>
        <td colspan="2"  style='text-align:right;'><?php echo number_format($operation['total'], 2); ?></td>
    </tr>
    
    </tbody>
    </table>
</section>
    
<input type="hidden" name="operation_id"  value="<?php echo  $operation['id'];?>" id="operation_id" >

    <section class="events">   
        <input type="submit" name="update" value="Update">
        <a href="index.php?page=quote_details&action=create&id=<?php echo $operation['id']; ?>">+</a>
        <a href="index.php?page=quote_details&action=delete&id=<?php echo $operation_detail['id']; ?>">-</a>
        
        <input type="button" name="print" value="Print" onclick="downloadPDF()">

        <!--<a href="index.php?page=requests&action=mail&id=<?php //echo $operation['id']; ?>">Mail</a>-->

      
        <a href="#" onclick="openStripeModal(<?php echo $operation['total']; ?>); return false;">Pay</a>
        <?php if($operation['required']){
            echo $operation['required'];
        } else {
        ?>
        <a href="index.php?page=invoices&action=create&operation=<?php echo $operation['id'];?>">Order<?php echo $operation['required'];?></a>        
        <?php } ?>       

        <a href="index.php?page=quotes&action=index"> Otro</a>
    </section>    
    
</form>
<form action="index.php?page=requests&action=mail&id=<?= $id ?>" method="post" enctype="multipart/form-data">
    <label for="attachment">Adjuntar archivos:</label>
    <input type="file" name="attachment[]" id="attachment" multiple>
    
    <button type="submit">Enviar</button>
</form>

<script>

function openStripeWindow() {
    window.open('https://ikusa.net/stripe/index.php', '_blank', 'width=600,height=700');
}
function downloadPDF() {
  
    // Capturamos el valor de budget_id y redirigimos al enlace con ese parámetro
    var operationId = document.getElementById('operation_id').value;
    //var url = 'https://ikusa.net/admin/fpdf/quote.php?id=' + operationId;
     
    var url = 'https://ikusa.net/admin/index.php?page=requests&action=print&id=' + operationId;
    // var url = 'https://ikusa.net/admin/fpdf/quote.php?id=' + quoteId;
    // Abre la URL en una nueva pestaña
    window.open(url, '_blank');
    // Redirige a la URL que genera el PDF
    //window.location.href = url;
}

// // function openStripeModal(amount) {
//     alert(amount);
//     const modal = document.getElementById('stripeModal');
//     const iframe = document.getElementById('stripeIframe');
//     iframe.src = `https://ikusa.net/stripe/index.php?amount=${amount}`; // Pasa el monto a Stripe
//     modal.style.display = 'block';
// }

// document.getElementById('closeModal').onclick = function () {
//     const modal = document.getElementById('stripeModal');
//     const iframe = document.getElementById('stripeIframe');
//     iframe.src = ''; // Limpia el iframe para evitar que siga cargando en segundo plano
//     modal.style.display = 'none';
// };


// function openStripeModal(total) {
//     var modal = document.getElementById("stripeModal");
//     var iframe = document.getElementById("stripeIframe");
//     iframe.src = `https://ikusa.net/stripe/index.php?amount=${total}`; // Pasa el monto a Stripe
//     // Asegura que 'total' esté en el formato correcto
//     if (!total || isNaN(parseFloat(total))) {
//         alert("El monto no es válido");
//         return;
//     }

//     // Define la URL de Stripe (ajústala según tu integración)
//     //iframe.src = "tu_url_de_pago_de_stripe_aqui?amount=" + total;

//     // Muestra el modal
//     modal.style.display = "block";
// }

    function openStripeModal(total) {
        var modal = document.getElementById("stripeModal");
        var iframe = document.getElementById("stripeIframe");
        iframe.src = `https://ikusa.net/stripe/index.php?amount=${total}`; // Pasa el monto a Stripe
        // Asegura que 'total' esté en el formato correcto
        if (!total || isNaN(parseFloat(total))) {
            alert("El monto no es válido");
            return;
        }

        // Muestra el modal
        modal.style.display = "block";
    }

    // Cerrar modal al hacer clic en el botón de cerrar
    document.getElementById("closeModal").addEventListener("click", function () {
        document.getElementById("stripeModal").style.display = "none";
        document.getElementById("stripeIframe").src = ''; // Limpia el iframe
    });

    // Cerrar el modal al hacer clic fuera de él
    window.onclick = function (event) {
        const modal = document.getElementById('stripeModal');
        if (event.target === modal) {
            const iframe = document.getElementById('stripeIframe');
            iframe.src = ''; 
            modal.style.display = 'none';
        }
    };

// Cerrar modal
// document.getElementById("closeModal").addEventListener("click", function () {
//     document.getElementById("stripeModal").style.display = "none";
// });



window.onclick = function (event) {
    const modal = document.getElementById('stripeModal');
    if (event.target === modal) {
        const iframe = document.getElementById('stripeIframe');
        iframe.src = ''; 
        modal.style.display = 'none';
    }
};

</script>