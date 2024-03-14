<?php
include('conexion.php');
$conn = Conectar();
// Función para generar un token único
function generarToken()
{
    return bin2hex(random_bytes(32)); // Genera un token de 32 bytes en hexadecimal
}

function insertarToken($usuario_id, $token, $conn)
{
    // Calcular la fecha de expiración (por ejemplo, 1 día desde ahora)
    $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+1 day'));
    $sql = "INSERT INTO reset_password_tokens (usuario_id, token, expiry_at) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $usuario_id, $token, $fecha_expiracion);
    $stmt->execute();
    $stmt->close();
}

function solicitarRestablecimiento($usuario, $conn)
{
    $sql = "SELECT id FROM usuarios WHERE usuario = ? OR correo = ? OR telefono = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $usuario, $usuario, $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $usuario_id = $row['id'];
        
        $token = generarToken();
        
        insertarToken($usuario_id, $token, $conn);
        
        header("Location: reset_password_form.php?token=" . urlencode($token));
        exit();
    } else {
        echo "No se encontró ningún usuario con esa dirección de correo electrónico.";
    }
}

// Verificar si se envió el formulario de solicitud de restablecimiento de contraseña
if (isset($_POST['recuperar_contraseña'])) {
    // Obtener el correo electrónico proporcionado por el usuario
    $usuario = $_POST['usuario'];
    
    // Llamar a la función para manejar la solicitud de restablecimiento de contraseña
    solicitarRestablecimiento($usuario, $conn);
}
?>
