<?php
class Aluno {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Buscar aluno pelo email (login)
    public function buscarPorEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM alunos WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar aluno pelo ID (para obter dados atuais, inclusive treino selecionado)
    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM alunos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cadastrar novo aluno
    public function cadastrar($nome, $email, $telefone, $cpf, $data_nascimento, $genero, $senha) {
        $sql = "INSERT INTO alunos (nome, email, telefone, cpf, data_nascimento, genero, senha) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nome, $email, $telefone, $cpf, $data_nascimento, $genero, $senha]);
    }

    // Atualizar dados do aluno (nome, email e opcionalmente senha)
    public function atualizar($id, $nome, $email, $senha = null) {
        if ($senha) {
            $sql = "UPDATE alunos SET nome = ?, email = ?, senha = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nome, $email, $senha, $id]);
        } else {
            $sql = "UPDATE alunos SET nome = ?, email = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$nome, $email, $id]);
        }
    }

    // Atualizar treino selecionado pelo aluno
    public function atualizarTreinoSelecionado($id, $treino) {
        $sql = "UPDATE alunos SET treino_selecionado = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$treino, $id]);
    }
}
