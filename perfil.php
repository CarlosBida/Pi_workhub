<?php
include 'conexaoBD.php'; // Conectar ao banco de dados

session_start(); // Inicia a sessão

$message = ''; // Inicializa a variável de mensagem

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Acesso negado. Você precisa estar logado.");
}

// Obtém o ID do usuário da sessão
$userId = $_SESSION['user_id'];

// Verifica se foi enviado um formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Senha opcional

    // Atualiza os dados do usuário
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash da senha
        $sql = "UPDATE usuarios SET username = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $hashed_password, $userId);
    } else {
        // Se a senha não for fornecida, não a atualiza
        $sql = "UPDATE usuarios SET username = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $email, $userId);
    }

    // Executa a atualização e verifica o resultado
    if ($stmt->execute()) {
        $message = "Alterações salvas com sucesso!";
    } else {
        $message = "Erro ao salvar as alterações: " . $stmt->error;
    }
}

// Consulta para obter os dados do usuário
$sql = "SELECT id, username, email FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o usuário foi encontrado
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("Usuário não encontrado.");
}

$conn->close(); // Fecha a conexão com o banco de dados
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="manifest" href="manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Perfil</title>
    <link rel="stylesheet" href="css/stylePerfil.css">
    <link href="img/favicon.ico" rel="icon">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
    <style>
        .logout-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="logo"><img src="img/Workhub logo.png"></div>
    <div class="profile-container">
        <h1>Perfil do Usuário</h1>
        <?php if (!empty($message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form id="profile-form" method="POST" action="">
            <div class="form-control">
                <label for="username">Nome de Usuário:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-control">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-control">
                <label for="password">Nova Senha:</label>
                <input type="password" id="password" name="password" placeholder="Digite sua nova senha (opcional)">
            </div>
            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
    <nav name="navBar" id="navBar">
        <ul class="navlinks">
            <li><a href="inicio.php"><ion-icon name="home-outline"></ion-icon></a></li>
            <li><a href="cadastro.php"><ion-icon name="add-outline"></ion-icon></a></li>
            <li><a href="dashbord.php"><ion-icon name="grid-outline"></ion-icon></a></li>
            <li><a href="perfil.php"><ion-icon name="person-outline"></ion-icon></a></li>
        </ul>
    </nav>

    <!-- Ícone de Sair -->
    <div class="logout-icon" onclick="window.location.href='login.php'">
        <ion-icon name="log-out-outline" style="font-size: 24px;"></ion-icon>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('sw.js')
                .then((registration) => {
                    console.log('Service Worker registrado com sucesso:', registration);
                })
                .catch((error) => {
                    console.log('Registro do Service Worker falhou:', error);
                });
        });
    }
    </script>
</body>
</html>