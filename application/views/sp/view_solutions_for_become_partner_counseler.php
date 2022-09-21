<?php 
// echo "<pre>";
// print_r($s->result());
// echo "</pre>";
// die;



;?>
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
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Training & Certification</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("SpController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Training & Certification Lists</li>
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
        
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Select Product Group </h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
              </button>
              
            </div>
          </div>
          <!-- /.card-header -->
          
          <div class="card-body">
          <div class="row">
          
                
          <div class="col-12 card-footer mb-2 text-center">Available Career Group</div>
                    
                    
          <?php 
            foreach($s->result() as $row)
            {
               
          ?>
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                    <div class="card card-primary h-100">
                  
                      <div class="card-header">
                        <h3 class="card-title"><?php echo $row->c_group; ?></h3>
                      </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Detail</strong>
                <?php 
                    $solutions = $this->Sp_model->get_solution($row->c_group);
                    // echo "<pre>";
                    // print_r($solutions->result_array());
                    // echo "</pre>";
                ?>
                <p class="text-muted">
                   <?php 
                    foreach($solutions->result() as $sl)
                    {
                        echo $sl->solution.", ";
                    }
                    ?>
                </p>

                <strong><i class="fas fa-info-circle mr-1"></i> Status</strong>
                <p class="text-muted">
                  <?php
                  $grp = $row->c_group;
                  $where = "email='$email' and grp='$grp'";
                $this->db->where($where);
                $d_row = $this->db->get('partner_counselor_status')->row();
                // echo "<pre>Status";
                // print_r($d_row);
                // echo "</pre>";
                if(isset($d_row->status))
                {$status = $d_row->status;}
                else{$status = '';}
                if($status == 'ua')
                {
                  ?>
                  <div class="col-12 card-footer mb-2" align="right">Approval Pending</div>
              <?php
                }
                else if($status == 'Ap')
                {
                  ?>
                  </p>
                  <hr>
                 
                  <p class="text-muted">
                    <a href="<?php echo base_url().'SpController/onboarding_exam/'.$row->c_group; ?>" class="btn btn-primary btn-block"><b>Proceed To Exam</b></a>
                  </p>
                    
               <?php
                }
                else if($status == 'Rp')
                {
                  ?>
                  </p>
                  <hr>
                 
                  <p class="text-muted">
                    
                    <a href="#"><b>
                    <?php 
                      if($d_row->exam_passed == 'Pass')
                      {
                        echo 'Onboarding Assessment Passed, Please Wait for Further Communication';
                      }
                      else{
                        echo 'Onboarding Assessment Failed, Please Wait for Further Communication';
                      }
                    ?>
                   </b></a>
                  </p>
               <?php
                }
                else if($status == 'Ac')
                {
                  ?>
                  </p>
                  <hr>
                 
                  <p class="text-muted">
                    
                    <a href="<?php echo base_url().'SpController/ce_exam/'.$row->c_group; ?>" class="btn btn-primary btn-block"><b>Proceed To Certification Exam</b></a>
                  </p>
               <?php
                }
                else if($status == 'St')
                {
                  ?>
                  </p>
                  <hr>
                 
                  <p class="text-muted">
                    
                    <b>Training Scheduled</b>
                  </p>
               <?php
                }
                else if($status == 'Tc')
                {
                  ?>
                  </p>
                  <hr>
                 
                  <p class="text-muted">
                    
                  <a href="<?php echo base_url().'SpController/request_certification_exam/'.$row->c_group; ?>" class="btn btn-primary btn-block"><b>Request for Certification Exam</b></a>
                  </p>
               <?php
                }
                else if($status == 'Ca')
                {
                  ?>
                  </p>
                  <hr>
                 
                  <p class="text-muted">
                    
                  <b>Certification Exam Approval Pending</b>
                  </p>
               <?php
                }
                else if($status == 'CRp')
                {
                  ?>
                  </p>
                  <hr>
                 
                  
                   
                    <p class="text-muted">
                    
                      <a href="<?php echo base_url().'OtherAjax/ce_certificate.php?email='.base64_encode($email).'&grp='.base64_encode($grp); ?>" class="btn btn-primary btn-block"><b>Download Certificate</b></a>
                    </p>
                    
                    
                   
               <?php
                }
                else if($status == 'CRf')
                {
                  ?>
                  </p>
                  <hr>
                 
                  
                   
                    <p class="text-muted">
                    
                      <a href="<?php echo base_url().'SpController/request_certification_exam/'.$row->c_group; ?>" class="btn btn-primary btn-block"><b>Certification Exam Failed. Request for Reassessment</b></a>
                    </p>
                    
                   
               <?php
                }
                else
                {
                  ?>
                  </p>
                  <hr>
                 
                  <p class="text-muted">
                    
                    <a href="<?php echo base_url().'SpController/request_onboarding_exam/'.$row->c_group; ?>" class="btn btn-primary btn-block"><b>Request for Onboarding Exam</b></a>
                  </p>
               <?php
                }
                ?>
                               
                
                
               
              </div>
             
              <!-- /.card-body -->
            </div>
            </div>
            <?php 
            }
           ?>
            <!-- /.card -->
           
            
       
        </div>
  </section>
        <script
  src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  
        <script>
$(document).on("click", ".open-homeEvents", function () {
      var eventId = $(this).data('id');
     $.ajax({
            type : 'post',
            url : '<?php echo base_url()."OtherAjax/fetch_record.php"; ?>', //Here you will fetch records 
            data :  'rowid='+ eventId, //Pass $id
            success : function(data){
            $('#idHolder').html(data);//Show fetched data from database
            }
        });
     
});
  
</script>
        