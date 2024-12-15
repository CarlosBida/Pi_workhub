<?php
// Conectar ao banco de dados
include('conexaoBD.php');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo "Você precisa estar logado para acessar esta página.";
    exit;
}

// Verifica se o ID da reserva foi passado
if (isset($_GET['id'])) {
    $reserva_id = $_GET['id'];

    // Recupera a data da reserva e o usuario_id do solicitante
    $queryReserva = "SELECT data, usuario_id FROM reservas WHERE id = ?";
    $stmtReserva = $conn->prepare($queryReserva);
    $stmtReserva->bind_param("i", $reserva_id);
    $stmtReserva->execute();
    $stmtReserva->bind_result($data, $usuario_id);
    $stmtReserva->fetch();
    $stmtReserva->close();

    // Recupera o e-mail do usuário que solicitou a reserva
    $queryEmail = "SELECT email FROM usuarios WHERE id = ?";
    $stmtEmail = $conn->prepare($queryEmail);
    $stmtEmail->bind_param("i", $usuario_id);
    $stmtEmail->execute();
    $stmtEmail->bind_result($solicitante_email);
    $stmtEmail->fetch();
    $stmtEmail->close();

    // Atualiza o status da reserva para 'aceita'
    $queryAceitar = "UPDATE reservas SET status = 'aceita' WHERE id = ?";
    $stmtAceitar = $conn->prepare($queryAceitar);
    $stmtAceitar->bind_param("i", $reserva_id);

    if ($stmtAceitar->execute()) {
        // Após atualizar o status da reserva, insere a notificação
        $queryNotificacao = "INSERT INTO notificacoes (user_email, mensagem) VALUES (?, ?)";
        $stmtNotificacao = $conn->prepare($queryNotificacao);
        
        // Monta a mensagem de notificação
        $mensagemNotificacao = "Sua reserva para o dia " . $data . " foi aceita.";
        $stmtNotificacao->bind_param("ss", $solicitante_email, $mensagemNotificacao);
        $stmtNotificacao->execute();

        // Redireciona para a página de gerenciar reservas com uma mensagem de sucesso
        header("Location: gerenciaReservas.php?message=Reserva aceita com sucesso.");
    } else {
        echo "Erro ao aceitar a reserva.";
    }

    $stmtAceitar->close();
} else {
    echo "ID da reserva não fornecido.";
}

$conn->close();
?>