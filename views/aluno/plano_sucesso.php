<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['aluno_id'])) {
    header("Location: /FMU-GYM/views/aluno/login.php"); // Caminho ajustado
    exit();
}

$aluno_id = $_SESSION['aluno_id'];
$nome_plano_escolhido = ""; // Valor padrão

$stmt_plano = $pdo->prepare("SELECT tipo FROM planos WHERE aluno_id = ? AND ativo = 1 ORDER BY data_inicio DESC, id DESC LIMIT 1");
$stmt_plano->execute([$aluno_id]);
$plano_info = $stmt_plano->fetch(PDO::FETCH_ASSOC);

if ($plano_info) {
    $nome_plano_escolhido = htmlspecialchars(ucfirst($plano_info['tipo']));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucesso! - FMU FIT</title>
    <link rel="stylesheet" href="../../assets/css/style-cadastro.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; 
            color: #333;
        }
        main.content-area-simples { /* Nova classe para um layout simples */
            flex-grow: 1;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .mensagem-status {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            max-width: 500px; /* Largura da mensagem */
        }
        .mensagem-status h1 {
            font-size: 2em;
            color: #d3220e; /* Cor principal do tema */
            margin-bottom: 10px;
        }
        .mensagem-status p {
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .mensagem-status strong {
            font-weight: bold;
        }
        .btn-acao-sucesso { /* Botão para voltar ou outra ação */
            display: inline-block;
            padding: 10px 25px;
            background-color: #d3220e;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-acao-sucesso:hover {
            background-color: #a93226; /* Tom mais escuro */
        }
    </style>
</head>
<body>

    <header>
        <div class="header-content">
            <div class="textos-header">
                <h1>Operação Concluída</h1>
                <p>Sua solicitação foi processada com sucesso.</p>
            </div>
            <img src="/FMU-GYM/assets/images/FMUFIT.png" alt="Logo FMU FIT" class="header-img">
        </div>
    </header>

    <main class="content-area-simples">
        <div class="mensagem-status">
            <h1>Sucesso!</h1>
            <?php if ($plano_info): ?>
                <p>Seu plano <strong><?= $nome_plano_escolhido ?></strong> foi confirmado e está ativo.</p>
                <p>Aproveite todos os benefícios!</p>
            <?php else: ?>
                <p>Sua solicitação de plano foi processada.</p>
                <p>Você pode verificar os detalhes na sua área de aluno.</p>
            <?php endif; ?>
            <a href="area_aluno.php" class="btn-acao-sucesso">Ir para Área do Aluno</a>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> FMU FIT - Todos os direitos reservados.</p>
    </footer>

</body>
</html>