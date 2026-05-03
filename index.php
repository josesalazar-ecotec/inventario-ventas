<?php

require_once "config/database.php";

$database = new Database();
$conn = $database->getConnection();

echo "<h1>Sistema Inventario + Ventas</h1>";
echo "<p>Conexión a MySQL realizada correctamente.</p>";