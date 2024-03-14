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
            <h1>Cambiar contraseña</h1>
            <form action="validacion.php?token=<?php echo $_GET['token']; ?>" method="post">

                <div class="field-wrap">
                    <label>
                        Contraseña<span class="req">*</span>
                    </label>
                    <input type="password" required autocomplete="off" name="new_password" />
                </div>
                <div class="field-wrap">
                    <label>
                        Confirmar contraseña<span class="req">*</span>
                    </label>
                    <input type="password" required autocomplete="off" name="confirm_password" />
                </div>
                <button class="button button-block" name="cambio_contraseña">Enviar</button>

            </form>

        </div>

    </div> <!-- /form -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="../js/index.js"></script>
</body>

</html>