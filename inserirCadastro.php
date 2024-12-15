<?php
// Conexão com o banco de dados
include 'conexaoBD.php'; 

$message = '';

// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Acesso negado. Você precisa estar logado.");
}

$userId = $_SESSION['user_id']; // Captura o ID do usuário logado

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário e faz a validação
    $nome = trim($_POST['nome']);
    $valor = trim($_POST['valor']);
    $localizacao = trim($_POST['localizacao']);
    $descricao = trim($_POST['descricao']);
    $telefone = trim($_POST['telefone']);
    $capacidade = trim($_POST['capacidade']);
    $tipo = trim($_POST['tipo']);
    $amenidades = trim($_POST['amenidades']);
    $regras = trim($_POST['regras']);
    $disponibilidade = isset($_POST['disponibilidade']) ? $_POST['disponibilidade'] : []; // Assume que é um array

    // Verifica se os campos estão vazios
    if (empty($nome) || empty($valor) || empty($localizacao) || 
        empty($descricao) || empty($telefone) || empty($capacidade) || 
        empty($tipo) || empty($amenidades) || empty($regras) || 
        empty($disponibilidade)) {
        $message = "Todos os campos são obrigatórios.";
    } else {
        // Processamento das imagens
        $imagens = [];
        if (isset($_FILES['imagens']) && !empty($_FILES['imagens']['name'][0])) {
            $uploadError = false; // Variável de controle para erros de upload

            foreach ($_FILES['imagens']['tmp_name'] as $key => $tmp_name) {
                $file_name = basename($_FILES['imagens']['name'][$key]);
                $file_tmp = $_FILES['imagens']['tmp_name'][$key];
                $file_path = "uploads/" . $file_name;

                // Verifica se a imagem foi enviada corretamente
                if ($_FILES['imagens']['error'][$key] === UPLOAD_ERR_OK) {
                    // Move a imagem para o diretório "uploads"
                    if (move_uploaded_file($file_tmp, $file_path)) {
                        $imagens[] = $file_path; // Armazena o caminho da imagem
                    } else {
                        $message = "Erro ao fazer upload da imagem: $file_name";
                        $uploadError = true; // Define erro de upload
                        break; // Sai do loop
                    }
                } else {
                    $message = "Erro ao enviar a imagem: $file_name";
                    $uploadError = true; // Define erro de upload
                    break; // Sai do loop
                }
            }

            // Se houve erro no upload, redireciona com a mensagem
            if ($uploadError) {
                header("Location: cadastro.php?message=" . urlencode($message));
                exit();
            }
        }

        // Converte o array de imagens para JSON
        $imagens_json = json_encode($imagens);

        // Insere os dados no banco de dados
        $stmt = $conn->prepare("INSERT INTO espacos (nome, valor, localizacao, descricao, telefone, capacidade, tipo, amenidades, regras, disponibilidade, imagens, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisssssssssi", $nome, $valor, $localizacao, $descricao, $telefone, $capacidade, $tipo, $amenidades, $regras, json_encode($disponibilidade), $imagens_json, $userId);

        if ($stmt->execute()) {
            // Mensagem de sucesso
            $message = "Cadastro realizado com sucesso!";
            // Redireciona para a página de cadastro com a mensagem de sucesso
            header("Location: cadastro.php?message=" . urlencode($message));
            exit();
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