<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Cardápio Online</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h1>Crie sua Conta</h1>
            <p>É rápido e fácil para começar a criar seu cardápio.</p>
            
            <form action="registrar_usuario.php" method="POST">
                <div class="form-group">
                    <label for="username">Nome de Usuário</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <?php
                if (isset($_SESSION['registro_error'])) {
                    echo '<p class="error-message">' . htmlspecialchars($_SESSION['registro_error']) . '</p>';
                    unset($_SESSION['registro_error']);
                }
                ?>

                <button type="submit" class="btn btn-success">Cadastrar</button>
            </form>
            <div class="auth-link">
                <a href="index.php">Já tem uma conta? Faça login</a>
            </div>
        </div>
    </div>
</body>
</html>
