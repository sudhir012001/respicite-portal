<style>
a{color:black;}

.btn-my{
  color: #fc9928;
  border-color: #fc9928;
}

.btn-my:hover {
    color: #fff;
    background-color: #fc9928;
    border-color: #fc9928;
}
.table td{
  border-top: 0.5px solid #dee2e6;
}

.my-width{
  width:57%;
}

@media (max-width: 767.98px) { 
  .table tr {
    display: flex;
    flex-direction: column;
    width: 100%;
    text-align: justify;
    border: 1px solid #e2e5e7;
    border-radius: 7px;
    margin-bottom: 12px;
  }
  .my-width{
    width:100%;
  }
}
</style>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Code List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Purchase code</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
        <?php 
        $url_msg = $this->session->flashdata("url_msg");
        if(!empty($url_msg)){
          if($url_msg == "request_code"){
            echo '<div class="alert alert-info">
            Purchase new assessments.
          </div>';
          }
        }?>
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
$email = $user['email'];
// $user = $this->session->userdata('user');
//             echo "<pre>";print_r($user);die;
            
//             $this->db->where('user_id',$user['id']);
//             $this->db->where('payment_type','razorypay');
//             $this->db->select('*');
//             $reseller_details = $this->db->get('paymentcrd');

//             if(isset($reseller_details)){
//                  foreach($reseller_details->result() as $reseller_row)
//                 {
//                     $status = $reseller_row->status;
//                     $id = $reseller_row->id;
//                 }   
//             }
//           $status=isset($status)?$status:'';
           
//           $this->db->where('user_id',$user['id']);
//             $this->db->where('payment_type','stripe');
//             $this->db->select('*');
//             $reseller_detailsstripe = $this->db->get('paymentcrd');

//             if(isset($reseller_detailsstripe)){
//                  foreach($reseller_detailsstripe->result() as $reseller_row1)
//                 {
//                     $statuss = $reseller_row1->status;
//                     $ids = $reseller_row1->id;
//                 }   
//             }
//           $statuss=isset($statuss)?$status:'';
?>
  <div class="row">
          <div class="col-12">
            <!-- <div class="card"> -->
              <!-- <div class="card-header">
                <h3 class="card-title">Code List</h3>
              </div>  -->
              <!-- /.card-header -->
              
              <!-- <div class="card-body">  -->
                          <?php 
                            $this->db->where('email',$email);
                            $row = $this->db->get('user_details');
                            foreach($row->result() as $row)
                            {
                              $code = $row->user_id;
                            }
                            // $where = "user_id='$code' and email!='$email' and iam='reseller'";
                            $where = "user_id='$code' and email!='$email' and (iam='reseller' or iam='sp')";
                            $this->db->where($where);
                            $row2 = $this->db->get('user_details');
                            // echo "<pre>";
                            // print_r($row2->result());die();
                            foreach($row2->result() as $row2)
                            {
                                $r_email = $row2->email;
                            }
                            $sl_get = $this->db->get('solutions');
                            // echo "<pre>";
                            // print_r($sl_get->result());die();
                            
                            ?>

                            <div class="card card-default collapsed-card">
                              <div class="card-header">
                                <h3 class="card-title">
                                  Solution for School students
                                </h3>

                                <div class="card-tools">
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                  </button>
                                </div>
                              </div>
                              <!-- /.card-header -->

                              <div class="card-body">
                                <div class="table-responsive">
                                  <table class="table">
                                  <?php
                                    foreach($sl_get->result() as $sl)
                                    {                                      
                                        $solution = $sl->solution;
                                        $dsn = $sl->display_solution_name;
                                        $where = "email='$r_email' and solution='$solution'";
                                        $this->db->where($where);
                                        $list_no = $this->db->get('generated_code_details')->num_rows();
                                        
                                        if($list_no>0)
                                        {                                  
                                            if($sl->c_group == "Career Explorer")
                                            {                                
                                            ?>
                                            <tr>
                                              <td>                                               
                                                <p class="p-0 m-0"><?php echo $dsn; ?></p>
                                                <p class="p-0 m-0"><?php echo $sl->mrp; ?></p>
                                              </td>
                                              <td>
                                                <?php $rr = explode(",",$allowed_services);
                                                if(in_array("offline", $rr))
                                                {?>
                                               <a class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>
                                             <?php }
                                             if(count($paymentGateway) > 0){ 
                                              ?>
                                               <br>
                                               
                                               <a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a>
                                             <?php } ?>
                                             
                                               <!--<a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>shared/payments/StripController/stripView">Pay online </a>-->
                                              
                                              </td>
                                              <td class="my-width">                                               
                                                <p class="p-0 m-0"><?php echo $sl->description; ?></p>
                                              </td>
                                              
                                            </tr>
                                            <?php                                   
                                            }
                                        }
                                    }
                                  ?>
                                  </table>
                                  </div>
                                <!-- /.card-body -->
                              </div>
                            </div>

                            <div class="card card-default collapsed-card">
                              <div class="card-header">
                                <h3 class="card-title">
                                Solution for College students 
                                </h3>

                                <div class="card-tools">
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                  </button>
                                </div>
                              </div>
                              <!-- /.card-header -->

                              <div class="card-body">
                              <div class="table-responsive">
                                  <table class="table">
                                  <?php
                                    foreach($sl_get->result() as $sl)
                                    {                                      
                                        $solution = $sl->solution;
                                        $dsn = $sl->display_solution_name;                                
                                        $where = "email='$r_email' and solution='$solution'";
                                        $this->db->where($where);
                                        $list_no = $this->db->get('generated_code_details')->num_rows();
                                        
                                        if($list_no>0)
                                        {                                  
                                            if($sl->c_group == "Career Builder"){                                
                                            ?>
                                            <tr>
                                              <td>                                               
                                                <p class="p-0 m-0"><?php echo $dsn; ?></p>
                                                <p class="p-0 m-0"><?php echo $sl->mrp; ?></p>
                                              </td>
                                              <td>
                                               <a class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>
                                               <br>
                                               <a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a>
                                              </td>
                                              <td class="my-width">                                               
                                                <p class="p-0 m-0"><?php echo $sl->description; ?></p>
                                              </td>
                                              
                                            </tr>
                                            <?php                                   
                                            }
                                        }
                                    }
                                  ?>
                                  </table>
                                  </div>
                                <!-- /.card-body -->
                              </div>
                            </div>

                            <div class="card card-default collapsed-card">
                              <div class="card-header">
                                <h3 class="card-title">
                                Solution for Professionals
                                </h3>

                                <div class="card-tools">
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                  </button>
                                </div>
                              </div>
                              <!-- /.card-header -->

                              <div class="card-body">
                              <div class="table-responsive">
                                  <table class="table">
                                  <?php
                                    foreach($sl_get->result() as $sl)
                                    {                                      
                                        $solution = $sl->solution;
                                        $dsn = $sl->display_solution_name;                                
                                        $where = "email='$r_email' and solution='$solution'";
                                        $this->db->where($where);
                                        $list_no = $this->db->get('generated_code_details')->num_rows();
                                        
                                        if($list_no>0)
                                        {                                  
                                            if($sl->c_group == "Career Excellence"){                                
                                            ?>
                                            <tr>
                                              <td>                                               
                                                <p class="p-0 m-0"><?php echo $dsn; ?></p>
                                                <p class="p-0 m-0"><?php echo $sl->mrp; ?></p>
                                              </td>
                                              <td>
                                               <a class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>
                                               <br>
                                               <a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a>
                                              </td>
                                              <td class="my-width">                                               
                                                <p class="p-0 m-0"><?php echo $sl->description; ?></p>
                                              </td>
                                              
                                            </tr>
                                            <?php                                   
                                            }
                                        }
                                    }
                                  ?>
                                  </table>
                                  </div>
                                <!-- /.card-body -->
                              </div>
                            </div>

                            <div class="card card-default collapsed-card">
                              <div class="card-header">
                                <h3 class="card-title">
                                  Solution for Parents
                                </h3>

                                <div class="card-tools">
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                  </button>
                                </div>
                              </div>
                              <!-- /.card-header -->

                              <div class="card-body">
                              <div class="table-responsive">
                                  <table class="table">
                                  <?php
                                    foreach($sl_get->result() as $sl)
                                    {                                      
                                        $solution = $sl->solution;
                                        $dsn = $sl->display_solution_name;                                
                                        $where = "email='$r_email' and solution='$solution'";
                                        $this->db->where($where);
                                        $list_no = $this->db->get('generated_code_details')->num_rows();
                                        
                                        if($list_no>0)
                                        {                                  
                                            if($sl->c_group == "Positive Parenting"){                                
                                            ?>
                                            <tr>
                                              <td>                                               
                                                <p class="p-0 m-0"><?php echo $dsn; ?></p>
                                                <p class="p-0 m-0"><?php echo $sl->mrp; ?></p>
                                              </td>
                                              <td>
                                               <a class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>
                                               <br>
                                               <a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a>
                                              </td>
                                              <td class="my-width">                                               
                                                <p class="p-0 m-0"><?php echo $sl->description; ?></p>
                                              </td>                                              
                                            </tr>
                                            <?php                                   
                                            }
                                        }
                                    }
                                  ?>
                                  </table>
                                  </div>
                                <!-- /.card-body -->
                              </div>
                            </div>

                            <div class="card card-default collapsed-card">
                              <div class="card-header">
                                <h3 class="card-title">
                                  Overseas Services
                                </h3>

                                <div class="card-tools">
                                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                  </button>
                                </div>
                              </div>
                              <!-- /.card-header -->

                              <div class="card-body">
                              <div class="table-responsive">
                                  <table class="table">
                                  <?php
                                //   echo "<pre>";
                                //   print_r($sl_get->result());die();
                                    foreach($sl_get->result() as $sl)
                                    {   //echo "email=".$r_email."sol=".$solution;                                 
                                        $solution = $sl->solution;
                                        $dsn = $sl->display_solution_name;                                
                                        $where = "email='$r_email' and solution='$solution'";
                                        $this->db->where($where);
                                        $list_no = $this->db->get('generated_code_details')->num_rows();
                                        
                                        //echo "list no".$list_no."<br>";
                                        
                                        if($list_no>0)
                                        {                                  
                                            if($sl->c_group == "Overseas Companion"){  //echo "<br>".$sl->c_group."<br>";                              
                                            ?>
                                            <tr>
                                              <td>                                               
                                                <p class="p-0 m-0"><?php echo $dsn; ?></p>
                                                <p class="p-0 m-0"><?php echo $sl->mrp; ?></p>
                                              </td>
                                              <td>
                                               <!-- <a class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>
                                               <br>
                                               <a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a> -->
                                                                                               <?php $rr = explode(",",$allowed_services);
                                                if(in_array("offline", $rr))
                                                {?>
                                               <a class="btn btn-sm btn-outline-secondary btn-my" href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Pay offline</a>
                                             <?php }
                                             if(count($paymentGateway) > 0){ 
                                              ?>
                                               <!-- <br> -->
                                               
                                               <a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>BaseController/user_request_code_online/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $sl->id; ?>">Pay online</a>
                                             <?php } ?>
                                             
                                               <!--<a class="btn btn-sm btn-outline-success mt-2" href="<?php echo base_url(); ?>shared/payments/StripController/stripView">Pay online </a>-->
                                              
                                              </td>
                                              <td class="my-width">                                               
                                                <p class="p-0 m-0"><?php echo $sl->description; ?></p>
                                              </td>                                              
                                            </tr>
                                            <?php                                   
                                            }
                                        }
                                    } 
                                  ?>
                                  </table>
                                  </div>
                                <!-- /.card-body -->
                              </div>
                            </div>

                           
              <!-- </div> -->
              
              
             

              <!-- /.card-body -->
            <!-- </div> -->
            <!-- /.card -->
          </div>
        </div>

        

        
       
