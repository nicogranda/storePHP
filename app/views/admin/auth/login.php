<main class="login-wrapper">
  <form method="POST" action="index.php?page=admin&action=auth" class="login-box">
    <img src="images/borjas-design.png" alt="BorjasDesign" class="login-logo">

    <?php if (isset($_SESSION['error'])): ?>
      <p class="login-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <input type="text" name="username" placeholder="User" class="login-input">
    <input type="password" name="password" placeholder="Password" class="login-input">
    <button type="submit" class="login-button">Ingresar</button>

    <div class="login-links">
      <a href="forgot-password.php">¿Olvidaste tu contraseña?</a>
      <a href="register.php">Regístrate</a>
    </div>

    <div class="login-google">
      <?php include __DIR__ . '/../../components/GoogleLogin.php'; ?>
    </div>
  </form>
</main>

<style>
/* ==============================
   VARIABLES DE COLOR
   (puedes moverlas a :root global)
=================================*/
/* :root {
  --color-primary: #6c63ff;
  --color-primary-hover: #5a52e0;
  --color-background: white;
  --color-text: #1f1c2c;
  --color-error: #e63946;
  --color-error-bg: #ffe6e9;
} */
body {
  background: white;
}

/* === LOGIN FORM STYLES === */
.login-wrapper {
  min-height: 65vh;
  display: flex;
  justify-content: center;
  align-items: center;
  /* background: linear-gradient(135deg, var(--color-text), #928dab); */
  padding: 2rem;
}

.login-box {
  background: var(--color-background);
  padding: 1.5rem 2rem;
  border-radius: 10px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 350px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.8rem;
  height: 60vh;
}

.login-logo {
  width: 180px;
  margin-bottom: 0.5rem;
}

.login-input {
  width: 100%;
  padding: 0.7rem 0.9rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 0.95rem;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.login-input:focus {
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
  outline: none;
}

.login-button {
  width: 100%;
  background: var(--color-primary);
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 0.8rem;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.1s ease-in-out;
}

.login-button:hover {
  background: var(--color-primary-hover);
}

.login-button:active {
  transform: scale(0.98);
}

.login-error {
  color: var(--color-error);
  background: var(--color-error-bg);
  padding: 0.5rem 0.8rem;
  border-radius: 6px;
  width: 100%;
  text-align: center;
  font-size: 0.85rem;
}

.login-links {
  display: flex;
  justify-content: space-between;
  width: 100%;
  font-size: 0.85rem;
}

.login-links a {
  color: var(--color-primary);
  text-decoration: none;
  transition: color 0.3s ease;
}

.login-links a:hover {
  color: var(--color-primary-hover);
}

.login-google {
  width: 100%;
  text-align: center;
}
</style>
