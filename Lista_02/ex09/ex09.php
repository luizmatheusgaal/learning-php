<?php
/*
Exercício 9 (retorno de falha). Crie ex09.php, que recebe nome por $_POST (ou $_GET, para facilitar o teste). 
Se o nome estiver vazio, devolva status = 'nok' e mensagem = 'Nome obrigatorio.'; caso contrário, devolva status = 'ok' e mensagem = 'Registro recebido.'.
Em ambos os casos, o retorno deve seguir o mesmo formato JSON. 
*/

$nome = $_POST['nome'] ?? $_GET['nome'] ?? '';
$retorno = [
    'status' => 'ok',
    'mensagem' => 'Registro recebido.'
];

if (empty($nome)) {
    $retorno['status'] = 'nok';
    $retorno['mensagem'] = 'Nome obrigatorio.';
}

header('Content-Type: application/json');
echo json_encode($retorno);
