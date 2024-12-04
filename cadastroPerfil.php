<?php
include 'conexaoBD.php'; // Conectar ao banco de dados

$message = ''; // Inicializa a variável de mensagem

// Verifica se foi enviado um formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Senha obrigatória

    // Verifica se o e-mail já está em uso
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Este e-mail já está cadastrado.";
    } else {
        // Cria um novo usuário
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash da senha
        $sql = "INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // Executa a inserção e verifica o resultado
        if ($stmt->execute()) {
            $message = "Cadastro realizado com sucesso!";
        } else {
            $message = "Erro ao cadastrar: " . $stmt->error;
        }
    }

    $stmt->close(); // Fecha a declaração
}

$conn->close(); // Fecha a conexão com o banco de dados
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="css/stylePerfil.css">
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    <!-- Fonte -->
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
    <style>
        .back-icon {
            position: absolute;
            top: 20px;
            left: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="back-icon" onclick="window.location.href='login.php'">
        <ion-icon name="arrow-back-outline" style="font-size: 24px;"></ion-icon>
    </div>
    <div class="logo"><img src="img/Workhub logo.png"></div>
    <div class="profile-container">
        <h1>Cadastrar Usuário</h1>
        <?php if (!empty($message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form id="profile-form" method="POST" action="">
            <div class="form-control">
                <label for="username">Nome de Usuário:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-control">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-control">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            </div>
            <button type="submit">Cadastrar</button>
        </form>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>