<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../models/Plano.php';

if (!isset($_SESSION['aluno_id'])) {
    redirecionar('../views/aluno/login.php');
}

$planoModel = new Plano($pdo);
$aluno_id = $_SESSION['aluno_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['tipo']) && isset($_POST['pagamento'])) {
        $tipo = $_POST['tipo'];
        $pagamento = $_POST['pagamento'];
        $data_inicio = date('Y-m-d');

        $planoModel->desativarPlanos($aluno_id);
        $planoModel->criarPlano($aluno_id, $tipo, $pagamento, $data_inicio);

        redirecionar('../views/aluno/area_aluno.php');
    }
}

// Para exibir o plano ativo (exemplo de uso)
$planoAtivo = $planoModel->buscarAtivoPorAluno($aluno_id);
