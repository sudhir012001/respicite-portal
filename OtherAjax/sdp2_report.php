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

//$pagecount = $pdf->setSourceFile('report_template/SDP2_2Page.pdf');





// Import the first page from the PDF and add to dynamic PDF
$pagecount = $pdf->setSourceFile('report_template/SDP2_2Page.pdf');
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);


// Add the client details page
include('Second_Detail_Page.php');

// Import the 2nd page from the PDF and add to dynamic PDF
$pagecount = $pdf->setSourceFile('report_template/SDP2_2Page.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);




// Reference the PDF you want to use (use relative path)
$pagecount = $pdf->setSourceFile('report_template/SDP1.pdf');

// Import the first page from the PDF and add to dynamic PDF






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
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='sdp2_part1' and qno='$qno'";
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

checking_size($logo,$pdf);

$pdf->SetFontSize('9.5');
$pdf->SetXY(148,103);
$pdf->Cell(20, 10,$pp4_status, 0, 0, 'L');

$pdf->SetXY(148,117);
$pdf->Cell(20, 10,$pp2_status, 0, 0, 'L');

$pdf->SetXY(148,131);
$pdf->Cell(20, 10,$pp3_status, 0, 0, 'L');

$pdf->SetXY(148,146);
$pdf->Cell(20, 10,$pp1_status, 0, 0, 'L');

$pdf->SetXY(148,159);
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
        'A'=>['color'=>[0,255,0],'value'=>$pp1_score],
        'B'=>['color'=>[0,25,10],'value'=>$pp2_score],
        'C'=>['color'=>[172,13,26],'value'=>$pp3_score],
        'D'=>['color'=>[247,253,4],'value'=>$pp4_score],
        'E'=>['color'=>[10,25,100],'value'=>$pp5_score]
);
$data2=Array(
    'A-Empathy'=>['color'=>[0,255,0],'value'=>$pp1_score],
    'B-Manage emotions'=>['color'=>[0,25,10],'value'=>$pp2_score],
    'C-Motivating oneself'=>['color'=>[172,13,26],'value'=>$pp3_score],
    'D-Self-awareness'=>['color'=>[247,253,4],'value'=>$pp4_score],
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
$legendY=197;

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
$pdf->SetXY(33,265);
$pdf->Cell(20, 10,$fa, 0, 0, 'L');
$pdf->SetFontSize('16','B');
$pdf->SetXY(50,180);
$pdf->Cell(100,10,"Emotional Intelligence",0,0,'C');
$pagecount = $pdf->setSourceFile('report_template/emotional.pdf');
if($pp1_score<=65)
{
    $tpl = $pdf->importPage(5);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    checking_size($logo,$pdf);
}
if($pp2_score<=65)
{
    $tpl = $pdf->importPage(3);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    checking_size($logo,$pdf);
}
if($pp3_score<=65)
{
    $tpl = $pdf->importPage(4);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    checking_size($logo,$pdf);
}
if($pp4_score<=65)
{
    $tpl = $pdf->importPage(2);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    checking_size($logo,$pdf);
}
if($pp5_score<=65)
{
    $tpl = $pdf->importPage(6);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    checking_size($logo,$pdf);
}
    // add signature
$pagecount = $pdf->setSourceFile('report_template/SDP2.pdf');

// Import the first page from the PDF and add to dynamic PDF
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

$para = [
    'Problem solving',
    'Knowledge Management',
    'Communication (Advanced)',
    'Integrity'
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
            $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='sdp2_part2' and qno='$qno'";
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
    $pdf->SetXY(145,63);
    $pdf->MultiCell(40, 6,$p_status[0]);
    
    $pdf->SetXY(145,77);
    $pdf->MultiCell(40, 6,$p_status[1]);
    
    $pdf->SetXY(145,92);
    $pdf->MultiCell(40, 6,$p_status[2]);
    
    $pdf->SetXY(145,107);
    $pdf->MultiCell(40, 6,$p_status[3]);
    
    
    
    
    
    //graph
    $chartX=35;
    $chartY=150;
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
        'D'=>['color'=>[77,106,12],'value'=>$p_per[3]]
        
        
    );
    $data2=Array(
        'A - '.$para[0]=>['color'=>[24,203,238],'value'=>$p_per[0]],
        'B - '.$para[1]=>['color'=>[172,13,26],'value'=>$p_per[1]],
        'C - '.$para[2]=>['color'=>[247,253,4],'value'=>$p_per[2]],
        'D - '.$para[3]=>['color'=>[77,106,12],'value'=>$p_per[3]]
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
    // $pdf->Cell(100,10,"STUDY HABITS STRENGTH",0,0,'C');
    
    //legend properties
    $legendX=138;
    $legendY=160;
    
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
    $pdf->SetXY(30,230);
    $pdf->MultiCell(150,6,$fa);
    $pdf->SetFontSize('16','B');
    $pdf->SetXY(55,140);
    $pdf->MultiCell(150,6,'Work Skills');
    $pdf->AddPage();
    
    checking_size($logo,$pdf);
    $ref=array();
    //checking need attension question
    foreach($need_s as $need_s)
    {
       
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