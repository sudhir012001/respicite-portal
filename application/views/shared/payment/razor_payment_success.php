<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successfully</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        .main {
            width: 23rem;
            margin: 0 auto;
            padding-top: 5rem;
        }

        .icon {
            font-size: 7em;
        }

        .green {
            color: #07c107;
        }

        .inner-1 {

            text-align: center;
            border: 2px solid #07c145;
            padding: 41px;
            border-radius: 6px;
        }

        .inner-1 a {
            text-decoration: none;
            padding: 8px 15px;
            background: #ff8100;
            color: white;
            border-radius: 24px;
        }

        .order-id{
            border: 1px dashed;
            padding: 8px;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="inner-1">
            <i class="far fa-check-circle icon fa-beat green"></i>
            <h2 class="green">Payment Successfully</h2>
            <h3>Your Order ID</h3>
            <h3 class="order-id"><?php echo $roder_id; ?></h3>
            <p>Thank you for purchasing</p>
            <br>
            <a href="<?php echo base_url('BaseController/counselingParameters');?>">Go to Counseling Parameters View</a>
        </div>
    </div>
</body>


</html>