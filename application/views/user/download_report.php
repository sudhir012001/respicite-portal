<?php
ob_start();

// include composer packages
use setasign\Fpdi\Fpdi;
require_once(base_url().'OtherAjax/fpdf181/fpdf181/fpdf.php');
require_once(base_url().'OtherAjax/fpdi2/fpdi2/src/autoload.php');

// Create new Landscape PDF
$pdf = new FPDI();

// Reference the PDF you want to use (use relative path)
$pagecount = $pdf->setSourceFile( base_url().'OtherAjax/Report-Blank Template.pdf' );

// Import the first page from the PDF and add to dynamic PDF
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);


ob_end_clean();
$pdf->Output('d.pdf');
ob_end_flush();
?>