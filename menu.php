<?php
include 'conexao.php';
include 'gerenciar_pratos.php';

// Verifica se um nome de usuário foi passado na URL
if (!isset($_GET['user'])) {
    die("Cardápio não encontrado. Especifique um usuário na URL (ex: menu.php?user=nomedousuario).");
}
$username = $_GET['user'];

// Busca o ID do usuário com base no nome de usuário
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Se o usuário for encontrado, busca os pratos dele
if ($user = $result->fetch_assoc()) {
    $id_usuario = $user['id'];
    // Usa a função para listar apenas os pratos ativos daquele usuário
    $pratos = listarPratosAtivosPorUsuario($id_usuario);
} else {
    // Se o usuário não existir, exibe uma mensagem de erro
    die("Usuário do cardápio não encontrado.");
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio de <?= htmlspecialchars($username) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <!-- Div adicionada para garantir a centralização do título -->
        <div style="text-align: center; padding: 1.5rem 0;">
            <h1 style="font-size: 2rem; font-weight: 700;">Cardápio de <?= htmlspecialchars($username) ?></h1>
        </div>
    </header>

    <main class="container">
        <div class="menu-grid">
            
            <?php if (empty($pratos)): ?>
                <p style="grid-column: 1 / -1; text-align: center; padding: 4rem 0;">Este cardápio ainda não tem pratos cadastrados.</p>
            <?php else: ?>
                <?php foreach ($pratos as $prato): ?>
                <div class="menu-card">
                    <img src="<?= $prato['imagem'] ? 'uploads/' . htmlspecialchars($prato['imagem']) : 'https://placehold.co/600x400/EBF4FF/333333?text=Prato+sem+foto' ?>" alt="Foto de <?= htmlspecialchars($prato['nome']) ?>">
                    <div class="card-content">
                        <h3><?= htmlspecialchars($prato['nome']) ?></h3>
                        <p class="price">R$ <?= htmlspecialchars(number_format($prato['preco'], 2, ',', '.')) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </main>
    
    <!-- Rodapé agora com estilo para centralizar -->
    <footer style="text-align: center; padding: 2rem 0; margin-top: 2rem; font-size: 0.9rem; color: #666;">
        <p>Gerado com Cardápio Online &copy; <?= date('Y') ?></p>
    </footer>
</body>
</html>