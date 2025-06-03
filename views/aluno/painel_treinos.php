<?php
session_start();

if (!isset($_SESSION['aluno_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../../includes/db.php';

$aluno_id = $_SESSION['aluno_id'];

// Buscar o nome do aluno
$stmt_aluno = $pdo->prepare("SELECT nome FROM alunos WHERE id = ?");
$stmt_aluno->execute([$aluno_id]);
$aluno = $stmt_aluno->fetch(PDO::FETCH_ASSOC);
$nome_aluno = $aluno ? htmlspecialchars($aluno['nome']) : 'Aluno';

// Buscar treinos salvos
$stmt = $pdo->prepare("SELECT * FROM treinos WHERE aluno_id = ? ORDER BY id DESC");
$stmt->execute([$aluno_id]);
$treinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mensagem de sucesso
$mensagem_sucesso = '';
if (!empty($_SESSION['mensagem_sucesso'])) {
    $mensagem_sucesso = $_SESSION['mensagem_sucesso'];
    unset($_SESSION['mensagem_sucesso']);
}

// OBS: $_POST['tipo'] só estará disponível se form foi postado nesta página
// Como o form é enviado para outro controller, removemos esta verificação do PHP
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel de Treinos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
        }
        .header, .footer {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: #cc0000;
        }
        select, button {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        select {
            width: 100%;
            max-width: 300px;
        }
        button {
            background-color: #cc0000;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #990000;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .exercises {
            margin-top: 20px;
        }
        .exercise {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .exercise h3 {
            margin-top: 0;
            color: #333;
        }
        .exercise ul {
            padding-left: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <header class="header">FMU FIT</header>

    <h2>Bem-vindo(a), <?= $nome_aluno ?>!</h2>
    <h2>Meus Treinos</h2>

    <?php if (!empty($mensagem_sucesso)): ?>
        <div class="success-message"><?= htmlspecialchars($mensagem_sucesso) ?></div>
    <?php endif; ?>

    <h3>SELECIONE SEU TREINO</h3>

    <form method="post" action="../../controllers/AlunoController.php" id="formTreino">
        <input type="hidden" name="acao" value="salvar_treino">
        <input type="hidden" name="descricao" id="descricaoTreino">
        <input type="hidden" name="titulo" id="tituloTreino">

        <label for="tipoTreino">Tipo:</label>
        <select name="tipo" id="tipoTreino" required onchange="mostrarExercicios()">
            <option value="">-- Selecione --</option>
            <option value="condicionamento">Condicionamento</option>
            <option value="emagrecimento">Emagrecimento</option>
            <option value="hipertrofia">Hipertrofia</option>
        </select>

        <div id="exerciciosContainer"></div>
        <button type="submit" id="btnSalvar" disabled>Salvar Treino</button>
    </form>

    <hr/>

    <?php if (!empty($treinos)): ?>
        <h3>Treinos Salvos</h3>
        <?php foreach ($treinos as $treino): ?>
            <div class="exercises">
                <h3>Treino: <?= htmlspecialchars($treino['titulo']) ?></h3>
                <pre style="white-space: pre-wrap;"><?= htmlspecialchars($treino['descricao']) ?></pre>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Você ainda não possui treinos salvos.</p>
    <?php endif; ?>

    <a href="area_aluno.php">Voltar</a>

    <footer class="footer">© 2025 Academia</footer>
</div>

<script>
const treinosPredefinidos = {
    condicionamento: [
        { dia: 'Dia A – Cardio + Core', exercicios: ['Corrida leve na esteira – 10 min', 'Burpees – 3x15', 'Prancha abdominal – 3x30s', 'Abdominal bicicleta – 3x20', 'Agachamento com salto – 3x15', 'Corrida HIIT (20s rápido / 40s lento) – 10 min'] },
        { dia: 'Dia B – Corpo todo funcional', exercicios: ['Polichinelos – 3x30', 'Flexão de braço – 3x15', 'Agachamento com kettlebell – 3x15', 'Mountain climbers – 3x30s', 'Tríceps banco – 3x15', 'Subida em banco – 3x15 (cada perna)'] },
        { dia: 'Dia C – Resistência muscular', exercicios: ['Remada no TRX – 3x12', 'Agachamento isométrico – 3x30s', 'Abdominal prancha lateral – 3x30s', 'Afundo alternado – 3x12', 'Flexão com apoio – 3x15', 'Corrida leve – 10 min'] }
    ],
    emagrecimento: [
        { dia: 'Dia A – Aeróbico Intervalado + Abdômen', exercicios: ['Corrida HIIT – 15 min', 'Prancha – 3x40s', 'Abdominal oblíquo – 3x20', 'Agachamento com salto – 3x15', 'Abdominal remador – 3x20'] },
        { dia: 'Dia B – Circuito Funcional Total', exercicios: ['Polichinelos – 3x40', 'Flexão – 3x15', 'Afundo – 3x12', 'Mountain climbers – 3x30s', 'Tríceps banco – 3x15', 'Corrida leve – 10 min'] },
        { dia: 'Dia C – Musculação para definição', exercicios: ['Agachamento – 3x15', 'Remada – 3x15', 'Leg Press – 3x12', 'Desenvolvimento de ombro – 3x15', 'Rosca direta – 3x15', 'Bicicleta – 15 min'] }
    ],
    hipertrofia: [
        { dia: 'Dia A – Peito e Tríceps', exercicios: ['Supino reto – 4x10', 'Supino inclinado – 3x12', 'Crucifixo – 3x12', 'Tríceps testa – 4x10', 'Tríceps corda – 3x12', 'Flexão diamante – 3x15'] },
        { dia: 'Dia B – Costas e Bíceps', exercicios: ['Barra fixa – 4x8', 'Remada – 4x10', 'Puxada frontal – 3x12', 'Rosca direta – 4x10', 'Rosca martelo – 3x12', 'Rosca concentrada – 3x12'] },
        { dia: 'Dia C – Pernas e Ombros', exercicios: ['Agachamento – 4x10', 'Leg press – 4x12', 'Extensora – 3x12', 'Elevação lateral – 4x12', 'Desenvolvimento militar – 4x10', 'Encolhimento – 3x15'] }
    ]
};

function mostrarExercicios() {
    const tipo = document.getElementById('tipoTreino').value;
    const container = document.getElementById('exerciciosContainer');
    const descricaoInput = document.getElementById('descricaoTreino');
    const tituloInput = document.getElementById('tituloTreino');
    const btnSalvar = document.getElementById('btnSalvar');

    container.innerHTML = '';
    descricaoInput.value = '';
    tituloInput.value = '';
    btnSalvar.disabled = true;

    if (treinosPredefinidos[tipo]) {
        let html = '<div class="exercises">';
        let descricao = `Treino: ${tipo.toUpperCase()}\n\n`;
        let titulo = tipo.charAt(0).toUpperCase() + tipo.slice(1);

        treinosPredefinidos[tipo].forEach(dia => {
            html += `<div class="exercise"><h3>${dia.dia}</h3><ul>`;
            descricao += `${dia.dia}:\n`;
            dia.exercicios.forEach(ex => {
                html += `<li>${ex}</li>`;
                descricao += `- ${ex}\n`;
            });
            html += '</ul></div>\n';
            descricao += '\n';
        });

        html += '</div>';
        container.innerHTML = html;
        descricaoInput.value = descricao.trim();
        tituloInput.value = titulo;
        btnSalvar.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const tipo = document.getElementById('tipoTreino').value;
    if (tipo) mostrarExercicios();
});
</script>
</body>
</html>
