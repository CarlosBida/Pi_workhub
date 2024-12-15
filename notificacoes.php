<?php
session_start();
include('conexaoBD.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ID do usuário logado
$user_id = $_SESSION['user_id'];

// Buscar as notificações do usuário
$query = "SELECT notificacoes.* 
          FROM notificacoes 
          WHERE notificacoes.user_email = (
              SELECT email FROM usuarios WHERE id = ?
          ) 
          ORDER BY notificacoes.data_criacao DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notificacoes = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificações</title>
    <link rel="stylesheet" href="css/styleNotificacao.css"> 
</head>
<body>
    <nav name="navBar" id="navBar">
        <ul class="navlinks">
            <li><a href="inicio.php"><ion-icon name="home-outline"></ion-icon></a></li>
            <li><a href="cadastro.php"><ion-icon name="add-outline"></ion-icon></a></li>
            <li><a href="dashbord.php"><ion-icon name="grid-outline"></ion-icon></a></li>
            <li><a href="perfil.php"><ion-icon name="person-outline"></ion-icon></a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="top-bar">
            <a href="inicio.php" class="back-button">
                <ion-icon name="arrow-back-outline"></ion-icon> Voltar
            </a>
        </div>
        <h1>Notificações</h1>
        <?php if (empty($notificacoes)): ?>
            <p>Você não tem notificações.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($notificacoes as $notificacao): ?>
                    <li>
                        <p><?php echo htmlspecialchars($notificacao['mensagem']); ?></p>
                        <small><?php echo date("d/m/Y H:i", strtotime($notificacao['data_criacao'])); ?></small>
                        <hr>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>