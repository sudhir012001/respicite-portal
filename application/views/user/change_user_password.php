<style>
  a{color:black}
  
  .profile-box img{
    width: 8rem;
    height: 6.5rem;
    object-fit: contain;
    margin: 10px;
  }

  .border-round{
    border:1px solid #fc9928;
  }

  .color-b{
    background-color:#fc9928;
    color:white;
  }
</style>
<body class="hold-transition login-page">

    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Change Password</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Change Password</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <br><br>
        <div class="row">
            <div class="col-md-3"></div>
          <div class="col-md-6">

            <!-- Profile Image -->
            <div class="bg-white rounded border-round shadow">
                <div class="card-body box-profile">
                <div class="profile-box">
                  <div class="d-flex justify-content-start align-items-center flex-wrap">
                    <img class="img-circle shadow"
                        src="<?php echo base_url().$user['profile_photo']; ?>"
                        alt="User profile picture">
                    <h5 class="ml-2"><?php echo $user['fullname']; ?></h5>
                  </div>                  
                </div>

                <h3 class="profile-username text-center"></h3>

                <p class="text-muted text-center">Change Password</p>

              
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
          <input type="text" name="old_psw" value="<?php echo set_value('old_psw'); ?>" class="form-control <?php echo (form_error('old_psw')!="") ? 'is-invalid' : ''; ?>" placeholder="Old Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user text-success"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('old_psw')); ?></p>
        </div>
        
          <input type="hidden" class="form-control <?php echo (form_error('email')!="") ? 'is-invalid' : ''; ?>" name="email" value="<?php echo $user['email']; ?>" placeholder="Email">

        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('npsw')!="") ? 'is-invalid' : ''; ?>" name="npsw" value="" placeholder="New Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock text-success"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('npsw')); ?></p>
        </div>
        
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('cnpsw')!="") ? 'is-invalid' : ''; ?>" name="cnpsw" value="" placeholder="Confirm New Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock text-success"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('cnpsw')); ?></p>
        </div>

        <div class="row">
          <div class="col-6">
           <!-- blank portion -->
          </div>
          <!-- /.col -->
          <div class="col-6 text-right">
            <button type="submit" name="changepwd" class="btn color-b btn-block">Change Password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

                <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
           
            <!-- /.card -->
          </div>
          <!-- /.col -->
          
          <!-- /.col -->
        </div>
        <!-- /.row -->

               

      </div><!-- /.container-fluid -->
      <br>
    </section>
    <!-- /.content -->
  </div>

  