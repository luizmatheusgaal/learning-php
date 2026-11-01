<?php
header('Content-Type: application/json');

$retorno = [
    'status' => 'nok',
    'mensagem' => '',
    'data' => []
];

include_once 'conexao.php';

$sql = "SELECT id, nome, preco, estoque FROM produto WHERE ativo = 1 ORDER BY preco ASC";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();

$produtos = [];
while ($linha = $resultado->fetch_assoc()) {
    $produtos[] = [
        'id' => (int)$linha['id'],
        'nome' => $linha['nome'],
        'preco' => (float)$linha['preco'],
        'estoque' => (int)$linha['estoque']
    ];
}

if (!empty($produtos)) {
    $retorno['status'] = 'ok';
    $retorno['mensagem'] = 'Consulta efetuada.';
    $retorno['data'] = $produtos;
} else {
    $retorno['mensagem'] = 'Nao ha produtos disponiveis.';
    $retorno['data'] = [];
}

$stmt->close();
$conexao->close();

echo json_encode($retorno);
