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
}