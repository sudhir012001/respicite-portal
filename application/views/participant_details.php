<style>
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }
</style>
<div class="content-wrapper bg-white" style="min-height: 706px">
  <!-- Content Header (Page header) -->
  <section
    class="content-header mb-3"
    style="
      padding: 6px 0.5rem;
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
    "
  >
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em; color: #7f7f7f">
            Participant Details
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="<?php echo base_url("UserController/vocational_training");?>"
                >Back to View Trainings</a
              >
            </li>
            <li class="breadcrumb-item active">Participant Details</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
    
  <section class="content ">
    <div class="container-fluid">      
        <!-- main content #start -->
        <div class="card">
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th>Email id</th>
                      <th>Participant Name</th>
                      <th>Apply Date</th>
                      <th>Training Approval Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($participant_info)){ foreach($participant_info as $v){ ?>
                      <tr>
                        <td><?php echo $v->user_email?></td>
                        <td><?php echo $v->fullname?></td>
                        <td><?php echo $v->apply_date?></td>
                        <td><?php
                          if($v->training_status == "approval_pending"){
                            echo "<button class='btn btn-sm btn-outline-primary btn-change-status' data-pid='$v->id' data-req-type='approved'>Click to Approved</button>";
                          }elseif($v->training_status == "approved"){
                            echo "<button class='btn btn-sm btn-outline-info btn-change-status' data-pid='$v->id' data-req-type='completed'>Click to Completed</button>";
                          }elseif($v->training_status == "completed"){
                            echo "<p class='text-success m-0 p-0'>Completed</p>";
                          }
                        ?></td>
                      </tr>                      
                    <?php } }else{
                      echo "<tr><td colspan='5' class='text-center'>Data Not Found.</td></tr>";
                    } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
        <!-- main content #end -->

    </div>
  </section>
</div>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

<script>
  const BASE_URL = '<?php echo base_url();?>';
  $(document).on("click",".btn-change-status",function(){    
    let pid = $(this).data("pid");
    let req_type = $(this).data("req-type");
    let this_tag = $(this).parent();

   $.get(`${BASE_URL}UserController/sp_ajax_work/${pid}/PARTICIPANT_REQUEST?req_type=${req_type}`,function(res){
    let res_json = JSON.parse(res);    
    if(res_json.MSG == "OK"){
      if(req_type == "approved"){
        this_tag.html("<p class='text-success m-0 p-0'>Approved</p>");
      }
      if(req_type == "completed"){
        this_tag.html("<p class='text-success m-0 p-0'>Completed</p>");
      }
    }
    if(res_json.MSG == "ERROR" || res_json.MSG == "EMPTY"){
      alert("Something is wrong.");
    }
   }); 

  });
</script>
