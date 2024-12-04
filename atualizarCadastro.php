<?php
include 'conexaoBD.php'; // Inclui o arquivo de conexão

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $localizacao = $_POST['localizacao'];
    $descricao = $_POST['descricao'];

    // Lidar com o upload de imagens (caso novas sejam enviadas)
    if (!empty($_FILES['imagens']['name'][0])) {
        $imagens = [];
        foreach ($_FILES['imagens']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['imagens']['name'][$key];
            $file_tmp = $_FILES['imagens']['tmp_name'][$key];
            $upload_directory = "uploads/"; // Diretório para salvar as imagens

            // Mover o arquivo para o diretório de uploads
            if (move_uploaded_file($file_tmp, $upload_directory . $file_name)) {
                $imagens[] = $upload_directory . $file_name;
            }
        }
        $imagens_json = json_encode($imagens);
    } else {
        // Se não foram enviadas novas imagens, mantenha as existentes
        $sql = "SELECT imagens FROM espacos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $imovel = $result->fetch_assoc();
        $imagens_json = $imovel['imagens'];
    }

    // Atualizar os dados do imóvel
    $sql = "UPDATE espacos SET nome = ?, valor = ?, localizacao = ?, descricao = ?, imagens = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsssi", $nome, $valor, $localizacao, $descricao, $imagens_json, $id);

    if ($stmt->execute()) {
        // Redirecionar para o dashboard com uma mensagem de sucesso
        header("Location: dashbord.php?message=Cadastro atualizado com sucesso!");
        exit();
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }
} else {
    echo "Método de requisição inválido ou ID do imóvel não especificado.";
}

$conn->close(); // Fecha a conexão com o banco de dados
?>