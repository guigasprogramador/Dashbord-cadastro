<?php
include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$sql = "SELECT * FROM clientes WHERE data_vencimento = CURDATE()";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $clientes = "";
    while ($row = $result->fetch_assoc()) {
        $clientes .= "Nome: " . $row['nome'] . ", Telefone: " . $row['telefone'] . ", Email: " . $row['email'] . ", Endereço: " . $row['endereco'] . ", Data de Vencimento: " . $row['data_vencimento'] . "<br>";
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'guigasprogramador@gmail.com';
        $mail->Password = 'gabriel040522';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('guigasprogramador@gmail.com', 'Sistema de Clientes');
        $mail->addAddress('guuilher@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = 'Clientes Vencendo Hoje';
        $mail->Body = $clientes;

        $mail->send();
        echo 'E-mail enviado com sucesso';
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
} else {
    echo "Nenhum cliente vencendo hoje";
}

$conn->close();
?>
