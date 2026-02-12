<?php
require_once __DIR__ . '/../models/MovieModel.php';
require_once __DIR__ . '/../models/ScreeningModel.php';

class MovieController {
    private MovieModel $movieModel;
    private ScreeningModel $screeningModel;

    public function __construct() {
        $this->movieModel = new MovieModel();
        $this->screeningModel = new ScreeningModel();
    }

    public function index(): void {
        $movies = $this->movieModel->getAll();
        require __DIR__ . '/../views/movies/index.php';
    }

    public function show(): void {
        $id = (int)($_GET['id'] ?? 0);
        $movie = $this->movieModel->findById($id);
        if (!$movie) { header('Location: index.php'); exit; }
        $screenings = $this->screeningModel->getByMovie($id);
        require __DIR__ . '/../views/movies/show.php';
    }
}
