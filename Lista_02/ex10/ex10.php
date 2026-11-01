<?php
/*
Exercício 10 (consumindo o JSON). Crie ex10.php, que devolve um $retorno padrão com status = 'ok' e uma lista de 3 produtos (id, nome, preco) em data.
*/
$products = [
    [
        'id' => 1,
        'nome' => 'Produto 1',
        'preco' => 10.00
    ],
    [
        'id' => 2,
        'nome' => 'Produto 2',
        'preco' => 20.00
    ],
    [
        'id' => 3,
        'nome' => 'Produto 3',
        'preco' => 30.00
    ]
];

$retorno = [
    'status' => 'ok',
    'mensagem' => 'Produtos encontrados',
    'data' => $products
];

if (rand(0, 1) == 1) {
    $retorno['status'] = 'nok';
    $retorno['mensagem'] = 'Erro ao buscar produtos';
    $retorno['data'] = [];
}

header('Content-Type: application/json');
echo json_encode($retorno);
