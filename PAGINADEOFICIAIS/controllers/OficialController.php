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
        require_once __DIR__ . '/../includes/OficialStatusStore.php';
        $oficiais = Oficial::all();
        $statusData = OficialStatusStore::all();

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
    }

    public function edit() {
        include '../models/Oficial.php';
        require_once __DIR__ . '/../includes/OficialStatusStore.php';

        Oficial::edit($_POST);

        // O quadro exibe o status temporário (arquivo) acima do banco; sem isto a edição não apareceria
        OficialStatusStore::set((int)$_POST['id'], $_POST['status']);
    }

    public function remove() {
        include '../models/Oficial.php';
        Oficial::remove($_POST['id'] ?? 0);
    }
}
