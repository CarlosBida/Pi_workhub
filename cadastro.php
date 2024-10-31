<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Imóvel - Workhub</title>
    <link rel="stylesheet" href="css/styleCadastro.css"> 
    <link href="img/favicon.ico" rel="icon">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
</head>
<body>
    <div class="info-1">Cadastre seu espaço</div>
    <div class="msg-1">Diga-nos mais sobre o seu espaço!</div>

    <form action="process_form.php" method="POST" enctype="multipart/form-data">
        <div class="form-control">
            <label class="placeholder">Tipo</label>
            <input type="text" name="tipo" required>
        </div>
        <div class="form-control">
            <label class="placeholder">Valor</label>
            <input type="number" name="valor" required>
        </div>
        <div class="form-control">
            <label class="placeholder">Nome do espaço</label>
            <input type="text" name="nome" required>
        </div>
        <div class="form-control">
            <label class="placeholder">Localização</label>
            <input type="text" name="localizacao" required>
        </div>
        <div class="form-control">
            <label class="placeholder">Uma breve descrição do seu espaço</label>
            <textarea name="descricao" required></textarea>
        </div>
        <div class="form-control">
            <label class="placeholder">Imagens</label>
            <input type="file" name="imagens[]" multiple required>
        </div>

        <div class="drag-and-drop-field">
            <div class="label-text">Envie as fotos de sua propriedade</div>
        </div>
        <div class="button">
            <input type="submit" value="Cadastrar" style="display:none;">
            Cadastrar
        </div>
    </form>

    <nav name="navBar" id="navBar">
        <ul class="navlinks">
            <li><ion-icon name="home-outline"></ion-icon></li>
            <li><ion-icon name="heart-outline"></ion-icon></li>
            <li><ion-icon name="chatbubble-ellipses-outline"></ion-icon></li>
            <li><ion-icon name="person-outline"></ion-icon></li>
        </ul>
    </nav>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>