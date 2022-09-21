<?php
ob_start();
$code = base64_decode($_GET['code']);
include('dbconn.php');
$p1 = 0;
$p2 = 0;
$p3 = 0;
$p4 = 0;
$p5 = 0;
$sql = "select * from sdq_detail";
$res = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($res))
{
    $qno = $row['qno'];
    $category = $row['category'];
    $nature = $row['nature'];
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part4' and qno='$qno'";
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
        else
        {
            $temp_ans = 2;   
        }
    }
    else
    {
        if($ans==1)
        {
            $temp_ans = 2;
        }
        else if($ans==2)
        {
            $temp_ans = 1;
        }
        else
        {
            $temp_ans = 0;   
        }
    }
    if($category=='Emotions')
    {
        $p1 = $p1 + $temp_ans;
    }
    else if($category=='Conduct')
    {
        $p2 = $p2 + $temp_ans;
    }
    else if($category=='Activity')
    {
        $p3 = $p3 + $temp_ans;
    }
    else if($category=='Peers')
    {
        $p4 = $p4 + $temp_ans;
    }
    else 
    {
        $p5 = $p5 + $temp_ans;
    }
}

// include composer packages
use setasign\Fpdi\Fpdi;
require_once('fpdf181/fpdf181/fpdf.php');
require_once('fpdi2/fpdi2/src/autoload.php');
// Create new Landscape PDF
$pdf = new FPDI();

// Reference the PDF you want to use (use relative path)
$pagecount = $pdf->setSourceFile('Report-Blank Template.pdf');

// Import the first page from the PDF and add to dynamic PDF
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
include('Second_Detail_Page.php');
$pagecount = $pdf->setSourceFile('Report-Blank Template.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
$sql_for_logo = "select * from user_code_list where code='$code'";
$res_for_logo = mysqli_query($con,$sql_for_logo);
$row_for_logo = mysqli_fetch_array($res_for_logo);
$reseller_id = $row_for_logo['reseller_id'];

$sql_for_reseller_info = "select * from reseller_homepage where r_email='$reseller_id'";
$res_for_reseller_info = mysqli_query($con,$sql_for_reseller_info);
$row_for_reseller_info = mysqli_fetch_array($res_for_reseller_info);
$logo = $row_for_reseller_info['logo'];
$logo = 'https://users.respicite.com/'.$logo;
// $logo = '../uploads/default.jpg';

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
$r1 = 0;
$r2 = 0;
$r3 = 0;
$r4 = 0;
$r5 = 0;

    
$sql3 = "select * from sdq_score_info where parameter='Emotions' and score='$p1'";
$res3 = mysqli_query($con,$sql3);
$row3 = mysqli_fetch_array($res3);
$result3 = $row3['result'];
$pdf->SetFont('Arial', 'B', '24');
$pdf->SetTextColor(51,102,0);
$pdf->SetFontSize('10');
$pdf->SetXY(66,239);
$pdf->Cell(20, 10, $result3, 0, 0, 'L');
if($result3=='Needs attention' || $result3=='Needs immediate attention')
{
    $r1 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(114,239);
    $pdf->Cell(20, 10,'Specific details given at the end of report', 0, 0, 'L');
}

else if ($result3=='Strength')
{
    $pdf->SetFontSize('10');
    $pdf->SetXY(114,239);
    $pdf->Cell(20, 10,'Overall OK, Specific details at the end', 0, 0, 'L');   
    
    
}

$sql4= "select * from sdq_score_info where parameter='Conduct' and score='$p2'";
$res4 = mysqli_query($con,$sql4);
$row4 = mysqli_fetch_array($res4);
$result4 = $row4['result'];
$pdf->SetFontSize('10');
$pdf->SetXY(66,244);
$pdf->Cell(20, 10, $result4, 0, 0, 'L');
if($result4=='Needs attention' || $result4=='Needs immediate attention')
{
    $r2 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(114,244);
    $pdf->Cell(20, 10,'Specific details given at the end of report', 0, 0, 'L');
}

else
if ($result4=='Strength')
{
    $pdf->SetFontSize('10');
    $pdf->SetXY(114,244);
    $pdf->Cell(20, 10,'Overall OK, Specific details at the end', 0, 0, 'L');
    
    
}

$sql5= "select * from sdq_score_info where parameter='Activity' and score='$p3'";
$res5 = mysqli_query($con,$sql5);
$row5 = mysqli_fetch_array($res5);
$result5 = $row5['result'];
$pdf->SetFontSize('10');
$pdf->SetXY(66,249);
$pdf->Cell(20, 10, $result5, 0, 0, 'L');
if($result5=='Needs attention' || $result5=='Needs immediate attention')
{
    $r3 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(114,249);
    $pdf->Cell(20, 10,'Specific details given at the end of report', 0, 0, 'L');
}
else
if ($result5=='Strength')
{
    
    $pdf->SetFontSize('10');
    $pdf->SetXY(114,249);
    $pdf->Cell(20, 10,'Overall OK, Specific details at the end', 0, 0, 'L'); 
    
}
$sql6= "select * from sdq_score_info where parameter='Peers' and score='$p4'";
$res6 = mysqli_query($con,$sql6);
$row6 = mysqli_fetch_array($res6);
$result6 = $row6['result'];
$pdf->SetFontSize('10');
$pdf->SetXY(66,254);
$pdf->Cell(20, 10, $result6, 0, 0, 'L');
if($result6=='Needs attention' || $result6=='Needs immediate attention')
{
    $r4 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(114,254);
    $pdf->Cell(20, 10,'Specific details given at the end of report', 0, 0, 'L');
}
else
if ($result6=="Strength")
{
    
    $pdf->SetFontSize('10');
    $pdf->SetXY(114,254);
    $pdf->Cell(20, 10,'Overall OK, Specific details at the end', 0, 0, 'L');
    
}



$sql7= "select * from sdq_score_info where parameter='Prosocial behaviour' and score='$p5'";
$res7 = mysqli_query($con,$sql7);
$row7 = mysqli_fetch_array($res7);
$result7 = $row7['result'];
$pdf->SetFontSize('10');
$pdf->SetXY(66,259);
$pdf->Cell(20, 10, $result7, 0, 0, 'L');
if($result7=='Needs attension' || $result7=='Needs immediate attension')
{
    $r5 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(114,259);
    $pdf->Cell(20, 10,'Specific details given at the end of report', 0, 0, 'L');
}

else 
if ($result7=="Strength")
{
    
    $pdf->SetFontSize('10');
    $pdf->SetXY(114,259);
    $pdf->Cell(20, 10,'Overall OK, Specific details at the end', 0, 0, 'L');
    
    
}

//page 3 
$tpl = $pdf->importPage(3);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

checking_size($logo,$pdf);

$pp1 = 0;
$pp2 = 0;
$pp3 = 0;
$pp4 = 0;
$pp5 = 0;
$pp6 = 0;
$pp7 = 0;
$pp8 = 0;

$sql8 = "select * from sha_details";
$res8 = mysqli_query($con,$sql8);
$i = 10;
while($row8 = mysqli_fetch_array($res8))
{
    $qno8 = $row8['qno'];
    $category8 = $row8['category'];
    
    $sql9 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part3' and qno='$qno8'";
    $res9 = mysqli_query($con,$sql9);
    $row9 = mysqli_fetch_array($res9);
    $ans9 = $row9['ans'];
    
    if($ans9==1)
    {
        $temp_ans = 4;
    }
    else if($ans9==2)
    {
        $temp_ans = 3;
    }
    else if($ans9==3)
    {
        $temp_ans = 2;
    }
    else if($ans9==4)
    {
        $temp_ans = 1;   
    }
   
    if($category8=='Test Anxiety Management')
    {
        $pp1 = $pp1 + $temp_ans;
    }
    else if($category8=='Time Management')
    {
        $pp2 = $pp2 + $temp_ans; 
    }
    else if($category8=='Writing Skills')
    {
        $pp3 = $pp3 + $temp_ans; 
    }
    else if($category8=='Reading Speed')
    {
        $pp4 = $pp4 + $temp_ans; 
    }
    else if($category8=='Concentration')
    {
        $pp5 = $pp5 + $temp_ans; 
    }
    else if($category8=='Reading Comprehension')  
    {
        $pp6 = $pp6 + $temp_ans; 
    }
    else if($category8=='Note Taking')
    {
        $pp7 = $pp7 + $temp_ans; 
    }
    else if($category8=='Test Preparation And Test Taking')
    {
        $pp8 = $pp8 + $temp_ans; 
    }
    $i = $i + 10;

}

$pp1_score = ($pp1*100) / 16;
$pp2_score = ($pp2*100) / 16;
$pp3_score = ($pp3*100) / 16;
$pp4_score = ($pp4*100) / 16;
$pp5_score = ($pp5*100) / 16;
$pp6_score = ($pp6*100) / 16;
$pp7_score = ($pp7*100) / 16;
$pp8_score = ($pp8*100) / 16;

$chartX=30;
$chartY=147;
//dimension
$chartWidth=110;
$chartHeight=65;
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
//chart data
// $data=Array(
//         'A'=>['color'=>[0,255,0],'value'=>$pp1_score],
//         'B'=>['color'=>[172,13,26],'value'=>$pp2_score],
//         'C'=>['color'=>[0,105,100],'value'=>$pp3_score],
//         'D'=>['color'=>[100,105,100],'value'=>$pp4_score],
//         'E'=>['color'=>[10,25,100],'value'=>$pp5_score],
//         'F'=>['color'=>[0,55,20],'value'=>$pp6_score],
//         'G'=>['color'=>[50,150,20],'value'=>$pp7_score],
//         'H'=>['color'=>[100,255,250],'value'=>$pp8_score]
// );
$data=Array(
    'A'=>['color'=>[24,203,238],'value'=>$pp1_score],
    'B'=>['color'=>[172,13,26],'value'=>$pp2_score],
    'C'=>['color'=>[247,253,4],'value'=>$pp3_score],
    'D'=>['color'=>[77,106,12],'value'=>$pp4_score],
    'E'=>['color'=>[255,82,0],'value'=>$pp5_score],
    'F'=>['color'=>[16,53,46],'value'=>$pp6_score],
    'G'=>['color'=>[219,244,169],'value'=>$pp7_score],
    'H'=>['color'=>[104,38,102],'value'=>$pp8_score]
);
$data2=Array(
    'A - Test Anxiety Management'=>['color'=>[24,203,238],'value'=>$pp1_score],
    'B - Time Management'=>['color'=>[172,13,26],'value'=>$pp2_score],
    'C - Writing Skills'=>['color'=>[247,253,4],'value'=>$pp3_score],
    'D - Reading Speed'=>['color'=>[77,106,12],'value'=>$pp4_score],
    'E - Concentration'=>['color'=>[255,82,0],'value'=>$pp5_score],
    'F - Reading Comprehension'=>['color'=>[16,53,46],'value'=>$pp6_score],
    'G - Note Taking'=>['color'=>[219,244,169],'value'=>$pp7_score],
    'H - Test Preparation And Test Taking'=>['color'=>[104,38,102],'value'=>$pp8_score]
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
        $chartBoxX+95,
        $yAxisPos-2.5,
        $chartBoxX,
        $yAxisPos-2.5     
         );  
   }
   else if($i==20)
   {
    $pdf->Line(
        $chartBoxX+95,
        $yAxisPos-2.5,
        $chartBoxX,
        $yAxisPos-2.5     
         );  
   }
   else if($i==60)
   {
    $pdf->Line(
        $chartBoxX+95,
        $yAxisPos-5,
        $chartBoxX,
        $yAxisPos-5     
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
foreach($data as $itemName=>$item)
{
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
$pdf->Cell(100,10,"STUDY HABITS STRENGTH",0,0,'C');

//legend properties
$legendX=141;
$legendY=157;

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

$pos = 232;

    // $pp = '$pp'.$i.'_score';
$s1 = 0;
$s2 = 0;
$s3 = 0;
$s4 = 0;
$s5 = 0;
$s6 = 0;
$s7 = 0;
$s8 = 0;
if($pp1_score<=65)
{
    $s1 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(28,$pos);
    $pdf->Cell(20, 10,'Test Anxiety Management', 0, 0, 'L');
    $pos = $pos + 5;
}
if($pp2_score<=65)
{
    $s2 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(28,$pos);
    $pdf->Cell(20, 10,'Time Management', 0, 0, 'L');
    $pos = $pos + 5;
}
if($pp3_score<=65)
{
    $s3 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(28,$pos);
    $pdf->Cell(20, 10,'Writing Skills', 0, 0, 'L');
    $pos = $pos + 5;
}
if($pp4_score<=65)
{
    $s4 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(28,$pos);
    $pdf->Cell(20, 10,'Reading Speed', 0, 0, 'L');
    $pos = $pos + 5;
}
if($pp5_score<=65)
{
    $s5 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(28,$pos);
    $pdf->Cell(20, 10,'Concentration', 0, 0, 'L');
    $pos = $pos + 5;
}
if($pp6_score<=65)
{
    $s6 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(28,$pos);
    $pdf->Cell(20, 10,'Reading Comprehension', 0, 0, 'L');
    $pos = $pos + 5;
}
if($pp7_score<=65)
{
    $s7 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(28,$pos);
    $pdf->Cell(20, 10,'Note Taking', 0, 0, 'L');
    $pos = $pos + 5;
}
if($pp8_score<=65)
{
    $s8 = 1;
    $pdf->SetFontSize('10');
    $pdf->SetXY(28,$pos);
    $pdf->Cell(20, 10,'Test Preparation And Test Taking', 0, 0, 'L');
    $pos = $pos + 5;
}
$tpl = $pdf->importPage(4);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

checking_size($logo,$pdf);
$tpl = $pdf->importPage(5);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

checking_size($logo,$pdf);
//mother scoring
$psm1 = 0;
$psm2 = 0;
$psm3 = 0;
$psm4 = 0;
$psm5 = 0;
$psm6 = 0;
$psm7 = 0;
$psm8 = 0;
$psm9 = 0;
$psm10 = 0;
$psm11 = 0;

$psm_no1 = 0;
$psm_no2 = 0;
$psm_no3 = 0;
$psm_no4 = 0;
$psm_no5 = 0;
$psm_no6 = 0;
$psm_no7 = 0;
$psm_no8 = 0;
$psm_no9 = 0;
$psm_no10 = 0;
$psm_no11 = 0;
$sql10 ="select * from ps_details";
$res10 = mysqli_query($con,$sql10);
while($row10 = mysqli_fetch_array($res10))
{
    $qno10 = $row10['qno'];
    $category = $row10['dimension'];
    $nature = $row10['type'];
    $sql11 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part1' and qno='$qno10'";
    $res11 = mysqli_query($con,$sql11);
    $row11 = mysqli_fetch_array($res11);
    $ans = $row11['ans'];
    if($nature=='F')
    {
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
            $temp_ans = 4;
        }
        else
        {
            $temp_ans = 5;   
        }
    }
    else
    {
        if($ans==1)
        {
            $temp_ans = 5;
        }
        else if($ans==2)
        {
            $temp_ans = 4;
        }
        else if($ans==3)
        {
            $temp_ans = 2;
        }
        else
        {
            $temp_ans = 1;   
        }
    }
    if($category=='Corporal punishment')
    {
        $psm1 = $psm1 + $temp_ans;
        $psm_no1 = $psm_no1 + 1;
    }
    else if($category=='Directiveness')
    {
        $psm2 = $psm2 + $temp_ans;
        $psm_no2 = $psm_no2 + 1;
    }
    else if($category=='Nonreasoning, punitive strategies')
    {
        $psm3 = $psm3 + $temp_ans;
        $psm_no3 = $psm_no3 + 1;
    }
    else if($category=='Verbal hostility')
    {
        $psm4 = $psm4 + $temp_ans;
        $psm_no4 = $psm_no4 + 1;
    }
    else if($category=='Democratic participation')
    {
        $psm5 = $psm5 + $temp_ans;
        $psm_no5 = $psm_no5 + 1;
    }
    else if($category=='Good natured/ easy going')
    {
        $psm6 = $psm6 + $temp_ans;
        $psm_no6 = $psm_no6 + 1;
    }
    else if($category=='Reasoning/ Induction')
    {
        $psm7 = $psm7 + $temp_ans;
        $psm_no7 = $psm_no7 + 1;
    }
    else if($category=='Warmth & Involvement')
    {
        $psm8 = $psm8 + $temp_ans;
        $psm_no8 = $psm_no8 + 1;
    }
    else if($category=='Ignoring mis-behaviour')
    {
        $psm9 = $psm9 + $temp_ans;
        $psm_no9 = $psm_no9 + 1;
    }
    else if($category=='Lack of follow-through')
    {
        $psm10 = $psm10 + $temp_ans;
        $psm_no10 = $psm_no10 + 1;
    }
    else if($category=='Self-confidence')
    {
        $psm11 = $psm11 + $temp_ans;
        $psm_no11 = $psm_no11 + 1;
    }

}

//Added by Sudhir on 25th Oct 2021
//Array of PS categories

$arr_ps_cat = array('Corporal punishment','Directiveness','Nonreasoning, punitive strategies',
                 'Verbal hostility','Democratic participation','Good natured/ easy going',
                 'Reasoning/ Induction','Warmth & Involvement','Ignoring mis-behaviour',
                 'Lack of follow-through','Self-confidence'
                 );
                 
echo "Parenting Style Categories :<pre>";
print_r($arr_ps_cat);

$psm_score1 = ($psm1*100)/($psm_no1*5);
$psm_score2 = ($psm2*100)/($psm_no2*5);
$psm_score3 = ($psm3*100)/($psm_no3*5);
$psm_score4 = ($psm4*100)/($psm_no4*5);
$psm_score5 = ($psm5*100)/($psm_no5*5);
$psm_score6 = ($psm6*100)/($psm_no6*5);
$psm_score7 = ($psm7*100)/($psm_no7*5);
$psm_score8 = ($psm8*100)/($psm_no8*5);
$psm_score9 = ($psm9*100)/($psm_no9*5);
$psm_score10 = ($psm10*100)/($psm_no10*5);
$psm_score11 = ($psm11*100)/($psm_no11*5);
//end of mother scoring

//Added by Sudhir on 25 Oct 2021
//Array of mother's score
$arr_psm_scr = array($psm_score1,$psm_score2,$psm_score3,
                 $psm_score4,$psm_score5,$psm_score6,
                 $psm_score7,$psm_score8,$psm_score9,
                 $psm_score10,$psm_score11
                 );

echo "Parenting Style Score - Mother :<pre>";
print_r($arr_psm_scr);

//father scoring
$psf1 = 0;
$psf2 = 0;
$psf3 = 0;
$psf4 = 0;
$psf5 = 0;
$psf6 = 0;
$psf7 = 0;
$psf8 = 0;
$psf9 = 0;
$psf10 = 0;
$psf11 = 0;

$psf_no1 = 0;
$psf_no2 = 0;
$psf_no3 = 0;
$psf_no4 = 0;
$psf_no5 = 0;
$psf_no6 = 0;
$psf_no7 = 0;
$psf_no8 = 0;
$psf_no9 = 0;
$psf_no10 = 0;
$psf_no11 = 0;
$sql12 ="select * from ps_details";
$res12 = mysqli_query($con,$sql12);
while($row12 = mysqli_fetch_array($res12))
{
    $qno12 = $row12['qno'];
    $category = $row12['dimension'];
    $nature = $row12['type'];
    $sql13 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part2' and qno='$qno12'";
    $res13 = mysqli_query($con,$sql13);
    $row13 = mysqli_fetch_array($res13);
    $ans = $row13['ans'];
    if($nature=='F')
    {
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
            $temp_ans = 4;
        }
        else
        {
            $temp_ans = 5;   
        }
    }
    else
    {
        if($ans==1)
        {
            $temp_ans = 5;
        }
        else if($ans==2)
        {
            $temp_ans = 4;
        }
        else if($ans==3)
        {
            $temp_ans = 2;
        }
        else
        {
            $temp_ans = 1;   
        }
    }
    if($category=='Corporal punishment')
    {
        $psf1 = $psf1 + $temp_ans;
        $psf_no1 = $psf_no1 + 1;
    }
    else if($category=='Directiveness')
    {
        $psf2 = $psf2 + $temp_ans;
        $psf_no2 = $psf_no2 + 1;
    }
    else if($category=='Nonreasoning, punitive strategies')
    {
        $psf3 = $psf3 + $temp_ans;
        $psf_no3 = $psf_no3 + 1;
    }
    else if($category=='Verbal hostility')
    {
        $psf4 = $psf4 + $temp_ans;
        $psf_no4 = $psf_no4 + 1;
    }
    else if($category=='Democratic participation')
    {
        $psf5 = $psf5 + $temp_ans;
        $psf_no5 = $psf_no5 + 1;
    }
    else if($category=='Good natured/ easy going')
    {
        $psf6 = $psf6 + $temp_ans;
        $psf_no6 = $psf_no6 + 1;
    }
    else if($category=='Reasoning/ Induction')
    {
        $psf7 = $psf7 + $temp_ans;
        $psf_no7 = $psf_no7 + 1;
    }
    else if($category=='Warmth & Involvement')
    {
        $psf8 = $psf8 + $temp_ans;
        $psf_no8 = $psf_no8 + 1;
    }
    else if($category=='Ignoring mis-behaviour')
    {
        $psf9 = $psf9 + $temp_ans;
        $psf_no9 = $psf_no9 + 1;
    }
    else if($category=='Lack of follow-through')
    {
        $psf10 = $psf10 + $temp_ans;
        $psf_no10 = $psf_no10 + 1;
    }
    else if($category=='Self-confidence')
    {
        $psf11 = $psf11 + $temp_ans;
        $psf_no11 = $psf_no11 + 1;
    }

}

$psf_score1 = ($psf1*100)/($psf_no1*5);
$psf_score2 = ($psf2*100)/($psf_no2*5);
$psf_score3 = ($psf3*100)/($psf_no3*5);
$psf_score4 = ($psf4*100)/($psf_no4*5);
$psf_score5 = ($psf5*100)/($psf_no5*5);
$psf_score6 = ($psf6*100)/($psf_no6*5);
$psf_score7 = ($psf7*100)/($psf_no7*5);
$psf_score8 = ($psf8*100)/($psf_no8*5);
$psf_score9 = ($psf9*100)/($psf_no9*5);
$psf_score10 = ($psf10*100)/($psf_no10*5);
$psf_score11 = ($psf11*100)/($psf_no11*5);
//end of father scoring

//Added by Sudhir on 26-Oct-2021
//Define array of scores
$arr_psf_scr = array($psf_score1,$psf_score2,$psf_score3,
                 $psf_score4,$psf_score5,$psf_score6,
                 $psf_score7,$psf_score8,$psf_score9,
                 $psf_score10,$psf_score11
                 );
                 

echo "Parenting Style Score - Father :<pre>";
print_r($arr_psf_scr);


$chartX=30;
$chartY=158;
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
$barWidth=7;
//chart data
$data=Array(
    'A'=>['color'=>[24,203,238],'value'=>$psm_score5],
    'B'=>['color'=>[172,13,26],'value'=>$psf_score5],
    'C'=>['color'=>[247,253,4],'value'=>$psm_score6],
    'D'=>['color'=>[77,106,12],'value'=>$psf_score6],
    'E'=>['color'=>[255,82,0],'value'=>$psm_score7],
    'F'=>['color'=>[16,53,46],'value'=>$psf_score7],
    'G'=>['color'=>[219,244,169],'value'=>$psm_score8],
    'H'=>['color'=>[104,38,102],'value'=>$psf_score8]
);
$data2=Array(
'A-Mother[Democratic participation]'=>['color'=>[24,203,238],'value'=>$psm_score5],
'B-Father[Democratic participation]'=>['color'=>[172,13,26],'value'=>$psf_score5],
'C-Mother[Good natured/ easy going]'=>['color'=>[247,253,4],'value'=>$psm_score6],
'D-Father[Good natured/ easy going]'=>['color'=>[77,106,12],'value'=>$psf_score6],
'E-Mother[Reasoning/ Induction]'=>['color'=>[255,82,0],'value'=>$psm_score7],
'F-Father[Reasoning/ Induction]'=>['color'=>[16,53,46],'value'=>$psf_score7],
'G-Mother[Warmth & Involvement]'=>['color'=>[219,244,169],'value'=>$psm_score8],
'H-Father[Warmth & Involvement]'=>['color'=>[104,38,102],'value'=>$psf_score8]
);

//data max
$dataMax=0;
foreach($data as $item)
{
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
for($i=0;$i<=$dataMax; $i+=$dataStep)
{
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
         $yAxisPos-1.25,
         $chartBoxX,
         $yAxisPos-1.25     
          );  
    }
    else if($i==20)
    {
     $pdf->Line(
         $chartBoxX+85,
         $yAxisPos-1.25,
         $chartBoxX,
         $yAxisPos-1.25     
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
foreach($data as $itemName=>$item)
{
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
    $pdf->Cell(100,10,"Parenting style (Authoritative dimensions)",0,0,'C');

    //legend properties
    $legendX=132;
    $legendY=160;

    //draw th legend
    $pdf->SetFont('Arial','',6.5);

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

//end second graph

//start third graph

$chartX=30;
$chartY=224;
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
$barWidth=7;
//chart data
$data=Array(
    'A'=>['color'=>[24,203,238],'value'=>$psm_score1],
    'B'=>['color'=>[172,13,26],'value'=>$psf_score1],
    'C'=>['color'=>[247,253,4],'value'=>$psm_score2],
    'D'=>['color'=>[77,106,12],'value'=>$psf_score2],
    'E'=>['color'=>[255,82,0],'value'=>$psm_score3],
    'F'=>['color'=>[16,53,46],'value'=>$psf_score3],
    'G'=>['color'=>[219,244,169],'value'=>$psm_score4],
    'H'=>['color'=>[104,38,102],'value'=>$psf_score4]
);
$data2=Array(
'A-Mother[Corporal punishment]'=>['color'=>[24,203,238],'value'=>$psm_score1],
'B-Father[Corporal punishment]'=>['color'=>[172,13,26],'value'=>$psf_score1],
'C-Mother[Directiveness]'=>['color'=>[247,253,4],'value'=>$psm_score2],
'D-Father[Directiveness]'=>['color'=>[77,106,12],'value'=>$psf_score2],
'E-Mother[Nonreasoning, punitive strategies]'=>['color'=>[255,82,0],'value'=>$psm_score3],
'F-Father[Nonreasoning, punitive strategies]'=>['color'=>[16,53,46],'value'=>$psf_score3],
'G-Mother[Verbal hostility]'=>['color'=>[219,244,169],'value'=>$psm_score4],
'H-Father[Verbal hostility]'=>['color'=>[104,38,102],'value'=>$psf_score4]
);
//data max
$dataMax=0;
foreach($data as $item)
{
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
for($i=0;$i<=$dataMax; $i+=$dataStep)
{
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
         $yAxisPos-1.25,
         $chartBoxX,
         $yAxisPos-1.25     
          );  
    }
    else if($i==20)
    {
     $pdf->Line(
         $chartBoxX+85,
         $yAxisPos-1.25,
         $chartBoxX,
         $yAxisPos-1.25     
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
foreach($data as $itemName=>$item)
{
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
$pdf->Cell(100,10,"Parenting style (Authoritarian dimensions)",0,0,'C');

//legend properties

$legendX=133;
$legendY=227;

//draw th legend
$pdf->SetFont('Arial','',6.5);

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

//end third graph


$tpl = $pdf->importPage(6);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);

//start fourth graph

$chartX=30;
$chartY=42;
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
$barWidth=7;
//chart data
$data=Array(
        'A'=>['color'=>[24,203,238],'value'=>$psm_score9],
        'B'=>['color'=>[172,13,26],'value'=>$psf_score9],
        'C'=>['color'=>[247,253,4],'value'=>$psm_score10],
        'D'=>['color'=>[77,106,12],'value'=>$psf_score10],
        'E'=>['color'=>[255,82,0],'value'=>$psm_score11],
        'F'=>['color'=>[16,53,46],'value'=>$psf_score11]     
);
$data2=Array(
    'A-Mother[Ignoring mis-behaviour]'=>['color'=>[24,203,238],'value'=>$psm_score9],
    'B-Father[Ignoring mis-behaviour]'=>['color'=>[172,13,26],'value'=>$psf_score9],
    'C-Mother[Lack of follow-through]'=>['color'=>[247,253,4],'value'=>$psm_score10],
    'D-Father[Lack of follow-through]'=>['color'=>[77,106,12],'value'=>$psf_score10],
    'E-Mother[Self-confidence]'=>['color'=>[255,82,0],'value'=>$psm_score11],
    'F-Father[Self-confidence]'=>['color'=>[16,53,46],'value'=>$psf_score11]
);
//data max
$dataMax=0;
foreach($data as $item)
{
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
for($i=0;$i<=$dataMax; $i+=$dataStep)
{
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
         $yAxisPos-1.25,
         $chartBoxX,
         $yAxisPos-1.25     
          );  
    }
    else if($i==20)
    {
     $pdf->Line(
         $chartBoxX+85,
         $yAxisPos-1.25,
         $chartBoxX,
         $yAxisPos-1.25     
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
foreach($data as $itemName=>$item)
{
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
$pdf->Cell(100,10,"Parenting style (Permissive dimension)",0,0,'C');

//legend properties

$legendX=133;
$legendY=46;

//draw th legend
$pdf->SetFont('Arial','',6.5);

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




/*Added by Sudhir on 25-Oct-2021, New algorithm for PS inf

{
    $arr_sev_name = ['Immediate attention', 'Needs attention', 'Satisfactory','Strength'];
    $arr_sev_level = [3,2,1,0];
    $arr_sev_val = [50,65,85,100];
    $arr_sev_f = array();
    $arr_sev_m = array();
    $cat_cnt = count($arr_ps_cat);
    $sev_cnt = count($arr_sev_val);
    $arr_sev = array();
    
    echo "Count of Categories :".$cat_cnt."<br>";
    
    for ($i=0;$i<$cat_cnt;$i++)
    {
        echo "Father Loop :".$i."<br>";
        for($j=0;$j<$sev_cnt;$j++)
        {
            if($arr_psf_scr[$i]<=$arr_sev_val[$j])
            {
                array_push($arr_sev_f,$arr_sev_level[$j]);
                break;
            }
        }
    }
    
    echo "Parenting Style Severity Levels  - Father :<pre>";
    print_r($arr_sev_f);
    
    for ($i=0;$i<$cat_cnt;$i++)
    {
        for($j=0;$j<$sev_cnt;$j++)
        {
            if($arr_psm_scr[$i]<=$arr_sev_val[$j])
            {
                array_push($arr_sev_m,$arr_sev_level[$j]);
                break;
            }
        }
    }
    
    
    echo "Parenting Style Severity Levels  - Mother :<pre>";
    print_r($arr_sev_m);
    
    
    for ($i=0;$i<$cat_cnt;$i++)
    {
        array_push($arr_sev, max($arr_sev_m[$i], $arr_sev_f[$i]));
    }
    
    echo "Parenting Style Severity Levels  - Overall :<pre>";
    print_r($arr_sev);
    
    $X0 = 138;
    $Y0 = 122;
    $arr_Y = array($Y0, $Y0 + 18, $Y0 + 29, 
                   $Y0 + 40, $Y0+51, $Y0 + 62, 
                   $Y0 + 71.5, $Y0 + 80, $Y0 + 90,
                   $Y0 + 99, $Y0 + 106);
    
    for ($i=0;$i<$cat_cnt;$i++)
    {
        
        $pdf->SetFontSize('9');
        $pdf->SetXY($X0,$arr_Y[$i]);
        for($j=0;$j<$sev_cnt;$j++)
        {
            if($arr_sev[$i] == $arr_sev_level[$j])
            {
                
                $txt_inf = $arr_sev_name[$j];
                echo "Inference for ".$arr_ps_cat[$i]." :".$txt_inf."<br>";
                $pdf->Cell(20, 10,$txt_inf, 0, 0, 'L');
                break;
                
            }
        }
        
        
    }

}

End of Added by Sudhir on 25-oct-2021, New algorithm for PS inf*/




/* Commented by Sudhir on 25-oct-2021, old algorithm for ps inferences*/
{
    $pdf->SetFontSize('9');
    $pdf->SetXY(140,122);
    
    if($psf_score8<50 || $psm_score8<50)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,122);
        $pdf->Cell(20, 10,'Immediate attention', 0, 0, 'L');
    }
    else if($psf_score8>=50 && $psf_score8<65 || $psm_score8>=50 && $psm_score8<65)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,122);
        $pdf->Cell(20, 10,'Needs attention', 0, 0, 'L');
    }
    
    
    if($psf_score6<50 || $psm_score6<50)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,140);
        $pdf->Cell(20, 10,'Immediate attention', 0, 0, 'L');
    }
    else if($psf_score6>=50 && $psf_score6<65 || $psm_score6>=50 && $psm_score6<65)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,140);
        $pdf->Cell(20, 10,'Needs attention', 0, 0, 'L');
    }
    
    
    if($psf_score7<50 || $psm_score7<50)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,151);
        $pdf->Cell(20, 10,'Immediate attention', 0, 0, 'L');
    }
    else if($psf_score7>=50 && $psf_score7<65 || $psm_score7>=50 && $psm_score7<65)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,151);
        $pdf->Cell(20, 10,'Needs attention', 0, 0, 'L');
    }
    
    
    if($psf_score5<50 || $psm_score5<50)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,162);
        $pdf->Cell(20, 10,'Immediate attention', 0, 0, 'L');
    }
    else if($psf_score5>=50 && $psf_score5<65 || $psm_score5>=50 && $psm_score5<65)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,162);
        $pdf->Cell(20, 10,'Needs attention', 0, 0, 'L');
    }
    
    
    if($psf_score1<50 || $psm_score1<50)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,173);
        $pdf->Cell(20, 10,'Immediate attention', 0, 0, 'L');
    }
    else if($psf_score1>=50 && $psf_score1<65 || $psm_score1>=50 && $psm_score1<65)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,173);
        $pdf->Cell(20, 10,'Needs attention', 0, 0, 'L');
    }
    
    
    if($psf_score3<50 || $psm_score3<50)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,184);
        $pdf->Cell(20, 10,'Immediate attention', 0, 0, 'L');
    }
    else if($psf_score3>=50 && $psf_score3<65 || $psm_score3>=50 && $psm_score3<65)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,184);
        $pdf->Cell(20, 10,'Needs attention', 0, 0, 'L');
    }
    
    
    if($psf_score4<50 || $psm_score4<50)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,193.5);
        $pdf->Cell(20, 10,'Immediate attention', 0, 0, 'L');
    }
    else if($psf_score4>=50 && $psf_score4<65 || $psm_score4>=50 && $psm_score4<65)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,193.5);
        $pdf->Cell(20, 10,'Needs attention', 0, 0, 'L');
    }
    
    
    if($psf_score2<50 || $psm_score2<50)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,202);
        $pdf->Cell(20, 10,'Immediate attention', 0, 0, 'L');
    }
    else if($psf_score2>=50 && $psf_score2<65 || $psm_score2>=50 && $psm_score2<65)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,202);
        $pdf->Cell(20, 10,'Needs attention', 0, 0, 'L');
    }
    
    
    if($psf_score11<50 || $psm_score11<50)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,212);
        $pdf->Cell(20, 10,'Immediate attention', 0, 0, 'L');
    }
    else if($psf_score11>=50 && $psf_score11<65 || $psm_score11>=50 && $psm_score11<65)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,212);
        $pdf->Cell(20, 10,'Needs attention', 0, 0, 'L');
    }
    
    
    if($psf_score9<50 || $psm_score9<50)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,221);
        $pdf->Cell(20, 10,'Immediate attention', 0, 0, 'L');
    }
    else if($psf_score9>=50 && $psf_score9<65 || $psm_score9>=50 && $psm_score9<65)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,221);
        $pdf->Cell(20, 10,'Needs attention', 0, 0, 'L');
    }
    
    
    if($psf_score10<50 || $psm_score10<50)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,228);
        $pdf->Cell(20, 10,'Immediate attention', 0, 0, 'L');
    }
    else if($psf_score10>=50 && $psf_score10<65 || $psm_score10>=50 && $psm_score10<65)
    {
        $pdf->SetFontSize('10');
        $pdf->SetXY(140,228);
        $pdf->Cell(20, 10,'Needs attention', 0, 0, 'L');
    }
}


/** End of comment by Sudhir on 25-oct-21, old algorithm for ps inf **/


$tpl = $pdf->importPage(7);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);

$data = array();
$item = array();
if($r1==1)
{
    $sql14 = "select * from sdq_detail where category='Emotional'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $nature = $row14['nature'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];

        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part4' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($nature=='F')
        {
            if($ans==1)
            {
                $temp_ans = 0;
                $d_ans = 'Not true';
            }
            else if($ans==2)
            {
                $temp_ans = 1;
                $d_ans = 'Somewhat true';
            }
            else
            {
                $temp_ans = 2; 
                $d_ans = 'Certainly true';  
            }
        }
        else
        {
            if($ans==1)
            {
                $temp_ans = 2;
                $d_ans = 'Not true';
            }
            else if($ans==2)
            {
                $temp_ans = 1;
                $d_ans = 'Somewhat true';
            }
            else
            {
                $temp_ans = 0;   
                $d_ans = 'Certainly true';
            }
        }
        
        $sql16 = "select * from sdq_huh where category='Emotional' and qno='$qno' and uhl='$ans'";
        $res16 = mysqli_query($con,$sql16);
        $num_count = mysqli_num_rows($res16);
        $row16 = mysqli_fetch_array($res16);
        if($num_count>0)
        {
            array_push($data,array($row16['item'],$d_ans,$poi,$suggestion));
        }
    }   
}

if($r2==1)
{
    $sql14 = "select * from sdq_detail where category='Conduct'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $nature = $row14['nature'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];

        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part4' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($nature=='F')
        {
            if($ans==1)
            {
                $temp_ans = 0;
                $d_ans = 'Not true';
            }
            else if($ans==2)
            {
                $temp_ans = 1;
                $d_ans = 'Somewhat true';
            }
            else
            {
                $temp_ans = 2; 
                $d_ans = 'Certainly true';  
            }
        }
        else
        {
            if($ans==1)
            {
                $temp_ans = 2;
                $d_ans = 'Not true';
            }
            else if($ans==2)
            {
                $temp_ans = 1;
                $d_ans = 'Somewhat true';
            }
            else
            {
                $temp_ans = 0;   
                $d_ans = 'Certainly true';
            }
        }
        
        $sql16 = "select * from sdq_huh where category='Conduct' and qno='$qno' and uhl='$ans'";
        $res16 = mysqli_query($con,$sql16);
        $num_count = mysqli_num_rows($res16);
        $row16 = mysqli_fetch_array($res16);
        if($num_count>0)
        {
            array_push($data,array($row16['item'],$d_ans,$poi,$suggestion));
        }
    }   
}

if($r3==1)
{
    $sql14 = "select * from sdq_detail where category='Activity'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $nature = $row14['nature'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];

        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part4' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($nature=='F')
        {
            if($ans==1)
            {
                $temp_ans = 0;
                $d_ans = 'Not true';
            }
            else if($ans==2)
            {
                $temp_ans = 1;
                $d_ans = 'Somewhat true';
            }
            else
            {
                $temp_ans = 2; 
                $d_ans = 'Certainly true';  
            }
        }
        else
        {
            if($ans==1)
            {
                $temp_ans = 2;
                $d_ans = 'Not true';
            }
            else if($ans==2)
            {
                $temp_ans = 1;
                $d_ans = 'Somewhat true';
            }
            else
            {
                $temp_ans = 0;   
                $d_ans = 'Certainly true';
            }
        }
        
        $sql16 = "select * from sdq_huh where category='Activity' and qno='$qno' and uhl='$ans'";
        $res16 = mysqli_query($con,$sql16);
        $num_count = mysqli_num_rows($res16);
        $row16 = mysqli_fetch_array($res16);
        if($num_count>0)
        {
            array_push($data,array($row16['item'],$d_ans,$poi,$suggestion));
        }
    }   
}

if($r4==1)
{
    // $data = array();
    // $item = array();
    $sql14 = "select * from sdq_detail where category='Peers'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $nature = $row14['nature'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];

        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part4' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($nature=='F')
        {
            if($ans==1)
            {
                $temp_ans = 0;
                $d_ans = 'Not true';
            }
            else if($ans==2)
            {
                $temp_ans = 1;
                $d_ans = 'Somewhat true';
            }
            else
            {
                $temp_ans = 2; 
                $d_ans = 'Certainly true';  
            }
        }
        else
        {
            if($ans==1)
            {
                $temp_ans = 2;
                $d_ans = 'Not true';
            }
            else if($ans==2)
            {
                $temp_ans = 1;
                $d_ans = 'Somewhat true';
            }
            else
            {
                $temp_ans = 0;   
                $d_ans = 'Certainly true';
            }
        }
        
        $sql16 = "select * from sdq_huh where category='Peers' and qno='$qno' and uhl='$temp_ans'";
        $res16 = mysqli_query($con,$sql16);
        $num_count = mysqli_num_rows($res16);
        $row16 = mysqli_fetch_array($res16);
        if($num_count>0)
        {
  
            // array_push($data,array($row16['item'],$d_ans,$poi,$suggestion));
            array_push($data,array($row16['item'],$d_ans,$poi,$suggestion));
             
        }    
            
    }   
    //checking length
         
    /// end of records ///

}

if($r5==1)
{
    $sql14 = "select * from sdq_detail where category='Prosocial behaviour'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $nature = $row14['nature'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];
        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part4' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($nature=='F')
        {
            if($ans==1)
            {
                $temp_ans = 0;
                $d_ans = 'Not true';
            }
            else if($ans==2)
            {
                $temp_ans = 1;
                $d_ans = 'Somewhat true';
            }
            else
            {
                $temp_ans = 2; 
                $d_ans = 'Certainly true';  
            }
        }
        else
        {
            if($ans==1)
            {
                $temp_ans = 2;
                $d_ans = 'Not true';
            }
            else if($ans==2)
            {
                $temp_ans = 1;
                $d_ans = 'Somewhat true';
            }
            else
            {
                $temp_ans = 0;   
                $d_ans = 'Certainly true';
            }
        }
        $sql16 = "select * from sdq_huh where category='Prosocial behaviour' and qno='$qno' and uhl='$temp_ans'";
        $res16 = mysqli_query($con,$sql16);
        $num_count = mysqli_num_rows($res16);
        $row16 = mysqli_fetch_array($res16);
        if($num_count>0)
        {
            array_push($data,array($row16['item'],$d_ans,$poi,$suggestion));
            //  First row of data is over 
        }
    }   
}

if($s1==1)
{
    $sql14 = "select * from sha_details where category='Test Anxiety Management'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];
        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part3' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($ans==1)
        {
            $temp_ans = 4;
            $d_ans = 'Strongly disagree';
        }
        else if($ans==2)
        {
            $temp_ans = 3;
            $d_ans = 'Somewhat disagree';
        }
        else if($ans==3)
        {
            $temp_ans = 2;
            $d_ans = 'Somewhat agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
        else if($ans==4)
        {
            $temp_ans = 1;
            $d_ans = 'Strongly agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
    
    }   
}

if($s2==1)
{
    $sql14 = "select * from sha_details where category='Time Management'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];
        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part3' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($ans==1)
        {
            $temp_ans = 4;
            $d_ans = 'Strongly disagree';
        }
        else if($ans==2)
        {
            $temp_ans = 3;
            $d_ans = 'Somewhat disagree';
        }
        else if($ans==3)
        {
            $temp_ans = 2;
            $d_ans = 'Somewhat agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
        else if($ans==4)
        {
            $temp_ans = 1;
            $d_ans = 'Strongly agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
    
    }   
}

if($s3==1)
{
    $sql14 = "select * from sha_details where category='Writing Skills'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];
        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part3' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($ans==1)
        {
            $temp_ans = 4;
            $d_ans = 'Strongly disagree';
        }
        else if($ans==2)
        {
            $temp_ans = 3;
            $d_ans = 'Somewhat disagree';
        }
        else if($ans==3)
        {
            $temp_ans = 2;
            $d_ans = 'Somewhat agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
        else if($ans==4)
        {
            $temp_ans = 1;
            $d_ans = 'Strongly agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
    
    }   
}

if($s4==1)
{
    $sql14 = "select * from sha_details where category='Reading Speed'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];
        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part3' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($ans==1)
        {
            $temp_ans = 4;
            $d_ans = 'Strongly disagree';
        }
        else if($ans==2)
        {
            $temp_ans = 3;
            $d_ans = 'Somewhat disagree';
        }
        else if($ans==3)
        {
            $temp_ans = 2;
            $d_ans = 'Somewhat agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
        else if($ans==4)
        {
            $temp_ans = 1;
            $d_ans = 'Strongly agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
    
    }   
}

if($s5==1)
{
    $sql14 = "select * from sha_details where category='Concentration'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];
        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part3' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($ans==1)
        {
            $temp_ans = 4;
            $d_ans = 'Strongly disagree';
        }
        else if($ans==2)
        {
            $temp_ans = 3;
            $d_ans = 'Somewhat disagree';
        }
        else if($ans==3)
        {
            $temp_ans = 2;
            $d_ans = 'Somewhat agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
        else if($ans==4)
        {
            $temp_ans = 1;
            $d_ans = 'Strongly agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
    
    }   
}

if($s6==1)
{
    $sql14 = "select * from sha_details where category='Reading Comprehension'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];
        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part3' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($ans==1)
        {
            $temp_ans = 4;
            $d_ans = 'Strongly disagree';
        }
        else if($ans==2)
        {
            $temp_ans = 3;
            $d_ans = 'Somewhat disagree';
        }
        else if($ans==3)
        {
            $temp_ans = 2;
            $d_ans = 'Somewhat agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
        else if($ans==4)
        {
            $temp_ans = 1;
            $d_ans = 'Strongly agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
    
    }   
}

if($s7==1)
{
    $sql14 = "select * from sha_details where category='Note Taking'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];
        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part3' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($ans==1)
        {
            $temp_ans = 4;
            $d_ans = 'Strongly disagree';
        }
        else if($ans==2)
        {
            $temp_ans = 3;
            $d_ans = 'Somewhat disagree';
        }
        else if($ans==3)
        {
            $temp_ans = 2;
            $d_ans = 'Somewhat agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
        else if($ans==4)
        {
            $temp_ans = 1;
            $d_ans = 'Strongly agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
    
    }   
}
if($s8==1)
{
    $sql14 = "select * from sha_details where category='Test Preparation And Test Taking'";
    $res14 = mysqli_query($con,$sql14);
    while($row14=mysqli_fetch_array($res14)) 
    {
        $qno = $row14['qno'];
        $poi = $row14['poi'];
        $suggestion = $row14['suggestion'];
        $sql15 = "select * from ppe_part1_test_details where code='$code' and solution='ppe_part3' and qno='$qno'";
        $res15 = mysqli_query($con,$sql15);
        $row15 = mysqli_fetch_array($res15);
        $ans = $row15['ans'];
        if($ans==1)
        {
            $temp_ans = 4;
            $d_ans = 'Strongly disagree';
        }
        else if($ans==2)
        {
            $temp_ans = 3;
            $d_ans = 'Somewhat disagree';
        }
        else if($ans==3)
        {
            $temp_ans = 2;
            $d_ans = 'Somewhat agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
        else if($ans==4)
        {
            $temp_ans = 1;
            $d_ans = 'Strongly agree';
            array_push($data,array($row14['item'],$d_ans,$poi,$suggestion));
        }
    
    }   
}

// $pdf2 = new FPDF();

// $pdf2->$pdf;
$pdf->SetFont('Arial','B',12);
$pdf->SetXY(12.5,38);
$width_cell=array(38,30,48,70);
$pdf->SetTextColor(118,146,60);
$pdf->SetFillColor(219,233,201); // Background color of header 
// Header starts /// 
$pdf->cell(184,8,'CONCERNS, INFLUENCERS, SUGGESTIONS',1,1,'L',true);
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(12.5,46);
$pdf->SetTextColor(118,146,60);
$pdf->SetFillColor(219,233,201);
$pdf->Cell(30,6,'Key Concern',1,0,'L',true); // First header column 
$pdf->Cell(25,6,'Response',1,0,'L',true); // Second header column
$pdf->Cell(65,6,'Possible other influencers',1,0,'L',true); // Third header column 
$pdf->Cell(64,6,'Suggestions',1,1,'L',true); // Fourth header column
//// header is over ///////

$pdf->SetFont('Arial','B',7);
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
$cells = 4;

//$width_cell=array(38,30,48,70);

$page_height = 320; // mm 
$pdf->SetAutoPageBreak(false);
foreach($data as $item)
{
    
        $x = $x;
        $y = $y+$maxheight;
        $height_of_cell=$y-$yn;
        $space_left=$page_height-($y); // space left on page
        if ($height_of_cell > $space_left) 
        {
            // $pdf->Write($y+$yn,'Next');
            $tpl = $pdf->importPage(7);
            $pdf->AddPage();
            $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
            checking_size($logo,$pdf);
            $pdf->SetFont('Arial','B',12);
            $pdf->SetXY(12.5,38);
            $width_cell=array(38,30,48,70);
            $pdf->SetTextColor(118,146,60);
            $pdf->SetFillColor(219,233,201); // Background color of header 
            // Header starts /// 
            $pdf->cell(184,8,'CONCERNS, INFLUENCERS, SUGGESTIONS',1,1,'L',true);
            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(12.5,46);
            $pdf->SetTextColor(118,146,60);
            $pdf->SetFillColor(219,233,201);
            $pdf->Cell(30,6,'Key Concern',1,0,'L',true); // First header column 
            $pdf->Cell(25,6,'Response',1,0,'L',true); // Second header column
            $pdf->Cell(65,6,'Possible other influencers',1,0,'L',true); // Third header column 
            $pdf->Cell(64,6,'Suggestions',1,1,'L',true); // Fourth header column
            //   $pdf->SetXY(12, 30);// page break
            $pdf->SetFont('Arial','B',7);
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
                $pdf->MultiCell(25, $height, $item[$i]); 
            }
            else if($i==2)
            {
                $pdf->SetXY($x + (57) , $y);
                $pdf->MultiCell(60, $height, $item[$i]); 
            }
            else
            {
                $pdf->SetXY($x + (60*2) , $y);
                $pdf->MultiCell(60, $height, $item[$i]);  
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
                $pdf->Line($x + 30 + 25, $y, $x + 30 + 25, $y + $maxheight); 
            }
            else if($i==3)
            {
                $pdf->Line($x + 60*2, $y, $x + 60*2, $y + $maxheight); 
            }
            else
            {
                $pdf->Line($x + 60*2 +60+4, $y, $x + 60*2 +60+4, $y + $maxheight);
            }
            

        }
        $pdf->Line($x, $y, $x + $width * $cells, $y);// top line
        $pdf->Line($x, $y + $maxheight, $x + $width * $cells, $y + $maxheight);//bottom
                     
} 
    
include('Remark.php');


ob_end_clean();
$pdf->AliasNbPages();

$pdf->Output();
// $pdf2->Output();

ob_end_flush();

?>