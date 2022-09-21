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


// Import the first page from the PDF and add to dynamic PDF
$pagecount = $pdf->setSourceFile('report_template/WPA.pdf');
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);


//Add client's details
include('Second_Detail_Page.php');


// Import 2nd-page from the PDF and add to dynamic PDF
$pagecount = $pdf->setSourceFile('report_template/WPA.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);


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
    
//WPA part 1 #START here.
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
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='wpa_part1' and qno='$qno'";
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
$pdf->Cell(20, 10,'Your Aptitude Score : '.$score, 0, 0, 'L');


$pdf->SetFontSize('11');
if($score>27)
{
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
    $tpl = $pdf->importPage(6);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    
    checking_size($logo,$pdf);
    
}
else
{
    $tpl = $pdf->importPage(7);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

    checking_size($logo,$pdf);

}

//WPA part 1 #END here.

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

echo "Group Details :<pre>";
print_r($grp);

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

echo "Group wise categories :<br>";
echo "Categories for Group ".$grp[0]."<pre>";
print_r($p1);
echo "Categories for Group ".$grp[1]."<pre>";
print_r($p2);
echo "Categories for Group ".$grp[2]."<pre>";
print_r($p3);





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
    $p_status[$j] =0;
    
}
//calculation
echo "Fetching all responses for Group :".$pmt1."<br>";
$sql3 = "select * from wpa_part2 where grp='$pmt1'";
$res3 = mysqli_query($con,$sql3);
while($row3 = mysqli_fetch_array($res3))
{
    $qno = $row3['qno'];
    $category = $row3['category'];
    $sub_category = $row3['sub_category'];
    $grp = $row3['grp'];
    echo "Group Fetched :".$grp.", Category Fetched :".$category.", Sub-category Fetched :".$sub_category."<br>";
    
    $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='wpa_part2' and qno='$qno'";
    $res4 = mysqli_query($con,$sql4);
    $row4 = mysqli_fetch_array($res4);
    $ans = $row4['first_ans'];
    echo "Answer Fetched :".$ans."<br>"; 
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

/*
echo "Category-wise scores in Group :".$pmt1."<pre>";
$print_r($p_score[0]);
echo "Category-wise no of questions in Group :".$pmt1."<pre>";
$print_r($q_n[0]);
*/

$coma_count = 0;
$fa = '';
$need_s = array();

echo "Status of Categories in Group :".$pmt1."<br>";


for($j=0;$j<count($p1);$j++)
{
    
    
    echo "Category Name :".$p1[$j]."<br>";
    echo "Category Question Count :".q_n[$j]."<br>";
    echo "Category Score :".$p_score[$j]."<br>";
    echo "Category Score_per :".$p_per[$j]."<br>";
    
    
    
    if ($q_n[$j]==0)
    {
        $p_status[$j] = 'Low relevance in current role';
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
    
    echo "Category Status :".$p_status[$j]."<br>";

    


 
}





//status
$pdf->SetFontSize('9.5');
$pdf->SetXY(142,66);
$pdf->Cell(40, 6,$p_status[0],0,0,'L',false);

$pdf->SetXY(142,80);
$pdf->Cell(40, 6,$p_status[1],0,0,'L',false);

$pdf->SetXY(142,94);
$pdf->Cell(40, 6,$p_status[2],0,0,'L',false);

$pdf->SetXY(142,109);
$pdf->Cell(40, 6,$p_status[3],0,0, 'L',false);

$pdf->SetXY(142,121.5);
$pdf->Cell(40, 6,$p_status[4],0,0,'L',false);

$pdf->SetXY(142,135);
$pdf->Cell(20, 10,$p_status[5],0,0,'L',false);

$pdf->SetXY(142,148);
$pdf->Cell(40, 6,$p_status[6],0,0,'L',false);

$pdf->SetXY(142,163);
$pdf->Cell(40, 6,$p_status[7],0,0,'L',false);

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
$pdf->SetFont('Arial','B',16);
$pdf->SetXY($chartX,$chartY);
//$pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2));
$pdf->Cell(100,10,"Task Leadership",0,0,'C');

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
$pdf->Cell(50,5,$index,0,0);
$currentLegendY+=5;
}

$pdf->SetFontSize('11');
$pdf->SetXY(25,250);
$pdf->MultiCell(150,6,$fa);


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
    $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='wpa_part2' and qno='$qno'";
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

for($j=0;$j<count($p2);$j++)
{
    
    if ($q2_n[$j]==0)
    {
        $p2_status[$j] = 'Low relevance in current role';
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
$pdf->SetFontSize('9.5');
$pdf->SetXY(147,72);
$pdf->Cell(40, 6,$p2_status[0],0,0,'L',false);
//$pdf->MultiCell(40, 6,$p2_status[0]);

$pdf->SetXY(147,88);
$pdf->Cell(40, 6,$p2_status[1],0,0,'L',false);
//$pdf->MultiCell(40, 6,$p2_status[1]);

$pdf->SetXY(147,106);
$pdf->Cell(40, 6,$p2_status[2],0,0,'L',false);
//$pdf->MultiCell(40, 6,$p2_status[2]);

$pdf->SetXY(147,121);
$pdf->Cell(40, 6,$p2_status[3],0,0,'L',false);
//$pdf->MultiCell(40, 6,$p2_status[3]);

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
$pdf->SetFont('Arial','B',16);
$pdf->SetXY($chartX,$chartY);
//$pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2));
$pdf->Cell(100,10,"Personal Leadership",0,0,'C');

//legend properties
$legendX=135;
$legendY=165;

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
$pdf->SetXY(30,235);
$pdf->Cell(20, 10,$fa, 0, 0, 'L');


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
    $p3_status[$j] =0;
    
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
    $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='wpa_part2' and qno='$qno'";
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
        $p3_status[$j] = 'Low relevance in current role';
        $p3_per[$j] =0;
    }
    
    else
    
    {
        $p3_per[$j] = ($p3_score[$j]*100)/($q3_n[$j]*5); 
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
//status
$pdf->SetFontSize('9.5');
$pdf->SetXY(147,70);
$pdf->Cell(20, 10,$p3_status[0], 0, 0, 'L');

$pdf->SetXY(147,83);
$pdf->Cell(20, 10,$p3_status[1], 0, 0, 'L');

$pdf->SetXY(147,98);
$pdf->Cell(20, 10,$p3_status[2], 0, 0, 'L');

$pdf->SetXY(147,111);
$pdf->Cell(20, 10,$p3_status[3], 0, 0, 'L');

$pdf->SetXY(147,122);
$pdf->Cell(20, 10,$p3_status[4], 0, 0, 'L');

$pdf->SetXY(147,135);
$pdf->Cell(20, 10,$p3_status[5], 0, 0, 'L');

$pdf->SetXY(147,149);
$pdf->Cell(20, 10,$p3_status[6], 0, 0, 'L');

$pdf->SetXY(147,165);
$pdf->Cell(20, 10,$p3_status[7], 0, 0, 'L');

$pdf->SetXY(147,175);
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
$pdf->SetFont('Arial','B',16);
$pdf->SetXY($chartX,$chartY);
//$pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2));
$pdf->Cell(100,10,"People leadership",0,0,'C');

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
$pdf->Cell(20, 10,$fa, 0, 0, 'L');

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
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(219,233,201); // Background color of header 
// Header starts /// 
$pdf->cell(184,8,'Concerns, Suggestions',1,1,'L',true);
$pdf->SetFont('Arial','B',11);
$pdf->SetXY(12.5,46);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(219,233,201);
$pdf->Cell(30,6,'Dimension',1,0,'L',true); // First header column 
$pdf->Cell(55,6,'Description',1,0,'L',true); // Second header column

$pdf->Cell(99,6,'Status',1,1,'L',true); // Fourth header column

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(12.5,52);
$width_cell=array(38,30,48,70);
$pdf->SetTextColor(0,0,0);
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
$pdf->SetAutoPageBreak(true);
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
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFillColor(219,233,201); // Background color of header 
            // Header starts /// 
            $pdf->cell(184,8,'Concerns, Suggestions',1,1,'L',true);
            $pdf->SetFont('Arial','B',11);
            $pdf->SetXY(12.5,46);
            $pdf->SetTextColor(0,0,0);
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
    

  
include('Remark.php');

ob_end_clean();
$pdf->AliasNbPages();

$pdf->Output();
// $pdf2->Output();

ob_end_flush();

?>