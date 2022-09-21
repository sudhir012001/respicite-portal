<?php 
include('dbconn.php');

    $part_name = array('uce_part1_1',
    'uce_part1_2',
    'uce_part1_4_1',
    'uce_part1_4_2',
    'uce_part1_5',
    'uce_part2',
    'uce_part2_2',
    'uce_part2_3',
    'uce_part2_4',
    'uce_part2_5',
    'uce_part2_6',
    'uce_part2_7'
);


    $part_q_count = array(27,60,26,24,16,18,19,20,40,90,42,35);
    $part_opt_count =array(4,4,2,2,4,5,6,4,5,2,4,2);


    $val_rank_ord =array(21,5,5);

     $test_code = "AWSW377105";
    $test_email = "sudhir012001@gmail.com";

    $user_profile = array(
    'Test User',
    '26-04-2013',
    '8',
    '8th',
    'student',
    'class',
    'male',
    '8585858585',
    'bijju@gmail.com',
    'delhi');

    $update_sql = "update user_code_list set name='$user_profile[0]',
    dob='$user_profile[1]',
    cls='$user_profile[2]',
    cls_detail='$user_profile[3]',
    cls_type='$user_profile[4]',
    gender='$user_profile[5]',
    mobile='$user_profile[6]',
    email='$user_profile[7]',
    address='$user_profile[8]' where code='$test_code'";
    mysqli_query($con,$update_sql);

    $del_sql = "delete from ppe_part1_test_details where email='$test_email' and code='$test_code'";
    mysqli_query($con,$del_sql);

    $del_sql = "delete from wls_part2_rank_ordring where email='$test_email' and code='$test_code'";
    mysqli_query($con,$del_sql);
    $solution  = array('uce_part1','uce_part2','uce_part3');
    foreach($solution as $s)
    {
        $update_sql = "update user_assessment_info set status='Ap' where code='$test_code' and email='$test_email' and part='$s'";
        mysqli_query($con,$update_sql);
    }

    $update_sql = "update user_code_list set status='Ap' where code='$test_code' and email='$test_email'";
    mysqli_query($con,$update_sql);
    $count = count($part_name);
    for($i=0; $i<$count;$i++)
    {
        for($j=1;$j<=$part_q_count[$i];$j++)
        {
            $x = rand(1,$part_opt_count[$i]);
            $sql = "insert into ppe_part1_test_details(email,qno,solution,code,ans) values('$test_email','$j','$part_name[$i]','$test_code','$x')";
            mysqli_query($con,$sql);
        } 
    }
  
    $del_sql = "delete * from wls_part2_rank_ordring where email='$test_email' and code='$test_code'";
    mysqli_query($con,$del_sql);
    
    for($i=1;$i<=$val_rank_ord[0];$i++)
    {
        $array_n = array();
        for($j=1;$j<=$val_rank_ord[1];$j++)
        {
            S:
            $x = rand(1,$val_rank_ord[2]);
            if( in_array( $x ,$array_n ) )
            {
                goto S;
            }
            else
            {
                
                $sql = "insert into wls_part2_rank_ordring(email,solution,code,grp,qno,ordr) values('$test_email','uce_part1_3','$test_code','$i','$j','$x')";
                mysqli_query($con,$sql);
                array_push($array_n,$x); 
            }
           
        }
    }
    $solution  = array('uce_part1','uce_part2','uce_part3');
    foreach($solution as $s)
    {
        $update_sql = "update user_assessment_info set status='Rp' where code='$test_code' and email='$test_email' and part='$s'";
        mysqli_query($con,$update_sql);
    }
    $update_sql = "update user_code_list set status='Rp' where code='$test_code' and email='$test_email'";
    mysqli_query($con,$update_sql);
    
    echo("completed");
?>