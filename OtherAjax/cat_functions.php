<?php
    function infant_ei_calculation($con,$code)
    {
        $score = 0;
        $temp_score =0;
        $MAX_SCORE = 19.36;
        $arr_opt = ['1','2','3','4'];
        $arr_score = ['0','1','3','4'];
        $arr_score_r = ['4','3','1','0'];
        $sql = "select * from ppe_part1_test_details where code='$code' and solution='cat_part1'";
        $res = mysqli_query($con,$sql);
        while($row = mysqli_fetch_array($res))
        {
            $qno = $row['qno'];
            $ans = $row['ans'];
            $sql2 = "select * from cat_part1 where qno='$qno'";
            $res2 = mysqli_query($con,$sql2);
            $row2 = mysqli_fetch_array($res2);
            $nature = $row2['nature'];
            $fl = $row2['fl'];
            foreach($arr_opt as $opt){
               
                if($opt == $ans){
                    
                    if($nature=='F'){
                        $temp_score = $arr_score[$opt-1];
                    } else {
                         if($nature=='R'){
                       
                             $temp_score = $arr_score_r[$opt-1];
                                           
                           
            
                            }
                                    
                    }
                }
                 
                
                
            }
            $temp_score = $temp_score * $fl;
            echo "IQ Score ";
            echo $temp_score.'<br>';
            echo $score = $score + $temp_score;
            echo "<br>";
         
        }

        $score = $score/$MAX_SCORE*100;
        // echo round($score);
        return round($score);
    }

    function iq_cpm_score_calculation($con,$code)
    {
        $arr_cat = array('A', 'Ab', 'B');
        $arr_cat_score = array('0','0','0');
        $temp_score = 0;
        $score = 0;
        $arr_response =array();
        $sql = "select * from ppe_part1_test_details where code='$code' and solution='cat_part2'";
        $res = mysqli_query($con,$sql);
        while($row = mysqli_fetch_array($res))
        {
            array_push($arr_response,$row);
        }
        // $arr_response = mysqli_fetch_all($res);
        // print_r($arr_response);
        $count = count($arr_response);
        // print_r($arr_response);
        $arr_db = array();
        $sql2 = "select qno,set_no,Correct_response from cat_part2_correct_ans";
        $res2 = mysqli_query($con,$sql2);
        while($row2 = mysqli_fetch_array($res2))
        {
            array_push($arr_db,$row2);
        }
        
        echo "<br><br>";
        // print_r($arr_db);
        for($i=0;$i<$count;$i++)
        {
            if ($arr_response[$i][4] == $arr_db[$i][0]){
                 if($arr_response[$i][5]==$arr_db[$i][2])
                 {
                    if($arr_db[$i][1]==$arr_cat[0]){
                        $arr_cat_score[0] += 1;
                    }
                    else if($arr_db[$i][1]==$arr_cat[1]){
                        $arr_cat_score[1] += 1;
                    }
                    else if($arr_db[$i][1]==$arr_cat[2]){
                        $arr_cat_score[2] += 1;
                    }
                 }
            }
        }
        // echo $arr_cat_score[0].", ".$arr_cat_score[1].", ".$arr_cat_score[2]." =";
       
      $score = $arr_cat_score[0] + $arr_cat_score[1] + $arr_cat_score[2];
      echo "<br>Score in calculating Function </br>";
      echo $score;
      echo "<br>";
      //return $score;
      return $arr_cat_score;
        
    }


    function iq_cpm_score_discrpency($con,$arr_cat_score)
    {
        $discrepency =0;
        $score = $arr_cat_score[0] + $arr_cat_score[1] + $arr_cat_score[2];
        //echo($arr_cat_score);
        // echo "<br>";
        $sql = "select * from cat_score_mapping where Score='$score'";
        $res = mysqli_query($con,$sql);
        $row = mysqli_fetch_array($res);
        $fetch_score = array($row['A'],$row['Ab'],$row['B']);
        print_r($fetch_score);
        for($i=0;$i<3;$i++)
        {
            if(abs($arr_cat_score[$i]-$fetch_score[$i])>1)
            {
                $discrepency += 1;
            }
            

            
        }
        // echo $discrepency;
        return $discrepency;
       
    }

    function iq_cpm_percentile($con, $score, $dob){
        // s:
        echo "<br>Checking Errors in Percentile Calculation <br>";
        echo "Array of Score: ";
        print_r($score);
        echo "<br>";
        $score = $score[0]+$score[1]+$score[2];
        echo 'score : '.$score;
        echo $dob;
        $dob = date_create($dob);
        echo "<br>Date of Birth: ";
      
        
        $dob = date_format($dob, 'Y-m-d');
        echo $dob;
       
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dob), date_create($today));
       
        $y = $diff->format('%Y');
        
        $m = $diff->format('%m');
     
        $d = $diff->format('%d');
        
        $m = $m + $d/30;
        if ($m>=3 and $m<=9){
            $m = 6;
            $age = $y."Y".$m."M";
        } else if ($m< 3){
            $m = 0;
            $age = "0".$y."Y";
        } else if ($m >9){
            $m = 0;
            $y += 1;
            $age = "0".$y."Y";
        }
        
        echo "<br>Age: ".$age."<br>";
        $data = array();
        $sql = "select * from cat_iq_cpm_score where Age='$age' order by Percentile ASC";
        $res = mysqli_query($con,$sql);
        $num = mysqli_num_rows($res);
        while($row3 = mysqli_fetch_array($res))
        {
            array_push($data,$row3);
        }
        
        echo "<br>";
        print_r($data);
        echo "<br>";
        
        $count = count($data);
        $percentile =0;
        
       
        for ($i=0;$i<$count;$i++){
           
           if($score == $data[$i][3]){
                $percentile = $data[$i][2];
               
               break;
           } else {
               if ($score <$data[$i][3]){
                 $hs = $data[$i][3];
                 
                 $hp = $data[$i][2];
                 
                 $ls = $data[$i-1][3];
                 
                 $lp = $data[$i-1][2];
                 
                 $percentile = $lp + (($score - $ls)/($hs-$ls))*($hp - $lp);
                 
                 break;
               }
           }
        } 
        
        if ($score >$data[$count-1][3]){
            $percentile = $data[$count-1][2];
        }
        
       
        return round($percentile,2);
       
        
       
    }
?>