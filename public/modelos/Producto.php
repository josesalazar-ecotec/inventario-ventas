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
}