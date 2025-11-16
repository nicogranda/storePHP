<?php 
$user_id = $_SESSION['user_id']; // Asegúrate de que la sesión esté iniciada
?>
<section class="container-form">
    <form action="index.php?page=order&action=create" method="POST">
        <label for="request_id">RFQ ID:</label>
        <input type="number" name="request_id" id="request_id" required>
        
        <!-- Campo oculto para user_id -->
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        
        <button type="submit">Crear Orden</button>
    </form>    
</section>

<!--<section class="container-form">-->
<!--<form action="index.php?page=order&action=create" method="POST">-->
<!--    <label for="request_id">RFQ ID:</label>-->
<!--    <input type="number" name="request_id" id="request_id" required>-->
    
<!--    <label for="user_id">User ID:</label>-->
<!--    <input type="number" name="user_id" id="user_id" required>-->
    
<!--    <button type="submit">Crear Orden</button>-->
<!--</form>    -->
<!--</section>-->
