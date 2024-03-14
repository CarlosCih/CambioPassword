<?php
include('conexion.php');

$conn = Conectar();

// Función para cifrar la contraseña usando sha1
function cifrarContrasena($contrasena)
{
    return sha1($contrasena);
}

// Función para realizar el inicio de sesión
function login($user, $pass, $conn)
{
    $token = "NULL";
    $query = 'SELECT * FROM Usuarios WHERE Usuario = ? LIMIT 1';
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result && $result->num_rows > 0){
        $fila = $result->fetch_assoc();
        if (sha1($pass) === $fila['contrasena']) { // Comparar contraseñas usando sha1
            $token = sha1($fila['usuario'].$fila['contrasena'].$fila['fecha_sesion']);
            $query2 = 'UPDATE Usuarios SET token = ? WHERE id = ?';
            $stmt2 = $conn->prepare($query2);
            $stmt2->bind_param("si", $token, $fila['id']);
            $stmt2->execute();
            // Verificar si la actualización fue exitosa
            if ($stmt2->affected_rows > 0) {
                // La actualización fue exitosa
                $stmt2->close();
                return $token;
            } else {
                // La actualización falló
                $stmt2->close();
                return "NULL";
            }
        }
    }
    $stmt->close();
    return $token;
}

// Función para validar el formulario
function validar($conn)
{
    // Verificar si se enviaron los datos del formulario
    if (isset($_POST['registro']))
    {
        // Verificar si se enviaron todos los datos necesarios para el registro
        if (isset($_POST['nombres']) && isset($_POST['apellidos']) && isset($_POST['usuario']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['telefono']))
        {
            // Obtener los datos del formulario
            $nombres = $_POST['nombres'];
            $apellidos = $_POST['apellidos'];
            $usuario = $_POST['usuario'];
            $correo = $_POST['email'];
            $contrasena = $_POST['password'];
            $telefono = $_POST['telefono'];

            // Registrar el usuario en la base de datos
            $contrasenaCifrada = cifrarContrasena($contrasena);
            $sql = "INSERT INTO usuarios (nombres, apellidos, usuario, correo, contrasena, telefono) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $nombres, $apellidos, $usuario, $correo, $contrasenaCifrada, $telefono);
            if ($stmt->execute())
            {
                echo "<script>alert('Usuario registrado correctamente.');</script>";
                echo "<script>window.location='../index.html'</script>";
            }
            else
            {
                echo "<script>alert('Error al registrar usuario: " . $stmt->error . "');</script>";
                echo "<script>window.location='../index.html'</script>";
            }
            $stmt->close();
        }
        else
        {
            echo "<script>alert('Error en el formulario.');</script>";
            echo "Error en el formulario";
        }
    }
    elseif (isset($_POST['login']))
    {
        // Procesar el formulario de inicio de sesión
        $user = $_POST['usuario'];
        $pass = $_POST['contrasena'];

        // Iniciar sesión
        $token = login($user, $pass, $conn);

        // Verificar si se obtuvo un token válido
        if($token != "NULL"){
            session_start();
            $_SESSION['token'] = $token;
            $_SESSION['usuario'] = $user;
            header("Location: dashboard.php");
            exit();
        }else{
            header("Location: ../index.html?error=1");
            exit();
        }
    }
}

function cambioPassword($conn){
    if (isset($_POST['cambio_contraseña'])) {
        // Obtener los datos del formulario
        $password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $token = $_GET['token']; // Obtener el token de la URL
    
        // Verificar la coincidencia de las contraseñas
        if ($password !== $confirm_password) {
            echo "<script>alert('Las contraseñas no coinciden. Por favor, inténtelo de nuevo.');</script>";
            echo "<script>window.location.reload();</script>";
            exit(); // Detener la ejecución del script
        }
    
        // Verificar si el token es válido y está dentro del tiempo límite
        $sql = "SELECT usuario_id FROM reset_password_tokens WHERE token = ? AND expiry_at > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 1) {
            // El token es válido, actualizar la contraseña en la base de datos
            $row = $result->fetch_assoc();
            $usuario_id = $row['usuario_id'];
    
            // Cifrar la nueva contraseña (deberías usar el mismo método que al registrar el usuario)
            $contrasenaCifrada = md5($password);
    
            // Actualizar la contraseña del usuario
            $sql_update = "UPDATE usuarios SET contrasena = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $contrasenaCifrada, $usuario_id);
            $stmt_update->execute();
    
    
            // Mostrar mensaje de éxito
            echo "<script>alert('¡La contraseña se cambió correctamente!');</script>";
            echo "<script>window.location='../index.html'</script>";
        } else {
            // El token no es válido o ha expirado
            echo "<script>alert('El enlace para restablecer la contraseña no es válido o ha expirado. Intente nuevamente.');</script>";
            // echo "<script>window.location='../index.html'</script>";
        }
    
        $stmt->close();
        $stmt_update->close();
    }
}

cambioPassword($conn);

// Validar el formulario y manejar el inicio de sesión
validar($conn);

// Cerrar la conexión a la base de datos
$conn->close();