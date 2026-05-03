<?php

class Venta
{
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function registrar(int $productoId, int $cantidad): bool
    {
        $this->conn->beginTransaction();

        try {
            $sqlProducto = "SELECT precio, stock FROM productos WHERE id = :id LIMIT 1";
            $stmtProducto = $this->conn->prepare($sqlProducto);
            $stmtProducto->bindParam(":id", $productoId, PDO::PARAM_INT);
            $stmtProducto->execute();

            $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

            if (!$producto || $producto["stock"] < $cantidad) {
                $this->conn->rollBack();
                return false;
            }

            $total = $producto["precio"] * $cantidad;

            $sqlVenta = "INSERT INTO ventas (total) VALUES (:total)";
            $stmtVenta = $this->conn->prepare($sqlVenta);
            $stmtVenta->bindParam(":total", $total);
            $stmtVenta->execute();

            $ventaId = (int) $this->conn->lastInsertId();

            $sqlDetalle = "INSERT INTO venta_detalles 
                (venta_id, producto_id, cantidad, precio_unitario, subtotal)
                VALUES (:venta_id, :producto_id, :cantidad, :precio_unitario, :subtotal)";

            $stmtDetalle = $this->conn->prepare($sqlDetalle);
            $stmtDetalle->bindParam(":venta_id", $ventaId, PDO::PARAM_INT);
            $stmtDetalle->bindParam(":producto_id", $productoId, PDO::PARAM_INT);
            $stmtDetalle->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
            $stmtDetalle->bindParam(":precio_unitario", $producto["precio"]);
            $stmtDetalle->bindParam(":subtotal", $total);
            $stmtDetalle->execute();

            $sqlStock = "UPDATE productos SET stock = stock - :cantidad WHERE id = :id";
            $stmtStock = $this->conn->prepare($sqlStock);
            $stmtStock->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
            $stmtStock->bindParam(":id", $productoId, PDO::PARAM_INT);
            $stmtStock->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}