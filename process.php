<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $data_aquisicao = $_POST['data_aquisicao'];
    $periodicidade = $_POST['periodicidade'];
    $validade = $_POST['validade'];
    $data_cadastro = date('Y-m-d');

    $sql = "INSERT INTO clientes (nome, telefone, email, endereco, data_aquisicao, periodicidade, data_cadastro, validade) VALUES ('$nome', '$telefone', '$email', '$endereco', '$data_aquisicao', '$periodicidade', '$data_cadastro', '$validade')";

    if ($conn->query($sql) === TRUE) {
        echo "Novo cliente cadastrado com sucesso";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
