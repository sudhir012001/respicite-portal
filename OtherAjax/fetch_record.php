<?php
//Include database connection
if($_POST['rowid']) {
    $id = $_POST['rowid']; //escape string
    // Run the Query
    // Fetch Records
    // Echo the data you want to show in modal
   include('dbconn.php');
   $sql2 = "select * from user_code_list where id='$id'";
   $res2 = mysqli_query($con,$sql2);
   $row2 = mysqli_fetch_array($res2);
   $solution = $row2['solution'];
   $code = $row2['code'];
 }

?>
<center><h3><?php echo $solution; ?></h3></center>
<table border="1" style="padding:10px;">
<tr>
<!-- <th>Label</th> -->
<th>Part</th>
<th>Details</th>
<th>Action</th>
</tr>
<?php
   
    $sql = "select * from services_list where solution='$solution'";
    $res = mysqli_query($con,$sql);
    while($row=mysqli_fetch_array($res))
    {
        ?>
            
            <tr>
            <!-- <td>
            <center><?php echo $row['label']; ?></center>
            </td> -->
            <td>
            <center><?php echo $row['part']; ?></center>
            </td>
           
            <td>
            <?php echo $row['description']; ?>
            </td>
            <td>
            <center><a href="<?php 
            if($row['current_link']!='')
            {
                echo $row['current_link'].'/'.base64_encode($code);
            }
            else
            {
                echo $row['linl'];
            }
             ?>"><b>Visit</b></a></center>
            </td>
            </tr>
           
          
        <?php
    }
?>
 </table>   