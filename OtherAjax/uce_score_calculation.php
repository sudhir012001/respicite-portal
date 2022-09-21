<?php 
    function uce_part3_score_calculation($code,$con)
    {
        $cat = array('Enterprising','Social','Conventional');
        $count = count($cat);
        for($i=0;$i<$count;$i++)
        {
            $per[$i]=0;
            $score[$i]=0;
            $num[$i]=0;
        }
        $sql = "select * from uce_part2_7";
        $res = mysqli_query($con,$sql);
        while($row = mysqli_fetch_array($res))
        {
            $qno = $row['qno'];
            $category = $row['category'];
            $sql2 = "select * from ppe_part1_test_details where code='$code' and solution='uce_part2_7' and qno='$qno'";
            $res2 = mysqli_query($con,$sql2);
            $row2 = mysqli_fetch_array($res2);
            $ans = $row2['ans'];
            if($ans==1)
            {
                $temp_ans = 1;
            }
            else
            {
                $temp_ans = 0;
            }
            $i = 0;
            foreach($cat as $ct)
            {
                if($ct==$category)
                {
                   
                    $score[$i] = $score[$i] + $temp_ans;
                    $num[$i] = $num[$i] + 1;
                }
                $i = $i +1;
            }
        }
        $per_2_7 = array();
        $per_2_7[0] = $score[0]/$num[0] * 100;
        $per_2_7[1] = $score[1]/$num[1] * 100;
        $per_2_7[2] = $score[2]/$num[2] * 100;
        return $per_2_7;
    }
?>