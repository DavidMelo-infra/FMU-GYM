<?php
session_start();

if (!isset($_SESSION['aluno_id'])) {
    header("Location: login.php"); 
    exit();
}

require_once '../../includes/db.php';

$aluno_id = $_SESSION['aluno_id'];


$stmt_aluno = $pdo->prepare("SELECT nome FROM alunos WHERE id = ?");
$stmt_aluno->execute([$aluno_id]);
$aluno = $stmt_aluno->fetch(PDO::FETCH_ASSOC);

$nome_aluno_formatado = $aluno ? htmlspecialchars(explode(' ', trim($aluno['nome']))[0]) : 'Aluno';


$stmt_treinos_salvos = $pdo->prepare("SELECT * FROM treinos WHERE aluno_id = ? ORDER BY id DESC");
$stmt_treinos_salvos->execute([$aluno_id]);
$treinos_salvos = $stmt_treinos_salvos->fetchAll(PDO::FETCH_ASSOC);


$mensagem_sucesso = '';
if (!empty($_SESSION['mensagem_sucesso'])) {
    $mensagem_sucesso = $_SESSION['mensagem_sucesso'];
    unset($_SESSION['mensagem_sucesso']);
} elseif (isset($_GET['msg']) && $_GET['msg'] === 'sucesso') {
    $mensagem_sucesso = "Treino salvo com sucesso!";
} elseif (isset($_GET['s']) && $_GET['s'] === 'ok') { 
    $mensagem_sucesso = "Treino salvo com sucesso!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel de Treinos - FMU FIT</title>
    <link rel="stylesheet" href="../../assets/css/styles-seletor.css">
</head>
<body>

    <header>
      <div class="header-content">
        <div class="textos-header">
          <h1>Painel de Treinos</h1>
          <p>Escolha seu objetivo, visualize e salve seu treino personalizado!</p>
        </div>
        <img src="../../assets/images/FMUFIT.png" alt="Logo FMU FIT" class="header-img"> </div>
    </header>

    <div class="page-container"> <?php if (!empty($mensagem_sucesso)): ?>
            <div class="success-message"><?= htmlspecialchars($mensagem_sucesso) ?></div>
        <?php endif; ?>

        <div class="selector">
            <p style="font-size: 1.5em; color: #333; margin-bottom:10px; text-align: center;"> <strong><?= $nome_aluno_formatado ?></strong>!
            </p>
            <label for="tipoTreino" class="frase-vermelha" style="display: block; text-align: center; margin-bottom: 10px;">
              Qual seu objetivo hoje? Escolha abaixo para ver os detalhes do treino!
            </label>

            <form method="post" action="../../controllers/AlunoController.php" id="formTreino">
                <input type="hidden" name="acao" value="salvar_treino">
                <input type="hidden" name="descricao" id="descricaoTreino">
                <input type="hidden" name="titulo" id="tituloTreino">

                <select name="tipo" id="tipoTreino" required onchange="mostrarExercicios()">
                    <option value="">-- Selecione seu Objetivo --</option>
                    <option value="condicionamento">Condicionamento</option>
                    <option value="emagrecimento">Emagrecimento</option>
                    <option value="hipertrofia">Hipertrofia</option>
                </select>
                
                <div id="exerciciosContainer" class="exercises" style="margin-top: 20px;"></div>
                
                <button type="submit" id="btnSalvar" disabled>Salvar Treino Visualizado</button>
            </form>
        </div>

        <div class="gif-container">
            <img src="../../assets/images/fitness_animation_red_strong.gif" alt="GIF animado de treino" style="width:180px;"> </div>

        <hr style="margin: 40px 0;"/>

        <?php if (!empty($treinos_salvos)): ?>
            <section class="saved-workouts-container">
                <h3>Seus Treinos Salvos</h3>
                <?php foreach ($treinos_salvos as $treino): ?>
                    <div class="saved-workout-item">
                        <h4>Treino de <?= htmlspecialchars(ucfirst($treino['titulo'])) ?></h4>
                        <pre><?= htmlspecialchars($treino['descricao']) ?></pre>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <p style="text-align:center; margin-top:20px;">Você ainda não possui treinos salvos.</p>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px; margin-bottom: 20px;">
            <a href="area_aluno.php" style="text-decoration:none; background-color: #555; color:white; padding: 12px 25px; border-radius:8px; font-size:16px;">Voltar para Área do Aluno</a>
        </div>

    </div> <footer>
      <p>&copy; <?= date('Y') ?> FMU FIT - Todos os direitos reservados.</p>
    </footer>

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
    


    if (tipo && treinosPredefinidos[tipo]) {
        let htmlGerado = ''; 
        let descricaoTextoCompleto = ''; 
        
        tituloInput.value = tipo; 

        treinosPredefinidos[tipo].forEach(dia => {
            htmlGerado += `<div class="exercise"><h3>${dia.dia}</h3><ul>`; 
            descricaoTextoCompleto += `${dia.dia}:\n`;
            dia.exercicios.forEach(ex => {
                htmlGerado += `<li>${ex}</li>`;
                descricaoTextoCompleto += `- ${ex}\n`;
            });
            htmlGerado += '</ul></div>';
            descricaoTextoCompleto += '\n';
        });
        
        container.innerHTML = htmlGerado; 
        descricaoInput.value = descricaoTextoCompleto.trim();
        btnSalvar.disabled = false; 
    
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const btnSalvar = document.getElementById('btnSalvar');
    if(btnSalvar) btnSalvar.disabled = true; 
});
</script>

</body>
</html>