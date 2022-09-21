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
                    <form action=""method="post">
                        <?php
                          $row = $this->User_model->get_homepage_data($user['email']);
                          foreach($row->result() as $row)
                          {
                            $head1 = $row->company_head1;
                            $head2 = $row->company_head2;
                            $head3 = $row->company_head3;
                            $detail1 = $row->company_detail1;
                            $detail2 = $row->company_detail2;
                            $detail3 = $row->company_detail3;
                          }  
                        ?>
                        <label>Organization Details</label>
                        <div class="input-group mb-3">
                            
                            <input type="text" class="form-control <?php echo (form_error('head1')!="") ? 'is-invalid' : ''; ?>" name="head1" value="<?php echo $head1; ?>" placeholder="Organization Heading one">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-facebook"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('head1')); ?></p>
                        </div>
                        
                        <div class="input-group mb-3">
                            <input type="text" class="form-control <?php echo (form_error('detail1')!="") ? 'is-invalid' : ''; ?>" name="detail1" value="<?php echo $detail1; ?>" placeholder="Organization Detail one">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-twitter"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('detail1')); ?></p>
                        </div>  
                        <div class="input-group mb-3">
                            
                            <input type="text" class="form-control <?php echo (form_error('head2')!="") ? 'is-invalid' : ''; ?>" name="head2" value="<?php echo $head2; ?>" placeholder="Organization Heading two">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-facebook"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('head2')); ?></p>
                        </div>
                        
                        <div class="input-group mb-3">
                            <input type="text" class="form-control <?php echo (form_error('detail2')!="") ? 'is-invalid' : ''; ?>" name="detail2" value="<?php echo $detail2; ?>" placeholder="Organization Detail two">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-twitter"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('detail2')); ?></p>
                        </div>  
                        <div class="input-group mb-3">
                            
                            <input type="text" class="form-control <?php echo (form_error('head3')!="") ? 'is-invalid' : ''; ?>" name="head3" value="<?php echo $head3; ?>" placeholder="Organization Heading three">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-facebook"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('head3')); ?></p>  
                        </div>
                        
                        <div class="input-group mb-3">
                            <input type="text" class="form-control <?php echo (form_error('detail3')!="") ? 'is-invalid' : ''; ?>" name="detail3" value="<?php echo $detail3; ?>" placeholder="Organization Detail three">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-twitter"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('detail3')); ?></p>
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

  