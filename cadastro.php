<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Imóvel - Workhub</title>
    <link rel="stylesheet" href="css/styleCadastro.css"> 
    <link href="img/favicon.ico" rel="icon">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
    <link rel="manifest" href="manifest.json">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <div class="logo"><img src="img/Workhub logo.png" alt="Logo Workhub"></div>
    <div class="info-1">Cadastre seu espaço</div>
    <div class="msg-1">
        <?php echo isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Diga-nos mais sobre o seu espaço!'; ?>
    </div>

    <form action="inserirCadastro.php" method="POST" enctype="multipart/form-data">
        <div class="form-control">
            <label class="placeholder">Nome do espaço</label>
            <input type="text" name="nome" required>
        </div>
        <div class="form-control">
            <label class="placeholder">Valor</label>
            <input type="number" name="valor" required>
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
            <label class="placeholder">Telefone para contato</label>
            <input type="tel" name="telefone" required placeholder="(XX) XXXXX-XXXX">
        </div>
        <div class="form-control">
            <label class="placeholder">Capacidade (número de pessoas)</label>
            <input type="number" name="capacidade" required>
        </div>
        <div class="form-control">
            <label class="placeholder">Tipo de espaço</label>
            <select name="tipo" required>
                <option value="Sala de Reunião">Sala de Reunião</option>
                <option value="Escritório">Escritório</option>
                <option value="Salao">Salao</option>
                <option value="Estúdio">Estúdio</option>
                <option value="Coworking">Coworking</option>
            </select>
        </div>
        <div class="form-control">
            <label class="placeholder">Amenidades (separar por vírgulas)</label>
            <input type="text" name="amenidades" placeholder="Wi-Fi, Projetor, Ar-condicionado">
        </div>
        <div class="form-control">
            <label class="placeholder">Regras do espaço (separar por vírgulas)</label>
            <input type="text" name="regras" placeholder="Não fumar, Horário de silêncio após as 22h">
        </div>
        <div class="form-control">
            <label class="placeholder">Disponibilidade (selecione as datas indisponiveis)</label>
            <input type="text" id="datepicker" name="disponibilidade" readonly required>
        </div>
        <div class="form-control">
            <label class="placeholder">Imagens</label>
            <input type="file" name="imagens[]" multiple required>
        </div>
        <div class="cadastrar">
            <button type="submit">Cadastrar</button>
        </div>
    </form>

    <nav id="navBar">
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

    // Configuração do Flatpickr para selecionar múltiplas datas
    flatpickr("#datepicker", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        allowInput: true,
        onClose: function(selectedDates) {
            const dates = selectedDates.map(date => date.toISOString().split('T')[0]);
            document.getElementById("datepicker").value = dates.join(', ');
        }
    });
    </script>
</body>
</html>