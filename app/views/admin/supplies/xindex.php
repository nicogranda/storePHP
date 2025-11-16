<main>

<table class="container-form">
  
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Unidad</th>
            <th>Valor de Unidad</th>
        </tr>

        <?php foreach ($supplies as $supply) : ?>
            <tr>
                <td><?= $supply['id'] ?></td>
                <td><?= $supply['name'] ?></td>
                <td><?= $supply['unit'] ?></td>
                <td><?= $supply['unit_value'] ?></td>
            </tr>
        <?php endforeach; ?>

</table>
</main>
