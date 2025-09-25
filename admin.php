<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

include 'gerenciar_pratos.php';
$pratos = listarPratos();

$protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$base_url = $protocolo . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$url_cardapio = rtrim($base_url, '/') . "/menu.php?user=" . urlencode($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de <?= htmlspecialchars($_SESSION['username']) ?></title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
</head>
<body>
    <header>
        <nav>
            <div class="welcome-text">Bem-vindo, <?= htmlspecialchars($_SESSION['username']) ?>!</div>
            <div class="nav-links">
                <a href="<?= $url_cardapio ?>" target="_blank">Ver meu Cardápio</a>
                <a href="logout.php" class="logout-btn">Sair</a>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="admin-grid">
            <!-- Coluna da Esquerda: Adicionar Prato e QR Code -->
            <div>
                <div class="admin-section">
                    <h2>Adicionar Novo Prato</h2>
                    <form action="gerenciar_pratos.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="acao" value="adicionar">
                        <div class="form-group">
                            <label for="nome">Nome do Prato</label>
                            <input type="text" id="nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="preco">Preço (R$)</label>
                            <input type="number" id="preco" name="preco" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="imagem">Foto do Prato</label>
                            <input type="file" id="imagem" name="imagem">
                        </div>
                        <div class="form-group-checkbox">
                            <input type="checkbox" id="ativo" name="ativo" checked>
                            <label for="ativo">Prato Ativo</label>
                        </div>
                        <button type="submit" class="btn btn-success">Salvar Prato</button>
                    </form>
                </div>
                <div class="admin-section" style="margin-top: 2rem; text-align: center;">
                    <h2>QR Code</h2>
                    <div id="qrcode" style="display: flex; justify-content: center; padding: 1rem; background-color: #f8f9fa; border-radius: 8px;"></div>
                    <p style="margin-top: 1rem; font-size: 0.8rem; color: #666;">Imprima e coloque nas mesas.</p>
                </div>
            </div>
            
            <!-- Coluna da Direita: Lista de Pratos -->
            <div class="admin-section">
                <h2>Seus Pratos</h2>
                <div class="dish-list">
                    <?php if (empty($pratos)): ?>
                        <p>Você ainda não cadastrou nenhum prato.</p>
                    <?php else: ?>
                        <?php foreach ($pratos as $prato): ?>
                            <div class="dish-item">
                                <div class="dish-info">
                                    <img src="<?= $prato['imagem'] ? 'uploads/' . htmlspecialchars($prato['imagem']) : 'https://placehold.co/80x80/EBF4FF/333333?text=S/Foto' ?>" alt="Foto do prato">
                                    <div>
                                        <h3><?= htmlspecialchars($prato['nome']) ?></h3>
                                        <p>R$ <?= htmlspecialchars(number_format($prato['preco'], 2, ',', '.')) ?></p>
                                    </div>
                                </div>
                                <div class="dish-actions">
                                    <a href="gerenciar_pratos.php?acao=toggle_status&id=<?= $prato['id'] ?>" class="status <?= $prato['ativo'] ? 'active' : 'inactive' ?>">
                                        <?= $prato['ativo'] ? 'Ativo' : 'Inativo' ?>
                                    </a>
                                    <a href="gerenciar_pratos.php?acao=remover&id=<?= $prato['id'] ?>" onclick="return confirm('Tem certeza?')" class="remove-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "<?= $url_cardapio ?>",
            width: 220,
            height: 220,
        });
    </script>
</body>
</html>