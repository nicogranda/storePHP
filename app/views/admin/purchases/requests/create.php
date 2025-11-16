<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Unidad</th>
                    <th>Valor de Unidad</th>
                    <th>Seleccionar</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; foreach ($products as $product) : ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= $product['name'] ?></td>
                        <td><?= $product['unit'] ?></td>
                        <td><?= $product['unit_value'] ?></td>
                        <td>
                            <button type="button" class="select-btn" 
                                    data-id="<?= $product['id'] ?>" 
                                    data-name="<?= $product['name'] ?>" 
                                    data-quantity="1" 
                                    data-unit-value="<?= $product['unit_value'] ?>">
                                +
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Formulario -->
<section class="container-form">
    <form name="form1" method="post" action="index.php?page=requests&action=create" accept-charset="UTF-8" onsubmit="return validateForm();">
        <div class="form-group">
            <input type="text" name="alias" id="alias" placeholder="Alias" onblur="search();" class="input-field">
            <span id="brandError" class="error-message" style="display:none;">Please fill in the Brand field</span>
        </div>

        <div class="form-group">
            <input type="text" name="business_name" id="businessName" placeholder="Business/Name" class="input-field">
            <span id="businessError" class="error-message">Please fill in the Business/Name field</span>
        </div>
        <div class="form-group">
            <input type="email" name="email" id="email" placeholder="E-mail" class="input-field">
            <span id="emailError" class="error-message">Please fill in a valid E-mail</span>
        </div>
        <!--<div class="form-group">-->
        <!--    <input type="text" name="business_type_id" id="business_type_id" placeholder="Business Type" class="input-field">-->
        <!--    <span id="businessTypeError" class="error-message">Please select a Business Type</span>-->
        <!--</div>-->
        <br>
        
        <div id="selectedItems"></div>
        
        <a href="#" id="openModal" class="custom-modal-trigger">+</a>
        <span id="itemError" class="error-message">Please fill in the Item field</span>
         
        <input type="submit" value="Submit" class='button-primary'>
    </form>
</section>
<div id="resultado"></div>


<!-- Definición de la función search -->
<script>
function search() {
    var alias = document.getElementById('alias').value;

    fetch('providers.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `alias=${encodeURIComponent(alias)}`
    })
    .then(response => response.json())
    .then(data => {
        // Verificar si la búsqueda fue exitosa
        if (data.success) {
            // Llenar los campos con los datos del cliente
            document.getElementById('businessName').value = data.data.name;
            document.getElementById('email').value = data.data.email;
            // document.getElementById('business_type_id').value = data.data.business_type_id;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud.');
    });
}

</script>

<script>

    let itemIndex = 0;
    document.querySelectorAll('.select-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const unitValue = this.getAttribute('data-unit-value');
            
            const selectedItemsDiv = document.getElementById('selectedItems');
            
            selectedItemsDiv.innerHTML += `
                <div>
                    <input type="hidden" name="item[${itemIndex}][product_id]" value="${id}" readonly>
                    <input type="text" name="x[${itemIndex}][product_name]" id="item" value="${name}" readonly>
                    <input type="text" name="item[${itemIndex}][quantity]" value="1" placeholder="Quantity">
                    <input type="text" name="item[${itemIndex}][unit_value]" value="${unitValue}" readonly>
                    <input type="hidden" name="item[${itemIndex}][discount]" value="0" readonly>
                    <input type="hidden" name="item[${itemIndex}][vat_rate]" value="0" readonly>
                    <input type="hidden" name="item[${itemIndex}][note]" value="-" readonly>
                    <input type="hidden" name="item[${itemIndex}][created_at]" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly>
                    <input type="hidden" name="item[${itemIndex}][updated_at]" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly>

                </div>
            `;
            itemIndex++;
        });
    });

    document.getElementById('openModal').addEventListener('click', function (event) {
        event.preventDefault();
        document.getElementById('modal').style.display = 'block';
    });

    document.querySelector('.close').addEventListener('click', function () {
        document.getElementById('modal').style.display = 'none';
    });


function validateForm() {
    let isValid = true;

    // Obtener elementos
    const alias = document.getElementById('alias');
    const businessName = document.getElementById('businessName');
    const email = document.getElementById('email');
    const itemError = document.getElementById('itemError');
    const aliasError = document.getElementById('brandError');
    const businessError = document.getElementById('businessError');
    const emailError = document.getElementById('emailError');

    // Validar campos vacíos
    if (alias.value.trim() === '') {
        aliasError.style.display = 'block';
        isValid = false;
    } else {
        aliasError.style.display = 'none';
    }

    if (businessName.value.trim() === '') {
        businessError.style.display = 'block';
        isValid = false;
    } else {
        businessError.style.display = 'none';
    }

    if (email.value.trim() === '') {
        emailError.style.display = 'block';
        isValid = false;
    } else {
        emailError.style.display = 'none';
    }

    // Validar formato de email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value.trim())) {
        emailError.style.display = 'block';
        isValid = false;
    } else {
        emailError.style.display = 'none';
    }

    // Validar los ítems seleccionados
    const selectedItems = document.querySelectorAll('#selectedItems input[name*="product_name"]');
    if (selectedItems.length === 0) {
        itemError.style.display = 'block';
        isValid = false;
    } else {
        itemError.style.display = 'none';
    }

    return isValid;
}


</script>


<!-- Estilos para el modal -->
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        padding: 20px;
        width: 80%;
        max-width: 600px;
        border-radius: 8px;
        position: relative;
    }
    
    .modal-content {
        max-height: 80vh; /* Limita la altura máxima al 80% de la ventana */
        overflow-y: auto; /* Agrega scroll vertical si el contenido es muy grande */
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        color: #333;
    }

    iframe {
        width: 100%;
        height: 400px;
    }
    /*Form*/
     .form-group {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 500px;
        margin-bottom: 15px;
    }

    .input-field {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .error-message {
        color: red;
        font-size: 12px;
        display: none;
    }
</style>

