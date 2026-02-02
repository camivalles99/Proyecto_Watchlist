<?php
// Configuración de conexión
$conn = new mysqli("localhost", "root", "", "proyecto_watchlist");
if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);
// Esto ayuda con las tildes y ñ
$conn->set_charset("utf8");
?>