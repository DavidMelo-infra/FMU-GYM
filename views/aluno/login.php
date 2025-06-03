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
    <title>Login - FMU FIT</title>
    <link rel="stylesheet" href="../../assets/css/style-cadastro.css" />
</head>
<body>
    <header>
        <div class="header-content">
            <div class="textos-header">
                <h1>FMU FIT</h1>
                <p>Acesse sua conta para continuar</p>
            </div>
            <img src="../../assets/images/FMUFIT.png" alt="Logo FMU FIT" class="header-img">
        </div>
    </header>

    <main>
        <section class="form-container">
            <h2>Login</h2>

            <?php if (isset($_GET['erro'])): ?>
                <p class="error-message">E-mail ou senha incorretos.</p>
            <?php endif; ?>
            <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'ok'): ?>
                <p class="success-message">Cadastro realizado com sucesso! Faça login.</p>
            <?php endif; ?>

            <form method="post" action="../../controllers/AlunoController.php">
                <input type="hidden" name="acao" value="login" />
                
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required autocomplete="off">
                
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required autocomplete="off">
                
                <button type="submit">Entrar</button>
            </form>
            <p class="register-link">Não tem conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> FMU FIT - Todos os direitos reservados.</p>
    </footer>
</body>
</html>