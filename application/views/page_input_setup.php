<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1>Page Setup</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Page Setup</li>
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
                    <!-- <img class="profile-user-img img-fluid img-circle"
                        src="<?php echo base_url().$user['profile_photo']; ?>"
                        alt="User profile picture"> -->
                    </div>

                    <h3 class="profile-username text-center"></h3>

                    <p class="text-muted text-center">Page Setup</p>

              
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
                    <form action="" enctype="multipart/form-data" method="post">
                        <div class="form-group">
                            <label for="exampleInputFile">Reseller Logo</label>
                            <div class="input-group">
                                <div class="custom-file">
                                <input type="file" class="form-control" name="img" id="img">
                                </div>
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Banner Image</label>
                            <div class="input-group">
                                <div class="custom-file">
                                <input type="file" class="form-control" name="ban_img" id="ban_img">
                                </div>
                            
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <textarea class="form-control" name="ban_msg" value="<?php echo set_value('ban_msg'); ?>" rows="3" placeholder="Enter Banner Message ..."></textarea>
                        </div>
                        <div class="form-group">
                            
                            <textarea class="form-control" name="about" value="<?php echo set_value('about'); ?>" rows="3" placeholder="Enter About Us ..."></textarea>
                        </div>
                        <label>Contact Details</label>
                        <div class="input-group mb-3">
                            
                            <input type="email" class="form-control <?php echo (form_error('email')!="") ? 'is-invalid' : ''; ?>" name="email" value="<?php echo set_value('email'); ?>" placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('email')); ?></p>
                        </div>
                        <div class="form-group">
                        
                            <textarea class="form-control" name="addr" value="<?php echo set_value('addr'); ?>" rows="2" placeholder="Enter Address..."></textarea>
                        </div>
                        <div class="input-group mb-3">
                            <input type="tel" class="form-control <?php echo (form_error('mobile')!="") ? 'is-invalid' : ''; ?>" name="mobile" value="<?php echo set_value('mobile'); ?>" placeholder="Mobile No.">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('mobile')); ?></p>
                        </div>    
                        
                        <div class="row">
                            <div class="col-8">
                            <!-- blank portion -->
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button type="submit" name="savebtn" class="btn btn-primary btn-block">Save</button>
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

  