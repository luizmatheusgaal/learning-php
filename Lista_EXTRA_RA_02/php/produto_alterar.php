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
$nome = $_POST['nome'] ?? '';
$preco = (float)str_replace(',', '.', $_POST['preco'] ?? '0');
$ativo = isset($_POST['ativo']) ? (int)$_POST['ativo'] : 1;

if ($preco <= 0) {
    $retorno['mensagem'] = 'Preco invalido.';
    echo json_encode($retorno);
    exit;
}

include_once 'conexao.php';

// Consulta previa para verificar se o id existe
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

$sqlUpdate = "UPDATE produto SET nome = ?, preco = ?, ativo = ? WHERE id = ?";
$stmtUpdate = $conexao->prepare($sqlUpdate);
$stmtUpdate->bind_param('sdii', $nome, $preco, $ativo, $id);
if ($stmtUpdate->execute()) {
    $retorno['status'] = 'ok';
    $retorno['mensagem'] = 'Produto alterado com sucesso.';
} else {
    $retorno['mensagem'] = 'Falha ao alterar o produto.';
}

$stmtUpdate->close();
$conexao->close();

echo json_encode($retorno);
