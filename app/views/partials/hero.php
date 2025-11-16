<section class="hero">
  <div class="hero-image">
    <img src="images/heros/borjas_design.png" alt="Boras Design">
  </div>
  <!-- <div class="hero-content">
    <div class="hero-row top">
      <img src="images/ceo/maria-castillo-borjas.png" alt="Hero Image Top">
    </div>
    <div class="hero-row bottom">
      <img src="images/ceo/maria-castillo-borjas.png" alt="Hero Image Bottom">
    </div>
  </div> -->
</section>

<style>
.hero {
  display: flex;
  align-items: stretch;
  height: 60vh;
  overflow: hidden;
  margin: 0 auto;
  width: 100%;
  padding: 20px 0;
  gap: 10px;
}

.hero-image {
  flex: 1;
}

.hero-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  
}

.hero-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.hero-row {
  flex: 1; /* Cada fila ocupa la mitad */
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;

}

.hero-row img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* 📱 Responsive */
@media (max-width: 768px) {
  .hero {
    flex-direction: column;
  }

  .hero-image,
  .hero-content {
    flex: none;
    height: 50vh;
  }

  .hero-row {
    flex: 1;
  }
}
</style>
