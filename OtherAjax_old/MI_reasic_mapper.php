<?php 
  
    function MI_reasic_mapper($code,$con)
    {

        $ctr = array();
      
        $sql_ctr = "select distinct riasec_map from qce_part1_question where riasec_map!='TBD'";
        $res_ctr = mysqli_query($con,$sql_ctr);
        while($row_ctr = mysqli_fetch_array($res_ctr))
        {
        
            array_push($ctr,$row_ctr['riasec_map']);
        
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
            if($ans==1)
            {
                $ans = 1;
            }
            else if($ans==2)
            {
                $ans = 0;
            }
            $check_category = "select * from qce_part1_question where qno='$qno'";
            $res_check_category = mysqli_query($con,$check_category);
            $row_check_category = mysqli_fetch_array($res_check_category);
            $category = $row_check_category['riasec_map'];
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
            echo $score[$i]." / ".$cat_count[$i]."<br>";
            $per[$i]['name'] = $ctr[$i];
            $per[$i]['score'] = $score[$i] / $cat_count[$i] * 100;
            // $per[$i]['val'] = $per[$i]['val'] / $count;
            $per[$i]['cat']= $ctr[$i];
            echo $per[$i]['score']."<br>";
        }

        
        return($per);

        // return $array_interest = array('60','50','45','80','70','40');

    }
    function riasec_top_three($per,$con,$code)
    {
        $count = count($per);
        $del_sql = "delete from top_value_db where code='$code' and solution='qce_part2'";
        mysqli_query($con,$del_sql);
        $get_score1 = "select * from top_value_db where code='$code' and solution='qce_part2'";
        $res_score = mysqli_query($con,$get_score1);
        $num = mysqli_num_rows($res_score);
        if($num==0)
        {
            for($i=0;$i<$count;$i++)
            {
                
                $sc = $per[$i]['score'];   
                $cat = $per[$i]['cat'];     
                $sql_insert = "insert into top_value_db(solution,code,category,per) values('qce_part2','$code','$cat','$sc')";
                mysqli_query($con,$sql_insert);
            }
        }
        $top_cat = array();
        
        $get_score2 = "select * from top_value_db where code='$code' and solution='qce_part2' order by per DESC limit 3";
        $res_score2 = mysqli_query($con,$get_score2);
        while($row_score2 = mysqli_fetch_array($res_score2))
        {
            array_push($top_cat,$row_score2['category']);
        }
        return $top_cat;
    }

    function int_code_name_mapper($t)
    {
        $int_codes = array('R','I','A','S','E','C');
        $int_names = array('Realistic', 'Investigative','Artistic','Social', 'Enterprising','Conventional');
        $i=0;
        foreach($int_codes as $int_code){
            if($int_code == $t){
                return($int_names[$i]);
            } else {
                $i = $i + 1;
            }
        }
    }

    function interest_mapper($con,$cd,$S1,$S2,$S3,$J1,$J2,$J3,$code)
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
       
       
        // $available = 0;
        // if ($J1 == $S1 || $J1 == $S2 || $J1 == $S3){
        //     $availble = 1;
        // } else
        // {
        //     $J1 ="X";
        // }
        // if ($J2 == $S1 || $J2 == $S2 || $J2 == $S3){
        //     $available = 1;
        // } else 
        // {
            
            
        //     $J2 = "X";
        // }
        // if ($J3 == $S1 || $J3 == $S2 || $J3 == $S3)
        // {
        //     $available = 1;
        // } else {
        //     $J3 = "X";
        // }
       



        // $match="";



        // if ($J1 == $S1)
        // {
        //     if ($J2 == $S2)
        //     {
        //         if ($J3 == $S3){
        //          $match = $match_array[7];
        //         } else 
        //         if ($J3 == "NA") {
        //             $match = $match_array[4];
        //         } else 
        //         {
        //             $match = $match_array[3]; 
        //         }

        //     } 
        //     else if ($J2 == $S3)
        //     {
        //         if ($J3 == $S2){
        //             $match = $match_array[7];
        //           } else 
        //           if ($J3 == "NA") {
        //               $match = $match_array[7];
        //           } else 
        //           {
        //               $match = $match_array[6]; 
        //           }
        //     } 
        //     else if($J2 == "NA")
        //     {
        //         $match = $match_array[6];    
        //     } 
        //     else 
        //     {
        //         if ($J3 == $S2){
        //             $match = $match_array[3];
        //           } else 
        //           if ($J3 == $S3) {
        //               $match = $match_array[3];
        //           } 
        //           else 
        //           if ($J3 == "NA") {
        //               $match = $match_array[4];
        //           } else  
        //           {
        //               $match = $match_array[2]; 
        //           }  
        //     }
        // } 
        // else if ($J1 == $S2)
        // {
        //     if ($J2 == $S1)
        //     {
        //         if ($J3 == $S3){
        //          $match = $match_array[7];
        //         } else 
        //         if ($J3 == "NA") {  
        //             $match = $match_array[3];
        //         } else 
        //         {
        //             $match = $match_array[3]; 
        //         }

        //     } 
        //     else if ($J2 == $S3)
        //     {
        //         if ($J3 == $S1)
        //         {
        //             $match = $match_array[5];
        //         } 
        //         else if ($J3 == "NA") 
        //         {
        //               $match = $match_array[2];
        //         } 
        //         else 
        //         {
        //               $match = $match_array[2]; 
        //         }
        //     } 
        //     else if($J2 == "NA")
        //     {
        //         $match = $match_array[1];    
        //     } 
        //     else 
        //     {
        //         if ($J3 == $S1){
        //             $match = $match_array[2];
        //           } else 
        //           if ($J3 == $S2) {
        //               $match = $match_array[2];
        //           } 
        //           else 
        //           if ($J3 == "NA") {
        //               $match = $match_array[2];
        //           } else  
        //           {
        //               $match = $match_array[1]; 
        //           }  
        //     }
        // } 
        // else if($J1 == $S3){
        //     if ($J2 == $S1)
        //     {
        //         if ($J3 == $S2){
        //          $match = $match_array[5];
        //         } else 
        //         if ($J3 == "NA") {  
        //             $match = $match_array[2];
        //         } else 
        //         {
        //             $match = $match_array[2]; 
        //         }

        //     } 
        //     else if ($J2 == $S2)
        //     {
        //         if ($J3 == $S1)
        //         {
        //             $match = $match_array[7];
        //         } 
        //         else if ($J3 == "NA") 
        //         {
        //               $match = $match_array[2];
        //         } 
        //         else 
        //         {
        //               $match = $match_array[2]; 
        //         }
        //     } 
        //     else if($J2 == "NA")
        //     {
        //         $match = $match_array[1];    
        //     } 
        //     else 
        //     {
        //         if ($J3 == $S1){
        //             $match = $match_array[2];
        //           } else 
        //           if ($J3 == $S2) {
        //               $match = $match_array[2];
        //           } 
        //           else 
        //           if ($J3 == "NA") {
        //               $match = $match_array[1];
        //           } else  
        //           {
        //               $match = $match_array[0]; 
        //           }  
        //     }
        // } 
        // else if($J1 == "X")
        // {
        //     if ($J2 == $S1)
        //     {
        //         if ($J3 == $S2){
        //          $match = $match_array[2];
        //         } else 
        //         if ($J3 == "NA") {  
        //             $match = $match_array[2];
        //         } 
        //         else 
        //         if ($J3 == $S3) {  
        //             $match = $match_array[2];
        //         } else 
        //         {
        //             $match = $match_array[1]; 
        //         }

        //     } 
        //     else if ($J2 == $S2)
        //     {
        //         if ($J3 == $S1)
        //         {
        //             $match = $match_array[3];
        //         } 
        //         else if ($J3 == $S3) 
        //         {
        //               $match = $match_array[3];
        //         } 
        //         else if ($J3 == "NA") 
        //         {
        //               $match = $match_array[2];
        //         } 
        //         else 
        //         {
        //               $match = $match_array[1]; 
        //         }
        //     } 
        //     else if ($J2 == $S3)
        //     {
        //         if ($J3 == $S1)
        //         {
        //             $match = $match_array[2];
        //         } 
        //         else if ($J3 == $S2) 
        //         {
        //               $match = $match_array[2];
        //         } 
        //         else if ($J3 == "NA") 
        //         {
        //               $match = $match_array[1];
        //         } 
        //         else 
        //         {
        //               $match = $match_array[0]; 
        //         }
        //     } 
        //     else if($J2 == "NA")
        //     {
        //         $match = $match_array[1];    
        //     } 
        //     else 
        //     {
        //         if ($J3 == $S1){
        //             $match = $match_array[1];
        //           } else 
        //           if ($J3 == $S2) {
        //               $match = $match_array[1];
        //           } 
        //           else 
        //           if ($J3 == $S3) {
        //               $match = $match_array[0];
        //           } 
        //     }
        // }
       echo $match;
       echo "<br>";
        return $match;
    }

?>