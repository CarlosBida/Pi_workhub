<?php
session_start(); // Inicia a sessão

include 'conexaoBD.php'; // Inclui o arquivo de conexão ao banco de dados

$message = ''; // Inicializa a variável de mensagem

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para verificar se o usuário existe
    $sql = "SELECT id, username, password FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário foi encontrado
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifica a senha
        if (password_verify($password, $user['password'])) {
            // Senha correta, inicia a sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: inicio.php"); // Redireciona para a página inicial
            exit();
        } else {
            $message = "Senha incorreta.";
        }
    } else {
        $message = "Usuário não encontrado.";
    }

    $conn->close(); // Fecha a conexão com o banco de dados
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="manifest" href="manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workhub</title>
    <link rel="stylesheet" href="css/style_login.css">
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    <!-- Fonte -->
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
</head>
<body>
<div class="login">
    <div class="ellipse-48"></div>
    <div class="ellipse-47"></div>

    <img class="logo" src="img/Workhub logo.png" />
  
    <div class="info-top">
        <span class="fa-a-o-seu-login">Faça o seu Login</span>
        <span class="bem-vindo-de-volta-ao">Bem-vindo de volta ao</span>
        <div class="user">
            <img class="user2" src="img/User.png" />
        </div>
    </div>
    
    <!-- Mensagem de erro -->
    <?php if (!empty($message)): ?>
        <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="insira-seu-email">
            <input type="email" name="email" placeholder="Digite seu E-mail" required>
            <span class="fa fa-user"></span>
        </div>
        <div class="insira-sua-senha">
            <input type="password" name="password" placeholder="Digite sua Senha" required>
            <span class="fa fa-lock"></span>
        </div>

        <button type="submit" class="botao-login">Login</button>
    </form>

    <div class="social-login">
        <a href="#" class="social_1">
            <img src="img/google logo.png">     
        </a>
        <a href="#" class="social_2">
            <img src="img/facebook logo.png"> 
        </a>
        <a href="#" class="social_3">
            <img src="img/apple logo.png">   
        </a>
    </div>

    <a href="#" class="esqueceu-sua-senha">Esqueceu sua senha?</a>
   
    <span class="ou-logue-com">Ou logue com</span>

    <div class="cadastrar">
      <span>
        <span class="cadastrar-span">
          Não tem cadastro?
        </span>
        <a href="cadastroPerfil.php" class="cadastrar-link">Crie sua conta.</a>
      </span>
    </div>
</div>
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