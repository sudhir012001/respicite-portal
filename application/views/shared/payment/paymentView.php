  <!-- Content Wrapper. Contains page content -->
   <?php
    // echo "<pre>";
    // print_r($payment_gateways);
    // foreach($payment_gateways['names'] as $v)
    // {
    //   $link[$v] = [
    //     'configure'=>base_url().'payment-gateway/'.$v.'/keys',
    //     'status'=> base_url().'/shared/payments/'.$v.'Controller/'.$v.'Status',
    //   ];
    // }
    // print_r($link);


    // echo "</pre>";
    // die;
   ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo $payment_gateways['dashboard']['heading'];?></h1>
          </div><!-- /.col -->
          <div class="col-sm-2">
            <?php if($user['fullname']=='Admin'){ ?>
            <a class="m-0 btn btn-primary" href="<?php echo base_url().'payment-gateway/addparameter'; ?>"><?php echo $payment_gateways['dashboard']['add_pg'];?></a>
          <?php }else{?>
            <a class="m-0 btn btn-primary" href="<?php echo base_url().'AdminController/allowed_services_offline/'.$user['id']; ?>"><?php $rr = explode(",",$allowed_services); if(in_array("offline", $rr)){
                $button = "offlineDeactive";
            }else{
              $button = "offlineActive";
            }?><?php echo $button;?></a>
          <?php }?>
          </div><!-- /.col -->
          <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Integration</li>
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
        <?php 
                    $msg = $this->session->flashdata('msg');
                    if($msg !="")
                    {
                    ?>     
                    <div class="alert alert-success">
                        <?php echo $msg; ?>
                    </div>
                    <?php 
                    }
                     $msg2 = $this->session->flashdata('msg2');
                    if($msg2 !="")
                    {
                    ?>
                    <div class="alert alert-danger">
                    <?php echo $msg2; ?>
                    </div>
                    <?php 
                    }
                    ?>
        <div class="row">
<?php foreach($payment_details as $row){  ?>
        <div class="col-12 col-lg-6 ">
                    <div class="card bg-cyan" style="min-height: 150px; padding-top:20px;">
                                <div class="card-body">
                                    <a href="<?php echo base_url().'payment-gateway/'.$row['payment_type_name'].'/keys/'.$row["id"].''; ?>"  style="text-decoration: none; color:white;">
                                    <div class="row" style="min-height: 130px; ">
                                      <div class="col-5" style="opacity: .7;" >
                                        <img class="bg-cyan" src="<?php echo base_url().'/assets/dist/img/file2.png'; ?>" style="height: 90px; width:90px;"  alt="Card image cap">                
                                      </div>
                                      <div class="col-7" style="opacity: .7; color:black;">
                                      <span><h4><b><?php echo $row['payment_type_name'] ?></b></h4></span>
                                      </div> 
                                    </div> 
                                    </a>
                                    
                                   
                                    
                                    <a href="<?php echo base_url().'shared/payments/'.$row['controller_name'].'/'.$row["payment_type_name"].'Status?id='.$row['id']."&status=".$row['crd_status']; ?>"  class='btn btn-primary'><?php echo  $row['crd_status']==1?'Active':'Deactive'; ?></a>
                               
                                </div>
                    </div>
            </div>
<?php } ?>
          
          

          <!-- <div class="col-lg-6 col-12"> -->
            <!-- small box -->
            <!-- <div class="small-box bg-success" style="min-height: 180px;">
              <div class="inner" style="min-height: 180px;">
                <h3>53<sup style="font-size: 20px">%</sup></h3>

                <p>Approve User Code</p>
              </div>
              <div class="icon">
                <i class="fa fa-thumbs-up"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
          <!-- ./col -->
          <!-- <div class="col-12 col-lg-6 ">
                    <div class="card bg-pink" style="min-height: 150px; padding-top:20px;">
                                <div class="card-body">
                                    <a href="<?php echo base_url().'UserController/certification'; ?>"  style="text-decoration: none; color:white;">
                                    <div class="row" style="min-height: 130px; ">
                                      <div class="col-5" style="opacity: .7;">
                                        <img class="bg-pink" src="<?php echo base_url().'/assets/dist/img/certificate2.png'; ?>" style="height: 90px; width:90px;" alt="Card image cap">                
                                      </div>
                                      <div class="col-7" style="opacity: .7; color:black;">
                                      <span><h4><b>Certification</b></h4></span>
                                      </div> 
                                    </div>  
                                    </a>
                                </div>
                    </div>
            </div> -->

          <!-- <div class="col-lg-6 col-12"> -->
            <!-- small box -->
            <!-- <div class="small-box bg-danger" style="min-height: 180px;">
              <div class="inner" style="min-height: 180px;">
                <h3>65</h3>

                <p>Certification</p>
              </div>
              <div class="icon">
                <i class="fa fa-file"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
          <!-- ./col -->
          <!-- <div class="col-12 col-lg-6 ">
                    <div class="card bg-green" style="min-height: 150px; padding-top:20px;">
                                <div class="card-body">
                                    <a href="<?php echo base_url().'/UserController/edit_reseller_profile'; ?>"  style="text-decoration: none; color:white;">
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
            </div> -->
          <!-- <div class="col-lg-6 col-12"> -->
            <!-- small box -->
            <!-- <div class="small-box bg-warning" style="min-height: 180px;">
              <div class="inner" style="min-height: 180px;">
                <h3>44</h3>

                <p>Profile Update</p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
          <!-- ./col -->
         
        </div>
        <!-- /.row -->
        <!-- Main row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
   <!-- /.content-wrapper -->
        
  