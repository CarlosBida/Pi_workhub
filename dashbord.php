<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="manifest" href="manifest.json">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workhub</title>
    <link rel="stylesheet" href="css/styleDash.css">
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    <!-- Fonte -->
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
</head>
<body>
    <div class="home-profissional">
    <?php
        session_start(); // Inicia a sessão
        if (isset($_GET['message'])) {
            echo '<div class="success-message">' . htmlspecialchars($_GET['message']) . '</div>';
        }

        include 'conexaoBD.php'; // Inclui o arquivo de conexão

        // Verifica se o usuário está logado
        if (!isset($_SESSION['user_id'])) {
            die("Acesso negado. Você precisa estar logado.");
        }

        $userId = $_SESSION['user_id']; // Captura o ID do usuário logado

        // Consulta para contar o número de solicitações pendentes do usuário
        $sql_count = "SELECT COUNT(*) as total FROM reservas WHERE usuario_id = ? AND status = 'pendente'";
        $stmt_count = $conn->prepare($sql_count);
        $stmt_count->bind_param("i", $userId);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $total_solicitacoes = 0; // Inicializa a variável
        if ($result_count->num_rows > 0) {
            $row_count = $result_count->fetch_assoc();
            $total_solicitacoes = $row_count['total']; // Obtém o total de solicitações pendentes
        }
        $stmt_count->close(); // Fecha o statement
    ?>
        <div class="rectangle-7"></div>
        <img class="notebook" src="img/notebook.svg" />
        <img class="subtract" src="img/Subtract.svg" />
        <div class="agenda">
        <a href="gerenciaReservas.php" style="text-decoration: none; color: inherit;">Solicitações</a>
        </div>
        <div class="_3"><?php echo htmlspecialchars($total_solicitacoes); ?></div> <!-- Exibe o número de solicitações pendentes -->
        <div class="rectangle-8"></div>
        <img class="subtract2" src="img/Subtract.svg" />
        <img class="chart-alt" src="img/Chart_alt.svg" />
        <div class="title">Solicitações Pendentes</div>
        <img class="work-removebg-preview-2" src="img/Workhub logo.png" />
    </div>
    <!-- Lista de imóveis -->
    <div class="lista-imoveis">
        <ul>
            <?php
            // Consulta para selecionar os espaços cadastrados pelo usuário
            $sql = "SELECT id, nome, valor, imagens, localizacao, descricao FROM espacos WHERE usuario_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Saída de dados de cada linha
                while ($row = $result->fetch_assoc()) {
                    // Decodifica as imagens do formato JSON
                    $imagens = json_decode($row['imagens']);
                    $imagem_principal = !empty($imagens) ? $imagens[0] : 'img/default.png'; // Imagem padrão caso não tenha

                    echo '<li>';
                    echo '<img src="' . htmlspecialchars($imagem_principal) . '" alt="' . htmlspecialchars($row['nome']) . '" class="mini-imagem" width="50" height="50">';
                    echo '<div class="descricao">';
                    echo '<h3><a href="editarCadastro.php?id=' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['nome']) . '</a></h3>';
                    echo '<p>R$ ' . htmlspecialchars(number_format($row['valor'], 2, ',', '.')) . '</p>';
                    echo '<p>Localização: ' . htmlspecialchars($row['localizacao']) . '</p>'; // Adiciona a localização
                    echo '<p>' . htmlspecialchars($row['descricao']) . '</p>'; // Adiciona a descrição
                    echo '</div>';
                    echo '</li>';
                }
            } else {
                echo '<li>Nenhum espaço cadastrado.</li>';
            }

            $stmt->close(); // Fecha o statement
            $conn->close(); // Fecha a conexão com o banco de dados
            ?>
        </ul>
    </div>
    <!-- Início navBar -->
    <nav name="navBar" id="navBar">
        <ul class="navlinks">
            <li><a href="inicio.php"><ion-icon name="home-outline"></ion-icon></a></li>
            <li><a href="cadastro.php"><ion-icon name="add-outline"></ion-icon></a></li>
            <li><a href="dashbord.php"><ion-icon name="grid-outline"></ion-icon></a></li>
            <li><a href="perfil.php"><ion-icon name="person-outline"></ion-icon></a></li>
        </ul>
    </nav>
    <!-- Fim navBar -->

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/app.js"></script>
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