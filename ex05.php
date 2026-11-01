<?php
$retorno = [
    'status' => 'ok',
    'mensagem' => '',
    'data' => []
];

if (isset($_GET['id'])) {
    $retorno['mensagem'] = 'Buscando o registro ' . (int)$_GET['id'];
} else {
    $retorno['mensagem'] = 'Nenhum ID informado';
}

echo json_encode($retorno);