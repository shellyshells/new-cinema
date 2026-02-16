<?php
require_once __DIR__ . '/../models/ReservationModel.php';
require_once __DIR__ . '/../models/ScreeningModel.php';

class ReservationController {
    private ReservationModel $reservationModel;
    private ScreeningModel $screeningModel;

    public function __construct() {
        $this->reservationModel = new ReservationModel();
        $this->screeningModel = new ScreeningModel();
    }

    public function index(): void {
        requireLogin();
        $reservations = $this->reservationModel->getByUser($_SESSION['user_id']);
        require __DIR__ . '/../views/reservations/index.php';
    }

    public function create(): void {
        requireLogin();
        $screeningId = (int)($_GET['screening_id'] ?? 0);
        $screening = $this->screeningModel->findById($screeningId);
        if (!$screening) { header('Location: index.php'); exit; }
        require __DIR__ . '/../views/reservations/create.php';
    }

    public function store(): void {
        requireLogin();
        $screeningId = (int)($_POST['screening_id'] ?? 0);
        $seats = (int)($_POST['seats'] ?? 1);
        $error = null;

        if ($seats < 1 || $seats > 10) {
            $error = "Nombre de places invalide (1-10).";
        }

        $screening = $this->screeningModel->findById($screeningId);
        if (!$screening) { header('Location: index.php'); exit; }

        if (!$error && $screening['available_seats'] < $seats) {
            $error = "Pas assez de places disponibles. Disponibles : " . $screening['available_seats'];
        }

        if ($error) {
            require __DIR__ . '/../views/reservations/create.php';
            return;
        }

        $this->screeningModel->decreaseSeats($screeningId, $seats);
        $this->reservationModel->create($_SESSION['user_id'], $screeningId, $seats);
        $_SESSION['success'] = "Réservation confirmée !";
        header('Location: index.php?action=reservations');
    }

    public function cancel(): void {
        requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $reservation = $this->reservationModel->findById($id);

        if ($reservation && ($reservation['user_id'] == $_SESSION['user_id'] || isAdmin())) {
            $this->screeningModel->increaseSeats($reservation['screening_id'], $reservation['seats']);
            $this->reservationModel->delete($id);
            $_SESSION['success'] = "Réservation annulée.";
        }
        header('Location: ' . (isAdmin() ? 'index.php?action=admin_reservations' : 'index.php?action=reservations'));
    }
}
