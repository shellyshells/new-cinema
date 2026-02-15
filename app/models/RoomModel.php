<?php
require_once __DIR__ . '/Database.php';

class RoomModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM rooms ORDER BY name")->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, int $capacity): int {
        $stmt = $this->db->prepare("INSERT INTO rooms (name, capacity) VALUES (?, ?)");
        $stmt->execute([$name, $capacity]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $name, int $capacity): bool {
        $stmt = $this->db->prepare("UPDATE rooms SET name = ?, capacity = ? WHERE id = ?");
        return $stmt->execute([$name, $capacity, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM rooms WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getCapacity(int $id): int {
        $stmt = $this->db->prepare("SELECT capacity FROM rooms WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ? (int)$result['capacity'] : 0;
    }
}
