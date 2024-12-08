<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="manifest" href="manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Imóvel - Workhub</title>
    <link rel="stylesheet" href="css/styleCadastro.css"> 
    <link href="img/favicon.ico" rel="icon">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
</head>
<body>
    <div class="logo"><img src="img/Workhub logo.png"></div>
    <div class="info-1">Edite seu espaço</div>
    <div class="msg-1">
        <?php 
        echo isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Atualize as informações do seu espaço!';
        ?>
    </div>

    <?php
    include 'conexaoBD.php'; // Inclui o arquivo de conexão

    // Verifica se o ID do imóvel foi passado
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        
        // Consulta para buscar os dados do imóvel
        $sql = "SELECT nome, valor, localizacao, descricao, telefone, imagens FROM espacos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se o imóvel foi encontrado
        if ($result->num_rows > 0) {
            $imovel = $result->fetch_assoc();
        } else {
            die('Imóvel não encontrado.');
        }
    } else {
        die('ID do imóvel não especificado.');
    }
    ?>

    <form action="atualizarCadastro.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        
        <div class="form-control">
            <label class="placeholder">Nome do espaço</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($imovel['nome']); ?>" required>
        </div>
        <div class="form-control">
            <label class="placeholder">Valor</label>
            <input type="number" name="valor" value="<?php echo htmlspecialchars($imovel['valor']); ?>" required>
        </div>
        <div class="form-control">
            <label class="placeholder">Localização</label>
            <input type="text" name="localizacao" value="<?php echo htmlspecialchars($imovel['localizacao']); ?>" required>
        </div>
        <div class="form-control">
            <label class="placeholder">Telefone para contato</label>
            <input type="tel" name="telefone" value="<?php echo htmlspecialchars($imovel['telefone']); ?>" required placeholder="(XX) XXXXX-XXXX">
        </div>
        <div class="form-control">
            <label class="placeholder">Uma breve descrição do seu espaço</label>
            <textarea name="descricao" required><?php echo htmlspecialchars($imovel['descricao']); ?></textarea>
        </div>
        <div class="form-control">
            <label class="placeholder">Imagens</label>
            <input type="file" name="imagens[]" multiple>
            <p>Imagens atuais: <?php 
                $imagens = json_decode($imovel['imagens']);
                foreach ($imagens as $imagem) {
                    echo '<img src="' . htmlspecialchars($imagem) . '" alt="Imagem do imóvel" width="50" height="50">';
                }
            ?></p>
        </div>
        <div class="cadastrar">
            <button type="submit">Atualizar</button>
        </div>
    </form>

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