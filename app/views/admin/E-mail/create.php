<head>
    <meta charset="UTF-8"> <!-- Esta metaetiqueta especifica UTF-8 -->
    <title>E-mail</title>

    <link rel="stylesheet" href="css/email.css" type="text/css" charset="utf-8" />
</head>
<main class="container-form">
    
    <form action="" method="post" id="f1" name="f1" enctype="multipart/form-data" onsubmit="return validacion()">
        
        <!-- Sender's Email input -->
        <input type="email" name="senderEmail" id="senderEmail" placeholder="Sender Email" value="contact@ikusa.net" readonly class='item'>

          <!--alias input -->
        <input type="text" name="alias" id="alias" placeholder="Alias" onblur="search();" class="input-field">
 
        <!-- Business input -->
        <input type="text" name="business_name" id="businessName" placeholder="Business">
        
        <!-- Email input -->
        <input type="email" name="mailerTo" id="email" placeholder="Email" class='item'>
        <div id='alertemail' style='display:none;' class="alert">E-mail</div>

        <!-- Representative input -->
        <input type="text" name="representative" id="representative" placeholder="Representative" class='item'>
        
        <!-- Subject input -->
        <input type="text" name="subject" id="subject" placeholder="Subject" class='item'>
        <div id='alertsubject' style='display:none;' class="alert">Subject</div>
        
        
        <!-- Campaign input -->
        <input type="text" name="campaign" id="campaign" placeholder="Campaign" class='item'>
        <div id='campaign' style='display:none;' class="alert">Show campaign name</div>
        
        <!-- Message selector -->
            <select name="message_id" id="message_id" onchange="toggleMessageTextarea()" class='item'>
                <option value="">Select</option>
                <option value="Layout">Layout</option>
                <option value="Final Artwork">Final Artwork</option>
                <option value="Information">Information</option>
                <option value="Goods Delivery Note (GDN) ">Goods Delivery Note (GDN) </option>
                <option value="Document Transmittal">Document Transmittal</option>
                
            </select>
        <div id='alertdate' style='display:none;' class="alert">Show Message</div>
        
        <!-- Textarea without TinyMCE -->
        <div class='item' id="messageContainer" style='display: none;'>
            <textarea name="message" id="message" rows="10" cols="50" placeholder="Enter your message here"></textarea>
        </div>
    
        <!-- File input -->
        <!--<input type="file" name="attachment[]" multiple title="Attachment" class='item'>-->
        <div style="padding: 0px 0 20px 0;">
            <label for="fileUpload" class="custom-file-upload">Adjuntar archivo</label>
            <input type="file" id="fileUpload" name="attachment[]" multiple title="Attachment" class='item'>
            <span id="file-name">Ningún archivo seleccionado</span> <!-- Aquí se mostrarán los archivos -->
        </div>
        
        <!-- Submit button -->
        <input type="submit" name="send" id="send" value="Send" class="button-principal">
    
    </form>
</main>

<script>

// Search alias
function search() {
    var alias = document.getElementById('alias').value;

    fetch('clients.php', {
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
    
// Change file attachment
document.getElementById('fileUpload').addEventListener('change', function() {
    let fileName = this.files.length > 0 ? Array.from(this.files).map(f => f.name).join(', ') : "Ningún archivo seleccionado";
    document.getElementById('file-name').textContent = fileName;
});

  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("send").disabled = true;

    let inputs = ["email", "subject", "campaign", "message_id"];
    inputs.forEach(id => {
        document.getElementById(id).addEventListener("input", validarFormulario);
    });

    // Evento para actualizar los archivos seleccionados
    document.getElementById('fileUpload').addEventListener('change', function() {
        let fileList = this.files;
        let fileName = fileList.length > 0 ? Array.from(fileList).map(f => f.name).join(', ') : "Ningún archivo seleccionado";
        document.getElementById('file-name').textContent = fileName;
    });

    // Agregar evento para mostrar el textarea según la opción seleccionada
    document.getElementById('message_id').addEventListener("change", toggleMessageTextarea);

    function toggleMessageTextarea() {
        const messageContainer = document.getElementById('messageContainer');
        messageContainer.style.display = document.getElementById('message_id').value === "Information" ? "block" : "none";
        validarFormulario();
    }

    function validarFormulario() {
        let email = document.getElementById('email').value.trim();
        let subject = document.getElementById('subject').value.trim();
        let campaign = document.getElementById('campaign').value.trim();
        let message_id = document.getElementById('message_id').value;

        let valid = true;

        if (email === "") {
            document.getElementById('alertemail').style.display = 'block';
            document.getElementById('alertemail').innerText = 'El email no puede estar vacío';
            valid = false;
        } else {
            document.getElementById('alertemail').style.display = 'none';
        }

        if (subject === "") {
            document.getElementById('alertsubject').style.display = 'block';
            document.getElementById('alertsubject').innerText = 'El asunto no puede estar vacío';
            valid = false;
        } else {
            document.getElementById('alertsubject').style.display = 'none';
        }

        // Activa o desactiva el botón según la validación
        document.getElementById("send").disabled = !valid;
    }
});

</script>

<style>
.custom-file-upload {
    display: inline-block;
    background-color: #F15A24; /* Anaranjado */
    color: white;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-family: var(--font-primary, sans-serif);
}
.custom-file-upload:hover {
    background-color: #D94E1F; /* Un poco más oscuro */
}
input[type="file"] {
    display: none;
}


 /* General body and page styles */
/*body {*/
/*    font-family: Arial, sans-serif;*/
/*    background-color: #f4f4f4;*/
/*    margin: 0;*/
/*    padding: 0;*/
/*    display: flex;*/
/*    justify-content: center;*/
/*    align-items: center;*/
/*    height: 100vh;*/
/*}*/

/* Container for the form */
/*.container-form {*/
/*    background-color: #fff;*/
/*    padding: 20px;*/
/*    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
    /*width: 30%; /* Reduced to 30% for a smaller form */
    /*max-width: 350px; /* Adjusted max-width to keep the form compact */
/*    border-radius: 8px;*/
/*}*/

/* Style for the form items */
form .item {
    margin-bottom: 15px;
}

/* Input fields styling */
input[type="email"],
input[type="file"],
input[type="text"],
select,
textarea {
    width: 100%;  /* Ensures inputs take up full width of the container */
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

/* Placeholder styles */
input::placeholder, textarea::placeholder {
    color: #888;
}

/* Submit button styles */
input[type="submit"] {
    background-color: #F15A24; /* Using your primary color */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    width: 100%; /* Makes the submit button take full width of the container */
}

/* Hover effect for the submit button */
input[type="submit"]:hover {
    background-color: #e14e18; /* Darken color for hover effect */
}

/* Alert message styling */
.alert {
    color: red;
    font-size: 12px;
    display: none;
}

/* Responsive Design */
@media (max-width: 600px) {
    /*.container-form {*/
       /* width: 90%; /* Make the container smaller on mobile */
    /*    padding: 15px;*/
    /*}*/

  
    }
}

</style>