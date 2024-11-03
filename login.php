<?php
session_start();
include 'include/config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar o banco para encontrar o usuário
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $db_password);
        $stmt->fetch();

        // Verificar a senha (sem criptografia para testes)
        if ($password === $db_password) {
            // Autenticação bem-sucedida
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;

            header('Location: catalogo.php');
            exit;
        } else {
            $_SESSION['login_error'] = "Nome de usuário ou senha inválidos.";
        }
    } else {
        $_SESSION['login_error'] = "Nome de usuário ou senha inválidos.";
    }
    $stmt->close();
    $conn->close();

    // Redirecionar de volta para index.php em caso de erro
    header('Location: catalogo.php');
    exit;
}
?>
