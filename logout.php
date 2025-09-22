<?php
// Inicia a sessão para poder manipulá-la
session_start();

// Limpa todas as variáveis da sessão (remove os dados de login)
$_SESSION = array();

// Destrói a sessão completamente
session_destroy();

// Redireciona o usuário para a página de login
header("Location: index.php");
exit;
?>
