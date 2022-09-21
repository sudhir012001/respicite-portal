<?php 
ob_start();
$code = base64_decode($_GET['code']);
include('dbconn.php');
include('MI_score_calculation.php');

require_once('general_functions.php');

use setasign\Fpdi\Fpdi;
require_once('fpdf181/fpdf181/fpdf.php');
require_once('fpdi2/fpdi2/src/autoload.php');
require_once('cat_functions.php');
// Create new Landscape PDF
$pdf = new FPDI();
include('Second_detail_page.php');
// Reference the PDF you want to use (use relative path)
$pagecount = $pdf->setSourceFile('report_template/Cat_Report.pdf');

// Import the first page from the PDF and add to dynamic PDF

//Page No 1
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
// End of Page no 1

//Start Page no 2
$infant_ei_score = infant_ei_calculation($con,$code);
$pdf->SetXY(130,92);
$pdf->SetFontSize(18);
$pdf->Cell(132, 19,$infant_ei_score, 0, 0, 'L',false);

$cpm_score = iq_cpm_score_calculation($con,$code);
iq_cpm_score_discrpency($con,$cpm_score);
// $pdf->SetXY(130,228);
// $pdf->SetFontSize(18);
// $pdf->Cell(132, 19,$cpm_score, 0, 0, 'L',false);

$iq_cpm_percentile = iq_cpm_percentile($con, $cpm_score, $dob);
$pdf->SetXY(122, 229);
$pdf->SetFont('arial');
$pdf->Cell(0,20,$iq_cpm_percentile, 0, 0,'L',false); 

$r_detail_sql = "select * from reseller_homepage where r_email='$r_id'";
$r_detail_res = mysqli_query($con,$r_detail_sql);
$r_detail_row = mysqli_fetch_array($r_detail_res);
$logo = $r_detail_row['logo'];
$logo = 'http://localhost/users.cgcareers.online/'.$logo;
function checking_size($logo,$pdf)
{
    $size = getimagesize($logo);
    $wImg = $size[0];
    $hImg = $size[1];
    if($wImg<=512 && $hImg<=512)
    {
        $pdf->SetXY(170, 8);
        $pdf->SetFont('arial');
        $pdf->Cell(0,20,$pdf->Image($logo,$pdf->GetX(), $pdf->GetY(),30), 0, 0,'R',false);
    }
    else
    {
        $pdf->SetXY(150, 10);
        $pdf->SetFont('arial');
        $pdf->Cell(0,20,$pdf->Image($logo,$pdf->GetX(), $pdf->GetY(),50), 0, 0,'R',false);   
    }
}

checking_size($logo,$pdf);



//end Page no 2
//page 3
$pagecount = $pdf->setSourceFile('report_template/CAT_Report.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);
include('cat_sdq_score_calculation.php');

if($iq_cpm_percentile>=50 && $iq_cpm_percentile<75)
{
    $tpl = $pdf->importPage(4);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    // checking_size($logo,$pdf);
}
else
{
    $tpl = $pdf->importPage(3);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    // checking_size($logo,$pdf);
}

$pagecount = $pdf->setSourceFile('report_template/Counsellor Remarks.pdf');
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

$pdf->SetFontSize('9');
$pdf->SetXY(35,60);
$pdf->MultiCell(150,6,$c_remark);

ob_end_clean();
$pdf->AliasNbPages();

$pdf->Output();
// $pdf2->Output();

ob_end_flush();
?>