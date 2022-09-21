<?php
ob_start();
$code = base64_decode($_GET['code']);
include('dbconn.php');

$sql_for_logo = "select * from user_code_list where code='$code'";
$res_for_logo = mysqli_query($con,$sql_for_logo);
$row_for_logo = mysqli_fetch_array($res_for_logo);
$reseller_id = $row_for_logo['reseller_id'];
$user_id = $row_for_logo['user_id'];
// include composer packages
use setasign\Fpdi\Fpdi;

require_once('fpdf181/fpdf181/fpdf.php');
require_once('fpdi2/fpdi2/src/autoload.php');

// Create new Landscape PDF
$pdf = new FPDI();


// Reference the PDF you want to use (use relative path)
$pagecount = $pdf->setSourceFile('report_template/SDP1_2Page.pdf');

// Import the first page from the PDF and add to dynamic PDF
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);


include('Second_Detail_Page.php');
$pagecount = $pdf->setSourceFile('report_template/SDP1.pdf');
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

    // add signature
    

//page 3 
$tpl = $pdf->importPage(3);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

checking_size($logo,$pdf);
$score = 0;
//checking score
$sql = "select * from wpa_part1";
$res = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($res))
{
    $qno = $row['qno'];
    $nature = $row['nature'];
    $r_a = $row['right_ans'];
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='sdp1_part1' and qno='$qno'";
    $res2 = mysqli_query($con,$sql2);
    $row2 = mysqli_fetch_array($res2);
    $ans = $row2['ans'];
    if($ans == $r_a)
    {
        $score = $score + 1;
    }
    
}

$pdf->SetFontSize('14');
$pdf->SetXY(58,215);
$pdf->Cell(20, 10,':-'.$score, 0, 0, 'L');

if($score>27)
{
    $pdf->SetFontSize('11');
    $tpl = $pdf->importPage(4);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

    checking_size($logo,$pdf);
}
else if($score>=22 && $score<=27)
{
    $tpl = $pdf->importPage(5);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

    checking_size($logo,$pdf);
}
else if($score<22)
{
    $tpl = $pdf->importPage(7);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

    checking_size($logo,$pdf);
}

//personality
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
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='sdp1_part2' and qno='$qno'";
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
$user_id = $row_for_logo['user_id'];

/* Commented by Sudhir on 19.10.2021******

$sql_m_f = "select * from user_details where email='$user_id'";
echo "User ID :".$user_id."<br>";
$res_m_f = mysqli_query($con,$sql_m_f);
$row_m_f = mysqli_fetch_array($res_m_f);
$gender = $row_m_f['gender'];

**********************************/

$gender = $row_for_logo['gender'];


$coma_count = 0;
$fa = '';
if($gender=='Male')
{
    //echo "Gender :".$gender."<br>";
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
    //echo "Gender :".$gender."<br>";
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
        //echo "Agreeableness ".$p4."<br>";
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
        //echo "Agreeableness ".$p4."<br>";
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
        //echo "Agreeableness ".$p4."<br>";
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

$p_score1 = ($p1*100)/($p_no1*4);
$p_score2 = ($p2*100)/($p_no2*4);
$p_score3 = ($p3*100)/($p_no3*4);
$p_score4 = ($p4*100)/($p_no4*4);
$p_score5 = ($p5*100)/($p_no4*4);

// include composer packages

// Create new Landscape PDF



// Reference the PDF you want to use (use relative path)
$pagecount = $pdf->setSourceFile('report_template/WLA.pdf');

// Import the first page from the PDF and add to dynamic PDF

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

$pdf->SetFontSize('11');
$pdf->SetXY(148,90);
$pdf->Cell(20, 10,$p3_status, 0, 0, 'L');

$pdf->SetXY(148,115);
$pdf->Cell(20, 10,$p5_status, 0, 0, 'L');

$pdf->SetXY(148,130);
$pdf->Cell(20, 10,$p2_status, 0, 0, 'L');

$pdf->SetXY(148,145);
$pdf->Cell(20, 10,$p4_status, 0, 0, 'L');

$pdf->SetXY(148,164);
$pdf->Cell(20, 10,$p1_status, 0, 0, 'L');


//start graph
$chartX=37;
$chartY=201;
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
$pdf->SetFont('Arial','B',10);
$pdf->SetXY($chartX,$chartY);
//$pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2));
// $pdf->Cell(100,10,"Personality",0,0,'C');


//legend properties
$legendX=139;
$legendY=203;

//draw th legend
$pdf->SetFont('Arial','',8);

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
$pdf->SetXY(30,265);
$pdf->Cell(20, 10,$fa, 0, 0, 'L');
$pdf->SetFontSize('18','B');
$pdf->SetXY(53,192);
$pdf->Cell(100,10,"Personality",0,0,'C');
$pagecount = $pdf->setSourceFile('report_template/Personality.pdf');
echo "Personality Scores : P1".$p1.", P2 :".$p2;

/********************************
 * commented by Sudhir on 19-10-21
 
if($p1>23)

********************************/

if($p1_status == 'Needs immediate attention' || $p1_status == 'Needs attention' )
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

/**********************************
 * commented by Sudhir on 19-10-21
 
if($p2<=23)

**********************************/


if($p2_status == 'Needs immediate attention' || $p2_status == 'Needs attention' )
{
    $tpl = $pdf->importPage(3);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    checking_size($logo,$pdf);
}


/**********************************
 * commented by Sudhir on 19-10-21
 *
if($p3<=23)
**********************************/


if($p3_status == 'Needs immediate attention' || $p3_status == 'Needs attention' )
{
    $tpl = $pdf->importPage(1);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    checking_size($logo,$pdf);
}


//if($p4<=26)



if($p4_status == 'Needs immediate attention' || $p4_status == 'Needs attention' )
{
    $tpl = $pdf->importPage(4);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    checking_size($logo,$pdf);
}



//if($p5<=28)
if($p5_status == 'Needs immediate attention' || $p5_status == 'Needs attention' )
{
    $tpl = $pdf->importPage(2);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    checking_size($logo,$pdf);
}

//end personality

//last page

$pagecount = $pdf->setSourceFile('report_template/SDP1.pdf');
$tpl = $pdf->importPage(8);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

checking_size($logo,$pdf);

$para = [
'Communication (Foundation)',
'Quality management',
'Task closure',
'Working with Information',
'Initiative & Effort'
];
$count = count($para);
for($j=0;$j<$count;$j++)
{
    $p_score[$j] = 0;
    $q_n[$j] = 0; 
    $p_per[$j] = 0; 
    $p_status[$j] =0; 
}
for($i=0;$i<$count;$i++)
{
    $cat = $para[$i];
    $sql3 = "select * from wpa_part2 where category='$cat'";
    $res3 = mysqli_query($con,$sql3);
    while($row3 = mysqli_fetch_array($res3))
    {
        $qno = $row3['qno'];
        $category = $row3['category'];
        $sub_category = $row3['sub_category'];
        $grp = $row3['grp'];
        $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='sdp1_part3' and qno='$qno'";
        $res4 = mysqli_query($con,$sql4);
        $row4 = mysqli_fetch_array($res4);
        $ans = $row4['first_ans'];
        if($ans == 'yes')
        {
            $sec_ans = $row4['sec_ans'];
            $p_score[$i] = $p_score[$i] + $sec_ans;
            $q_n[$i] = $q_n[$i] + 1; 

        }
        
    }
}

$coma_count = 0;
$fa = '';
$need_s = array();
for($j=0;$j<$count;$j++)
{
   
    $p_per[$j] = ($p_score[$j]*100)/($q_n[$j]*5); 
    if($p_per[$j]>0 && $p_per[$j]<=50)
    {
        $p_status[$j] = 'Needs immediate Attention';
        if($coma_count==0)
        {
            $fa = $fa.$para[$j];
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', '.$para[$j];
            $coma_count = 1;
        }
        array_push($need_s,$para[$j]);   
    }
    else if($p_per[$j]>50 && $p_per[$j]<=65)
    {
        $p_status[$j] = 'Needs Attention';
        if($coma_count==0)
        {
            $fa = $fa.$para[$j];
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', '.$para[$j];
            $coma_count = 1;
        }
        array_push($need_s,$para[$j]);
    }
    else if($p_per[$j]>65 && $p_per[$j]<=85)
    {
        $p_status[$j] = 'Satisfactory';
    }
    else if($p_per[$j]>85)
    {
        $p_status[$j] = 'Strength';   
    }
    else
    {
        $p_status[$j] = 'NA';
    }

 
}

$pdf->SetFontSize('9.5');
$pdf->SetXY(142,61);
$pdf->MultiCell(40, 6,$p_status[1]);

$pdf->SetXY(142,75);
$pdf->MultiCell(40, 6,$p_status[2]);

$pdf->SetXY(142,90);
$pdf->MultiCell(40, 6,$p_status[3]);

$pdf->SetXY(142,105);
$pdf->MultiCell(40, 6,$p_status[0]);

$pdf->SetXY(142,123);
$pdf->MultiCell(40, 6,$p_status[4]);




//graph
$chartX=35;
$chartY=175;
//dimension
$chartWidth=100;
$chartHeight=50;
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
$barWidth=7;

$data=Array(
    'A'=>['color'=>[24,203,238],'value'=>$p_per[0]],
    'B'=>['color'=>[172,13,26],'value'=>$p_per[1]],
    'C'=>['color'=>[247,253,4],'value'=>$p_per[2]],
    'D'=>['color'=>[77,106,12],'value'=>$p_per[3]],
    'E'=>['color'=>[255,82,0],'value'=>$p_per[4]],
    
    
);
$data2=Array(
    'A - '.$para[0]=>['color'=>[24,203,238],'value'=>$p_per[0]],
    'B - '.$para[1]=>['color'=>[172,13,26],'value'=>$p_per[1]],
    'C - '.$para[2]=>['color'=>[247,253,4],'value'=>$p_per[2]],
    'D - '.$para[3]=>['color'=>[77,106,12],'value'=>$p_per[3]],
    'E - '.$para[4]=>['color'=>[255,82,0],'value'=>$p_per[4]]
    
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
        $yAxisPos-1.85,
        $chartBoxX,
        $yAxisPos-1.85     
         );  
   }
   else if($i==20)
   {
    $pdf->Line(
        $chartBoxX+85,
        $yAxisPos-1.85,
        $chartBoxX,
        $yAxisPos-1.85     
         );  
   }
   else if($i==60)
   {
    $pdf->Line(
        $chartBoxX+85,
        $yAxisPos-3,
        $chartBoxX,
        $yAxisPos-3     
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
// $pdf->Cell(100,10,"Work Skills",0,0,'C');

//legend properties
$legendX=138;
$legendY=185;

//draw th legend
$pdf->SetFont('Arial','',7);

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
$pdf->SetXY(25,250);
$pdf->MultiCell(150,6,$fa);

$pdf->SetFontSize('16','B');
$pdf->SetXY(55,160);
$pdf->Cell(100,10,"Work Skills",0,0,'C');
$pdf->AddPage();

checking_size($logo,$pdf);
$ref=array();
//checking need attension question
foreach($need_s as $need_s)
{
    //echo $need_s."<br>";
    
    $sql = "select * from wpa_part2 where category='$need_s'";
    $res = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($res))
    {
        $qno = $row['qno'];
        $f1 = $row['category']." (".$row['sub_category'].")";
       
        $sql2 = "select * from wpa_part2_user_ans where qno='$qno' and code='$code'";
        $res2 = mysqli_query($con,$sql2);
        $row2 = mysqli_fetch_array($res2);
        if($row2['first_ans']=='yes' && $row2['sec_ans']=='1' || $row2['first_ans']=='yes' && $row2['sec_ans']=='2')
        {
            array_push($ref,array($f1,$row['item'],$row['inferences']));
        }
    }
}

$pdf->SetFont('Arial','B',12);
$pdf->SetXY(12.5,38);
$width_cell=array(38,30,48,70);
$pdf->SetTextColor(118,146,60);
$pdf->SetFillColor(219,233,201); // Background color of header 
// Header starts /// 
$pdf->cell(184,8,'Concerns, Suggestions',1,1,'L',true);
$pdf->SetFont('Arial','B',11);
$pdf->SetXY(12.5,46);
$pdf->SetTextColor(118,146,60);
$pdf->SetFillColor(219,233,201);
$pdf->Cell(30,6,'Dimension',1,0,'L',true); // First header column 
$pdf->Cell(55,6,'Description',1,0,'L',true); // Second header column

$pdf->Cell(99,6,'Status',1,1,'L',true); // Fourth header column

$pdf->SetFont('Arial','B',10);
$pdf->SetXY(12.5,52);
$width_cell=array(38,30,48,70);
$pdf->SetTextColor(118,146,60);
$pdf->SetFillColor(219,233,201); // Background color of header 
// Header starts /// 

$xn = $x = $pdf->GetX();
$yn = $y = $pdf->GetY();

$maxheight = 0;
$width=46;
$height=6;
$cells = 3;

//$width_cell=array(38,30,48,70);

$page_height = 300; // mm 
$pdf->SetAutoPageBreak(false);
foreach($ref as $item)
{
    
        $x = $x;
        $y = $y+$maxheight;
        $height_of_cell=$y-$yn;
        $space_left=$page_height-($y); // space left on page
        if ($height_of_cell > $space_left) 
        {
            // $pdf->Write($y+$yn,'Next');
            
            $pdf->AddPage();
           
            checking_size($logo,$pdf);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetXY(12.5,38);
            $width_cell=array(38,30,48,70);
            $pdf->SetTextColor(118,146,60);
            $pdf->SetFillColor(219,233,201); // Background color of header 
            // Header starts /// 
            $pdf->cell(184,8,'Concerns, Suggestions',1,1,'L',true);
            $pdf->SetFont('Arial','B',11);
            $pdf->SetXY(12.5,46);
            $pdf->SetTextColor(118,146,60);
            $pdf->SetFillColor(219,233,201);
            $pdf->Cell(30,6,'Dimension',1,0,'L',true); // First header column 
            $pdf->Cell(55,6,'Description',1,0,'L',true); // Second header column

            $pdf->Cell(99,6,'Status',1,1,'L',true); // Fourth header column
        //   $pdf->SetXY(12, 30);// page break
            $pdf->SetFont('Arial','B',10);
            $x=$xn;
            $y=$yn;
        }
        // $ty=$y;
        // $space_left=$page_height-$ty; // space left on page
        // if ($j/5==floor($j/5) && $ty > $space_left) {
        //   $pdf->AddPage(); // page break
        //   $x=$x;
        //   $y=$y;
        // }
        
           
        
            $maxheight = 0;
            for ($i = 0; $i < $cells; $i++) 
            {
                // $pdf->SetXY($x + ($width * ($i)) , $y);
                if($i==0)
                {
                    $pdf->SetXY($x + (0) , $y);
                    $pdf->MultiCell(30, $height, $item[$i]);
                }
                else if($i==1)
                {
                    $pdf->SetXY($x + (30) , $y);
                    $pdf->MultiCell(55, $height, $item[$i]); 
                }
                else
                {
                    $pdf->SetXY($x + (89) , $y);
                    $pdf->MultiCell(90, $height, $item[$i]);  
                }
                
                if ($pdf->GetY() - $y > $maxheight) 
                {
                    $maxheight = $pdf->GetY() - $y;
                    
                } 
                            
            }
            // $pdf->SetXY($x + ($width * ($i + 1)), $y);

            for ($i = 0; $i < $cells + 1; $i++) 
            {
                if($i==0)
                {
                    $pdf->Line($x + 30 * $i, $y, $x + 30 * $i, $y + $maxheight);
                }
                else if($i==1)
                {
                    $pdf->Line($x + 30 * $i, $y, $x + 30 * $i, $y + $maxheight); 
                }
                else if($i==2)
                {
                    $pdf->Line($x + 30 + 55, $y, $x + 30 + 55, $y + $maxheight); 
                }
                else
                {
                    $pdf->Line($x + 60*2 +60+4, $y, $x + 60*2 +60+4, $y + $maxheight);
                }
                

            }
            $pdf->Line($x, $y, $x + $width * 4, $y);// top line
            $pdf->Line($x, $y + $maxheight, $x + $width * 4, $y + $maxheight);//bottom
                        
} 
    

  
include('Remark.php');

ob_end_clean();
$pdf->AliasNbPages();

$pdf->Output();
// $pdf2->Output();

ob_end_flush();

?>