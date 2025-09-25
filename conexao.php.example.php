<?php
// ARQUIVO DE EXEMPLO: NÃO CONTÉM SENHAS REAIS.
// INSTRUÇÕES: 
// 1. Faça uma cópia deste arquivo.
// 2. Renomeie a cópia para "conexao.php".
// 3. Preencha os valores abaixo com as credenciais do seu banco de dados (local ou da InfinityFree).
// 4. O arquivo "conexao.php" real está no .gitignore e nunca deve ser enviado ao GitHub.

$servername = "COLOQUE_SEU_HOST_AQUI";       // Ex: sql201.epizy.com ou localhost
$username = "COLOQUE_SEU_USUARIO_AQUI";   // Ex: epiz_12345678 ou root
$password = "COLOQUE_SUA_SENHA_AQUI";       // Sua senha do banco de dados
$dbname = "COLOQUE_SEU_DB_NAME_AQUI";       // Ex: epiz_1234e5678_cardapio

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
