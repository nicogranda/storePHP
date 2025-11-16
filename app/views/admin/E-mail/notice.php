<head>
    <meta charset="UTF-8">
    <title>Email Enviado</title>

    <link rel="stylesheet" href="css/email.css" type="text/css" charset="utf-8" />
    <style>
      
        .form-container {
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 600px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            height: 35vw;
        }

        .item-sent {
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
<div style='height: 35vw;'>
    <div class="form-container">
        <div class="item-sent">
            <h2>Email Enviado</h2>
        </div>

        <div class="item-sent">
            <p>Hemos enviado un email a <strong><?php echo htmlspecialchars($mailerTo); ?></strong></p>
        </div>

        <div class="item-sent">
            <p>Desde el correo: <strong><?php echo htmlspecialchars($mailerFrom); ?></strong></p>
        </div>

        <div class="item-sent">
            <a href="index.php" class="return-link">Volver al formulario</a>
        </div>
    </div>
</div>