<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    echo 'Acesso negado.';
    exit;
}


// Caminho do arquivo temporÃ¡rio
$tempFile = sys_get_temp_dir() . '/oficiais_status.json';

// Carrega os dados existentes
if (file_exists($tempFile)) {
    $data = json_decode(file_get_contents($tempFile), true);
    if ($data === null) {
        echo 'Erro ao decodificar JSON existente: ' . json_last_error_msg();
        exit;
    }
} else {
    $data = [];
}

// Atualiza o status do oficial
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Atualiza o status no array
    $data[$id] = $status;

    // Abre o arquivo para escrita e adquire um bloqueio ex  fazerclusivo
    $file = fopen($tempFile, 'c+');
    if (!$file) {
        echo 'Erro ao abrir o arquivo: ' . $tempFile . ' - PermissÃµes: ' . substr(sprintf('%o', fileperms($tempFile)), -4);
        exit;
    }
    if (flock($file, LOCK_EX)) {
        // Posiciona o ponteiro do arquivo no inÃ­cio
        ftruncate($file, 0); // Trunca o arquivo para zero bytes
        rewind($file); // Coloca o ponteiro do arquivo no inÃ­cio

        // Escreve os dados no arquivo e libera o bloqueio
        if (fwrite($file, json_encode($data)) === false) {
            echo 'Erro ao escrever no arquivo.';
        } else {
            fflush($file); // Garante que todos os dados sejam escritos
            flock($file, LOCK_UN);
            fclose($file);
            echo 'Status atualizado com sucesso.';
        }
    } else {
        fclose($file);
        echo 'Erro ao adquirir bloqueio no arquivo.';
    }
}
?>
