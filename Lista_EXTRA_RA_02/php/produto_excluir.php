<?php
header('Content-Type: application/json');

$retorno = [
    'status' => 'nok',
    'mensagem' => '',
    'data' => []
];

if (!isset($_GET['id']) || $_GET['id'] === '') {
    $retorno['mensagem'] = 'E necessario informar um ID para exclusao.';
    echo json_encode($retorno);
    exit;
}

include_once 'conexao.php';

$id = (int)$_GET['id'];

$sqlBusca = "SELECT id, estoque FROM produto WHERE id = ?";
$stmtBusca = $conexao->prepare($sqlBusca);
$stmtBusca->bind_param('i', $id);
$stmtBusca->execute();
$resultado = $stmtBusca->get_result();

if ($linha = $resultado->fetch_assoc()) {
    $estoque = (int)$linha['estoque'];

    if ($estoque > 0) {
        $retorno['mensagem'] = 'Produto com estoque nao pode ser excluido.';
    } else {
        $sqlDelete = "DELETE FROM produto WHERE id = ?";
        $stmtDelete = $conexao->prepare($sqlDelete);
        $stmtDelete->bind_param('i', $id);
        $stmtDelete->execute();

        $retorno['status'] = 'ok';
        $retorno['mensagem'] = 'Produto excluido.';
        $stmtDelete->close();
    }
} else {
    $retorno['mensagem'] = 'Produto nao encontrado.';
}

$stmtBusca->close();
$conexao->close();

echo json_encode($retorno);
