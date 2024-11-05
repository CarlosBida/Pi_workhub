<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Perfil</title>
    <link rel="stylesheet" href="css/stylePerfil.css">
     <!-- Favicon -->
     <link href="img/favicon.ico" rel="icon">
     <!-- Fonte -->
     <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
</head>
<body>
    <div class="logo"><img src="img/Workhub logo.png"></div>
    <div class="profile-container">
        <h1>Perfil do Usuário</h1>
        <?php if (isset($message)): ?>
            <div class="success-message"><?php echo $message; ?></div>
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
    <!--Inicio navBar-->
    <nav name="navBar" id="navBar">
        <ul class="navlinks">
            <li><a href="inicio.php"><ion-icon name="home-outline"></ion-icon></a></li>
            <li><a href="cadastro.php"><ion-icon name="add-outline"></ion-icon></a></li>
            <li><a href="dashbord.php"><ion-icon name="grid-outline"></ion-icon></a></li>
            <li><a href="perfil.php"><ion-icon name="person-outline"></ion-icon></a></li>
        </ul>
    </nav>
    <!--Fim navBar-->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>