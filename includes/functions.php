<?php
function redirecionar($url) {
    header("Location: $url");
    exit();
}

function calcularDiasRestantes($tipo, $data_inicio) {
    $hoje = new DateTime();
    $inicio = new DateTime($data_inicio);

    switch ($tipo) {
        case 'mensal':
            $fim = clone $inicio;
            $fim->modify('+1 month');
            break;
        case 'trimestral':
            $fim = clone $inicio;
            $fim->modify('+3 months');
            break;
        case 'trimestral':
            $fim = clone $inicio;
            $fim->modify('+12 months');
            break;
            
        case 'experimental':
            $fim = clone $inicio;
            $fim->modify('+15 days');
            break;
        default:
            return 0;
    }

    $dias = $hoje->diff($fim)->days;
    return ($fim >= $hoje) ? $dias : 0;
}

function verificarPlanoAtivo($pdo, $aluno_id) {
    $stmt = $pdo->prepare("SELECT * FROM planos WHERE aluno_id = ? AND ativo = 1 ORDER BY data_inicio DESC LIMIT 1");
    $stmt->execute([$aluno_id]);
    $plano = $stmt->fetch();

    if (!$plano) {
        return false;
    }

    if ($plano['tipo'] === 'experimental') {
        $data_inicio = new DateTime($plano['data_inicio']);
        $hoje = new DateTime();
        $intervalo = $data_inicio->diff($hoje)->days;
        return $intervalo <= 15;
    }

    return true;
}
?>