<?php

require_once "Config/database.php";
require_once "public/modelos/Venta.php";

$database = new Database();
$db = $database->getConnection();

$ventaModel = new Venta($db);
$registros = $ventaModel->listarConDetalle();

$ventas = [];

foreach ($registros as $registro) {
    $id = $registro["venta_id"];

    if (!isset($ventas[$id])) {
        $ventas[$id] = [
            "fecha" => $registro["fecha"],
            "total" => $registro["total"],
            "detalles" => []
        ];
    }

    $ventas[$id]["detalles"][] = [
        "producto" => $registro["producto"],
        "cantidad" => $registro["cantidad"],
        "precio_unitario" => $registro["precio_unitario"],
        "subtotal" => $registro["subtotal"]
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de ventas</title>
    <link rel="stylesheet" href="public/recursos/css/estilos.css">
</head>
<body>

<div class="container">
    <?php include "menu.php"; ?>
    <div class="card">
        <h2>Historial de ventas</h2>

        <?php if (count($ventas) > 0): ?>
            <?php foreach ($ventas as $ventaId => $venta): ?>
                <div class="venta-card">
                    <div class="venta-header" onclick="toggleDetalle('detalle-<?= $ventaId ?>')">
                        <strong>Venta #<?= htmlspecialchars($ventaId) ?></strong>
                        <span><?= htmlspecialchars($venta["fecha"]) ?></span>
                        <span>Total: $<?= number_format($venta["total"], 2) ?></span>
                    </div>

                    <div class="venta-detalle" id="detalle-<?= $ventaId ?>">
                        <table>
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio unitario</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($venta["detalles"] as $detalle): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($detalle["producto"]) ?></td>
                                        <td><?= htmlspecialchars($detalle["cantidad"]) ?></td>
                                        <td>$<?= number_format($detalle["precio_unitario"], 2) ?></td>
                                        <td>$<?= number_format($detalle["subtotal"], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="texto-vacio">No existen ventas registradas todavía.</p>
        <?php endif; ?>

        <br>
        <a href="ventas.php">Registrar nueva venta</a> |
        <a href="productos.php">Productos</a>
    </div>
</div>

<script>
function toggleDetalle(id) {
    const detalle = document.getElementById(id);
    detalle.style.display = detalle.style.display === "block" ? "none" : "block";
}
</script>

</body>
</html>