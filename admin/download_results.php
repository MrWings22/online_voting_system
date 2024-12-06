<?php
// Include FPDF library
require_once('tcpdf\fpdf186\fpdf.php');

include 'db_connection.php';

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, 'Election Results', 0, 1, 'C');
$pdf->Ln(10); // Line break

// Get data from the database
$current_date = date("Y-m-d");
$election_status = $conn->query("SELECT end_date FROM elections WHERE end_date <= '$current_date' LIMIT 1");

if ($election_status && $election_status->num_rows > 0) {
    // Fetch winners
    $winners_main_union = $conn->query("SELECT fullname, votes FROM candidate WHERE position = 'Main Union' ORDER BY votes DESC")->fetch_all(MYSQLI_ASSOC);
    $winners_dept_rep = $conn->query("SELECT fullname, votes, department FROM candidate WHERE position = 'Department Representative' ORDER BY votes DESC")->fetch_all(MYSQLI_ASSOC);
    $winners_class_rep = $conn->query("SELECT fullname, votes, department, batch FROM candidate WHERE position = 'Class Representative' ORDER BY votes DESC")->fetch_all(MYSQLI_ASSOC);

    // Table for Main Union Winners
    if ($winners_main_union) {
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Main Union Winners', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(80, 10, 'Name', 1);
        $pdf->Cell(40, 10, 'Votes', 1, 1);
        foreach ($winners_main_union as $winner) {
            $pdf->Cell(80, 10, $winner['fullname'], 1);
            $pdf->Cell(40, 10, $winner['votes'], 1, 1);
        }
        $pdf->Ln(5); // Line break
    }

    // Table for Department Representative Winners
    if ($winners_dept_rep) {
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Department Representative Winners', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(60, 10, 'Name', 1);
        $pdf->Cell(50, 10, 'Department', 1);
        $pdf->Cell(40, 10, 'Votes', 1, 1);
        foreach ($winners_dept_rep as $winner) {
            $pdf->Cell(60, 10, $winner['fullname'], 1);
            $pdf->Cell(50, 10, $winner['department'], 1);
            $pdf->Cell(40, 10, $winner['votes'], 1, 1);
        }
        $pdf->Ln(5); // Line break
    }

    // Table for Class Representative Winners
    if ($winners_class_rep) {
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Class Representative Winners', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Name', 1);
        $pdf->Cell(50, 10, 'Department', 1);
        $pdf->Cell(40, 10, 'Batch', 1);
        $pdf->Cell(30, 10, 'Votes', 1, 1);
        foreach ($winners_class_rep as $winner) {
            $pdf->Cell(50, 10, $winner['fullname'], 1);
            $pdf->Cell(50, 10, $winner['department'], 1);
            $pdf->Cell(40, 10, $winner['batch'], 1);
            $pdf->Cell(30, 10, $winner['votes'], 1, 1);
        }
        $pdf->Ln(5); // Line break
    }
} else {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'No results published yet. The election might still be ongoing.', 0, 1, 'C');
}

// Output the PDF to the browser
$pdf->Output('D', 'Election_Results.pdf'); // Forces download
?>