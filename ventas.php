<?php

require_once "Config/database.php";
require_once "public/modelos/Producto.php";
require_once "public/modelos/Venta.php";

$database = new Database();
$db = $database->getConnection();

$productoModel = new Producto($db);
$ventaModel = new Venta($db);

$productos = $productoModel->listar();

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $productosSeleccionados = $_POST["producto_id"] ?? [];
    $cantidades = $_POST["cantidad"] ?? [];

    if (!is_array($productosSeleccionados)) {
        $productosSeleccionados = [$productosSeleccionados];
    }

    if (!is_array($cantidades)) {
        $cantidades = [$cantidades];
    }

    $items = [];

    for ($i = 0; $i < count($productosSeleccionados); $i++) {
        $productoId = (int) $productosSeleccionados[$i];
        $cantidad = (int) $cantidades[$i];

        if ($productoId > 0 && $cantidad > 0) {
            $items[] = [
                "producto_id" => $productoId,
                "cantidad" => $cantidad
            ];
        }
    }

    if (count($items) === 0) {
        $error = "Debe agregar al menos un producto.";
    } else {
        if ($ventaModel->registrarMultiple($items)) {
            $mensaje = "Venta registrada correctamente.";
            $productos = $productoModel->listar();
        } else {
            $error = "Error en la venta. Verifique stock.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas</title>
    <link rel="stylesheet" href="public/recursos/css/estilos.css">
</head>
<body>

<div class="container">
    <?php include "menu.php"; ?>
    <div class="card">
        <h2>Nueva venta</h2>

        <?php if ($mensaje): ?>
            <div class="alert success"><?= $mensaje ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" id="formVenta">
            <div class="fila-venta" style="font-weight:bold;">
                <span>Producto</span>
                <span>Cantidad</span>
                <span>Precio</span>
                <span>Subtotal</span>
            </div>
            <div id="items"></div>

            <button type="button" onclick="agregarItem()">+ Agregar producto</button>

            <br><br>

            <h3>Total: $<span id="total">0.00</span></h3>

            <button type="submit">Registrar venta</button>
        </form>

        <br>
        <a href="productos.php">Volver</a>
    </div>
</div>

<script>
const productos = <?= json_encode($productos) ?>;
</script>
 
<script src="public/recursos/js/ventas.js"></script>

</body>
</html>