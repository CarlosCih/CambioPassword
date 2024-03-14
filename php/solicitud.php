<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="form">

        <div id="login">
            <h1>Ingresar</h1>
            <p>Introduce tu usuario, número de teléfono o tu dirección de correo electrónico</p>
            <form action="recovery.php" method="post">

                <div class="field-wrap">
                    <label>
                        Usuario, teléfono o correo<span class="req"> *</span>
                    </label>
                    <input type="text" required autocomplete="off" name="usuario" />
                </div>
                <button class="button button-block" name="recuperar_contraseña">Enviar</button>

            </form>

        </div>

    </div> <!-- /form -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="../js/index.js"></script>
</body>

</html>