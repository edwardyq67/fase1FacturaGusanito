<?php
$servername = "127.0.0.1";
$username = "root";
$password = ""; // tu contraseña aquí si tienes una
$dbname = "factura";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Configurar el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>