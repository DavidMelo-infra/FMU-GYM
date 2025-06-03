<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../models/Treino.php';

if (!isset($_SESSION['aluno_id'])) {
    redirecionar('../views/aluno/login.php');
}

$treinoModel = new Treino($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao']) && $_POST['acao'] === 'salvar_treino') {
        $aluno_id = $_SESSION['aluno_id'];
        $tipo = $_POST['tipo'];
        $descricao = $_POST['descricao'];

        $treinoModel->criar($aluno_id, $tipo, $descricao);

        redirecionar('../views/aluno/painel_treinos.php?s=ok');
    }
}
