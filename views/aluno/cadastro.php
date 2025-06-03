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
    <title>Cadastro de Aluno - FMU FIT</title>
    <link rel="stylesheet" href="../../assets/css/style-cadastro.css" />
    <style>
        .error-message {
            color: red;
            background-color: #ffe0e0;
            border: 1px solid red;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="textos-header">
                <h1>Cadastro</h1>
                <p>Preencha seus dados para gerar seu treino personalizado</p>
            </div>
            <img src="../../assets/images/FMUFIT.png" alt="Logo FMU FIT" class="header-img">
        </div>
    </header>

    <main>
        <section class="form-container">
            <?php if (isset($_GET['erro'])): ?>
                <?php if ($_GET['erro'] === 'email'): ?>
                    <p class="error-message">E-mail já cadastrado.</p>
                <?php elseif ($_GET['erro'] === 'senha'): ?>
                    <p class="error-message">As senhas não coincidem.</p>
                <?php else: ?>
                    <p class="error-message">Ocorreu um erro no cadastro.</p>
                <?php endif; ?>
            <?php endif; ?>

            <form method="post" action="../../controllers/AlunoController.php" onsubmit="return validarSenha();">
                <input type="hidden" name="acao" value="cadastrar" />

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required autocomplete="off">

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required autocomplete="off">

                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" placeholder="(00) 00000-0000" maxlength="15" required autocomplete="off" oninput="formatTelefone(this);">

                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14" required autocomplete="off" oninput="formatCPF(this);">

                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento" required autocomplete="off">
                
                <label for="genero">Gênero:</label>
                <select id="genero" name="genero" required>
                    <option value="">Selecione</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                    <option value="outro">Outro</option>
                </select>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required minlength="6" autocomplete="off">

                <label for="confirmar_senha">Confirmar Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6" autocomplete="off">

                <button type="submit">Cadastrar</button>
            </form>
            <p style="text-align: center; margin-top: 15px;">Já tem conta? <a href="login.php">Faça login</a></p>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> FMU FIT - Todos os direitos reservados.</p>
    </footer>

    <script>
      function formatTelefone(input) {
          let value = input.value.replace(/\D/g, '');
          if (value.length > 10) { // (XX) XXXXX-XXXX
              value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
          } else if (value.length > 6) { // (XX) XXXX-XXXX
              value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
          } else if (value.length > 2) { // (XX) XXXX
              value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
          } else if (value.length > 0) { // (X
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

      function validarSenha() {
        const senha = document.getElementById('senha').value;
        const confirmar = document.getElementById('confirmar_senha').value;
        if (senha !== confirmar) {
          alert('As senhas não coincidem!');
          document.getElementById('confirmar_senha').focus();
          return false;
        }
        if (senha.length < 6) {
          alert('A senha deve ter no mínimo 6 caracteres.');
          document.getElementById('senha').focus();
          return false;
        }
        return true;
      }

      document.addEventListener('DOMContentLoaded', function() {
        const telefoneInput = document.getElementById('telefone');
        if(telefoneInput) {
            telefoneInput.placeholder = '(00) 00000-0000';
            telefoneInput.title = 'Digite um telefone no formato (00) 00000-0000';
        }
        const cpfInput = document.getElementById('cpf');
        if(cpfInput) {
            cpfInput.placeholder = '000.000.000-00';
            cpfInput.title = 'Digite um CPF no formato 000.000.000-00';
        }
      });
    </script>
</body>
</html>