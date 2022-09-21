<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
 <!-- Font Awesome -->
 <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/fontawesome-free/css/all.min.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/dist/css/adminlte.min.css'; ?>">
  <link rel="stylesheet" href="<?php echo base_url('/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
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
<body class="hold-transition login-page" style="background-image: url(<?php echo base_url('/assets/bg.svg'); ?>);background-repeat: no-repeat;
  background-attachment: fixed; background-size: cover;">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card shadow">
    <div class="card-header text-center bg-color-1 ">
      <div class="brand-logo m-0 p-1">
        <img src="<?php echo base_url('/assets/b-logo.png'); ?>" style="width: 11rem;margin-right: 16px;">
      </div>
    </div>
    <div class="card-body">
      <p class="login-box-msg text-secondary">Sign in to start your session</p>
      <?php 
        $msg = $this->session->flashdata('msg');
        if($msg !="")
        {
  ?>     
    <div class="alert alert-danger">
            <?php echo $msg; ?>
    </div>
    <?php 
}
$msg2 = $this->session->flashdata('msg2');
if($msg2 !="")
{
?>     
<div class="alert alert-success">
    <?php echo $msg2; ?>
</div>
<?php 
}
?>  
      <form action="" method="post">
        <div class="input-group mb-3">
        <?php 
          $ref_data =  $this->session->userdata("ref_data");
          $ref_user_id = "";
          if(!empty($ref_data)){
            $ref_user_id = $ref_data['user_id'];
          } ?>
          <input type="email" class="form-control <?php echo (form_error('email') != '') ? 'is-invalid' : ''; ?>" name="email" value="<?php echo (set_value('email'))?set_value('email'):$ref_user_id; ?>" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text text-success">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          <div class="invalid-feedback"><?php echo strip_tags(form_error('email')); ?></div>
        </div>
        <div class="input-group mb-3">
         
          <input type="password" class="form-control <?php echo (form_error('password') != '') ? 'is-invalid' : ''; ?>" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text text-success">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <div class="invalid-feedback"><?php echo strip_tags(form_error('password')); ?></div>
        </div>
        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" name="loginbtn" id="loginbtn" class="btn bg-color-1 text-white btn-block" style="font-size: 1.5em;">Log In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      

      <p class="mb-1 mt-2 ">
        <a class="text-secondary" href="<?php echo base_url('UserController/forgot_password'); ?>">Forgot Password</a>
      </p>
      <p class="mb-0">
        <a href="<?php echo base_url('UserController/registration'); ?>" class="text-secondary text-center">Register a new membership</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('/assets/dist/js/adminlte.min.js'); ?>"></script>
</body>
</html>