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

include_once 'conexao.php';

$id = (int)$_GET['id'];

$sql = "SELECT id, nome, preco, estoque FROM produto WHERE id = ? AND ativo = 1";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($linha = $resultado->fetch_assoc()) {
    $retorno['status'] = 'ok';
    $retorno['mensagem'] = 'Consulta efetuada.';
    $retorno['data'] = [
        [
            'id' => (int)$linha['id'],
            'nome' => $linha['nome'],
            'preco' => (float)$linha['preco'],
            'estoque' => (int)$linha['estoque']
        ]
    ];
} else {
    $retorno['mensagem'] = 'Produto nao encontrado.';
}

$stmt->close();
$conexao->close();

echo json_encode($retorno);
