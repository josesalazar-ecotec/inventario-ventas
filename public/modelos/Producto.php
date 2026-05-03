<?php

class Producto
{
    private PDO $conn;
    private string $table = "productos";

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function crear(string $nombre, string $descripcion, float $precio, int $stock): bool
    {
        $sql = "INSERT INTO {$this->table} (nombre, descripcion, precio, stock)
                VALUES (:nombre, :descripcion, :precio, :stock)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":stock", $stock);

        return $stmt->execute();
    }
    public function listar(): array
    {
        $sql = "SELECT id, nombre, descripcion, precio, stock, creado_en 
                FROM productos 
                ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM productos WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
    public function obtenerPorId(int $id): ?array
{
    $sql = "SELECT * FROM productos WHERE id = :id LIMIT 1";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado ?: null;
}

public function actualizar(int $id, string $nombre, string $descripcion, float $precio, int $stock): bool
{
    $sql = "UPDATE productos 
            SET nombre = :nombre,
                descripcion = :descripcion,
                precio = :precio,
                stock = :stock
            WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":descripcion", $descripcion);
    $stmt->bindParam(":precio", $precio);
    $stmt->bindParam(":stock", $stock);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    return $stmt->execute();
}
}