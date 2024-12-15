<?php
// Conectar ao banco de dados
include('conexaoBD.php');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo "Você precisa estar logado para acessar esta página.";
    exit;
}

// Assume que o ID do dono do espaço está na sessão
$usuario_id = $_SESSION['user_id']; // Corrigido para 'user_id'

// Busca os espaços do dono
$queryEspacos = "SELECT id FROM espacos WHERE usuario_id = ?";
$stmtEspacos = $conn->prepare($queryEspacos);
$stmtEspacos->bind_param("i", $usuario_id);
$stmtEspacos->execute();
$resultEspacos = $stmtEspacos->get_result();

// Verifica se o dono possui espaços
if ($resultEspacos->num_rows === 0) {
    header("Location: dashbord.php?message=Você não possui espaços cadastrados.");
    exit;
}

// Armazena os IDs dos espaços
$espacos_ids = [];
while ($row = $resultEspacos->fetch_assoc()) {
    $espacos_ids[] = $row['id'];
}

// Busca as reservas pendentes para os espaços do dono
if (count($espacos_ids) > 0) {
    $espacos_ids_placeholder = implode(',', array_fill(0, count($espacos_ids), '?'));
    $queryReservas = "SELECT r.*, e.nome AS espaco_nome 
                      FROM reservas r 
                      JOIN espacos e ON r.espaco_id = e.id 
                      WHERE r.espaco_id IN ($espacos_ids_placeholder) AND r.status = 'pendente'";
    $stmtReservas = $conn->prepare($queryReservas);
    $stmtReservas->bind_param(str_repeat('i', count($espacos_ids)), ...$espacos_ids);
    $stmtReservas->execute();
    $resultReservas = $stmtReservas->get_result();
} else {
    $resultReservas = null; // Se não houver espaços, não busca reservas
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="manifest" href="manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Reservas - Workhub</title>
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
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .reserva {
            border: 1px solid #ccc;
            margin: 10px 0;
            padding: 15px;
            border-radius: 10px;
            background: #fdfdfd;
        }
        .botao {
            background-color: #008f17;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .botao.recusar {
            background-color: #d9534f; /* Vermelho para recusar */
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
            background-color: #FFCCCC;
            margin-right: 10px;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <h2>Reservas Pendentes</h2>

        <?php if ($resultReservas && $resultReservas->num_rows > 0): ?>
            <?php while ($row = $resultReservas->fetch_assoc()): ?>
                <div class="reserva">
                    <p><strong>Espaço:</strong> <?php echo htmlspecialchars($row['espaco_nome']); ?></p>
                    <p><strong>Data:</strong> <?php echo htmlspecialchars($row['data']); ?></p>
                    <p><strong>Usuário ID:</strong> <?php echo htmlspecialchars($row['usuario_id']); ?></p>
                    <p><strong>Status:</strong> Pendente</p>
                    <a href="aceitarReserva.php?id=<?php echo $row['id']; ?>" class="botao">Aceitar</a>
                    <a href="recusarReserva.php?id=<?php echo $row['id']; ?>" class="botao recusar">Recusar</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Não há reservas pendentes.</p>
        <?php endif; ?>

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
</body>
</html>