<?php
// Conectar ao banco de dados
include('conexaoBD.php'); // Inclua sua conexão com o banco de dados

// Captura o ID do imóvel a partir da URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Busca os detalhes do imóvel no banco de dados
$query = "SELECT * FROM espacos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    // Se não encontrar o imóvel, redireciona ou mostra uma mensagem
    echo "Imóvel não encontrado.";
    exit;
}

$imagens = json_decode($row['imagens']);
$imagem_principal = !empty($imagens) ? $imagens[0] : 'img/default.png';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['nome']); ?> - Workhub</title>
    <link rel="stylesheet" href="css/styleDescricao.css">
    <link href="img/favicon.ico" rel="icon">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Raleway', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .main-container {
            padding: 20px;
            max-width: 400px; /* Largura máxima da caixa de conteúdo */
            margin: auto;
            background: white;
            border-radius: 20px; /* Bordas arredondadas */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .imagem-principal {
            width: 100%;
            height: auto;
            border-radius: 20px; /* Bordas arredondadas */
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Sombra leve */
        }
        .descricao {
            font-size: 1.5em;
            font-weight: bold; /* Negrito para destaque */
            margin-top: 15px; /* Espaçamento acima da descrição */
        }
        .localizacao, .valor {
            font-size: 1.1em; /* Tamanho da fonte */
            margin-top: 5px; /* Espaçamento acima */
            display: flex; /* Para alinhamento dos ícones */
            align-items: center; /* Centraliza verticalmente */
        }
        .localizacao i, .valor i {
            margin-right: 8px; /* Espaçamento entre o ícone e o texto */
            color: #007BFF; /* Cor dos ícones */
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 24px;
            color: #007BFF;
            text-decoration: none;
        }
        nav {
            background-color: #fff;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
        }
        .navlinks {
            list-style-type: none;
            text-align: center;
            padding: 0;
        }
        .navlinks li {
            display: inline;
            margin: 0 15px;
        }
        .navlinks a {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <a href="inicio.php" class="back-button">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>

        <img src="<?php echo htmlspecialchars($imagem_principal); ?>" alt="<?php echo htmlspecialchars($row['nome']); ?>" class="imagem-principal">

        <span class="descricao"><?php echo htmlspecialchars($row['nome']); ?></span>

        <div class="localizacao">
            <ion-icon name="location-outline" style="margin-right: 8px;"></ion-icon>
            <?php echo htmlspecialchars($row['localizacao']); ?>
        </div>

        <div class="valor">
            <ion-icon name="cash-outline" style="margin-right: 8px;"></ion-icon>
            <strong>R$ <?php echo htmlspecialchars(number_format($row['valor'], 2, ',', '.')); ?></strong>
        </div>
    </div>

    <nav name="navBar" id="navBar">
        <ul class="navlinks">
            <li><a href="inicio.php"><ion-icon name="home-outline"></ion-icon></a></li>
            <li><a href="cadastro.php"><ion-icon name="add-outline"></ion-icon></a></li>
            <li><a href="dashbord.php"><ion-icon name="grid-outline"></ion-icon></a></li>
            <li><a href="perfil.php"><ion-icon name="person-outline"></ion-icon></a></li>
        </ul>
    </nav>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>