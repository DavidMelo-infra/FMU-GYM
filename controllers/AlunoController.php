<?php
session_start();

require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../models/Aluno.php';

$alunoModel = new Aluno($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['acao'])) {

        switch ($_POST['acao']) {

            case 'login':
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $senha = $_POST['senha'];

                $aluno = $alunoModel->buscarPorEmail($email);

                if ($aluno && password_verify($senha, $aluno['senha'])) {
                    $_SESSION['aluno_id'] = $aluno['id'];
                    redirecionar('../views/aluno/area_aluno.php');
                } else {
                    redirecionar('../views/aluno/login.php?erro=1');
                }
                break;

            case 'cadastrar':
                $nome = $_POST['nome'];
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $telefone = $_POST['telefone'];
                $cpf = $_POST['cpf'];
                $data_nascimento = $_POST['data_nascimento'];
                $genero = $_POST['genero'];
                $senha = $_POST['senha'];
                $confirmar_senha = $_POST['confirmar_senha'];

                // Validação simples de senha
                if ($senha !== $confirmar_senha) {
                    redirecionar('../views/aluno/cadastro.php?erro=senha');
                    exit();
                }

                // Verifica se email já está cadastrado
                if ($alunoModel->buscarPorEmail($email)) {
                    redirecionar('../views/aluno/cadastro.php?erro=email');
                    exit();
                }

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                // Cadastra aluno com todos os dados
                $alunoModel->cadastrar($nome, $email, $telefone, $cpf, $data_nascimento, $genero, $senhaHash);

                redirecionar('../views/aluno/login.php?cadastro=ok');
                break;

            case 'editar':
                if (!isset($_SESSION['aluno_id'])) {
                    redirecionar('../views/aluno/login.php');
                    exit();
                }

                $aluno_id = $_SESSION['aluno_id'];
                $nome = $_POST['nome'];
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

                if (!empty($_POST['senha'])) {
                    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                    $alunoModel->atualizar($aluno_id, $nome, $email, $senhaHash);
                } else {
                    $alunoModel->atualizar($aluno_id, $nome, $email);
                }

                redirecionar('../views/aluno/editar_perfil.php?sucesso=1');
                break;

            case 'salvar_treino':
                if (!isset($_SESSION['aluno_id'])) {
                    redirecionar('../views/aluno/login.php');
                    exit();
                }

                $aluno_id = $_SESSION['aluno_id'];
                $tipo = $_POST['tipo'] ?? '';

                // Definição dos treinos predefinidos em PHP
                $treinos_predefinidos = [
                    'condicionamento' => [
                        ['dia' => 'Dia A – Cardio + Core', 'exercicios' => ['Corrida leve na esteira – 10 min', 'Burpees – 3x15', 'Prancha abdominal – 3x30s', 'Abdominal bicicleta – 3x20', 'Agachamento com salto – 3x15', 'Corrida HIIT (20s rápido / 40s lento) – 10 min']],
                        ['dia' => 'Dia B – Corpo todo funcional', 'exercicios' => ['Polichinelos – 3x30', 'Flexão de braço – 3x15', 'Agachamento com kettlebell – 3x15', 'Mountain climbers – 3x30s', 'Tríceps banco – 3x15', 'Subida em banco – 3x15 (cada perna)']],
                        ['dia' => 'Dia C – Resistência muscular', 'exercicios' => ['Remada no TRX – 3x12', 'Agachamento isométrico – 3x30s', 'Abdominal prancha lateral – 3x30s', 'Afundo alternado – 3x12', 'Flexão com apoio – 3x15', 'Corrida leve – 10 min']]
                    ],
                    'emagrecimento' => [
                        ['dia' => 'Dia A – Aeróbico Intervalado + Abdômen', 'exercicios' => ['Corrida HIIT – 15 min', 'Prancha – 3x40s', 'Abdominal oblíquo – 3x20', 'Agachamento com salto – 3x15', 'Abdominal remador – 3x20']],
                        ['dia' => 'Dia B – Circuito Funcional Total', 'exercicios' => ['Polichinelos – 3x40', 'Flexão – 3x15', 'Afundo – 3x12', 'Mountain climbers – 3x30s', 'Tríceps banco – 3x15', 'Corrida leve – 10 min']],
                        ['dia' => 'Dia C – Musculação para definição', 'exercicios' => ['Agachamento – 3x15', 'Remada – 3x15', 'Leg Press – 3x12', 'Desenvolvimento de ombro – 3x15', 'Rosca direta – 3x15', 'Bicicleta – 15 min']]
                    ],
                    'hipertrofia' => [
                        ['dia' => 'Dia A – Peito e Tríceps', 'exercicios' => ['Supino reto – 4x10', 'Supino inclinado – 3x12', 'Crucifixo – 3x12', 'Tríceps testa – 4x10', 'Tríceps corda – 3x12', 'Flexão diamante – 3x15']],
                        ['dia' => 'Dia B – Costas e Bíceps', 'exercicios' => ['Barra fixa – 4x8', 'Remada – 4x10', 'Puxada frontal – 3x12', 'Rosca direta – 4x10', 'Rosca martelo – 3x12', 'Rosca concentrada – 3x12']],
                        ['dia' => 'Dia C – Pernas e Ombros', 'exercicios' => ['Agachamento – 4x10', 'Leg press – 4x12', 'Extensora – 3x12', 'Elevação lateral – 4x12', 'Desenvolvimento militar – 4x10', 'Encolhimento – 3x15']]
                    ],
                ];

                if (!array_key_exists($tipo, $treinos_predefinidos)) {
                    die("Tipo de treino inválido.");
                }

                $descricao = "";
                foreach ($treinos_predefinidos[$tipo] as $dia) {
                    $descricao .= $dia['dia'] . "\n";
                    foreach ($dia['exercicios'] as $exercicio) {
                        $descricao .= " - $exercicio\n";
                    }
                    $descricao .= "\n";
                }

                // Inserir no banco
                $sql = "INSERT INTO treinos (aluno_id, titulo, descricao, criado_em) VALUES (?, ?, ?, NOW())";
                $stmt = $pdo->prepare($sql);

                if ($stmt->execute([$aluno_id, $tipo, $descricao])) {
                    redirecionar('../views/aluno/painel_treinos.php?msg=sucesso');
                } else {
                    echo "Erro ao salvar treino.";
                }
                break;
        }
    }
}
