<?php
// Recibimos los valores del formulario
$mailerTo = isset($_POST['mailerTo']) ? $_POST['mailerTo'] : '';
$mailerFrom = isset($_POST['senderEmail']) ? $_POST['senderEmail'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Email Enviado</title>
    <?php include '../../config.php'; ?>
    <?php include '../../assets.php'; ?>
    <link rel="stylesheet" href="css/email.css" type="text/css" charset="utf-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 90%;
            box-sizing: border-box;
            text-align: center;
        }

        .item {
            margin-bottom: 20px;
        }

        .return-link {
            display: inline-block;
            background-color: var(--color-primary);
            color: #ffffff;
            padding: 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .return-link:hover {
            background-color: #999999; /* Simula un color plateado en hover */
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="item">
            <h2>Email Enviado</h2>
        </div>

        <div class="item">
            <p>Hemos enviado un email a <strong><?php echo htmlspecialchars($mailerTo); ?></strong></p>
        </div>

        <div class="item">
            <p>Desde el correo: <strong><?php echo htmlspecialchars($mailerFrom); ?></strong></p>
        </div>

        <div class="item">
            <a href="index.php" class="return-link">Volver al formulario</a>
        </div>
    </div>
</body>
</html>