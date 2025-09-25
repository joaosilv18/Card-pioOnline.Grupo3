<?php
session_start(); // Essencial para acessar as variáveis de sessão
include 'conexao.php';

// --- FUNÇÕES DE CONSULTA (Públicas) ---

function listarPratos() {
    global $conn;
    if (!isset($_SESSION['id_usuario'])) {
        return [];
    }
    $id_usuario = $_SESSION['id_usuario'];
    $pratos = [];
    $stmt = $conn->prepare("SELECT * FROM pratos WHERE usuario_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $pratos[] = $row;
        }
    }
    $stmt->close();
    return $pratos;
}

function listarPratosAtivosPorUsuario($id_usuario) {
    global $conn;
    $pratos = [];
    $stmt = $conn->prepare("SELECT * FROM pratos WHERE ativo = 1 AND usuario_id = ? ORDER BY nome ASC");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $pratos[] = $row;
        }
    }
    $stmt->close();
    return $pratos;
}


// --- LÓGICA DE AÇÕES (Protegidas por Login) ---

// Lógica para Adicionar Prato
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'adicionar') {
    // Verificação de login AQUI, apenas para esta ação
    if (!isset($_SESSION['id_usuario'])) {
        die("Acesso negado. Você precisa estar logado para realizar esta ação.");
    }
    $id_usuario_logado = $_SESSION['id_usuario'];
    
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    $nome_imagem = null;

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $caminho_temporario = $_FILES['imagem']['tmp_name'];
        $pasta_uploads = 'uploads/';
        $nome_imagem = uniqid() . '.jpg';
        $caminho_final = $pasta_uploads . $nome_imagem;
        otimizarImagem($caminho_temporario, $caminho_final, 800, 75);
    }

    $stmt = $conn->prepare("INSERT INTO pratos (usuario_id, nome, preco, imagem, ativo) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdsi", $id_usuario_logado, $nome, $preco, $nome_imagem, $ativo);
    
    if ($stmt->execute()) {
        header('Location: admin.php');
        exit;
    }
    $stmt->close();
}

// Lógica para Ações via GET (Remover, Alterar Status)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['acao'])) {
    // Verificação de login AQUI, apenas para estas ações
    if (!isset($_SESSION['id_usuario'])) {
        die("Acesso negado. Você precisa estar logado para realizar esta ação.");
    }
    $id_usuario_logado = $_SESSION['id_usuario'];
    
    if ($_GET['acao'] == 'toggle_status' && isset($_GET['id'])) {
        $id_prato = $_GET['id'];
        $stmt = $conn->prepare("UPDATE pratos SET ativo = NOT ativo WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $id_prato, $id_usuario_logado);
        $stmt->execute();
        header('Location: admin.php');
        exit;
    }
    
    if ($_GET['acao'] == 'remover' && isset($_GET['id'])) {
        $id_prato = $_GET['id'];

        $stmt_select = $conn->prepare("SELECT imagem FROM pratos WHERE id = ? AND usuario_id = ?");
        $stmt_select->bind_param("ii", $id_prato, $id_usuario_logado);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        if($row = $result->fetch_assoc()){
            if($row['imagem'] && file_exists('uploads/' . $row['imagem'])){
                unlink('uploads/' . $row['imagem']);
            }
        }
        $stmt_select->close();

        $stmt_delete = $conn->prepare("DELETE FROM pratos WHERE id = ? AND usuario_id = ?");
        $stmt_delete->bind_param("ii", $id_prato, $id_usuario_logado);
        $stmt_delete->execute();
        header('Location: admin.php');
        exit;
    }
}


function otimizarImagem($origem, $destino, $largura_maxima, $qualidade) {
    list($largura, $altura, $tipo) = getimagesize($origem);
    switch ($tipo) {
        case IMAGETYPE_JPEG: $img_original = imagecreatefromjpeg($origem); break;
        case IMAGETYPE_PNG: $img_original = imagecreatefrompng($origem); break;
        default: move_uploaded_file($origem, $destino); return;
    }
    $ratio = $largura / $altura;
    if ($largura > $largura_maxima) {
        $nova_largura = $largura_maxima;
        $nova_altura = $largura_maxima / $ratio;
    } else {
        $nova_largura = $largura;
        $nova_altura = $altura;
    }
    $img_nova = imagecreatetruecolor($nova_largura, $nova_altura);
    if ($tipo == IMAGETYPE_PNG) {
        $fundo_branco = imagecolorallocate($img_nova, 255, 255, 255);
        imagefill($img_nova, 0, 0, $fundo_branco);
    }
    imagecopyresampled($img_nova, $img_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);
    imagejpeg($img_nova, $destino, $qualidade);
    imagedestroy($img_original);
    imagedestroy($img_nova);
}
?>

