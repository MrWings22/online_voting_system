<?php
// Include TCPDF library
require_once('tcpdf/tcpdf.php');

// Database connection
include 'db_connection.php';

// Get the current date
$current_date = date("Y-m-d");

// Fetch all winners if the election has ended
$election_status = $conn->query("SELECT end_date FROM elections WHERE end_date <= '$current_date' LIMIT 1");

if ($election_status && $election_status->num_rows > 0) {
    $winners_main_union = $conn->query("SELECT fullname, votes FROM candidate WHERE position = 'Main Union' ORDER BY votes DESC")->fetch_all(MYSQLI_ASSOC);
    $winners_dept_rep = $conn->query("SELECT fullname, votes, department FROM candidate WHERE position = 'Department Representative' ORDER BY votes DESC")->fetch_all(MYSQLI_ASSOC);
    $winners_class_rep = $conn->query("SELECT fullname, votes, department, batch FROM candidate WHERE position = 'Class Representative' ORDER BY votes DESC")->fetch_all(MYSQLI_ASSOC);
} else {
    echo "No results available yet.";
    exit;
}

// Create new PDF document
$pdf = new TCPDF();
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add title
$pdf->Cell(0, 10, 'Election Results', 0, 1, 'C');

// Add Main Union Results
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Main Union Winners', 0, 1, 'L');
foreach ($winners_main_union as $winner) {
    $pdf->Cell(0, 10, $winner['fullname'] . " with " . $winner['votes'] . " votes", 0, 1, 'L');
}

// Add Department Representative Results
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Department Representative Winners', 0, 1, 'L');
foreach ($winners_dept_rep as $winner) {
    $pdf->Cell(0, 10, $winner['fullname'] . " (" . $winner['department'] . ") with " . $winner['votes'] . " votes", 0, 1, 'L');
}

// Add Class Representative Results
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Class Representative Winners', 0, 1, 'L');
foreach ($winners_class_rep as $winner) {
    $pdf->Cell(0, 10, $winner['fullname'] . " (" . $winner['department'] . " - " . $winner['batch'] . " Batch) with " . $winner['votes'] . " votes", 0, 1, 'L');
}

// Output the PDF
$pdf->Output('Election_Results.pdf', 'D');
?>
