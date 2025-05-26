<?php
$host = "localhost";
$user = "root";
$password = "080100";
$database = "Inventario";
$port = 3306;

$conexion = new mysqli($host, $user, $password, $database, $port);

if ($conexion->connect_error) {
    die("Error al conectarse con la DB: " . $conexion->connect_error);
}
?>
