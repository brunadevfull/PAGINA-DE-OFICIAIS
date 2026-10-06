<?php

class Oficial {
    public static function all() {
        global $pdo;
        $stmt = $pdo->query('
            SELECT o.id, o.nome, p.descricao, p.imagem, o.status, o.localizacao, o.posto_id 
            FROM oficiais o
            JOIN postos p ON o.posto_id = p.id
            ORDER BY o.localizacao
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function add($data) {
        global $pdo;
        $nome = trim($data['nome']);
        $posto_id = (int)$data['posto'];
        $status = $data['status'];
        $localizacao = (int)$data['localizacao'];

        if (empty($nome) || empty($status) || $posto_id <= 0 || $localizacao < 0) {
            throw new Exception("Dados inválidos fornecidos.");
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("UPDATE oficiais SET localizacao = localizacao + 1 WHERE localizacao >= :localizacao");
            $stmt->execute([':localizacao' => $localizacao]);

            $stmt = $pdo->prepare("INSERT INTO oficiais (nome, posto_id, status, localizacao) VALUES (:nome, :posto_id, :status, :localizacao)");
            $stmt->execute([':nome' => $nome, ':posto_id' => $posto_id, ':status' => $status, ':localizacao' => $localizacao]);

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw new Exception("Falha ao adicionar oficial: " . htmlspecialchars($e->getMessage()));
        }
    }

    public static function edit($data) {
        global $pdo;
        $id = (int)$data['id'];
        $nome = trim($data['nome']);
        $posto_id = (int)$data['posto'];
        $status = $data['status'];
        $localizacao = (int)$data['localizacao'];

        if (empty($nome) || empty($status) || $posto_id <= 0 || $localizacao < 0) {
            throw new Exception("Dados inválidos fornecidos.");
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("UPDATE oficiais SET nome = :nome, posto_id = :posto_id, status = :status, localizacao = :localizacao WHERE id = :id");
            $stmt->execute([':nome' => $nome, ':posto_id' => $posto_id, ':status' => $status, ':localizacao' => $localizacao, ':id' => $id]);

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw new Exception("Falha ao editar oficial: " . htmlspecialchars($e->getMessage()));
        }
    }

    public static function remove($id) {
        global $pdo;
        $id = (int)$id;

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("SELECT localizacao FROM oficiais WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $localizacao = $stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM oficiais WHERE id = :id");
            $stmt->execute([':id' => $id]);

            $stmt = $pdo->prepare("UPDATE oficiais SET localizacao = localizacao - 1 WHERE localizacao > :localizacao");
            $stmt->execute([':localizacao' => $localizacao]);

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw new Exception("Falha ao remover oficial: " . htmlspecialchars($e->getMessage()));
        }
    }
}
?>

