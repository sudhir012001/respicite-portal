<?php 
ob_start();
$code = base64_decode($_GET['code']);
$a_scr = array(); //stores result

require_once('dbconn.php');
include('MI_score_calculation.php');

require_once('general_functions.php');



use setasign\Fpdi\Fpdi;
require_once('fpdf181/fpdf181/fpdf.php');
require_once('fpdi2/fpdi2/src/autoload.php');



// Create new Landscape PDF
$pdf = new FPDI();
// include('Second_Detail_Page.php');
// Reference the PDF you want to use (use relative path)

$pagecount = $pdf->setSourceFile('report_template/rpt_tpt_disha.pdf');

// Import the first page from the PDF and add to dynamic PDF



//Page No 1 - Report header
$tpl = $pdf->importPage(1);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
// End of Page no 1



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


//end Page no 3
//Page no 2
$pagecount = $pdf->setSourceFile('report_template/rpt_tpt_disha.pdf');
$tpl = $pdf->importPage(2);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
// End of Page no 3







//page 4
$pagecount = $pdf->setSourceFile('report_template/rpt_tpt_disha.pdf');
$tpl = $pdf->importPage(3);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);


//page 5
$pagecount = $pdf->setSourceFile('report_template/rpt_tpt_disha.pdf');
$tpl = $pdf->importPage(4);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);


//page 6
$pagecount = $pdf->setSourceFile('report_template/rpt_tpt_disha.pdf');
$tpl = $pdf->importPage(5);
$pdf->AddPage();
$pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
checking_size($logo,$pdf);
include('sdq_score_calculation.php');





/********************************** 
 * check if results are available
**********************************/
$tbl_scr = 'all_scores';
$sql = "SELECT COUNT(1) FROM ".$tbl_scr;
echo $sql."<br>";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
$total = $row[0];
echo "Printing Now<pre>";
print_r($row);

echo "Printed Now<br>";



/**********************************
 * If not stored, Put all responses in arrays
 * *******************************/
if ($total == 0)
{
    

 
$t = 'ce_pref_que_id';
$r = 'ppe_part1_test_details';
$ro = 'ce_nextmove_pref_que';
$resp = array();
$solution = array('ce_disha_part_1','ce_disha_part_2');
$tbls = array('ce_nextmove_que','ce_nextmove_pref_que');
$sol_type = array('single_choice', 'rank_ordering');



$ctr = count($solution);

/************************************************************
for($i=0;$i<$ctr;$i++){
    $resp[$solution[$i]]=array();
}
for ($i=0;$i<$ctr;$i++)

{
    $sql = "SELECT solution, qno, ans FROM ".$r." WHERE code = '".$code."' AND solution = '".$solution[$i]."'";
    echo 'SQL Query :'.$sql."<br>";
    $res = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($res)){
        array_push($resp[$solution[$i]], $row);
    }

    
}
*************************************************************/


/******************************
 *Scoring of Part 1
 *****************************/



$tbl = $tbls[0];
//echo $tbl;
$sets = array();




//'cap_que' category wise score
{
    
    //Find categories for cap_que
    $cats = array();
    $sql = "SELECT DISTINCT(cat) FROM ".$tbl." WHERE set_name = 'cap_que'";
    //echo 'SQL Query :'.$sql."<br>";
    $res = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($res))
    {
        array_push($cats, $row[0]);
    }
    
    $cat_cnt = count($cats);
    $cat_scr = array();
    echo "Categories - Part 1<pre>";
    print_r($cats);
    
    //Initialize cat score & question count
    for ($i=0;$i<$cat_cnt;$i++){
        $cat_scr[$i] =0;
        $cat_q_cnt[$i] =0;
    }
    
    //Find traits_que categories
    {
        
        $trt_cats = array();
        $trt_subcats = array();
        
    
        //Fetch categories
        $sql = "SELECT DISTINCT cat FROM ".$tbl." WHERE set_name='trait_que'"; 
        echo 'SQL Query :'.$sql."<br>";
        $res = mysqli_query($con,$sql);
        while($row = mysqli_fetch_array($res))
        {
            array_push($trt_cats,$row);
            
        } 
        
        $trt_cats_cnt = count($trt_cats);
        
        echo "<pre>";
        print_r($trt_cats);
        
        
        //Fetch sub_cats
        //Fetch categories
        $sql = "SELECT DISTINCT cat,sub_cat FROM ".$tbl." WHERE set_name='trait_que'"; 
        echo 'SQL Query :'.$sql."<br>";
        $res = mysqli_query($con,$sql);
        while($row = mysqli_fetch_array($res))
        {
            echo "<pre>";
            print_r($row);
            array_push($trt_subcats,$row);
            
        } 
        

        
        $trt_subcats_cnt = count($trt_subcats);
        
        echo "Subcategory Array :<pre>";
        print_r($trt_subcats);
        $trt_cat_cnt_scr = array();
        $trt_subcat_scr = array();
        
        for ($i=0;$i<$trt_cats_cnt;$i++)
        {
            //echo "In Loop!!"."<br>";
            //echo "<br>".$trt_cats[$i]['cat']."<br>";
            $trt_cat_cnt_scr[$i]['cat'] = $trt_cats[$i]['cat'];
            //$trt_cat_cnt_scr[$i]['count'] = 0;
            //$trt_cat_cnt_scr[$i]['max_scr']=0;
            $trt_cat_cnt_scr[$i]['score']=0;
            
        }
        
        //echo "Trait Category :<pre>";
        //print_r($trt_cat_cnt_scr);
        
        for ($i=0;$i<$trt_subcats_cnt;$i++)
        {
            $trt_subcat_scr[$i]['cat'] = $trt_subcats[$i]['cat'];
            $trt_subcat_scr[$i]['sub_cat'] = $trt_subcats[$i]['sub_cat'];
            $trt_subcat_scr[$i]['score'] =0;
            $trt_subcat_scr[$i]['max_scr']=0;
            
        }
    }
    
    echo "<pre>";
    print_r($trt_subcat_Scr);
    
    
    //Get responses
    $sql = "SELECT solution, qno, ans FROM ".$r." WHERE code = '".$code."' AND solution = '".$solution[0]."'";
    //echo 'SQL Query :'.$sql."<br>";
    $res = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($res))
    {
        $sql2 = "SELECT qno, set_name, cat,sub_cat FROM ".$tbl." WHERE qno = '".$row['qno']."'";
        //echo 'SQL Query :'.$sql2."<br>";
        $res2 = mysqli_query($con,$sql2);
        $row2 = mysqli_fetch_array($res2);
        if($row2['set_name']=='cap_que')
        {
            //echo "Yes<br>";
            for ($i=0;$i<$cat_cnt;$i++)
            {
                if($cats[$i]==$row2['cat'])
                {
                    //echo "Cat = ".$cats[$i];
                    //echo $row['ans'];
                $cat_scr[$i] += $row['ans'];
                $cat_q_cnt[$i] += 1;
                //echo "Cat Score :".$cat_scr[$i]."<br>";
                break;
                }
            }
  
        }
        else
        if($row2['set_name']=='trait_que')
        {

            for($i=0;$i<$trt_subcats_cnt;$i++)
            {
                if($trt_subcat_scr[$i]['sub_cat']==$row2['sub_cat'])
                {
                    $trt_subcat_scr[$i]['score'] += $row['ans'];
                    $trt_subcat_scr[$i]['max_scr'] +=1;
 
                }
 
        }
    }
    }
    
    echo "<pre>";
    //print_r($trt_cat_cnt_scr);
    print_r($trt_subcat_scr);
 
    //%age Score calculation & score table update
     for ($i=0;$i<$cat_cnt;$i++)
     {
         
         //score calculation
         $cat_scr[$i] = round($cat_scr[$i] *100/ $cat_q_cnt[$i]);
         
         
         //score table update
         $values = "'".$code."','".$solution[0]."','"."cap_que"."','".$cats[$i]."','".$cat_scr[$i]."'";
         $sql = "INSERT INTO ".$tbl_scr. " (code, solution, set_name, cat, score) "."VALUES (".$values.")";
         $values1 = "code='".$code."'"
                    .",solution='".$solution[0]."'"
                    .",set_name='cap_que'"
                    .",cat='".$cats[$i]."'"
                    .",score='".$cat_scr[$i]."'";
         $sql = $sql." ON DUPLICATE KEY UPDATE ".$values1;
         //echo $sql."<br>";
         
         if (!mysqli_query($con,$sql))
         {
             echo "<br>Capacity Score Insert Failed !!<br>";
         };
         
         
         
         
     }
     
     //%age score for MI & Personality
     for ($i=0;$i<$trt_subcats_cnt;$i++)
         {

                $max_score = $trt_subcat_scr[$i]['max_scr'];
                $trt_subcat_scr[$i]['score'] = round($trt_subcat_scr[$i]['score']*100/$max_score);
                
                //Insert into all_score table
                $values = "'".$code."','"
                          .$solution[0]."','"
                          ."trait_que"."','"
                          .$trt_subcat_scr[$i]['cat']."','"
                          .$trt_subcat_scr[$i]['sub_cat']."','"
                          .$trt_subcat_scr[$i]['score']."'";
                 $sql = "INSERT INTO ".$tbl_scr. " (code, solution, set_name, cat, sub_cat, score) "."VALUES (".$values.")";
                 $values1 = "code='".$code."'"
                            .",solution='".$solution[0]."'"
                            .",set_name='trait_que'"
                            .",cat='".$trt_subcat_scr[$i]['cat']."'"
                            .",sub_cat='".$trt_subcat_scr[$i]['sub_cat']."'"
                            .",sub_cat='".$trt_subcat_scr[$i]['score']."'";
                 $sql = $sql." ON DUPLICATE KEY UPDATE ".$values1;
                 echo $sql."<br>";
                 
                 if (!mysqli_query($con,$sql))
                 {
                     echo "<br>Capacity Score Insert Failed !!<br>";
                 };
                

         }

}



//'pref' category wise score
{
    
    $tbl_cat = 'ce_pref_que_id';
    $tbl_que = 'ce_nextmove_pref_que';
    $pref_cats = array();
    $pref_subcats = array();
  
  //create cat array
  $sql = "SELECT DISTINCT cat FROM ".$tbl_cat; 
    //echo 'SQL Query :'.$sql."<br>";
    $res = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($res))
    {
        array_push($pref_cats,$row);
        
    } 
    
    //echo "<pre>";
    //print_r($pref_cats);
  

  
  //create sub_cat array
  

    $sql = "SELECT DISTINCT cat, sub_cat FROM ".$tbl_cat; 
    //echo 'SQL Query :'.$sql."<br>";
    $res = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($res))
    {
        array_push($pref_subcats,$row);
        
    }  
    
    //echo "<pre>";
    //print_r($pref_subcats);
    
    //calculate cat, subcat wise score
    $pref_cat_cnt = count($pref_cats);
    
    //Initialize sub_cat score
    $pref_scr = array();
    $pref_subcats_cnt = count($pref_subcats);
       
    for ($i=0;$i<$pref_subcats_cnt;$i++)
        
    {
        $pref_scr[$i]=0;
    }
    
    
    $sql = "SELECT solution, qno, ans FROM ".$r." WHERE code = '".$code."' AND solution = '".$solution[1]."'";
    echo 'SQL Query :'.$sql."<br>";
    $res = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($res))
    {
        //echo "<pre>";
        //print_r($row);
        
        $opt = 'opt_'.$row['ans'];
        
        $sql = "SELECT $opt from ".$tbl_que. " WHERE qno = '".$row['qno']."'";
        //echo $sql."<br>";
        $res1 = mysqli_query($con,$sql);
        $row1 = mysqli_fetch_array($res1);
        
        //echo "<pre>";
        //print_r($row1[0]);
        
        //find the response cat, sub_cat;
        $sql = "SELECT * from ".$tbl_cat. " WHERE item = '".$row1[0]."'";
        //echo $sql."<br>";
        $res2 = mysqli_query($con,$sql);
        $row2 = mysqli_fetch_array($res2);
        

        
        //echo "pref_subcats<pre>";
        //print_r($pref_subcats);
        //echo "row2<pre>";
        //print_r($row2);
        
        //echo $pref_subcats_cnt."<br>";
        
        for ($i=0;$i<$pref_subcats_cnt;$i++){
            
            //echo "Into Loop!!<br>";
            
            //echo $pref_subcats[$i][sub_cat];
            if ($pref_subcats[$i]['sub_cat']==$row2['sub_cat'])
            {
                $pref_scr[$i] += 1;
            }
      
        }

    }
    
    echo "<pre>";
    print_r($pref_scr);
    
    //Insert into score database
    $cat_max_score =array();
    for ($i=0;$i<$pref_cat_cnt;$i++){
        $cat_max_score[$i]['cat'] = $pref_cats[$i][0];
        $cat_max_score[$i]['score'] =0;
    }
    
    echo "Max Category Score<pre>";
    print_r($cat_max_score);
    
    for ($i=0;$i<$pref_subcats_cnt;$i++)
        {
            for($j=0;$j<$pref_cat_cnt;$j++)
            {
                if($pref_subcats[$i]['cat']==$cat_max_score[$j]['cat'])
                {
                    
                    $cat_max_score[$j]['score'] +=$pref_scr[$i];   
                }
            }
        }
    
    for ($i=0;$i<$pref_subcats_cnt;$i++)
    {
        $max_scr = '';
        
        for($j=0;$j<$pref_cat_cnt;$j++)
            {
                if($pref_subcats[$i]['cat']==$cat_max_score[$j]['cat'])
                {
                    $max_scr = $cat_max_score[$j]['score'];   
                }
            }
        
        $values = "'".$code."','".$solution[1]."','"."pref_que"."','".$pref_subcats[$i]['cat']."','".$pref_subcats[$i][sub_cat]."','".round(100*$pref_scr[$i]/$max_scr)."'";
         $sql = "INSERT INTO ".$tbl_scr. " (code, solution, set_name, cat, sub_cat, score) "."VALUES (".$values.")";
         echo $sql."<br>";
         if (!mysqli_query($con,$sql))
         {
             echo "<br>Capacity Score Insert Failed !!<br>";
         };
        
        
        
    }
    
    
    
    
    
    
}


$sql = "SELECT * FROM ".$tbl_scr." WHERE code='".$code."'";
echo $sql."<br>";
$result = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($result))
{
    //echo "<pre>";
    //print_r($row);
    array_push($a_scr,$row);
}

//echo "<pre>";
//print_r($a_scr);


}

/**********************************
 * If result stored, use them 
 * *******************************/

else
{
   
   //Fetch all results
   $a_scr = array();
    $sql = "SELECT * FROM ".$tbl_scr." WHERE code='".$code."'";
    echo $sql."<br>";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        //echo "<pre>";
        //print_r($row);
        array_push($a_scr,$row);
    }
    
    //echo "<pre>";
    //print_r($a_scr);
   
   

   
    
}


/*
require 'SVGGraph/autoloader.php';
$graph = new Goat1000\SVGGraph\SVGGraph(640, 480);

$graph->colours(['red','green','blue']);
$graph->values(100, 200, 150);
$graph->links('/Tom/', '/Dick/', '/Harry/');
$graph->render('BarGraph');
*/






//Print to PDF
$ans_cnt = count($a_scr);
for ($i=0;$i<$ans_cnt;$i++)
{
    /*
    if($a_scr[$i]['set_name']=='cap_que')
    {
        if($a_scr['cat'] == 'Modern workplace wkills')
        {
            
        }
        if($a_scr['cat'] == 'Entreprenuerial capacity')
        {
            
        }
        if($a_scr['cat'] == 'Study Habits')
        {
            
        }
        if($a_scr['cat'] == 'Teaching')
        {
            
        }
        if($a_scr['cat'] == 'Immigration')
        {
            
        }
        
    }
    
    else
    if($a_scr[$i]['set_name']=='trait_que')
    {
        if($a_scr['cat']=='MI')
        {
           if($a_scr['sub_cat'] == 'Verbal-Linguistic')
            {
                
            } 
            
            if($a_scr['sub_cat'] == 'Logical-Mathematical')
            {
                
            }
            
            if($a_scr['sub_cat'] == 'Visual-Spatial')
            {
                
            }
            
            if($a_scr['sub_cat'] == 'Intrapersonal')
            {
                
            }
            
            if($a_scr['sub_cat'] == 'Interpersonal')
            {
                
            }
            
            if($a_scr['sub_cat'] == 'Bodily-Kinesthetic')
            {
                
            }
            
            if($a_scr['sub_cat'] == 'Naturalistic')
            {
                
            }
            
            if($a_scr['sub_cat'] == 'Musical')
            {
                
            }
            
        }
        else
        if($a_scr['cat']=='Personality')
        {
            if($a_scr['sub_cat'] == 'O')
            {
                
            }
            
            if($a_scr['sub_cat'] == 'C')
            {
                
            }
            
            if($a_scr['sub_cat'] == 'E')
            {
                
            }
            
            if($a_scr['sub_cat'] == 'A')
            {
                
            }
            
            if($a_scr['sub_cat'] == 'N')
            {
                
            }
            
            
        }
        
    }
    */
    //else
    if ($a_scr[$i]['set_name']=='pref_que')
    {
        
       if($a_scr[$i]['cat']=='Vocation')
        {
            if($a_scr[$i]['sub_cat'] == 'Job')
            {
                
            }
            
            if($a_scr[$i]['sub_cat'] == 'Entreprenuership')
            {
                
            }
            
            if($a_scr[$i]['sub_cat'] == 'Research')
            {
                
            }
            
            if($a_scr[$i]['sub_cat'] == 'Academics')
            {
                
            }
            
            
        }
        
         if($a_scr[$i]['cat']=='Location')
        {
            if($a_scr[$i]['sub_cat'] == 'Abroad')
            {
                
            }
            
            if($a_scr[$i]['sub_cat'] == 'India')
            {
                
            }
       
            
            
        }
         
        
    }
    
    
    
    
}



//Plot Bar Graph
{
/*
require('diag/diag.php');

$pdf = new PDF_Diag();
$pdf->AddPage();

$data = array('Men' => 1510, 'Women' => 1610, 'Children' => 1400);

//Pie chart

$pdf->SetFont('Arial', 'BIU', 12);
$pdf->Cell(0, 5, '1 - Pie chart', 0, 1);
$pdf->Ln(8);

$pdf->SetFont('Arial', '', 10);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Cell(30, 5, 'Number of men:');
$pdf->Cell(15, 5, $data['Men'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Number of women:');
$pdf->Cell(15, 5, $data['Women'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Number of children:');
$pdf->Cell(15, 5, $data['Children'], 0, 0, 'R');
$pdf->Ln();
$pdf->Ln(8);

$pdf->SetXY(90, $valY);
$col1=array(100,100,255);
$col2=array(255,100,100);
$col3=array(255,255,100);
$pdf->PieChart(100, 35, $data, '%l (%p)', array($col1,$col2,$col3));
$pdf->SetXY($valX, $valY + 40);


//Bar diagram
$pdf->SetFont('Arial', 'BIU', 12);
$pdf->Cell(0, 5, '2 - Bar diagram', 0, 1);
$pdf->Ln(8);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->BarDiagram(190, 70, $data, '%l : %v (%p)', array(255,175,100));
$pdf->SetXY($valX, $valY + 80);

//$pdf->Output();

*/
}
//End of Plot Bar Graph



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
  /*
   $per[$i]['val'] = $score[$i] / $cat_count[$i];
    $per[$i]['val'] = $per[$i]['val'] / $count;
    $per[$i]['cat']= $ctr[$i];
    */
}



/*

print_r($d = mi_score_level_mapper(50));
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
*
include('Remark.php');
*/


ob_end_clean();
$pdf->AliasNbPages();

$pdf->Output();
// $pdf2->Output();

ob_end_flush();


 ?>