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
$pagecount = $pdf->setSourceFile('report_template/JRAP1and2.pdf');

// Import the first page from the PDF and add to dynamic PDF
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

include('Second_Detail_Page.php');

/*************************************
 * Commented by Sudhir on 20 Oct 2021
 
$pagecount = $pdf->setSourceFile('report_template/Client_Details.pdf');
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

**************************************/


$pagecount = $pdf->setSourceFile('report_template/JRAP1and2.pdf');
// Import the 2nd page from the PDF and add to dynamic PDF
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

/*
$detail_sql = "select * from user_code_list where code='$code'";
$detail_res = mysqli_query($con,$detail_sql);
$detail_row = mysqli_fetch_array($detail_res);
$name = $detail_row['name'];
$dob = $detail_row['dob'];
$gender = $detail_row['gender'];
$mobile = $detail_row['mobile'];
$email = $detail_row['email'];
$address = $detail_row['address'];
$r_id = $detail_row['reseller_id'];

$r_detail_sql = "select * from reseller_homepage where r_email='$r_id'";
$r_detail_res = mysqli_query($con,$r_detail_sql);
$r_detail_row = mysqli_fetch_array($r_detail_res);


$r2_detail_sql = "select * from user_details where email='$r_id'";
$r2_detail_res = mysqli_query($con,$r2_detail_sql);
$r2_detail_row = mysqli_fetch_array($r2_detail_res);
$r_name = $r2_detail_row['fullname'];
$r_address = $r_detail_row['address'];
$r_email = $r_detail_row['email']; 
$r_mobile = $r_detail_row['contact']; 

$pdf->SetFont('arial');
$pdf->SetFontSize('12');
$pdf->SetXY(80,62);
$pdf->Cell(20, 10,$name, 0, 0, 'L');

$pdf->SetXY(80,70);
$pdf->Cell(20, 10,$dob, 0, 0, 'L');

$pdf->SetXY(80,79);
$pdf->Cell(20, 10,$gender, 0, 0, 'L');

$pdf->SetXY(80,88);
$pdf->Cell(20, 10,$mobile, 0, 0, 'L');

$pdf->SetXY(80,97);
$pdf->Cell(20, 10,$email, 0, 0, 'L');

$pdf->SetXY(80,106);
$pdf->Cell(20, 10,$address, 0, 0, 'L');


$pdf->SetXY(80,138);
$pdf->Cell(20, 10,$r_name, 0, 0, 'L');

$pdf->SetXY(80,148);
$pdf->Cell(20, 10,$r_mobile, 0, 0, 'L');

$pdf->SetXY(80,157);
$pdf->Cell(20, 10,$r_email, 0, 0, 'L');

$pdf->SetXY(80,166);
$pdf->Cell(20, 10,$r_address, 0, 0, 'L');
$pagecount = $pdf->setSourceFile('report_template/JRAP1and2.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
*/

$sql_for_reseller_info = "select * from reseller_homepage where r_email='$reseller_id'";
$res_for_reseller_info = mysqli_query($con,$sql_for_reseller_info);
$row_for_reseller_info = mysqli_fetch_array($res_for_reseller_info);
$logo = $row_for_reseller_info['logo'];
// $logo = 'https://users.respicite.online/'.$logo;
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

// checking_size($logo,$pdf);

    // add signature

$pagecount = $pdf->setSourceFile('report_template/SDP1.pdf');

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
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='jrap_part1' and qno='$qno'";
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

$pagecount = $pdf->setSourceFile('report_template/SDP3.pdf');

// Import the first page from the PDF and add to dynamic PDF
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);


checking_size($logo,$pdf);

$score = array();
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
        //echo "Temp Score :".$temp_score."<br>";
        $sql_ck = "select * from wls_part2_1_detail where grp='$i' and qno='$j'";
        //Added by Sudhir end
        
        
        
    
      
        $res_ck = mysqli_query($con,$sql_ck);
        $row_ck = mysqli_fetch_array($res_ck);
        $q_id = $row_ck['q_id'];

        $score[$q_id] = $score[$q_id] + $temp_score;  
    }
    
   
}

/*
echo "<pre>";
print_r($score);
*/

$get_score1 = "select * from temp_order_score where code='$code'";
$res_score = mysqli_query($con,$get_score1);
$num = mysqli_num_rows($res_score);
if($num==0)
{
    for($i=1;$i<=21;$i++)
    {
        $sc = $score[$i];
        
        //echo "Value Score :".$sc."<br>";
        
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
    $pdf->SetXY(40,$pos);
    $pdf->SetFont('arial');

    {
      $pdf->Cell(0,20,$row_record_qno['item'], 0, 0,'L',false);   
    }
 
    $pos = $pos + 13;
}

//jrap part 3 mwp 1
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
        $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='jrap_part3' and qno='$qno'";
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
$pdf->SetXY(142,63);
$pdf->MultiCell(40, 6,$p_status[1]);

$pdf->SetXY(142,77);
$pdf->MultiCell(40, 6,$p_status[2]);

$pdf->SetXY(142,89);
$pdf->MultiCell(40, 6,$p_status[3]);

$pdf->SetXY(142,107);
$pdf->MultiCell(40, 6,$p_status[0]);

$pdf->SetXY(142,125);
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
// $pdf->Cell(100,10,"STUDY HABITS STRENGTH",0,0,'C');

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
            $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='jrap_part3' and qno='$qno'";
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
    $pdf->MultiCell(40, 5,$p_status[0]);
    
    $pdf->SetXY(145,76);
    $pdf->MultiCell(40, 5,$p_status[1]);
    
    $pdf->SetXY(145,91);
    $pdf->MultiCell(40, 5,$p_status[2]);
    
    $pdf->SetXY(145,104);
    $pdf->MultiCell(40, 5,$p_status[3]);
    
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
    //$pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2)-50);
    //$pdf->Cell(100,10,"Personal Leadership Skills",0,0,'C');
    
    //legend properties
    $legendX=138;
    $legendY=155;
    
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
    $pdf->SetXY(35,230);
    $pdf->MultiCell(150,6,$fa);
 
    
    checking_size($logo,$pdf);
    
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
    
    
$pagecount = $pdf->setSourceFile('report_template/SDP3.pdf');
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
            $sql4 = "select * from wpa_part2_user_ans where code='$code' and solution='jrap_part3' and qno='$qno'";
            $res4 = mysqli_query($con,$sql4);
            $row4 = mysqli_fetch_array($res4);
            $ans = $row4['first_ans'];
            if($ans == 'yes')
            {
                if($i==3)
                {
                    $j==2;
                    $sec_ans = $row4['sec_ans'];
                    $p_score[$j] = $p_score[$j] + $sec_ans;
                    $q_n[$j] = $q_n[$j] + 1; 
                }
                else
                {
                    $sec_ans = $row4['sec_ans'];
                    $p_score[$i] = $p_score[$i] + $sec_ans;
                    $q_n[$i] = $q_n[$i] + 1; 
                }
                
            }

            
        }
    }
    
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
    
    $pdf->SetFontSize('9.5');
    $pdf->SetXY(145,68);
    $pdf->MultiCell(40, 6,$p_status[0]);
    
    $pdf->SetXY(145,84);
    $pdf->MultiCell(40, 6,$p_status[1]);
    
    $pdf->SetXY(145,98);
    $pdf->MultiCell(40, 6,$p_status[2]);
    
    
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
    // $pdf->Cell(100,10,"STUDY HABITS STRENGTH",0,0,'C');
    
    //legend properties
    $legendX=138;
    $legendY=155;
    
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
    $pdf->SetXY(35,232);
    $pdf->MultiCell(150,6,$fa);
    
    $pdf->AddPage();
    
    checking_size($logo,$pdf);
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
                $pdf->SetFont('Arial','',10);
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
                        $pdf->MultiCell(30, $height, $item[$i],0,'L',false);
                    }
                    else if($i==1)
                    {
                        $pdf->SetXY($x + (30) , $y);
                        $pdf->MultiCell(55, $height, $item[$i],0,'L',false); 
                    }
                    else
                    {
                        $pdf->SetXY($x + (89) , $y);
                        $pdf->MultiCell(90, $height, $item[$i],0,'L',false);  
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
    

//Added functionality JRAP interview guide - Sudhir - 25-oct-21    
/*
{
    //add first 2 pages
    $pagecount = $pdf->setSourceFile('report_template/JRAPInt_header.pdf');
    $tpl = $pdf->importPage(2);
    $pdf->AddPage();
    checking_size($logo,$pdf);
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    
    $pagecount = $pdf->setSourceFile('report_template/JRAPInt_header.pdf');
    $tpl = $pdf->importPage(3);
    $pdf->AddPage();
    checking_size($logo,$pdf);
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    $arr_int = array();
    //Get all interview content in an array
    {
 
        $sql_int = "SELECT * FROM cb_jrap_interview";
        $res_int = mysqli_query($con, $sql_int);
        $set_page_chr = 1000;
        $tot_question = 0;
        $cnt_temp=0;
        while($row_int = mysqli_fetch_array($res_int))
        {
            //array_push($arr_int, $row_int);
            
            
            $question = "Q-".$row_int[0].". ".$row_int['question'];
            //$purpose  = "\r\n\r\n"."Purpose or Intention of interviewer -";
            $purpose = $row_int['purpose'];
            
            if($row_int['expectations'] !='')
            {
                $purpose .= "\r\n".$row_int['expectations'];
            }
            
            //$observation = "Observations made by interviewer- ";
            $observation = $row_int['observations'];
            
            //$response = "Possible response(s) - ";
            $response = $row_int['model_answer_components'];
            $response .= "\r\n"."Example - ".$row_int['model_answer'];
            $arr_int[$cnt_temp]['question'] = $question;
            $arr_int[$cnt_temp]['purpose'] = $purpose;
            $arr_int[$cnt_temp]['observations'] = $observation;
            $arr_int[$cnt_temp]['response'] = $response;
            
            $arr_headings = array();
            $arr_headings['purpose'] = 'Purpose or Intetion of interviewer';
            $arr_headings['observations'] ='Observations made by Interviewer - ';
            $arr_headings['response'] = 'Possible response(s) - ';
            
            $col_head = array(12, 3, 46);
            $col_txt = array(0,0,0);
            $col_que = array(100,100,100);
           
            $cnt_temp +=1;
            $tot_question = $cnt_temp;
      
            
        }
        
        
        
        
  
    }
    
    
    //echo "<pre>";
    //print_r($arr_int);
    
    //print into pdf
    
    $pg_ctr=1;
    $txt_ln = 0;
    $txt_str = '';
    
    //add page
    {
        $pagecount = $pdf->setSourceFile('report_template/Border Page.pdf');
        $tpl = $pdf->importPage(1);
        $pdf->AddPage();
        checking_size($logo,$pdf);
        $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
        
        //settings
        $pdf->SetMargins(30,30,30);
        $pdf->SetY(31);
    }
    //$page_width = $pdf->GetPageWidth();
       
    for ($i=0;$i<$tot_question;$i++)
    {
 
        
        foreach($arr_int[$i] as $key=>$val)
        {
            
            //echo "Key-value pair :".$key." : ".$val."<br>";
            //adding a page
            {
                $txt_str .= $val;
                //echo $txt_str."<br>";
                $txt_len = strlen($txt_str);
                $bal_chars = $txt_len % $set_page_chr;
                $complete_pages = ($txt_len - $bal_chars) / $set_page_chr;
                

                //echo "Page No :".$y."<br>";
                if($complete_pages>$pg_ctr+1){
                    echo "Complete Pages :".$complete_pages."<br>";
                    $pagecount = $pdf->setSourceFile('report_template/Border Page.pdf');
                    $tpl = $pdf->importPage(1);
                    $pdf->AddPage();
                    checking_size($logo,$pdf);
                    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                    
                    //settings
                    $pdf->SetMargins(30,30,30);
                    $pdf->SetAutoPageBreak(1,2);
                    $pdf->SetY(31);
                    //$page_width = $pdf->GetPageWidth();
                   
                $pg_ctr = $complete_pages;
                    
                }
                
                
            }

             $pdf->SetX(31);
            if($key == 'question')
            {
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetTextColor($col_que[0],$col_que[1],$col_que[2]);
                $pdf->MultiCell($page_width,8 , $val, 0,'L', false);
                $pdf->Ln(2);
            }
            
            if($key == 'purpose')
            {
                
                //heading
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetTextColor($col_head[0],$col_head[1],$col_head[2]);
                $pdf->MultiCell($page_width,8 , $arr_headings['purpose'], 0,'L', false);
                
                
                //txt
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetTextColor($col_txt[0],$col_txt[1],$col_txt[2]);
                $pdf->MultiCell($page_width,8 , $val, 0,'L', false);
                $pdf->Ln(2);
            }
            
            if($key == 'observations')
            {
                //heading
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetTextColor($col_head[0],$col_head[1],$col_head[2]);
                $pdf->MultiCell($page_width,8 , $arr_headings['observations'], 0,'L', false);
                
                
                //txt
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetTextColor($col_txt[0],$col_txt[1],$col_txt[2]);
                $pdf->MultiCell($page_width,8 , $val, 0,'L', false);
                $pdf->Ln(2);
            }
            if($key == 'response')
            {
                //heading
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetTextColor($col_head[0],$col_head[1],$col_head[2]);
                $pdf->MultiCell($page_width,8 , $arr_headings['response'], 0,'L', false);
                
                
                //txt
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetTextColor($col_txt[0],$col_txt[1],$col_txt[2]);
                $pdf->MultiCell($page_width,8 , $val, 0,'L', false);
                $pdf->Ln(2);
            }
        }
        //print question
        
        
            
    }
    
}
*/



$pagecount = $pdf->setSourceFile('report_template/Counsellor Remarks.pdf');
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

$pdf->SetFontSize('9');
$pdf->SetXY(35,60);
$pdf->MultiCell(150,6,$c_remark);

//echo("Hi");


ob_end_clean();
$pdf->AliasNbPages();

$pdf->Output();
// $pdf2->Output();

ob_end_flush();



?>

  