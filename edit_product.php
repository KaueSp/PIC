<?php
session_start();
include 'include/config.php';

// Habilitar exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    $_SESSION['edit_product_error'] = "Você precisa estar logado para editar produtos.";
    header('Location: index.php');
    exit;
}

// Verifica se a requisição é POST e se o botão de edição foi pressionado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_product'])) {
    // Coletar e sanitizar os dados do formulário
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    // Verificar se 'category' existe e não é nulo antes de processar
    if (isset($_POST['category']) && is_array($_POST['category'])) {
        $category = json_encode(array_values(json_decode($_POST['category'][0], true)));
    } else {
        $category = json_encode([]); // Se não existir, define como um array vazio
    }

    $image_url = isset($_POST['image_url']) ? trim($_POST['image_url']) : '';

    // Validação básica
    if (empty($name) || empty($category)) {
        $_SESSION['edit_product_error'] = "Por favor, preencha todos os campos obrigatórios corretamente.";
        header("Location: catalogo.php");
        exit;
    }

    // Preparar a consulta para evitar SQL Injection
    $sql = "UPDATE products SET name = ?, description = ?, category = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $_SESSION['edit_product_error'] = "Erro na preparação da consulta: " . $conn->error;
        header('Location: catalogo.php');
        exit;
    }

    $stmt->bind_param("ssssi", $name, $description, $category, $image_url, $product_id);

    if ($stmt->execute()) {
        $_SESSION['edit_product_success'] = "Produto atualizado com sucesso!";
    } else {
        $_SESSION['edit_product_error'] = "Erro ao atualizar produto: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redireciona para a página do catálogo
    header('Location: catalogo.php');
    exit;
}

// Se o código chegar aqui, é porque não houve redirecionamento
echo "Nenhum redirecionamento ocorreu. Verifique se a lógica está correta.";
?>
