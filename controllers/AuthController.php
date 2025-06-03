<?php

session_start();
require_once __DIR__ . '/../models/Aluno.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

class AuthController {

    public static function login($email, $senha) {
        $conn = conectarBanco();
        $stmt = $conn->prepare("SELECT * FROM alunos WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $aluno = $result->fetch_assoc();
            if (password_verify($senha, $aluno['senha'])) {
                $_SESSION['aluno_id'] = $aluno['id'];
                $_SESSION['aluno_nome'] = $aluno['nome'];
                header("Location: ../views/aluno/area_aluno.php");
                exit();
            }
        }

        header("Location: ../views/aluno/login.php?erro=1");
        exit();
    }

    public static function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../views/aluno/login.php");
        exit();
    }
}

// Controle de requisição via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao']) && $_POST['acao'] === 'login') {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        AuthController::login($email, $senha);
    }
}

// Logout manual
if (isset($_GET['acao']) && $_GET['acao'] === 'logout') {
    AuthController::logout();
}
