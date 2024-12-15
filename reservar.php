<?php
// Conectar ao banco de dados
include('conexaoBD.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $espaco_id = $_POST['espaco_id'];

    // Verifica se o usuário está logado
    if (isset($_SESSION['user_id'])) {
        $usuario_id = $_SESSION['user_id'];
    } else {
        echo "Usuário não está logado.";
        exit;
    }

    // Insere a solicitação de reserva no banco de dados como 'pendente'
    $query = "INSERT INTO reservas (espaco_id, usuario_id, data, status) VALUES (?, ?, ?, 'pendente')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $espaco_id, $usuario_id, $data);

    if ($stmt->execute()) {
        echo "Solicitação de reserva enviada com sucesso! O dono do espaço será notificado.";
    } else {
        echo "Erro ao enviar a solicitação: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Método não permitido.";
}
?>