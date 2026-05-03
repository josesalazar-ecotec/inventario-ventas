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
            $nombre = "";
            $descripcion = "";
            $precio = "";
            $stock = "";
        } else {
            $error = "No se pudo registrar el producto.";
        }
    }
}
$database = new Database();
$db = $database->getConnection();

$producto = new Producto($db);
$productos = $producto->listar();
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
                <input type="text" name="nombre" value="<?= htmlspecialchars($nombre ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion"><?= htmlspecialchars($descripcion ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" name="precio" value="<?= htmlspecialchars($precio ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" name="stock" value="<?= htmlspecialchars($stock ?? '') ?>">
                </div>
            </div>

            <button type="submit">Guardar producto</button>
        </form>
        <section class="tabla-contenedor">
    <h2>Productos registrados</h2>

    <?php if (count($productos) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item["id"]) ?></td>
                        <td><?= htmlspecialchars($item["nombre"]) ?></td>
                        <td><?= htmlspecialchars($item["descripcion"]) ?></td>
                        <td>$<?= number_format($item["precio"], 2) ?></td>
                        <td><?= htmlspecialchars($item["stock"]) ?></td>
                        <td><?= htmlspecialchars($item["creado_en"]) ?></td>
                        <td class="acciones">
                            <a class="btn-editar" href="editar_producto.php?id=<?= htmlspecialchars($item["id"]) ?>">Editar</a>

                            <a 
                            class="btn-eliminar" 
                            href="eliminar_producto.php?id=<?= htmlspecialchars($item["id"]) ?>"
                            onclick="return confirm('¿Seguro que deseas eliminar este producto?');"
                            >
                            Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="texto-vacio">No hay productos registrados todavía.</p>
    <?php endif; ?>
</section>
    </main>
</div>

<script src="public/recursos/js/productos.js"></script>
<script>
setTimeout(() => {
    const alertas = document.querySelectorAll('.alert');
    alertas.forEach(a => a.style.display = 'none');
}, 3000);
</script>
</body>
</html>