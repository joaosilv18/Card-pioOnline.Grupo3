<?php
session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica se o usuário já existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['registro_error'] = "Este nome de usuário já está em uso.";
        header("Location: registro.php");
        exit;
    }
    $stmt->close();

    // Criptografa a senha para segurança
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insere o novo usuário no banco
    $stmt = $conn->prepare("INSERT INTO usuarios (username, senha) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['registro_sucesso'] = "Conta criada com sucesso! Faça o login.";
        header("Location: index.php");
    } else {
        $_SESSION['registro_error'] = "Erro ao criar a conta. Tente novamente.";
        header("Location: registro.php");
    }
    $stmt->close();
    $conn->close();
}
?>
