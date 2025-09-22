<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h1>Cardápio Online</h1>
            <p>Acesse seu painel ou crie uma conta</p>
            
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <?php
                if (isset($_SESSION['login_error'])) {
                    echo '<p class="error-message">' . htmlspecialchars($_SESSION['login_error']) . '</p>';
                    unset($_SESSION['login_error']);
                }
                if (isset($_SESSION['registro_sucesso'])) {
                    echo '<p class="success-message">' . htmlspecialchars($_SESSION['registro_sucesso']) . '</p>';
                    unset($_SESSION['registro_sucesso']);
                }
                ?>

                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
            <div class="auth-link">
                <a href="registro.php">Não tem uma conta? Cadastre-se</a>
            </div>
        </div>
    </div>
</body>
</html>

