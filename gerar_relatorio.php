<?php
require 'vendor/autoload.php';
include 'config.php';

use setasign\Fpdi\Fpdi;
use setasign\Fpdf\Fpdf;

class PDF extends Fpdi
{
    // Cabeçalho
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Relatorio mensal de Validade de Clientes', 0, 1, 'C');
        $this->Ln(10);
    }

    // Rodapé
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Cria o PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Consulta ao banco de dados
$hoje = date('Y-m-d');
$alerta_sql = "SELECT * FROM clientes WHERE validade BETWEEN '$hoje' AND DATE_ADD('$hoje', INTERVAL 5 DAY)";
$alerta_result = $conn->query($alerta_sql);

if ($alerta_result->num_rows > 0) {
    while ($row = $alerta_result->fetch_assoc()) {
        
        $pdf->Cell(70, 10, $row['nome'], 1);
        $pdf->Cell(50, 10, $row['telefone'], 1);
        $pdf->Cell(30, 10, $row['data_aquisicao'], 1);
        $pdf->Cell(30, 10, $row['validade'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'Nenhum cliente com validade proxima', 1, 1, 'C');
}

$pdf->Output('D', 'relatorio_clientes.pdf');
?>
