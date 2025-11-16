<main>
<div class="search-bar">
    <?php if ($currentPage > 1): ?>
        <a href="index.php?page=orders&action=index&currentPage=<?= $currentPage - 1 ?>">&lt;&lt;</a>
    <?php endif; ?>
    
    <?php if ($currentPage < $totalPages): ?>
        <a href="index.php?page=orders&action=index&currentPage=<?= $currentPage + 1 ?>">&gt;&gt;</a>
    <?php endif; ?>
    
    <form method="POST" action="index.php?page=orders&action=search">
        <input type="text" name="search" placeholder="Search Order ID" 
               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>">
        <button type="submit">Buscar</button>
    </form>
</div>

<table class="container-form">
    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Client</th>
        <th>Price</th>
        <th>Action</th>
    </tr>
  
<?php 
$totalAmount = 0; // Inicializar el total
foreach ($orders as $order): 
    $totalAmount += $order['total']; // Sumar el monto a $totalAmount
?>
<tr>
    <td style="text-align: right;">
        <a href="index.php?page=orders&action=show&id=<?= $order['order_id']; ?>">
            <?= $order['order_id']; ?>
        </a>
    </td>
    <td style="padding: 0 0 0 10px;">
        <?= date('Y-m-d', strtotime($order['order_date'])); ?>
    </td>
    <td style="padding: 0 0 0 10px;">
        <?= htmlspecialchars($order['user_email'] ?? 'No user', ENT_QUOTES, 'UTF-8'); ?>
    </td>
    <td style="padding: 0 0 0 10px; text-align: right;">
        <?= number_format((float)$order['total'], 2); ?>
    </td>
    <td>
        <a href="javascript:void(0);" class="delete-btn" data-id="<?= $order['order_id']; ?>">Delete</a>
    </td>
</tr>
<?php endforeach; ?>

<tr>
    <td colspan="3" style="text-align: right; font-weight: bold;">Total</td>
    <td style="text-align: right; font-weight: bold;">
        <?= number_format($totalAmount, 2); ?>
    </td>
    <td></td>
</tr>
</table>

<!-- Modal de Confirmación -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>¿Estás seguro de que deseas eliminar esta orden?</p>
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

    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            deleteId = this.getAttribute("data-id");
            modal.style.display = "block";
        });
    });

    confirmBtn.addEventListener("click", function () {
        if (deleteId) {
            window.location.href = `index.php?page=orders&action=delete&id=${deleteId}`;
        }
    });

    cancelBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});
</script>
