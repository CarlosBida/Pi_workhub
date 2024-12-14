<?php

// Configurações de conexão
$servername = "localhost";
$username = "root";
$password = ""; // Deixe vazio se não houver senha
$dbname = "bdworkhub"; // Substitua pelo nome do seu banco de dados

// Cria a conexão
$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    echo "<p>Erro ao tentar conectar à Base de Dados <b>$dbname</b></p>";
}
?>