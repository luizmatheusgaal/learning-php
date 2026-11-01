<?php
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';

if (empty($nome)) {
    echo 'Nome obrigatório';
} else {
    echo 'Olá, ' . htmlspecialchars($nome) . '!';
}
