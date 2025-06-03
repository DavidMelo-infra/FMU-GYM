<?php
// Middleware para proteger rotas que exigem autenticação

session_start();

function verificarAutenticacaoAluno() {
    if (!isset($_SESSION['aluno_id'])) {
        header("Location: /views/aluno/login.php");
        exit();
    }
}

function verificarAutenticacaoAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: /views/admin/login.php");
        exit();
    }
}
