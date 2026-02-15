<?php
require_once __DIR__ . '/Database.php';

class ScreeningModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getByMovie(int $movieId): array {
        $stmt = $this->db->prepare(
            "SELECT s.*, r.name as room_name, r.capacity as total_seats 
             FROM screenings s 
             JOIN rooms r ON s.room_id = r.id 
             WHERE s.movie_id = ? AND s.screening_date >= CURDATE() 
             ORDER BY s.screening_date, s.screening_time"
        );
        $stmt->execute([$movieId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT s.*, m.title as movie_title, r.name as room_name, r.capacity as total_seats 
             FROM screenings s 
             JOIN movies m ON s.movie_id = m.id 
             JOIN rooms r ON s.room_id = r.id 
             WHERE s.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getAll(): array {
        return $this->db->query(
            "SELECT s.*, m.title as movie_title, r.name as room_name, r.capacity as total_seats 
             FROM screenings s 
             JOIN movies m ON s.movie_id = m.id 
             JOIN rooms r ON s.room_id = r.id 
             ORDER BY s.screening_date, s.screening_time"
        )->fetchAll();
    }

    public function create(int $movieId, int $roomId, string $date, string $time, int $availableSeats): int {
        $stmt = $this->db->prepare(
            "INSERT INTO screenings (movie_id, room_id, screening_date, screening_time, available_seats) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$movieId, $roomId, $date, $time, $availableSeats]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, int $roomId, string $date, string $time): bool {
        // When updating room, reset available seats to room capacity
        $stmt = $this->db->prepare(
            "UPDATE screenings s 
             JOIN rooms r ON r.id = ? 
             SET s.room_id = ?, s.screening_date = ?, s.screening_time = ?, s.available_seats = r.capacity 
             WHERE s.id = ?"
        );
        return $stmt->execute([$roomId, $roomId, $date, $time, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM screenings WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function decreaseSeats(int $id, int $count): bool {
        $stmt = $this->db->prepare("UPDATE screenings SET available_seats = available_seats - ? WHERE id = ? AND available_seats >= ?");
        return $stmt->execute([$count, $id, $count]);
    }

    public function increaseSeats(int $id, int $count): bool {
        $stmt = $this->db->prepare(
            "UPDATE screenings s 
             JOIN rooms r ON s.room_id = r.id 
             SET s.available_seats = s.available_seats + ? 
             WHERE s.id = ? AND s.available_seats + ? <= r.capacity"
        );
        return $stmt->execute([$count, $id, $count]);
    }
}
