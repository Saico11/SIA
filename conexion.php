<?php
$host = 'localhost';  // Cambiar si el servidor es diferente
$port = '5432';       // Puerto por defecto de PostgreSQL
$dbname = 'inv';  // Reemplazar con el nombre de tu base de datos
$user = 'postgres';    // Usuario de la base de datos
$password = '080100';  // Contraseña del usuario

try {
    // Establecer la conexión
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    // Establecer el modo de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>

