<?php
class UserModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function updateUserPassword($id, $password) {
        $stmt = $this->pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
        return $stmt->execute(['password' => $password, 'id' => $id]);
    }
}
?>
