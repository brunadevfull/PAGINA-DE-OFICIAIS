<?php
class AdminModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAdminById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id AND is_admin = TRUE');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAdminPassword($id, $password) {
        $stmt = $this->pdo->prepare('UPDATE users SET password = :password WHERE id = :id AND is_admin = TRUE');
        return $stmt->execute(['password' => $password, 'id' => $id]);
    }
}
