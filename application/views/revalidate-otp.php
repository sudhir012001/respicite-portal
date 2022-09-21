<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verify Email by OTP</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/fontawesome-free/css/all.min.css'; ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/dist/css/adminlte.min.css'; ?>">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <h3>Verify Email ID</h3>
    </div>
    <div class="card-body">
      

      <?php 
        $form_hide = false;
        if(!empty($this->session->flashdata("otp_msg"))){
      
        if($this->session->flashdata("otp_msg") == "OK"){ $form_hide = true; ?>
            <div class="alert alert-success pb-0"><p>Your email has been verified successfully. You can now login into your account. <a href="<?php echo base_url("UserController/login");?>">Click here to Login</a> </p></div>
      <?php }elseif($this->session->flashdata("otp_msg") == "INVELID_OTP"){ ?>
          <div class="alert alert-danger pb-0"><p>Invalid OTP</p></div>
      <?php } }?>
      
      <?php if(!$form_hide){?>
      <div class="alert alert-success pb-0 login-box-msg"> 
        <p>An OTP has been successfully sent to your email verification.</p>
      </div>
      <div class="text-center mt-2 mb-2">
          <button class="btn btn-xs btn-success" id="brn_re_send_otp">Re-send OTP</button>
      </div>
            <!-- <div class="alert alert-success pb-0 resend-msg" style="display:none"><p>A new OTP has been resent on your registered email id.</p></div>
            <div class="alert alert-danger pb-0 already-msg" style="display:none"><p>This email id has already been verified. You can login using this email id.</p></div> -->
      <form action="" method="post">
        
        <div class="input-group mb-3">
          <input type="text" disabled class="form-control disabled" value="<?php echo $this->session->userdata("reverify_email_id")['email'];?>">
          <div class="input-group-append">
            <div class="input-group-text">
            <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <?php echo form_error("otp");?>
        <div class="input-group mb-3">
          <input type="text" required class="form-control" value="<?php echo set_value('otp');?>" name="otp" placeholder="Enter OTP">
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
            <button type="submit" name="btn_validate_otp" value="btn_validate_otp" class="btn btn-primary btn-block">Validate OTP</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <?php }?>
  
    
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
<script>
    $("#brn_re_send_otp").click(function(){
        var URL = "<?php echo base_url("UserController/ajax_work");?>"
        $.post(URL, {action:"RESEND_OTP",email_id: "<?php echo $this->session->userdata("reverify_email_id")['email'];?>"}, function(res){               
              if(res.msg_code == "OTP_RESEND"){
                /* $(".already-msg").hide();
                $(".resend-msg").show(); */
                $(".login-box-msg p").text("A new OTP has been resent on your registered email id.")
              }
              if(res.msg_code == "ALREADY_UPDATE_STATUS"){
                /* $(".already-msg").show();
                $(".resend-msg").hide(); */
                $(".login-box-msg p").text("This email id has already been verified. You can login using this email id.")
              }            
           });
    })
</script>

</body>
</html>
