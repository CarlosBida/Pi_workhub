<?php
// Conectar ao banco de dados
include('conexaoBD.php');
session_start();

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
    echo "Imóvel não encontrado.";
    exit;
}

// Verifique se a coluna 'imagens' não está vazia
$imagens = json_decode($row['imagens']);
$imagem_principal = !empty($imagens) ? $imagens[0] : 'img/default.png';

// Processar a coluna 'disponibilidade'
$disponibilidade = explode(',', $row['disponibilidade']);
$events = []; // Inicializa o array de eventos

foreach ($disponibilidade as $dia) {
    $events[] = [
        'start' => trim($dia),
        'end' => trim($dia),
        'color' => '#FFCCCC'   // Cor para dias indisponíveis
    ];
}

// Busca as reservas aceitas para o espaço
$queryReservasAceitas = "SELECT data FROM reservas WHERE espaco_id = ? AND status = 'aceita'";
$stmtReservasAceitas = $conn->prepare($queryReservasAceitas);
$stmtReservasAceitas->bind_param("i", $id);
$stmtReservasAceitas->execute();
$resultReservasAceitas = $stmtReservasAceitas->get_result();

// Adiciona as reservas aceitas ao array de eventos
while ($rowReserva = $resultReservasAceitas->fetch_assoc()) {
    $events[] = [
        'start' => $rowReserva['data'],
        'end' => $rowReserva['data'],
        'color' => '#FFFF00'  // Cor para dias reservados (amarelo)
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="manifest" href="manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['nome']); ?> - Workhub</title>

    <link href="img/favicon.ico" rel="icon">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/lang/pt-br.js'></script>

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
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .imagem-principal {
            width: 100%;
            height: auto;
            border-radius: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        .info-container {
            margin-top: 15px;
        }
        .descricao {
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }
        .localizacao, .valor, .telefone, .capacidade, .tipo {
            font-size: 1.1em;
            margin-top: 5px;
            display: flex;
            align-items: center;
        }
        .localizacao i, .valor i, .telefone i, .capacidade i, .tipo i {
            margin-right: 8px;
            color: #007BFF;
        }
        .regras {
            color: #d9534f; /* Destaque para as regras */
            font-weight: bold;
            margin-top: 15px;
        }
        #calendar {
            margin-top: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: white;
            padding: 10px;
            z-index: 1; /* Certifique-se de que o calendário fique acima */
            position: relative;
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
            display: flex;
            background-color: rgba(255, 255, 255, 0.9); 
            justify-content: space-around; 
            align-items: center;
            position: fixed; 
            bottom: 0;
            width: 90%;
            border-radius: 30px;    
            padding: 10px 0; 
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1); 
            z-index: 10; 
        }
        .navlinks {
            display: flex; 
            justify-content: space-around; 
            width: 100%; 
        }
        .navlinks li {
            list-style: none;
            margin: 0; 
            padding: 0 15px; 
        }
        .navlinks li a {
            text-decoration: none; 
            color: inherit; 
            display: flex; 
            align-items: center; 
        }
        .navlinks li ion-icon {
            font-size: 33px;
        }
        .navlinks li a:hover ion-icon {
            color: #008f17; 
        }
        .legenda {
            display: flex;
            align-items: center;
            margin: 20px 0;
            font-size: 0.9em;
        }
        .legenda div {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border-radius: 3px;
        }
        .legenda div:first-child {
            background-color: #FFCCCC; /* Cor para dias indisponíveis */
        }
    </style>
</head>

<body>
    <div class="main-container">
        <a href="inicio.php" class="back-button">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>

        <img src="<?php echo htmlspecialchars($imagem_principal); ?>" alt="<?php echo htmlspecialchars($row['nome']); ?>" class="imagem-principal">

        <div class="info-container">
            <span class="descricao"><?php echo htmlspecialchars($row['nome']); ?></span>
            <div class="localizacao">
                <ion-icon name="location-outline"></ion-icon>
                <?php echo htmlspecialchars($row['localizacao']); ?>
            </div>
            <div class="telefone">
                <ion-icon name="call-outline"></ion-icon>
                <strong><?php echo htmlspecialchars($row['telefone']); ?></strong>
            </div>
            <div class="valor">
                <ion-icon name="cash-outline"></ion-icon>
                <strong>R$ <?php echo htmlspecialchars(number_format($row['valor'], 2, ',', '.')); ?></strong>
            </div>
            <div class="capacidade">
                <ion-icon name="people-outline"></ion-icon>
                <strong>Capacidade: <?php echo htmlspecialchars($row['capacidade']); ?> pessoas</strong>
            </div>
            <div class="tipo">
                <ion-icon name="business-outline"></ion-icon>
                <strong>Tipo: <?php echo htmlspecialchars($row['tipo']); ?></strong>
            </div>
            <div class="regras">
                <strong>Regras:</strong> <?php echo nl2br(htmlspecialchars($row['regras'])); ?>
            </div>
            <div class="descricao" style="color: black; margin-top: 15px;">
                <p><?php echo nl2br(htmlspecialchars($row['descricao'])); ?></p>
            </div>
        </div>
    </div>

    <div class="legenda">
        <div></div>
        <span>Dias em vermelho estão indisponíveis para locação.</span>
        <div></div>
        <span>Dias em amarelo estão reservados.</span>
    </div>

    <div id="calendar"></div>

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
        $(document).ready(function() {
            var reservedEvents = <?php echo json_encode($events); ?>; // Array de eventos reservados

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: false,
                selectable: true,
                eventLimit: true,
                locale: 'pt-br',
                events: reservedEvents,
                dayRender: function(date, cell) {
                    var isReserved = reservedEvents.some(function(event) {
                        return moment(date).isBetween(event.start, event.end, null, '[]');
                    });

                    if (isReserved) {
                        cell.css("background-color", "#FFFF00"); // Tom claro para dias reservados (amarelo)
                        cell.append("<span style='color: green; font-weight: bold;'>Reservado</span>");
                    } else {
                        var isUnavailable = reservedEvents.some(function(event) {
                            return moment(date).isBetween(event.start, event.end, null, '[]');
                        });

                        if (isUnavailable) {
                            cell.css("background-color", "#FFCCCC"); // Tom claro para dias indisponíveis
                            cell.append("<span style='color: red; font-weight: bold;'>Indisponível</span>");
                        }
                    }
                },
                dayClick: function(date, jsEvent, view) {
                    var formattedDate = moment(date).format('YYYY-MM-DD');
                    var isReserved = reservedEvents.some(function(event) {
                        return moment(date).isBetween(event.start, event.end, null, '[]');
                    });

                    if (isReserved) {
                        alert('Esse dia está indisponível para locação.');
                    } else {
                        if (confirm('Deseja solicitar a reserva para o dia ' + formattedDate + '?')) {
                            $.ajax({
                                url: 'solicitarReserva.php',
                                method: 'POST',
                                data: {
                                    data: formattedDate,
                                    espaco_id: <?php echo $id; ?>
                                },
                                success: function(response) {
                                    alert(response);
                                },
                                error: function() {
                                    alert('Erro ao solicitar a reserva. Tente novamente.');
                                }
                            });
                        }
                    }
                }
            });
        });
        
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