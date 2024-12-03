<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workhub</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="img/favicon.ico" rel="icon">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
</head>
<body>
    <div class="destaque">
        <div class="container-sup">
            <img src="img/Workhub logo.png" alt="LOCAHUB" class="logo">
            <h3>Local</h3>
            <div class="location">
                <ion-icon name="chevron-down-outline"></ion-icon>
                <h1>Telêmaco</h1>
            </div>
        </div>

        <div class="busca-site">
            <form action="#" method="post">
                <div class="input">
                    <input type="text" name="" placeholder="Buscar o melhor local">
                    <ion-icon name="search-outline"></ion-icon>
                </div>
                <div class="filter">
                    <img src="img/Vector.svg" alt="">
                </div>
            </form>
        </div>

        <div class="carousel-container">
            <a href="#"><span>Ver Mais</span></a>   
            <h2>O lugar certo para você</h2>
            <div class="carousel">
                <?php
                include 'conexaoBD.php'; // Inclui o arquivo de conexão

                // Consulta para selecionar os espaços cadastrados
                $sql = "SELECT nome, valor, localizacao, descricao, imagens FROM espacos";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Saída de dados de cada linha
                    while ($row = $result->fetch_assoc()) {
                        $imagens = json_decode($row['imagens']);
                        $imagem_principal = !empty($imagens) ? $imagens[0] : 'img/default.png'; // Imagem padrão caso não tenha

                        echo '<div class="slide">';
                        echo '<img src="' . htmlspecialchars($imagem_principal) . '" alt="' . htmlspecialchars($row['nome']) . '">';
                        echo '<div class="info">';
                        echo '<h3>' . htmlspecialchars($row['nome']) . '</h3>';
                        echo '<p>R$ ' . htmlspecialchars($row['valor']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Nenhum espaço cadastrado.</p>';
                }

                $conn->close(); // Fecha a conexão com o banco de dados
                ?>
            </div>
        </div>
        <!-- Lista de imóveis -->
        <div class="lista-imoveis">
            <h2>Imóveis Cadastrados</h2>
            <ul>
                <?php
                include 'conexaoBD.php'; // Inclui o arquivo de conexão

                // Consulta para selecionar os espaços cadastrados
                $sql = "SELECT nome, valor, imagens FROM espacos";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Saída de dados de cada linha
                    while ($row = $result->fetch_assoc()) {
                        // Decodifica as imagens do formato JSON
                        $imagens = json_decode($row['imagens']);
                        $imagem_principal = !empty($imagens) ? $imagens[0] : 'img/default.png'; // Imagem padrão caso não tenha

                        echo '<li>';
                        echo '<img src="' . htmlspecialchars($imagem_principal) . '" alt="' . htmlspecialchars($row['nome']) . '" class="mini-imagem">';
                        echo '<div class="descricao">';
                        echo '<h3>' . htmlspecialchars($row['nome']) . '</h3>';
                        echo '<p>R$ ' . htmlspecialchars($row['valor']) . '</p>';
                        echo '</div>';
                        echo '</li>';
                    }
                } else {
                    echo '<li>Nenhum espaço cadastrado.</li>';
                }

                $conn->close(); // Fecha a conexão com o banco de dados
                ?>
            </ul>
        </div>

        <nav name="navBar" id="navBar">
            <ul class="navlinks">
                <li><a href="inicio.php"><ion-icon name="home-outline"></ion-icon></a></li>
                <li><a href="cadastro.php"><ion-icon name="add-outline"></ion-icon></a></li>
                <li><a href="dashbord.php"><ion-icon name="grid-outline"></ion-icon></a></li>
                <li><a href="perfil.php"><ion-icon name="person-outline"></ion-icon></a></li>
            </ul>
        </nav>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>