<script>
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
        // Verificar si la b¨²squeda fue exitosa
        if (data.success) {
            // Llenar los campos con los datos del cliente
            document.getElementById('business').value = data.data.name;
            document.getElementById('email').value = data.data.email;
            // document.getElementById('business_type_id').value = data.data.business_type_id;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurri¨® un error al procesar la solicitud.');
    });
}
</script>
