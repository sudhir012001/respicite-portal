<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
    />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="<?php echo base_url().'/assets/plugins/fontawesome-free/css/all.min.css'; ?>"
    />
    <!-- Theme style -->
    <link
      rel="stylesheet"
      href="<?php echo base_url().'/assets/dist/css/adminlte.min.css'; ?>"
    />
    <link
      rel="stylesheet"
      href="<?php echo base_url('/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>"
    />
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

  <body
    class="hold-transition login-page"
    style="background-image: url(<?php echo base_url('/assets/bg.svg'); ?>);background-repeat: no-repeat;
  background-attachment: fixed; background-size: cover;"
  >
    <div class="login-box">
      <div class="card">
      <!-- Forgot Password #START -->
      <div class="forgot-password"> 
        <div class="card-header text-center text-white bg-color-1">
        <div class="brand-logo m-0 p-1">
                   <img src="<?php echo base_url('/assets/b-logo.png'); ?>" style="width: 11rem;margin-right: 16px;">
                  </div>
          <p class="m-0">Forgot Password</p>
        </div>
        <div class="card-body">
        
        <!-- message -->
        <div class="alert alert-danger pb-0 show-msg" style="display:none;"><p>You did not select a file to upload.</p></div>
        <!-- message -->


          <form action="" method="post" id="forgot_pass">
            <div class="input-group mb-3">
              <input
                type="email"
                class="form-control"
                name="email_id"
                value=""
                placeholder="Email"
                required
              />
              <div class="input-group-append">
                <div class="input-group-text text-success">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
              <div class="invalid-feedback"></div>
            </div>

            <div class="row">
              <div class="col-12 mb-2">
                <button
                  style="font-size: 1.5em;"
                  type="submit"
                  class="btn bg-color-1 text-white btn-block"
                >
                Reset Password
                </button>
              </div>
              <!-- /.col -->
            </div>
          </form>
          <p class="mb-1">
            <a href="<?php echo base_url('UserController/login');?>" class="text-secondary">Login</a>
         </p>
         <p class="mb-0">
        <a href="<?php echo base_url('UserController/registration');?>" class="text-center text-secondary">Register a new membership</a>
        </p>
        </div>
      </div>
      <!-- Forgot Password #END -->
      <div class="create-password" style="display:none;">
                <div class="card-header text-center bg-color-1 text-white">
                  <div class="brand-logo m-0 p-1">
                   <img src="<?php echo base_url('/assets/b-logo.png'); ?>" style="width: 11rem;margin-right: 16px;">
                  </div>
                <p class="m-0">Create a new password</p>
                </div>
                <div class="card-body ">
                  <!-- message -->
                  
                  <div class="alert alert-success pb-0 after-success-reset"><p class="text-center">OTP is sent your Email ID.</p></div>
                  <div class="alert alert-danger pb-0 show-msg-2" style="display:none;"><p></p></div>
                  <!-- message -->
                  <form action="" method="post" class="after-success-reset" id="create_new_pass" >
                    <div class="input-group mb-3">
                    <input
                      type="email"
                      class="form-control disabled"
                      disabled
                      name="email_otp"
                      autocomplete="off"
                    />
                    </div>
                    <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control required"
                        name="otp_code"
                        placeholder="OTP"
                        required
                        autocomplete="off"
                    />
                    </div>
                    <div class="input-group mb-3">                    
                    <input
                        type="password"
                        class="form-control required"
                        name="new_pass"
                        placeholder="New password"
                        required
                        autocomplete="off"
                    />
                   
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button
                            style="font-size: 1.5em;"
                            type="submit"
                            class="btn bg-color-1 text-white btn-block "
                            >
                            Create
                            </button>
                        </div>
                        <!-- /.col -->
                        </div>
                    </form>
                </div>
          </div>
    <!-- Create a new password #END -->
      
      </div>
    </div>

    

    <!-- jQuery -->
    <script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url('/assets/dist/js/adminlte.min.js'); ?>"></script>
    
    <script>
        var URL = "<?php echo base_url("UserController/ajax_work");?>";
        
        $("#forgot_pass").submit(function(e){
            e.preventDefault();            
            $.post(URL, {action:"FORGOT_PASSWORD",email_id: e.target.email_id.value}, function(res){               
              
               if(res.msg_code == "SEND_OPT"){
                $(".forgot-password").hide();
                $(".create-password").show();
                $(".create-password form").find("[type=email]").val(e.target.email_id.value);
                $(".show-msg").hide();
               }
               if(res.msg_code == "USER_NOT_FOUND"){
                $(".show-msg").show();
                $(".show-msg p").text("Invalid email address");
               }
            });
        });

        $("#create_new_pass").submit(function(e){
            e.preventDefault();            
            $.post(URL, {action:"NEW_PASSWORD",otp_code: e.target.otp_code.value,
            new_pass:e.target.new_pass.value,email_id:e.target.email_otp.value}, function(res){               
              
              if(res.msg_code == "ERROR_VALIDATION"){
                $(".show-msg-2").show();
                $(".show-msg-2 p").html(res.msg_content);
                $(".show-msg-2").removeClass("alert-success");
                $(".show-msg-2").addClass("alert-danger");
              }

              if(res.msg_code=="OTP_INVALID"){
                $(".show-msg-2").show();
                $(".show-msg-2 p").text("OTP Invalid.");
                $(".show-msg-2").removeClass("alert-success");
                $(".show-msg-2").addClass("alert-danger");
              }

              if(res.msg_code=="UPDATE_DONE"){
                $(".show-msg-2").show();
                $(".show-msg-2").removeClass("alert-danger");
                $(".show-msg-2").addClass("alert-success");
                $(".after-success-reset").hide();
                $(".show-msg-2 p").html("Your password has been reset successfully. <a href='<?echo base_url('UserController/login');?>'>Click here to Login.</a>");               
              }
             
            });
        });
    </script>

</body>
</html>
