<?php
session_start();
if (!isset($_SESSION['aluno_id'])) {
    header("Location: /FMU-GYM/views/aluno/login.php"); 
    exit();
}

require_once '../../includes/db.php'; 
$aluno_id = $_SESSION['aluno_id'];
$nome_aluno_saudacao = 'Aluno(a)'; 

$stmt_aluno = $pdo->prepare("SELECT nome FROM alunos WHERE id = ?");
$stmt_aluno->execute([$aluno_id]);
$aluno_info = $stmt_aluno->fetch(PDO::FETCH_ASSOC);

if ($aluno_info && !empty($aluno_info['nome'])) {
    $nome_aluno_saudacao = htmlspecialchars(explode(' ', trim($aluno_info['nome']))[0]);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Aluno - FMU FIT</title>
    <link rel="stylesheet" href="../../assets/css/area_aluno_style.css" />
    </head>
<body>
    <header class="page-header">
        <div class="logo-area">
            <img src="../../assets/images/FMUFIT.png" alt="Logo FMU FIT"> <h1>Área do Aluno</h1>
        </div>
        <div class="user-info">
            <p>Olá, <?= $nome_aluno_saudacao ?>!</p>
        </div>
    </header>

    <div class="main-container">
        <h2>Bem-vindo(a) de volta!</h2>
        
        <ul class="navigation-menu">
            <li>
                <a href="plano.php">
                    <span class="icon-placeholder">📄</span> Verificar/Escolher Plano
                </a>
            </li>
            <li>
                <a href="painel_treinos.php">
                    <span class="icon-placeholder">🏋️</span> Consultar/Salvar Treino
                </a>
            </li>
            <li>
                <a href="editar_perfil.php">
                    <span class="icon-placeholder">👤</span> Editar Perfil
                </a>
            </li>
            <li>
                <a href="/FMU-GYM/public/logout.php"> <span class="icon-placeholder">🚪</span> Sair
                </a>
            </li>
        </ul>
    </div>

    <footer class="page-footer">
        <p>&copy; <?= date('Y') ?> FMU FIT - Todos os direitos reservados.</p>
    </footer>
</body>
</html>