<?php
require_once __DIR__ . '/../models/MovieModel.php';
require_once __DIR__ . '/../models/ScreeningModel.php';
require_once __DIR__ . '/../models/ReservationModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/RoomModel.php';

class AdminController {
    private MovieModel $movieModel;
    private ScreeningModel $screeningModel;
    private ReservationModel $reservationModel;
    private UserModel $userModel;
    private RoomModel $roomModel;

    public function __construct() {
        requireAdmin();
        $this->movieModel = new MovieModel();
        $this->screeningModel = new ScreeningModel();
        $this->reservationModel = new ReservationModel();
        $this->userModel = new UserModel();
        $this->roomModel = new RoomModel();
    }

    public function movies(): void {
        $movies = $this->movieModel->getAll();
        require __DIR__ . '/../views/admin/movies.php';
    }

    public function createMovie(): void {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $duration = (int)($_POST['duration'] ?? 0);
        $error = null;

        if (!$title || !$duration) {
            $error = "Titre et durée obligatoires.";
            $movies = $this->movieModel->getAll();
            require __DIR__ . '/../views/admin/movies.php';
            return;
        }
        $this->movieModel->create($title, $description, $duration);
        header('Location: index.php?action=admin_movies');
    }

    public function updateMovie(): void {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $duration = (int)($_POST['duration'] ?? 0);
        if ($title && $duration) {
            $this->movieModel->update($id, $title, $description, $duration);
        }
        header('Location: index.php?action=admin_movies');
    }

    public function deleteMovie(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->movieModel->delete($id);
        header('Location: index.php?action=admin_movies');
    }

    public function screenings(): void {
        $screenings = $this->screeningModel->getAll();
        $movies = $this->movieModel->getAll();
        $rooms = $this->roomModel->getAll();
        $error = $_SESSION['screening_error'] ?? null;
        unset($_SESSION['screening_error']);
        require __DIR__ . '/../views/admin/screenings.php';
    }

    public function createScreening(): void {
        $movieId = (int)($_POST['movie_id'] ?? 0);
        $roomId = (int)($_POST['room_id'] ?? 0);
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        
        if ($movieId && $roomId && $date && $time) {
            // Get movie duration for availability check
            $movie = $this->movieModel->findById($movieId);
            if (!$movie) {
                $_SESSION['screening_error'] = "Film introuvable.";
                header('Location: index.php?action=admin_screenings');
                return;
            }
            
            // Check room availability
            if (!$this->screeningModel->isRoomAvailable($roomId, $date, $time, $movie['duration'])) {
                $room = $this->roomModel->findById($roomId);
                $_SESSION['screening_error'] = "La salle « " . $room['name'] . " » est déjà occupée à ce créneau horaire.";
                header('Location: index.php?action=admin_screenings');
                return;
            }
            
            $roomCapacity = $this->roomModel->getCapacity($roomId);
            $this->screeningModel->create($movieId, $roomId, $date, $time, $roomCapacity);
        }
        header('Location: index.php?action=admin_screenings');
    }

    public function deleteScreening(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->screeningModel->delete($id);
        header('Location: index.php?action=admin_screenings');
    }

    public function reservations(): void {
        $reservations = $this->reservationModel->getAll();
        require __DIR__ . '/../views/admin/reservations.php';
    }

    public function users(): void {
        $users = $this->userModel->getAll();
        require __DIR__ . '/../views/admin/users.php';
    }

    public function deleteUser(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id !== $_SESSION['user_id']) {
            $this->userModel->delete($id);
        }
        header('Location: index.php?action=admin_users');
    }

    // Room management
    public function rooms(): void {
        $rooms = $this->roomModel->getAll();
        require __DIR__ . '/../views/admin/rooms.php';
    }

    public function createRoom(): void {
        $name = trim($_POST['name'] ?? '');
        $capacity = (int)($_POST['capacity'] ?? 0);
        $error = null;

        if (!$name || $capacity < 1) {
            $error = "Nom et capacité obligatoires.";
            $rooms = $this->roomModel->getAll();
            require __DIR__ . '/../views/admin/rooms.php';
            return;
        }
        $this->roomModel->create($name, $capacity);
        header('Location: index.php?action=admin_rooms');
    }

    public function updateRoom(): void {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $capacity = (int)($_POST['capacity'] ?? 0);
        if ($name && $capacity > 0) {
            $this->roomModel->update($id, $name, $capacity);
        }
        header('Location: index.php?action=admin_rooms');
    }

    public function deleteRoom(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->roomModel->delete($id);
        header('Location: index.php?action=admin_rooms');
    }
}
