<!-- Browser in pagination -->
<div class="pagination" style="
	display: flex;
	flex-direction: row;
	flex-wrap: nowrap;
	justify-content: center;
	align-items: center;
	align-content: stretch;">

    <?php
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        echo "<a href='index.php?page=order&action=index&currentPage=$prevPage'><<</a>";
    }

    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;
        echo "<a href='index.php?page=order&action=index&currentPage=$nextPage'>>></a>";
    }
    ?>

<form method="POST" action="index.php?page=order&action=search">
    <div style="display: flex; width: 300px;">
    <input type="text" name="search" placeholder="<?php echo htmlspecialchars('Search Order Id'); ?>" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>" style="flex-grow: 1; margin-right: 10px;">

    <button type="submit" style="height: 35px;">Search</button>
    </div>
</form>
</div>


<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>RFQ</th>
            <th>Date</th>
            <th>Provider</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($requires as $rfq): ?>
            <tr>
                <td style="text-align: right;"><a href="index.php?page=order&action=show&id=<?php echo $rfq['id']; ?>"><?php echo $rfq['id']; ?></a></td>
                <td style="text-align: right;"><a href="index.php?page=RFQ&action=show&id=<?php echo $rfq['request_id']; ?>"><?php echo $rfq['request_id']; ?></a></td>
                <td style="padding: 0 0 0 10px;"> <?php echo date('Y-m-d', strtotime($rfq['created_at'])); ?></td>
                <td style="padding: 0 0 0 10px;"> <?php echo $rfq['business_name']; ?></td>
                <td><a href="javascript:void(0);" class="delete-btn" data-id="<?php echo $rfq['id']; ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal de Confirmación -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>¿Estás seguro de que deseas eliminar esta Order?</p>
        <button id="confirmDelete">Sí, eliminar</button>
        <button id="cancelDelete">Cancelar</button>
    </div>
</div>

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