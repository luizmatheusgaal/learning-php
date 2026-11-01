<?php
header('Content-Type: application/json');

$retorno = [
    'status' => 'nok',
    'mensagem' => '',
    'data' => []
];

$termo = $_GET['termo'] ?? '';

if (empty($termo)) {
    $retorno['mensagem'] = 'Informe um termo de busca.';
    echo json_encode($retorno);
    exit;
}

$somenteEstoque = isset($_GET['somente_estoque']) && $_GET['somente_estoque'] == '1';

include_once 'conexao.php';

$busca = '%' . $termo . '%';

if ($somenteEstoque) {
    $sql = "SELECT id, nome, preco, estoque FROM produto WHERE ativo = 1 AND nome LIKE ? AND estoque > 0 ORDER BY nome ASC";
} else {
    $sql = "SELECT id, nome, preco, estoque FROM produto WHERE ativo = 1 AND nome LIKE ? ORDER BY nome ASC";
}

$stmt = $conexao->prepare($sql);
$stmt->bind_param('s', $busca);
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
    $retorno['mensagem'] = 'Nenhum produto encontrado.';
    $retorno['data'] = [];
}

$stmt->close();
$conexao->close();

echo json_encode($retorno);
