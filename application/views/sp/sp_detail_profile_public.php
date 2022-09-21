<?php 
 foreach($s->result() as $gmail)
 {
     $email = $gmail->email;
     $p_photo = $gmail->profile_photo;
 }
  $this->db->where('email',$email);
  $record = $this->db->get('sp_profile_detail');
  foreach($record->result() as $record)
  {
    $about_us = $record->about_us;
    $key_services = $record->key_services;
    $address = $record->address;
    $mobo = $record->contact;
    $fb = $record->fb_url;
    $twt = $record->twitter_url;
    $insta = $record->insta_url;
    $linke = $record->linkedin_url;
    $heading1 = $record->heading1;
    $content1 = $record->content1;
    $heading2 = $record->heading2;
    $content2 = $record->content2;
    $heading3 = $record->heading3;
    $content3 = $record->content3;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SP Page</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/fontawesome-free/css/all.min.css'; ?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'; ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url().'/assets/dist/css/adminlte.min.css'; ?>">
</head>
<body>
<div id="modalHomeEvents" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;">
        <label>Contact Details</label>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <div class="input-group mb-3">
                                
                    <a href="mailto:<?php echo $email; ?>" class="btn btn-sm btn-primary"> <span class="fas fa-envelope"></span> </a>  
                           <b>Email :-</b> <?php echo $email; ?>
                            <br>
        </div>        
                           <div class="input-group mb-3">
                        
                               <a href="tel:<?php echo $mobo; ?>" class="btn btn-sm btn-primary"> <span class="fas fa-phone"></span></a> 
                                <b>Mobile :- </b>
                           <?php echo $mobo; ?>
                            <br>
                            
                        </div>     
                            
                        <div class="form-group">
                        <span class="fas fa-home"><b>Address :- </b>
                       </span> <?php echo $address; ?>
                        </div>
                           
        
            
        </div>
        <div class="modal-footer">
        
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>

    </div>
</div>
      <!-- Modal content-->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Service Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="https://respicite.com">Home</a></li>
              <li class="breadcrumb-item active">Service Profile</li>
            </ol>
          </div>
          
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
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <div class="container">
    <section class="content">

      <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-sm-6">
              <!-- <h3 class="d-inline-block d-sm-none">bbcbcxbcbcxbcxbx</h3> -->
              <div class="col-12">
                <img src="<?php echo base_url().$p_photo; ?>" class="product-image" alt="Profile Image">
              </div>
             
            </div>
            <div class="col-12 col-sm-6">
              <form action="<?php echo base_url('SpController/sp_detail'); ?>"  method="post"> 
              <h3 class="my-3">About Us</h3>
              <div class="form-group">
                <?php echo $about_us; ?>
              </div>
              
              <hr>
              <h4>Key Services</h4>
              <div class="form-group">
                <?php echo $key_services; ?>
              </div>
            
              <div class="mt-4">
                        <div class="form-group">
                        <a href="mailto:<?php echo $email; ?>" class="btn btn-sm btn-primary"> <span class="fas fa-envelope"></span> </a>  
                           <b>Email :-</b> <?php echo $email; ?>
                            <br>
                            </div>
                         <div class="input-group mb-3">
                        
                               <a href="tel:<?php echo $mobo; ?>" class="btn btn-sm btn-primary"> <span class="fas fa-phone"></span> </a> 
                                <b>  Mobile :- </b> <?php echo $mobo; ?>
                            <br>
                            
                        </div>     
                            
                        <div class="form-group">
                        <span class="fas fa-home"></span><b> Address :- </b>
                        <?php echo $address; ?>
                        </div>
                    
              </div>

                
              <div class="mt-4 product-share">
              <?php 
                // if($fb!='')
                if(!empty($fb))
                {
                ?>
                 <a href="<?php echo $fb; ?>" class="text-gray" style="cursor: pointer;">
                  <i class="fab fa-facebook-square fa-2x"></i>
                </a>
             <?php       
                }
              ?>
              
              <?php 
                // if($twt!='')
                if(!empty($twt))
                {
                ?>
                  <a href="<?php echo $twt; ?>" class="text-gray" style="cursor: pointer;">
                  <i class="fab fa-twitter-square fa-2x"></i>
                </a>
                
             <?php       
                }
              ?>
              <?php 
                // if($insta!='')
                if(!empty($insta))
                {
                ?>
                 <a href="<?php echo $insta; ?>" class="text-gray" style="cursor: pointer;">
                 <i class="fab fa-instagram-square fa-2x"></i>
                </a>
             <?php       
                }
              ?>
               
               <?php 
                // if($fb!='')
                if(!empty($linke))
                {
                ?>
                 <a href="<?php echo $linke; ?>" class="text-gray" style="cursor: pointer;">
                 <i class="fab fa-linkedin fa-2x"></i>
                </a>
             <?php       
                }
              ?>
               
              </div>

            </div>
          </div>
          <div class="row mt-4">
            <nav class="w-100">
              <div class="nav nav-tabs" id="product-tab" role="tablist">
                <!-- <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Services</a> -->
                <!-- <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Reviews</a> -->
                <a class="nav-item nav-link active" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">More About us</a>
              </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent">
              <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
              
             
              <?php 
              $i = 1;
                   
                    foreach($l->result() as $row)  
                    {
                      ?> 
             
              <div class="col-sm-12">
            <div class="card card-solid">
            <div class="card-body pb-0">
            <div class="form-group">
                    <?php
                  
                    
                      $l1 = $row->l1;
                      $this->db->where('id',$l1);
                      $l1_d = $this->db->get('provider_level_one');
                      foreach($l1_d->result() as $l1_d)
                      {
                        echo $l1_d->l1;
                        echo "->"; 
                      }
                      $l2 = $row->l2;
                     
                        $this->db->where('id',$l2);
                        $l2_d = $this->db->get('provider_level_two');
                        foreach($l2_d->result() as $l2_d)
                        {
                          echo $l2_d->l2;
                          echo "->"; 
                        }
                      
                      
                      $l3 = $row->l3_id;
                      
                        $this->db->where('id',$l3);
                        $l3_d = $this->db->get('provider_level_three');
                        foreach($l3_d->result() as $l3_d)
                        {
                          echo $l3_d->l3;
                           
                        }
                        $where = "l1='$l1' and l2='$l2' and l3_id='$l3'";
                        $this->db->where($where);
                        $para = $this->db->get('provider_level_four');
                        foreach($para->result() as $para)
                        {
                          $para1 = $para->param_one;
                          $para2 = $para->param_two;
                          $para3 = $para->param_three;
                          $para4 = $para->param_four;

                        }
                      echo "<br>";
                      echo $para1;
                      echo "(".$row->p1.")";
                      echo ", ";
                      echo $para2;
                      echo " (".$row->p2.")";
                      echo ", ";
                      echo $para3;
                      echo " (".$row->p3.")";
                      echo ", ";
                      echo $para4;
                      echo " (".$row->p4.")";

                      echo "<br>";
                      echo "<div align='right' class='text-primary'><b>Price:- $row->price</b></div>";
                     
                     
                     
                    ?>
                    </div>
            </div>
            </div>
              </div>
                  <?php                                    
                
                 $i++;
                  }
                  ?>  
           
                 
              
              
                
              </div>
           
              <!-- <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">  </div> -->
              <div class="tab-pane fade show active" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab"> 
            
              <div class="form-group">
              
               <b><?php echo $heading1; ?></b>
              </div>
              <div class="form-group">
                <?php echo $content1; ?>
              </div>
              <div class="form-group">
              
               <b><?php echo $heading2; ?></b>
              </div>
              <div class="form-group">
                <?php echo $content2; ?>
              </div>
              <div class="form-group">
              
               <b><?php echo $heading3; ?></b>
              </div>
              <div class="form-group">
                <?php echo $content3; ?>
              </div>
            
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  </div>
 <!-- jQuery -->
<script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('/assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('/assets/dist/js/adminlte.min.js'); ?>"></script>
</body>
</html>
 