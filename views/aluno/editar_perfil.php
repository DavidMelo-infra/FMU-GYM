<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (!isset($_SESSION['aluno_id'])) {
    header("Location: login.php");
    exit();
}

$aluno_id = $_SESSION['aluno_id'];

// Buscar dados atuais do aluno
$stmt = $pdo->prepare("SELECT nome, email, cpf, telefone, data_nascimento, genero FROM alunos WHERE id = ?");
$stmt->execute([$aluno_id]);
$aluno = $stmt->fetch();

if (!$aluno) {
    echo "Aluno não encontrado.";
    exit();
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($nome === '' || $email === '' || $cpf === '' || $telefone === '' || $data_nascimento === '' || $genero === '') {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
    } else {
        // Verifica se e-mail já está sendo usado
        $stmt = $pdo->prepare("SELECT id FROM alunos WHERE email = ? AND id != ?");
        $stmt->execute([$email, $aluno_id]);
        if ($stmt->fetch()) {
            $mensagem = "Este e-mail já está em uso por outro usuário.";
        } else {
            // Verifica se CPF está sendo usado
            $stmt = $pdo->prepare("SELECT id FROM alunos WHERE cpf = ? AND id != ?");
            $stmt->execute([$cpf, $aluno_id]);
            if ($stmt->fetch()) {
                $mensagem = "Este CPF já está em uso por outro usuário.";
            } else {
                // Monta SQL de atualização com ou sem senha
                if ($senha !== '') {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE alunos SET nome = ?, email = ?, cpf = ?, telefone = ?, data_nascimento = ?, genero = ?, senha = ? WHERE id = ?");
                    $dados = [$nome, $email, $cpf, $telefone, $data_nascimento, $genero, $senha_hash, $aluno_id];
                } else {
                    $stmt = $pdo->prepare("UPDATE alunos SET nome = ?, email = ?, cpf = ?, telefone = ?, data_nascimento = ?, genero = ? WHERE id = ?");
                    $dados = [$nome, $email, $cpf, $telefone, $data_nascimento, $genero, $aluno_id];
                }

                if ($stmt->execute($dados)) {
                    $mensagem = "Dados atualizados com sucesso.";
                    // Atualiza array do formulário
                    $aluno['nome'] = $nome;
                    $aluno['email'] = $email;
                    $aluno['cpf'] = $cpf;
                    $aluno['telefone'] = $telefone;
                    $aluno['data_nascimento'] = $data_nascimento;
                    $aluno['genero'] = $genero;
                } else {
                    $mensagem = "Erro ao atualizar os dados.";
                }
            }
        }
    }
}

include '../../includes/header.php';
?>

<h2>Editar Perfil</h2>

<?php if ($mensagem): ?>
    <p style="color:<?= strpos($mensagem, 'sucesso') !== false ? 'green' : 'red' ?>;"><?= htmlspecialchars($mensagem) ?></p>
<?php endif; ?>

<form method="post" action="">
    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?= htmlspecialchars($aluno['nome']) ?>" required><br>

    <label>E-mail:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($aluno['email']) ?>" required><br>

    <label>CPF:</label><br>
    <input type="text" name="cpf" value="<?= htmlspecialchars($aluno['cpf']) ?>" required><br>

    <label>Telefone:</label><br>
    <input type="text" name="telefone" value="<?= htmlspecialchars($aluno['telefone']) ?>" required><br>

    <label>Data de Nascimento:</label><br>
    <input type="date" name="data_nascimento" value="<?= htmlspecialchars($aluno['data_nascimento']) ?>" required><br>

    <label>Gênero:</label><br>
    <select name="genero" required>
        <option value="">Selecione</option>
        <option value="Masculino" <?= $aluno['genero'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
        <option value="Feminino" <?= $aluno['genero'] === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
        <option value="Outro" <?= $aluno['genero'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
    </select><br>

    <label>Nova Senha (opcional):</label><br>
    <input type="password" name="senha"><br>

    <button type="submit">Salvar</button>
</form>

<p><a href="area_aluno.php">Voltar</a></p>

<?php include '../../includes/footer.php'; ?>
