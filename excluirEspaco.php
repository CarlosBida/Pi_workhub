<?php
include 'conexaoBD.php'; // Inclui o arquivo de conexão

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Exclui as reservas relacionadas ao espaço
    $sql = "DELETE FROM reservas WHERE espaco_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Exclui o imóvel
    $sql = "DELETE FROM espacos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header('Location: inicio.php?message=Imóvel excluído com sucesso.');
    } else {
        header('Location: editarCadastro.php?id=' . $id . '&message=Erro ao excluir imóvel.');
    }

    $stmt->close();
    $conn->close();
} else {
    die('ID do imóvel não especificado.');
}
?>