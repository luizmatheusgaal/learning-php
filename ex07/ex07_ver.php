<!-- Crie ex07_ver.php, que também inicia a sessão e exibe o usuário se isset($_SESSION['usuario']), ou "Sem sessão ativa" caso contrário. Por fim, crie ex07_sair.php, que usa session_unset() e session_destroy(), e teste o fluxo completo. -->

<?php
session_start();
if (isset($_SESSION['usuario'])) {
    echo "Usuário: " . $_SESSION['usuario'];
} else {
    echo "Sem sessão ativa";
}
?>