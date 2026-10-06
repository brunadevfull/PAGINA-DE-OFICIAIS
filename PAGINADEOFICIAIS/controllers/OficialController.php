<?php
class OficialController {
    public function index() {
        include 'models/Oficial.php';
        require_once 'includes/MilitaryPersonnelRepository.php';

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Verifica se o usuário está logado
        $is_logged_in = isset($_SESSION['user_id']);
        $body_class = $is_logged_in ? 'logged-in' : 'logged-out';

        // Obtém os oficiais locais
        $oficiais = Oficial::all();

        $personnelRepository = new MilitaryPersonnelRepository();

        $personnelErrors = [];
        $officerOptions = [];
        $masterOptions = [];

        try {
            $officerOptions = $personnelRepository->getPersonnelOptions('officer');
        } catch (Exception $exception) {
            $personnelErrors[] = $exception->getMessage();
        }

        try {
            $masterOptions = $personnelRepository->getPersonnelOptions('master');
        } catch (Exception $exception) {
            $personnelErrors[] = $exception->getMessage();
        }

        if (empty($officerOptions) || empty($masterOptions)) {
            if (empty($personnelErrors)) {
                $personnelErrors[] = 'Nenhum registro encontrado no banco de militares.';
            }
        }

        // Inclui a view e passa as variáveis necessárias
        include 'views/oficiais/index.php';
    }

    public function add() {
        include '../models/Oficial.php';
        Oficial::add($_POST);
        header('Location: ../index.php');
    }

    public function edit() {
        include '../models/Oficial.php';
        Oficial::edit($_POST);
        header('Location: ../index.php');
    }

    public function remove() {
        include '../models/Oficial.php';
        Oficial::remove($_POST['id']);
        header('Location: ../index.php');
    }
}
?>

