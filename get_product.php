<?php
header('Content-Type: application/json');

// Configurações de conexão com o banco de dados
$host = 'localhost';
$dbname = 'admin_products_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o ID do produto foi fornecido
    if (!isset($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'ID do produto não fornecido.']);
        exit;
    }

    $productId = $_GET['id'];

    // Consulta para obter o produto, incluindo o campo de categorias JSON
    $stmt = $pdo->prepare("SELECT id, name, description, image_url, category FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Produto não encontrado.']);
        exit;
    }

    // Decodifica o campo de categorias JSON
    $categories = json_decode($product['category'], true);

    // Monta a resposta JSON
    $product['categories'] = $categories;
    echo json_encode(['success' => true, 'product' => $product]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao conectar ao banco de dados: ' . $e->getMessage()]);
}
?>
