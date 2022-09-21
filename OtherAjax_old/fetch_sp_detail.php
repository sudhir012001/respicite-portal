<?php
//Include database connection
if($_POST['rowid']) {
    $id = $_POST['rowid']; //escape string
    // Run the Query
    // Fetch Records
    // Echo the data you want to show in modal
   include('dbconn.php');
   
   $email = $id;
   $sql = "select * from user_details where email='$email'";
   $res = mysqli_query($con,$sql);
   while($row = mysqli_fetch_array($res))
   {    
        $name = $row['fullname'];;
        $mobile = $row['mobile'];
   }
   
   $sql2 = "select * from sp_profile_detail where email='$email'";
   $res2 = mysqli_query($con,$sql2);
   $row2 = mysqli_fetch_array($res2);
   $key_services = $row2['key_services'];
   $about_us = $row2['about_us'];
   $address = $row2['address'];
 }

?>
 <div class="card ">
              
          <div class="card-header">
                <h3 class="card-primary card-title"><strong> <?php echo $name; ?></strong></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong> Email</strong>

                <p class="text-muted">
                <?php echo $email; ?>                </p>

                <hr>

                <strong> Contact</strong>

                <p class="text-muted"><?php echo $mobile; ?></p>

                <hr>

                <strong> About Us</strong>

                <p class="text-muted">
                <?php echo $about_us; ?><br>
                  <span class="tag tag-success"><?php echo $key_services; ?></span><br>
                  <span class="tag tag-info"><?php echo $address; ?></span></p>
                  <hr>
                  <strong> Links</strong>
                  <p class="text-muted">
                    <a href="<?php echo $row2['fb_link']; ?>"><i class="fab fa-facebook-square fa-2x"></i></a>
                    <a href="<?php echo $row2['twitter_link']; ?>"><i class="fab fa-twitter-square fa-2x"></i></a>
                    <a href="<?php echo $row2['insta_link']; ?>"><i class="fab fa-instagram-square fa-2x"></i></a>
                    <a href="<?php echo $row2['linke_link']; ?>"><i class="fab fa-linkedin fa-2x"></i></a>
                


                </p>

                <hr>

                <strong>Price</strong><br>
                <?php 
                    echo "<table border='1'>";
                    $sql3 = "select * from provider_detail_four where email='$email'";
                    $res3 = mysqli_query($con,$sql3);
                    while($row3 = mysqli_fetch_array($res3))
                    {
                        echo "<tr><td>";
                        $l1 = $row3['l1'];
                        $l2 = $row3['l2'];
                        $l3 = $row3['l3_id'];
                        $sql4 = "select * from provider_level_one where id='$l1'";
                        $res4 = mysqli_query($con,$sql4);
                        $row4 = mysqli_fetch_array($res4);
                        echo $row4['l1'].'->';
                        $sql5 = "select * from provider_level_two where id='$l2'";
                        $res5 = mysqli_query($con,$sql5);
                        $row5 = mysqli_fetch_array($res5);
                        echo $row5['l2'].'->';
                        $sql6 = "select * from provider_level_three where id='$l3'";
                        $res6 = mysqli_query($con,$sql6);
                        $row6 = mysqli_fetch_array($res6);
                        echo $row6['l3'].'<br>';
                        $sql7 = "select * from provider_level_four where l1='$l1' and l2='$l2' and l3_id='$l3'";
                        $res7 = mysqli_query($con,$sql7);
                        $row7 = mysqli_fetch_array($res7);
                        echo $row7['param_one'].'('.$row3['p1'].') ';
                        echo $row7['param_two'].'('.$row3['p2'].') ';
                        echo $row7['param_three'].'('.$row3['p3'].') ';
                        echo $row7['param_four'].'('.$row3['p4'].') <br>';
                        echo "<div align='right'><b>";
                        echo 'Price :'.$row3['price'];
                        echo "</b></div>";
                        echo "</td></tr>";
                    }
                    echo "</table>";
                ?>
                
              </div>
              <!-- /.card-body -->
            </div>
