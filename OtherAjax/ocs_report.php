<?php
ob_start();
$code = base64_decode($_GET['code']);
include('dbconn.php');

//uce_report_v1
//echo "uce_report_v1"."<br>";

// include composer packages
use setasign\Fpdi\Fpdi;
require_once('fpdf181/fpdf181/fpdf.php');
require_once('fpdi2/fpdi2/src/autoload.php');
require_once('uce_score_calculation.php');
// Create new Landscape PDF
$pdf = new FPDI();
$pagecount = $pdf->setSourceFile('report_template/UCE.pdf');


// Import the first page from the PDF and add to dynamic PDF
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

include('Second_Detail_Page.php');
// Reference the PDF you want to use (use relative path)





$pagecount = $pdf->setSourceFile('report_template/UCE.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
$sql_for_logo = "select * from user_code_list where code='$code'";
$res_for_logo = mysqli_query($con,$sql_for_logo);
$row_for_logo = mysqli_fetch_array($res_for_logo);
$reseller_id = $row_for_logo['reseller_id'];
//echo "reseller id".$reseller_id;

$class = $row_for_logo['cls'];
$sql_for_reseller_info = "select * from reseller_homepage where r_email='$reseller_id'";
$res_for_reseller_info = mysqli_query($con,$sql_for_reseller_info);
$row_for_reseller_info = mysqli_fetch_array($res_for_reseller_info);
$logo = $row_for_reseller_info['logo'];
$logo = 'http://users.respicite.com/'.$logo;
// $logo = '../uploads/default.jpg';

//echo $row_for_reseller_info['logo'];die();
function checking_size($logo,$pdf)
{
    //print_r($pdf);die();
    $size = getimagesize($logo);
    $wImg = $size[0];
    $hImg = $size[1];
    echo "before";
    if($wImg<=512 && $hImg<=512)
    {   echo "if";
        $pdf->SetXY(170, 8);
        $pdf->SetFont('arial');
        $pdf->Cell(0,20,$pdf->Image($logo,$pdf->GetX(), $pdf->GetY(),30), 0, 0,'R',false);
    }
    else
    {   echo "else";
        $pdf->SetXY(150, 10);
        $pdf->SetFont('arial');
        $pdf->Cell(0,20,$pdf->Image($logo,$pdf->GetX(), $pdf->GetY(),50), 0, 0,'R',false);   
    }
}

checking_size($logo,$pdf);

    // add signature

$tpl = $pdf->importPage(3);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]); 

$para = array();
$sql = "select DISTINCT sub_cat from uce_part1";
$res = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($res))
{
    array_push($para,$row['sub_cat']);
}
$count = count($para);
for($i=0;$i<$count;$i++)
{
    $score[$i]=0;
    $q_qty[$i]=0;
    $per[$i]=0;
}


/** VS **/
for($i=0;$i<$count;$i++)
{
    $cat = $para[$i];
    $sql3 = "select * from uce_part1 where sub_cat='$cat'";
    $res3 = mysqli_query($con,$sql3);
    while($row3 = mysqli_fetch_array($res3))
    {
        $qno = $row3['qno'];
        $sql4 = "select * from ppe_part1_test_details where code='$code' and solution='uce_part1_1' and qno='$qno'";
        $res4 = mysqli_query($con,$sql4);
        $row4 = mysqli_fetch_array($res4);
        $ans = $row4['ans'];
        if($ans=='1'){$temp_ans = 4;}
        else if($ans=='2'){$temp_ans = 3;}
        else if($ans=='3'){$temp_ans = 1;}
        else if($ans=='4'){$temp_ans = 0;}
        $score[$i] = $score[$i] + $temp_ans;
        $q_qty[$i] = $q_qty[$i] + 1; 
    }
}

for($i=0;$i<$count;$i++)
{
   $q_qty[$i] = $q_qty[$i] * 4; 
   $per[$i] = $score[$i]*100 / $q_qty[$i];
}

$chartX=27;
$chartY=150;
//dimension
$chartWidth=90;
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
    $para[0]=>['color'=>[24,203,238],'value'=>$per[0]],
    $para[1]=>['color'=>[172,13,26],'value'=>$per[1]],
    $para[2]=>['color'=>[247,253,4],'value'=>$per[2]]
     
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
        $chartBoxX+75,
        $yAxisPos-1.85,
        $chartBoxX,
        $yAxisPos-1.85     
         );  
   }
   else if($i==20)
   {
    $pdf->Line(
        $chartBoxX+75,
        $yAxisPos-1.85,
        $chartBoxX,
        $yAxisPos-1.85     
         );  
   }
   else if($i==60)
   {
    $pdf->Line(
        $chartBoxX+75,
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
// $pdf->Cell(100,10,"STUDY HABITS STRENGTH",0,0,'C');

//legend properties

//For Belief inferences 

if($per[2]<50)
{
    $sql_inf = "select * from inferences_table where CI='Low'";
    $res_inf = mysqli_query($con,$sql_inf);
    $row_inf = mysqli_fetch_array($res_inf);
    $belief_inf = $row_inf['Beliefs'];
}
else if($per[2]>=50 && $per[2]<=75)
{
    $sql_inf = "select * from inferences_table where CI='Medium'";
    $res_inf = mysqli_query($con,$sql_inf);
    $row_inf = mysqli_fetch_array($res_inf);
    $belief_inf = $row_inf['Beliefs'];
}
else if($per[2]>75)
{
    $sql_inf = "select * from inferences_table where CI='High'";
    $res_inf = mysqli_query($con,$sql_inf);
    $row_inf = mysqli_fetch_array($res_inf);
    $belief_inf = $row_inf['Beliefs'];
}

$pdf->SetFontSize('9');
$pdf->SetXY(78,233);
$pdf->MultiCell(120,6,$belief_inf,1);

//For Awareness inferences 
if($per[1]<50)
{
    $sql_inf = "select * from inferences_table where CI='Low'";
    $res_inf = mysqli_query($con,$sql_inf);
    $row_inf = mysqli_fetch_array($res_inf);
    $aw_inf = $row_inf['Awareness'];
}
else if($per[1]>=50 && $per[1]<=75)
{
    $sql_inf = "select * from inferences_table where CI='Medium'";
    $res_inf = mysqli_query($con,$sql_inf);
    $row_inf = mysqli_fetch_array($res_inf);
    $aw_inf = $row_inf['Awareness'];
}
else if($per[1]>75)
{
    $sql_inf = "select * from inferences_table where CI='High'";
    $res_inf = mysqli_query($con,$sql_inf);
    $row_inf = mysqli_fetch_array($res_inf);
    $aw_inf = $row_inf['Awareness'];
}
$pdf->SetFontSize('9');
$pdf->SetXY(78,245);
$pdf->MultiCell(120,6,$aw_inf,1);

//For Information inferences 

if($per[0]<50)
{
    $sql_inf = "select * from inferences_table where CI='Low'";
    $res_inf = mysqli_query($con,$sql_inf);
    $row_inf = mysqli_fetch_array($res_inf);
    $in_inf = $row_inf['Information'];
}
else if($per[0]>=50 && $per[0]<=75)
{
    $sql_inf = "select * from inferences_table where CI='Medium'";
    $res_inf = mysqli_query($con,$sql_inf);
    $row_inf = mysqli_fetch_array($res_inf);
    $in_inf = $row_inf['Information'];
}
else if($per[0]>75)
{
    $sql_inf = "select * from inferences_table where CI='High'";
    $res_inf = mysqli_query($con,$sql_inf);
    $row_inf = mysqli_fetch_array($res_inf);
    $in_inf = $row_inf['Information'];
}
$pdf->SetFontSize('9');
$pdf->SetXY(78,265);
$pdf->MultiCell(120,6,$in_inf,1);

$tpl = $pdf->importPage(4);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]); 

//Uce part1_2 - Interests
$p1_2_para = array();
$p1_2_sql = "select DISTINCT category from uce_part1_2";
$p1_2_res = mysqli_query($con,$p1_2_sql);
while($p1_2_row = mysqli_fetch_array($p1_2_res))
{
    array_push($p1_2_para,$p1_2_row['category']);
}
$p1_2_count = count($p1_2_para);
for($i=0;$i<$p1_2_count;$i++)
{
    $p1_2_score[$i]=0;
    $p1_2_q_qty[$i]=0;
    $p1_2_per[$i]=0;

}
for($i=0;$i<$p1_2_count;$i++)
{
    $p1_2_cat = $p1_2_para[$i];
    $p1_2_sql3 = "select * from uce_part1_2 where category='$p1_2_cat'";
    $p1_2_res3 = mysqli_query($con,$p1_2_sql3);
    while($p1_2_row3 = mysqli_fetch_array($p1_2_res3))
    {
        $qno = $p1_2_row3['qno'];
        $p1_2_sql4 = "select * from ppe_part1_test_details where code='$code' and solution='uce_part1_2' and qno='$qno'";
        $p1_2_res4 = mysqli_query($con,$p1_2_sql4);
        $p1_2_row4 = mysqli_fetch_array($p1_2_res4);
        $ans = $p1_2_row4['ans'];
        if($ans=='1')
        {
            $temp_ans = 0;
        }
        else if($ans=='2')
        {
            $temp_ans = 1;
        }
        else if($ans=='3')
        {
            $temp_ans = 3;
        }
        else if($ans=='4')
        {
            $temp_ans = 4;
        }
        $p1_2_score[$i] = $p1_2_score[$i] + $temp_ans;
        $p1_2_q_qty[$i] = $p1_2_q_qty[$i] + 1; 
    }
}

for($i=0;$i<$p1_2_count;$i++)
{
   $p1_2_q_qty[$i] = $p1_2_q_qty[$i] * 4; 
   $p1_2_per[$i] = round($p1_2_score[$i]*100 / $p1_2_q_qty[$i],0);
}


//print interest scores
//echo"Interest Scores <pre>";
//print_r($p1_2_per);

$chartX=27;
$chartY=194;
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
    $p1_2_para[0]=>['color'=>[24,203,238],'value'=>$p1_2_per[0]],
    $p1_2_para[1]=>['color'=>[172,13,26],'value'=>$p1_2_per[1]],
    $p1_2_para[2]=>['color'=>[247,253,4],'value'=>$p1_2_per[2]],
    $p1_2_para[3]=>['color'=>[10,25,100],'value'=>$p1_2_per[3]],
    $p1_2_para[4]=>['color'=>[0,25,10],'value'=>$p1_2_per[4]],
    $p1_2_para[5]=>['color'=>[24,0,238],'value'=>$p1_2_per[5]]
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


$get_score1 = "select * from top_value_db where code='$code' and solution='uce_part1_2'";
$res_score = mysqli_query($con,$get_score1);
$num = mysqli_num_rows($res_score);
if($num==0)
{
    for($i=0;$i<$p1_2_count;$i++)
    {
        $sc = $p1_2_per[$i];   
        $cat = $p1_2_para[$i]; 
        echo "Interest Category :".$cat." , Value :".$sc."<br>";
        $sql_insert = "insert into top_value_db(solution,code,category,per) values('uce_part1_2','$code','$cat','$sc')";
        mysqli_query($con,$sql_insert);
    }
}
$pos = 204;
$s = array();
$cat_name=array();
$cat_name['R'] = "Realistic";
$cat_name['I'] = "Investigative";
$cat_name['A'] = "Artistic";
$cat_name['S'] = "Social";
$cat_name['E'] = "Enterprising";
$cat_name['C'] = "Conventional";

$get_score = "select * from top_value_db where code='$code' order by per DESC limit 3";
$res_score = mysqli_query($con,$get_score);
while($row_score = mysqli_fetch_array($res_score))
{
    
    $x=$row_score['category']."(".$cat_name[$row_score['category']].")";
    array_push($s,$row_score['category']);
    echo "Top Interest :".$x."<br>";
    $pdf->SetXY(135,$pos);
    $pdf->SetFont('arial','B',11);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(0,20,$x, 0, 1,'C',false);
    $pos = $pos + 12.5;
}

$tpl = $pdf->importPage(5);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);


//Values
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
//$get_score1 = "select * from temp_order_score where code='$code'";
//$res_score = mysqli_query($con,$get_score1);
//$num = mysqli_num_rows($res_score);



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

/*added by Sudhir */
else

{
    for($i=1;$i<=21;$i++)
    {
        $sc = $score[$i];
        //echo "<br>";
        
        $sql_update = "update temp_order_score SET score = '$sc' where q_id='$i' AND code ='$code'";
       
        mysqli_query($con,$sql_update);
    }
    
    
}

/* End of added by Sudhir */
// Your Top Needs score.
$i = 1;
$sum_top_5_score = 0;
$pos = 220;

/*Heading */
$pdf->SetXY(35,210);
$pdf->SetFont('arial','B',16);
$pdf->SetTextColor(24,156,243);
$pdf->MultiCell(100,5,"Your Top Needs");
/* !Heading */
$pdf->SetFont('arial','B',12);
$get_score = "select * from temp_order_score where code='$code' order by score DESC limit 5";
$res_score = mysqli_query($con,$get_score);
$counter = 1;
while($row_score = mysqli_fetch_array($res_score))
{
    $q_id = $row_score['q_id'];
    
    //printing on pdf table
    $get_record_qno = "select * from wls_part2_2_detail where qno='$q_id'";
    $res_record_qno = mysqli_query($con,$get_record_qno);
    $row_record_qno = mysqli_fetch_array($res_record_qno);
    $pdf->SetXY(35,$pos);
    $pdf->SetFont('arial');
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(150,5,$counter.'. '.$row_record_qno['sub_category'].' - '.$row_record_qno['item']);
    $y = $pdf->GetY();
    $pos = $y + 2;
    $counter = $counter + 1 ;
}

$onet = array();
$sql = "select DISTINCT onet from wls_part2_2_detail";
$res = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($res))
{
    array_push($onet,$row['onet']);
}
$o_count = count($onet);
for($i=0;$i<$o_count;$i++)
{
    $qqty[$i] = 0;
    $o_score[$i] = 0; 
    $o_per[$i] = 0;
    $ot = $onet[$i];
    $f_score[$i] = 0;
    $o_sql = "select * from wls_part2_2_detail where onet='$ot'";
    $o_res = mysqli_query($con,$o_sql);
    while($o_row = mysqli_fetch_array($o_res))
    {
        $qno = $o_row['qno'];
        $get_score = "select * from temp_order_score where code='$code' and q_id='$qno'";
        $res_score = mysqli_query($con,$get_score);
        $row_score = mysqli_fetch_array($res_score);
        $o_score[$i] = $o_score[$i] + $row_score['score'];
        $qqty[$i] = $qqty[$i] + 1;
    }
    $o_score[$i] = $o_score[$i] / $qqty[$i];
    $f_score[$i] = $o_score[$i]*100/30;
    
}



$tpl = $pdf->importPage(6);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]); 

//personalty score.
$E = 0; 
$I = 0;
$S = 0;
$N = 0;
$T = 0;
$F = 0;
$P = 0;
$J = 0;

    $sql_p4 = "select * from ppe_part1_test_details where solution='uce_part1_4_1' and  code='$code' or solution='uce_part1_4_2' and  code='$code'";
    $res_p4 = mysqli_query($con,$sql_p4);
    while($row_p4 = mysqli_fetch_array($res_p4))
    {
        $qn = $row_p4['qno'];
        $ans = $row_p4['ans'];
        $solution = $row_p4['solution'];
        if($solution=='uce_part1_4_1')
        {
            $part = 'Part1';
        }
        else
        {
            $part = 'Part2';
        }

        $sql_scr = "select * from uce_part1_4_scoring where Part='$part' and Q='$qn' and O='$ans'";
        $res_scr = mysqli_query($con,$sql_scr);
        $row_scr = mysqli_fetch_array($res_scr);
        $type = $row_scr['Type'];
        if($type=='E')
        {
            $E=$E+$row_scr['Points'];
        }
        else if($type=='I')
        {
            $I=$I+$row_scr['Points'];
        }
        else if($type=='S')
        {
            $S=$S+$row_scr['Points'];
        }
        else if($type=='N')
        {
            $N=$N+$row_scr['Points'];
        }
        else if($type=='T')
        {
            $T=$T+$row_scr['Points'];
        }
        else if($type=='F')
        {
            $F=$F+$row_scr['Points'];
        }
        else if($type=='P')
        {
            $P=$P+$row_scr['Points'];
        }
        else if($type=='J')
        {
            $J=$J+$row_scr['Points'];
        }
    }

    $val_f = array();
    if($I>=$E)
    {
        $val1 = $I;
        $value1 = 'I';
        array_push($val_f,'I - Introversion');
    }
    else
    {
        $val1 = $E;
        $value1 = 'E';
        array_push($val_f,'E - Extraversion');
    }

    if($N>=$S)
    {
        $val2 = $N;
        $value2 = 'N';
        array_push($val_f,'N - Intuiting');
    }
    else
    {
        $val2 = $S;
        $value2 = 'S';
        array_push($val_f,'S - Sensing');
    }

    if($T>=$F)
    {
        $val3 = $T;
        $value3 = 'T';
        array_push($val_f,'T - Thinking');
    }
    else
    {
        $val3 = $F;
        $value3 = 'F';
        array_push($val_f,'F - Feeling');
    }
    
    if($P>=$J)
    {
        $val4 = $P;
        $value4 = 'P';
        array_push($val_f,'P - Perceiving');
    }
    else
    {
        $val4 = $J;
        $value4 = 'J';
        array_push($val_f,'J - Judging');
    }

        $pos = 188;
        foreach($val_f as $vl)
        {
            $pdf->SetXY(145,$pos);
            $pdf->MultiCell(150,6,$vl);  
            $pos = $pos + 10;
        }
       
        $val1 = $val1/($I + $E);
        $val2 = $val2/($N + $S); 
        $val3 = $val3/ ($T + $F);
        $val4 = $val4/($P + $J); 

        $val1_per = $val1 * 100;
      
        $val2_per = $val2 * 100;
     
        $val3_per = $val3 * 100;
        
        $val4_per = $val4 * 100;
        
        $infre = array();
        if($val1_per<=75)
        {
            $val1_status = "Medium";
           
        }
        else
        {
            $val1_status = "High"; 
        }

        if($val2_per<=75)
        {
            $val2_status = "Medium";
           
        }
        else
        {
            $val2_status = "High"; 
        }

        if($val3_per<=75)
        {
            $val3_status = "Medium";
           
        }
        else
        {
            $val3_status = "High"; 
        }

        if($val4_per<=75)
        {
            $val4_status = "Medium";
           
        }
        else
        {
            $val4_status = "High"; 
        }
        $chartX=35;
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
            $value1 =>['color'=>[24,203,238],'value'=>$val1_per],
            $value2 =>['color'=>[172,13,26],'value'=>$val2_per],
            $value3 =>['color'=>[247,253,4],'value'=>$val3_per],
            $value4 =>['color'=>[10,25,100],'value'=>$val4_per]
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

        $pdf->SetFontSize('14','B');
        $pdf->SetFont('arial');
        
        $pdf->SetXY(40,246);
        $pdf->Cell(0,20,$value1, 0, 0,'L',false);     

        $pdf->SetXY(80,246);
        $pdf->Cell(0,20,$value2, 0, 0,'L',false);   

        $pdf->SetXY(118,246);
        $pdf->Cell(0,20,$value3, 0, 0,'L',false);   

        $pdf->SetXY(159,246);
        $pdf->Cell(0,20,$value4, 0, 0,'L',false);   
        $pdf->SetFontSize('27','B');
        $pdf->SetTextColor(11,170,54);
        $pdf->SetXY(142,158);
        $pdf->Cell(0,20,$value1.$value2.$value3.$value4, 0, 0,'L',false);   
        $pdf->SetFontSize('14','B');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetXY(40,252);
        $pdf->Cell(1,20,$val1_status, 0, 0,'C',false);     

        $pdf->SetXY(80,252);
        $pdf->Cell(1,20,$val2_status, 0, 0,'C',false);   

        $pdf->SetXY(118,252);
        $pdf->Cell(1,20,$val3_status, 0, 0,'C',false);   

        $pdf->SetXY(159,252);
        $pdf->Cell(1,20,$val4_status, 0, 0,'C',false); 

        $sql_inf1 = "select * from uce_part1_4_inference where vlu='$value1'";
        $res_inf1 = mysqli_query($con,$sql_inf1);
        $row_inf1 = mysqli_fetch_array($res_inf1);
        $ref = $row_inf1['Statement_1'].", ".$row_inf1['Statement_2'];
        array_push($infre,array('E-I Dimension',$ref));
        
        $sql_inf2 = "select * from uce_part1_4_inference where vlu='$value2'";
        $res_inf2 = mysqli_query($con,$sql_inf2);
        $row_inf2 = mysqli_fetch_array($res_inf2);
        $ref = $row_inf2['Statement_1'].", ".$row_inf2['Statement_2'];
        array_push($infre,array('S-N Dimension',$ref));
           
        $sql_inf3 = "select * from uce_part1_4_inference where vlu='$value3'";
        $res_inf3 = mysqli_query($con,$sql_inf3);
        $row_inf3 = mysqli_fetch_array($res_inf3);
        $ref = $row_inf3['Statement_1'].", ".$row_inf3['Statement_2'];
        array_push($infre,array('T-F Dimension',$ref));

        $sql_inf4 = "select * from uce_part1_4_inference where vlu='$value4'";
        $res_inf4 = mysqli_query($con,$sql_inf4);
        $row_inf4 = mysqli_fetch_array($res_inf4);
        $ref = $row_inf4['Statement_1'].", ".$row_inf4['Statement_2'];
        array_push($infre,array('J-P Dimension',$ref));
        


    $tpl = $pdf->importPage(7);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]); 
    $pdf->SetFontSize('11','B');
    $pos = 50;
    foreach($infre as $inf)
    {
       
        $pdf->SetXY(30,$pos+5);
        $pdf->MultiCell(150,6,$inf[0].' - '.$inf[1],0,'L',false); 
        $pos = $pdf->GetY() +2;
    }

    $tpl = $pdf->importPage(10);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]); 

//learning inpreference.
    $v = 0;
    $a = 0;
    $r = 0;
    $k = 0;
    
    $sql = "select * from ppe_part1_test_details where code='$code' and solution='uce_part1_5'";
    $res = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($res))
    {
        //echo $row['qno'].'=>'.$row['ans']."<br>";
        $x = explode(',',$row['ans']);
        $ln = count($x);
        for($i=0;$i<$ln;$i++)
        {
            $qno = $row['qno'];
            $option = $x[$i];
            $sql2 = "select * from uce_part1_5_scoring where qno='$qno' and option='$option'";
            $res2 = mysqli_query($con,$sql2);
            $row2 = mysqli_fetch_array($res2);
            $pcode = $row2['code'];
            if($pcode=='V')
            {
                $v = $v +1;
            }
            else if($pcode=='A')
            {
                $a = $a +1;
            }
            else if($pcode=='R')
            {
                $r = $r +1;
            }
            else if($pcode=='K')
            {
                $k= $k +1;
            }
        }
    }

    
    echo $v_per = $v/16*100;
    echo $a_per = $a/16*100;
    echo $r_per = $r/16*100;
    echo $k_per = $k/16*100;
    

    $chartX=23;
    $chartY=228.5;
    //dimension
    $chartWidth=95;
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
    
    $data=Array(
        'V' =>['color'=>[24,203,238],'value'=>$v_per],
        'A' =>['color'=>[172,13,26],'value'=>$a_per],
        'R' =>['color'=>[247,253,4],'value'=>$r_per],
        'K' =>['color'=>[10,25,100],'value'=>$k_per]
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
            $chartBoxX+80,
            $yAxisPos-1.85,
            $chartBoxX,
            $yAxisPos-1.85     
                );  
        }
        else if($i==20)
        {
        $pdf->Line(
            $chartBoxX+80,
            $yAxisPos-1.85,
            $chartBoxX,
            $yAxisPos-1.85     
                );  
        }
        else if($i==60)
        {
        $pdf->Line(
            $chartBoxX+80,
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


    
    
    //UCE Part 2 - Aptitude Part 1

    //2.1
    $score_2_1 = 0;
    
    $nm_1 = 0;
    
    $sql_2_1_result = "select * from ppe_part1_test_details where solution='uce_part2' and code='$code'";
    $res_2_1_result = mysqli_query($con,$sql_2_1_result);
    while($row_2_1_result = mysqli_fetch_array($res_2_1_result))
    {
        $qno = $row_2_1_result['qno'];
        $ans = $row_2_1_result['ans'];
        $sql_2_1 = "select * from uce_part2 where part='part1' and qno='$qno'";
        $res_2_1 = mysqli_query($con,$sql_2_1);
        $row_2_1 = mysqli_fetch_array($res_2_1);
        $r_ans = $row_2_1['r_ans'];
        if($ans==$r_ans)
        {
            
            $score_2_1 = $score_2_1 + 1;
        }
        $nm_1 = $nm_1 + 1;
    }

    //echo 'AR Score: '.$score_2_1."<br>";
    //part 2.2
    $score_2_2 = 0;
    $nm_2 = 0;
    $sql_2_2_result = "select * from ppe_part1_test_details where solution='uce_part2_2' and code='$code'";
    $res_2_2_result = mysqli_query($con,$sql_2_2_result);
    while($row_2_2_result = mysqli_fetch_array($res_2_2_result))
    {
        $qno = $row_2_2_result['qno'];
        $ans = $row_2_2_result['ans'];
        $sql_2_2 = "select * from uce_part2_2 where qno='$qno'";
        $res_2_2 = mysqli_query($con,$sql_2_2);
        $row_2_2 = mysqli_fetch_array($res_2_2);
        $r_ans = $row_2_2['r_ans'];
        if($ans==$r_ans)
        {
            $score_2_2 = $score_2_2 + 1;
        }
        $nm_2 = $nm_2 + 1;
    }
    
    //echo 'VR Score :'.$score_2_2."<br>";

    // part 2.3
    $score_2_3 = 0;
    $nm_3 = 0;
    $sql_2_3_result = "select * from ppe_part1_test_details where solution='uce_part2_3' and code='$code'";
    $res_2_3_result = mysqli_query($con,$sql_2_3_result);
    while($row_2_3_result = mysqli_fetch_array($res_2_3_result))
    {
        $qno = $row_2_3_result['qno'];
        $ans = $row_2_3_result['ans'];
        $sql_2_3 = "select * from uce_part2 where part='part3' and qno='$qno'";
        $res_2_3 = mysqli_query($con,$sql_2_3);
        $row_2_3 = mysqli_fetch_array($res_2_3);
        $r_ans = $row_2_3['r_ans'];
        if($ans==$r_ans)
        {
            $score_2_3 = $score_2_3 + 1;
        }
        $nm_3 = $nm_3 + 1;
    }
    
    echo 'SA Score :'.$score_2_3."<br>";

    // part 2.4
    $score_2_4 = 0;
    $nm_4 = 0;
    $sql_2_4_result = "select * from ppe_part1_test_details where solution='uce_part2_4' and code='$code'";
    $res_2_4_result = mysqli_query($con,$sql_2_4_result);
    while($row_2_4_result = mysqli_fetch_array($res_2_4_result))
    {
        $qno = $row_2_4_result['qno'];
        $ans = $row_2_4_result['ans'];
        $sql_2_4 = "select * from uce_part2 where part='part4' and qno='$qno'";
        $res_2_4 = mysqli_query($con,$sql_2_4);
        $row_2_4 = mysqli_fetch_array($res_2_4);
        $r_ans = $row_2_4['r_ans'];
        if($ans==$r_ans)
        {
            $score_2_4 = $score_2_4 + 1;
        }
        else
        {
            $score_2_4 = $score_2_4 - 0.25;
        }
        $nm_4 = $nm_4 + 1;
    }
    
    echo 'COM Score :'.$score_2_4."<br>";
    
    // part 2.5
    $score_2_5 = 0;
    $nm_5 = 0;
    $sql_2_5_result = "select * from ppe_part1_test_details where solution='uce_part2_5' and code='$code'";
    $res_2_5_result = mysqli_query($con,$sql_2_5_result);
    while($row_2_5_result = mysqli_fetch_array($res_2_5_result))
    {
        $qno = $row_2_5_result['qno'];
        $ans = $row_2_5_result['ans'];
        $sql_2_5 = "select * from uce_part5 where qno='$qno'";
        $res_2_5 = mysqli_query($con,$sql_2_5);
        $row_2_5 = mysqli_fetch_array($res_2_5);
        $r_ans = $row_2_5['r_ans'];
        if($ans==$r_ans)
        {
            $score_2_5 = $score_2_5 + 1;
        }
        else
        {
            $score_2_5 = $score_2_5 - 1;
        }
        $nm_5 = $nm_5 + 1;
    }
    
    echo 'NC Score :'.$score_2_5."<br>";
    

    // part 2.6
    $score_2_6 = 0;
    $nm_6 = 0;
    $sql_2_6_result = "select * from ppe_part1_test_details where solution='uce_part2_6' and code='$code'";
    $res_2_6_result = mysqli_query($con,$sql_2_6_result);
    while($row_2_6_result = mysqli_fetch_array($res_2_6_result))
    {
        $qno = $row_2_6_result['qno'];
        $ans = $row_2_6_result['ans'];
        $sql_2_6 = "select * from uce_part2 where part='part6' and qno='$qno'";
        $res_2_6 = mysqli_query($con,$sql_2_6);
        $row_2_6 = mysqli_fetch_array($res_2_6);
        $r_ans = $row_2_6['r_ans'];
        if($ans==$r_ans)
        {
            $score_2_6 = $score_2_6 + 1;
        }
        else
        {
            $score_2_6 = $score_2_6 - 0.3333;
        }
        $nm_6 = $nm_6 + 1;
    }
    
    echo 'OM Score :'.$score_2_6."<br>";
    $pagecount = $pdf->setSourceFile('report_template/UCE_2_7.pdf');

    // Import the first page from the PDF and add to dynamic PDF
    $tpl = $pdf->importPage(1);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
   
    
  //ar
 
  $apt_value = array();
  for($i=1;$i<=6;$i++)
  {
    
    if($i==1)
    {
        $param = 'AR';
        $s_type = $score_2_1;
    }
    else if($i==2)
    {
        $param = 'VR';
        $s_type = $score_2_2;
    }
    else if($i==3)
    {
        $param = 'SA';
        $s_type = $score_2_3;
    }
    else if($i==4)
    {
        $param = 'COM';
        $s_type = $score_2_4;
    }
    else if($i==5)
    {
        $param = 'NC';
        $s_type = $score_2_5;
    }
    else if($i==6)
    {
        $param = 'OM';
        $s_type = $score_2_6;
    }
    
    echo "Currently Working Here"."<br>";
    echo "Param :".$param."<br>";
    echo "Score :".$s_type."<br>";

    $sql = "select * from uce_apt_calculation where param = '$param' and class = '$class' and score > '$s_type' limit 1";
    echo "Query :".$sql."<br>";
    $res = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($res);
   
    $high_score = $row['score'];
   
   
    
    echo "High Score :".$high_score."<br>";
        
    $high_l = $row['fs'];
    
    if($high_l>0){
        $low_l = $high_l - 1;
    } else {
        $low_l = $high_l;
    }
   
    $sql = "select * from uce_apt_calculation where param='$param' and class='$class' and fs='$low_l' limit 1";
    $res = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($res);

    //$low_l = $row['fs'];
    
    $low_score = $row['score'];
    echo "Low Score :".$low_score."<br>";

    //$low_score;
    $f_score = $high_score - $low_score; 
    
    $y = $s_type - $low_score;
    echo "s_type = ".$s_type."<br>";
    if ($s_type <=0){
        $per[$i] =0;
        $z = 0;
        array_push($apt_value,$z);
    }
    else 
    {
        if ($s_type == $low_score || $s_type == $high_score)
        {
            echo "This condition is true <br>";
            //original before 17 Jan 2022
           /*  $z = $s_type;
            array_push($apt_value,$z);
            $per[$i] = $z/7*100; */
            
            
            //changes on 17 Jan 2022
            $f_score = $high_score - $low_score;
            echo "Score Range :".$f_score;
            $y = $s_type - $low_score;
            $delta = $y/$f_score;

            $z = $delta + $low_l;
            array_push($apt_value,$z);
            $per[$i] = $z/7*100;
            //End of changes on 17 Jan 2022
            // echo "per = ".$per[$i]."<br>";
        } 
       
        else 
        {
            $f_score = $high_score - $low_score;
            echo "Score Range :".$f_score;
            $y = $s_type - $low_score;
            $delta = $y/$f_score;

            $z = $delta + $low_l;
            array_push($apt_value,$z);
            $per[$i] = $z/7*100;
        }
            
    }
    
        echo "Type :".$param."<br>";

      
  }
 
  /* echo "<pre>";
  print_r($apt_value); */
//   die();

$pt = value_level_mapper($per[1]);
$cols = explode(",", $pt['color']);
$pdf->SetFillColor($cols[0],$cols[1],$cols[2]);
$pdf->SetFontSize('12');
$pdf->SetXY(109.5,49);
$pdf->MultiCell(26.5,17,$pt['level'],0,'L',true);
    

$pt = value_level_mapper($per[2]);
$cols = explode(",", $pt['color']);
$pdf->SetFillColor($cols[0],$cols[1],$cols[2]);
$pdf->SetFontSize('12');
$pdf->SetXY(109.5,67);
$pdf->MultiCell(26.5,13,$pt['level'],0,1,true);
    
    
$pt = value_level_mapper($per[3]);
$SA_Level = $pt['level'];

echo "<br>SA Level : $SA_Level<br>";
// die();
$cols = explode(",", $pt['color']);
$pdf->SetFillColor($cols[0],$cols[1],$cols[2]);
$pdf->SetFontSize('12');
$pdf->SetXY(109.5,80);
$pdf->MultiCell(26.5,11,$pt['level'],0,1,true);
    
    
$pt = value_level_mapper($per[4]);
$cols = explode(",", $pt['color']);
$pdf->SetFillColor($cols[0],$cols[1],$cols[2]);
$pdf->SetFontSize('12');
$pdf->SetXY(109.5,91);
$pdf->MultiCell(26.5,10,$pt['level'],0,1,true);
    
        
$pt = value_level_mapper($per[5]);
$cols = explode(",", $pt['color']);
$pdf->SetFillColor($cols[0],$cols[1],$cols[2]);
$pdf->SetFontSize('12');
$pdf->SetXY(109.5,101);
$pdf->MultiCell(26.5,18,$pt['level'],0,1,true);
    
        
$pt = value_level_mapper($per[6]);
$cols = explode(",", $pt['color']);
$pdf->SetFillColor($cols[0],$cols[1],$cols[2]);
$pdf->SetFontSize('12');
$pdf->SetXY(109.5,120);
$pdf->MultiCell(26.5,12,$pt['level'],0,1,true);
    
    
$per_2_7 = uce_part3_score_calculation($code,$con);

$pt = value_level_mapper($per_2_7[0]);
$cols = explode(",", $pt['color']);
$pdf->SetFillColor($cols[0],$cols[1],$cols[2]);
$pdf->SetFontSize('14');
$pdf->SetXY(137,182);
$pdf->MultiCell(55,21,$pt['level'],0,1,'C',true);

$pt = value_level_mapper($per_2_7[1]);
$cols = explode(",", $pt['color']);
$pdf->SetFillColor($cols[0],$cols[1],$cols[2]);
$pdf->SetFontSize('14');
$pdf->SetXY(137,204);
$pdf->MultiCell(55,19,$pt['level'],0,1,'C',true);

$pt = value_level_mapper($per_2_7[2]);
$cols = explode(",", $pt['color']);
$pdf->SetFillColor($cols[0],$cols[1],$cols[2]);
$pdf->SetFontSize('14');
$pdf->SetXY(137,224);
$pdf->MultiCell(55,28,$pt['level'],0,1,'C',true);



// Interest Mapper

    
    echo $code."<br>";
    // echo "Candidate Interests :<pre>";
    // print_r($s);
    $sql = "select * from career_int_latest";
 
    $GLOBALS['sa_override']=[];
    
    $res = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($res))
    {
        $cd = $row['profession_name'];
       // echo  "Profession :".$cd."J1 ".$row['J1']." J2 ".$row['J2']." J3 ".$row['J3']."<br>";
        $match = interest_mapper_uce($con,$cd,$s[0],$s[1],$s[2], $row['J1'],$row['J2'],$row['J3'],$code);
        $sql_update = "update career_sui_latest set inte='$match' where profession_name='$cd' and code='$code'";
        mysqli_query($con,$sql_update);


       
        
        $x='';
        $sql_query = "SELECT mandatory_apt FROM `career_apt_latest_2` WHERE profession_name = '$cd'";
        $mandatory_apt_sql = mysqli_query($con,$sql_query);
        
        while($data = mysqli_fetch_array($mandatory_apt_sql)) {
            $x  = $data['mandatory_apt'];
        };

        
        //Sudhir - 29-06-22
        if ($x=='SA')
        {
            $GLOBALS['sa_override'][$cd]['int'] = $match;
            
        }

        
    }
    
    
        function interest_mapper_uce($con,$cd,$S1,$S2,$S3,$J1,$J2,$J3,$code)
    {
        $match_array = array("VL", "L", "M", "MH", "H3", "H2", "H1", "VH");
        
        // echo 'j1'.$J1."<br>";
        // echo 'j2'.$J2."<br>";
        // echo 'j3'.$J3."<br>";
        // echo 'S1'.$S1."<br>";
        // echo 'S2'.$S2."<br>";
        // echo 'S3'.$S3."<br>";
       $cnd = array();
       $sql = "select * from career_int_map";
       $res = mysqli_query($con,$sql);
       while($row = mysqli_fetch_array($res))
       {
           array_push($cnd,$row);
       }
       $cnd_count = count($cnd);
       
       //J1 mapping
       $str1='';
       $str2='';
       $str3='';
       $str4='';
       if ($J1=="NA"){$str1="J1=NA";} else
       if ($J1==$S1){$str1="J1=S1";} else
       if ($J1==$S2){$str1="J1=S2";} else
       if ($J1==$S3){$str1="J1=S3";} else
                    {$str1="J1=X";}
                    
        if ($J2=="NA"){$str2="J2=NA";} else
        if ($J2==$S1){$str2="J2=S1";} else
        if ($J2==$S2){$str2="J2=S2";} else
        if ($J2==$S3){$str2="J2=S3";} else
                    {$str2="J2=X";}
                    
        if ($J3=="NA"){$str3="J3=NA";} else
        if ($J3==$S1){$str3="J3=S1";} else
        if ($J3==$S2){$str3="J3=S2";} else
        if ($J3==$S3){$str3="J3=S3";} else
                    {$str3="J3=X";}
        $str4 = $str1.$str2.$str3; 
        $match = '';
        for($i=0;$i<$cnd_count;$i++){
            if($str4==$cnd[$i]['cndt'])
            {
                $match=$cnd[$i]['mtch'];
                break;
            }
            
        }
        
        if($match==''){
            $match ="VL";
        }
       

      // echo $match;
      // echo "<br>";
        return $match;
    }
    
    
    function interest_mapper_old($cd,$S1,$S2,$S3,$J1,$J2,$J3,$code)
    {
        $match_array = array("VL", "L", "M", "MH", "H3", "H2", "H1", "VH");
        
       // echo $S1."<br>";
       
       // echo $J1."<br>";;
        $available = 0;
        if ($J1 == $S1 || $J1 == $S2 || $J1 == $S3){
            $availble = 1;
        } else
        {
            $J1 ="X";
        }
        if ($J2 == $S1 || $J2 == $S2 || $J2 == $S3){
            $available = 1;
        } else 
        {
            
            $J2 = "X";
        }
        if ($J3 == $S1 || $J3 == $S2 || $J3 == $S3)
        {
            $available = 1;
        } else {
            $J3 = "X";
        }
 
        $match="";

        if ($J1 == $S1)
        {
            if ($J2 == $S2)
            {
                if ($J3 == $S3){
                 $match = $match_array[7];
                } else 
                if ($J3 == "NA") {
                    $match = $match_array[4];
                } else 
                {
                    $match = $match_array[3]; 
                }

            } 
            else if ($J2 == $S3)
            {
                if ($J3 == $S2){
                    $match = $match_array[7];
                   } else 
                   if ($J3 == "NA") {
                       $match = $match_array[7];
                   } else 
                   {
                       $match = $match_array[6]; 
                   }
            } 
            else if($J2 == "NA")
            {
                $match = $match_array[6];    
            } 
            else 
            {
                if ($J3 == $S2){
                    $match = $match_array[3];
                   } else 
                   if ($J3 == $S3) {
                       $match = $match_array[3];
                   } 
                   else 
                   if ($J3 == "NA") {
                       $match = $match_array[4];
                   } else  
                   {
                       $match = $match_array[2]; 
                   }  
            }
        } 
        else if ($J1 == $S2)
        {
            if ($J2 == $S1)
            {
                if ($J3 == $S3){
                 $match = $match_array[7];
                } else 
                if ($J3 == "NA") {  
                    $match = $match_array[3];
                } else 
                {
                    $match = $match_array[3]; 
                }

            } 
            else if ($J2 == $S3)
            {
                if ($J3 == $S1)
                {
                    $match = $match_array[5];
                } 
                else if ($J3 == "NA") 
                {
                       $match = $match_array[2];
                } 
                else 
                {
                       $match = $match_array[2]; 
                }
            } 
            else if($J2 == "NA")
            {
                $match = $match_array[1];    
            } 
            else 
            {
                if ($J3 == $S1){
                    $match = $match_array[2];
                   } else 
                   if ($J3 == $S2) {
                       $match = $match_array[2];
                   } 
                   else 
                   if ($J3 == "NA") {
                       $match = $match_array[2];
                   } else  
                   {
                       $match = $match_array[1]; 
                   }  
            }
        } 
        else if($J1 == $S3){
            if ($J2 == $S1)
            {
                if ($J3 == $S2){
                 $match = $match_array[5];
                } else 
                if ($J3 == "NA") {  
                    $match = $match_array[2];
                } else 
                {
                    $match = $match_array[2]; 
                }

            } 
            else if ($J2 == $S2)
            {
                if ($J3 == $S1)
                {
                    $match = $match_array[7];
                } 
                else if ($J3 == "NA") 
                {
                       $match = $match_array[2];
                } 
                else 
                {
                       $match = $match_array[2]; 
                }
            } 
            else if($J2 == "NA")
            {
                $match = $match_array[1];    
            } 
            else 
            {
                if ($J3 == $S1){
                    $match = $match_array[2];
                   } else 
                   if ($J3 == $S2) {
                       $match = $match_array[2];
                   } 
                   else 
                   if ($J3 == "NA") {
                       $match = $match_array[1];
                   } else  
                   {
                       $match = $match_array[0]; 
                   }  
            }
        } 
        else if($J1 == "X")
        {
            if ($J2 == $S1)
            {
                if ($J3 == $S2){
                 $match = $match_array[2];
                } else 
                if ($J3 == "NA") {  
                    $match = $match_array[2];
                } 
                else 
                if ($J3 == $S3) {  
                    $match = $match_array[2];
                } else 
                {
                    $match = $match_array[1]; 
                }

            } 
            else if ($J2 == $S2)
            {
                if ($J3 == $S1)
                {
                    $match = $match_array[3];
                } 
                else if ($J3 == $S3) 
                {
                       $match = $match_array[3];
                } 
                else if ($J3 == "NA") 
                {
                       $match = $match_array[2];
                } 
                else 
                {
                       $match = $match_array[1]; 
                }
            } 
            else if ($J2 == $S3)
            {
                if ($J3 == $S1)
                {
                    $match = $match_array[2];
                } 
                else if ($J3 == $S2) 
                {
                       $match = $match_array[2];
                } 
                else if ($J3 == "NA") 
                {
                       $match = $match_array[1];
                } 
                else 
                {
                       $match = $match_array[0]; 
                }
            } 
            else if($J2 == "NA")
            {
                $match = $match_array[1];    
            } 
            else 
            {
                if ($J3 == $S1){
                    $match = $match_array[1];
                   } else 
                   if ($J3 == $S2) {
                       $match = $match_array[1];
                   } 
                   else 
                   if ($J3 == $S3) {
                       $match = $match_array[0];
                   } 
            }
        }
        return $match;
    }

    
    $sql_apt = "select * from career_apt_latest";
    $res_apt = mysqli_query($con,$sql_apt);
    while($row_apt = mysqli_fetch_array($res_apt))
    {
        $cd = $row_apt['profession_name'];
   
        
        // Added by Sudhir
        $ar_w = $row_apt['AR_w'];
        $vr_w = $row_apt['VR_w'];
        $sa_w = $row_apt['SA_w'];

        $com_w = $row_apt['COM_w'];
        $nc_w = $row_apt['NC_w'];
        $om_w = $row_apt['OM_w'];
        
        
        //End of added by Sudhir
        $match = aptitude_mapper($apt_value[0],$apt_value[1],$apt_value[2],$apt_value[3],$apt_value[4],$apt_value[5],
        $row_apt['AR_v'],$row_apt['VR_v'],$row_apt['SA_v'],$row_apt['COM_v'],$row_apt['NC_v'],$row_apt['OM_v'],
        $ar_w,$vr_w,$sa_w,$com_w,$nc_w,$om_w,$cd,$con,$SA_Level);
        $sql_update = "update career_sui_latest set apt='$match' where profession_name='$cd' and code='$code'";
        mysqli_query($con,$sql_update);
    }
    function aptitude_mapper($a1,$a2,$a3,$a4,$a5,$a6,$b1,$b2,$b3,$b4,$b5,$b6,$c1,$c2,$c3,$c4,$c5,$c6,$prof_name,$con,$SA_Level)
    {

        $match = '';
        $ar_level = array('L','M','H2','H1','VH');
        $ar_level_val = array(0.40,0.60,0.75,0.90,1.00);
        
        $arr_level = array();
        if ($a1 >= $b1) {array_push($arr_level,1); } else {array_push($arr_level,0);}
        if ($a2 >= $b2) {array_push($arr_level,1); } else {array_push($arr_level,0);}
        if ($a3 >= $b3) {array_push($arr_level,1); } else {array_push($arr_level,0);}
        if ($a4 >= $b4) {array_push($arr_level,1); } else {array_push($arr_level,0);}
        if ($a5 >= $b5) {array_push($arr_level,1); } else {array_push($arr_level,0);}
        if ($a6 >= $b6) {array_push($arr_level,1); } else {array_push($arr_level,0);}
        $sum = $arr_level[0]*$c1 + $arr_level[1]*$c2 +$arr_level[2]*$c3 + $arr_level[3]*$c4 +$arr_level[4]*$c5 + $arr_level[5]*$c6;
        // echo "Match coeff :".$sum."<br>";
        for ($i=0;$i<count($ar_level_val);$i++)
        {
            if($sum <= $ar_level_val[$i])
            {
                $match = $ar_level[$i];
                break;
            }
        }

        //Modification - 25-04-22 - Code adapted to SA
        $man_apt = null;
        $x='';
        $sql_query = "SELECT mandatory_apt FROM `career_apt_latest_2` WHERE profession_name = '$prof_name'";
        $mandatory_apt_sql = mysqli_query($con,$sql_query);
        
        while($data = mysqli_fetch_array($mandatory_apt_sql)) {
            $x = $man_apt = $data['mandatory_apt'];
        };
        
        //Sudhir - 29-06-22
        if ($man_apt=='SA')
        {
            if ($SA_Level == "Low")
           {
               $GLOBALS['sa_override'][$prof_name]['apt'] = 'L';
               
           }

           if ($SA_Level == "Medium")
           {
               $GLOBALS['sa_override'][$prof_name]['apt'] = 'M';
               
           }

        }
        //End of Sudhir - 29-06-22
        //Old elimination logic - between 25-04-22 till 29-06-22 
        // if($man_apt=='SA' && ($SA_Level == "Low"  || $SA_Level == "Medium")){
        //     $match = $ar_level[0]; 
        // }
        //End of Old elimination logic - between 25-04-22 till 29-06-22


        return $match;        
    }

    // echo "<pre>";
    // print_r($GLOBALS['sa_override']);
    // echo "</pre>";
    // die();
    
    //Overall recommendations
    $logic_sql = "select * from career_logic_latest";
    $logic_res = mysqli_query($con,$logic_sql);
    $arr_logic = array();
    
    while($data = mysqli_fetch_array($logic_res)) {
        
        array_push($arr_logic,$data);
      }
      
    
    // echo "<pre>";
    //print_r($logic_arr);
    $sql_ft = "select * from career_sui_latest where code='$code'";
    $res_ft = mysqli_query($con,$sql_ft);
    while($row_ft=mysqli_fetch_array($res_ft))
    {
        $cd = $row_ft['profession_name'];
        $x = "I-".$row_ft['inte']."-A-".$row_ft['apt'];
       
        
        //echo $row_ft['apt'].'<br>';
        foreach($arr_logic as $la)
        {
            
            if($la[5] == $x){
                $match = $la[4];
                $sql_update = "update career_sui_latest set recommendation='$match' where profession_name='$cd' and code='$code'";
                mysqli_query($con,$sql_update);
                //echo $match."<br>";
               
            }

        } 
        //New Logic for sa_override - 29 June 2022 - Sudhir
        $arr_sa_override = [
            //apt => int
            "L" => ["VL", "L", "M"],
            "M"  => ["VL", "L"],
           
        ];
        $sa_override_val = "Avoid";
        foreach($GLOBALS['sa_override'] as $k=>$v)
        {
           foreach($arr_sa_override as $k1=>$v1)
           {
            if($k1 == $v['apt'] )
            {
                if(in_array($v['int'], $v1))
                {
                    $sql_update = "update career_sui_latest set recommendation='$sa_override_value' where profession_name='$cd' and code='$code'";        
                }
            }
           }
        }
        
        //End of New Logic for sa_override - 29 June 2022 - Sudhir
        
    }
    

    
    


   
    //$choise = array('Top Choice','Good Choice','Optional','Explore');
    $careers_to_disp = 50;
    $max_from_clstr = 20;
    $choise = array('Top Choice','Good Choice','Optional');
    $t = array();
    $i = 1;
    $arr_a = array();
    $arr_b = array();
    $arr_c = array();
    $arr_careers = array();
    $arr_ch_val = array('Top Choice','Good Choice','Optional','Develop','Explore');
    $arr_ch_rank = array(4,3,2,1,0);
    $cnt_ch_val = count($arr_ch_val);
    
    for ($i=0;$i<$cnt_ch_val;$i++)
    {
        $arr_b[$arr_ch_val[$i]] = array();
    }
    
    
echo "<pre>
code start here by test............ <br> </pre>";

    //Added by Sudhir on 20 Jan 22
    {
        $count_of_professions = 0;
        $max_count = 20;
        $arr_professions = [];

        foreach($arr_ch_val as $v_ch_val){
            $count_limit = $max_count - $count_of_professions;
            if($count_limit > 0){
            $sql_query_ch = "SELECT a.Cluster, a.recommendation, a.profession_name,b.11th_12th,b.Education";
            $sql_query_ch .= " FROM career_sui_latest a LEFT JOIN career_edu_latest b ON b.profession_name = a.profession_name";
            $sql_query_ch .= " WHERE a.code='$code' AND (a.recommendation ='$v_ch_val' AND a.Cluster !='#N/A') AND b.display_priority = 1";
            $sql_query_ch .= " AND b.11th_12th IS NOT NULL AND b.11th_12th != '#NAME?'";
            $sql_query_ch .= " ORDER BY  a.Cluster";
            $sql_query_ch .= " LIMIT $count_limit";

            $res_ch = mysqli_query($con,$sql_query_ch);
            $count_num = mysqli_num_rows($res_ch);
            if($count_num > 0){
                while($res_ch_result = mysqli_fetch_array($res_ch)){
                    $arr_professions[] = $res_ch_result;
                }
            }
          
             $count_of_professions += $count_num;
            }else{
                break;
            }
            
        }

        //count 
        $arr_cluster=[];
        $arr_stream =[];
        foreach($arr_professions as $v_professions){
            array_push($arr_cluster,$v_professions['Cluster']);
            array_push($arr_stream, $v_professions['11th_12th']);
        }
           
            $arr_cluster_200122 = array_count_values($arr_cluster);        
            $arr_stream_200122 =  array_count_values($arr_stream);
            arsort($arr_cluster_200122);
            arsort($arr_stream_200122);

            // print_r($arr_c);
            // print_r($arr_s);
       
            // die();
    }
    //Added by Sudhir on 01 Nov 21
    echo __LINE__ ." I am here...";
    {
        
        $sql_cnt_x = "SELECT Cluster, recommendation, COUNT(profession_name) FROM career_sui_latest";
        $sql_cnt_x .= " WHERE code='$code' AND recommendation !='Avoid' AND Cluster !='#N/A' ";
        $sql_cnt_x .= " GROUP BY Cluster, recommendation ";
        $sql_cnt_x .= " ORDER BY COUNT(profession_name) DESC";
        $res_cnt_x = mysqli_query($con,$sql_cnt_x);
        while($row = mysqli_fetch_array($res_cnt_x))
        {
            if($row['recommendation']=='Top Choice')
            {
                array_push($arr_b['Top Choice'],$row);
            }
            if($row['recommendation']=='Good Choice')
            {
                array_push($arr_b['Good Choice'],$row);
            }
            if($row['recommendation']=='Optional')
            {
                array_push($arr_b['Optional'],$row);
            }
            if($row['recommendation']=='Develop')
            {
                array_push($arr_b['Develop'],$row);
            }
            if($row['recommendation']=='Explore')
            {
                array_push($arr_b['Explore'],$row);
            }

        }
        echo __LINE__ ." I am here... <br>";
        
        
        function sortByOrder($a, $b) 
        {
            return $a['COUNT(profession_name)'] - $b['COUNT(profession_name)'];
        }
        echo __LINE__ ." I am here... <br>";
        {
            /* rsort($arr_b['Top Choice'], 'sortByOrder');
            rsort($arr_b['Good Choice'], 'sortByOrder');
            rsort($arr_b['Optional'], 'sortByOrder');
            rsort($arr_b['Develop'], 'sortByOrder');
            rsort($arr_b['Explore'], 'sortByOrder'); */

            // sort($array, SORT_FLAG_CASE);
            
            sort($arr_b['Top Choice'], SORT_NUMERIC ,'sortByOrder');
            sort($arr_b['Good Choice'],SORT_NUMERIC, 'sortByOrder');
            sort($arr_b['Optional'] ,SORT_NUMERIC, 'sortByOrder');
            sort($arr_b['Develop'],SORT_NUMERIC, 'sortByOrder');
            sort($arr_b['Explore'] ,SORT_NUMERIC, 'sortByOrder');
        }
        echo __LINE__ ." I am here... <br>";
        //echo top choice array
        //echo "Top Choice Array<pre>";
        //print_r($arr_b);
        $cnt_profs =0;
  
        echo __LINE__ ." I am here...";
        foreach($arr_b['Top Choice'] as $temp_x)
        {
                
                echo "Entered Top Choice :"."<br>";
                if($temp_x['COUNT(profession_name)']<$max_from_clstr)
                {
                 $cnt_profs += $temp_x['COUNT(profession_name)'];   
                }
                else
                {
                    $cnt_profs += $max_from_clstr;
                }
                
                $clstr = $temp_x['Cluster'];
                //echo "Cluster :".$clstr." has ".$temp_x['COUNT(profession_name)']."<br>";
                
                {
                   $sql_choice_top = "select * from career_sui_latest where Cluster = '$clstr' AND recommendation='Top Choice' AND code='$code' LIMIT $max_from_clstr";
                   $res_choice_top = mysqli_query($con,$sql_choice_top);
                   //echo "No of Top Choice Clusters : ".mysqli_num_rows($res_choice_top)."<br>";
                   while($row = mysqli_fetch_array($res_choice_top))
                   {
                        
                        $pro_x = $row['profession_name'];
                        //echo "Profession Name :".$pro_x."<br>";
                        $sql_choice_top_1 = "select * from career_edu_latest where profession_name = '$pro_x'";
                        $res_choice_top_1 = mysqli_query($con,$sql_choice_top_1);
                        while($row_1 = mysqli_fetch_array($res_choice_top_1))
                        {
                          array_push($t, array($pro_x,$clstr,$row_1['11th_12th'], $row_1['Education'],'Top Choice'));
                          
                        }
                   }
                    
                }    
        //if(!in_array($temp_x['Cluster'],$arr_c))
                {
                   array_push($arr_c,$temp_x['Cluster']); 
                }
                
        if($cnt_profs >= $careers_to_disp)
                {
                   break;
                }
     
         }
    }
        echo __LINE__ ." I am here...<br>";
         if ($cnt_profs < $careers_to_disp)
         {
            foreach($arr_b['Good Choice'] as $temp_x)
            {
                    echo "Entered Good Choice :"."<br>";
                    if($temp_x['COUNT(profession_name)']<$max_from_clstr)
                    {
                     $cnt_profs += $temp_x['COUNT(profession_name)'];   
                    }
                    else
                    {
                        $cnt_profs += $max_from_clstr;
                    }
                    $clstr = $temp_x['Cluster'];
                    //echo "Cluster :".$clstr." has ".$temp_x['COUNT(profession_name)']."<br>";
                    
                    {
                       $sql_choice_good = "select * from career_sui_latest where Cluster = '$clstr' AND recommendation='Good Choice' and code='$code' LIMIT $max_from_clstr";
                       $res_choice_good = mysqli_query($con,$sql_choice_good);
                       echo "No of Top Choice Clusters : ".mysqli_num_rows($res_choice_good)."<br>";
                       while($row = mysqli_fetch_array($res_choice_good))
                       {
                            $pro_x = $row['profession_name'];
                            //echo "Profession Name :".$pro_x."<br>";
                            $sql_choice_good_1 = "select * from career_edu_latest where profession_name = '$pro_x'";
                            $res_choice_good_1 = mysqli_query($con,$sql_choice_good_1);
                            while($row_1 = mysqli_fetch_array($res_choice_good_1))
                            {
                              array_push($t, array($pro_x,$clstr,$row_1['11th_12th'], $row_1['Education'],'Good Choice'));
                            }
                       }
                    }
                    
               //     if(!in_array($temp_x['Cluster'],$arr_c))
                    {
                       array_push($arr_c,$temp_x['Cluster']); 
                    }
                    
                    if($cnt_profs >= $careers_to_disp)
                    {
                        break;
                    }
            }

         }
         if ($cnt_profs < $careers_to_disp)
         {
          foreach($arr_b['Optional'] as $temp_x)
            {
                    echo "Entered Optional :"."<br>";
                    if($temp_x['COUNT(profession_name)']<$max_from_clstr)
                    {
                     $cnt_profs += $temp_x['COUNT(profession_name)'];   
                    }
                    else
                    {
                        $cnt_profs += $max_from_clstr;
                    }
                    $clstr = $temp_x['Cluster'];
                   // echo "Cluster :".$clstr." has ".$temp_x['COUNT(profession_name)']."<br>";
                    
                    {
                       $sql_choice_opt = "select * from career_sui_latest where Cluster = '$clstr' AND recommendation='Optional' and code='$code' LIMIT $max_from_clstr";
                       $res_choice_opt = mysqli_query($con,$sql_choice_opt);
                       //echo "No of Top Choice Clusters : ".mysqli_num_rows($res_choice_opt)."<br>";
                        while($row = mysqli_fetch_array($res_choice_opt))
                       {
                            $pro_x = $row['profession_name'];
                            //echo "Profession Name :".$pro_x."<br>";
                            $sql_choice_opt_1 = "select * from career_edu_latest where profession_name = '$pro_x'";
                            $res_choice_opt_1 = mysqli_query($con,$sql_choice_opt_1);
                            while($row_1 = mysqli_fetch_array($res_choice_opt_1))
                            {
                              array_push($t, array($pro_x,$clstr,$row_1['11th_12th'], $row_1['Education'],'Optional'));
                              //var_dump($t);
                            }
                       }
                    }
                    
               //     if(!in_array($temp_x['Cluster'],$arr_c))
                    {
                       array_push($arr_c,$temp_x['Cluster']); 
                    }
                    
                    if($cnt_profs >= $careers_to_disp)
                    {
                        break;
                    }
            }
         }
         
         if ($cnt_profs < $careers_to_disp)
         {
           foreach($arr_b['Develop'] as $temp_x)
            {
                    echo "Entered Developed :"."<br>";
                    if($temp_x['COUNT(profession_name)']<$max_from_clstr)
                    {
                     $cnt_profs += $temp_x['COUNT(profession_name)'];   
                    }
                    else
                    {
                        $cnt_profs += $max_from_clstr;
                    }
                    $clstr = $temp_x['Cluster'];
                    //echo "Cluster :".$clstr." has ".$temp_x['COUNT(profession_name)']."<br>";
                    
                    {
                       $sql_choice_dev = "select * from career_sui_latest where Cluster = '$clstr' AND recommendation='Develop' and code='$code' LIMIT $max_from_clstr";
                       $res_choice_dev = mysqli_query($con,$sql_choice_dev);
                       echo "No of Top Choice Clusters : ".mysqli_num_rows($res_choice_dev)."<br>";
                        while($row = mysqli_fetch_array($res_choice_dev))
                       {
                            $pro_x = $row['profession_name'];
                            //echo "Profession Name :".$pro_x."<br>";
                            $sql_choice_dev_1 = "select * from career_edu_latest where profession_name = '$pro_x'";
                            $res_choice_dev_1 = mysqli_query($con,$sql_choice_dev_1);
                            while($row_1 = mysqli_fetch_array($res_choice_dev_1))
                            {
                              array_push($t, array($pro_x,$clstr,$row_1['11th_12th'], $row_1['Education'],'Develop'));
                            }
                       }
                    }
                    
               //     if(!in_array($temp_x['Cluster'],$arr_c))
                    {
                       array_push($arr_c,$temp_x['Cluster']); 
                    }
                    
                    if($cnt_profs >= $careers_to_disp)
                    {
                        break;
                    }
            }

         }
         
         if ($cnt_profs < $careers_to_disp)
         {
           foreach($arr_b['Explore'] as $temp_x)
            {
                    echo "Entered Explore :"."<br>";
                    if($temp_x['COUNT(profession_name)']<$max_from_clstr)
                    {
                     $cnt_profs += $temp_x['COUNT(profession_name)'];   
                    }
                    else
                    {
                        $cnt_profs += $max_from_clstr;
                    }
                    $clstr = $temp_x['Cluster'];
                    //echo "Cluster :".$clstr." has ".$temp_x['COUNT(profession_name)']."<br>";
                    
                    {
                       $sql_choice_exp = "select * from career_sui_latest where Cluster = '$clstr' AND recommendation='Explore' and code='$code' LIMIT $max_from_clstr";
                       $res_choice_exp = mysqli_query($con,$sql_choice_exp);
                       //echo "No of Top Choice Clusters : ".mysqli_num_rows($res_choice_exp)."<br>";
                        while($row = mysqli_fetch_array($res_choice_exp))
                       {
                            $pro_x = $row['profession_name'];
                            //echo "Profession Name :".$pro_x."<br>";
                            $sql_choice_exp_1 = "select * from career_edu_latest where profession_name = '$pro_x'";
                            $res_choice_exp_1 = mysqli_query($con,$sql_choice_exp_1);
                            while($row_1 = mysqli_fetch_array($res_choice_exp_1))
                            {
                              array_push($t, array($pro_x,$clstr,$row_1['11th_12th'], $row_1['Education'],'Explore'));
                            }
                       }
                    }
                    
              //      if(!in_array($temp_x['Cluster'],$arr_c))
                    {
                       array_push($arr_c,$temp_x['Cluster']); 
                    }
                    
                    if($cnt_profs >= $careers_to_disp)
                    {
                        break;
                    }
            }
         }
        

    
    
    // echo "Count of Professions :".$cnt_profs."<br><pre>";
   // print_r($arr_c);
   // var_dump($arr_careers);
    
    
    
    

 
    
    //echo top cluster array
    //echo "Top Clusters :<pre>";
    //print_r($arr_a);
    
    $clusters_to_display = 0;
    //echo "No of rows fetched :".$res_cnt->num_rows."<br>";
 
    
   
    
    //Top Cluster analysis pdf
    
    
    
    $clust_ctr=0;
    $top_clusters = "";
    echo $to_print = "Your Top Career Clusters"."\r\n\r\n";
    echo "Test"."<br>";
    // print_r($arr_c);
    // echo "<pre>";
    //Added on 21-01-2022
    foreach($arr_cluster_200122 as $top_clstr=> $v_top_cluster)
    //End of Added on 21-01-2022

    /* Commented on  21-01-2022  
    foreach($arr_c as $top_clstr)
    */
    //while($row_cnt = mysqli_fetch_array($res_cnt))
    {
        // print_r($top_clstr);
        // die();
        echo "1"."<br>";
        $clusters_to_display +=1;
        if ($clust_ctr < $clusters_to_display)
        {
            //echo "2"."<br>";
            //$x = $row_cnt['Cluster'];
            
            $x = $top_clstr;   
            echo "<br>Top Cluster: $x<br>";
            
            $sql_desc = "SELECT Description FROM career_cluster_desc WHERE Cluster = '$x'";
            
            $res_desc = mysqli_query($con, $sql_desc);
            
            if (!$res_desc) {
                printf("Error: %s\n", mysqli_error($con));
                exit();
            }
            
            $row_desc = mysqli_fetch_array($res_desc);
            
            /* $bypass_condition = ($x == "Arts, Audio/Video Technology & Communications") && ($SA_Level=="Low");
            if($bypass_condition != "TRUE"){
                
            } */

            $top_clusters .="'".$x."',";
            $to_print .= $x."\r\n".$row_desc['Description']."\r\n\r\n";
            

            //echo ($to_print)."<br>";
            
            
        
        }
        $clust_ctr += 1;
        if($clusters_to_display >2)
        {
            $clusters_to_display = 2;
            break;
        }
       
        
        
      
    }
    
    //die();
    //echo "Clusters to display :".$clusters_to_display."<br>";
    $top_clusters = rtrim($top_clusters, ", ");
    echo "Top Clusters :".$top_clusters."<br>";
    
    
    
    $pagecount = $pdf->setSourceFile('report_template/Border Page.pdf');
    $tpl = $pdf->importPage(1);
    $pdf->AddPage("P", "A4");
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    $pdf->SetXY(20,20);
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(170,6.5, $to_print,0,'L',false);
    //End of Added by Sudhir
    
    //Top Stream printing to PDF - 21 Jan 2022
    $to_print_stream_210122 = 'Your Top Streams';
    $to_print_streams_max = 2;
    //$to_print_stream_label =['1st Preference', '2nd Preference'];
    $to_print_stream_label =['', ''];
    $to_print_stream_ctr = 0;
    foreach($arr_stream_200122 as $k_stream_to_print=>$v_stream_to_print)
    {
        
        if($to_print_stream_ctr<$to_print_streams_max)
        {
            $to_print_stream_210122 .= "\r\n\r\n".$to_print_stream_label[$to_print_stream_ctr]." ".$k_stream_to_print."\r\n\r\n";
            $to_print_stream_ctr +=1;           
        }
      
    }
    $pdf->SetX(20);
    $pdf->SetFont('Arial','',15);
    $pdf->MultiCell(170,6.5, $to_print_stream_210122,0,'L',false);


    //End of Top Stream printing to PDF - 21 Jan 2022
    
    
   
    echo "Client Code :".$code."<br>";
    

    $p_ct =0;
    

    
    //echo "Professions :<pre>";
    //print_r($t);

    function value_level_mapper($per_val)
    {
        $status['level'] = '';
        $status['color'] = '';
        if($per_val<40)
        {
            $status['level'] = 'Low';
            $status['color'] = '247,89,73';
        } else
        if($per_val<60)
        {
            $status['level'] = 'Medium';
            $status['color'] = '234,250,33';
        }
        else
        if($per_val<75)
        {
            $status['level'] = 'High';
            $status['color'] = '95,250,33';
        }
        else
        if($per_val<=100 )
        {
            $status['level'] = 'Very High';
            $status['color'] = '74,191,27';
        }
        return $status;
    }

    
    
    
    $pdf->addPage();
    checking_size($logo,$pdf);
    $pdf->SetFont('Arial','B',24);
    $pdf->SetXY(10,36);
    $pdf->SetTextColor(11,170,54);
    $pdf->SetFillColor(255,255,255);
    $pdf->Cell(80,6,'Career Suggestions',0,0,'L',true);

    $pdf->SetFont('Arial','B',9);
    $pdf->SetXY(10,46);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(219,233,201);
    $pdf->Cell(30,6,'Profession Name',1,0,'L',true); // First header column 
    
    //Added by Sudhir on 24-Oct-2021
    
    $pdf->Cell(30,6,'Cluster',1,0,'L',true); // First header column 
    
    //End of Added by Sudhir
    
    $pdf->Cell(25,6,'11Th / 12Th',1,0,'L',true); // Second header column
    $pdf->Cell(65,6,'Higher Education',1,0,'L',true); // Third header column 
    $pdf->Cell(34,6,'Recommendation',1,1,'L',true); // Fourth header column
    $xn = $x = $pdf->GetX();
    $yn = $y = $pdf->GetY();
    $pdf->SetFont('Arial','B',9);
    $maxheight = 0;
    $width=46;
    $height=6;
    $cells = count($t);
    
    //$width_cell=array(38,30,48,70);
    
    $page_height = 400; // mm 
    $pdf->SetAutoPageBreak(true);
    // echo "<pre>";
    //print_r($t);
    echo "<br>here------------------<br>";

    $arr_professions_final = [];
    foreach($arr_professions as $k=>$v)
    {
        
        //Lines added on 25-04-22 - to adapt for SA
        
        $cluster = $v[0];
        $reco = $v[1];
        $prof = $v[2];
        $_11th = $v[3];
        $edu = $v[4];

       $arr_professions_final[] = [$prof,$cluster,$_11th,$edu,$reco];


    }
    foreach($arr_professions_final as $item)
    //End of 21-01-22 - Added below foreacg

    /* 21-01-22 - Commented foreach
     foreach($t as $item)
    */
    {
        
            
            $x = $x;
            $y = $y+$maxheight;
            $height_of_cell=$y-$yn;
            $space_left=$page_height-($y); // space left on page
            if ($height_of_cell > $space_left) 
            {
                // $pdf->Write($y+$yn,'Next');
                // $tpl = $pdf->importPage(7);
                $pdf->AddPage();
                // $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                checking_size($logo,$pdf);
                
                $pdf->SetFont('Arial','B',9);
                $pdf->SetXY(10,46);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFillColor(219,233,201);
                $pdf->Cell(30,6,'Profession Name',1,0,'L',true); // First header column 
                
                //Added by Sudhir on 24-Oct-2021
    
                $pdf->Cell(30,6,'Cluster',1,0,'L',true); // First header column 
    
                //End of Added by Sudhir
                
                $pdf->Cell(25,6,'11Th / 12Th',1,0,'L',true); // Second header column
                $pdf->Cell(65,6,'Higher Education',1,0,'L',true); // Third header column 
                $pdf->Cell(34,6,'Recommendation',1,1,'L',true); // Fourth header column
                $xn = $x = $pdf->GetX();
                $yn = $y = $pdf->GetY();
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
                    $pdf->MultiCell(30, $height, $item[$i],0,'L');
                }
                else if($i==1)
                {
                    $pdf->SetXY($x + (30) , $y);
                    $pdf->MultiCell(30, $height, $item[$i],0,'L'); 
                }
                else if($i==2)
                {
                    $pdf->SetXY($x + (60) , $y);
                    $pdf->MultiCell(25, $height, $item[$i],0,'L'); 
                }
                else if($i==3)
                {
                    $pdf->SetXY($x + (85) , $y);
                    $pdf->MultiCell(65, $height, $item[$i],0,'L');  
                }
                else 
                {
                    $pdf->SetXY($x + (150) , $y);
                    $pdf->MultiCell(20, $height, $item[$i],0,'L');
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
                    $pdf->Line($x + 60, $y, $x + 60, $y + $maxheight); 
                }
                else if($i==3)
                {
                    $pdf->Line($x + 85, $y, $x + 85, $y + $maxheight); 
                }
                else if($i==4)
                {
                    $pdf->Line($x + 150, $y, $x + 150, $y + $maxheight);
                }  
                
                
                
                    $pdf->Line($x + 184, $y, $x+ 184, $y + $maxheight);
                
            }
            
            $pdf->Line($x, $y, $x + 184, $y);// top line
            $pdf->Line($x, $y + $maxheight, $x + 184, $y + $maxheight);//bottom
                         
    } 
      
    include('Remark.php');


ob_end_clean();
$pdf->AliasNbPages();


$pdf->Output();


// $pdf2->Output();

ob_end_flush();


?>