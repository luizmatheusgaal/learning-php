<?php
header('Content-Type: application/json');

$retorno = [
    'status' => 'nok',
    'mensagem' => '',
    'data' => []
];

if (!isset($_GET['id']) || $_GET['id'] === '') {
    $retorno['mensagem'] = 'ID nao informado.';
    echo json_encode($retorno);
    exit;
}

$id = (int)$_GET['id'];
$tipo = $_POST['tipo'] ?? '';
$quantidade = (int)($_POST['quantidade'] ?? 0);

if (($tipo !== 'entrada' && $tipo !== 'saida') || $quantidade <= 0) {
    $retorno['mensagem'] = 'Dados invalidos.';
    echo json_encode($retorno);
    exit;
}

include_once 'conexao.php';

$sqlBusca = "SELECT id FROM produto WHERE id = ?";
$stmtBusca = $conexao->prepare($sqlBusca);
$stmtBusca->bind_param('i', $id);
$stmtBusca->execute();
$resultadoBusca = $stmtBusca->get_result();

if ($resultadoBusca->num_rows == 0) {
    $retorno['mensagem'] = 'Produto nao encontrado.';
    $stmtBusca->close();
    $conexao->close();
    echo json_encode($retorno);
    exit;
}
$stmtBusca->close();

if ($tipo === 'entrada') {
    $sql = "UPDATE produto SET estoque = estoque + ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('ii', $quantidade, $id);
} else {
    $sql = "UPDATE produto SET estoque = estoque - ? WHERE id = ? AND estoque >= ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('iii', $quantidade, $id, $quantidade);
}
$stmt->execute();

if ($stmt->affected_rows == 0) {
    $retorno['mensagem'] = 'Estoque insuficiente.';
    $stmt->close();
    $conexao->close();
    echo json_encode($retorno);
    exit;
}
$stmt->close();

$sqlEstoque = "SELECT estoque FROM produto WHERE id = ?";
$stmtEstoque = $conexao->prepare($sqlEstoque);
$stmtEstoque->bind_param('i', $id);
$stmtEstoque->execute();
$resultado = $stmtEstoque->get_result();
$linha = $resultado->fetch_assoc();

$retorno['status'] = 'ok';
$retorno['mensagem'] = 'Estoque atualizado.';
$retorno['data'] = [
    [
        'estoque' => (int)$linha['estoque']
    ]
];

$stmtEstoque->close();
$conexao->close();

echo json_encode($retorno);
