<?php
function Conectar()
{
    $servidor = "localhost";
    $usaurio = "root";
    $password  = "";
    $base_datos = "dlogin";

    $conn = new mysqli($servidor, $usaurio, $password, $base_datos);

    if ($conn->connect_error) {
        die("Conexion fallida - ERROR conexion" . $conn->connect_error);
    }

    return $conn;
}

?>