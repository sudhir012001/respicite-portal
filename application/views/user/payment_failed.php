<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
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

        .red {
            color: #ff4040;
        }

        .inner-1 {

            text-align: center;
            border: 2px solid #ff4040;
            padding: 41px;
            border-radius: 6px;
        }

        .inner-1 a {
            text-decoration: none;
            padding: 8px 15px;
            background: #ff4040;
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
            <i class="far fa-times-circle fa-beat red icon"></i>
            <h2 class="red">Payment Failed</h2>
            <h3>Your Order ID</h3>
            <h3 class="order-id"><?php echo $roder_id; ?></h3>
            <p>Try again</p>
            <br>
            <a href="<?php echo base_url('BaseController/purchase_code_history');?>">Go to Purchase Code History</a>
        </div>
    </div>
</body>


</html>