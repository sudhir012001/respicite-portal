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
    $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='qce_part1' and qno='$qno'";
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

$r1 = 0;
$r2 = 0;
$r3 = 0;
$r4 = 0;
$r5 = 0;
    
$sql3 = "select * from sdq_score_info where parameter='Emotions' and score='$p1'";
$res3 = mysqli_query($con,$sql3);
$row3 = mysqli_fetch_array($res3);
$result3 = $row3['result'];
$pdf->SetFont('Arial');
$pdf->SetTextColor(0,0,0);
$color = explode(',',level_color_mapper($result3));

$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetFontSize('11');
$pdf->SetXY(140,75);
$pdf->Cell(50,21, $result3, 0, 1,'L',true);

$sql4= "select * from sdq_score_info where parameter='Conduct' and score='$p2'";
$res4 = mysqli_query($con,$sql4);
$row4 = mysqli_fetch_array($res4);
$result4 = $row4['result'];

$color = explode(',',level_color_mapper($result4));

$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetFontSize('11');
$pdf->SetXY(140,97);
$pdf->Cell(50, 15, $result4, 0, 1,'L',true);


$sql5= "select * from sdq_score_info where parameter='Activity' and score='$p3'";
$res5 = mysqli_query($con,$sql5);
$row5 = mysqli_fetch_array($res5);
$result5 = $row5['result'];
$color = explode(',',level_color_mapper($result5));
$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetFontSize('11');
$pdf->SetXY(140,112);
$pdf->Cell(50, 15, $result5, 0, 1, 'L',true);
print_r($color);
$sql6= "select * from sdq_score_info where parameter='Peers' and score='$p4'";
$res6 = mysqli_query($con,$sql6);
$row6 = mysqli_fetch_array($res6);
$result6 = $row6['result'];
$color = explode(',',level_color_mapper($result6));
$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetFontSize('11');
$pdf->SetXY(140,127);
$pdf->Cell(50, 15, $result6, 0, 1, 'L',true);

$sql7= "select * from sdq_score_info where parameter='Prosocial behaviour' and score='$p5'";
$res7 = mysqli_query($con,$sql7);
$row7 = mysqli_fetch_array($res7);
$result7 = $row7['result'];
$color = explode(',',level_color_mapper($result7));
$pdf->SetFillColor($color[0],$color[1],$color[2]);
$pdf->SetFontSize('11');
$pdf->SetXY(140,142);
$pdf->Cell(50, 14, $result7, 0, 1, 'L',true);



?>