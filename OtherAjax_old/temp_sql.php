<?php 
include('dbconn.php');
    for($i=76;$i<=84;$i++)
    {
        $sql = "insert into ppe_part1_test_details(email,solution,code,qno,ans)
        values('sudhir012001@gmail.com','qce_part2','AWSW728468','$i','1')";
        mysqli_query($con,$sql);
    }
?>