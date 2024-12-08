<?php
// Conexão com o banco de dados
include 'conexaoBD.php'; 

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário e faz a validação
    $nome = trim($_POST['nome']);
    $valor = trim($_POST['valor']);
    $localizacao = trim($_POST['localizacao']);
    $descricao = trim($_POST['descricao']);
    $telefone = trim($_POST['telefone']); // Captura o telefone

    // Verifica se os campos estão vazios
    if (empty($nome) || empty($valor) || empty($localizacao) || empty($descricao) || empty($telefone)) {
        $message = "Todos os campos são obrigatórios.";
    } else {
        // Processamento das imagens
        $imagens = [];
        if (isset($_FILES['imagens']) && !empty($_FILES['imagens']['name'][0])) {
            foreach ($_FILES['imagens']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['imagens']['name'][$key];
                $file_tmp = $_FILES['imagens']['tmp_name'][$key];
                $file_path = "uploads/" . basename($file_name);

                // Move a imagem para o diretório "uploads"
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $imagens[] = $file_path; // Armazena o caminho da imagem
                } else {
                    $message = "Erro ao fazer upload da imagem: $file_name";
                    break;
                }
            }
        }

        // Converte o array de imagens para JSON
        $imagens_json = json_encode($imagens);

        // Insere os dados no banco de dados
        $stmt = $conn->prepare("INSERT INTO espacos (nome, valor, localizacao, descricao, telefone, imagens) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissss", $nome, $valor, $localizacao, $descricao, $telefone, $imagens_json); // Adiciona o telefone

        if ($stmt->execute()) {
            $message = "Espaço cadastrado com sucesso!";
        } else {
            $message = "Erro ao cadastrar: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();

// Redireciona de volta para a página de cadastro com a mensagem
header("Location: cadastro.php?message=" . urlencode($message));
exit();
?>