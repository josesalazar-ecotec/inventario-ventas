<?php

class Venta
{
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function registrarMultiple(array $items): bool
{
    $this->conn->beginTransaction();

    try {
        $totalGeneral = 0;

        // Validar stock y calcular total
        foreach ($items as $item) {
            $sql = "SELECT precio, stock FROM productos WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $item["producto_id"], PDO::PARAM_INT);
            $stmt->execute();

            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$producto || $producto["stock"] < $item["cantidad"]) {
                $this->conn->rollBack();
                return false;
            }

            $subtotal = $producto["precio"] * $item["cantidad"];
            $totalGeneral += $subtotal;
        }

        // Crear venta
        $sqlVenta = "INSERT INTO ventas (total) VALUES (:total)";
        $stmtVenta = $this->conn->prepare($sqlVenta);
        $stmtVenta->bindParam(":total", $totalGeneral);
        $stmtVenta->execute();

        $ventaId = (int) $this->conn->lastInsertId();

        // Insertar detalles
        foreach ($items as $item) {

            $sql = "SELECT precio FROM productos WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $item["producto_id"], PDO::PARAM_INT);
            $stmt->execute();

            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            $subtotal = $producto["precio"] * $item["cantidad"];

            // Insert detalle
            $sqlDetalle = "INSERT INTO venta_detalles 
            (venta_id, producto_id, cantidad, precio_unitario, subtotal)
            VALUES (:venta_id, :producto_id, :cantidad, :precio, :subtotal)";

            $stmtDetalle = $this->conn->prepare($sqlDetalle);
            $stmtDetalle->bindParam(":venta_id", $ventaId);
            $stmtDetalle->bindParam(":producto_id", $item["producto_id"]);
            $stmtDetalle->bindParam(":cantidad", $item["cantidad"]);
            $stmtDetalle->bindParam(":precio", $producto["precio"]);
            $stmtDetalle->bindParam(":subtotal", $subtotal);
            $stmtDetalle->execute();

            // Actualizar stock
            $sqlStock = "UPDATE productos 
                         SET stock = stock - :cantidad 
                         WHERE id = :id";

            $stmtStock = $this->conn->prepare($sqlStock);
            $stmtStock->bindParam(":cantidad", $item["cantidad"]);
            $stmtStock->bindParam(":id", $item["producto_id"]);
            $stmtStock->execute();
        }

        $this->conn->commit();
        return true;

    } catch (Exception $e) {
        $this->conn->rollBack();
        return false;
    }
}
}