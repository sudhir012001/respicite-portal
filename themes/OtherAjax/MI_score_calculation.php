<?php
    // $MI_levels = array('Highly Dominant', 'Dominant', 'Less Dominant', 'Non Dominant');
   
    function mi_results()
    {
        $MI_results = array();
        
        $MI_results[0]['level']='Highly Dominant';
        $MI_results[0]['value'] ='A';
        $MI_results[1]['level']='Dominant';
        $MI_results[1]['value'] ='B';
        $MI_results[2]['level']='Less Dominant';
        $MI_results[2]['value'] ='C';
        $MI_results[3]['level']='Non Dominant';
        $MI_results[3]['value'] ='D';

      
        return $MI_results;
    }

    function mi_score_level_mapper($mi_score)
    {
       echo "<pre>";
       print_r($mi_score);
        $hd='';
        
        $ld='';
        $nd='';
        $d = array();
        $levels =['Highly Dominant','Dominant','Less Dominant','Non Dominant'];
        $d['Highly_Dominant'] = "";
        $d['Dominant'] = "";
        $d['Less_Dominant'] = "";
        $d['Non_Dominant'] = "";
        
        $level_value =['0.10625','0.08125','0.0625','0.00'];
             $count = count($mi_score);
        echo $count;
        for($i=0;$i<$count;$i++)
        {
           //$mi_score['val'] = array(0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00);
            if($mi_score[$i]['val']>$level_value[0])
            {
                $d['Highly_Dominant'] .= $mi_score[$i]['cat'].',';
            } else 
            if ($mi_score[$i]['val']>$level_value[1]){
                $d['Dominant'] .= $mi_score[$i]['cat'].',';
            }
            else 
            if ($mi_score[$i]['val']>$level_value[2]){
                $d['Less_Dominant'] .= $mi_score[$i]['cat'].',';
            }
            else 
            if ($mi_score[$i]['val']>=$level_value[3]){
        
                $d['Non_Dominant'] .= $mi_score[$i]['cat'].',';
            }

           
        }
        print_r($d);
        return $d;
        
    }

    function career_selector($con,$code)
    {
        $choice = array('Top Choice','Good Choice','Optional');
        $t = array();
        $i = 1;
        foreach($choice as $ch)
        {
            $sql_choice = "select * from career_sui_latest where recommendation='$ch' and code='$code'";
            $res_choice = mysqli_query($con,$sql_choice);
            while($row_choice = mysqli_fetch_array($res_choice))
            {
                    $r_c = $row_choice['profession_name'];
                    $sql_all_data = "select * from career_edu_latest where profession_name='$r_c'";
                    $res_all_data = mysqli_query($con,$sql_all_data);
                    while($row_a_d = mysqli_fetch_array($res_all_data))
                    {
                        if($i<=40)
                        {
                            array_push($t,array($row_a_d['profession_name'],$row_a_d['11th_12th'],$row_a_d['Education'],$ch));
                        }
                        
                        $i = $i + 1;
                    }
                    
            }
        }
        return $t;
    }

    function career_selection_table_pdf($pdf,$t,$logo)
    {
        $pdf->addPage();
    checking_size($logo,$pdf);
    $pdf->SetFont('Arial','B',24);
    $pdf->SetXY(10,36);
    $pdf->SetTextColor(11,170,54);
    $pdf->SetFillColor(219,233,201);
    $pdf->Cell(30,6,'Career Suggestions',0,0,'L',false);

    $pdf->SetFont('Arial','B',10);
    $pdf->SetXY(10,46);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(219,233,201);
    $pdf->Cell(30,6,'Profession Name',1,0,'L',true); // First header column 
    $pdf->Cell(25,6,'11Th / 12Th',1,0,'L',true); // Second header column
    $pdf->Cell(95,6,'Higher Education',1,0,'L',true); // Third header column 
    $pdf->Cell(34,6,'Choice',1,1,'L',true); // Fourth header column
    $xn = $x = $pdf->GetX();
    $yn = $y = $pdf->GetY();
    $pdf->SetFont('Arial','B',9);
    $maxheight = 0;
    $width=46;
    $height=6;
    $cells = 4;
    
    //$width_cell=array(38,30,48,70);
    
    $page_height = 400; // mm 
    $pdf->SetAutoPageBreak(true);
    foreach($t as $item)
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
                $pdf->SetFont('Arial','B',24);
                $pdf->SetXY(10,36);
                $pdf->SetTextColor(11,170,54);
                $pdf->SetFillColor(219,233,201);
                $pdf->Cell(30,6,'Career Suggestions',0,0,'L',false);
                $pdf->SetFont('Arial','B',10);
                $pdf->SetXY(10,46);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetFillColor(219,233,201);
                $pdf->Cell(30,6,'Profession Name',1,0,'L',true); // First header column 
                $pdf->Cell(25,6,'11Th / 12Th',1,0,'L',true); // Second header column
                $pdf->Cell(95,6,'Higher Education',1,0,'L',true); // Third header column 
                $pdf->Cell(34,6,'Choice',1,1,'L',true); // Fourth header column
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
                    $pdf->MultiCell(30, $height, $item[$i]);
                }
                else if($i==1)
                {
                    $pdf->SetXY($x + (30) , $y);
                    $pdf->MultiCell(25, $height, $item[$i]); 
                }
                else if($i==2)
                {
                    $pdf->SetXY($x + (56) , $y);
                    $pdf->MultiCell(90, $height, $item[$i]); 
                }
                else
                {
                    $pdf->SetXY($x + (152) , $y);
                    $pdf->MultiCell(60, $height, $item[$i]);  
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
                    $pdf->Line($x + 30 + 25, $y, $x + 30 + 25, $y + $maxheight); 
                }
                else if($i==3)
                {
                    $pdf->Line($x + 150, $y, $x + 150, $y + $maxheight); 
                }
                else
                {
                    $pdf->Line($x + 60*2 +60+4, $y, $x + 60*2 +60+4, $y + $maxheight);
                }
                
    
            }
            $pdf->Line($x, $y, $x + $width * $cells, $y);// top line
            $pdf->Line($x, $y + $maxheight, $x + $width * $cells, $y + $maxheight);//bottom
                         
    } 
  
    
    }
    
   
    

    
?>