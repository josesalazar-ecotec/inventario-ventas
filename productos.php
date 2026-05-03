<?php

require_once "Config/database.php";
require_once "public/modelos/Producto.php";

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = (float) ($_POST["precio"] ?? 0);
    $stock = (int) ($_POST["stock"] ?? 0);

    if ($nombre === "") {
        $error = "El nombre del producto es obligatorio.";
    } elseif ($precio <= 0) {
        $error = "El precio debe ser mayor a 0.";
    } elseif ($stock < 0) {
        $error = "El stock no puede ser negativo.";
    } else {
        $database = new Database();
        $db = $database->getConnection();

        $producto = new Producto($db);

        if ($producto->crear($nombre, $descripcion, $precio, $stock)) {
            $mensaje = "Producto registrado correctamente.";
        } else {
            $error = "No se pudo registrar el producto.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos | Inventario + Ventas</title>
    <link rel="stylesheet" href="public/recursos/css/estilos.css">

</head>
<body>

<div class="container">
    <header class="header">
        <h1>Inventario + Ventas</h1>
        <p>Registro de productos</p>
    </header>

    <main class="card">
        <h2>Nuevo producto</h2>

        <?php if ($mensaje): ?>
            <div class="alert success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" id="formProducto" class="form">
            <div class="form-group">
                <label for="nombre">Nombre del producto</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Laptop Lenovo">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" placeholder="Descripción opcional"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" id="precio" name="precio" step="0.01" min="0" placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" min="0" placeholder="0">
                </div>
            </div>

            <button type="submit">Guardar producto</button>
        </form>
    </main>
</div>

<script src="public/recursos/js/productos.js"></script>
</body>
</html>