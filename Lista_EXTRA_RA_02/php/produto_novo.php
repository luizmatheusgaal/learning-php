<?php
header('Content-Type: application/json');

$retorno = [
    'status' => 'nok',
    'mensagem' => '',
    'data' => []
];

$nome = $_POST['nome'] ?? '';
$preco = $_POST['preco'] ?? '';

if (empty($nome) || $preco === '') {
    $retorno['mensagem'] = 'Nome e preco sao obrigatorios.';
    echo json_encode($retorno);
    exit;
}

include_once 'conexao.php';

$preco = (float)str_replace(',', '.', $preco);
$estoque = isset($_POST['estoque']) ? (int)$_POST['estoque'] : 0;
$ativo = isset($_POST['ativo']) ? (int)$_POST['ativo'] : 1;

$sql = "INSERT INTO produto (nome, preco, estoque, ativo) VALUES (?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('sdii', $nome, $preco, $estoque, $ativo);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $retorno['status'] = 'ok';
    $retorno['mensagem'] = 'Produto cadastrado com sucesso.';
} else {
    $retorno['mensagem'] = 'Falha ao cadastrar o produto.';
}

$stmt->close();
$conexao->close();

echo json_encode($retorno);
