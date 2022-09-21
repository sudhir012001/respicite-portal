<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1>Add/Edit credential</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'UserController/dashboard'; ?>">Home</a></li>
              <li class="breadcrumb-item active">Add/Edit credential</li>
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
                <!-- <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                       src="<?php echo base_url().$user['profile_photo']; ?>"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center"></h3>-->

                <p class="text-muted text-center">Stripe credential</p> 

              
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
            
         <!-- <form action="<?php echo base_url('UserController/do_upload'); ?>" enctype="multipart/form-data" method="post">    
               <div class="form-group">
                  <label for="exampleInputFile">Change Profile Photo</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img" id="img">
                        <label class="custom-file-label" for="img">Choose file </label>
                      </div>
                      <div class="input-group-append">
                      <button type="submit" name="uploadbtn" id="uploadbtn" class="btn btn-primary btn-block">Upload</button>
                      </div>
                    </div>
                  </div>    
           

      </form> -->

      <!-- <form action="<?php echo base_url('UserController/sign_upload'); ?>" enctype="multipart/form-data" method="post">    
               <div class="form-group">
                  <label for="exampleInputFile">Upload Signature(Max Height 100px)</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img" id="img">
                        <label class="custom-file-label" for="img">Choose file </label>
                      </div>
                      <div class="input-group-append">
                      <button type="submit" name="uploadbtn" id="uploadbtn" class="btn btn-primary btn-block">Upload</button>
                      </div>
                    </div>
                  </div>    
           

      </form> -->
      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" name="api_key" value="<?php echo isset($get_payment_details_id_vias->api_key)? $get_payment_details_id_vias->api_key:''; ?>" class="form-control <?php echo (form_error('api_key')!="") ? 'is-invalid' : ''; ?>" placeholder="API KEY">
         <!-- <input type="hidden" class="form-control"  name="payment_id" value="<?php //echo isset($user['user_ids'])? $user['user_ids']:0; ?>" placeholder="Email"> -->
          <!-- <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
            
          </div> -->
          <p class="invalid-feedback"><?php echo strip_tags(form_error('api_key')); ?></p>
        </div>
        
        
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('api_secret')!="") ? 'is-invalid' : ''; ?>" name="api_public" value="<?php echo isset($get_payment_details_id_vias->secret_key)? $get_payment_details_id_vias->secret_key:''; ?>" placeholder="YOUR PUBLISHABLE KEY">
          <!-- <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div> -->
          <p class="invalid-feedback"><?php echo strip_tags(form_error('api_secret')); ?></p>
        </div>
        
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              
            </div>
            <p class="invalid-feedback"><?php echo strip_tags(form_error('terms')); ?></p>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="updatebtn" class="btn btn-primary btn-block">Save/Update</button>
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

  