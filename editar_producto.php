<?php

require_once "Config/database.php";
require_once "public/modelos/Producto.php";

if (!isset($_GET["id"])) {
    header("Location: productos.php");
    exit;
}

$id = (int) $_GET["id"];

$database = new Database();
$db = $database->getConnection();

$productoModel = new Producto($db);
$producto = $productoModel->obtenerPorId($id);

if (!$producto) {
    header("Location: productos.php");
    exit;
}

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = (float) ($_POST["precio"] ?? 0);
    $stock = (int) ($_POST["stock"] ?? 0);

    if ($nombre === "") {
        $error = "El nombre es obligatorio.";
    } elseif ($precio <= 0) {
        $error = "El precio debe ser mayor a 0.";
    } elseif ($stock < 0) {
        $error = "El stock no puede ser negativo.";
    } else {
        if ($productoModel->actualizar($id, $nombre, $descripcion, $precio, $stock)) {
            $mensaje = "Producto actualizado correctamente.";
            $producto = $productoModel->obtenerPorId($id);
        } else {
            $error = "Error al actualizar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>
    <link rel="stylesheet" href="public/recursos/css/estilos.css">
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Editar producto</h2>

        <?php if ($mensaje): ?>
            <div class="alert success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="form">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($producto["nombre"]) ?>">
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion"><?= htmlspecialchars($producto["descripcion"]) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Precio</label>
                    <input type="number" step="0.01" name="precio" value="<?= $producto["precio"] ?>">
                </div>

                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" value="<?= $producto["stock"] ?>">
                </div>
            </div>

            <button type="submit">Actualizar producto</button>
        </form>

        <br>
        <a href="productos.php">← Volver</a>
    </div>
</div>

</body>
</html>