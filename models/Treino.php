<?php
require_once __DIR__ . '/../includes/db.php';

class Treino {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarPorAluno($aluno_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM treinos WHERE aluno_id = ? ORDER BY id DESC");
        $stmt->execute([$aluno_id]);
        return $stmt->fetchAll();
    }

    public function criar($aluno_id, $tipo, $descricao) {
        $stmt = $this->pdo->prepare("INSERT INTO treinos (aluno_id, tipo, descricao) VALUES (?, ?, ?)");
        return $stmt->execute([$aluno_id, $tipo, $descricao]);
    }

    public function excluir($id, $aluno_id) {
        $stmt = $this->pdo->prepare("DELETE FROM treinos WHERE id = ? AND aluno_id = ?");
        return $stmt->execute([$id, $aluno_id]);
    }
}
