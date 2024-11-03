<?php
include 'include/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Verificar se o nome de usuário ou e-mail já existem
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Nome de usuário ou e-mail já em uso.";
    } else {
        // Inserir o novo usuário no banco de dados
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);

        if ($stmt->execute()) {
            echo "Registro bem-sucedido!";
        } else {
            echo "Erro: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- Formulário de Registro -->
<form action="register.php" method="POST">
    Nome de Usuário: <input type="text" name="username" required><br>
    E-mail: <input type="email" name="email" required><br>
    Senha: <input type="password" name="password" required><br>
    <input type="submit" value="Registrar">
</form>
