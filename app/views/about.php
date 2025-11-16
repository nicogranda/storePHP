<section class="ceo-section">
  <div class="ceo-container">
    <div class="ceo-photo">
      <img src="<?php echo 'images/ceo/maria-castillo-borjas.jpg';?>" 
           alt="María Castillo Borjas" 
           title="<?php echo 'María Castillo Borjas';?>">
    </div>
    <div class="ceo-bio">
      <h2>María Castillo Borjas</h2>
      <h4>CEO & Graphic Designer</h4>
      <p>
        María Castillo Borjas is a Venezuelan creative visionary and CEO of Borjas Design. 
        With over a decade of experience in graphic design, she has led projects that blend 
        artistic expression with strategic branding. Her approach merges aesthetics and purpose, 
        transforming visual identities into meaningful stories that connect with audiences worldwide.
      </p>
    </div>
  </div>
</section>

<style>
.ceo-section {
  width: 60%;
  margin: 0 auto;
  padding: 60px 20px;
  box-sizing: border-box;
  display: flex;
  justify-content: center;
}

.ceo-container {
  display: flex;
  flex-direction: row; /* ← fuerza horizontal */
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  width: 100%;
  gap: 40px;
}

.ceo-photo {
  flex: 0 0 40%;
  display: flex;
  justify-content: center;
}

.ceo-photo img {
  width: 100%;
  max-width: 400px;
  border-radius: 10px;
  object-fit: cover;
}

.ceo-bio {
  flex: 0 0 60%;
}

.ceo-bio h2 {
  font-size: 2em;
  margin-bottom: 5px;
}

.ceo-bio h4 {
  color: #777;
  font-weight: 500;
  margin-bottom: 20px;
}

.ceo-bio p {
  line-height: 1.7;
  font-size: 1.1em;
  color: #333;
}

/* Responsive: stack en móvil */
@media (max-width: 768px) {
  .ceo-container {
    flex-direction: column;
    text-align: center;
  }

  .ceo-photo, .ceo-bio {
    flex: 0 0 100%;
  }

  .ceo-photo img {
    max-width: 300px;
  }

  .ceo-bio h2 {
    font-size: 1.6em;
  }
}
</style>
