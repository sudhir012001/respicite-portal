<?php 
ob_start();
$code = base64_decode($_GET['code']);
include('dbconn.php');
include('MI_score_calculation.php');

require_once('general_functions.php');

use setasign\Fpdi\Fpdi;
require_once('fpdf181/fpdf181/fpdf.php');
require_once('fpdi2/fpdi2/src/autoload.php');
// Create new Landscape PDF
$pdf = new FPDI();
// include('Second_Detail_Page.php');
// Reference the PDF you want to use (use relative path)
$pagecount = $pdf->setSourceFile('report_template/QCE_Report.pdf');

// Import the first page from the PDF and add to dynamic PDF

//Page No 1
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
// End of Page no 1

//Start Page no 2



// $logo = '../uploads/default.jpg';
include('Second_Detail_Page.php');
$r_detail_sql = "select * from reseller_homepage where r_email='$r_id'";
$r_detail_res = mysqli_query($con,$r_detail_sql);
$r_detail_row = mysqli_fetch_array($r_detail_res);
$logo = $r_detail_row['logo'];
$logo = 'https://users.respicite.com/'.$logo;
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


//end Page no 2
//page 3
$pagecount = $pdf->setSourceFile('report_template/QCE_Report.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);
$pagecount = $pdf->setSourceFile('report_template/mi_static_page.pdf');
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);
$pagecount = $pdf->setSourceFile('report_template/QCE_Report.pdf');
$tpl = $pdf->importPage(4);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);
include('sdq_score_calculation.php');


$ctr = array();
$sql_ctr = "select distinct intelligence from qce_part1_question where intelligence!='TBD'";
$res_ctr = mysqli_query($con,$sql_ctr);
while($row_ctr = mysqli_fetch_array($res_ctr))
{
   
    array_push($ctr,$row_ctr['intelligence']);
}
$count = count($ctr);
for($i=0;$i<$count;$i++)
{
    $score[$i] = 0;
    $cat_count[$i] = 0;
}
$sql_ans = "select * from ppe_part1_test_details where code='$code' and solution='qce_part2'";
$res_ans = mysqli_query($con,$sql_ans);
while($row_ans = mysqli_fetch_array($res_ans))
{
    $qno = $row_ans['qno'];
    $ans = $row_ans['ans'];
    if($ans == 1)
    {
        $ans = 1;
    }
    else if($ans == 2)
    {
        $ans = 0;
    }
    $check_category = "select * from qce_part1_question where qno='$qno'";
    $res_check_category = mysqli_query($con,$check_category);
    $row_check_category = mysqli_fetch_array($res_check_category);
    $category = $row_check_category['intelligence'];
    for($i=0;$i<$count;$i++)
    {
        if($category==$ctr[$i])
        {
            $score[$i] = $score[$i] + $ans;
            $cat_count[$i] = $cat_count[$i] + 1;
        }
    }
}

for($i=0;$i<$count;$i++)
{
   $per[$i]['val'] = $score[$i] / $cat_count[$i];
    $per[$i]['val'] = $per[$i]['val'] / $count;
    $per[$i]['cat']= $ctr[$i];
}





print_r($d = mi_score_level_mapper($per));
$color = explode(',',level_color_mapper("Highly Dominant"));
$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetXY(58,203);
$pdf->Cell(132, 19,'', 0, 1, 'L',true);
$pdf->SetXY(58,204);
$pdf->MultiCell(132, 5,$d['Highly_Dominant'], 0, 1, 'L',true);


$color = explode(',',level_color_mapper("Dominant"));
$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetXY(58,223.5);
$pdf->Cell(132, 15,'', 0, 1, 'L',true);
$pdf->SetXY(58,225);
$pdf->MultiCell(132, 5,$d['Dominant'], 0, 1, 'L',true);

$color = explode(',',level_color_mapper("Less Dominant"));
$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetXY(58,239);
$pdf->Cell(132, 15,'', 0, 1, 'L',true);
$pdf->SetXY(58,240);
$pdf->MultiCell(132, 5,$d['Less_Dominant'], 0, 1, 'L',true);

$color = explode(',',level_color_mapper('Non Dominant'));
$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetXY(58,255);
$pdf->Cell(132, 13,'', 0, 1, 'L',true);
$pdf->SetXY(58,256);
$pdf->MultiCell(132, 5,$d['Non_Dominant'], 0, 1, 'L',true);

$tpl = $pdf->importPage(5);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);
include('qce_vark.php');

$tpl = $pdf->importPage(6);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);
include('MI_reasic_mapper.php');
$array_interest = MI_reasic_mapper($code,$con);
$top_interests = riasec_top_three($array_interest,$con,$code);

// for($i=0;$i<3;$i++)
// {
//     if($top_interests=='I')
// }
$pdf->SetFontSize(14);
$pdf->SetTextColor(255,255,255);
$pdf->SetXY(144,210);
$int = int_code_name_mapper($top_interests[0]).'('.$top_interests[0].')';
$pdf->Cell(132, 13,$int, 0, 0, 'L',false);
$pdf->SetXY(144,220);
$int =int_code_name_mapper($top_interests[1]).'('.$top_interests[1].')';
$pdf->Cell(132, 13,$int, 0, 0, 'L',false);
$pdf->SetXY(144,230);
$int = int_code_name_mapper($top_interests[2]).'('.$top_interests[2].')';
$pdf->Cell(132, 13,$int, 0, 0, 'L',false);
$pdf->SetTextColor(0,0,0);
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
    $array_interest[0]['name']=>['color'=>[24,203,238],'value'=>$array_interest[0]['score']],
    $array_interest[1]['name']=>['color'=>[172,13,26],'value'=>$array_interest[1]['score']],
    $array_interest[2]['name']=>['color'=>[247,253,4],'value'=>$array_interest[2]['score']],
    $array_interest[3]['name']=>['color'=>[10,25,100],'value'=>$array_interest[3]['score']],
    $array_interest[4]['name']=>['color'=>[0,25,10],'value'=>$array_interest[4]['score']],
    $array_interest[5]['name']=>['color'=>[24,0,238],'value'=>$array_interest[5]['score']]
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
//    if($i==40)
//    {
//     $pdf->Line(
//         $chartBoxX+85,
//         $yAxisPos-1.85,
//         $chartBoxX,
//         $yAxisPos-1.85     
//          );  
//    }
//    else if($i==20)
//    {
//     $pdf->Line(
//         $chartBoxX+85,
//         $yAxisPos-1.85,
//         $chartBoxX,
//         $yAxisPos-1.85     
//          );  
//    }
//    else if($i==60)
//    {
//     $pdf->Line(
//         $chartBoxX+85,
//         $yAxisPos-3,
//         $chartBoxX,
//         $yAxisPos-3     
//          );  
//    }
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

$sql = "select * from career_sui_latest where code='$code'";
$res = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($res))
{
   echo $cd = $row['profession_name'];
   echo "<br>";
    $sql2 = "select * from career_int_latest where profession_name='$cd'";
    $res2 = mysqli_query($con,$sql2);
    $row2 = mysqli_fetch_array($res2);
    
    // echo  "J1 ".$row['J1']." J2 ".$row['J2']." J3 ".$row['J3']."<br>";
   
    $match = interest_mapper($con,$cd,$top_interests[0],$top_interests[1],$top_interests[2], $row2['J1'],$row2['J2'],$row2['J3'],$code);
    if($match=='H1' || $match=='VH')
    {
        $recommendation = 'Top Choice';
    }
    else if($match=='H2' || $match=='H3')
    {
        $recommendation = 'Good Choice';
    }
    else if($match=='MH')
    {
        $recommendation = 'Optional';
    }
    else
    {
        $recommendation = 'Ignore';
    }
   
    $sql_update = "update career_sui_latest set inte='$match', recommendation='$recommendation' where profession_name='$cd' and code='$code'";
    mysqli_query($con,$sql_update);
}

$t = career_selector($con,$code);
career_selection_table_pdf($pdf,$t,$logo);
include('Remark.php');

ob_end_clean();
$pdf->AliasNbPages();

$pdf->Output();
// $pdf2->Output();

ob_end_flush();
 ?>