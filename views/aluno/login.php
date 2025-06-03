<?php
session_start();
if (isset($_SESSION['aluno_id'])) {
    header("Location: area_aluno.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="../../assets/css/style.css" />
</head>
<body>
    <div class="container">
        <header class="header">FMU FIT</header>

        <h2>Login</h2>
        <?php if (isset($_GET['erro'])): ?>
            <p class="error">E-mail ou senha incorretos.</p>
        <?php endif; ?>
        <?php if (isset($_GET['cadastro'])): ?>
            <p class="success">Cadastro realizado com sucesso! Faça login.</p>
        <?php endif; ?>
        <form method="post" action="../../controllers/AlunoController.php">
            <input type="hidden" name="acao" value="login" />
            <label for="email">E-mail:</label><br/>
            <input type="email" id="email" name="email" required /><br/>
            <label for="senha">Senha:</label><br/>
            <input type="password" id="senha" name="senha" required /><br/>
            <button type="submit">Entrar</button>
        </form>
        <p>Não tem conta? <a href="cadastro.php">Cadastre-se aqui</a></p>

        <footer class="footer">© 2025 FMU FIT</footer>
    </div>
</body>
</html>
