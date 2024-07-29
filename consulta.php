<?php
include 'config.php';

$sql = "SELECT * FROM clientes";
$result = $conn->query($sql);

$clientes = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
}

echo json_encode($clientes);

$conn->close();
?>
