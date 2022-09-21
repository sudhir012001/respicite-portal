<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Page Setup</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("SpController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Social Link</li>
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
                        <?php
                          $row = $this->User_model->get_homepage_data($user['email']);
                          foreach($row->result() as $row)
                          {
                            $fb_link = $row->fb_url;
                            $twt_link = $row->twt_url;
                            $insta_link = $row->insta_url;
                            $link_link = $row->link_url;
                            $footer = $row->ftr;
                          }  
                        ?>
                        <label>Social Link</label>
                        <div class="input-group mb-3">
                            
                            <input type="text" class="form-control <?php echo (form_error('fb_link')!="") ? 'is-invalid' : ''; ?>" name="fb_link" value="<?php echo $fb_link; ?>" placeholder="Facebook Link">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-facebook"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('fb_link')); ?></p>
                        </div>
                        
                        <div class="input-group mb-3">
                            <input type="text" class="form-control <?php echo (form_error('twt_link')!="") ? 'is-invalid' : ''; ?>" name="twt_link" value="<?php echo $twt_link; ?>" placeholder="Twitter Link">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-twitter"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('twt_link')); ?></p>
                        </div>  
                        <div class="input-group mb-3">
                            
                            <input type="text" class="form-control <?php echo (form_error('insta_link')!="") ? 'is-invalid' : ''; ?>" name="insta_link" value="<?php echo $insta_link; ?>" placeholder="Instagram Link">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-instagram"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('insta_link')); ?></p>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control <?php echo (form_error('link_link')!="") ? 'is-invalid' : ''; ?>" name="link_link" value="<?php echo $link_link; ?>" placeholder="Linkedin Link">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-twitter"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('link_link')); ?></p>
                        </div>     
                        <div class="input-group mb-3">
                            <input type="text" class="form-control <?php echo (form_error('footer')!="") ? 'is-invalid' : ''; ?>" name="footer" value="<?php echo $footer; ?>" placeholder="Footer Copyright Message">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-twitter"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('footer')); ?></p>
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

  