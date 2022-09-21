<?php
ob_start();
$code = base64_decode($_GET['code']);
include('dbconn.php');
$sql_for_logo = "select * from user_code_list where code='$code'";
$res_for_logo = mysqli_query($con,$sql_for_logo);
$row_for_logo = mysqli_fetch_array($res_for_logo);
$reseller_id = $row_for_logo['reseller_id'];
$user_id = $row_for_logo['user_id'];

$sql_for_reseller_info = "select * from reseller_homepage where r_email='$reseller_id'";
$res_for_reseller_info = mysqli_query($con,$sql_for_reseller_info);
$row_for_reseller_info = mysqli_fetch_array($res_for_reseller_info);
$logo = $row_for_reseller_info['logo'];
$signature = 'https://users.respicite.com/'.$row_for_reseller_info['reseller_signature'];
$logo = 'https://users.respicite.com/'.$logo;
// $logo = '../uploads/default.jpg';
$size = getimagesize($logo);
$wImg = $size[0];
$hImg = $size[1];


use setasign\Fpdi\Fpdi;

require_once('fpdf181/fpdf181/fpdf.php');
require_once('fpdi2/fpdi2/src/autoload.php');

// Create new Landscape PDF
$pdf = new FPDI();



// Import the first page from the PDF and add to dynamic PDF
$pagecount = $pdf->setSourceFile('report_template/SDP3_2Page.pdf');
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);


//Add client Details Page
require_once('Second_Detail_Page.php');


// Import the 2nd page from the PDF and add to dynamic PDF
$pagecount = $pdf->setSourceFile('report_template/SDP3_2Page.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);


$pagecount = $pdf->setSourceFile('report_template/SDP3.pdf');

// Import the first page from the PDF and add to dynamic PDF
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
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
        
        
        
        /* commented by Sudhir
        $temp_score = 6-$j; 
        $sql_ck = "select * from wls_part2_1_detail where grp='$i' and qno='$ordr'";
        */
        
        //Added by Sudhir
        $temp_score = 6-$ordr; 
        $sql_ck = "select * from wls_part2_1_detail where grp='$i' and qno='$j'";
        //Added by Sudhir end
        

        
        $res_ck = mysqli_query($con,$sql_ck);
        $row_ck = mysqli_fetch_array($res_ck);
        $q_id = $row_ck['q_id'];

        $score[$q_id] = $score[$q_id] + $temp_score; 
    }
    
    
    
   
}
for ($i=0;$i<21;$i++)
{
    //echo "<pre>";
    //print_r($score);
    
    
}

$get_score1 = "select * from temp_order_score where code='$code'";
$res_score = mysqli_query($con,$get_score1);
$num = mysqli_num_rows($res_score);
for ($i=0;$i<21;$i++)
{
    //echo "Score :".$score[$i]."<br>";
}
if($num==0)
{
    for($i=1;$i<=21;$i++)
    {
        $sc = $score[$i];
        //echo "<br>";
        
        $sql_insert = "insert into temp_order_score(q_id,code,score) values('$i','$code','$sc')";
        mysqli_query($con,$sql_insert);
    }
}

$i = 1;
$sum_top_5_score = 0;
$pos = 200;
$get_score = "select * from temp_order_score where code='$code' order by score DESC limit 5";
$res_score = mysqli_query($con,$get_score);
while($row_score = mysqli_fetch_array($res_score))
{
    $q_id = $row_score['q_id'];
    $score_f = $row_score['score'];
    $get_record_qno = "select * from wls_part2_1_detail where q_id='$q_id'";
    $res_record_qno = mysqli_query($con,$get_record_qno);
    $row_record_qno = mysqli_fetch_array($res_record_qno);
    //echo "<pre>";
    //print_r($row_record_qno);
    $pdf->SetXY(40,$pos);
    $pdf->SetFont('arial');
    $pdf->Cell(0,20,$row_record_qno['item'], 0, 0,'L',false);
    $pos = $pos + 13;
}

$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

$para = [
    'Emotional Intelligence',
    'Social skills',
    'Cooperation',
    'Coordination'
    ];
    $count = count($para);
    for($j=0;$j<$count-1;$j++)
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
            $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='sdp3_part2' and qno='$qno'";
            $res4 = mysqli_query($con,$sql4);
            $row4 = mysqli_fetch_array($res4);
            $ans = $row4['first_ans'];
            if($ans == 'yes')
            {
                if($i==3)
                {
                  $j=2;
                    $sec_ans = $row4['sec_ans'];
                   $p_score[$j] = $p_score[$j] + $sec_ans;
                   
                   $q_n[$j] = $q_n[$j] + 1; 
                }
                else
                {
                    $sec_ans = $row4['sec_ans'];
                    //doubt Danish
                    $p_score[$i] = $p_score[$i] + $sec_ans;
                   
                    $q_n[$i] = $q_n[$i] + 1; 
                }
                
            }

            
        }
    }
    
    echo "<pre>";
    print_r($q_n);
    print_r($p_score);
    
    $coma_count = 0;
    $fa = '';
    $need_s = array();
    for($j=0;$j<$count-1;$j++)
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
            if($j==2)
            {
                array_push($need_s,'Cooperation'); 
                array_push($need_s,'Coordination');
            }
            else
            {
                array_push($need_s,$para[$j]); 
            }
              
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
            if($j==2)
            {
                array_push($need_s,'Cooperation'); 
                array_push($need_s,'Coordination');
            }
            else
            {
                array_push($need_s,$para[$j]); 
            }
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
    
    $pdf->SetFontSize('9');
    $pdf->SetXY(145,68);
    $pdf->Cell(40, 6,$p_status[0]);
    
    $pdf->SetXY(145,86);
    $pdf->Cell(40, 6,$p_status[1]);
    
    $pdf->SetXY(145,98);
    $pdf->Cell(40, 6,$p_status[2]);
    
    
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
        'C'=>['color'=>[247,253,4],'value'=>$p_per[2]]
       
    );
    $data2=Array(
        'A - '.$para[0]=>['color'=>[24,203,238],'value'=>$p_per[0]],
        'B - '.$para[1]=>['color'=>[172,13,26],'value'=>$p_per[1]],
        'C - Working with People'=>['color'=>[247,253,4],'value'=>$p_per[2]]
       
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
    $pdf->SetXY($chartX,135);
    //$pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2));
    $pdf->Cell(100,10,"Work Skills",0,0,'C');
    
    //legend properties
    $legendX=138;
    $legendY=163;
    
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
    $pdf->SetXY(35,232);
    $pdf->MultiCell(150,6,$fa);
    
    $pagecount = $pdf->setSourceFile('report_template/Border Page.pdf');
    $tpl = $pdf->importPage(1);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    
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
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(219,233,201); // Background color of header 
    // Header starts /// 
    $pdf->cell(184,8,'Concerns, Suggestions',1,1,'L',true);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetXY(12.5,46);
    $pdf->SetTextColor(0,0,0);
    // $pdf->SetFillColor(219,233,201);
    $pdf->Cell(30,6,'Dimension',1,0,'L',true); // First header column 
    $pdf->Cell(55,6,'Description',1,0,'L',true); // Second header column
    
    $pdf->Cell(99,6,'Status',1,1,'L',true); // Fourth header column
    
    $pdf->SetFont('Arial','B',10);
    $pdf->SetXY(12.5,52);
    $width_cell=array(38,30,48,70);
    $pdf->SetTextColor(0,0,0);
    // $pdf->SetFillColor(219,233,201); // Background color of header 
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
                
                $tpl = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
               
                checking_size($logo,$pdf);
                $pdf->SetFont('Arial','B',12);
                $pdf->SetXY(12.5,38);
                $width_cell=array(38,30,48,70);
                $pdf->SetTextColor(118,146,60);
                // $pdf->SetFillColor(219,233,201); // Background color of header 
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