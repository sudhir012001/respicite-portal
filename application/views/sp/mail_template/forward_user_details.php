<?php 


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forward</title>
    <style>
       
        body{
            
            font-size:1.5rem;
            font-family: system-ui;
        }
        .main{
            width:35rem;
            margin:0 auto;
            border:2px solid white;
            border-radius:5px;
            margin-top:5rem;
            border:2px solid black;
            color:black;
        }

        .main hr{
            height: 0;
            border: none;
            border-bottom: 1px solid white;
        }

        .logo{
            width:10rem;    
            padding:5px;        
        }

        .logo img{
            width:100%;
        }
        .t-box{
            background-color:#fc9928;
            padding:10px;
            width: 100%;
        }

        .t-box div{
            margin: 10px 3px 10px 3px;
        }

        .btn-cv{
            padding: 3px 9px;
            color: black;
            text-decoration: none;
            font-size: 0.8em;
            border: 2px solid white;
            border-radius: 5px;
            margin:5px;
        }
        
        .heading{
            text-align: center;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
    <?php $sp = $this->session->userdata('user');?>
    <div class="main">
            <div class="logo">
                <img src="https://respicite.com/assets/images/respicite279-88.png">
            </div>
        <table class="t-box">
            <tr>
                <td colspan="2"><div class="heading">Service Provider Details</div></td>
            </tr>
            <tr>
                <td><div>Name</div></td>
                <td><div><?php echo $sp['fullname'];?></div></td>
            </tr>
            <tr>
                <td><div>Email ID</div></td>
                <td><div><?php echo $sp['email'];?></div></td>
            </tr>
            <tr>
                <td><div>Post Job</div></td>
                <td><div><a class="btn-cv" href='<?php echo "https://respicite.com/job-details.php?jid=".base64_encode($user_details->job_id);?>'> Click here </a></div></td>
            </tr>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            <tr>
                <td colspan="2"><div class="heading">User job request details</div></td>
            </tr>
            <tr>
                <td><div>Name</div></td>
                <td><div><?php echo $user_details->fullname?></div></td>
            </tr>
            <tr>
                <td><div>User Email</td>
                <td><div><?php echo $user_details->user_email?></div></td>
            </tr>
            <tr>
                <td><div>Apply Date</div></td>
                <td><div><?php echo $user_details->apply_date?></div></td>
            </tr>
            <tr>
                <td><div>Job Status</div></td>
                <td><div><?php echo $user_details->job_status?></div></td>
            </tr>
            <tr>
                <td><div>Download cv</div></td>
                <td><div><a class="btn-cv" href='<?php echo base_url("uploads/jobs_cv/".$user_details->cv_path)?>'> Click here </a></div></td>
            </tr>
        </table>
    </div>

</body>
</html>