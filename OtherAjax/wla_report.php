<?php
ob_start();
$code = base64_decode($_GET['code']);
include('dbconn.php');
$p1 = 0;
$p2 = 0;
$p3 = 0;
$p4 = 0;
$p5 = 0;
$p_no1 = 0;
$p_no2 = 0;
$p_no3 = 0;
$p_no4 = 0;
$p_no5 = 0;
$sql = "select * from wla_part1_details";
$res = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($res))
{
    $qno = $row['qno'];
    $category = $row['category'];
    $nature = $row['nature'];
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='wla_part1' and qno='$qno'";
    $res2 = mysqli_query($con,$sql2);
    $row2 = mysqli_fetch_array($res2);
    $ans = $row2['ans'];
    if($nature=='F')
    {
        if($ans==1)
        {
            $temp_ans = 0;
        }
        else if($ans==2)
        {
            $temp_ans = 1;
        }
        else if($ans==3)
        {
            $temp_ans = 2;
        }
        else if($ans==4)
        {
            $temp_ans = 3;
        }
        else
        {
            $temp_ans = 4;   
        }
    }
    else
    {
        if($ans==1)
        {
            $temp_ans = 4;
        }
        else if($ans==2)
        {
            $temp_ans = 3;
        }
        else if($ans==3)
        {
            $temp_ans = 2;
        }
        else if($ans==4)
        {
            $temp_ans = 1;
        }
        else
        {
            $temp_ans = 0;   
        }
    }
    if($category=='N')
    {
        $p1 = $p1 + $temp_ans;
        $p_no1 = $p_no1 +1;
    }
    else if($category=='E')
    {
        $p2 = $p2 + $temp_ans;
        $p_no2 = $p_no2 +1;
    }
    else if($category=='O')
    {
        $p3 = $p3 + $temp_ans;
        $p_no3 = $p_no3 +1;
    }
    else if($category=='A')
    {
        $p4 = $p4 + $temp_ans;
        $p_no4 = $p_no4 +1;
    }
    else
    {
        $p5 = $p5 + $temp_ans;
        $p_no5 = $p_no5 +1;
    }
}

$sql_for_logo = "select * from user_code_list where code='$code'";
$res_for_logo = mysqli_query($con,$sql_for_logo);
$row_for_logo = mysqli_fetch_array($res_for_logo);
$reseller_id = $row_for_logo['reseller_id'];
/* Commented by Sudhir on 23 Oct 2021
$user_id = $row_for_logo['user_id'];
$sql_m_f = "select * from user_details where email='$user_id'";
$res_m_f = mysqli_query($con,$sql_m_f);
$row_m_f = mysqli_fetch_array($res_m_f);
$gender = $row_m_f['gender'];
************************************/

$gender = $row_for_logo['gender'];
echo "Gender :".$gender."<br>";
$coma_count = 0;
$fa = '';


if($gender=='Male')
{
    
    //checking N
    if($p1>0 && $p1<=8)
    {
        $p1_status = 'Strength';
    }
    else if($p1>8 && $p1<=15)
    {
        $p1_status = 'Satisfactory';
    }
    else if($p1>15 && $p1<=23)
    {
        $p1_status = 'Moderate';
    }
    else if($p1>23 && $p1<=30)
    {
        $p1_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Neuroticism';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Neuroticism';
            $coma_count = 1;
        }
    }
    else
    {
        $p1_status = 'Needs immediate Attention';
        if($coma_count==0)
        {
            $fa = $fa.'Neuroticism';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Neuroticism';
            $coma_count = 1;
        }
    }
    //checking E
    if($p2>0 && $p2<=17)
    {
        $p2_status = 'Needs immediate attention';
        if($coma_count==0)
        {
            $fa = $fa.'Extraversion';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Extraversion';
            $coma_count = 1;
        }
    }
    else if($p2>17 && $p2<=23)
    {
        $p2_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Extraversion';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Extraversion';
            $coma_count = 1;
        }
    }
    else if($p2>23 && $p2<=30)
    {
        $p2_status = 'Moderate';
    }
    else if($p2>30 && $p2<=36)
    {
        $p2_status = 'Satisfactory';
    }
    else
    {
        $p2_status = 'Strength';
    }
    //checking O
    if($p3>0 && $p3<=17)
    {
        $p3_status = 'Needs immediate attention';
        if($coma_count==0)
        {
            $fa = $fa.'Openness to experience';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Openness to experience';
            $coma_count = 1;
        }
    }
    else if($p3>17 && $p3<=23)
    {
        $p3_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Openness to experience';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Openness to experience';
            $coma_count = 1;
        }
    }
    else if($p3>23 && $p3<=30)
    {
        $p3_status = 'Moderate';
    }
    else if($p3>30 && $p3<=36)
    {
        $p3_status = 'Satisfactory';
    }
    else
    {
        $p3_status = 'Strength';
    }
    //checking A
    if($p4>0 && $p4<=21)
    {
        $p4_status = 'Needs immediate attention';
        if($coma_count==0)
        {
            $fa = $fa.'Agreeableness';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Agreeableness';
            $coma_count = 1;
        }
    }
    else if($p4>21 && $p4<=26)
    {
        $p4_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Agreeableness';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Agreeableness';
            $coma_count = 1;
        }
    }
    else if($p4>26 && $p4<=33)
    {
        $p4_status = 'Moderate';
    }
    else if($p4>33 && $p4<=38)
    {
        $p4_status = 'Satisfactory';
    }
    else
    {
        $p4_status = 'Strength';
    }
    //checking C
    if($p5>0 && $p5<=22)
    {
        $p5_status = 'Needs immediate attention';
        if($coma_count==0)
        {
            $fa = $fa.'Contentiousness';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Contentiousness';
            $coma_count = 1;
        }
    }
    else if($p5>22 && $p5<=28)
    {
        $p5_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Contentiousness';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Contentiousness';
            $coma_count = 1;
        }
    }
    else if($p5>28 && $p5<=35)
    {
        $p5_status = 'Moderate';
    }
    else if($p5>35 && $p5<=41)
    {
        $p5_status = 'Satisfactory';
    }
    else
    {
        $p5_status = 'Strength';
    }
}
else
{
    //checking N
    if($p1>0 && $p1<=9)
    {
        $p1_status = 'Strength';
    }
    else if($p1>9 && $p1<=17)
    {
        $p1_status = 'Satisfactory';
    }
    else if($p1>17 && $p1<=26)
    {
        $p1_status = 'Moderate';
    }
    else if($p1>26 && $p1<=34)
    {
        $p1_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Neuroticism';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Neuroticism';
            $coma_count = 1;
        }
    }
    else
    {
        $p1_status = 'Needs immediate attention';
        if($coma_count==0)
        {
            $fa = $fa.'Neuroticism';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Neuroticism';
            $coma_count = 1;
        }
    }
    //checking E
    if($p2>0 && $p2<=19)
    {
        $p2_status = 'Needs immediate attention';
        if($coma_count==0)
        {
            $fa = $fa.'Extraversion';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Extraversion';
            $coma_count = 1;
        }
    }
    else if($p2>19 && $p2<=25)
    {
        $p2_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Extraversion';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Extraversion';
            $coma_count = 1;
        }
    }
    else if($p2>25 && $p2<=32)
    {
        $p2_status = 'Moderate';
    }
    else if($p2>32 && $p2<=38)
    {
        $p2_status = 'Satisfactory';
    }
    else
    {
        $p2_status = 'Strength';
    }
    //checking O
    if($p3>0 && $p3<=19)
    {
        $p3_status = 'Needs immediate attention';
        if($coma_count==0)
        {
            $fa = $fa.'Openness to experience';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Openness to experience';
            $coma_count = 1;
        }
    }
    else if($p3>19 && $p3<=25)
    {
        $p3_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Openness to experience';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Openness to experience';
            $coma_count = 1;
        }
    }
    else if($p3>25 && $p3<=32)
    {
        $p3_status = 'Moderate';
    }
    else if($p3>32 && $p3<=38)
    {
        $p3_status = 'Satisfactory';
    }
    else
    {
        $p3_status = 'Strength';
    }
    //checking A
    if($p4>0 && $p4<=24)
    {
        $p4_status = 'Needs immediate attention';
        if($coma_count==0)
        {
            $fa = $fa.'Agreeableness';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Agreeableness';
            $coma_count = 1;
        }
    }
    else if($p4>24 && $p4<=30)
    {
        $p4_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Agreeableness';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Agreeableness';
            $coma_count = 1;
        }
    }
    else if($p4>30 && $p4<=36)
    {
        $p4_status = 'Moderate';
    }
    else if($p4>36 && $p4<=42)
    {
        $p4_status = 'Satisfactory';
    }
    else
    {
        $p4_status = 'Strength';
    }
    //checking C
    if($p5>0 && $p5<=22)
    {
        $p5_status = 'Needs immediate attention';
        if($coma_count==0)
        {
            $fa = $fa.'Contentiousness';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Contentiousness';
            $coma_count = 1;
        }

    }
    else if($p5>22 && $p5<=29)
    {
        $p5_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Contentiousness';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Contentiousness';
            $coma_count = 1;
        }
    }
    else if($p5>29 && $p5<=36)
    {
        $p5_status = 'Moderate';
    }
    else if($p5>36 && $p5<=42)
    {
        $p5_status = 'Satisfactory';
    }
    else
    {
        $p5_status = 'Strength';
    }
}


echo "Personality Score & Status:<br>";
echo "Neuroticism Score :".$p1." Count :".$p_no1." Status :".$p1_status."<br>";
echo "Extraversion :".$p2." Count :".$p_no2."Status :".$p2_status."<br>";
echo "Openness :".$p3." Count :".$p_no3."Status :".$p3_status."<br>";
echo "Agreeableness :".$p4." Count :".$p_no4."Status :".$p4_status."<br>";
echo "Conscientiousness :".$p5." Count :".$p_no5."Status :".$p5_status."<br>";


$p_score1 = ($p1*100)/($p_no1*4);
$p_score2 = ($p2*100)/($p_no2*4);
$p_score3 = ($p3*100)/($p_no3*4);
$p_score4 = ($p4*100)/($p_no4*4);
$p_score5 = ($p5*100)/($p_no4*4);

// include composer packages
use setasign\Fpdi\Fpdi;

require_once('fpdf181/fpdf181/fpdf.php');
require_once('fpdi2/fpdi2/src/autoload.php');

// Create new Landscape PDF
$pdf = new FPDI();


// Reference the PDF you want to use (use relative path)


// Import the first page from the PDF and add to dynamic PDF
$pagecount = $pdf->setSourceFile('report_template/WLA_Page1_2.pdf');
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);


//Include client details
include('Second_Detail_Page.php');

//Import Page 2 of header pages
$pagecount = $pdf->setSourceFile('report_template/WLA_Page1_2.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
$pagecount = $pdf->setSourceFile('report_template/WLA.pdf');

$sql_for_reseller_info = "select * from reseller_homepage where r_email='$reseller_id'";
$res_for_reseller_info = mysqli_query($con,$sql_for_reseller_info);
$row_for_reseller_info = mysqli_fetch_array($res_for_reseller_info);
$logo = $row_for_reseller_info['logo'];
$logo = 'https://users.respicite.com/'.$logo;
// $logo = '../uploads/default.jpg';
$size = getimagesize($logo);
$wImg = $size[0];
$hImg = $size[1];

// if($wImg == $hImg){
//      if ($wImg <=278){

//      } else {

//      }
// } else {

// }
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


    // add signature
    

//page 3 
$tpl = $pdf->importPage(3);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

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

$pdf->SetFontSize('10');
$pdf->SetXY(148,90);
$pdf->Cell(20, 10,$p3_status, 0, 0, 'L');

$pdf->SetXY(148,112);
$pdf->Cell(20, 10,$p5_status, 0, 0, 'L');

$pdf->SetXY(148,128);
$pdf->Cell(20, 10,$p2_status, 0, 0, 'L');

$pdf->SetXY(148,144);
$pdf->Cell(20, 10,$p4_status, 0, 0, 'L');

$pdf->SetXY(148,164);
$pdf->Cell(20, 10,$p1_status, 0, 0, 'L');


//start graph
$chartX=37;
$chartY=199;
//dimension
$chartWidth=100;
$chartHeight=45;
//padding
$chartTopPadding=10;
$chartLeftPadding=10;
$chartBottomPadding=10;
$chartRightPadding=5;
//chart box
$chartBoxX=$chartX+$chartLeftPadding;
$chartBoxY=$chartY+$chartTopPadding;
$chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
$chartBoxHeight=$chartHeight-$chartTopPadding-$chartBottomPadding;
//bar width
$barWidth=8;
//chart data
$data=Array(
        'N'=>['color'=>[0,255,0],'value'=>$p_score1],
        'E'=>['color'=>[0,25,10],'value'=>$p_score2],
        'O'=>['color'=>[0,105,100],'value'=>$p_score3],
        'A'=>['color'=>[100,105,100],'value'=>$p_score4],
        'C'=>['color'=>[10,25,100],'value'=>$p_score5]
);
$data2=Array(
    'N-Neuroticism'=>['color'=>[0,255,0],'value'=>$p_score1],
    'E-Extraversion'=>['color'=>[0,25,10],'value'=>$p_score2],
    'O-Openness to experience'=>['color'=>[0,105,100],'value'=>$p_score3],
    'A-Agreeableness'=>['color'=>[100,105,100],'value'=>$p_score4],
    'C-Contentiousness'=>['color'=>[10,25,100],'value'=>$p_score5]
    
);
//data max
$dataMax=0;
foreach($data as $item){
    if($item['value']>$dataMax)$dataMax=100;
} 
//data step
$dataStep=20;
//set font, line width, color
$pdf->SetFont('Arial', '',9);
$pdf->SetLineWidth(0.2);
$pdf->SetDrawColor(0);
//chart boundary
$pdf->Rect($chartX,$chartY,$chartWidth,$chartHeight);
//vertical axis line
$pdf->Line(
    $chartBoxX,
    $chartBoxY,
    $chartBoxX,
    $chartBoxY+$chartBoxHeight

);
//horizontal axis line
$pdf->Line(
    $chartBoxX,
    $chartBoxY+$chartBoxHeight,
    $chartBoxX+$chartBoxWidth,
    $chartBoxY+$chartBoxHeight

);
//vertical axis calculate chart y axis scale unit
$yAxisUnits=$chartBoxHeight/$dataMax;
//draw vertical y axis label
for($i=0;$i<=$dataMax; $i+=$dataStep){
    //y position
    $yAxisPos=$chartBoxY+($yAxisUnits*$i);
    //draw y axis line
    $pdf->Line(
   $chartBoxX-2,
   $yAxisPos,
   $chartBoxX,
   $yAxisPos     
    );
   
    //set cell position for y axis labels
    $pdf->SetXY($chartBoxX-$chartLeftPadding,$yAxisPos-2);
    //write label
    $pdf->Cell($chartLeftPadding-4,5,$dataMax-$i,0,0,'R');
    
}
//horizontal axis set cell width
$pdf->SetXY($chartBoxX,$chartBoxY+$chartBoxHeight);
//cell width
$xLabelWidth=$chartBoxWidth/ count($data);
//loop horizontal axis and draw the bars
$barXPos=0;
foreach($data as $itemName=>$item){
    //print the label
    $pdf->Cell($xLabelWidth,5,$itemName,0,0,'C');
//drawing the bar
//bar color
$pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);
//bar height
$barHeight=$yAxisUnits*$item['value'];
//bar x position
$barX=($xLabelWidth/2)+($xLabelWidth*$barXPos);
$barX=$barX-($barWidth/2);
$barX=$barX+$chartBoxX;
//bar y position
$barY=$chartBoxHeight-$barHeight;
$barY=$barY+$chartBoxY;
//draw the bar
$pdf->Rect($barX,$barY,$barWidth,$barHeight,'DF');
//increment $barXPos
$barXPos++;
}
$pdf->SetFont('Arial','B',16);
$pdf->SetXY($chartX,$chartY);
//$pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2));
$pdf->Cell(100,10,"Personality",0,0,'C');


//legend properties
$legendX=139;
$legendY=210;

//draw th legend
$pdf->SetFont('Arial','',8.5);

//store current legend Y position
$currentLegendY=$legendY;
foreach($data2 as $index=>$item)
{
//add legend color
$pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);

// remove border
$pdf->SetDrawColor($item['color'][0],$item['color'][1],$item['color'][2]);
$pdf->Rect($legendX,$currentLegendY,5,5,'DF');
$pdf->SetXY($legendX+6,$currentLegendY);
$pdf->Cell(50,5,$index,0,0);
$currentLegendY+=5;
}

$pdf->SetFontSize('11');
$pdf->SetXY(33,267);
$pdf->Cell(20, 10,$fa, 0, 0, 'L');

$pagecount = $pdf->setSourceFile('report_template/Personality.pdf');
if($p1>23)
{
    $tpl = $pdf->importPage(5);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

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
if($p2<=23)
{
    $tpl = $pdf->importPage(3);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
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
if($p3<=23)
{
    $tpl = $pdf->importPage(1);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
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
if($p4<=26)
{
    $tpl = $pdf->importPage(4);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
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
if($p5<=28)
{
    $tpl = $pdf->importPage(2);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
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
//WLA Part 1 #END here.
$pagecount = $pdf->setSourceFile('report_template/WLA.pdf');
$tpl = $pdf->importPage(4);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
$pp1 = 0;
$pp2 = 0;
$pp3 = 0;
$pp4 = 0;
$pp5 = 0;
$sql = "select * from wla_part2_details";
$res = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($res))
{
    $qno = $row['qno'];
    $category = $row['category'];
    $nature = $row['nature'];
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='wla_part2' and qno='$qno'";
    $res2 = mysqli_query($con,$sql2);
    $row2 = mysqli_fetch_array($res2);
    $ans = $row2['ans'];
    
    if($ans==1)
    {
        $temp_ans = 1;
    }
    else if($ans==2)
    {
        $temp_ans = 2;
    }
    else if($ans==3)
    {
        $temp_ans = 3;
    }
    else if($ans==4)
    {
        $temp_ans = 4;
    }
    else
    {
        $temp_ans = 5;   
    }
    
    if($category=='Empathy')
    {
        $pp1 = $pp1 + $temp_ans;
    }
    else if($category=='Manage emotions')
    {
        $pp2 = $pp2 + $temp_ans;
    }
    else if($category=='Motivating oneself')
    {
        $pp3 = $pp3 + $temp_ans;
    }
    else if($category=='Self-awareness')
    {
        $pp4 = $pp4 + $temp_ans;
    }
    else if($category=='Social skills')
    {
        $pp5 = $pp5 + $temp_ans;
    }
}


$pp1_score = $pp1*100/50;
$pp2_score = $pp2*100/50;
$pp3_score = $pp3*100/50;
$pp4_score = $pp4*100/50;
$pp5_score = $pp5*100/50;

echo "Empathy Score :"."<br>";
echo "Empathy :".$pp1."<br>";
echo "Manage Emotions :".$pp2."<br>";
echo "Motivating Oneself :".$pp3."<br>";
echo "Self-awareness :".$pp4."<br>";
echo "Social Skills :".$pp5."<br>";
$coma_count = 0;
$fa = '';
    //checking Empathy
if($pp1_score <= 50)
{
    $pp1_status = 'Immediately needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Empathy';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Empathy';
        $coma_count = 1;
    }
}
else if($pp1_score>50 && $pp1_score<=65)
{
    $pp1_status = 'Needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Empathy';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Empathy';
        $coma_count = 1;
    }
}
else if($pp1_score>65 && $pp1_score<=85)
{
    $pp1_status = 'Satisfactory';
}
else if($pp1_score>85)
{
    $pp1_status = 'Strength'; 
}
//checking Manage emotions
if($pp2_score <= 50)
{
    $pp2_status = 'Immediately needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Emotions';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Emotions';
        $coma_count = 1;
    }
}
else if($pp2_score>50 && $pp2_score<=65)
{
    $pp2_status = 'Needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Emotions';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Emotions';
        $coma_count = 1;
    }
}
else if($pp2_score>65 && $pp2_score<=85)
{
    $pp2_status = 'Satisfactory';
}
else if($pp2_score>85)
{
    $pp2_status = 'Strength'; 
}
//checking Motivating oneself
if($pp3_score <= 50)
{
    $pp3_status = 'Immediately needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Motivating oneself';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Motivating oneself';
        $coma_count = 1;
    }
}
else if($pp3_score>50 && $pp3_score<=65)
{
    $pp3_status = 'Needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Motivating oneself';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Motivating oneself';
        $coma_count = 1;
    }
}
else if($pp3_score>65 && $pp3_score<=85)
{
    $pp3_status = 'Satisfactory';
}
else if($pp3_score>85)
{
    $pp3_status = 'Strength'; 
}
//checking Self-awareness
if($pp4_score <= 50)
{
    $pp4_status = 'Immediately needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Self-awareness';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Self-awareness';
        $coma_count = 1;
    }
}
else if($pp4_score>50 && $pp4_score<=65)
{
    $pp4_status = 'Needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Self-awareness';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Self-awareness';
        $coma_count = 1;
    }
}
else if($pp4_score>65 && $pp4_score<=85)
{
    $pp4_status = 'Satisfactory';
}
else if($pp4_score>85)
{
    $pp4_status = 'Strength'; 
}

//checking Social skills
if($pp5_score <= 50)
{
    $pp5_status = 'Immediately needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Social skills';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Social skills';
        $coma_count = 1;
    }
}
else if($pp5_score>50 && $pp5_score<=65)
{
    $pp5_status = 'Needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Social skills';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Social skills';
        $coma_count = 1;
    }
}
else if($pp5_score>65 && $pp5_score<=85)
{
    $pp5_status = 'Satisfactory';
}
else if($pp5_score>85)
{
    $pp5_status = 'Strength'; 
}

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

$pdf->SetFontSize('11');
$pdf->SetXY(148,103);
$pdf->Cell(20, 10,$pp4_status, 0, 0, 'L');

$pdf->SetXY(148,117);
$pdf->Cell(20, 10,$pp2_status, 0, 0, 'L');

$pdf->SetXY(148,130);
$pdf->Cell(20, 10,$pp3_status, 0, 0, 'L');

$pdf->SetXY(148,143);
$pdf->Cell(20, 10,$pp1_status, 0, 0, 'L');

$pdf->SetXY(148,157);
$pdf->Cell(20, 10,$pp5_status, 0, 0, 'L');


//start graph
$chartX=37;
$chartY=190;
//dimension
$chartWidth=100;
$chartHeight=45;
//padding
$chartTopPadding=10;
$chartLeftPadding=10;
$chartBottomPadding=10;
$chartRightPadding=5;
//chart box
$chartBoxX=$chartX+$chartLeftPadding;
$chartBoxY=$chartY+$chartTopPadding;
$chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
$chartBoxHeight=$chartHeight-$chartTopPadding-$chartBottomPadding;
//bar width
$barWidth=8;
//chart data
$data=Array(
        'A'=>['color'=>[0,255,0],'value'=>$pp4_score],
        'B'=>['color'=>[0,25,10],'value'=>$pp2_score],
        'C'=>['color'=>[0,105,100],'value'=>$pp3_score],
        'D'=>['color'=>[100,105,100],'value'=>$pp1_score],
        'E'=>['color'=>[10,25,100],'value'=>$pp5_score]
);
$data2=Array(
    'A-Self-awareness'=>['color'=>[0,255,0],'value'=>$pp4_score],
    'B-Manage emotions'=>['color'=>[0,25,10],'value'=>$pp2_score],
    'C-Motivating oneself'=>['color'=>[0,105,100],'value'=>$pp3_score],
    'D-Empathy'=>['color'=>[100,105,100],'value'=>$pp1_score],
    'E-Social skills'=>['color'=>[10,25,100],'value'=>$pp5_score]
    
);
//data max
$dataMax=0;
foreach($data as $item){
    if($item['value']>$dataMax)$dataMax=100;
} 
//data step
$dataStep=20;
//set font, line width, color
$pdf->SetFont('Arial', '',9);
$pdf->SetLineWidth(0.2);
$pdf->SetDrawColor(0);
//chart boundary
$pdf->Rect($chartX,$chartY,$chartWidth,$chartHeight);
//vertical axis line
$pdf->Line(
    $chartBoxX,
    $chartBoxY,
    $chartBoxX,
    $chartBoxY+$chartBoxHeight

);
//horizontal axis line
$pdf->Line(
    $chartBoxX,
    $chartBoxY+$chartBoxHeight,
    $chartBoxX+$chartBoxWidth,
    $chartBoxY+$chartBoxHeight

);
//vertical axis calculate chart y axis scale unit
$yAxisUnits=$chartBoxHeight/$dataMax;
//draw vertical y axis label
for($i=0;$i<=$dataMax; $i+=$dataStep){
    //y position
    $yAxisPos=$chartBoxY+($yAxisUnits*$i);
    //draw y axis line
    $pdf->Line(
   $chartBoxX-2,
   $yAxisPos,
   $chartBoxX,
   $yAxisPos     
    );
    if($i==40)
   {
    $pdf->Line(
        $chartBoxX+85,
        $yAxisPos-2.5,
        $chartBoxX,
        $yAxisPos-2.5     
         );  
   }
   else if($i==20)
   {
    $pdf->Line(
        $chartBoxX+85,
        $yAxisPos-2.5,
        $chartBoxX,
        $yAxisPos-2.5     
         );  
   }
   else if($i==60)
   {
    $pdf->Line(
        $chartBoxX+85,
        $yAxisPos-2.5,
        $chartBoxX,
        $yAxisPos-2.5     
         );  
   }
    //set cell position for y axis labels
    $pdf->SetXY($chartBoxX-$chartLeftPadding,$yAxisPos-2);
    //write label
    $pdf->Cell($chartLeftPadding-4,5,$dataMax-$i,0,0,'R');
    
}
//horizontal axis set cell width
$pdf->SetXY($chartBoxX,$chartBoxY+$chartBoxHeight);
//cell width
$xLabelWidth=$chartBoxWidth/ count($data);
//loop horizontal axis and draw the bars
$barXPos=0;
foreach($data as $itemName=>$item){
    //print the label
    $pdf->Cell($xLabelWidth,5,$itemName,0,0,'C');
//drawing the bar
//bar color
$pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);
//bar height
$barHeight=$yAxisUnits*$item['value'];
//bar x position
$barX=($xLabelWidth/2)+($xLabelWidth*$barXPos);
$barX=$barX-($barWidth/2);
$barX=$barX+$chartBoxX;
//bar y position
$barY=$chartBoxHeight-$barHeight;
$barY=$barY+$chartBoxY;
//draw the bar
$pdf->Rect($barX,$barY,$barWidth,$barHeight,'DF');
//increment $barXPos
$barXPos++;
}
$pdf->SetFont('Arial','B',10);
$pdf->SetXY($chartX,$chartY);
//$pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2));
// $pdf->Cell(100,10,"Emotional Intelligence",0,0,'C');

//legend properties
$legendX=139;
$legendY=198;

//draw th legend
$pdf->SetFont('Arial','B');

//store current legend Y position
$currentLegendY=$legendY;
foreach($data2 as $index=>$item)
{
//add legend color
$pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);

// remove border
$pdf->SetDrawColor($item['color'][0],$item['color'][1],$item['color'][2]);
$pdf->Rect($legendX,$currentLegendY,5,5,'DF');
$pdf->SetXY($legendX+6,$currentLegendY);
$pdf->Cell(50,5,$index,0,0);
$currentLegendY+=5;
}
$pdf->SetFontSize('11');
$pdf->SetXY(33,265);
$pdf->Cell(20, 10,$fa, 0, 0, 'L');

$pdf->SetFontSize('16','B');
$pdf->SetXY(33,180);
$pdf->Cell(100,10,"Emotional Intelligence",0,0,'C');
$pagecount = $pdf->setSourceFile('report_template/emotional.pdf');
if($pp1_score<=65)
{
    $tpl = $pdf->importPage(5);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
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
if($pp2_score<=65)
{
    $tpl = $pdf->importPage(3);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
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
if($pp3_score<=65)
{
    $tpl = $pdf->importPage(4);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
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
if($pp4_score<=65)
{
    $tpl = $pdf->importPage(2);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
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
if($pp5_score<=65)
{
    $tpl = $pdf->importPage(6);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
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

include('Remark.php');

ob_end_clean();
$pdf->AliasNbPages();

$pdf->Output();
// $pdf2->Output();

ob_end_flush();


?>