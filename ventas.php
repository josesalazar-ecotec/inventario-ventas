<?php

require_once "Config/database.php";
require_once "public/modelos/Producto.php";
require_once "public/modelos/Venta.php";

$mensaje = "";
$error = "";

$database = new Database();
$db = $database->getConnection();

$productoModel = new Producto($db);
$ventaModel = new Venta($db);

$productos = $productoModel->listar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productoId = (int) ($_POST["producto_id"] ?? 0);
    $cantidad = (int) ($_POST["cantidad"] ?? 0);

    if ($productoId <= 0) {
        $error = "Debe seleccionar un producto.";
    } elseif ($cantidad <= 0) {
        $error = "La cantidad debe ser mayor a 0.";
    } else {
        if ($ventaModel->registrar($productoId, $cantidad)) {
            $mensaje = "Venta registrada correctamente.";
            $productos = $productoModel->listar();
        } else {
            $error = "No se pudo registrar la venta. Revise el stock disponible.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas | Inventario + Ventas</title>
    <link rel="stylesheet" href="public/recursos/css/estilos.css">
</head>
<body>

<div class="container">
    <header class="header">
        <h1>Inventario + Ventas</h1>
        <p>Registro de ventas</p>
    </header>

    <main class="card">
        <h2>Nueva venta</h2>

        <?php if ($mensaje): ?>
            <div class="alert success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" id="formVenta" class="form">
            <div class="form-group">
                <label for="producto_id">Producto</label>
                <select id="producto_id" name="producto_id">
                    <option value="">Seleccione un producto</option>

                    <?php foreach ($productos as $producto): ?>
                        <?php if ((int) $producto["stock"] > 0): ?>
                            <option value="<?= htmlspecialchars($producto["id"]) ?>">
                                <?= htmlspecialchars($producto["nombre"]) ?>
                                - Stock: <?= htmlspecialchars($producto["stock"]) ?>
                                - $<?= number_format($producto["precio"], 2) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="cantidad">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" min="1" placeholder="Ej: 1">
            </div>

            <button type="submit">Registrar venta</button>
        </form>

        <br>
        <a href="productos.php">Ir a productos</a>
    </main>
</div>

<script src="public/recursos/js/ventas.js"></script>
</body>
</html>