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

    /**
     * Check if a room is available for a screening at a given date/time
     * Considers movie duration to prevent overlapping screenings
     */
    public function isRoomAvailable(int $roomId, string $date, string $time, int $movieDuration, ?int $excludeScreeningId = null): bool {
        // Calculate the end time of the proposed screening
        $startTime = strtotime("$date $time");
        $endTime = $startTime + ($movieDuration * 60); // duration in seconds
        
        // Find screenings in the same room on the same date
        $sql = "SELECT s.screening_time, m.duration 
                FROM screenings s 
                JOIN movies m ON s.movie_id = m.id 
                WHERE s.room_id = ? AND s.screening_date = ?";
        $params = [$roomId, $date];
        
        if ($excludeScreeningId) {
            $sql .= " AND s.id != ?";
            $params[] = $excludeScreeningId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $existingScreenings = $stmt->fetchAll();
        
        foreach ($existingScreenings as $screening) {
            $existingStart = strtotime("$date " . $screening['screening_time']);
            $existingEnd = $existingStart + ($screening['duration'] * 60);
            
            // Check for overlap: new screening starts before existing ends AND new screening ends after existing starts
            if ($startTime < $existingEnd && $endTime > $existingStart) {
                return false; // Conflict found
            }
        }
        
        return true; // No conflicts
    }

    /**
     * Get available rooms for a given date/time and movie duration
     */
    public function getAvailableRooms(string $date, string $time, int $movieDuration): array {
        require_once __DIR__ . '/RoomModel.php';
        $roomModel = new RoomModel();
        $allRooms = $roomModel->getAll();
        
        $availableRooms = [];
        foreach ($allRooms as $room) {
            if ($this->isRoomAvailable($room['id'], $date, $time, $movieDuration)) {
                $availableRooms[] = $room;
            }
        }
        
        return $availableRooms;
    }
}
