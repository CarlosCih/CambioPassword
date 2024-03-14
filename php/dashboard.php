<?php
session_start();
// Verificar si el usuarios a ha iniciado sesión
	if(!isset($_SESSION['token'])){
		// Si el usuario no ha iniciado sesión, redirigirlo a la pagina de login
		header('Location: ../index.html');
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="form">
        <div id="login">
            <h1>Bienvenid@</h1>
            <h1><?php echo $_SESSION['usuario']; ?></h2>

            <form action="salir.php" method="post">
                <button class="button button-block" name="salir">Salir</button>
            </form>

        </div>
    </div>
</body>

</html>