<!DOCTYPE html>
<html lang="es">

<body>
<header>
<div class="burger-menu" onclick="toggleMenu()">
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
</div>

<div class="menu" id="menu">
    <!-- <a href="/">Home</a> -->
    <!-- <a href="#" id="portfolio-link">Portfolio</a> -->
    <!-- <div id="portfolio-menu"> -->
    <a href="index.php?page=portfolio&action=show&category=new-arrivals">New Arrivals</a>
    <a href="index.php?page=portfolio&action=show&category=Necklaces">Necklaces</a>
    <a href="index.php?page=portfolio&action=show&category=Earrings">Earrings</a>
    <a href="index.php?page=portfolio&action=show&category=Bracelets">Bracelets</a>
    <a href="index.php?page=portfolio&action=show&category=Pendents">Pendents</a>
    <!-- </div> -->
    <a href="/index.php?page=contact&action=mail">Contact Us</a>
</div>
<a href="index.php?page=portfolio">
    <img src="images/borjas-design.png" style="width: 200px;"/>
</a>

<div id="quote" style="margin-right:50px;">
    <i class="fa-solid fa-magnifying-glass"></i>
    <?php include "app/views/components/MiniCart.php"; ?>
</div>


</header>
<script>
function toggleMenu() {
    document.querySelector('.burger-menu').classList.toggle('active');
    document.getElementById('menu').classList.toggle('active');
}

document.addEventListener('DOMContentLoaded', function() {
    const portfolioLink = document.getElementById('portfolio-link');
    const portfolioMenu = document.getElementById('portfolio-menu');

    portfolioLink.addEventListener('click', function(event) {
        event.preventDefault(); // Previene el comportamiento por defecto del enlace
        if (portfolioMenu.style.display === 'none' || portfolioMenu.style.display === '') {
            portfolioMenu.style.display = 'block';
        } else {
            portfolioMenu.style.display = 'none';
        }
    });
});
</script>

