<style>
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }
  .sp-photo{
    width: 180px;
    box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%);
    height: 147px;
    border-radius: 100%;
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
            Vocational Training Details
          </h1>
        </div>
        
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="https://users.respicite.com/AdminController/sp_vocation_trainings"
                >Vocational Training</a
              >
            </li>
            <li class="breadcrumb-item active">Vocational Training Details</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
        <!--  Vocational Training #start -->
        <div class="card card-default in">
          <div class="card-header">
              <h3 class="card-title">Service Provider Info and Action</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
            <div class="row">
              <?php if(!empty($vocational_info)){
                $vocational_data = $vocational_info[0]['training_info'];
                ?>
                  <div class="col-md-6">
                    <div class="m-2">
                      <div class="text-center"><img class="sp-photo" src="<?php echo base_url($vocational_data->profile_photo);?>"></div>
                      
                      <br>
                      <table class="table ">
                        <tr>
                          <td>User ID </td>
                          <td><b><?php echo $vocational_data->user_id;?></b></td>
                        </tr>
                        <tr>
                          <td>Name </td>
                          <td><b><?php echo $vocational_data->fullname;?></b></td>
                        </tr>
                        <tr>
                          <td>Email </td>
                          <td><b><?php echo $vocational_data->email;?></b></td>
                        </tr>
                        <tr>
                          <td>Mobile No.  </td>
                          <td><b><?php echo $vocational_data->mobile;?></b></td>
                        </tr>
                      </table>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="m-2">
                      <table class="table ">
                          <tr>
                            <td>Training ID</td>
                            <td><b><?php echo $vocational_data->id;?></b></td>
                          </tr>
                          <tr>
                            <td>Training Status</td>
                            <td>
                              <?php
                                if($vocational_data->training_status == "in_creation"){
                                    echo "<p class='m-0 p-0 text-success'>In Creation</p>";
                                } elseif($vocational_data->training_status == "approval_pending"){
                                    echo "<p class='m-0 p-0 text-info'>Approval Pending</p>";
                                }elseif($vocational_data->training_status == "approved"){
                                    echo "<p class='m-0 p-0 text-success'>Approved</p>";
                                }elseif($vocational_data->training_status == "suspended"){
                                    echo "<p class='m-0 p-0 text-danger'>Suspended</p>";
                                }elseif($vocational_data->training_status == "approval_remark"){
                                    echo "<p class='m-0 p-0 text-success'>Approval Remark</p>";
                                }
                              ?>  
                            </td>
                          </tr>
                          <tr>
                            <td>Certification Status</td>
                            <td><b id="status_update"><?php echo $vocational_data->certification_status;?></b></td>
                          </tr>
                          <tr>
                            <td>Certification Action</td>
                            <td>
                                                            
                              <?php if($vocational_data->training_status == "approved"){
                                  echo "<button class='btn btn-sm btn-outline-primary m-1 disabled'>Training Approve</button>";
                                  if($vocational_data->certification_status == "requested"){
                                    echo "<button class='btn btn-sm btn-outline-primary m-1' data-v-id='$vocational_data->id' id='btn_certification_approve'>Certification Approve</button>";
                                  }else{
                                    echo "<button class='btn btn-sm btn-outline-primary m-1 disabled'>Certification Approve</button>";
                                  }
                              }else{
                                if($vocational_data->training_status != "in_creation"){
                                  echo "<button class='btn btn-sm btn-outline-primary m-1' data-v-id='$vocational_data->id' id='btn_approve'>Training Approve</button>";
                                }else{
                                  echo "<button class='btn btn-sm btn-outline-primary m-1 disabled'>Training Approve</button>";
                                }
                                  echo "<button class='btn btn-sm btn-outline-primary m-1 disabled'>Certification Approve</button>";
                              }?>
                                                            
                            </td>
                          </tr>
                        </table>
                    </div>
                  </div>
                <?php } ?>
             </div>
            </div>
        </div>
        <!-- Vocational Training #End -->       
                
        <!-- ------------------------- -->
        <!--  Vocational Training #start -->
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Vocational Training</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <?php if(!empty($vocational_info)){
                  $vocational_data = $vocational_info[0]['training_info'];
                  ?>
                  
                    <p class="mb-0"><b>Training Name</b></p>
                    <p><?php echo $vocational_data->training_name;?></p>
                    <p class="mb-0"><b>Training Description</b></p>
                    <p><?php echo $vocational_data->training_desc;?></p>
                                
                <?php } ?>
                <!-- <div class="m-2 p-2 border shadow rounded">
                </div> -->
              </div>
             </div>
            </div>
        </div>
        <!-- ------------------------- -->
        <!-- All Sub Sections #start -->
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">All Sub Sections</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
            <div class="row">              
              <?php if(!empty($vocational_info)){
                  $count_i = 1;
                  $section_data = $vocational_info[1]['section_info'];
                    foreach($section_data as $v){ ?>
                    <div class="col-md-6">
                          <div class="card m-2 border shadow rounded">
                            <div class="card-header">
                              <h3 class="card-title">Section <?php echo $count_i?></h3>
                            </div>
                            <div class="card-body">
                              <p class="mb-0"><b>Section Name : </b></p>
                              <p><?php echo $v->section_name;?></p>
                              <p class="mb-0"><b>Section Description : </b></p>
                              <p><?php echo $v->section_desc;?></p>
                            </div>
                          </div>
                        <!-- card #end  -->
                    </div>
                    <?php $count_i++; } }?> 
                  </div>
                </div>
        </div>
        <!-- All Sub Sections #end -->
         <!-- ------------------------- -->                 
        <!-- Training Content file #start  -->
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Content file</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
                    <div class="row">
                      <div class="col-3"></div>
                      <div class="col-md-6"> 
                        <div class="m-2 border shadow rounded">
                            <div class="card-header">
                              <h3 class="card-title">Training Content file</h3>
                            </div>
                                  
                        <div class="card-body">
                        <?php if(!empty($vocational_info)){
                          $vocational_data = $vocational_info[0]['training_info'];
                          $file_mine = $vocational_data->file_mine;
                          $file_name = $vocational_data->training_file_loc;
                          $file_content = base_url("uploads/vocational_training/$vocational_data->training_file_loc");
                          if($file_mine == "png" || $file_mine == "jpg" || $file_mine == "jpeg" || $file_mine == "gif"){
                            echo "<img class='w-100' src='$file_content' alt=''>";
                          }else{
                            echo "<a href='$file_content' target='_black'>Click here to Download.</a>";
                          }
                        }?>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
        </div>
        <!-- Training Content file #end  -->        
        <!-- ------------------------- -->        
        <!-- Training Approval Remark #start -->
        <!-- Training Approval Modal #start-->
        <div class="modal fade" id="modal-approval" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Training Approval Remark</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>      
              <div class="modal-body">
                <div class="form-group">
                  <input type="hidden" id="voc_training_id_ap" value="<?php echo $vocational_data->id;?>">
                  <textarea class="form-control" id="remark_ap" rows="3" placeholder="Type Here ..."></textarea>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btn_remark_ap" class="btn btn-primary">Approval Remark</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Training Approval Remark</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
            <?php
              if($vocational_data->training_status != "in_creation"){
                echo "<button class='btn btn-sm btn-outline-primary m-1' data-toggle='modal' data-target='#modal-approval'>Add Remark</button>";
              }else{
                echo "<p class='m-0 p-0 text-success'>SP Resquest Pending</p>";
              }
            ?>
            <div class="row">
              <?php if(!empty($vocational_info[3]['approval_remark'])){
                $approval_remark = $vocational_info[3]['approval_remark'];
                ?>
                  <div class="col-md-12">
                    <div class="m-2">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <td>Remark</td>
                            <td>Date</td>
                            <td>Action</td>
                          </tr>
                        </thead>
                          <?php foreach($approval_remark as $v){
                            echo "<tr>";
                            echo "<td>$v->remark</td>";
                            echo "<td>".date("d-m-Y", strtotime($v->remark_date))."</td>";
                            echo "<td><button data-r-id='$v->id' class='btn btn-sm btn-outline-danger btn-delte-remark'>Delete</button></td>";
                            echo "</tr>";
                          }?>
                      </table>
                    </div>
                  </div>
                <?php } ?>
             </div>
            </div>
        </div>
        <!-- Training Approval Remark  #End -->
        <!-- ------------------------- -->
        <!-- Training Suspend Remark #start -->
         <!-- Training Suspend Modal #start-->
         <div class="modal fade" id="modal-suspend" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Training Suspend Remark</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>      
              <div class="modal-body">
                <div class="form-group">
                  <input type="hidden" id="voc_training_id" value="<?php echo $vocational_data->id;?>">
                  <textarea class="form-control" id="remark" rows="3" placeholder="Type Here ..."></textarea>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btn_remark_suspend" class="btn btn-primary">Suspend</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- Training Suspend Modal #end-->        
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Training Suspend Remark </h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
            <?php
              if($vocational_data->training_status != "in_creation"){
                echo "<button class='btn btn-sm btn-outline-primary m-1' data-toggle='modal' data-target='#modal-suspend'>Add Suspend Remark</button>";
              }else{
                echo "<p class='m-0 p-0 text-success'>SP Resquest Pending</p>";
              }
            ?>
          
            <div class="row">
              <?php if(!empty($vocational_info[2]['remark_info'])){
                $remark_info = $vocational_info[2]['remark_info'];
                ?>
                  <div class="col-md-12">
                    <div class="m-2">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <td>Remark</td>
                            <td>Date</td>
                            <td>Action</td>
                          </tr>
                        </thead>
                          <?php foreach($remark_info as $v){
                            echo "<tr>";
                            echo "<td>$v->remark</td>";
                            echo "<td>".date("d-m-Y", strtotime($v->remark_date))."</td>";
                            echo "<td><button data-r-id='$v->id' class='btn btn-sm btn-outline-danger btn-delte-remark'>Delete</button></td>";
                            echo "</tr>";
                          }?>
                      </table>
                    </div>
                  </div>
                <?php } ?>
             </div>
            </div>
        </div>
        <!-- Training Suspend Remark #End -->
        <!-- ------------------------- -->
        <!--  Certification Suspend Remark #start -->
        <!-- Certification Suspend Modal #start-->
        <div class="modal fade" id="modal-certification-suspend" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Certification Suspend Remark</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>      
              <div class="modal-body">
                <div class="form-group">
                  <input type="hidden" id="voc_cetification_id" value="<?php echo $vocational_data->id;?>">
                  <textarea class="form-control" id="remark_cetification" rows="3" placeholder="Type Here ..."></textarea>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btn_cetification_remark" class="btn btn-primary">Suspend</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- Certification Suspend Modal #end-->
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Certification Suspend Remark</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
            <?php
              if($vocational_data->certification_status == "requested"){
                echo "<button class='btn btn-sm btn-outline-primary m-1' data-toggle='modal' data-target='#modal-certification-suspend'>Add Suspend Remark</button>";
              }else{
                echo "<p class='m-0 p-0 text-success'>SP Resquest Pending</p>";
              }
            ?>
          
            <div class="row">
              <?php if(!empty($vocational_info[4]['certification_remark'])){
                $certification_remark = $vocational_info[4]['certification_remark'];
                ?>
                  <div class="col-md-12">
                    <div class="m-2">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <td>Remark</td>
                            <td>Date</td>
                            <td>Action</td>
                          </tr>
                        </thead>
                          <?php foreach($certification_remark as $v){
                            echo "<tr>";
                            echo "<td>$v->remark</td>";
                            echo "<td>".date("d-m-Y", strtotime($v->remark_date))."</td>";
                            echo "<td><button data-r-id='$v->id' class='btn btn-sm btn-outline-danger btn-delte-remark'>Delete</button></td>";
                            echo "</tr>";
                          }?>
                      </table>
                    </div>
                  </div>
                <?php } ?>
             </div>
            </div>
        </div>
                          
        <!-- Certification Content file #start  -->
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Certification Content file</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
                    <div class="row">
                      <div class="col-3"></div>
                      <div class="col-md-6"> 
                        <div class="m-2 border shadow rounded">
                            <div class="card-header">
                              <h3 class="card-title">Certification Content file</h3>
                            </div>
                                  
                        <div class="card-body">
                        <?php if(!empty($vocational_info)){
                          $vocational_data = $vocational_info[0]['training_info'];
                          if(!empty($vocational_data->certification_file_loc)){
                            $file_content = base_url("uploads/vocational_training/$vocational_data->certification_file_loc");
                            echo "<a href='$file_content' target='_black'>Click here to Download.</a>";
                          }else{
                            echo "<p>Certification request pending by SP.</P>";
                          }
                        }?>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
        </div>
        <!-- Certification Content file #end  -->   
        <!--  Certification Suspend Remark #End -->
         <!-- ------------------------- -->  
    </div> <!--main row #end-->
  </div>
</section>
</div>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<script>
  const BASE_URL = '<?php echo base_url();?>';
  $("#btn_remark_suspend").click(function(){
    var remark_v = $("#remark").val();
    var voc_tra_id = $("#voc_training_id").val();
    remark_v = remark_v.trim();
    if(remark_v != ""){
      $.post(`${BASE_URL}AdminController/ajax_work/ADD_REMARK`,
      {
        v_id:voc_tra_id,
        remark:remark_v,
        r_type:"suspended"
      },
      function(res){
        if(res.MSG == "OK"){
          alert("Successfully update Remark.");
          $("#modal-suspend").modal("hide");
          document.location.reload()
        }
      })
    }else{
      alert("Please enter remark...");
    }
  });

  $("#btn_remark_ap").click(function(){
    var remark_v = $("#remark_ap").val();
    var voc_tra_id = $("#voc_training_id_ap").val();
    remark_v = remark_v.trim();
    if(remark_v != ""){
      $.post(`${BASE_URL}AdminController/ajax_work/ADD_REMARK`,
      {
        v_id:voc_tra_id,
        remark:remark_v,
        r_type:"approval_remark"
      },
      function(res){
        if(res.MSG == "OK"){
          alert("Successfully add Remark.");
          document.location.reload();
        }
      })
    }else{
      alert("Please enter remark...");
    }
  });

  $("#btn_cetification_remark").click(function(){
    var remark_v = $("#remark_cetification").val();
    var voc_tra_id = $("#voc_cetification_id").val();
    remark_v = remark_v.trim();
    if(remark_v != ""){
      $.post(`${BASE_URL}AdminController/ajax_work/ADD_REMARK`,
      {
        v_id:voc_tra_id,
        remark:remark_v,
        r_type:"certification_suspended"
      },
      function(res){
        if(res.MSG == "OK"){
          alert("Successfully add Remark.");
          document.location.reload();
        }
      })
    }else{
      alert("Please enter remark...");
    }
  });

  // --remove #start
  $(document).on("click",".btn-delte-remark",function(){
    var r_id = $(this).data("r-id");
    var box_row = $(this).parent().parent();
    if(confirm("Are you sure you want to delete?")){
      $.get(`${BASE_URL}AdminController/ajax_work/REMOVE_REMARK?r=${r_id}`,function(res){        
        if(res.MSG == "OK"){
          box_row.remove();
          alert("Successfully Delete this section");
        }
      });
    }
  })
  // --remove #end

  // --btn_approve #start
  $("#btn_approve").click(function(){
    var v_id = $(this).data("v-id");
    if(confirm("Are you sure you want to approved Training?")){
      $.get(`${BASE_URL}AdminController/ajax_work/APPROVED?vid=${v_id}`,function(res){        
        if(res.MSG == "OK"){
          document.location.reload();
          alert("Successfully Approved");
        }
      });
    }
  })
  // --btn_approve #end

  // --btn_certification_approve #start
  $("#btn_certification_approve").click(function(){
    var v_id = $(this).data("v-id");
    if(confirm("Are you sure you want to Approved Certification?")){
      $.get(`${BASE_URL}AdminController/ajax_work/CERTIFICATION_APPROVED?vid=${v_id}`,function(res){
        console.log(res);     
        if(res.MSG == "OK"){
          document.location.reload();
          alert("Successfully Approved");
        }

        if(res.MSG == "approval_pending"){
          alert("Training Approval Pending")
        }
      });
    }
  })
  // --btn_certification_approve #end

  
</script>
