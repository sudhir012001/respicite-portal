  <!-- Content Wrapper. Contains page content -->
  <style>a{color:black;}</style>
  <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <div class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Dashboard</h1>
          </div><!-- /.col -->
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div> -->
          <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row" >
          <div class="col-lg-6 col-12">
              <div class="small-box bg-info pt-3">
                <div class="inner">
                  <h3 class="pl-3">Purchase <br> Code</h3>                
                </div>
                <div class="icon pt-2">
                  <i class="fas fa-shopping-cart" style="font-size: 7rem;"></i>
                </div>
                <a href="<?php echo base_url().'BaseController/request_code'; ?>" class="small-box-footer pt-2 pb-2 mt-3">
                  Click Here <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
          </div>

          <div class="col-lg-6 col-12">
              <div class="small-box bg-success pt-3">
                <div class="inner">
                  <h3 class="pl-3">Take <br> Assessment</h3>                  
                </div>
                <div class="icon pt-2">
                <i class="fa fa-graduation-cap" style="font-size: 7rem;"></i>
                </div>
                <a href="<?php echo base_url().'BaseController/view_code';?>"  class="small-box-footer pt-2 pb-2 mt-3">
                  Click Here <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
          </div>

          <div class="col-lg-6 col-12">
              <div class="small-box bg-danger pt-3">
                <div class="inner">
                  <h3 class="pl-3">Change <br> Password</h3>                  
                </div>
                <div class="icon pt-2">
                  <i class="fa fa-lock" style="font-size: 7rem;"></i>
                </div>
                <a href="<?php echo base_url().'/BaseController/change_password'; ?>" class="small-box-footer pt-2 pb-2 mt-3">
                  Click Here <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
          </div>

          <div class="col-lg-6 col-12">
              <div class="small-box bg-primary pt-3">
                <div class="inner">
                  <h3 class="pl-3">Profile <br>   Update</h3>                  
                </div>
                <div class="icon pt-2" >
                  <i class="fas fa-user-edit" style="font-size: 7rem;"></i>
                </div>
                <a href="<?php echo base_url().'BaseController/edit_user_profile'; ?>" class="small-box-footer pt-2 pb-2 mt-3">
                  Click Here <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
          </div>

          <div class="col-lg-4 col-12"></div>


          <!-- <div class="col-12 col-lg-6 ">

                    <div class="card bg-cyan" style="min-height: 150px; padding-top:20px;">
                                <div class="card-body">
                                    <a href="<?php //echo base_url().'BaseController/request_code'; ?>"  style="text-decoration: none; color:white;">
                                    <div class="row" style="min-height: 130px; ">
                                      <div class="col-5" style="opacity: .7;" >
                                        <img class="bg-cyan" src="<?php //echo base_url().'/assets/dist/img/file2.png'; ?>" style="height: 90px; width:90px;"  alt="Card image cap">                
                                      </div>
                                      <div class="col-7" style="opacity: .7; color:black;">
                                      <span><h4><b>Purchase Code</b></h4></span>
                                      </div> 
                                    </div>  
                                    </a>
                                </div>
                    </div>
            </div> -->

            <!-- <div class="col-12 col-lg-6 ">
                    <div class="card bg-yellow" style="min-height: 150px; padding-top:20px;">
                                <div class="card-body">
                                    <a href="<?php //echo base_url().'BaseController/view_code'; ?>"  style="text-decoration: none; color:white;">
                                    <div class="row" style="min-height: 130px; ">
                                      <div class="col-5" style="opacity: .7;">
                                        <img class="bg-yellow" src="<?php //echo base_url().'/assets/dist/img/file2.png'; ?>" style="height: 90px; width:90px;" alt="Card image cap">                
                                      </div>
                                      <div class="col-7" style="opacity: .7; color:black;">
                                      <span><h4><b>Take Assessment</b></h4></span>
                                      </div> 
                                    </div>  
                                    </a>
                                </div>
                    </div>
            </div> -->

            <!-- <div class="col-12 col-lg-6 ">
                    <div class="card bg-pink" style="min-height: 150px; padding-top:20px;">
                                <div class="card-body">
                                    <a href="<?php //echo base_url().'/BaseController/change_password'; ?>"  style="text-decoration: none; color:white;">
                                    <div class="row" style="min-height: 130px; ">
                                      <div class="col-5" style="opacity: .7;">
                                        <img class="bg-pink" src="<?php //echo base_url().'/assets/dist/img/profile2.png'; ?>" style="height: 90px; width:90px;" alt="Card image cap">                
                                      </div>
                                      <div class="col-7" style="opacity: .7; color:black;">
                                      <span><h4><b>Change Password</b></h4></span>
                                      </div> 
                                    </div>  
                                    </a>
                                </div>
                    </div>
            </div> -->

           <!--  <div class="col-12 col-lg-6 ">
                    <div class="card bg-green" style="min-height: 150px; padding-top:20px;">
                                <div class="card-body">
                                    <a href="<?php //echo base_url().'BaseController/edit_user_profile'; ?>"  style="text-decoration: none; color:white;">
                                    <div class="row" style="min-height: 130px; ">
                                      <div class="col-5" style="opacity: .7;">
                                        <img class="bg-green" src="<?php //echo base_url().'/assets/dist/img/profile2.png'; ?>" style="height: 90px; width:90px;" alt="Card image cap">                
                                      </div>
                                      <div class="col-7" style="opacity: .7; color:black;">
                                      <span><h4><b>Profile Update</b></h4></span>
                                      </div> 
                                    </div>  
                                    </a>
                                </div>
                    </div>
            </div>
        </div> -->
        <!-- /.row -->
        <!-- Main row -->
        