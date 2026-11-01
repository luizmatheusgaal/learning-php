<?php
/*Parte 1 — Declaração de variáveis

Exercício 1. Declare as variáveis $nome (string), $idade (integer), $altura (float), $ativo (boolean) e $apelido (null). Exiba cada uma com echo e, em seguida, use var_dump() para mostrar o tipo de cada variável.
*/
$nome = "Luiz Matheus";
echo $nome . "\n";

$idade = 20;
echo $idade . "\n";

$altura = 1.75;
echo $altura . "\n";

$ativo = true;
echo $ativo . "\n";

$apelido = null;
echo $apelido . "\n";

/*
Exercício 2. Declare $produto = 'Caderno', $preco = 18.5 e $quantidade = 4. Exiba a frase "Produto: Caderno | Total: R$ 74" de duas formas: usando concatenação com ponto (.) e usando interpolação com aspas duplas. Calcule o total com uma variável $total.*/
$produto = 'Caderno';
$preco = 18.5;
$quantidade = 4;
$total = $preco * $quantidade;
echo "Produto: " . $produto . " | Total: R$ " . $total . "\n";
echo "Produto: $produto | Total: R$ $total \n";

/*
Exercício 3. Crie o array associativo $aluno com as chaves nome, curso e nota. Exiba os três valores usando echo e acesse cada um pela chave (ex.: $aluno['nome']). Depois, use print_r() para mostrar a estrutura completa do array.
Parte 2 — Estruturas condicionais
*/
$aluno = [
    "nome" => "Luiz Matheus",
    "curso" => "Ciência da Computação",
    "nota" => 9.5
];
echo $aluno['nome'] . "\n";
echo $aluno['curso'] . "\n";
echo $aluno['nota'] . "\n";
print_r($aluno);

/*
Exercício 4 (if...else). Declare $idade com um valor qualquer. Se a idade for maior ou igual a 18, exiba "Maior de idade"; caso contrário, exiba "Menor de idade".
*/
$idade = 20;
if ($idade >= 18) {
    echo "Maior de idade\n";
} else {
    echo "Menor de idade\n";
}

/*
Exercício 5 (if...else if...else). Declare $nota (float). Exiba o conceito conforme a faixa: A para nota >= 9, B para nota >= 7, C para nota >= 5 e "Reprovado" para os demais casos. Teste com os valores 9.5, 7.5, 5 e 3.
*/
$nota = 9.5;
if ($nota >= 9) {
    echo "A\n";
} elseif ($nota >= 7) {
    echo "B\n";
} elseif ($nota >= 5) {
    echo "C\n";
} else {
    echo "Reprovado\n";
}

/*
Exercício 6 (if...else if...else). Declare $temperatura (em °C). Exiba "Frio" se for menor que 15, "Agradável" se estiver entre 15 e 25 (inclusive) e "Calor" se for maior que 25. Em seguida, reescreva apenas a verificação de $ativo (boolean) usando o operador ternário para exibir "Ativo" ou "Inativo".
*/
$temperatura = 15;
if ($temperatura < 15) {
    echo "Frio\n";
} elseif ($temperatura >= 15 && $temperatura <= 25) {
    echo "Agradável\n";
} else {
    echo "Calor\n";
}

echo $ativo ? "Ativo\n" : "Inativo\n";

/*
Parte 3 — Laços de repetição
/*

Exercício 7 (for). Use um laço for para exibir a tabuada de um número definido em $numero (de 1 a 10), no formato "5 x 3 = 15", uma linha por resultado.
*/
$numero = 10;

for ($i = 1; $i <= 10; $i++) {
    echo $numero . " x " . $i . " = " . ($numero * $i) . "\n";
}


/*
Exercício 8 (for com condicional). Use um laço for de 1 a 20 e exiba apenas os números pares. Ao final, exiba também a soma de todos os números pares encontrados, acumulada em uma variável $soma.
*/
$sum_odd = 0;
for ($i = 1; $i <= 20; $i++) {
    if ($i % 2 == 0) {
        echo $i . "\n";
        $sum_odd += $i;
    }
}
echo "Soma dos números pares: " . $sum_odd . "\n";


/*
Exercício 9 (foreach). Crie o array indexado $frutas = ['maçã', 'banana', 'laranja', 'uva'] e percorra-o com foreach, exibindo cada fruta. Depois, crie o array associativo $cliente com nome, email e usuario e percorra-o com foreach ($cliente as $chave => $valor), exibindo "chave: valor".
*/
$frutas = ['maçã', 'banana', 'laranja', 'uva'];
foreach ($frutas as $fruta) {
    echo $fruta . "\n";
}

$cliente = [
    'nome' => 'Luiz Matheus',
    'email' => 'luizmatheusga@gmail.com',
    'usuario' => 'luizmatheusga'
];
foreach ($cliente as $chave => $valor) {
    echo $chave . ": " . $valor . "\n";
}


/*
Exercício 10 (while). Use um laço while com a variável $contador começando em 10 e fazendo contagem regressiva até 1, exibindo cada número. Ao terminar, exiba "Fim!". Em seguida, reescreva a contagem com um for e compare as duas formas.
*/

$contador = 10;
while ($contador >= 1) {
    echo $contador . "\n";
    $contador--;
}
echo "Fim!\n";

for ($i = 10; $i >= 1; $i--) {
    echo $i . "\n";
}
echo "Fim!\n";
