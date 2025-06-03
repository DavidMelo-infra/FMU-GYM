<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Academia - Sistema</title>
    <link rel="stylesheet" href="/css/style.css" />
</head>
<body>
<header>
    <nav>
        <ul>
            <?php if (isset($_SESSION['aluno_id'])): ?>
                <li><a href="/FMU-GYM/views/aluno/area_aluno.php">Área do Aluno</a></li>
                 <li><a href="/FMU-GYM/public/logout.php">Sair - Realizar Logout</a></li>
            <?php else: ?>
                <li><a href="/views/aluno/login.php">Login</a></li>
                <li><a href="/views/aluno/cadastro.php">Cadastro</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
