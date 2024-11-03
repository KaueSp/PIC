<?php
session_start();
include 'include/config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    $_SESSION['add_product_error'] = "Você precisa estar logado para adicionar produtos.";
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    // Coletar e sanitizar os dados do formulário
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $categories = isset($_POST['category']) ? json_encode($_POST['category']) : null; // Salva as categorias como JSON
    $image_url = trim($_POST['image_url']);
    $admin_id = $_SESSION['admin_id'];

    // Validação básica
    if (empty($name)) {
        $_SESSION['add_product_error'] = "O campo 'Nome do Produto' é obrigatório.";
        header('Location: catalogo.php');
        exit;
    }

    // Preparar a consulta para evitar SQL Injection
    $sql = "INSERT INTO products (name, description, category, image_url, created_by) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $description, $categories, $image_url, $admin_id);

    if ($stmt->execute()) {
        $_SESSION['add_product_success'] = "Produto adicionado com sucesso!";
    } else {
        $_SESSION['add_product_error'] = "Erro ao adicionar produto: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header('Location: catalogo.php');
    exit;
}
?>
