<?php
    $v = 0;
    $a = 0;
    $r = 0;
    $k = 0;
    
    $sql = "select * from ppe_part1_test_details where code='$code' and solution='qce_part3'";
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

    $v_per = $v/16*100;
    $a_per = $a/16*100;
    $r_per = $r/16*100;
    $k_per = $k/16*100;

    $chartX=23;
    $chartY=217.8;
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

?>