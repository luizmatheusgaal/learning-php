<!-- Por fim, crie ex07_sair.php, que usa session_unset() e session_destroy(), e teste o fluxo completo. -->

<?php
session_start();
session_unset();
session_destroy();
?>