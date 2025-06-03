<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php'; // Usado para calcularDiasRestantes

if (!isset($_SESSION['aluno_id'])) {
    header("Location: login.php");
    exit();
}

$aluno_id = $_SESSION['aluno_id'];
$mensagem_nome_aluno = ''; // Para a mensagem personalizada

// Buscar nome do aluno para mensagem personalizada
$stmt_nome = $pdo->prepare("SELECT nome FROM alunos WHERE id = ?");
$stmt_nome->execute([$aluno_id]);
$aluno_info = $stmt_nome->fetch(PDO::FETCH_ASSOC);
if ($aluno_info && !empty($aluno_info['nome'])) {
    $primeiro_nome = explode(' ', trim($aluno_info['nome']))[0];
    $mensagem_nome_aluno = htmlspecialchars($primeiro_nome) . ', escolha o melhor plano para você!';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lógica PHP existente para processar o POST (cancelar ou escolher plano)
    // ... (seu código PHP de processamento do POST aqui) ...
    // Exemplo simplificado do seu código original:
    if (isset($_POST['acao']) && $_POST['acao'] === 'cancelar_plano') { // Adicione um input hidden com name="acao" value="cancelar_plano" no form de cancelar
        $stmt = $pdo->prepare("UPDATE planos SET ativo = 0 WHERE aluno_id = ? AND ativo = 1");
        $stmt->execute([$aluno_id]);
        header("Location: plano.php?msg=cancelado"); // Redireciona para atualizar a página
        exit();
    } elseif (isset($_POST['tipo'])) { // Novo plano sendo escolhido
        $tipo = $_POST['tipo'];
        $data_inicio = date('Y-m-d');
        $pagamento = $_POST['pagamento'] ?? null;

        $cartao_nome = $_POST['cartao_nome'] ?? null;
        $cartao_num = $_POST['cartao_num'] ?? null;
        $cartao_validade = $_POST['cartao_validade'] ?? null; // Seu HTML usa type="date", o mockup usa type="month"
        $cartao_cvv = $_POST['cartao_cvv'] ?? null;

        if ($tipo === 'experimental') {
            $pagamento = 'nenhum'; // Conforme JS e mockup
            $cartao_nome = $cartao_num = $cartao_validade = $cartao_cvv = null;
        }

        // Desativa plano atual
        $stmt_desativa = $pdo->prepare("UPDATE planos SET ativo = 0 WHERE aluno_id = ? AND ativo = 1");
        $stmt_desativa->execute([$aluno_id]);

        // Insere novo plano
        $stmt_insere = $pdo->prepare("
            INSERT INTO planos 
            (aluno_id, tipo, pagamento, data_inicio, ativo, cartao_nome, cartao_num, cartao_validade, cartao_cvv)
            VALUES (?, ?, ?, ?, 1, ?, ?, ?, ?)
        ");
        $stmt_insere->execute([
            $aluno_id, $tipo, $pagamento, $data_inicio,
            $cartao_nome, $cartao_num, $cartao_validade, $cartao_cvv
        ]);

        // Redirecionar para uma página de sucesso ou área do aluno
        // O mockup redirecionava para 'seletor-final.html' após confirmação.
        // O seu código original redirecionava para 'plano_sucesso.php'.
        header("Location: plano_sucesso.php");
        exit();
    }
}

// Busca plano ativo para exibir
$stmt_plano_ativo = $pdo->prepare("SELECT * FROM planos WHERE aluno_id = ? AND ativo = 1 ORDER BY data_inicio DESC LIMIT 1");
$stmt_plano_ativo->execute([$aluno_id]);
$plano_ativo = $stmt_plano_ativo->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Planos e Pagamento - FMU FIT</title>
    <link rel="stylesheet" href="../../assets/css/style-cadastro.css" />
    </head>
<body>
    <header>
        <div class="header-content">
            <div class="textos-header">
                <h1>Planos e Pagamento</h1>
                <p>Escolha o plano ideal para você e a forma de pagamento.</p>
            </div>
            <img src="../../assets/images/FMUFIT.png" alt="Logo FMU FIT" class="header-img">
        </div>
    </header>

    <main>
        <?php if ($plano_ativo): ?>
            <section class="form-container" style="margin-bottom: 30px; text-align:center;"> <h2>Seu Plano Atual</h2>
                <p><strong>Tipo:</strong> <?= htmlspecialchars($plano_ativo['tipo']) ?></p>
                <p><strong>Forma de Pagamento:</strong> <?= htmlspecialchars($plano_ativo['pagamento'] ?? 'N/A') ?></p>
                <p><strong>Data de Início:</strong> <?= htmlspecialchars(date("d/m/Y", strtotime($plano_ativo['data_inicio']))) ?></p>
                <?php if ($plano_ativo['tipo'] === 'experimental' && function_exists('calcularDiasRestantes')): ?>
                    <p><strong>Dias Restantes (Experimental):</strong> <?= calcularDiasRestantes('experimental', $plano_ativo['data_inicio']) ?></p>
                <?php endif; ?>
                <form method="post" action="plano.php" style="margin-top: 15px;">
                    <input type="hidden" name="acao" value="cancelar_plano" />
                    <button type="submit" class="button-cancelar">Cancelar Plano Atual</button> </form>
            </section>
        <?php else: ?>
            <section class="pagamento-container" style="text-align: center;"> <?php if ($mensagem_nome_aluno): ?>
                    <p id="mensagem-nome" style="font-size: 1.4em; font-weight: bold; color: #b30000; margin-bottom: 20px;"><?= $mensagem_nome_aluno ?></p>
                 <?php endif; ?>
            </section>
            
            <form method="post" action="plano.php" id="formPlanoEscolha">
                <section class="planos-container">
                    <label class="plano">
                        <input type="radio" name="tipo" value="mensal" required>
                        <h2>Plano Mensal</h2>
                        <p>R$ 99,90/mês</p>
                        <p>Acesso livre à academia</p>
                        <p>Suporte de instrutor</p>
                    </label>

                    <label class="plano">
                        <input type="radio" name="tipo" value="trimestral">
                        <h2>Plano Trimestral</h2>
                        <p>R$ 269,90 (3x)</p>
                        <p>Economize R$ 30</p>
                        <p>Treino personalizado</p>
                    </label>
                    
                    <label class="plano">
                        <input type="radio" name="tipo" value="anual">
                        <h2>Plano Anual</h2>
                        <p>R$ 899,90 (12x)</p>
                        <p>Economize mais de R$200</p>
                        <p>Consultoria nutricional grátis</p>
                    </label>
                    
                    <label class="plano pulse"> <input type="radio" name="tipo" value="experimental">
                        <h2>Plano Experimental</h2>
                        <p>(Alunos e Professores) FMU</p><br>
                        <p>15 dias Grátis</p>
                    </label>
                </section>

                <section class="pagamento-container" id="formaPagamentoContainer"> <h2>Forma de Pagamento</h2>
                    <label class="pagamento-opcao">
                        <input type="radio" name="pagamento" value="pix">
                        <span>PIX (Com desconto)</span>
                    </label>
                    <label class="pagamento-opcao">
                        <input type="radio" name="pagamento" value="cartao">
                        <span>Cartão de Crédito</span>
                    </label>
                </section>

                <div id="qrcode-pix" style="display: none; text-align: center; margin-top: 20px;">
                    <p style="font-weight: bold; margin-bottom: 10px; color: #b30000;">Escaneie o QR Code para realizar o pagamento</p>
                    <img src="../../assets/images/QR.png" alt="QR Code para pagamento via PIX" style="width: 200px;">
                </div>
                
                <div id="form-cartao" style="display: none; margin-top: 20px; max-width: 400px; margin-left:auto; margin-right:auto;">
                    <label for="cartao_nome">Nome no Cartão:</label>
                    <input type="text" id="cartao_nome" name="cartao_nome">
                
                    <label for="cartao_num">Número do Cartão:</label>
                    <input type="text" id="cartao_num" name="cartao_num" maxlength="19" placeholder="0000 0000 0000 0000">
                
                    <label for="cartao_validade">Vencimento:</label> <input type="text" id="cartao_validade" name="cartao_validade" placeholder="MM/AAAA"> 
                
                    <label for="cartao_cvv">Código de Segurança (CVV):</label>
                    <input type="text" id="cartao_cvv" name="cartao_cvv" maxlength="4" placeholder="000">
                </div>
                
                <div class="confirmar" id="btnConfirmarContainer"> <button type="submit" id="btn-confirmar-pagamento">Confirmar Plano</button>
                </div>
            
                <div id="confirmar-experimental" style="display: none; text-align: center; margin-top: 20px;">
                     <button type="submit" id="btn-confirmar-experimental">Confirmar Plano Experimental</button>
                </div>
            </form>
        <?php endif; ?>

        <div class="imagem-final" style="margin-top: 30px;"> <img src="../../assets/images/icones_cartoes.jpg" alt="Formas de Pagamento">
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> FMU FIT - Todos os direitos reservados.</p>
    </footer>

    <?php if (!$plano_ativo): ?>
    <script>
        // Script adaptado do seu plano.php e do mockup pagamento.js
        const tipoRadios = document.querySelectorAll('input[name="tipo"]');
        const formaPagamentoContainer = document.getElementById('formaPagamentoContainer'); // Era pagamentoContainer no seu JS
        const qrPixDiv = document.getElementById('qrcode-pix'); // Era qrPix no seu JS
        const cartaoFormDiv = document.getElementById('form-cartao'); // Era cartaoForm no seu JS
        const pagamentoRadios = document.querySelectorAll('input[name="pagamento"]');
        
        const formPlanoEscolha = document.getElementById('formPlanoEscolha'); // Form principal de escolha
        const btnConfirmarContainer = document.getElementById('btnConfirmarContainer'); // Botão/div de confirmação normal
        const confirmarExperimentalButtonDiv = document.getElementById('confirmar-experimental'); // Botão/div de experimental

        function atualizarInterfacePagamento() {
            const tipoSelecionado = document.querySelector('input[name="tipo"]:checked');
            const pagamentoSelecionado = document.querySelector('input[name="pagamento"]:checked');

            if (tipoSelecionado && tipoSelecionado.value === 'experimental') {
                if(formaPagamentoContainer) formaPagamentoContainer.style.display = 'none';
                if(qrPixDiv) qrPixDiv.style.display = 'none';
                if(cartaoFormDiv) cartaoFormDiv.style.display = 'none';
                
                // Lógica de botões do mockup pagamento.js
                if(btnConfirmarContainer) btnConfirmarContainer.style.display = 'none';
                if(confirmarExperimentalButtonDiv) confirmarExperimentalButtonDiv.style.display = 'block';
                
                // Resetar radios de pagamento
                pagamentoRadios.forEach(radio => radio.checked = false);

            } else { // Plano não experimental ou nenhum plano selecionado ainda
                if(formaPagamentoContainer) formaPagamentoContainer.style.display = 'block';
                if(btnConfirmarContainer) btnConfirmarContainer.style.display = 'block';
                if(confirmarExperimentalButtonDiv) confirmarExperimentalButtonDiv.style.display = 'none';

                if (pagamentoSelecionado) {
                    if (pagamentoSelecionado.value === 'pix') {
                        if(qrPixDiv) qrPixDiv.style.display = 'block';
                        if(cartaoFormDiv) cartaoFormDiv.style.display = 'none';
                    } else if (pagamentoSelecionado.value === 'cartao') {
                        if(qrPixDiv) qrPixDiv.style.display = 'none';
                        if(cartaoFormDiv) cartaoFormDiv.style.display = 'block';
                    }
                } else { // Nenhum pagamento selecionado
                    if(qrPixDiv) qrPixDiv.style.display = 'none';
                    if(cartaoFormDiv) cartaoFormDiv.style.display = 'none';
                }
            }
        }

        tipoRadios.forEach(radio => {
            radio.addEventListener('change', atualizarInterfacePagamento);
        });

        pagamentoRadios.forEach(radio => {
            radio.addEventListener('change', atualizarInterfacePagamento);
        });

        // Validação do formulário do seu plano.php original (adaptada)
        if (formPlanoEscolha) {
            formPlanoEscolha.addEventListener('submit', function (e) {
                const tipo = document.querySelector('input[name="tipo"]:checked')?.value;
                const pagamento = document.querySelector('input[name="pagamento"]:checked')?.value;

                if (!tipo) {
                    alert('Selecione um tipo de plano.');
                    e.preventDefault();
                    return;
                }

                if (tipo !== 'experimental' && !pagamento) {
                    alert('Selecione um método de pagamento.');
                    e.preventDefault();
                    return;
                }

                if (pagamento === 'cartao' && tipo !== 'experimental') {
                    const nome = document.getElementById('cartao_nome').value.trim();
                    const num = document.getElementById('cartao_num').value.trim();
                    const validade = document.getElementById('cartao_validade').value.trim(); // Ajustado para text
                    const cvv = document.getElementById('cartao_cvv').value.trim();

                    if (!nome || !num.match(/^\d{16,19}$/) || !validade.match(/^(0[1-9]|1[0-2])\/\d{4}$/) || !cvv.match(/^\d{3,4}$/)) {
                        alert('Preencha corretamente os dados do cartão (Nome, Número com 16-19 dígitos, Validade MM/AAAA, CVV com 3-4 dígitos).');
                        e.preventDefault();
                    }
                }
            });
        }
        
        // Estado inicial ao carregar a página
        window.addEventListener('DOMContentLoaded', atualizarInterfacePagamento);
    </script>
    <?php endif; ?>
</body>
</html>