<?php
// Models/Client.php

class Client {
    private $pdo;

    public function __construct() {
        // Aseguramos la conexión usando tu clase DatabaseCN
        $this->pdo = DatabaseCN::conectar();
    }

    // 1. Obtener todos los clientes (Ordenados por el más reciente)
    public function getClients() {
        // Seleccionamos las columnas tal cual están en tu BD de InfinityFree
        $stmt = $this->pdo->query("SELECT ID, NOMBRE, TELEFONO, CORREO, FECHA_REGISTRO FROM clientes ORDER BY ID DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Agregar cliente (Sincronizado con los 3 campos del formulario)
    public function addClient($nombre, $telefono, $correo) {
        $sql = "INSERT INTO clientes (NOMBRE, TELEFONO, CORREO) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $telefono, $correo]);
    }

    // 3. Actualizar cliente
    public function updateClient($id, $nombre, $telefono, $correo) {
        $sql = "UPDATE clientes SET NOMBRE=?, TELEFONO=?, CORREO=? WHERE ID=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $telefono, $correo, $id]);
    }

    // 4. Eliminar cliente
    public function deleteClient($id) {
        $sql = "DELETE FROM clientes WHERE ID=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
