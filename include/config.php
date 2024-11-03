<?php
// include/config.php

$servername = "localhost";
$username = "root"; // Usuário root padrão no XAMPP
$password = "";     // Senha vazia por padrão no XAMPP
$dbname = "admin_products_db";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
