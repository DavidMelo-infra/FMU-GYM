<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (!isset($_SESSION['aluno_id'])) {
    header("Location: login.php");
    exit();
}

$aluno_id = $_SESSION['aluno_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    if ($_POST['tipo'] === 'cancelar') {
        $stmt = $pdo->prepare("UPDATE planos SET ativo = 0 WHERE aluno_id = ?");
        $stmt->execute([$aluno_id]);
        header("Location: area_aluno.php");
        exit();
    } else {
        $tipo = $_POST['tipo'];
        $data_inicio = date('Y-m-d');
        $pagamento = $_POST['pagamento'] ?? null;

        $cartao_nome = $_POST['cartao_nome'] ?? null;
        $cartao_num = $_POST['cartao_num'] ?? null;
        $cartao_validade = $_POST['cartao_validade'] ?? null;
        $cartao_cvv = $_POST['cartao_cvv'] ?? null;

        if ($tipo === 'experimental') {
            $pagamento = 'nenhum';
            $cartao_nome = $cartao_num = $cartao_validade = $cartao_cvv = null;
        }

        // Desativa plano atual
        $stmt = $pdo->prepare("UPDATE planos SET ativo = 0 WHERE aluno_id = ?");
        $stmt->execute([$aluno_id]);

        // Insere novo plano
        $stmt = $pdo->prepare("
            INSERT INTO planos 
            (aluno_id, tipo, pagamento, data_inicio, ativo, cartao_nome, cartao_num, cartao_validade, cartao_cvv)
            VALUES (?, ?, ?, ?, 1, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $aluno_id,
            $tipo,
            $pagamento,
            $data_inicio,
            $cartao_nome,
            $cartao_num,
            $cartao_validade,
            $cartao_cvv
        ]);

        header("Location: plano_sucesso.php");
        exit();
    }
}

// Busca plano ativo
$stmt = $pdo->prepare("SELECT * FROM planos WHERE aluno_id = ? AND ativo = 1 ORDER BY data_inicio DESC LIMIT 1");
$stmt->execute([$aluno_id]);
$plano = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Plano</title>
    <link rel="stylesheet" href="../../assets/css/style.css" />
    <style>
        .radio-group {
            display: flex;
            gap: 20px;
            margin: 10px 0;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <header class="header">Sistema Academia</header>

    <h2>Plano Atual</h2>
    <?php if ($plano): ?>
        <p><strong>Tipo:</strong> <?= htmlspecialchars($plano['tipo']) ?></p>
        <?php if ($plano['tipo'] === 'experimental'): ?>
            <p><strong>Dias Restantes:</strong> <?= calcularDiasRestantes('experimental', $plano['data_inicio']) ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="tipo" value="cancelar" />
            <button type="submit">Cancelar Plano</button>
        </form>
    <?php else: ?>
        <p>Você não possui plano ativo.</p>
        <form method="post" id="formPlano">
            <label>Escolha o tipo de plano:</label>
            <div class="radio-group">
                <label><input type="radio" name="tipo" value="mensal" required><span>Mensal</span></label>
                <label><input type="radio" name="tipo" value="trimestral"><span>Trimestral</span></label>
                <label><input type="radio" name="tipo" value="anual"><span>Anual</span></label>
                <label><input type="radio" name="tipo" value="experimental"><span>Experimental</span></label>
            </div>

            <div id="pagamentoContainer">
                <label>Método de pagamento:</label>
                <div class="radio-group">
                    <label><input type="radio" name="pagamento" value="pix"><span>PIX</span></label>
                    <label><input type="radio" name="pagamento" value="cartao"><span>Cartão</span></label>
                </div>
            </div>

            <div id="qrPix" style="display: none;">
                <p>Escaneie o QR Code abaixo:</p>
                <img src="../../assets/imagens/qrcode-exemplo.png" alt="QR Code PIX" width="200">
            </div>

            <div id="cartaoForm" style="display: none;">
                <label>Nome no cartão:</label>
                <input type="text" name="cartao_nome"><br>

                <label>Número do cartão (16 dígitos):</label>
                <input type="text" name="cartao_num" maxlength="16"><br>

                <label>Validade:</label>
                <input type="date" name="cartao_validade"><br>

                <label>CVV (3 dígitos):</label>
                <input type="text" name="cartao_cvv" maxlength="3">
            </div>

            <button type="submit" style="margin-top: 15px;">Confirmar Plano</button>
        </form>
    <?php endif; ?>
</div>

<script>
    const tipoRadios = document.querySelectorAll('input[name="tipo"]');
    const pagamentoContainer = document.getElementById('pagamentoContainer');
    const qrPix = document.getElementById('qrPix');
    const cartaoForm = document.getElementById('cartaoForm');
    const pagamentoRadios = document.querySelectorAll('input[name="pagamento"]');
    const formPlano = document.getElementById('formPlano');

    function resetPagamento() {
        pagamentoRadios.forEach(radio => radio.checked = false);
        qrPix.style.display = 'none';
        cartaoForm.style.display = 'none';
    }

    tipoRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'experimental') {
                pagamentoContainer.style.display = 'none';
                resetPagamento();
            } else {
                pagamentoContainer.style.display = 'block';
            }
        });
    });

    pagamentoRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'pix') {
                qrPix.style.display = 'block';
                cartaoForm.style.display = 'none';
            } else if (radio.value === 'cartao') {
                qrPix.style.display = 'none';
                cartaoForm.style.display = 'block';
            }
        });
    });

    formPlano.addEventListener('submit', function (e) {
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

        if (pagamento === 'cartao') {
            const nome = formPlano.cartao_nome.value.trim();
            const num = formPlano.cartao_num.value.trim();
            const validade = formPlano.cartao_validade.value;
            const cvv = formPlano.cartao_cvv.value.trim();

            if (!nome || !num.match(/^\d{16}$/) || !validade || !cvv.match(/^\d{3}$/)) {
                alert('Preencha corretamente os dados do cartão.');
                e.preventDefault();
            }
        }
    });

    window.addEventListener('load', () => {
        const tipoSelecionado = document.querySelector('input[name="tipo"]:checked');
        if (tipoSelecionado && tipoSelecionado.value === 'experimental') {
            pagamentoContainer.style.display = 'none';
        }
    });
</script>
</body>
</html>
