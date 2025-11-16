<!-- Dropzone -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

<main class="container-form">

<form id="categoryForm" method="POST" action="" enctype="multipart/form-data">

  <!-- INFORMACIÓN DE LA CATEGORÍA -->
  <fieldset>
    <legend>Category Information</legend>
    <input type="hidden" name="language" value="EN">

    <label for="name">Category Name:</label>
    <input type="text" id="name" name="name" required class="input-field"><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description" class="input-field" rows="4" placeholder="Write a short description..."></textarea><br>
  </fieldset>

  <!-- DROPZONE PARA IMÁGENES -->
  <fieldset>
    <legend>Image</legend>
    <p>You can optionally upload an image for this category.</p>
    <div id="dropzone" class="dropzone"></div>
  </fieldset>

  <button type="submit" class="btn-submit">Create Category</button>
</form>

</main>

<style>
  .container-form {
    max-width: 700px;
    margin: 30px auto;
    padding: 25px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    font-family: "Segoe UI", sans-serif;
  }

  fieldset {
    border: 1px solid #ccc;
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 6px;
  }

  legend {
    font-weight: bold;
    color: #333;
  }

  label {
    display: block;
    margin-top: 10px;
    font-weight: 600;
  }

  .input-field {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #bbb;
    border-radius: 4px;
  }

  textarea.input-field {
    resize: vertical;
  }

  .btn-submit {
    background-color: #2ecc71;
    color: white;
    border: none;
    padding: 10px 18px;
    cursor: pointer;
    border-radius: 4px;
    font-size: 15px;
    transition: background-color 0.2s;
  }

  .btn-submit:hover {
    background-color: #27ae60;
  }

  .dropzone {
    border: 2px dashed #bbb;
    background: #fafafa;
    padding: 20px;
    border-radius: 6px;
  }
</style>

<script>
Dropzone.autoDiscover = false;

// --- Inicializamos Dropzone en modo manual ---
const dropzoneElement = document.querySelector("#dropzone");

const myDropzone = new Dropzone(dropzoneElement, {
  url: "#", // no se usa directamente
  autoProcessQueue: false,
  uploadMultiple: false,
  addRemoveLinks: true,
  maxFilesize: 5,
  acceptedFiles: "image/*",
  dictRemoveFile: "Remove",
  dictDefaultMessage: "Drag an image here or click to select one",
  init: function() {
    console.log("Dropzone initialized for category image.");
  }
});

// --- Manejar envío del formulario ---
// const form = document.getElementById("categoryForm");

// form.addEventListener("submit", function(e) {
//   e.preventDefault();

//   const formData = new FormData(form);

//   // Agregar archivo si hay imagen subida
//   if (myDropzone.files.length > 0) {
//     formData.append("image_file", myDropzone.files[0], myDropzone.files[0].name);
//   }
//   // /admin/index.php?page=categories&action=create
//   fetch(form.action || "index.php?page=categories&action=create", {
//   method: "POST",
//   body: formData
// })
// .then(response => response.text())
// .then(data => {
//   console.log("Server response:", data);

//   // 🔍 Depura primero antes de redirigir
//   if (data.includes("Error") || data.includes("Notice") || data.includes("Warning")) {
//     console.error("Server returned an error:", data);
//     alert("There was a problem creating the category. Check console for details.");
//     return;
//   }

//   // ✅ Redirige solo si todo fue bien
//   window.location.href = "index.php?page=categories&action=index";
// })
// .catch(err => {
//   console.error("Error submitting form:", err);
//   alert("Something went wrong. Check console for details.");
// });

// });
</script>
