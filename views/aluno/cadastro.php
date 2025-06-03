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
    <title>Cadastro</title>
    <link rel="stylesheet" href="../../assets/css/style.css" />
</head>
<body>

<header>
  <div class="header-content">
    <h1>Cadastro de Aluno</h1>
  </div>
</header>

<main>
  <div class="form-container">

    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'email'): ?>
      <p class="error">E-mail já cadastrado.</p>
    <?php endif; ?>

    <form method="post" action="../../controllers/AlunoController.php" onsubmit="return validarSenha();">
      <input type="hidden" name="acao" value="cadastrar" />

      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" required autocomplete="off">

      <label for="email">E-mail:</label>
      <input type="email" id="email" name="email" required autocomplete="off">

      <label for="telefone">Telefone:</label>
      <input type="tel" id="telefone" name="telefone" maxlength="15" required oninput="formatTelefone(this);">

      <label for="cpf">CPF:</label>
      <input type="text" id="cpf" name="cpf" maxlength="14" required oninput="formatCPF(this);">

      <label for="data_nascimento">Data de Nascimento:</label>
      <input type="date" id="data_nascimento" name="data_nascimento" required>

      <label for="genero">Gênero:</label>
      <select id="genero" name="genero" required>
        <option value="">Selecione</option>
        <option value="masculino">Masculino</option>
        <option value="feminino">Feminino</option>
        <option value="outro">Outro</option>
      </select>

      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" required minlength="6">

      <label for="confirmar_senha">Confirmar Senha:</label>
      <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">

      <button type="submit">Cadastrar</button>
    </form>

    <p>Já tem conta? <a href="login.php">Faça login</a></p>

  </div>
</main>

<footer>
  © 2025 Academia
</footer>

<script>
  function formatTelefone(input) {
      let value = input.value.replace(/\D/g, '');
      if (value.length > 10) {
          value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
      } else if (value.length > 5) {
          value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
      } else if (value.length > 2) {
          value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
      } else {
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

  function formatarAltura(input) {
      input.value = input.value.replace(/\D/g, '');
      if (input.value.length > 3) {
          input.value = input.value.slice(0, 3);
      }
  }

  function validarSenha() {
    const senha = document.getElementById('senha').value;
    const confirmar = document.getElementById('confirmar_senha').value;
    if (senha !== confirmar) {
      alert('As senhas não coincidem!');
      return false;
    }
    return true;
  }
</script>

</body>
</html>
