<?php
include 'conexaoBD.php'; // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $espaco_id = intval($_POST['espaco_id']);
    
    // Verifica se o campo de dados foi enviado
    if (isset($_POST['disponibilidade'])) {
        $datas = json_decode($_POST['disponibilidade'], true); // Decodifica o JSON
        
        // Prepara a inserção das reservas
        $stmt = $conn->prepare("INSERT INTO reservas (espaco_id, data_inicio, data_fim) VALUES (?, ?, ?)");
        
        foreach ($datas as $data) {
            // Para cada data, usamos a mesma data para início e fim
            $stmt->bind_param("iss", $espaco_id, $data, $data);
            $stmt->execute();
        }
        
        $stmt->close();
        header("Location: editarCadastro.php?id=$espaco_id&message=Reservas cadastradas com sucesso.");
    } else {
        header("Location: editarCadastro.php?id=$espaco_id&message=Nenhuma data selecionada.");
    }
    
    $conn->close();
}
?>