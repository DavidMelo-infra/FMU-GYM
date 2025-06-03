<?php
session_start();
require_once '../../includes/db.php';


if (!isset($_SESSION['aluno_id'])) {
    header("Location: /FMU-GYM/views/aluno/login.php"); 
    exit();
}

$aluno_id = $_SESSION['aluno_id'];
$mensagem = "";
$mensagem_tipo = "";

$stmt_fetch = $pdo->prepare("SELECT nome, email, cpf, telefone, data_nascimento, genero FROM alunos WHERE id = ?");
$stmt_fetch->execute([$aluno_id]);
$aluno = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    die("Erro: Aluno não encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cpf = trim($_POST['cpf'] ?? $aluno['cpf']);
    $telefone = trim($_POST['telefone'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($nome) || empty($email) || empty($cpf) || empty($telefone) || empty($data_nascimento) || empty($genero)) {
        $mensagem = "Por favor, preencha todos os campos obrigatórios.";
        $mensagem_tipo = "erro";
    } else {
        $atualizar_dados = true;
        if ($email !== $aluno['email']) {
            $stmt_check_email = $pdo->prepare("SELECT id FROM alunos WHERE email = ? AND id != ?");
            $stmt_check_email->execute([$email, $aluno_id]);
            if ($stmt_check_email->fetch()) {
                $mensagem = "Este e-mail já está em uso por outro usuário.";
                $mensagem_tipo = "erro";
                $atualizar_dados = false;
            }
        }
        if ($cpf !== $aluno['cpf']) {
            $stmt_check_cpf = $pdo->prepare("SELECT id FROM alunos WHERE cpf = ? AND id != ?");
            $stmt_check_cpf->execute([$cpf, $aluno_id]);
            if ($stmt_check_cpf->fetch()) {
                $mensagem = "Este CPF já está em uso por outro usuário.";
                $mensagem_tipo = "erro";
                $atualizar_dados = false;
            }
        }

        if ($atualizar_dados) {
            $params = [$nome, $email, $cpf, $telefone, $data_nascimento, $genero];
            $sql_update = "UPDATE alunos SET nome = ?, email = ?, cpf = ?, telefone = ?, data_nascimento = ?, genero = ?";

            if (!empty($senha)) {
                if (strlen($senha) < 6) {
                     $mensagem = "A nova senha deve ter pelo menos 6 caracteres.";
                     $mensagem_tipo = "erro";
                     $atualizar_dados = false;
                } else {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $sql_update .= ", senha = ?";
                    $params[] = $senha_hash;
                }
            }
            
            if($atualizar_dados){
                $sql_update .= " WHERE id = ?";
                $params[] = $aluno_id;
                
                $stmt_update = $pdo->prepare($sql_update);
                if ($stmt_update->execute($params)) {
                    $mensagem = "Dados atualizados com sucesso!";
                    $mensagem_tipo = "sucesso";
                    $stmt_fetch->execute([$aluno_id]); 
                    $aluno = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
                } else {
                    $mensagem = "Erro ao atualizar os dados. Tente novamente.";
                    $mensagem_tipo = "erro";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - FMU FIT</title>
    <link rel="stylesheet" href="../../assets/css/style-cadastro.css">
    <style>
        .feedback-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }
        .feedback-message.sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .feedback-message.erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-actions { 
            text-align: center;
            margin-top: 25px;
        }
        .form-actions a {
            text-decoration: none;
            color: #d3220e; 
            font-weight: bold;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.2s ease;
        }
        .form-actions a:hover {
            background-color: #f0f0f0;
            text-decoration: none;
        }
        .form-container .form-title-h2 { 
            text-align: center;
            color: #d3220e; 
            font-size: 2em; 
            margin-bottom: 25px;
        }
        .password-instruction {
            display: block; 
            margin-top: -5px; 
            margin-bottom: 15px; 
            font-size: 0.85em; 
            color: #777;
        }
        
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            background-color: #f4f4f4; 
            font-family: Arial, sans-serif;
        }
        main.content-area { 
            flex-grow: 1; 
            padding: 30px 20px; 
            display: flex;
            justify-content: center;
            align-items: flex-start; 
        }
    </style>
</head>
<body>

    <header> <div class="header-content">
            <div class="textos-header">
                <h1>Editar Perfil</h1>
                <p>Atualize suas informações pessoais.</p>
            </div>
            <img src="/FMU-GYM/assets/images/FMUFIT.png" alt="Logo FMU FIT" class="header-img"> </div>
    </header>

    <main class="content-area"><section class="form-container" style="max-width: 550px; margin-left: auto; margin-right: auto;"> <?php if (!empty($mensagem)): ?>
                <p class="feedback-message <?= htmlspecialchars($mensagem_tipo) ?>"><?= htmlspecialchars($mensagem) ?></p>
            <?php endif; ?>

            <form method="post" action="editar_perfil.php"> 
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($aluno['nome'] ?? '') ?>" required autocomplete="name">

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($aluno['email'] ?? '') ?>" required autocomplete="email">

                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($aluno['cpf'] ?? '') ?>" required autocomplete="off" placeholder="000.000.000-00" maxlength="14" oninput="formatCPF(this);">
                
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($aluno['telefone'] ?? '') ?>" required autocomplete="off" placeholder="(00) 00000-0000" maxlength="15" oninput="formatTelefone(this);">
                
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($aluno['data_nascimento'] ?? '') ?>" required autocomplete="bday">

                <label for="genero">Gênero:</label>
                <select id="genero" name="genero" required>
                    <option value="">Selecione</option>
                    <option value="masculino" <?= (($aluno['genero'] ?? '') === 'masculino' || ($aluno['genero'] ?? '') === 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                    <option value="feminino" <?= (($aluno['genero'] ?? '') === 'feminino' || ($aluno['genero'] ?? '') === 'Feminino') ? 'selected' : '' ?>>Feminino</option>
                    <option value="outro" <?= (($aluno['genero'] ?? '') === 'outro' || ($aluno['genero'] ?? '') === 'Outro') ? 'selected' : '' ?>>Outro</option>
                </select>

                <label for="senha">Nova Senha (opcional - mínimo 6 caracteres):</label>
                <input type="password" id="senha" name="senha" minlength="6" autocomplete="new-password">
 

                <button type="submit">Salvar Alterações</button>
            </form>

            <div class="form-actions">
                <a href="area_aluno.php">Voltar para Área do Aluno</a>
            </div>
        </section>
    </main>

    <footer> <p>&copy; <?= date('Y') ?> FMU FIT - Todos os direitos reservados.</p>
    </footer>

<script>
  function formatTelefone(input) {
      let value = input.value.replace(/\D/g, '');
      if (value.length > 10) { 
          value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
      } else if (value.length > 6) { 
          value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
      } else if (value.length > 2) { 
          value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
      } else if (value.length > 0) { 
          value = value.replace(/^(\d*)/, '($1');
      }
      input.value = value;
  }

  function formatCPF(input) {
      let value = input.value.replace(/\D/g, '');
      if (value.length > 9) {
          value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2}).*/, '$1.$2.$3-$4');
      } else if (value.length > 6) {
          value = value.replace(/^(\d{3})(\d{3})(\d{0,3}).*/, '$1.$2.$3');
      } else if (value.length > 3) {
          value = value.replace(/^(\d{3})(\d{0,3})/, '$1.$2');
      }
      input.value = value;
  }
  document.addEventListener('DOMContentLoaded', function() {
    const telefoneInput = document.getElementById('telefone');
    if(telefoneInput && telefoneInput.value) {
        formatTelefone(telefoneInput);
    }
    const cpfInput = document.getElementById('cpf');
    if(cpfInput && cpfInput.value) {
        formatCPF(cpfInput);
    }
  });
</script>

</body>
</html>