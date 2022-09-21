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
$pagecount = $pdf->setSourceFile('report_template/Career Mentor.pdf');

// Import the first page from the PDF and add to dynamic PDF
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

/*
$pdf->SetFont('arial','B');
$pdf->SetFontSize('40');
$pdf->SetXY(70,105);
$pdf->Cell(20, 10,'Career Mentoring', 0, 0, 'L');
*/

//Add client details
include('Second_Detail_Page.php');



$pagecount = $pdf->setSourceFile('report_template/Career Mentor.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

$pagecount = $pdf->setSourceFile('report_template/WPA.pdf');
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
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='ps_part1' and qno='$qno'";
    $res2 = mysqli_query($con,$sql2);
    $row2 = mysqli_fetch_array($res2);
    $ans = $row2['ans'];
    if($ans == $r_a)
    {
        $score = $score + 1;
    }
    
}

$pdf->SetFontSize('14');
$pdf->SetXY(35,229);
$pdf->Cell(20, 10,'Score:- '.$score, 0, 0, 'L');


$pdf->SetFontSize('11');



if($score > 27)

{
$tpl = $pdf->importPage(4);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);
}

else
if($score >=22 && $score <=27)
{
$tpl = $pdf->importPage(5);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);

$tpl = $pdf->importPage(6);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);
}

else
if($score <22)

{
$tpl = $pdf->importPage(7);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);
}



$tpl = $pdf->importPage(8);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);

$grp = array();
$g_grp = "SELECT DISTINCT grp FROM wpa_part2_grp_details";
$g_res = mysqli_query($con,$g_grp);
while($r = mysqli_fetch_array($g_res))
{   
    
   array_push($grp,$r['grp']);
} 

$p1 = array();
$p2 = array();
$p3 = array();
$para = array();
$s_category = "SELECT * FROM wpa_part2_grp_details";
$s_res = mysqli_query($con,$s_category);
while($r = mysqli_fetch_array($s_res))
{   
   array_push($para,$r['category']);
   
    if($r['grp']==$grp[0])
    {
        array_push($p1,$r['category']);
    }
    else if($r['grp']==$grp[1])
    {
        array_push($p2,$r['category']);
    }
    else
    {
        array_push($p3,$r['category']);
    }
  
}
$pmt1 = $grp[0];
$pmt2 = $grp[1];
$pmt3 = $grp[2];
// $para = ['Communication (Foundation)',
// 'Creativity & Innovation',
// 'Knowledge Management',
// 'Problem Solving',
// 'Quality management',
// 'Systems thinking',
// 'Task closure',
// 'Working with Information',
// 'Judgment & decision Making',
// 'Emotional Intelligence',
// 'Initiative & Effort',
// 'Integrity',
// 'Communication (Advanced)',
// 'Cooperation',
// 'Coordination',
// 'Customer Management',
// 'Influencing',
// 'Interacting with People',
// 'Leadership',
// 'Resource Management',
// 'Social skills'
// ];
$count = count($p1);

for($j=0;$j<$count;$j++)
{
    $p_score[$j] = 0;
    $q_n[$j] = 0; 
    $p_per[$j] = 0; 
    
}
//calculation
$sql3 = "select * from wpa_part2 where grp='$pmt1'";
$res3 = mysqli_query($con,$sql3);
while($row3 = mysqli_fetch_array($res3))
{
    $qno = $row3['qno'];
    $category = $row3['category'];
    $sub_category = $row3['sub_category'];
    $grp = $row3['grp'];
    $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='ps_part2' and qno='$qno'";
    $res4 = mysqli_query($con,$sql4);
    $row4 = mysqli_fetch_array($res4);
    $ans = $row4['first_ans'];
    if($ans == 'yes')
    {
        $sec_ans = $row4['sec_ans'];
        for($i=0;$i<count($p1);$i++)
        {
            if($p1[$i]==$category)
            {
                $p_score[$i] = $p_score[$i] + $sec_ans;
                $q_n[$i] = $q_n[$i] + 1; 
            } 
        }
    }
    
}


$coma_count = 0;
$fa = '';
$need_s = array();
for($j=0;$j<$count;$j++)
{
    
    if($q_n[$j]==0)
    {
       $p_status[$j] ='Not relevant in current role'; 
       $p_per[$j] =0;
    }
    
    else
    
    {
    $p_per[$j] = ($p_score[$j]*100)/($q_n[$j]*5); 
    if($p_per[$j]>0 && $p_per[$j]<=50)
    {
        $p_status[$j] = 'Needs immediate Attention';
        if($coma_count==0)
        {
            $fa = $fa.$p1[$j];
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', '.$p1[$j];
            $coma_count = 1;
        }
        array_push($need_s,$p1[$j]);   
    }
    else if($p_per[$j]>50 && $p_per[$j]<=65)
    {
        $p_status[$j] = 'Needs Attention';
        if($coma_count==0)
        {
            $fa = $fa.$p1[$j];
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', '.$p1[$j];
            $coma_count = 1;
        }
        array_push($need_s,$p1[$j]);
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

 
}


//status
$X0 = 141;
$Y0 = 65;
$set_Y = array($Y0, $Y0 + 15, $Y0 + 30, $Y0 + 45, $Y0+60, $Y0 + 70, $Y0 + 83, $Y0 + 98);
for ($i=0;$i<=7;$i++)
{
    $pdf->SetXY($X0,$set_Y[$i]);
    $pdf->Cell(20, 10,$p_status[$i], 0, 0, 'L');  
}

/*
$pdf->SetXY(143,65);
$pdf->Cell(20, 10,$p_status[0], 0, 0, 'L');

$pdf->SetXY(143,80);
$pdf->Cell(20, 10,$p_status[1], 0, 0, 'L');

$pdf->SetXY(143,95);
$pdf->Cell(20, 10,$p_status[2], 0, 0, 'L');

$pdf->SetXY(145,110);
$pdf->Cell(20, 10,$p_status[3], 0, 0, 'L');

$pdf->SetXY(145,125);
$pdf->Cell(20, 10,$p_status[4], 0, 0, 'L');

$pdf->SetXY(145,135);
$pdf->Cell(20, 10,$p_status[5], 0, 0, 'L');

$pdf->SetXY(145,148);
$pdf->Cell(20, 10,$p_status[6], 0, 0, 'L');

$pdf->SetXY(145,163);
$pdf->Cell(20, 10,$p_status[7], 0, 0, 'L');
*/

//graph
$chartX=32;
$chartY=182;
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
    'F'=>['color'=>[16,53,46],'value'=>$p_per[5]],
    'G'=>['color'=>[219,244,169],'value'=>$p_per[6]],
    'H'=>['color'=>[104,38,102],'value'=>$p_per[7]]
);
$data2=Array(
    'A - '.$p1[0]=>['color'=>[24,203,238],'value'=>$p_per[0]],
    'B - '.$p1[1]=>['color'=>[172,13,26],'value'=>$p_per[1]],
    'C - '.$p1[2]=>['color'=>[247,253,4],'value'=>$p_per[2]],
    'D - '.$p1[3]=>['color'=>[77,106,12],'value'=>$p_per[3]],
    'E - '.$p1[4]=>['color'=>[255,82,0],'value'=>$p_per[4]],
    'F - '.$p1[5]=>['color'=>[16,53,46],'value'=>$p_per[5]],
    'G - '.$p1[6]=>['color'=>[219,244,169],'value'=>$p_per[6]],
    'H - '.$p1[7]=>['color'=>[104,38,102],'value'=>$p_per[7]]
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
foreach($data as $itemName=>$item){
    //print the label
    echo "Item Name :".$itemName."<br>";
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
$legendX=135;
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
echo "Index :".$index."<br>";
$pdf->Cell(50,5,$index,0,0);
$currentLegendY+=5;
}

echo "Focus Area :".$fa."<br>";
$pdf->SetFontSize('11');
$pdf->SetXY(25,250);
if(strlen($fa)<50)
{
    $pdf->Cell(20, 10,$fa, 0, 0, 'L');
}
else
{
    $pdf->MultiCell(160, 8,$fa, 0,'L');
}




//page 8 end
//page 9 with graph
$tpl = $pdf->importPage(9);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

$count = count($p2);

for($j=0;$j<$count;$j++)
{
    $p2_score[$j] = 0;
    $q2_n[$j] = 0; 
    $p2_per[$j] = 0; 
    $p2_status[$j] =0;
}
//calculation
$sql3 = "select * from wpa_part2 where grp='$pmt2'";
$res3 = mysqli_query($con,$sql3);
while($row3 = mysqli_fetch_array($res3))
{
    $qno = $row3['qno'];
    $category = $row3['category'];
    $sub_category = $row3['sub_category'];
    $grp = $row3['grp'];
    $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='ps_part2' and qno='$qno'";
    $res4 = mysqli_query($con,$sql4);
    $row4 = mysqli_fetch_array($res4);
    $ans = $row4['first_ans'];
    if($ans == 'yes')
    {
        $sec_ans = $row4['sec_ans'];
        for($i=0;$i<count($p2);$i++)
        {
            if($p2[$i]==$category)
            {
                $p2_score[$i] = $p2_score[$i] + $sec_ans;
                $q2_n[$i] = $q2_n[$i] +1; 
            } 
        }
    }
    
}
$coma_count = 0;
$fa = '';

for($j=0;$j<$count;$j++)
{
    
    if($q2_n[$j]==0)
    {
        $p2_status[$j] = 'Not relevant in current role';
        $p2_per[$j] =0;
    }
    else
    {
        $p2_per[$j] = ($p2_score[$j]*100)/($q2_n[$j]*5); 
        if($p2_per[$j]>0 && $p2_per[$j]<=50)
        {
            $p2_status[$j] = 'Needs immediate Attention';
            if($coma_count==0)
            {
                $fa = $fa.$p2[$j];
                $coma_count = 1;
            }
            else
            {
                $fa = $fa.', '.$p2[$j];
                $coma_count = 1;
            }
            array_push($need_s,$p2[$j]);
                
        }
        else if($p2_per[$j]>50 && $p2_per[$j]<=65)
        {
            $p2_status[$j] = 'Needs Attention';
            if($coma_count==0)
            {
                $fa = $fa.$p2[$j];
                $coma_count = 1;
            }
            else
            {
                $fa = $fa.', '.$p2[$j];
                $coma_count = 1;
            }
            array_push($need_s,$p2[$j]);
        }
        else if($p2_per[$j]>65 && $p2_per[$j]<=85)
        {
            $p2_status[$j] = 'Satisfactory';
        }
        else if($p2_per[$j]>85)
        {
            $p2_status[$j] = 'Strength';   
        }
        else
        {
            $p2_status[$j] = 'NA';  
        }
    }

 
}

//status
$pdf->SetXY(147,75);
$pdf->Cell(20, 10,$p2_status[0], 0, 0, 'L');

$pdf->SetXY(147,90);
$pdf->Cell(20, 10,$p2_status[1], 0, 0, 'L');

$pdf->SetXY(147,108);
$pdf->Cell(20, 10,$p2_status[2], 0, 0, 'L');

$pdf->SetXY(147,120);
$pdf->Cell(20, 10,$p2_status[3], 0, 0, 'L');

//graph
$chartX=32;
$chartY=153;
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
    'A'=>['color'=>[24,203,238],'value'=>$p2_per[0]],
    'B'=>['color'=>[172,13,26],'value'=>$p2_per[1]],
    'C'=>['color'=>[247,253,4],'value'=>$p2_per[2]],
    'D'=>['color'=>[77,106,12],'value'=>$p2_per[3]],
    
);
$data2=Array(
    'A - '.$p2[0]=>['color'=>[24,203,238],'value'=>$p2_per[0]],
    'B - '.$p2[1]=>['color'=>[172,13,26],'value'=>$p2_per[1]],
    'C - '.$p2[2]=>['color'=>[247,253,4],'value'=>$p2_per[2]],
    'D - '.$p2[3]=>['color'=>[77,106,12],'value'=>$p2_per[3]],
    
);

echo "Graph Values for Personal Leadership <pre>";
print_r($data);
print_r($data2);


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
$legendX=135;
$legendY=165;

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
$pdf->SetXY(30,235);
if(strlen($fa)<50)
{
    $pdf->Cell(20, 10,$fa, 0, 0, 'L');
}
else
{
    $pdf->MultiCell(160, 8,$fa, 0,'L');
}
//$pdf->Cell(20, 10,$fa, 0, 0, 'L');


checking_size($logo,$pdf);

// page 9 end
// page 10 start 
$tpl = $pdf->importPage(10);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

checking_size($logo,$pdf);

$count = count($p3);

for($j=0;$j<$count;$j++)
{
    $p3_score[$j] = 0;
    $q3_n[$j] = 0; 
    $p3_per[$j] = 0; 
    
}
//calculation
$sql3 = "select * from wpa_part2 where grp='$pmt3'";
$res3 = mysqli_query($con,$sql3);
while($row3 = mysqli_fetch_array($res3))
{
    $qno = $row3['qno'];
    $category = $row3['category'];
    $sub_category = $row3['sub_category'];
    $grp = $row3['grp'];
    $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='ps_part2' and qno='$qno'";
    $res4 = mysqli_query($con,$sql4);
    $row4 = mysqli_fetch_array($res4);
    $ans = $row4['first_ans'];
    if($ans == 'yes')
    {
        $sec_ans = $row4['sec_ans'];
        for($i=0;$i<count($p3);$i++)
        {
            if($p3[$i]==$category)
            {
                $p3_score[$i] = $p3_score[$i] + $sec_ans;
                $q3_n[$i] = $q3_n[$i] +1; 
            } 
        }
    }
    
}
$coma_count = 0;
$fa = '';

for($j=0;$j<count($p3);$j++)
{
    if ($q3_n[$j]==0)
    {
        $p3_status[$j] = 'Not relevant in current role';
        $p3_per[$j] =0;
    }
    $p3_per[$j] = ($p3_score[$j]*100)/($q3_n[$j]*5); 
    {
        if($p3_per[$j]>0 && $p3_per[$j]<=50)
        {
            $p3_status[$j] = 'Needs immediate Attention';
            if($coma_count==0)
            {
                $fa = $fa.$p3[$j];
                $coma_count = 1;
            }
            else
            {
                $fa = $fa.', '.$p3[$j];
                $coma_count = 1;
            }
            array_push($need_s,$p3[$j]);
        }
        else if($p3_per[$j]>50 && $p3_per[$j]<=65)
        {
            $p3_status[$j] = 'Needs Attention';
            if($coma_count==0)
            {
                $fa = $fa.$p3[$j];
                $coma_count = 1;
            }
            else
            {
                $fa = $fa.', '.$p3[$j];
                $coma_count = 1;
            }
            array_push($need_s,$p3[$j]);
        }
        else if($p3_per[$j]>65 && $p3_per[$j]<=85)
        {
            $p3_status[$j] = 'Satisfactory';
        }
        else if($p3_per[$j]>85)
        {
            $p3_status[$j] = 'Strength';   
        }
        else
        {
            $p3_status[$j] = 'NA';  
        }
    }

 
}

//status
//status of People Leadership
 
$pdf->SetXY(147,68);
$pdf->Cell(20, 10,$p3_status[0], 0, 0, 'L');

$pdf->SetXY(147,81);
$pdf->Cell(20, 10,$p3_status[1], 0, 0, 'L');

$pdf->SetXY(147,96);
$pdf->Cell(20, 10,$p3_status[2], 0, 0, 'L');

$pdf->SetXY(147,109);
$pdf->Cell(20, 10,$p3_status[3], 0, 0, 'L');

$pdf->SetXY(147,120);
$pdf->Cell(20, 10,$p3_status[4], 0, 0, 'L');

$pdf->SetXY(147,137);
$pdf->Cell(20, 10,$p3_status[5], 0, 0, 'L');

$pdf->SetXY(147,149);
$pdf->Cell(20, 10,$p3_status[6], 0, 0, 'L');

$pdf->SetXY(147,163);
$pdf->Cell(20, 10,$p3_status[7], 0, 0, 'L');

$pdf->SetXY(147,173);
$pdf->Cell(20, 10,$p3_status[8], 0, 0, 'L');

//graph3
$chartX=32;
$chartY=195;
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

    'A '=>['color'=>[24,203,238],'value'=>$p3_per[0]],
    'B '=>['color'=>[172,13,26],'value'=>$p3_per[1]],
    'C '=>['color'=>[247,253,4],'value'=>$p3_per[2]],
    'D '=>['color'=>[77,106,12],'value'=>$p3_per[3]],
    'E '=>['color'=>[255,82,0],'value'=>$p3_per[4]],
    'F '=>['color'=>[16,53,46],'value'=>$p3_per[5]],
    'G '=>['color'=>[219,244,169],'value'=>$p3_per[6]],
    'H '=>['color'=>[219,244,169],'value'=>$p3_per[7]],
    'I '=>['color'=>[104,38,102],'value'=>$p3_per[8]]
    
);
$data2=Array(
    'A - '.$p3[0]=>['color'=>[24,203,238],'value'=>$p3_per[0]],
    'B - '.$p3[1]=>['color'=>[172,13,26],'value'=>$p3_per[1]],
    'C - '.$p3[2]=>['color'=>[247,253,4],'value'=>$p3_per[2]],
    'D - '.$p3[3]=>['color'=>[77,106,12],'value'=>$p3_per[3]],
    'E - '.$p3[4]=>['color'=>[255,82,0],'value'=>$p3_per[4]],
    'F - '.$p3[5]=>['color'=>[16,53,46],'value'=>$p3_per[5]],
    'G - '.$p3[6]=>['color'=>[219,244,169],'value'=>$p3_per[6]],
    'H - '.$p3[7]=>['color'=>[219,244,169],'value'=>$p3_per[7]],
    'I - '.$p3[8]=>['color'=>[104,38,102],'value'=>$p3_per[8]]
    
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
$legendX=135;
$legendY=195;

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
$pdf->SetXY(30,260);
if(strlen($fa)<50)
{
    $pdf->Cell(20, 10,$fa, 0, 0, 'L');
}
else
{
    $pdf->MultiCell(160, 8,$fa, 0,'L');
}
//$pdf->Cell(20, 10,$fa, 0, 0, 'L');

//inferences
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
// $pdf->SetTextColor(118,146,60);
$pdf->SetFillColor(219,233,201); // Background color of header 
// Header starts /// 
$pdf->cell(184,8,'Concerns, Suggestions',1,1,'L',true);
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(12.5,46);
// $pdf->SetTextColor(118,146,60);
$pdf->SetFillColor(219,233,201);
$pdf->Cell(30,6,'Dimension',1,0,'L',true); // First header column 
$pdf->Cell(55,6,'Description',1,0,'L',true); // Second header column

$pdf->Cell(99,6,'Status',1,1,'L',true); // Fourth header column

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(12.5,52);
$width_cell=array(38,30,48,70);
// $pdf->SetTextColor(118,146,60);
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
            // $pdf->SetTextColor(118,146,60);
            $pdf->SetFillColor(219,233,201); // Background color of header 
            // Header starts /// 
            $pdf->cell(184,8,'Concerns, Suggestions',1,1,'L',true);
            $pdf->SetFont('Arial','B',10);
            $pdf->SetXY(12.5,46);
            // $pdf->SetTextColor(118,146,60);
            $pdf->SetFillColor(219,233,201);
            $pdf->Cell(30,6,'Dimension',1,0,'L',true); // First header column 
            $pdf->Cell(55,6,'Description',1,0,'L',true); // Second header column

            $pdf->Cell(99,6,'Status',1,1,'L',true); // Fourth header column
        //   $pdf->SetXY(12, 30);// page break
            $pdf->SetFont('Arial','B',9);
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

//end wpa report
//start wla report

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
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='ps_part3' and qno='$qno'";
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

/* Commented by Sudhir on 22-Oct-2021
$user_id = $row_for_logo['user_id'];
$sql_m_f = "select * from user_details where email='$user_id'";
$res_m_f = mysqli_query($con,$sql_m_f);
$row_m_f = mysqli_fetch_array($res_m_f);
$gender = $row_m_f['gender'];
***********************************/

//Added by Sudhir on 22-Oct-2021
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

$p_score1 = ($p1*100)/($p_no1*4);
$p_score2 = ($p2*100)/($p_no2*4);
$p_score3 = ($p3*100)/($p_no3*4);
$p_score4 = ($p4*100)/($p_no4*4);
$p_score5 = ($p5*100)/($p_no4*4);
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

$pdf->SetFontSize('9.5');
$pdf->SetXY(148,98);
$pdf->Cell(20, 10,$p3_status, 0, 0, 'L');

$pdf->SetXY(148,115);
$pdf->Cell(20, 10,$p5_status, 0, 0, 'L');

$pdf->SetXY(148,132);
$pdf->Cell(20, 10,$p2_status, 0, 0, 'L');

$pdf->SetXY(148,148);
$pdf->Cell(20, 10,$p4_status, 0, 0, 'L');

$pdf->SetXY(148,167);
$pdf->Cell(20, 10,$p1_status, 0, 0, 'L');


//start graph
$chartX=37;
$chartY=200;
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
//$pdf->Cell(100,10,"GRAPH Namfffhhhfhhhhfhfhfhffhffsffffffffsfe",0,0,'C');


//legend properties
$legendX=139;
$legendY=200;

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
echo "Personality Focus Areas :".$fa."<br>";

//$pdf->Cell(20, 10,$fa, 0, 0, 'L');
if(strlen($fa)<50)
{
    $pdf->Cell(20, 10,$fa, 0, 0, 'L');
}
else
{
    $pdf->MultiCell(160, 8,$fa, 0,'L');
}

$pagecount = $pdf->setSourceFile('report_template/Personality.pdf');

echo "Neuroticism Status :".$p1_status."<br>";
echo "Extraversion Status :".$p2_status."<br>";
echo "Openness Status :".$p3_status."<br>";
echo "Agreeableness Status :".$p4_status."<br>";
echo "Conscientiousness Status :".$p5_status."<br>";

if($p1_status == 'Needs attention'|| $p1_status == 'Needs immediate attention')
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
if($p2_status == 'Needs attention'|| $p2_status == 'Needs immediate attention')
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
if($p3_status == 'Needs attention'|| $p3_status == 'Needs immediate attention')
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
if($p4_status == 'Needs attention'|| $p4_status == 'Needs immediate attention')
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
if($p5_status == 'Needs attention'|| $p5_status == 'Needs immediate attention')
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
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='ps_part4' and qno='$qno'";
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
    else
    {
        $pp5 = $pp5 + $temp_ans;
    }
}
$pp1_score = $pp1*100/50;
$pp2_score = $pp2*100/50;
$pp3_score = $pp3*100/50;
$pp4_score = $pp4*100/50;
$pp5_score = $pp5*100/50;

echo "Displaying EI Scores :<br>";
echo "Empathy  :".$pp1_score."<br>";
echo "Manage Emotions :".$pp2_score."<br>";
echo "Motivating Oneself :".$pp3_score."<br>";
echo "Self-awareness :".$pp4_score."<br>";
echo "Social Skills :".$pp5_score."<br>";

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
else
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
else
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
else
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
else
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
    $pp4_status = 'Needs attention';
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
else
{
    $pp5_status = 'Strength'; 
}

echo "Display EI Status <br>";
echo "Empathy :".$pp1_status."<br>";
echo "Manage Emotions :".$pp2_status."<br>";
echo "Motivating Self :".$pp3_status."<br>";
echo "Self-awareness :".$pp4_status."<br>";
echo "Social Skills :".$pp5_status."<br>";

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

$pdf->SetFontSize('9.5');
$pdf->SetXY(148,103);
$pdf->Cell(20, 10,$pp4_status, 0, 0, 'L');

$pdf->SetXY(148,116);
$pdf->Cell(20, 10,$pp2_status, 0, 0, 'L');

$pdf->SetXY(148,130);
$pdf->Cell(20, 10,$pp3_status, 0, 0, 'L');

$pdf->SetXY(148,145);
$pdf->Cell(20, 10,$pp1_status, 0, 0, 'L');

$pdf->SetXY(148,158);
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
        'C'=>['color'=>[0,105,100],'value'=>$pp3_score],
        'D'=>['color'=>[100,105,100],'value'=>$pp4_score],
        'E'=>['color'=>[10,25,100],'value'=>$pp5_score]
);
$data2=Array(
    'A-Empathy'=>['color'=>[0,255,0],'value'=>$pp1_score],
    'B-Manage emotions'=>['color'=>[0,25,10],'value'=>$pp2_score],
    'C-Motivating oneself'=>['color'=>[0,105,100],'value'=>$pp3_score],
    'D-Self-awareness'=>['color'=>[100,105,100],'value'=>$pp4_score],
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
//$pdf->Cell(100,10,"GRAPH Namfffhhhfhhhhfhfhfhffhffsffffffffsfe",0,0,'C');

//legend properties
$legendX=139;
$legendY=190;

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
$pdf->SetXY(33,270);
if(strlen($fa)<50)
{
    $pdf->Cell(20, 10,$fa, 0, 0, 'L');
}

else
{
  $pdf->MultiCell(160, 8,$fa, 0,'L');   
}


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

//wla close
//wls start

$p1 = 0;
$p2 = 0;
$p3 = 0;
$p4 = 0;
$p5 = 0;
$p6 = 0;
$p7 = 0;
$p_no1 = 0;
$p_no2 = 0;
$p_no3 = 0;
$p_no4 = 0;
$p_no5 = 0;
$p_no6 = 0;
$p_no7 = 0;
$sql = "select * from wls_part1_1";
$res = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($res))
{
    $qno = $row['qno'];
    $category = $row['sub_category'];
    $nature = $row['nature'];
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='ps_part5' and qno='$qno'";
    $res2 = mysqli_query($con,$sql2);
    $row2 = mysqli_fetch_array($res2);
    $ans = $row2['ans'];
    if($nature=='F')
    {
        if($ans==1)
        {
            $temp_ans = 3;
        }
        else if($ans==2)
        {
            $temp_ans = 2;
        }
        else if($ans==3)
        {
            $temp_ans = 1;
        }
        else
        {
            $temp_ans = 0;   
        }
    }
    else
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
        else
        {
            $temp_ans = 3;
        }
       
    }
    if($category=='Self-satisfaction')
    {
        $p1 = $p1 + $temp_ans;
        $p_no1 = $p_no1 +1;
    }
    else if($category=='Self-worth')
    {
        $p2 = $p2 + $temp_ans;
        $p_no2 = $p_no2 +1;
    }
    else if($category=='Self-appreciation')
    {
        $p3 = $p3 + $temp_ans;
        $p_no3 = $p_no3 +1;
    }
    
}
$sql = "select * from wls_part1_2";
$res = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($res))
{
    $qno = $row['qno'];
    $category = $row['sub_category'];
    $nature = $row['nature'];
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='ps_part5' and qno='$qno'";
    $res2 = mysqli_query($con,$sql2);
    $row2 = mysqli_fetch_array($res2);
    $ans = $row2['ans'];

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
            $temp_ans = 3;
        }
        else if($ans==4)
        {
            $temp_ans = 2;
        }
        else
        {
            $temp_ans = 1;   
        }
    }

 
    if($category=='Self-confidence')
    {
        $p4 = $p4 + $temp_ans;
        $p_no4 = $p_no4 +1;
    }
    else if($category=='Initiative')
    {
        $p5 = $p5 + $temp_ans;
        $p_no5 = $p_no5 +1;
    }
    else if($category=='Effort')
    {
        $p6 = $p6 + $temp_ans;
        $p_no6 = $p_no6 +1;
    }
    else if($category=='Persistence')
    {
        $p7 = $p7 + $temp_ans;
        $p_no7 = $p_no7 +1;
    }
    
}


//Self-concept scores

$p_score1 = ($p1*100)/($p_no1*3);
$p_score2 = ($p2*100)/($p_no2*3);
$p_score3 = ($p3*100)/($p_no3*3);
$p_score4 = ($p4*100)/($p_no4*5);
$p_score5 = ($p5*100)/($p_no5*5);
$p_score6 = ($p6*100)/($p_no6*5);
$p_score7 = ($p7*100)/($p_no7*5);




$coma_count = 0;
$fa = '';

    //checking Self-satisfaction
    if($p_score1>0 && $p_score1<=50)
    {
        $p1_status = 'Needs immediate attention';
        if($coma_count==0)
        {
            $fa = $fa.'Self-satisfaction';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Self-satisfaction';
            $coma_count = 1;
        }
    }
    else if($p_score1>50 && $p_score1<=65)
    {
        $p1_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Self-satisfaction';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Self-satisfaction';
            $coma_count = 1;
        }
    }
    else if($p_score1>65 && $p_score1<=85)
    {
        $p1_status = 'Satisfactory';
    }
    else
    {
        $p1_status = 'Strength';
    }
    
//checking Self-worth
    if($p_score2>0 && $p_score2<=50)
    {
        $p2_status = 'Needs immediate attention';
        if($coma_count==0)
        {
            $fa = $fa.'Self-worth';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Self-worth';
            $coma_count = 1;
        }
    }
    else if($p_score2>50 && $p_score2<=65)
    {
        $p2_status = 'Needs attention';
        if($coma_count==0)
        {
            $fa = $fa.'Self-worth';
            $coma_count = 1;
        }
        else
        {
            $fa = $fa.', Self-worth';
            $coma_count = 1;
        }
    }
    else if($p_score2>65 && $p_score2<=85)
    {
        $p2_status = 'Satisfactory';
    }
    else
    {
        $p2_status = 'Strength';
    }

//checking Self-appreciation
if($p_score3>0 && $p_score3<=50)
{
    $p3_status = 'Needs immediate attention';
    if($coma_count==0)
    {
        $fa = $fa.'Self-appreciation';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Self-appreciation';
        $coma_count = 1;
    }
}
else if($p_score3>50 && $p_score3<=65)
{
    $p3_status = 'Needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Self-appreciation';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Self-appreciation';
        $coma_count = 1;
    }
}
else if($p_score3>65 && $p_score3<=85)
{
    $p3_status = 'Satisfactory';
}
else
{
    $p3_status = 'Strength';
}

//checking Self-confidence
if($p_score4>0 && $p_score4<=50)
{
    $p4_status = 'Needs immediate attention';
    if($coma_count==0)
    {
        $fa = $fa.'Self-confidence';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Self-confidence';
        $coma_count = 1;
    }
}
else if($p_score4>50 && $p_score4<=65)
{
    $p4_status = 'Needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Self-confidence';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Self-confidence';
        $coma_count = 1;
    }
}
else if($p_score4>65 && $p_score4<=85)
{
    $p4_status = 'Satisfactory';
}
else
{
    $p4_status = 'Strength';
}

//checking Initiative
if($p_score5>0 && $p_score5<=50)
{
    $p5_status = 'Needs immediate attention';
    if($coma_count==0)
    {
        $fa = $fa.'Initiative';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Initiative';
        $coma_count = 1;
    }
}
else if($p_score5>50 && $p_score5<=65)
{
    $p5_status = 'Needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Initiative';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Initiative';
        $coma_count = 1;
    }
}
else if($p_score5>65 && $p_score5<=85)
{
    $p5_status = 'Satisfactory';
}
else
{
    $p5_status = 'Strength';
}

//checking Effort
if($p_score6>0 && $p_score6<=50)
{
    $p6_status = 'Needs immediate attention';
    if($coma_count==0)
    {
        $fa = $fa.'Effort';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Effort';
        $coma_count = 1;
    }
}
else if($p_score6>50 && $p_score6<=65)
{
    $p6_status = 'Needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Effort';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Effort';
        $coma_count = 1;
    }
}
else if($p_score6>65 && $p_score6<=85)
{
    $p6_status = 'Satisfactory';
}
else
{
    $p6_status = 'Strength';
}

//checking Persistence
if($p_score7<=50)
{
    $p7_status = 'Needs immediate attention';
    if($coma_count==0)
    {
        $fa = $fa.'Persistence';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Persistence';
        $coma_count = 1;
    }
}
else if($p_score7>50 && $p_score7<=65)
{
    $p7_status = 'Needs attention';
    if($coma_count==0)
    {
        $fa = $fa.'Persistence';
        $coma_count = 1;
    }
    else
    {
        $fa = $fa.', Persistence';
        $coma_count = 1;
    }
}
else if($p_score7>65 && $p_score7<=85)
{
    $p7_status = 'Satisfactory';
}
else
{
    $p7_status = 'Strength';
}


//Outputting Self-concept Status
// Reference the PDF you want to use (use relative path)
$pagecount = $pdf->setSourceFile('report_template/WLS.pdf');


checking_size($logo,$pdf);

    // add signature
    

//page 3 
$tpl = $pdf->importPage(3);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

checking_size($logo,$pdf);

$pdf->SetFontSize('11');
$pdf->SetXY(148,76);
$pdf->Cell(20, 10,$p1_status, 0, 0, 'L');

$pdf->SetXY(148,95);
$pdf->Cell(20, 10,$p2_status, 0, 0, 'L');

$pdf->SetXY(148,114);
$pdf->Cell(20, 10,$p3_status, 0, 0, 'L');

$pdf->SetXY(148,135);
$pdf->Cell(20, 10,$p4_status, 0, 0, 'L');

$pdf->SetXY(148,153);
$pdf->Cell(20, 10,$p5_status, 0, 0, 'L');

$pdf->SetXY(148,165);
$pdf->Cell(20, 10,$p6_status, 0, 0, 'L');

$pdf->SetXY(148,178);
$pdf->Cell(20, 10,$p7_status, 0, 0, 'L');


//start graph
$chartX=35;
$chartY=200;
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
$barWidth=8;
//chart data
$data=Array(
        'A'=>['color'=>[0,255,0],'value'=>$p_score1],
        'B'=>['color'=>[0,25,10],'value'=>$p_score2],
        'C'=>['color'=>[0,105,100],'value'=>$p_score3],
        'D'=>['color'=>[100,105,100],'value'=>$p_score4],
        'E'=>['color'=>[10,25,100],'value'=>$p_score5],
        'F'=>['color'=>[100,105,1],'value'=>$p_score6],
        'G'=>['color'=>[100,25,100],'value'=>$p_score7]
);
$data2=Array(
    'A-Self-satisfaction'=>['color'=>[0,255,0],'value'=>$p_score1],
    'B-Self-worth'=>['color'=>[0,25,10],'value'=>$p_score2],
    'C-Self-appreciation'=>['color'=>[0,105,100],'value'=>$p_score3],
    'D-Self-confidence'=>['color'=>[100,105,100],'value'=>$p_score4],
    'E-Initiative'=>['color'=>[10,25,100],'value'=>$p_score5],
    'F-Effort'=>['color'=>[100,105,1],'value'=>$p_score6],
    'G-Persistence'=>['color'=>[100,25,100],'value'=>$p_score7]
    
);


//Plotting Self-concept Chart
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
//$pdf->Cell(100,10,"GRAPH Namfffhhhfhhhhfhfhfhffhffsffffffffsfe",0,0,'C');


//legend properties
$legendX=139;
$legendY=200;

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

//Printing Self-concept inferences

$pdf->SetFontSize('11');
$pdf->SetXY(30,265);
$pdf->Cell(20, 10,$fa, 0, 0, 'L');
$p_score[1] = $p_score1;
$p_score[2] = $p_score2;
$p_score[3] = $p_score3;
$p_score[4] = $p_score4;
$p_score[5] = $p_score5;
$p_score[6] = $p_score6;
$p_score[7] = $p_score7;

$tpl = $pdf->importPage(4);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);


$sql_inferences1 = "select * from wls_self_esteem_in where id = '1'";
$res_inferences1 = mysqli_query($con,$sql_inferences1);
$row_in1 = mysqli_fetch_array($res_inferences1);

$pdf->SetXY(30,60);
$pdf->SetFont('arial','',8.5);
$pdf->MultiCell(150,5.5,$row_in1['general_esteem'],'J');

$pdf->SetXY(30,124);
$pdf->SetFont('arial','',8.5);
$pdf->MultiCell(150,5.5,$row_in1['healthy'],'J');

$pdf->SetXY(30,176);
$pdf->SetFont('arial','',8.5);
$pdf->MultiCell(150,5.5,$row_in1['unhealthy'],'J');
$pdf->SetXY(30,235);
$pdf->SetFont('arial','',8.5);
$pdf->MultiCell(150,5.5,$row_in1['dr_esteem'],'J');





//page 5

$tpl = $pdf->importPage(5);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);

$sql_inferences2 = "select * from wls_self_esteem_in where id = '1'";
$res_inferences2 = mysqli_query($con,$sql_inferences2);
$row_in2 = mysqli_fetch_array($res_inferences2);
$pdf->SetXY(30,60);
$pdf->SetFont('arial','',10);
$pdf->MultiCell(145,5.5,$row_in2['general_efficacy'],'J');

$pdf->SetXY(30,124);
$pdf->SetFont('arial','',10);
$pdf->MultiCell(145,5.5,$row_in2['high'],'J');

$pdf->SetXY(30,158);
$pdf->SetFont('arial','',10);
$pdf->MultiCell(145,5.5,$row_in2['low'],'J');

$pdf->SetXY(30,200);
$pdf->SetFont('arial','',10);
$pdf->MultiCell(145,5.5,$row_in2['dr_efficacy'],'J');

$tpl = $pdf->importPage(6);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);

//Starting top needs identification

for($i=1;$i<=21;$i++)
{
    $scorew[$i]=0;
}

for($i=1;$i<=21;$i++)
{
    
    for($j=1;$j<=5;$j++)
    {
        $sql_top = "select * from wls_part2_rank_ordring where code='$code' and grp='$i' and qno='$j'";
        $res_top = mysqli_query($con,$sql_top);
        $row_top = mysqli_fetch_array($res_top);
        $ordr = $row_top['ordr'];
        
        /* Commented by Sudhir on 23-Oct-2021
        $temp_score = 5-(int)$ordr; 
        $sql_ck = "select * from wls_part2_1_detail where grp='$i' and qno='$j'";
        * End of Comment ********************/
        
        $temp_score = 6-(int)$ordr; 
        $sql_ck = "select * from wls_part2_1_detail where grp='$i' and qno='$j'";
        
        
        $res_ck = mysqli_query($con,$sql_ck);
        $row_ck = mysqli_fetch_array($res_ck);
        $q_id = $row_ck['q_id'];

        $scorew[$q_id] = $scorew[$q_id] + $temp_score; 
    }
    
   
}
$get_score1 = "select * from temp_order_score where code='$code'";
$res_score = mysqli_query($con,$get_score1);
$num = mysqli_num_rows($res_score);
if($num==0)
{
    for($i=1;$i<=21;$i++)
    {
        $sc = $scorew[$i];
       
        
        $sql_insert = "insert into temp_order_score(q_id,code,score) values('$i','$code','$sc')";
        mysqli_query($con,$sql_insert);
    }
}

$i = 1;
$sum_top_5_score = 0;
$pos = 219;
$get_score = "select * from temp_order_score where code='$code' order by score DESC limit 5";
$res_score = mysqli_query($con,$get_score);
while($row_score = mysqli_fetch_array($res_score))
{
    $q_id = $row_score['q_id'];
    $score_f = $row_score['score'];
    echo "Score :".$score_f."<br>";
    $get_second_score = "select * from ppe_part1_test_details where solution='ps_part6_2' and code='$code' and qno='$q_id'";
    $res_secode_score = mysqli_query($con,$get_second_score);
    while($row_second_score=mysqli_fetch_array($res_secode_score))
    {
        $option = $row_second_score['ans'];
        if($option==1)
        {
            $temp_score = 1;
        }
        else if($option==2)
        {
            $temp_score = 2;
        }
        else if($option==3)
        {
            $temp_score = 4;
        }
        else
        {
            $temp_score = 5;
        }
        echo "Frequency :".$temp_score."<br>";
        $total_second_score[$i] = $score_f * $temp_score;
        
       
    }
    $i = $i+1;
    $sum_top_5_score = $sum_top_5_score + $score_f;

    //printing top needs on pdf table
    $get_record_qno = "select * from wls_part2_1_detail where q_id='$q_id'";
    $res_record_qno = mysqli_query($con,$get_record_qno);
    $row_record_qno = mysqli_fetch_array($res_record_qno);
    $pdf->SetXY(40,$pos);
    $pdf->SetFont('arial');
    $pdf->Cell(0,20,$row_record_qno['item'], 0, 0,'L',false);
    $pos = $pos + 8.5;
}

echo "Top Needs Score :".$sum_top_5_score."<br>";
echo "Need X Frequency Score :<pre>";
print_r($total_second_score);

$sum_of_second_score = 0;
for($i=1;$i<=5;$i++)
{
    $sum_of_second_score = $sum_of_second_score + $total_second_score[$i];
}

echo "Overall Weighted Score :".$sum_of_second_score."<br>";


//page 7 
$tpl = $pdf->importPage(7);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);


$devide = $sum_of_second_score/$sum_top_5_score;
$ve_per = $devide*100/5;

echo "Value Fulfilment Score :".$ve_per."<br>";

$score_third_f = 0;
$get_third_score = "select * from ppe_part1_test_details where solution='ps_part6_3' and code='$code'";
$res_third_score = mysqli_query($con,$get_third_score);
while($row_third_score=mysqli_fetch_array($res_third_score))
{
    $option = $row_third_score['ans'];
    if($option==1)
    {
        $temp_score = 1;
    }
    else if($option==2)
    {
        $temp_score = 2;
    }
    else if($option==3)
    {
        $temp_score = 4;
    }
    else
    {
        $temp_score = 5;
    }
    $score_third_f = $score_third_f + $temp_score;    
}



$participation = $score_third_f / 10;
$per = $participation * 100/5;

echo "Participation Score :".$per."<br>";

//graph
$chartX=35;
$chartY=46;
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
        'Value Expectation'=>['color'=>[0,255,0],'value'=>$ve_per],
        'Participation'=>['color'=>[0,25,10],'value'=>$per]
       
);
/* Commented by Sudhir on 23 Oct 2021
$data2=Array(
    'Value Expectation'=>['color'=>[0,255,0],'value'=>$p_score1],
    'Participation'=>['color'=>[0,25,10],'value'=>$p_score2],
    
);
************************************/

//Added by Sudhir on 23 Oct 2021

$data2=Array(
    'Value Expectation'=>['color'=>[0,255,0],'value'=>$ve_per],
    'Participation'=>['color'=>[0,25,10],'value'=>$per],
);


//End of Added by Sudhir on 23 Oct 2021



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
//$pdf->Cell(100,10,"GRAPH Namfffhhhfhhhhfhfhfhffhffsffffffffsfe",0,0,'C');


// //legend properties
// $legendX=130;
// $legendY=52;

// //draw th legend
// $pdf->SetFont('Arial','',8);

// //store current legend Y position
// $currentLegendY=$legendY;
// foreach($data2 as $index=>$item)
// {
// //add legend color
// $pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);

// // remove border
// $pdf->SetDrawColor($item['color'][0],$item['color'][1],$item['color'][2]);
// $pdf->Rect($legendX,$currentLegendY,5,5,'DF');
// $pdf->SetXY($legendX+6,$currentLegendY);
// $pdf->Cell(50,5,$index,0,0);
// $currentLegendY+=5;
// }
//value fulfilment status
if($ve_per<=50)
{
    $v_status = 'L';
    $v_s = 'Low';
}
else if($ve_per>50 && $ve_per<=80)
{
    $v_status = 'M';
    $v_s = 'Moderate';
}
else
{
    $v_status = 'H';
    $v_s = 'High';
}
//Participation status
if($per<=50)
{
    $p_status = 'L';
    $p_s = 'Low';
}
else if($per>50 && $per<=80)
{
    $p_status = 'M';
    $p_s = 'Moderate';
}
else
{
    $p_status = 'H';
    $h_s = 'High';
}

$pdf->SetXY(30,113);
$pdf->SetFont('arial','b',12);
$pdf->MultiCell(145,7,'Value Expectation:- '.$v_s.', Participation :- '.$p_s);

$sql_inferences = "select * from wls_satisfaction_in where Value_Fulfilment='$v_status' and Participation='$p_status'";
$res_inferences = mysqli_query($con,$sql_inferences);
$row_in = mysqli_fetch_array($res_inferences);




$str_inf = $row_in['Con_overall_sa']."\r\n".
           $row_in['Recommendation_1']."\r\n".
           $row_in['Recommendation_2']."\r\n";




$pdf->SetXY(30,123);
$pdf->SetFont('arial','',8.5);
$pdf->MultiCell(145,7,$str_inf);



/*
$pdf->SetXY(30,123);
$pdf->SetFont('arial','',8);
$pdf->MultiCell(145,7,$row_in['Con_overall_sa']);


$pdf->SetXY(30,150);
$pdf->SetFont('arial','',8);
$pdf->MultiCell(145,7,$row_in['Recommendation_1']);


$pdf->SetXY(30,223);
$pdf->SetFont('arial','',8);
$pdf->MultiCell(145,5.5,$row_in['Recommendation_2']);

*/


include('Remark.php');



ob_end_clean();
$pdf->AliasNbPages();

$pdf->Output();
// $pdf2->Output();

ob_end_flush();



?>