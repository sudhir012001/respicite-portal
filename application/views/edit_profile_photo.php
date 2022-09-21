<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1>Edit Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Profile</li>
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

                <p class="text-muted text-center">Update Profile</p>

              
           
            <!-- Profile Image -->
           

              
              <?php
                $msg = $this->session->flashdata('msg');
                if($msg != "")
                {
                    echo "<div class='alert alert-success'>$msg</div>";
                }
            ?>
      <form action="<?php echo base_url('UserController/do_upload'); ?>" enctype="multipart/form-data" method="post">    
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
      <!-- <div class="form-group">
      
                  
                    <label for="exampleInputFile">Change Profile Photo</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="form-control" name="img" id="img">
                        
                      </div>
                     
                    </div>
                  </div>
                  <div class="row">
          <div class="col-8">
            
          </div> -->
          <!-- /.col -->
          <!-- <div class="col-4">
            <button type="submit" name="uploadbtn" id="uploadbtn" class="btn btn-primary btn-block">Update</button>
          </div> -->
          <!-- /.col -->
        <!-- </div> -->
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