<?php
require_once __DIR__ . '/Database.php';

class MovieModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM movies ORDER BY title")->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $title, string $description, int $duration): int {
        $stmt = $this->db->prepare("INSERT INTO movies (title, description, duration) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $duration]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $title, string $description, int $duration): bool {
        $stmt = $this->db->prepare("UPDATE movies SET title=?, description=?, duration=? WHERE id=?");
        return $stmt->execute([$title, $description, $duration, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM movies WHERE id=?");
        return $stmt->execute([$id]);
    }
}
