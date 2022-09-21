<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registration</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/fontawesome-free/css/all.min.css'; ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/dist/css/adminlte.min.css'; ?>">
  <link href="<?php echo base_url('/assets/favicon.png');?>" rel="shortcut icon" type="image/png">
  <style>    
    .bg-color-1{
      background-color: #fc9928;
    }
    .text-color-1{
      color: #fc9928;
    }  
  </style>
</head>
<body class="hold-transition register-page"  style="background-image: url(<?php echo base_url('/assets/bg.svg'); ?>);background-repeat: no-repeat;
  background-attachment: fixed; background-size: cover;">
<div class="register-box">
  <div class="card card-outline">
  <div class="card-header text-center bg-color-1 ">
      <div class="brand-logo m-0 p-1">
      <?php 
     
      if(!empty($sp_logo)){
        $path_logo = base_url($sp_logo['logo']);
        if(!file_exists($path_logo))
        echo "<img src='".$path_logo."' style='width: 6rem;margin-right: 16px;'>";
        else
        echo "<img src='".base_url('/assets/b-logo.png')."' style='width: 11rem;margin-right: 16px;'>";
      }else{
        echo "<img src='".base_url('/assets/b-logo.png')."' style='width: 11rem;margin-right: 16px;'>";
      }      
      ?>
        
      </div>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new membership</p>
      <?php
                $msg = $this->session->flashdata('msg');
                if($msg != "")
                {
                    echo "<div class='alert alert-success'>$msg</div>";
                }
            ?>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" name="full_name" value="<?php echo set_value('full_name'); ?>" class="form-control <?php echo (form_error('full_name')!="") ? 'is-invalid' : ''; ?>" placeholder="Full name">
         
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user text-success"></span>
            </div>
  
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('full_name')); ?></p>
        </div>
        
        <div class="input-group mb-3">
          <input type="email" class="form-control <?php echo (form_error('email')!="") ? 'is-invalid' : ''; ?>" name="email" value="<?php echo set_value('email'); ?>" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope text-success"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('email')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="tel" class="form-control <?php echo (form_error('mobile')!="") ? 'is-invalid' : ''; ?>" name="mobile" value="<?php echo set_value('mobile'); ?>" placeholder="Mobile No.">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone text-success"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('mobile')); ?></p>
        </div>
        
        <input type="hidden" name="code" value="<?php echo $code; ?>">
        
        <div class="input-group mb-3">
          <input type="password" class="form-control <?php echo (form_error('password')!="") ? 'is-invalid' : ''; ?>" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock text-success"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('password')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control <?php echo (form_error('cpassword')!="") ? 'is-invalid' : ''; ?>" name="cpassword" placeholder="Retype password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock text-success"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('cpassword')); ?></p>
        </div>
        <div class="row">
          <div class="col-8 mb-2">
            <div class="icheck-primary">
              <input type="checkbox" id="terms" name="terms" class="form-control <?php echo (form_error('terms')!="") ? 'is-invalid' : ''; ?>" value="agree">
              <label for="terms" class="text-secondary">
               I agree to the <a href="https://respicite.com/terms-and-conditions.php" class="text-secondary" target="_black"><strong class="text-primary"> terms </strong></a>
              </label>
            </div>
            <p class="invalid-feedback"><?php echo strip_tags(form_error('terms')); ?></p>
          </div>
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" name="regbtn" style="font-size: 1.5em;" class="btn bg-color-1 text-white btn-block mb-2">Register</button>
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
      
      <a href="<?php echo base_url('BaseController/login/'.base64_encode($code)); ?>" class="text-center text-secondary">I already have a membership</a>
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
