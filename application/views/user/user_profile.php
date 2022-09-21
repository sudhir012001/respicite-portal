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

  .list-group-item {
    border:1px solid #fc9928;    
  }

  .list-group-unbordered>.list-group-item{
    padding-left: 7px;
    padding-right: 7px;
  }
</style>
<body class="hold-transition login-page">

    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item ">View Profile</li>
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

                <ul class="list-group list-group-unbordered mb-3  p-3">
                <li class="list-group-item">
                    <b>ID</b> <a class="float-right"><?php echo $user['user_id']; ?></a>
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
        <br>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>