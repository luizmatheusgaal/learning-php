<?php
$retorno = [
    'status' => 'nok',
    'mensagem' => '',
    'data' => []
];

$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($usuario) || empty($senha)) {
    header('Content-Type: application/json');
    $retorno['mensagem'] = 'Informe usuario e senha.';
    echo json_encode($retorno);
    exit;
}

include_once 'conexao.php';

$sql = "SELECT id, nome, senha, ativo FROM usuario WHERE usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('s', $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($linha = $resultado->fetch_assoc()) {
    $senhaOk = ($senha === $linha['senha']) || password_verify($senha, $linha['senha']);
    $ativo = ((int)$linha['ativo'] === 1);

    if ($senhaOk && $ativo) {
        session_start();
        $_SESSION['usuario_id'] = (int)$linha['id'];
        $_SESSION['usuario_nome'] = $linha['nome'];

        $retorno['status'] = 'ok';
        $retorno['mensagem'] = 'Login efetuado com sucesso.';
        $retorno['data'] = [
            [
                'id' => (int)$linha['id'],
                'nome' => $linha['nome']
            ]
        ];
    } else {
        $retorno['mensagem'] = 'Credenciais invalidas.';
    }
} else {
    $retorno['mensagem'] = 'Credenciais invalidas.';
}

$stmt->close();
$conexao->close();

header('Content-Type: application/json');
echo json_encode($retorno);
