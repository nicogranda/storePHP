<?php
$pages = 'invoices'; // Define la variable con un valor por defecto
?>

<div class="search-bar">
    <?php if ($currentPage > 1): ?>
        <a href="index.php?page=<?= $pages ?>&action=index&currentPage=<?= $currentPage - 1 ?>">&lt;&lt;</a>
    <?php endif; ?>
    
    <?php if ($currentPage < $totalPages): ?>
        <a href="index.php?page=<?= $pages ?>&action=index&currentPage=<?= $currentPage + 1 ?>">&gt;&gt;</a>
    <?php endif; ?>
    
    <form method="POST" action="index.php?page=<?= $pages ?>&action=search">
        <input type="text" name="search" placeholder="Search Quote Id" 
               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>">
        
        <select name="month">
            <option value="">Mes</option>
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>">
                    <?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>
                </option>
            <?php endfor; ?>
        </select>
        
        <select name="year">
            <option value="">Año</option>
            <?php for ($y = date("Y"); $y >= (date("Y") - 10); $y--): ?>
                <option value="<?= $y ?>"> <?= $y ?> </option>
            <?php endfor; ?>
        </select>
        
        <button type="submit">Buscar</button>
        
        <!--<a href="index.php?page=<?= $pages ?>&action=create">+</a>-->
    </form>
</div>


<main>
<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Quote</th>
            <th>Date</th>
            <th>Client</th>
            <th>Amount</th>
           
        </tr>
    </thead>
    <tbody>
<?php 
$totalAmount = 0; // Inicializar el total
foreach ($requires as $require): 
    $totalAmount += $require['amount']; // Sumar el monto a $totalAmount
?>
<tr>
    <td style="text-align: right;">
        <a href="index.php?page=invoices&action=show&id=<?php echo $require['id']; ?>">
            <?php echo $require['id']; ?>
        </a>
    </td>
    <td style="text-align: right;">
        <a href="index.php?page=quotes&action=show&id=<?php echo $require['quote_id']; ?>">
            <?php echo $require['quote_id']; ?>
        </a>
    </td>
    <td style="padding: 0 0 0 10px;">
        <?php echo date('Y-m-d', strtotime($require['created_at'])); ?>
    </td>
    <td style="padding: 0 0 0 10px;">
        <?php echo $require['business_name']; ?>
    </td>
    <td style="padding: 0 10px 0 10px; text-align:right;">
        <?php echo number_format($require['amount'], 2); ?>
    </td>
</tr>
<?php endforeach; ?>

<!-- Línea de total -->
<tr>
    <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
    <td style="text-align: right; font-weight: bold;">
        <?php echo number_format($totalAmount, 2); ?>
    </td>
</tr>

    </tbody>
</table>
</main>
<!-- Modal de Confirmación -->
<!--<div id="deleteModal" class="modal">-->
<!--    <div class="modal-content">-->
<!--        <p>¿Estás seguro de que deseas eliminar esta Order?</p>-->
<!--        <button id="confirmDelete">Sí, eliminar</button>-->
<!--        <button id="cancelDelete">Cancelar</button>-->
<!--    </div>-->
<!--</div>-->

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
            window.location.href = `index.php?page=order&action=delete&id=${deleteId}`;
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
<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    text-align: center;
    border-radius: 5px;
}

button {
    margin: 5px;
    padding: 10px;
    border: none;
    cursor: pointer;
}

#confirmDelete {
    background: red;
    color: white;
}

#cancelDelete {
    background: gray;
    color: white;
}
</style>