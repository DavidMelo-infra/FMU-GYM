<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['aluno_id'])) {
    header("Location: login.php");
    exit();
}

$aluno_id = $_SESSION['aluno_id'];

$stmt = $pdo->prepare("SELECT tipo FROM planos WHERE aluno_id = ? AND ativo = 1 ORDER BY data_inicio DESC LIMIT 1");

$stmt->execute([$aluno_id]);
$plano = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Plano Confirmado</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Plano cadastrado com sucesso!</h1>
        <?php if ($plano): ?>
            <p>Você escolheu o plano: <strong><?= htmlspecialchars($plano['tipo']) ?></strong></p>
        <?php else: ?>
            <p>Não foi possível identificar o plano escolhido.</p>
        <?php endif; ?>
        <a href="area_aluno.php">Voltar para área do aluno</a>
    </div>
</body>
</html>
