<?php
require_once __DIR__ . '/../includes/db.php';

class Plano {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function buscarAtivoPorAluno($aluno_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM planos WHERE aluno_id = ? AND ativo = 1 ORDER BY data_inicio DESC LIMIT 1");
        $stmt->execute([$aluno_id]);
        return $stmt->fetch();
    }

    public function desativarPlanos($aluno_id) {
        $stmt = $this->pdo->prepare("UPDATE planos SET ativo = 0 WHERE aluno_id = ?");
        return $stmt->execute([$aluno_id]);
    }

    public function criarPlano($aluno_id, $tipo, $pagamento, $data_inicio) {
        $stmt = $this->pdo->prepare("INSERT INTO planos (aluno_id, tipo, pagamento, data_inicio, ativo) VALUES (?, ?, ?, ?, 1)");
        return $stmt->execute([$aluno_id, $tipo, $pagamento, $data_inicio]);
    }
}
