  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <!-- <li class="breadcrumb-item"><a href="<?php //echo base_url().'BaseController/dashboard'; ?>">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li> -->
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row" >

            <div class="col-12 col-lg-6 ">
                    <div class="card bg-cyan" style="min-height: 150px; padding-top:20px;">
                                <div class="card-body">
                                    <a href="<?php echo base_url().'SpController/spprofile'; ?>"  style="text-decoration: none; color:white;">
                                    <div class="row" style="min-height: 130px; ">
                                      <div class="col-5" style="opacity: .7;" >
                                        <img class="bg-cyan" src="<?php echo base_url().'/assets/dist/img/file2.png'; ?>" style="height: 90px; width:90px;"  alt="Card image cap">                
                                      </div>
                                      <div class="col-7" style="opacity: .7; color:black;">
                                      <span><h4><b>Your Services</b></h4></span>
                                      </div> 
                                    </div>  
                                    </a>
                                </div>
                    </div>
            </div>

            <div class="col-12 col-lg-6 ">
                    <div class="card bg-yellow" style="min-height: 150px; padding-top:20px;">
                                <div class="card-body">
                                    <a href="<?php echo base_url().'/SpController/become_partner_counselor'; ?>"  style="text-decoration: none; color:white;">
                                    <div class="row" style="min-height: 130px; ">
                                      <div class="col-5" style="opacity: .7;">
                                        <img class="bg-yellow" src="<?php echo base_url().'/assets/dist/img/file2.png'; ?>" style="height: 90px; width:90px;" alt="Card image cap">                
                                      </div>
                                      <div class="col-7" style="opacity: .7; color:black;">
                                      <span><h4><b>Training & Certification</b></h4></span>
                                      </div> 
                                    </div>  
                                    </a>
                                </div>
                    </div>
            </div>

            <div class="col-12 col-lg-6 ">
                    <div class="card bg-pink" style="min-height: 150px; padding-top:20px;">
                                <div class="card-body">
                                    <a href="<?php echo base_url().'/SpController/change_password'; ?>"  style="text-decoration: none; color:white;">
                                    <div class="row" style="min-height: 130px; ">
                                      <div class="col-5" style="opacity: .7;">
                                        <img class="bg-pink" src="<?php echo base_url().'/assets/dist/img/profile2.png'; ?>" style="height: 90px; width:90px;" alt="Card image cap">                
                                      </div>
                                      <div class="col-7" style="opacity: .7; color:black;">
                                      <span><h4><b>Change Password</b></h4></span>
                                      </div> 
                                    </div>  
                                    </a>
                                </div>
                    </div>
            </div>

            <div class="col-12 col-lg-6 ">
                    <div class="card bg-green" style="min-height: 150px; padding-top:20px;">
                                <div class="card-body">
                                    <a href="<?php echo base_url().'/SpController/edit_sp_profile'; ?>"  style="text-decoration: none; color:white;">
                                    <div class="row" style="min-height: 130px; ">
                                      <div class="col-5" style="opacity: .7;">
                                        <img class="bg-green" src="<?php echo base_url().'/assets/dist/img/profile2.png'; ?>" style="height: 90px; width:90px;" alt="Card image cap">                
                                      </div>
                                      <div class="col-7" style="opacity: .7; color:black;">
                                      <span><h4><b>Profile Update</b></h4></span>
                                      </div> 
                                    </div>  
                                    </a>
                                </div>
                    </div>
            </div>
        </div>
        <!-- /.row -->
        <!-- Main row -->
        