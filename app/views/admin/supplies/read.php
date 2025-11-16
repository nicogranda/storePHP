<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Supplies</h2>
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
                <?php $i = 0; foreach ($supplies as $supply) : ?>
                    <tr>
                        <td><?= $supply['id'] ?></td>
                        <td><?= $supply['name'] ?></td>
                        <td><?= $supply['unit'] ?></td>
                        <td><?= $supply['unit_value'] ?></td>
                        <td>
                            <button type="button" class="select-btn" 
                                    data-id="<?= $supply['id'] ?>" 
                                    data-name="<?= $supply['name'] ?>" 
                                    data-quantity="1" 
                                    data-unit-value="<?= $supply['unit_value'] ?>">
                                +
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>