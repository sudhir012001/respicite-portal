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
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Edit Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Edit Profile</li>
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

                <p class="text-muted text-center">Update Profile</p>
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
            
              
                <form action="<?php echo base_url('BaseController/do_upload'); ?>" enctype="multipart/form-data" method="post">        
                  <div class="form-group">
                    <label for="exampleInputFile">Change Profile Photo</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img" id="img">
                        <label class="custom-file-label" for="img">Choose file </label>
                      </div>
                      <div class="input-group-append">
                      <button type="submit" name="uploadbtn" id="uploadbtn" class="btn color-b btn-block">Upload</button>
                      </div>
                    </div>
                  </div>
              </form>
      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" name="full_name" value="<?php echo $user['fullname']; ?>" class="form-control <?php echo (form_error('full_name')!="") ? 'is-invalid' : ''; ?>" placeholder="Full name">
         
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user text-success"></span>
            </div>
            
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('full_name')); ?></p>
        </div>
        
        <div class="input-group mb-1">
          <input type="hidden" class="form-control <?php echo (form_error('email')!="") ? 'is-invalid' : ''; ?>" name="email" value="<?php echo $user['email']; ?>" placeholder="Email">
          
          <p class="invalid-feedback"><?php echo strip_tags(form_error('email')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="tel" class="form-control <?php echo (form_error('mobile')!="") ? 'is-invalid' : ''; ?>" name="mobile" value="<?php echo $user['mobile']; ?>" placeholder="Mobile No.">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone text-success"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('mobile')); ?></p>
        </div>
        
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              
            </div>
            <p class="invalid-feedback"><?php echo strip_tags(form_error('terms')); ?></p>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="updatebtn" class="btn color-b btn-block">Update</button>
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

               <br>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  