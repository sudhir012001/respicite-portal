<?php 
require_once('general_functions.php');
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
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='cat_part3' and qno='$qno'";
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

$overall_score = $p1 + $p2 +$p3 +$p4 + $p5;
$r1 = 0;
$r2 = 0;
$r3 = 0;
$r4 = 0;
$r5 = 0;
    $para = '';
$sql3 = "select * from sdq_score_info where parameter='Emotions' and score='$p1'";
$res3 = mysqli_query($con,$sql3);
$row3 = mysqli_fetch_array($res3);
$result3 = $row3['result'];
$pdf->SetFont('Arial');
$pdf->SetTextColor(0,0,0);
$color = explode(',',level_color_mapper($result3));

if($result3=='Needs immediate attention' || $result3=='Needs attention')
{
    $para .= 'Emotions, ';
}
$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetFontSize('11');
$pdf->SetXY(128,102.4);
$pdf->Cell(60,19, $result3, 0, 1,'L',true);

$sql4= "select * from sdq_score_info where parameter='Conduct' and score='$p2'";
$res4 = mysqli_query($con,$sql4);
$row4 = mysqli_fetch_array($res4);
$result4 = $row4['result'];

$color = explode(',',level_color_mapper($result4));

$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetFontSize('11');
$pdf->SetXY(128,122.5);
$pdf->Cell(60, 18, $result4, 0, 1,'L',true);

if($result4=='Needs immediate attention' || $result4=='Needs attention')
{
    $para .= 'Conduct, ';
    
}
$sql5= "select * from sdq_score_info where parameter='Activity' and score='$p3'";
$res5 = mysqli_query($con,$sql5);
$row5 = mysqli_fetch_array($res5);
$result5 = $row5['result'];
$color = explode(',',level_color_mapper($result5));
$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetFontSize('11');
$pdf->SetXY(128,141.7);
$pdf->Cell(60, 18, $result5, 0, 1, 'L',true);
if($result5=='Needs immediate attention' || $result5=='Needs attention')
{
    $para .= 'Activity, ';
}
print_r($color);
$sql6= "select * from sdq_score_info where parameter='Peers' and score='$p4'";
$res6 = mysqli_query($con,$sql6);
$row6 = mysqli_fetch_array($res6);
$result6 = $row6['result'];
$color = explode(',',level_color_mapper($result6));
$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetFontSize('11');
$pdf->SetXY(128,161.2);
$pdf->Cell(60, 18.5, $result6, 0, 1, 'L',true);
if($result6=='Needs immediate attention' || $result6=='Needs attention')
{
    $para .= 'Peers, ';
   
}
$sql7= "select * from sdq_score_info where parameter='Prosocial behaviour' and score='$p5'";
$res7 = mysqli_query($con,$sql7);
$row7 = mysqli_fetch_array($res7);
$result7 = $row7['result'];
$color = explode(',',level_color_mapper($result7));
$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetFontSize('11');
$pdf->SetXY(128,181.2);
$pdf->Cell(60, 17.5, $result7, 0, 1, 'L',true);
if($result7=='Needs immediate attention' || $result7=='Needs attention')
{
    $para .= 'Prosocial behaviour, ';
    
}

if ($para !==""){
    $para = "Specific Focus Areas: ".$para;
}
$sql = "select * from cat_sdq_inf";
$res = mysqli_query($con,$sql);
$inf = array();
while($row = mysqli_fetch_array($res))
{
    array_push($inf,$row);
}
$pdf->SetFillColor(255,255,255);
if($overall_score>=0 && $overall_score<=13)
{
    $value = $inf[2][2]."\r\n\r\n".$para;
    $pdf->SetFontSize('11');
    $pdf->SetXY(27,227);
    $pdf->MultiCell(150, 7,$value); 
}
else if($overall_score>=14 && $overall_score<=16)
{
    $value = $inf[1][2]."\r\n\r\n".$para;
    $pdf->SetFontSize('11');
    $pdf->SetXY(27,227);
    $pdf->MultiCell(150, 7, $value); 
}
else
{
    $value = $inf[0][2]."\r\n\r\n".$para;
    $pdf->SetFontSize('11');
    $pdf->SetXY(27,227);
    $pdf->MultiCell(150, 7, $value); 
}

?>