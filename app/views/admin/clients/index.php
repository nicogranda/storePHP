<main>
    <div class="search-bar">
        <form method="GET" action="index.php?page=clients&action=index">
            <input type="text" name="search" placeholder="Search by ID or Name" 
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            <button type="submit">Buscar</button>
            <a href="index.php?page=clients&action=create">+</a>
        </form>

        <?php if ($currentPage > 1): ?>
            <a href="index.php?page=clients&action=index&currentPage=<?= $currentPage - 1 ?>">&lt;&lt;</a>
        <?php endif; ?>
        <?php if ($currentPage < $totalPages): ?>
            <a href="index.php?page=clients&action=index&currentPage=<?= $currentPage + 1 ?>">&gt;&gt;</a>
        <?php endif; ?>
    </div>

    <table class="container-form">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

       <?php 
$totalAmount = 0;
foreach ($businessList as $client): 
    $totalAmount += $client['amount'] ?? 0; // opcional si tienes campo amount
?>
<tr>
    <td>
        <a href="index.php?page=clients&action=show&id=<?= $client['id'] ?>">
            <?= $client['id'] ?>
        </a>
    </td>
    <td><?= htmlspecialchars($client['name'], ENT_QUOTES, 'UTF-8') ?></td>
    <td style="text-align: right;">
        <?= isset($client['amount']) ? number_format($client['amount'], 2) : '' ?>
    </td>
    <td>
        <?= isset($client['created_at']) ? date('Y-m-d', strtotime($client['created_at'])) : '' ?>
    </td>
    <td>
        <a href="javascript:void(0);" class="delete-btn" data-id="<?= $client['id'] ?>">Delete</a>
    </td>
</tr>
<?php endforeach; ?>


        <!-- Total -->
        <tr>
            <td colspan="2" style="text-align: right; font-weight: bold;">Total</td>
            <td style="text-align: right; font-weight: bold;">
                <?= number_format($totalAmount, 2) ?>
            </td>
            <td colspan="2"></td>
        </tr>
    </table>

    <!-- Modal de Confirmación -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p>¿Estás seguro de que deseas eliminar este registro?</p>
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
            window.location.href = `index.php?page=clients&action=delete&id=${deleteId}`;
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
