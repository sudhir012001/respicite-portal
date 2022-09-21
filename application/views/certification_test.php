<body class="hold-transition sidebar-mini">
<div id="modalHomeEvents" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;">
        <b>Service Details</b>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
    
        <input type="hidden" name="eventId" id="eventId"/>
        	<span id="idHolder"></span>	
            
        </div>
        <div class="modal-footer">
          
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Certification</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Solutions</a></li>
              <li class="breadcrumb-item active">Assessment List</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
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
        ?>
  <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <?php 
            foreach($s->result() as $row)
            {
                $this->db->where('c_group',$row->c_group);
                $rp = $this->db->get('solutions')->row();
               
        ?>
        <div class="card card-default collapsed-card">
          <div class="card-header">
            <h3 class="card-title"><?php echo $row->c_group; ?> </h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
              </button>
              
            </div>
          </div>
          <!-- /.card-header -->
          
          <div class="card-body">
          <div class="row">
          <?php 
                  $fstatus = $row->status;
                  if($fstatus=='1')
                  {
                   ?>
                   <div class="col-12 card-footer mb-2" align="right">Assessment Pending</div>
                   
           
                  <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                  <div class="card card-primary h-100">
                  
                      <div class="card-header">
                        <h3 class="card-title"><?php echo $rp->start_head; ?></h3>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <strong><i class="fas fa-book mr-1"></i> <?php echo $rp->start_detail_head; ?></strong>

                        <p class="text-muted">
                        <?php echo $rp->start_detail; ?>
                        </p>

                <hr>
                
                   
                <hr>
               
                          <p class="text-muted">
                            
                            <a href="<?php echo base_url().'UserController/certification_test'; ?>" class="btn btn-primary btn-block"><b>Take Assessment</b></a>
                          </p>
                   
                <?php
                    }
                    else
                    {
                        $this->db->where('c_group','Positive Parenting');
                        $r = $this->db->get('certificate_template')->row();
                        
                        if($row->per>=$r->per)
                        {
                     
                ?>
                  <div class="col-12 card-footer mb-2" align="right">Download Certificate</div>
                   
           
                   <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                   <div class="card card-primary h-100">
                   
                       <div class="card-header">
                         <h3 class="card-title"><?php echo $rp->success_head; ?></h3>
                       </div>
                       <!-- /.card-header -->
                       <div class="card-body">
                         <strong><i class="fas fa-book mr-1"></i> <?php echo $rp->success_detail_head; ?></strong>
         
                         <p class="text-muted">
                         <?php echo $rp->success_detail; ?>
                         </p>
         
                         <hr>
                         
                            
                         <hr>
                        
                        <p class="text-muted">
                          
                          <div class="col-12 card-footer mb-2" align="right"><a href="<?php echo base_url().'OtherAjax/download_certificate.php?email='.base64_encode($user['email']).'&& group='.$row->solution; ?>">Download Certificate</div>
                          <?php 
                              }
                              else
                              {
                                  if($row->rqst=='1')
                                  {
                           
                          ?>
                          <div class="col-12 card-footer mb-2" align="right">Your Request is pending please wait for approval.</div>
                          <?php 
                             }
                             else
                             {

                            
                             ?>
            <div class="col-12 card-footer mb-2" align="right"><a href="<?php echo base_url().'UserController/request_for_retest/'.$row->id; ?>">Request for Reassessment</a></div>
                   <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                   <div class="card card-primary h-100">
                   
                       <div class="card-header">
                         <h3 class="card-title"><?php echo $rp->fail_head; ?></h3>
                       </div>
                       <!-- /.card-header -->
                       <div class="card-body">
                         <strong><i class="fas fa-book mr-1"></i> <?php echo $rp->fail_detail_head; ?></strong>
         
                         <p class="text-muted">
                         <?php echo $rp->fail_detail; ?>
                         </p>
         
                         <hr>
                         
                            
                      
                        
                        
                          <?php 
                           }
                              }
                        
                    }
                    
                ?>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          
            </div>
          
          </div>
          
          <!-- /.card-body -->
          
          </div>
         
        </div>
              <?php
            }
        ?>
           
       
        </div>
     
  </section>
    