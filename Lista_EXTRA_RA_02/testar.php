<?php
/**
 * Script de testes automatizados para a Lista EXTRA RA 02
 * Executa todos os cenários solicitados no enunciado.
 */

// Inicia servidor PHP embutido para testes se não estiver rodando
$porta = 8089;
$urlBase = "http://127.0.0.1:{$porta}/php";

$comandoServidor = "php -S 127.0.0.1:{$porta} -t \"" . __DIR__ . "\" > /dev/null 2>&1 & echo $!";
$pid = trim(shell_exec($comandoServidor));
usleep(250000); // 250ms para inicializar

register_shutdown_function(function() use ($pid) {
    if (!empty($pid)) {
        shell_exec("kill -9 {$pid} 2>/dev/null");
    }
});

// Reseta banco de dados para estado inicial do enunciado
$c = new mysqli("127.0.0.1", "root", "", "projeto");
if ($c->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $c->connect_error . "\n");
}
$sql = file_get_contents(__DIR__ . "/banco.sql");
$c->multi_query($sql);
while ($c->more_results() && $c->next_result()) {;}
$c->close();

$total = 0;
$sucessos = 0;
$falhas = 0;

function executarRequisicao($url, $metodo = 'GET', $dados = [], $cookieFile = null) {
    $cmd = "curl -s -i ";
    if ($cookieFile) {
        $cmd .= "-b " . escapeshellarg($cookieFile) . " -c " . escapeshellarg($cookieFile) . " ";
    }
    if ($metodo === 'POST') {
        $cmd .= "-X POST ";
        foreach ($dados as $k => $v) {
            $cmd .= "-F " . escapeshellarg("{$k}={$v}") . " ";
        }
    }
    $cmd .= escapeshellarg($url);

    $reqawOutput = shell_exec($cmd);
    
    // Separa cabeçalhos do corpo
    $partes = explode("\r\n\r\n", $reqawOutput, 2);
    if (count($partes) < 2) {
        $partes = explode("\n\n", $reqawOutput, 2);
    }
    $body = $partes[1] ?? $reqawOutput;
    $json = json_decode(trim($body), true);

    return ['raw' => $body, 'json' => $json];
}

function testarCenario($nomeEx, $cenario, $condicaoPassou, $detalhes = '') {
    global $total, $sucessos, $falhas;
    $total++;
    if ($condicaoPassou) {
        $sucessos++;
        echo "  [\033[32mPASS\033[0m] {$cenario}\n";
    } else {
        $falhas++;
        echo "  [\033[31mFAIL\033[0m] {$cenario} - Detalhes: {$detalhes}\n";
    }
}

echo "\n=======================================================\n";
echo "    INICIANDO TESTES DA LISTA EXTRA RA 02\n";
echo "=======================================================\n\n";

$cookieSessao = tempnam(sys_get_temp_dir(), 'sess_');

// ---------------- EXERCÍCIO 01 ----------------
echo "EXERCICIO 01: produto_novo.php\n";
$req = executarRequisicao("{$urlBase}/produto_novo.php", 'POST', ['nome' => '', 'preco' => '']);
testarCenario('Ex01', 'Nome ou preco vazio -> nok', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Nome e preco sao obrigatorios.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/produto_novo.php", 'POST', [
    'nome' => 'Borracha branca',
    'preco' => '1.50',
    'estoque' => '25',
    'ativo' => '1'
]);
testarCenario('Ex01', 'Produto gravado com sucesso -> ok', $req['json']['status'] === 'ok' && $req['json']['mensagem'] === 'Produto cadastrado com sucesso.', json_encode($req['json']));

// ---------------- EXERCÍCIO 02 ----------------
echo "\nEXERCICIO 02: produto_listar.php\n";
$req = executarRequisicao("{$urlBase}/produto_listar.php", 'GET');
$passouEx2 = ($req['json']['status'] === 'ok' && is_array($req['json']['data']) && count($req['json']['data']) > 0);
testarCenario('Ex02', 'Listar produtos ativos ordenados por preco -> ok', $passouEx2, json_encode($req['json']));

// ---------------- EXERCÍCIO 03 ----------------
echo "\nEXERCICIO 03: produto_detalhe.php\n";
$req = executarRequisicao("{$urlBase}/produto_detalhe.php", 'GET');
testarCenario('Ex03', 'Sem id -> nok (ID nao informado.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'ID nao informado.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/produto_detalhe.php?id=999", 'GET');
testarCenario('Ex03', 'id=999 inexistente -> nok (Produto nao encontrado.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Produto nao encontrado.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/produto_detalhe.php?id=1", 'GET');
testarCenario('Ex03', 'id=1 existente e ativo -> ok (Consulta efetuada.)', $req['json']['status'] === 'ok' && isset($req['json']['data'][0]['nome']), json_encode($req['json']));

// ---------------- EXERCÍCIO 04 ----------------
echo "\nEXERCICIO 04: produto_alterar.php\n";
$req = executarRequisicao("{$urlBase}/produto_alterar.php", 'POST', ['nome' => 'X', 'preco' => '10', 'ativo' => '1']);
testarCenario('Ex04', 'Sem id na URL -> nok (ID nao informado.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'ID nao informado.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/produto_alterar.php?id=1", 'POST', ['nome' => 'Caneta', 'preco' => '0', 'ativo' => '1']);
testarCenario('Ex04', 'Preço zero ou negativo -> nok (Preco invalido.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Preco invalido.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/produto_alterar.php?id=999", 'POST', ['nome' => 'Inexistente', 'preco' => '10.50', 'ativo' => '1']);
testarCenario('Ex04', 'ID inexistente -> nok (Produto nao encontrado.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Produto nao encontrado.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/produto_alterar.php?id=1", 'POST', ['nome' => 'Caneta azul bic', 'preco' => '2.80', 'ativo' => '1']);
testarCenario('Ex04', 'Dados novos gravados -> ok (Produto alterado com sucesso.)', $req['json']['status'] === 'ok' && $req['json']['mensagem'] === 'Produto alterado com sucesso.', json_encode($req['json']));

// ---------------- EXERCÍCIO 05 ----------------
echo "\nEXERCICIO 05: produto_excluir.php\n";
$req = executarRequisicao("{$urlBase}/produto_excluir.php", 'GET');
testarCenario('Ex05', 'Sem id -> nok (E necessario informar um ID para exclusao.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'E necessario informar um ID para exclusao.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/produto_excluir.php?id=50", 'GET');
testarCenario('Ex05', 'id=50 inexistente -> nok (Produto nao encontrado.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Produto nao encontrado.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/produto_excluir.php?id=1", 'GET');
testarCenario('Ex05', 'id=1 com estoque 100 -> nok (Produto com estoque nao pode ser excluido.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Produto com estoque nao pode ser excluido.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/produto_excluir.php?id=3", 'GET');
testarCenario('Ex05', 'id=3 com estoque 0 -> ok (Produto excluido.)', $req['json']['status'] === 'ok' && $req['json']['mensagem'] === 'Produto excluido.', json_encode($req['json']));

// ---------------- EXERCÍCIO 06 ----------------
echo "\nEXERCICIO 06: usuario_login.php\n";
$req = executarRequisicao("{$urlBase}/usuario_login.php", 'POST', ['usuario' => '', 'senha' => '']);
testarCenario('Ex06', 'Campo vazio -> nok (Informe usuario e senha.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Informe usuario e senha.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/usuario_login.php", 'POST', ['usuario' => 'admin', 'senha' => 'errada']);
testarCenario('Ex06', 'admin / senha errada -> nok (Credenciais invalidas.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Credenciais invalidas.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/usuario_login.php", 'POST', ['usuario' => 'admin', 'senha' => '1234'], $cookieSessao);
testarCenario('Ex06', 'admin / 1234 correto -> ok e sessao criada', $req['json']['status'] === 'ok' && $req['json']['mensagem'] === 'Login efetuado com sucesso.', json_encode($req['json']));

// ---------------- EXERCÍCIO 07 ----------------
echo "\nEXERCICIO 07: estoque_resumo.php\n";
$req = executarRequisicao("{$urlBase}/estoque_resumo.php", 'GET'); // Sem cookie de sessão
testarCenario('Ex07', 'Sem sessao -> nok (Acesso negado. Faca login.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Acesso negado. Faca login.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/estoque_resumo.php", 'GET', [], $cookieSessao); // Com cookie de sessão
testarCenario('Ex07', 'Com sessao ativa -> ok (Resumo do estoque retornado)', $req['json']['status'] === 'ok' && isset($req['json']['data'][0]['total_produtos']), json_encode($req['json']));

// ---------------- EXERCÍCIO 08 ----------------
echo "\nEXERCICIO 08: produto_buscar.php\n";
$req = executarRequisicao("{$urlBase}/produto_buscar.php", 'GET');
testarCenario('Ex08', 'Termo vazio/ausente -> nok (Informe um termo de busca.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Informe um termo de busca.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/produto_buscar.php?termo=cad", 'GET');
testarCenario('Ex08', '?termo=cad -> ok (Caderno encontrado)', $req['json']['status'] === 'ok' && count($req['json']['data']) > 0, json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/produto_buscar.php?termo=xyz", 'GET');
testarCenario('Ex08', '?termo=xyz -> nok (Nenhum produto encontrado.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Nenhum produto encontrado.', json_encode($req['json']));

// ---------------- EXERCÍCIO 09 ----------------
echo "\nEXERCICIO 09: estoque_movimentar.php\n";
$req = executarRequisicao("{$urlBase}/estoque_movimentar.php", 'POST', ['tipo' => 'entrada', 'quantidade' => '10']);
testarCenario('Ex09', 'id ausente -> nok (ID nao informado.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'ID nao informado.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/estoque_movimentar.php?id=2", 'POST', ['tipo' => 'troca', 'quantidade' => '10']);
testarCenario('Ex09', 'tipo=troca invalido -> nok (Dados invalidos.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Dados invalidos.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/estoque_movimentar.php?id=2", 'POST', ['tipo' => 'entrada', 'quantidade' => '10']);
testarCenario('Ex09', 'id=2, entrada de 10 -> ok (Estoque atualizado para 50)', $req['json']['status'] === 'ok' && $req['json']['data'][0]['estoque'] === 50, json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/estoque_movimentar.php?id=2", 'POST', ['tipo' => 'saida', 'quantidade' => '15']);
testarCenario('Ex09', 'id=2, saída de 15 -> ok (Estoque atualizado para 35)', $req['json']['status'] === 'ok' && $req['json']['data'][0]['estoque'] === 35, json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/estoque_movimentar.php?id=2", 'POST', ['tipo' => 'saida', 'quantidade' => '9999']);
testarCenario('Ex09', 'id=2, saída maior que saldo -> nok (Estoque insuficiente.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Estoque insuficiente.', json_encode($req['json']));

// ---------------- EXERCÍCIO 10 ----------------
echo "\nEXERCICIO 10: venda_registrar.php\n";
$req = executarRequisicao("{$urlBase}/venda_registrar.php", 'POST', ['produto_id' => '1', 'quantidade' => '4']); // Sem sessão
testarCenario('Ex10', 'Sem login -> nok (Acesso negado. Faca login.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Acesso negado. Faca login.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/venda_registrar.php", 'POST', ['produto_id' => '1', 'quantidade' => '0'], $cookieSessao);
testarCenario('Ex10', 'Logado, quantidade=0 -> nok (Dados invalidos.)', $req['json']['status'] === 'nok' && $req['json']['mensagem'] === 'Dados invalidos.', json_encode($req['json']));

$req = executarRequisicao("{$urlBase}/venda_registrar.php", 'POST', ['produto_id' => '1', 'quantidade' => '4'], $cookieSessao);
testarCenario('Ex10', 'Logado, produto_id=1, quantidade=4 -> ok (Venda registrada.)', $req['json']['status'] === 'ok' && $req['json']['mensagem'] === 'Venda registrada.' && isset($req['json']['data'][0]['venda_id']), json_encode($req['json']));

@unlink($cookieSessao);

echo "\n=======================================================\n";
echo "                  RESUMO DOS TESTES\n";
echo "=======================================================\n";
echo "Total de cenários testados: {$total}\n";
echo "\033[32mSucessos: {$sucessos}\033[0m\n";
if ($falhas > 0) {
    echo "\033[31mFalhas: {$falhas}\033[0m\n";
} else {
    echo "\033[32mTODOS OS TESTES PASSARAM COM SUCESSO! (100% OK)\033[0m\n";
}
echo "=======================================================\n\n";
