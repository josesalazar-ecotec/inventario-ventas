<?php

require_once "Config/database.php";
require_once "public/modelos/Producto.php";

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: productos.php");
    exit;
}

$id = (int) $_GET["id"];

$database = new Database();
$db = $database->getConnection();

$producto = new Producto($db);
$producto->eliminar($id);

header("Location: productos.php");
exit;