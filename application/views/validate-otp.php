<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registration Page</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/fontawesome-free/css/all.min.css'; ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/dist/css/adminlte.min.css'; ?>"
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <h1><b></b>Enter OTP</h1>
    </div>
    <div class="card-body">
      <p class="login-box-msg">OTP has been successfully sent to your email</p>
      <?php
                $msg = $this->session->flashdata('msg');
                if($msg != "")
                {
                    echo "<div class='alert alert-danger'>$msg</div>";
                }
                
            ?>
      <form action="" method="post">
        <!--<form action="https://users.respicite.com/UserController/validate_otp" method="post">-->
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="otp" placeholder="Enter OTP">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-key"></span>
            </div>
          </div>
         
        </div>
          <!-- /.col -->
          <div class="row">
          <div class="col-6">
           
          </div>
          <!-- /.col -->
          <div class="col-6">
            <button type="submit" name="validate-btn" class="btn btn-primary btn-block">Validate OTP</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      
      <!--
      <div class="social-auth-links text-center">
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i>
          Sign up using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i>
          Sign up using Google+
        </a>
      </div>
      -->
      
    
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('/assets/dist/js/adminlte.min.js'); ?>"></script>
</body>
</html>
