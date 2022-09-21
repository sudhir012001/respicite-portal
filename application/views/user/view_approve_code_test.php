<style>
  a{color:black;}
  .title-ass{font-size:1.3rem;}
  .bg-card .card-header,.btn-primary{
    background-color:#fc9928;
    color:white;
  }
  .btn-primary{
    border:none;
  }
  hr{
    border-top: 1px solid #fc9928;
  }

  .card-title{
    position: relative;
  }

  .rounde-box{
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 100%;
      border: 4px solid white;
      line-height: 2rem;
      text-align: center;
      font-weight: bolder;
      position: absolute;
      left: 14.8rem;
      background: #fc9928;
  }

  .btn-my {
      color: #fff;
      background-color: #fc9928;
      border-color: #fc9928;
    }

    .btn-my:hover {
      color: #fff;
      background-color: #fc9928;
      border-color: #fc9928;
    }
    .btn-my:active {
      color: #fff;
      background-color: #fc9928;
      border-color: #fc9928;
    }
</style>
<body class="hold-transition sidebar-mini ">
<div id="modalHomeEvents" class="modal fade" role="dialog">
    <div class="modal-dialog ">

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
<div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Solutions</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/request_code'; ?>">Purchase code</a></li>
              <li class="breadcrumb-item">Take Assessment</li>
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
        <?php 
        $url_msg = $this->session->flashdata("url_msg");
        if(!empty($url_msg)){
          if($url_msg == "view_code"){
            echo '<div class="alert alert-info">
            Take assessment pending
          </div>';
          }
        }?>
      
        <!-- SELECT2 EXAMPLE -->
        <?php
        // echo "<pre>";
        // print_r($s->result_array());
        // echo "</pre>";
        // die;
        ?>
        <?php 

            foreach($s->result() as $row)
            {
             //echo $row->solution;die();
        ?>
        <div class="card card-default collapsed-card">
          <div class="card-header">
            <h3 class="card-title"><?php echo $row->display_solution_name; ?> Code 
            (<?php echo $row->code; ?>)</h3>

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
                  
                  //Sudhir's comment - Below condition needs to be made more robust
                  if($row->name=='' && $row->dob=='')
                  {
                    $lc = 0;
                    // echo "Validation error : Either name or date of birth is blank<br>";
                   
                  }
                  else
                  {
                    $lc = 1;
                  }
                  
                 
              
                  if($fstatus=='Ap')
                  {
                    ?>
                    <div class="col-12 mb-2 text-right title-ass pr-3" >Assessment Pending</div>
                   
                   <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                  <div class="card  h-100 bg-card shadow">
                  
                      <div class="card-header">
                        <h3 class="card-title"><?php echo "Update Personal Details";?><div class="rounde-box">1</div></h3>
                       
                      </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Detail</strong>
                <?php 
                    if($lc==0)
                    {
                ?>
                <p class="text-muted">
                   <?php echo "Please Fill Personal Details First then take assessment"; ?>
                </p>

                <hr>
               
                <p class="text-muted">
                  
                  <a href="<?php echo base_url().'BaseController/fill_personal_detail/'.base64_encode($row->code); ?>" class="btn btn-my btn-block"><b>Update Details</b></a>
                </p>
                <?php      
                    }
                    else
                    {
                ?>
                <p class="text-muted">
                   <?php echo "Detail Filled You can Continue Your assessment"; ?>
                </p>

                <hr>
               
                <p class="text-muted">
                  
                  Take Assessment
                </p>
                <?php      
                    }
                ?>
                
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          
            </div>         
            
                    <?php
                  $i = 1;
                  $step_no = 2;
                  $num = $this->Base_model->solution_list($email,$row->solution,$row->code)->num_rows();
                  //echo "No of rows of the solution :".$num.$row->solution."<br>";
                 
                  $solutions = $this->Base_model->solution_list($email,$row->solution,$row->code);
                  // echo "<pre>";
                  // print_r($solutions->result_array());
                  // echo "</pre>";
                  // die;
                  foreach($solutions->result() as $part)
                  {
                      /*
                      echo "<br><pre>";
                      print_r($part);
                      */
            ?>
          <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
          <div class="card  h-100 bg-card shadow">
          
              <div class="card-header ">
                <h3 class="card-title"><?php echo $part->dis_solution; ?><div class="rounde-box"><?php echo $step_no++;?></div></h3>
                
                
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Detail</strong>

                <p class="text-muted">
                <?php echo $part->details; ?>
                </p>

                <hr>
                <?php 
                    $status = $part->status;

                      if($status=="Ap")
                      {                 
                ?>
                <strong><i class="fas fa-info-circle mr-1"></i> Status</strong>
                <p class="text-muted">
                                       Assessment Pending 
                    </p>
                <hr>
                <?php 
                      if($i!=100 && $lc!=0)
                      {
                        for($j=1;$j<=$num;$j++)
                        {
                          if($j==$i)
                          {
                  ?>
                          <p class="text-muted">
                            
                            <a href="<?php echo base_url().'BaseController/'.$part->link.'/'.base64_encode($row->code); ?>" class="btn btn-my btn-block"><b>Take Assessment</b></a>
                          </p>
                      <?php
                          $i=100;
                          }
                        }
                      }
                      else
                      {
                        echo '<p class="text-muted">';
                        echo "<b>Assessment Locked</b>";
                        echo '</p>';
                      }
                     
                    }
                    else
                    {
                      $i++;
                       ?>
                        <strong><i class="fas fa-info-circle mr-1"></i> Status</strong>
                        <p class="text-muted">
                                              Assessment Completed 
                            </p>
                        <hr>
                        <p class="text-muted">
                          <b>Take next Assessment</b>
                        </p>
                <?php
                    }
                    
                ?>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          
            </div>
          
           <?php 
                  }  
                  $i = 1;            
           ?>
            <!-- /.row -->
           
        <?php 
              }
              else if($fstatus=="Rp")
              {
                ?>
                
                <div class="card  col-12 h-100">
                <div class="card-footer">
                
                <div class="row"><div class="col-12">Assessment Completed</div></div>
                </div>
                </div>
                <div class="card  col-12 h-100">
                <div class="card-footer">
                
                <div class="row"> 
                  <div class="col-6">Report Pending</div> 
                  <!--<div class="col-6" align="right"><a href="<?php //echo base_url().'OtherAjax/download_report.php?code='.base64_encode($row->code); ?>">Download Report</a></div>-->
                  
                  <?php 
                      if($row->solution == 'OCS'){ ?>
                            <!--<div class="col-6" align="right"><button onclick="window.open('https://faith-n-hope.com/services/reports','_blank')" class="btn btn-sm btn-outline-primary btn-open-modal view-report">Download Report</button></div>-->
                            <div class="col-6" align="right"><a href="<?php echo 'https://faith-n-hope.com/services/reports/'.base64_encode($row->code); ?>" _blank>Download Report</a></div>
                        
                        <?php }else{ ?>
                            <div class="col-6" align="right"><a href="<?php echo base_url().'OtherAjax/download_report.php?code='.base64_encode($row->code); ?>">Download Report</a></div>
                     <?php }
                   ?>
                  </div>
                
                </div>
                </div>
                
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
        