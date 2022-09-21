<?php
ob_start();
$code = base64_decode($_GET['code']);
include('dbconn.php');
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
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='wls_part1' and qno='$qno'";
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
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='wls_part1' and qno='$qno'";
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

$p_score1 = ($p1*100)/($p_no1*3);
$p_score2 = ($p2*100)/($p_no2*3);
$p_score3 = ($p3*100)/($p_no3*3);
$p_score4 = ($p4*100)/($p_no4*5);
$p_score5 = ($p5*100)/($p_no5*5);
$p_score6 = ($p6*100)/($p_no6*5);
$p_score7 = ($p7*100)/($p_no7*5);

$sql_for_logo = "select * from user_code_list where code='$code'";
$res_for_logo = mysqli_query($con,$sql_for_logo);
$row_for_logo = mysqli_fetch_array($res_for_logo);
$reseller_id = $row_for_logo['reseller_id'];

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
if($p_score7>0 && $p_score7<=50)
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

// include composer packages
use setasign\Fpdi\Fpdi;

require_once('fpdf181/fpdf181/fpdf.php');
require_once('fpdi2/fpdi2/src/autoload.php');

// Create new Landscape PDF
$pdf = new FPDI();


// Reference the PDF you want to use (use relative path)


// Import the first page from the PDF and add to dynamic PDF
$pagecount = $pdf->setSourceFile('report_template/WLS.pdf');
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

//Add client details page
include('Second_Detail_Page.php');



// Import 2nd page of 2-page cover pdf
$pagecount = $pdf->setSourceFile('report_template/WLS.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);


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
    

//page 3 
$tpl = $pdf->importPage(3);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

checking_size($logo,$pdf);


$sc_status = array($p1_status,$p2_status,$p3_status,$p4_status,$p5_status,$p6_status,$p7_status);
$status_X = 148;
$status_Y = array(76,95,114,135,153,165,178);
for($i=0;$i<7;$i++)
{
    $pdf->SetXY($status_X,$status_Y[$i]);
    if(strlen($sc_status[$i]<15))
    {
        $pdf->SetFontSize('11');
        $pdf->Cell(15, 10,$sc_status[$i], 0, 0, 'L');
    }
    else 
    {
        $pdf->SetFontSize('10');
        $pdf->MultiCell(15, 10,$sc_status[$i], 0, 'L');
    }
}
/*
echo "<pre>";
print_r($ei_status);

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
*/

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
$pdf->SetFont('Arial','B',9);
$pdf->SetXY($chartX,$chartY);
//$pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2));
$pdf->Cell(100,10,"Self-Concept",0,0,'C');


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
$pdf->SetXY(30,265);
$pdf->Cell(20, 10,$fa, 0, 0, 'L');
$p_score[1] = $p_score1;
$p_score[2] = $p_score2;
$p_score[3] = $p_score3;
$p_score[4] = $p_score4;
$p_score[5] = $p_score5;
$p_score[6] = $p_score6;
$p_score[7] = $p_score7;


$pagecount = $pdf->setSourceFile('report_template/Border Page.pdf');
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);
$ref = array();
$sql_inferences1 = "select * from wls_self_esteem_in where id = '1'";
$res_inferences1 = mysqli_query($con,$sql_inferences1);
$row_in1 = mysqli_fetch_array($res_inferences1);
array_push($ref,$row_in1['general_esteem']);
array_push($ref,$row_in1['healthy']);
array_push($ref,$row_in1['unhealthy']);
array_push($ref,$row_in1['dr_esteem']);

$pdf->SetXY(12.5,44);
$pdf->SetTextColor(255,255,255);
$pdf->cell(184,8,'Self-esteem (self-satisfaction, Self-worth, Self-appreciation) - Inferences, Development recommendations',1,1,'L',true);
$pdf->SetFont('Arial','B',11);

$pdf->SetFont('Arial','B',8);
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
    $cells = 0;
    
    //$width_cell=array(38,30,48,70);
    $p = 1;
    $page_height = 420; // mm 
    $pdf->SetAutoPageBreak(false);
    foreach($ref as $item)
    {
        if($p==1)
        {
            $heading = 'General';
        }
        else if($p==2)
        {
            $heading = 'People with healthy self-esteem';
        }
        else if($p==3)
        {
            $heading = 'People with unhealthy self-esteem';
        }
        else
        {
            $heading = 'Development recommendations';
        }
            $x = $x;
            $y = $y+$maxheight;
            $height_of_cell=$y-$yn;
            $space_left=$page_height-($y); // space left on page
            if ($height_of_cell > $space_left) 
            {
                // $pdf->Write($y+$yn,'Next');
                
                $tpl = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
               
                checking_size($logo,$pdf);
                $pdf->SetFont('Arial','B',10);
                $pdf->SetXY(12.5,38);
                $width_cell=array(38,30,48,70);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFillColor(219,233,201); // Background color of header 
                // Header starts /// 
                
                
            //   $pdf->SetXY(12, 30);// page break
                $pdf->SetFont('Arial','B',8.5);
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
                for ($i = 0; $i <= $cells; $i++) 
                {
                    // $pdf->SetXY($x + ($width * ($i)) , $y);
                    if($i==0)
                    {
                        $pdf->SetXY($x + (0) , $y);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(20, 10,$heading, 0, 0, 'L');
                        $pdf->SetXY($x + (0) , $y+10);
                        $pdf->SetFont('Arial','B',8.5);
                        $pdf->MultiCell(170, $height, $item);
                    }
                    
                    
                    if ($pdf->GetY() - $y > $maxheight) 
                    {
                        $maxheight = $pdf->GetY() - $y;
                        
                    } 
                                
                }
                // $pdf->SetXY($x + ($width * ($i + 1)), $y);
    
                for ($i = 0; $i <= $cells + 1; $i++) 
                {
                    if($i==0)
                    {
                        $pdf->Line($x + 170 * $i, $y, $x + 170 * $i, $y + $maxheight);
                    }
                    else if($i==1)
                    {
                        $pdf->Line($x + 184 * $i, $y, $x + 184 * $i, $y + $maxheight); 
                    }
                    
    
                }
                $pdf->Line($x, $y, $x + $width * 4, $y);// top line
                $pdf->Line($x, $y + $maxheight, $x + $width * 4, $y + $maxheight);//bottom
       $p++;                    
    } 
      





$pagecount = $pdf->setSourceFile('report_template/WLS.pdf');





// //page 5

// $tpl = $pdf->importPage(5);
// $pdf->AddPage();
// $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
// checking_size($logo,$pdf);

// $sql_inferences2 = "select * from wls_self_esteem_in where id = '1'";
// $res_inferences2 = mysqli_query($con,$sql_inferences2);
// $row_in2 = mysqli_fetch_array($res_inferences2);
// $pdf->SetXY(30,60);
// $pdf->SetFont('arial','',8);
// $pdf->MultiCell(145,5.5,$row_in2['general_efficacy'],'J');

// $pdf->SetXY(30,124);
// $pdf->SetFont('arial','',8);
// $pdf->MultiCell(145,5.5,$row_in2['high'],'J');

// $pdf->SetXY(30,158);
// $pdf->SetFont('arial','',8);
// $pdf->MultiCell(145,5.5,$row_in2['low'],'J');

// $pdf->SetXY(30,200);
// $pdf->SetFont('arial','',8);
// $pdf->MultiCell(145,5.5,$row_in2['dr_efficacy'],'J');

$tpl = $pdf->importPage(6);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);


for($i=1;$i<=21;$i++)
{
    $score[$i] = 0;
}

for($i=1;$i<=21;$i++)
{
    
    for($j=1;$j<=5;$j++)
    {
        $sql_top = "select * from wls_part2_rank_ordring where code='$code' and grp='$i' and qno='$j'";
        $res_top = mysqli_query($con,$sql_top);
        $row_top = mysqli_fetch_array($res_top);
        $ordr = $row_top['ordr'];
        
        /* Commented by Sudhir on 21-Oct-2021
        $temp_score = 6-$j; 
    
        $sql_ck = "select * from wls_part2_1_detail where grp='$i' and qno='$ordr'";
        
        *  Added by Sudhir on 21-Oct-2021*****/
        
        //Added by Sudhir on 21-Oct-2021
        $temp_score = 6-$ordr; 
        $sql_ck = "select * from wls_part2_1_detail where grp='$i' and qno='$j'";
   
        //End of Added by Sudhir on 21-Oct-2021
        
        
        
        $res_ck = mysqli_query($con,$sql_ck);
        $row_ck = mysqli_fetch_array($res_ck);
        $q_id = $row_ck['q_id'];

        $score[$q_id] = $score[$q_id] + $temp_score; 
    }
    
   
}
$get_score1 = "select * from temp_order_score where code='$code'";
$res_score = mysqli_query($con,$get_score1);
$num = mysqli_num_rows($res_score);
if($num==0)
{
    for($i=1;$i<=21;$i++)
    {
        $sc = $score[$i];
        echo "<br>";
        
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
    $get_second_score = "select * from ppe_part1_test_details where solution='wls_part2_2' and code='$code' and qno='$q_id'";
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
        $total_second_score[$i] = $score_f * $temp_score;
        
       
    }
    $i = $i+1;
    $sum_top_5_score = $sum_top_5_score + $score_f;

    //printing on pdf table
    $get_record_qno = "select * from wls_part2_1_detail where q_id='$q_id'";
    $res_record_qno = mysqli_query($con,$get_record_qno);
    $row_record_qno = mysqli_fetch_array($res_record_qno);
    $pdf->SetXY(40,$pos);
    $pdf->SetFont('arial','',10);
    $pdf->Cell(0,20,$row_record_qno['item'], 0, 0,'L',false);
    $pos = $pos + 8.5;
}

$sum_of_second_score = 0;
for($i=1;$i<=5;$i++)
{
    $sum_of_second_score = $sum_of_second_score + $total_second_score[$i];
}

//page 7 
$tpl = $pdf->importPage(7);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);


$devide = $sum_of_second_score/$sum_top_5_score;
$ve_per = $devide*100/5;
/*
echo "Value Expectation :".$devide."<br>";
echo "Value Expectation_Per :".$ve_per."<br>";
*/
$score_third_f = 0;
$get_third_score = "select * from ppe_part1_test_details where solution='wls_part2_3' and code='$code'";
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
$data2=Array(
    'Value Expectation'=>['color'=>[0,255,0],'value'=>$p_score1],
    'Participation'=>['color'=>[0,25,10],'value'=>$p_score2],
    
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
$pdf->SetFont('Arial','B',16);
$pdf->SetXY($chartX,$chartY);
//$pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2));
$pdf->Cell(100,10,"Life Role",0,0,'C');


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
    $p_s = 'High';
}

$pdf->SetXY(30,113);
$pdf->SetFont('arial','b',12);
$pdf->MultiCell(150,7,'Value Expectation:- '.$v_s.', Participation :- '.$p_s.'');

$sql_inferences = "select * from wls_satisfaction_in where Value_Fulfilment='$v_status' and Participation='$p_status'";
$res_inferences = mysqli_query($con,$sql_inferences);
$row_in = mysqli_fetch_array($res_inferences);

// $pdf->SetXY(30,123);
// $pdf->SetFont('arial','',10);
// $pdf->MultiCell(145,7,$row_in['Con_overall_sa']);

// $pdf->SetXY(30,150);
// $pdf->SetFont('arial','',10);
// $pdf->MultiCell(145,7,$row_in['Recommendation_1']);

// $pdf->SetXY(30,223);
// $pdf->SetFont('arial','',10);
// $pdf->MultiCell(145,5.5,$row_in['Recommendation_2']);
$item2 = array($row_in['Con_overall_sa'],$row_in['Recommendation_1'],$row_in['Recommendation_2']);

    $pdf->SetXY(12.5,113);
    $pdf->SetTextColor(255,255,255);
    $pdf->cell(184,8,'Value Expectation:- '.$v_s.', Participation :- '.$p_s,1,1,'L',true);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetFont('Arial','B',8);
    $pdf->SetXY(12.5,121);
    $width_cell=array(38,30,48,70);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(219,233,201); // Background color of header 
    // Header starts /// 
    
    $xn = $x = $pdf->GetX();
    $yn = $y = $pdf->GetY();
    
    $maxheight = 0;
    $width=46;
    $height=6;
    $cells = 0;
    
    //$width_cell=array(38,30,48,70);
    $p = 1;
    $page_height = 420; // mm 
    $pdf->SetAutoPageBreak(false);
    foreach($item2 as $item)
    {
       
            $x = $x;
            $y = $y+$maxheight;
            $height_of_cell=$y-$yn;
            $space_left=$page_height-($y); // space left on page
            if ($height_of_cell > $space_left) 
            {
                // $pdf->Write($y+$yn,'Next');
                
                $tpl = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
               
                checking_size($logo,$pdf);
                $pdf->SetFont('Arial','B',10);
                $pdf->SetXY(12.5,38);
                $width_cell=array(38,30,48,70);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFillColor(219,233,201); // Background color of header 
                // Header starts /// 
                
                
            //   $pdf->SetXY(12, 30);// page break
                $pdf->SetFont('Arial','B',8.5);
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
                for ($i = 0; $i <= $cells; $i++) 
                {
                    // $pdf->SetXY($x + ($width * ($i)) , $y);
                    if($i==0)
                    {
                        $pdf->SetXY($x + (0) , $y);
                        $pdf->SetFont('Arial','B',8.5);
                        $pdf->MultiCell(170, $height, $item);
                    }
                    
                    
                    if ($pdf->GetY() - $y > $maxheight) 
                    {
                        $maxheight = $pdf->GetY() - $y;
                        
                    } 
                                
                }
                // $pdf->SetXY($x + ($width * ($i + 1)), $y);
    
                for ($i = 0; $i <= $cells + 1; $i++) 
                {
                    if($i==0)
                    {
                        $pdf->Line($x + 170 * $i, $y, $x + 170 * $i, $y + $maxheight);
                    }
                    else if($i==1)
                    {
                        $pdf->Line($x + 184 * $i, $y, $x + 184 * $i, $y + $maxheight); 
                    }
                    
    
                }
                $pdf->Line($x, $y, $x + $width * 4, $y);// top line
                $pdf->Line($x, $y + $maxheight, $x + $width * 4, $y + $maxheight);//bottom
       $p++;                    
    } 

    include('Remark.php');
 

ob_end_clean();
$pdf->AliasNbPages();

$pdf->Output();
// $pdf2->Output();

ob_end_flush();



?>