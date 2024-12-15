<?php
// Conectar ao banco de dados
include('conexaoBD.php'); // Inclua sua conexão com o banco de dados

session_start(); // Inicia a sessão

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $espaco_id = $_POST['espaco_id'];

    // Verifica se o usuário está logado e obtém o ID do usuário
    if (isset($_SESSION['user_id'])) {
        $usuario_id = $_SESSION['user_id']; // Apenas o ID do usuário
    } else {
        echo "Usuário não está logado.";
        exit;
    }

    // Insere a solicitação de reserva no banco de dados
    $query = "INSERT INTO reservas (espaco_id, usuario_id, data, status) VALUES (?, ?, ?, 'pendente')";
    $stmt = $conn->prepare($query);
   
    if ($stmt === false) {
        echo "Erro ao preparar a consulta: " . $conn->error;
        exit;
    }

    // Bind apenas os IDs e a data
    $stmt->bind_param("iis", $espaco_id, $usuario_id, $data);
    
    if ($stmt->execute()) {
        echo "Solicitação de reserva enviada com sucesso!";
    } else {
        echo "Erro ao enviar a solicitação: " . $stmt->error; // Loga o erro do statement
    }
    $stmt->close();
} else {
    echo "Método não permitido.";
}
?>