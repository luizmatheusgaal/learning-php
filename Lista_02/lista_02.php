<?php
/*
Parte 1 — Arrays (item 7.5)
*/

/*
Exercício 1 (array indexado). Crie o array $frutas = ['maçã', 'banana', 'laranja']. Exiba o primeiro elemento com $frutas[0], adicione 'uva' ao final com $frutas[] = 'uva' e exiba o array completo com print_r().
*/
$frutas = ['maçã', 'banana', 'laranja'];
echo "Primeira fruta: " . $frutas[0] . "\n";

$frutas[] = 'uva';
print_r($frutas);


/*
Exercício 2 (array associativo). Crie o array $cliente com as chaves id, nome, email e ativo, preenchendo com valores de sua escolha. Exiba o nome e o e-mail usando echo e a chave correspondente (ex.: $cliente['nome']).
*/
$cliente = [
    'id' => 1,
    'nome' => 'João',
    'email' => 'joao@email.com',
    'ativo' => true
];

echo "Nome: " . $cliente['nome'] . "\n";
echo "Email: " . $cliente['email'] . "\n";


/*
Exercício 3 (lista de registros). Crie um array vazio $tabela = []; e adicione a ele três arrays associativos de clientes (cada um com id, nome e email) usando $tabela[] = $linha;. Percorra $tabela com foreach e exiba "id - nome (email)" em cada linha.
*/
$tabela = [];
$tabela[] = [
    'id' => 1,
    'nome' => 'João',
    'email' => 'joao@email.com'
];
$tabela[] = [
    'id' => 2,
    'nome' => 'Maria',
    'email' => 'maria@email.com'
];
$tabela[] = [
    'id' => 3,
    'nome' => 'Pedro',
    'email' => 'pedro@email.com'
];

foreach ($tabela as $cliente) {
    echo $cliente['id'] . " - " . $cliente['nome'] . " (" . $cliente['email'] . ")\n";
}

/*
Exercício 4 (json_encode). Com o array $tabela do exercício anterior, use json_encode($tabela) para convertê-lo em JSON e exiba o resultado. Em seguida, use json_decode() com o segundo parâmetro true para converter o JSON de volta em array e exiba o nome do segundo cliente.
*/

$json = json_encode($tabela);
echo $json . "\n";

$tabela_json = json_decode($json, true);
echo "Nome do segundo cliente: " . $tabela_json[1]['nome'] . "\n";

/*
Parte 2 — Superglobais (item 7.6)
*/

/*
Exercício 5 ($_GET). Crie ex05.php que receba o parâmetro id pela URL (ex05.php?id=42). Se isset($_GET['id']), exiba "Buscando o registro 42"; caso contrário, exiba "Nenhum ID informado". Converta o ID para inteiro com (int) antes de exibir.
*/

/*
Exercício 6 ($_POST). Crie um formulário HTML com os campos nome e email (método post) e o arquivo ex06.php para recebê-los. Use o operador ?? para evitar erro quando o campo não existir ($nome = $_POST['nome'] ?? '';). Se empty($nome), exiba "Nome obrigatório"; caso contrário, exiba "Olá, [nome]!".
*/

/*
Exercício 7 ($_SESSION). Crie ex07_login.php, que inicia a sessão com session_start() e grava $_SESSION['usuario'] = 'Ana'. Crie ex07_ver.php, que também inicia a sessão e exibe o usuário se isset($_SESSION['usuario']), ou "Sem sessão ativa" caso contrário. Por fim, crie ex07_sair.php, que usa session_unset() e session_destroy(), e teste o fluxo completo.
*/

/*
Parte 3 — Padrão de retorno JSON (item 7.7)
*/

/*
Exercício 8 (retorno de sucesso). Monte o array $retorno padrão do projeto, com status, mensagem e data vazios. Preencha-o como sucesso (status = 'ok', mensagem = 'Consulta efetuada.') e coloque em data um array com dois clientes (nome e email). Defina o header de JSON e exiba com json_encode().
*/
$retorno = [
    'status' => 'ok',
    'mensagem' => 'Consulta efetuada.',
    'data' => [
        [
            'nome' => 'Luiz Matheus',
            'email' => 'luizmatheusga@gmail.com'
        ],
        [
            'nome' => 'Ana',
            'email' => 'ana@gmail.com'
        ]
    ]
];
header('Content-Type: application/json');
echo json_encode($retorno);
/*
Exercício 9 (retorno de falha). Crie ex09.php, que recebe nome por $_POST (ou $_GET, para facilitar o teste). Se o nome estiver vazio, devolva status = 'nok' e mensagem = 'Nome obrigatorio.'; caso contrário, devolva status = 'ok' e mensagem = 'Registro recebido.'. Em ambos os casos, o retorno deve seguir o mesmo formato JSON.
*/

/*
Exercício 10 (consumindo o JSON). Crie ex10.php, que devolve um $retorno padrão com status = 'ok' e uma lista de 3 produtos (id, nome, preco) em data. Depois, crie ex10.html com um botão que, ao clicar, usa fetch('ex10.php') e await resposta.json(); se resposta.status == 'ok', exiba o nome de cada produto na tela; caso contrário, exiba alert(resposta.mensagem).
*/

/*
Parte 4 — Funções (item 7.4)
*/

/*
Exercício 11 (declaração e retorno). Escreva a função saudar($nome), que retorna 'Olá, ' . $nome . '!'. Chame a função com dois nomes diferentes e exiba os resultados com echo.
*/

/*
Exercício 12 (valor padrão). Escreva a função calcularTotal($preco, $quantidade = 1), que retorna o produto dos dois parâmetros. Chame-a passando os dois argumentos e depois passando apenas o preço, exibindo ambos os resultados.
*/

/*
Exercício 13 (funções nativas). Dada a string $texto = ' Ana Silva ', use trim(), strtolower(), strtoupper() e strlen() e exiba o resultado de cada uma. Depois, escreva a função respostaPadrao($status, $mensagem, $data = []), que retorna o array padrão do projeto (status, mensagem, data), e exiba o resultado de respostaPadrao('ok', 'Funcionando!') com json_encode().
*/
