<?php
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_banco = 'projeto';

$conexao = @new mysqli($db_host, $db_user, $db_pass, $db_banco);
if ($conexao->connect_error) {
    $conexao = @new mysqli('localhost', $db_user, $db_pass, $db_banco);
}

if ($conexao->connect_error) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'nok',
        'mensagem' => 'Falha na conexao: ' . $conexao->connect_error,
        'data' => []
    ]);
    exit;
}

$conexao->set_charset('utf8mb4');
