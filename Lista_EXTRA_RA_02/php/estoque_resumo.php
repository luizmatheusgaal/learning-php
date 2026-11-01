<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'nok',
        'mensagem' => 'Acesso negado. Faca login.',
        'data' => []
    ]);
    exit;
}

header('Content-Type: application/json');

$retorno = [
    'status' => 'ok',
    'mensagem' => 'Consulta efetuada.',
    'data' => []
];

include_once 'conexao.php';

$sql = "SELECT 
            COUNT(*) AS total_produtos,
            COALESCE(SUM(estoque), 0) AS total_unidades,
            COALESCE(SUM(preco * estoque), 0) AS valor_estoque
        FROM produto
        WHERE ativo = 1";

$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();

if ($linha = $resultado->fetch_assoc()) {
    $retorno['data'] = [
        [
            'total_produtos' => (int)$linha['total_produtos'],
            'total_unidades' => (int)$linha['total_unidades'],
            'valor_estoque' => (float)$linha['valor_estoque']
        ]
    ];
} else {
    $retorno['data'] = [
        [
            'total_produtos' => 0,
            'total_unidades' => 0,
            'valor_estoque' => 0
        ]
    ];
}

$stmt->close();
$conexao->close();

echo json_encode($retorno);
