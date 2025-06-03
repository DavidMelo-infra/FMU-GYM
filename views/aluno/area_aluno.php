<?php
session_start();
if (!isset($_SESSION['aluno_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Área do Aluno</title>
    <link rel="stylesheet" href="assets/CSS/STYLE.css" />
</head>
<body>
    <div class="container">
        <header class="header">FMU FIT</header>

        <h2>Bem-vindo à Área do Aluno</h2>
        <ul class="menu">
            <li><a href="plano.php">Verificar/Escolher Plano</a></li>
            <li><a href="painel_treinos.php">Consultar Treino</a></li>
            <li><a href="editar_perfil.php">Editar Perfil</a></li>
            <li><a href="/projeto/public/logout.php">Logout</a></li>

        </ul>

        <footer class="footer">© 2025 FMUFIT</footer>
    </div>
</body>
</html>
