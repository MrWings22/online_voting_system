<?php
require('tcpdf/fpdf186/fpdf.php');

// Get data from the POST request
$fullname = $_POST['fullname'];
$position = $_POST['position'];
$department = $_POST['department'];

// Create a new PDF instance
$pdf = new FPDF('L', 'mm', 'A4'); // Landscape orientation, A4 size
$pdf->AddPage();

// Add background image
// Background image path
$backgroundImage = 'uploads/backgroundc1.jpg';
$pdf->Image($backgroundImage, 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight(), 'JPG', '', '', true, 300); 


// Add college logo
$pdf->Image('uploads/fulllogo.png', 15, 15, 80, 25); // Adjust logo size and position to fit in the white space

// Add certificate title
$pdf->SetFont('Arial', 'B', 28);
$pdf->SetTextColor(0, 51, 102); // Dark blue for the college name
$pdf->SetY(40);
$pdf->Cell(0, 15, 'BVM Holy Cross College', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 24);
$pdf->SetTextColor(153, 0, 76); // Dark purple for the certificate title
$pdf->Cell(0, 15, 'Certificate of Achievement', 0, 1, 'C');

// Add congratulatory message
$pdf->SetFont('Arial', '', 16);
$pdf->SetTextColor(0, 0, 0); // Black for message text
$pdf->Ln(10);
$pdf->MultiCell(0, 10, "This certificate is proudly presented to:", 0, 'C');

// Add fancy font for the candidate's name
$pdf->AddFont('fancyfont', '', 'Ballroomwaltz-GBam.php'); // Add the fancy font (ensure the font file is in the right folder)
$pdf->SetFont('FANCYFONT', '', 28); // Use the fancy font for the name
$pdf->Cell(0, 10, $fullname, 0, 1, 'C');
$pdf->Ln(5);

// Continue with the message text
$pdf->SetFont('Arial', '', 16);
$pdf->MultiCell(0, 10, "For winning the '$position' position from the $department department elections.\nYour hard work and commitment are greatly appreciated by the college.", 0, 'C');
$pdf->Ln(20);

// Add signature fields
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(90, 10, 'Principal', 0, 0, 'C');
$pdf->Cell(90, 10, '', 0, 0, 'C'); // Space between signatures
$pdf->Cell(90, 10, 'Election Coordinator', 0, 1, 'C');

// Add placeholders for seals under the signatures
$pdf->SetY(150); // Adjust position to fit under the signatures
$pdf->Cell(90, 20, 'Seal', 0, 0, 'C'); // Principal's seal (no box)
$pdf->Cell(90, 20, '', 0, 0, 'C'); // Empty space (no box)
$pdf->Cell(90, 20, 'Seal', 0, 1, 'C'); // Election Coordinator's seal (no box)


// Output the file as a download
$pdf->Output('D', 'Winner_Certificate.pdf');
?>
