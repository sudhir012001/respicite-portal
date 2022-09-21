<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User Profile</li>
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
                
                <p class="text-muted text-center">Service Provider</p>

                <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                    <b> ID</b> <a class="float-right"><?php echo $user['user_id']; ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Full Name</b> <a class="float-right"><?php echo $user['fullname']; ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Email</b> <a class="float-right"><?php echo $user['email']; ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Mobile No.</b> <a class="float-right"><?php echo $user['mobile']; ?></a>
                  </li>
                </ul>

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