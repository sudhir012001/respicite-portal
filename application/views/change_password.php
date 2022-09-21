<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Change Password</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("SpController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Change Password</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-3"></div>
          <div class="col-md-6">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                       src="<?php echo base_url().$user['profile_photo']; ?>"
                       alt="User profile picture">
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
              <span class="fas fa-user"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('old_psw')); ?></p>
        </div>
        
          <input type="hidden" class="form-control <?php echo (form_error('email')!="") ? 'is-invalid' : ''; ?>" name="email" value="<?php echo $user['email']; ?>" placeholder="Email">

        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('npsw')!="") ? 'is-invalid' : ''; ?>" name="npsw" value="" placeholder="New Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('npsw')); ?></p>
        </div>
        
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('cnpsw')!="") ? 'is-invalid' : ''; ?>" name="cnpsw" value="" placeholder="Confirm New Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('cnpsw')); ?></p>
        </div>

        <div class="row">
          <div class="col-6">
           <!-- blank portion -->
          </div>
          <!-- /.col -->
          <div class="col-6">
            <button type="submit" name="changepwd" class="btn btn-primary btn-block">Change Password</button>
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
    </section>
    <!-- /.content -->
  </div>

  