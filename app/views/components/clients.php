<head>
    <style>
   
        .carousel-container {
            position: relative;
            width: 90%;
            margin: 0 auto;
            overflow: hidden;
            padding: 10px;
        }
        .carousel-wrapper {
            display: flex;
            transition: transform 0.5s ease-in-out;
            will-change: transform;
        }
        .carousel-item {
            flex: 0 0 auto;
            width: 150px; /* Ancho de cada imagen */
            margin: 0 5px; /* Espaciado entre imágenes */
            position: relative;
            cursor: pointer;
        }
        .carousel-item img {
            width: 100%;
            height: auto;
            display: block;
            /* filter: grayscale(100%); */
            transition: filter 0.5s ease-in-out;
        }
        .carousel-item:hover img, .carousel-item:focus img {
            filter: grayscale(0%);
        }
        .carousel-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }
        .carousel-button {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 18px;
        }
        .carousel-button:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
    </style>
</head>


<div class="carousel-container">
    <div class="carousel-wrapper">
        <?php 
            $dir = "images/clients/";
            $direc = @opendir($dir) or die("Permiso denegado");

            $files = [];
            while ($file = readdir($direc)) {
                if ($file != "." && $file != ".." && $file != ".DS_Store") {
                    $files[] = $file;
                }
            }
            closedir($direc);

            $totalFiles = count($files);

            // Clonar las imágenes para el efecto de carrusel infinito
            for ($i = 0; $i < $totalFiles; $i++) {
                echo "<div class='carousel-item'><img src='".$dir.$files[$i]."' alt='".$files[$i]."' /></div>";
            }
            for ($i = 0; $i < $totalFiles; $i++) {
                echo "<div class='carousel-item'><img src='".$dir.$files[$i]."' alt='".$files[$i]."' /></div>";
            }
        ?>
    </div>
    <div class="carousel-controls">
        <button class="carousel-button" id="prev">&lt;</button>
        <button class="carousel-button" id="next">&gt;</button>
    </div>
</div>

<script>
    const wrapper = document.querySelector('.carousel-wrapper');
    const items = document.querySelectorAll('.carousel-item');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');

    let index = 0;
    const totalItems = items.length / 2; // Porque hemos duplicado las imágenes
    const itemWidth = 150 + 10; // Ancho de cada imagen más el margen

    function updateCarousel() {
        const offset = -index * itemWidth;
        wrapper.style.transform = `translateX(${offset}px)`;

        // Si llegamos al final, ir a la primera posición sin transición
        if (index >= totalItems) {
            setTimeout(() => {
                wrapper.style.transition = 'none';
                wrapper.style.transform = 'translateX(0px)';
                index = 0;
                setTimeout(() => {
                    wrapper.style.transition = 'transform 0.5s ease-in-out';
                }, 20);
            }, 500);
        }
        updateActiveItem();
    }

    function nextSlide() {
        index++;
        if (index >= totalItems * 2) {
            index = totalItems; // Reinicia al punto inicial (la segunda copia)
        }
        updateCarousel();
    }

    function prevSlide() {
        index--;
        if (index < 0) {
            index = totalItems - 1; // Reinicia al punto final (la última imagen de la primera copia)
        }
        updateCarousel();
    }

    function updateActiveItem() {
        items.forEach((item, i) => {
            item.classList.toggle('active', i === index);
        });
    }

    prevButton.addEventListener('click', prevSlide);
    nextButton.addEventListener('click', nextSlide);

    // Auto-slide every 3 seconds
    setInterval(nextSlide, 3000);

    // Inicializa el carrusel
    updateCarousel();
</script>


