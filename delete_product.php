<?php
session_start();
include 'include/config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    $_SESSION['delete_product_error'] = "Você precisa estar logado para remover produtos.";
    header('Location: index.php');
    exit;
}

// Código para deletar o produto continua aqui...


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_product'])) {
    // Coletar e sanitizar os dados do formulário
    $product_id = intval($_POST['product_id']);

    // Preparar a consulta para evitar SQL Injection
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        $_SESSION['delete_product_success'] = "Produto removido com sucesso!";
    } else {
        $_SESSION['delete_product_error'] = "Erro ao remover produto: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header('Location: catalogo.php');
    exit;
}
?>