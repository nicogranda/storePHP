<?php $page = "supplies"; ?>
<main>
<div class="search-bar">
    <?php if ($currentPage > 1): ?>
        <a href="index.php?page=<?php echo $page; ?>&action=index&currentPage=<?= $currentPage - 1 ?>">&lt;&lt;</a>
    <?php endif; ?>
    
    <?php if ($currentPage < $totalPages): ?>
        <a href="index.php?page=<?php echo $page; ?>&action=index&currentPage=<?= $currentPage + 1 ?>">&gt;&gt;</a>
    <?php endif; ?>
    
    <form method="POST" action="index.php?page=<?php echo $page; ?>&action=search">
        <input type="text" name="search" placeholder="Search Product" 
               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>">
        
        
        <button type="submit">Buscar</button>
        
        <a href="index.php?page=<?php echo $page; ?>&action=create">+</a>
    </form>
</div>

<table class="container-form">
 
        <tr>
            <th>Id</th>
            <th>Date</th>
            <th>Product</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
  
<?php foreach ($products as $product): ?>
<tr>
    <td style="text-align: right;">
        <a href="index.php?page=<?php echo $page; ?>&action=show&id=<?php echo $product['id']; ?>">
            <?php echo $product['id']; ?>
        </a>
    </td>
    <td style="padding: 0 0 0 10px;">
        <?php echo date('Y-m-d', strtotime($product['created_at'])); ?>
    </td>
    <td style="padding: 0 0 0 10px;">
        <?php echo $product['name']; ?>
    </td>
    <td style="padding: 0 0 0 10px; text-align: right;">
        <?php echo number_format($product['unit_value'], 2); ?>
    </td>
    <td>  
        <a href="javascript:void(0);" class="delete-btn" data-id="<?php echo $product['id']; ?>">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
   
</table>


<!-- Modal de Confirmación -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>¿Estás seguro de que deseas eliminar esta cotización?</p>
        <button id="confirmDelete">Sí, eliminar</button>
        <button id="cancelDelete">Cancelar</button>
    </div>
</div>
</main>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let deleteId = null;
    const modal = document.getElementById("deleteModal");
    const confirmBtn = document.getElementById("confirmDelete");
    const cancelBtn = document.getElementById("cancelDelete");

    // Mostrar modal al hacer clic en "Delete"
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            deleteId = this.getAttribute("data-id");
            modal.style.display = "block";
        });
    });

    // Confirmar eliminación
    confirmBtn.addEventListener("click", function () {
        if (deleteId) {
            window.location.href = `index.php?page=<?php echo $page; ?>&action=delete&id=${deleteId}`;
        }
    });

    // Cancelar eliminación
    cancelBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Cerrar modal al hacer clic fuera
    window.addEventListener("click", function (e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});
</script>


