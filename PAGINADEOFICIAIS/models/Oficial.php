<?php

class Oficial {
    const STATUSES = ['bordo', 'terra'];

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

        if (empty($nome) || !in_array($status, self::STATUSES, true) || $posto_id <= 0 || $localizacao < 0) {
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
            error_log("Falha ao adicionar oficial: " . $e->getMessage());
            throw new Exception("Falha ao adicionar oficial.");
        }
    }

    public static function edit($data) {
        global $pdo;
        $id = (int)$data['id'];
        $nome = trim($data['nome']);
        $posto_id = (int)$data['posto'];
        $status = $data['status'];
        $localizacao = (int)$data['localizacao'];

        if ($id <= 0 || empty($nome) || !in_array($status, self::STATUSES, true) || $posto_id <= 0 || $localizacao < 0) {
            throw new Exception("Dados inválidos fornecidos.");
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("UPDATE oficiais SET nome = :nome, posto_id = :posto_id, status = :status, localizacao = :localizacao WHERE id = :id");
            $stmt->execute([':nome' => $nome, ':posto_id' => $posto_id, ':status' => $status, ':localizacao' => $localizacao, ':id' => $id]);

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Falha ao editar oficial: " . $e->getMessage());
            throw new Exception("Falha ao editar oficial.");
        }
    }

    public static function remove($id) {
        global $pdo;
        $id = (int)$id;

        if ($id <= 0) {
            throw new Exception("Dados inválidos fornecidos.");
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("SELECT localizacao FROM oficiais WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $localizacao = $stmt->fetchColumn();

            if ($localizacao === false) {
                $pdo->rollBack();
                throw new Exception("Oficial não encontrado.");
            }

            $stmt = $pdo->prepare("DELETE FROM oficiais WHERE id = :id");
            $stmt->execute([':id' => $id]);

            $stmt = $pdo->prepare("UPDATE oficiais SET localizacao = localizacao - 1 WHERE localizacao > :localizacao");
            $stmt->execute([':localizacao' => $localizacao]);

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Falha ao remover oficial: " . $e->getMessage());
            throw new Exception("Falha ao remover oficial.");
        }
    }
}
