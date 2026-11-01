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

$retorno = [
    'status' => 'nok',
    'mensagem' => '',
    'data' => []
];

$produto_id = (int)($_POST['produto_id'] ?? 0);
$quantidade = (int)($_POST['quantidade'] ?? 0);

if ($produto_id <= 0 || $quantidade <= 0) {
    header('Content-Type: application/json');
    $retorno['mensagem'] = 'Dados invalidos.';
    echo json_encode($retorno);
    exit;
}

$usuario_id = (int)$_SESSION['usuario_id'];

include_once 'conexao.php';

$sqlProduto = "SELECT id, preco, estoque FROM produto WHERE id = ? AND ativo = 1";
$stmtProduto = $conexao->prepare($sqlProduto);
$stmtProduto->bind_param('i', $produto_id);
$stmtProduto->execute();
$resProduto = $stmtProduto->get_result();
$produto = $resProduto->fetch_assoc();
$stmtProduto->close();

if (!$produto) {
    $retorno['mensagem'] = 'Produto nao encontrado.';
    $conexao->close();
    header('Content-Type: application/json');
    echo json_encode($retorno);
    exit;
}

$estoqueAtual = (int)$produto['estoque'];
$preco = (float)$produto['preco'];

if ($quantidade > $estoqueAtual) {
    $retorno['mensagem'] = 'Estoque insuficiente.';
    $conexao->close();
    header('Content-Type: application/json');
    echo json_encode($retorno);
    exit;
}

$valor_total = $preco * $quantidade;
$estoque_restante = $estoqueAtual - $quantidade;

$conexao->begin_transaction();

$sqlUpdate = "UPDATE produto SET estoque = estoque - ? WHERE id = ? AND estoque >= ?";
$stmtUpdate = $conexao->prepare($sqlUpdate);
$stmtUpdate->bind_param('iii', $quantidade, $produto_id, $quantidade);
$stmtUpdate->execute();

$sqlInsert = "INSERT INTO venda (produto_id, usuario_id, quantidade, valor_total) VALUES (?, ?, ?, ?)";
$stmtInsert = $conexao->prepare($sqlInsert);
$stmtInsert->bind_param('iiid', $produto_id, $usuario_id, $quantidade, $valor_total);
$stmtInsert->execute();

$venda_id = (int)$conexao->insert_id;

$stmtUpdate->close();
$stmtInsert->close();

$conexao->commit();
$conexao->close();

$retorno['status'] = 'ok';
$retorno['mensagem'] = 'Venda registrada.';
$retorno['data'] = [
    [
        'venda_id' => $venda_id,
        'valor_total' => $valor_total,
        'estoque_restante' => $estoque_restante
    ]
];

header('Content-Type: application/json');
echo json_encode($retorno);
