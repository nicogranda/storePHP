<!-- Incluye Dropzone -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

<main class="container-form">

<form id="productForm" method="POST" action="" enctype="multipart/form-data">
  
  <!-- IDENTIFICACIÓN DEL PRODUCTO -->
  <fieldset>
    <legend>Identificación del Producto</legend>
    <input type="hidden" name="language" value="EN">
    
    <label for="name">Nombre:</label>
    <input type="text" id="name" name="name" required class="input-field"><br>
    
    <!-- Categoría -->
    <label for="category_id">Categoría:</label>
    <select id="category_id" name="category_id" required class="input-field">
      <option value="">Selecciona una categoría</option>
      <?php foreach($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
      <?php endforeach; ?>
    </select><br>
    
    <!-- Unidad -->
    <label for="unit">Unidad:</label>
    <input type="text" id="unit" name="unit" value="pza" required class="input-field"><br>

    <!-- <label for="sku">SKU:</label>
    <input type="text" id="sku" name="sku" required class="input-field"><br> -->
    
    <label for="description">Descripción:</label>
    <textarea id="description" name="description" class="input-field"></textarea><br>
  </fieldset>

<fieldset>
  <legend>Variantes</legend>
  <div id="variantsContainer" class="variants-flex">

    <div class="variant">
      <!-- SKU de la variante -->
      <!-- <input type="text" name="variants[0][sku]" placeholder="SKU" required> -->

      <!-- Precio -->
      <input type="number" name="variants[0][price]" placeholder="Precio" step="0.01" required>

      <!-- Stock -->
      <input type="number" name="variants[0][stock]" placeholder="Cantidad en inventario" required>

      <!-- Peso -->
      <input type="number" name="variants[0][weight]" placeholder="Peso" step="0.01">

      <!-- Imagen -->
      <!-- <input type="text" name="variants[0][image_url]" placeholder="URL de imagen"> -->

      <!-- Atributos dinámicos -->
      <div class="variant-attributes">
        <div class="attribute">
          <input type="text" name="variants[0][attributes][0][atributo]" placeholder="Atributo (ej: color)" required>
          <input type="text" name="variants[0][attributes][0][atributo_valor]" placeholder="Valor (ej: Rojo)" required>
          <button type="button" class="removeAttribute"><i class="fas fa-trash"></i></button>
        </div>
      </div>

      <!-- Botón eliminar variante -->
      <button type="button" class="removeVariant"><i class="fas fa-trash"></i></button>
      
      <!-- Botón agregar atributo -->
      <button type="button" class="addAttribute">Agregar Atributo</button>

      <!-- Activo -->
      <select name="variants[0][is_active]">
        <option value="1" selected>Activo</option>
        <option value="0">Inactivo</option>
      </select>

    </div>

  </div>

  <!-- Botón agregar variante -->
  <button type="button" id="addVariant" class="add-btn"><i class="fas fa-plus"></i> Agregar Variante</button>
</fieldset>

  <!-- DIMENSIONES -->
<!--<fieldset>-->
<!--  <legend>Dimensiones</legend>-->
<!--  <div class="dimensions-flex">-->
<!--    <input type="number" id="weight" name="weight" step="0.01" placeholder="Peso (kg)" required>-->
<!--    <input type="number" id="length" name="length" step="0.1" placeholder="Largo (cm)" required>-->
<!--    <input type="number" id="width" name="width" step="0.1" placeholder="Ancho (cm)" required>-->
<!--    <input type="number" id="height" name="height" step="0.1" placeholder="Alto (cm)" required>-->
<!--  </div>-->
<!--</fieldset>-->

  <!-- DROPZONE PARA IMÁGENES -->
  <fieldset>
    <legend>Imágenes</legend>
    <div id="dropzone" class="dropzone"></div>
  </fieldset>

  <button type="submit" class="btn-submit">Crear Producto</button>
</form>

</main>

<style>
  .variants-flex {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	justify-content: center;
	align-items: center;
	align-content: space-evenly;
	gap: 10px;
}

  .variant {
    display: flex;
    gap: 10px;
    align-items: center;
  }

  .variant input {
    padding: 5px;
    width: 130px;
    /*min-width: 100px;*/
  }

  .variant button {
    background-color: #e74c3c;
    border: none;
    color: white;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 4px;
    height: 36px;
  }

  .add-btn {
    background-color: #2ecc71;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 4px;
    height: 36px;
  }
</style>

<style>
  .dimensions-flex {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
  }

  .dimensions-flex input {
    padding: 5px;
    width: 120px; /* tamaño uniforme */
  }
</style>

<script>
let variantIndex = 1;

document.getElementById('addVariant').addEventListener('click', () => {
  const container = document.getElementById('variantsContainer');
  const div = document.createElement('div');
  div.classList.add('variant');
  div.innerHTML = `
    <input type="text" name="variants[${variantIndex}][color]" placeholder="Color" required>
    <input type="text" name="variants[${variantIndex}][size]" placeholder="Talla" required>
    <input type="number" name="variants[${variantIndex}][price]" placeholder="Precio" step="0.01" required>
    <input type="number" name="variants[${variantIndex}][stock]" placeholder="Cantidad en inventario" required>
    <button type="button" class="removeVariant"><i class="fas fa-trash"></i></button>
  `;
  // Insertar antes del botón "Agregar" para que este siempre quede al final
  container.insertBefore(div, document.getElementById('addVariant'));
  variantIndex++;
});

document.getElementById('variantsContainer').addEventListener('click', e => {
  if(e.target.closest('.removeVariant')) {
    e.target.closest('.variant').remove();
  }
});
</script>
<script>
document.getElementById('variantsContainer').addEventListener('click', e => {
  if(e.target.classList.contains('removeVariant')) {
    e.target.parentElement.remove();
  }
});

</script>
<script>
Dropzone.autoDiscover = false;

// --- Inicializamos Dropzone como “recopilador”, no como uploader automático ---
const dropzoneElement = document.querySelector("#dropzone");

const myDropzone = new Dropzone(dropzoneElement, {
  url: "#", // no se usa, porque no sube aún
  autoProcessQueue: false, // 🔥 clave: no sube automáticamente
  uploadMultiple: true,
  parallelUploads: 10,
  addRemoveLinks: true,
  maxFilesize: 5, // MB
  acceptedFiles: "image/*",
  dictRemoveFile: "Eliminar",
  init: function() {
    console.log("Dropzone inicializado en modo manual.");
  }
});

// --- Capturamos el submit del formulario ---
const form = document.getElementById("productForm");

form.addEventListener("submit", function(e) {
  e.preventDefault(); // Evitamos el envío normal

  // Creamos un FormData con todos los campos del formulario
  const formData = new FormData(form);

  // Agregamos los archivos recogidos por Dropzone
  myDropzone.files.forEach((file, index) => {
    formData.append(`variants[${index}][image_file]`, file, file.name);
  });

  // --- Enviamos el formulario completo vía fetch ---
  fetch(form.action || "index.php?page=products&action=create", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    console.log("Respuesta del servidor:", data);
    // Puedes redirigir, mostrar mensaje, etc.
    window.location.href = "./index.php?page=products&action=index";
  })
  .catch(err => {
    console.error("Error al enviar:", err);
  });
});
</script>