<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workhub</title>
    <link rel="stylesheet" href="css/style.css">
     <!-- Favicon -->
     <link href="img/favicon.ico" rel="icon">
     <!-- Fonte -->
     <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>

</head>
<body>
    <!-- Inicio Destaque topo do site-->
    <div class="destaque">
        <!--Inicio navBar-->
        <nav name="navBar"id="navBar">
            <ul class="navlinks">
                <li><ion-icon name="home-outline"></ion-icon></li>
                <li><ion-icon name="heart-outline"></ion-icon></li>
                <li><ion-icon name="chatbubble-ellipses-outline"></ion-icon></li>
                <li><ion-icon name="person-outline"></ion-icon></li>
            </ul>
        </nav>
        <!--Fim navBar-->
        <!--Inicio container superior-->
        <div class="container-sup">
            <img src="img/Workhub logo.png" alt="LOCAHUB" class="logo">
            <h3>Local</h3>
            <div class="location">
                <ion-icon name="chevron-down-outline"></ion-icon>
                <h1>Telêmaco</h1>
            </div>
        </div>
        <!--Fim container superior-->
        <!--Inicio busca-->
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
        <!--Fim busca-->

        <!--Incio corpo do menu-->
        <div class="carousel-container">
            <a href="#"><span>Ver Mais</span></a>   
            <h2>O lugar certo para você</h2>
            <div class="carousel">
                <div class="slide">
                    <img src="img/casa-1.png" alt="Imóvel 1">
                    <div class="info">
                        <h3>HAIR BELEZA </h3>
                        <p>R$ 100.000</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="img/casa-2.png" alt="Imóvel 2">
                    <div class="info">
                        <h3>Imóvel 2</h3>
                        <p>R$ 150.000</p>
                    </div>
                </div>
                <!-- Adicione mais slides conforme necessário -->
            </div>
            <div class="recomendado"></div>
                <a href="#"><span>Ver Mais</span></a>   
                <h2>Recomendados</h2>
            <div class="carousel">
            </div>
        </div>
        </div>
        <!--Fim corpo do menu-->
    </div>
    <!--Final destaque -->

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>