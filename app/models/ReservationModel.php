<?php
require_once __DIR__ . '/Database.php';

class ReservationModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT r.*, s.screening_date, s.screening_time, m.title as movie_title, rm.name as room_name
                FROM reservations r
                JOIN screenings s ON r.screening_id = s.id
                JOIN movies m ON s.movie_id = m.id
                JOIN rooms rm ON s.room_id = rm.id
                WHERE r.user_id = ?
                ORDER BY r.created_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getAll(): array {
        return $this->db->query(
            "SELECT r.*, u.username, s.screening_date, s.screening_time, m.title as movie_title, rm.name as room_name
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN screenings s ON r.screening_id = s.id
                JOIN movies m ON s.movie_id = m.id
                JOIN rooms rm ON s.room_id = rm.id
                ORDER BY r.created_at DESC"
        )->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $userId, int $screeningId, int $seats): int {
        $stmt = $this->db->prepare("INSERT INTO reservations (user_id, screening_id, seats) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $screeningId, $seats]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM reservations WHERE id=?");
        return $stmt->execute([$id]);
    }
}
