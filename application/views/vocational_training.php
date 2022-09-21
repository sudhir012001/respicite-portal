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
            Vocational Training
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="https://users.respicite.com/UserController/dashboard"
                >Dashboard</a
              >
            </li>
            <li class="breadcrumb-item active">Vocational Training</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
    <!-- approval certification modal #start.-->
    <div class="modal fade" id="modal-approval-certification" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Upload Certification Content</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>      
          <div class="modal-body">
          <div class="file-error p-2 text-danger"></div>
            <div class="form-group">
              <div class="input-group">
                <div class="custom-file">
                  <form action="" method="post" id="form_cerfifcation_requrest" enctype="multipart/form-data">
                    <label for="content_file">Upload Training Content, The only zip file, and file size should be 2MB.</label>
                    <input type="file" name="file_content" class="custom-file-input" id="content_file">
                    <label class="custom-file-label" for="content_file">Choose file </label>
                    <div class="set-sp-info"></div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="form_cerfifcation_requrest" class="btn btn-primary btn-disable-req">Upload</button>
          </div>
        </div>
      </div>
    </div>
    <!-- approval certification modal #end.-->

  <section class="content ">
    <div class="container-fluid">      
          <p class="text-right"><a class="btn btn-sm btn-outline-primary" href="<?php echo base_url('UserController/vocational_training_add');?>">Add New Vocational Training</a></p>
         
        <!-- main content #start -->
        <div class="card">
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th>Training ID</th>
                      <th>Training Name</th>
                      <th class="text-center">Action</th>
                      <th class="text-center">Training Approval Status</th>
                      <th class="text-center">Certification Request Status</th>
                      <th class="text-center">Participants Details</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($vocational_info)){ foreach($vocational_info as $v){ ?>                    
                 <tr>  
                    <td><?php echo $v->id;?></td>
                    <td style="width:65%;white-space: break-spaces;"><p class="m-0 p-0"><?php echo $v->training_name;?></p></td>                    
                    <td class="text-center">
                      <a class="btn btn-sm btn-outline-primary m-1" href="<?php echo base_url('UserController/vocational_training_details/'.$v->id);?>">Details</a>
                      <?php
                        if($v->training_status == "in_creation"){
                            echo "<a class='btn btn-sm btn-outline-info m-1' href='".base_url('UserController/vocational_training_edit/'.$v->id)."'>Edit</a>";
                            echo "<button data-vid='$v->id' class='btn btn-sm btn-outline-danger m-1 btn-delete-this'>Delete</button>";
                        } elseif($v->training_status == "approval_pending"){
                            echo "<button class='btn btn-sm btn-outline-danger m-1 disabled'>Delete</button>";
                            echo "<a class='btn btn-sm btn-outline-info m-1 disabled' href='javascript:void(0);'>Edit</a>"; 
                        }elseif($v->training_status == "approval_remark" || $v->training_status == "suspended"){
                            echo "<a class='btn btn-sm btn-outline-info m-1' href='".base_url('UserController/vocational_training_edit/'.$v->id)."'>Edit</a>";
                            echo "<button data-vid='$v->id' class='btn btn-sm btn-outline-danger m-1 btn-delete-this'>Delete</button>";
                        }
                      ?>
                    </td> 
                    <td class="text-center">
                      <?php
                        if($v->training_status == "in_creation"){
                            echo "<button data-vid='$v->id'  data-req-type='approval_pending' class='btn btn-sm btn-outline-info btn-approval-request'>Approval Request</button>"; 
                        } elseif($v->training_status == "approval_pending"){
                            echo "<p class='text-info'>Approval Pending</p>";
                        }elseif($v->training_status == "approved"){
                            echo "<p class='text-success'>Approved</p>";
                        }elseif($v->training_status == "suspended"){
                            echo "<button data-vid='$v->id' data-req-type='approval_pending' class='btn btn-sm btn-outline-info btn-approval-request'><b class='text-danger'>(Suspended)</b> Re-Approval</button>";                            
                        }elseif($v->training_status == "approval_remark"){
                            echo "<button data-vid='$v->id' data-req-type='approval_pending' class='btn btn-sm btn-outline-info btn-approval-request'>Re-Approval Request</button>"; 
                        }
                      ?>
                    </td>
                    <td class="text-center">                      
                      <?php
                      if($v->training_status == "approved"){
                          if($v->certification_status == null){
                              echo "<button data-vid='$v->id' data-file-name='$v->certification_file_loc' data-req-type='requested' class='btn btn-sm btn-outline-info btn-certification-request'>Request for Certification</button>"; 
                          } elseif($v->certification_status == "requested"){
                              echo "<p class='text-info'>Approval Pending</p>";
                          }elseif($v->certification_status == "approved"){
                              echo "<p class='text-success'>Approved</p>";
                          }elseif($v->certification_status == "suspended"){
                              echo "<button data-vid='$v->id' data-file-name='$v->certification_file_loc'  data-req-type='requested' class='btn btn-sm btn-outline-info btn-certification-request'><b class='text-danger'>(Suspended)</b>, Re-Request</button>";                              
                          }
                      }else{
                        echo "<p class='text-danger'>Training Approval Pending</p>";
                      }
                      ?>
                    </td>
                    <td>
                      <a href="<?php echo base_url('UserController/participant_details/'.$v->id)?>" class='btn btn-sm btn-outline-info'>Click Here</a> 
                    </td>
                </tr> 
                <?php } }else{ echo "<tr><td colspan='6' class='text-center'>Data Not Found.</td></tr>";} ?>
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
  // --remove #start
  $(document).on("click",".btn-delete-this",function(){
    var vid = $(this).data("vid");
    var row = $(this).parent().parent();
    if(confirm("Are you sure you want to delete?")){
        $.get(`${BASE_URL}UserController/delete_itme/${vid}/REMOVE_VOCATIONAL`,function(res){
          let res_json = JSON.parse(res);
          if(res_json.MSG == "OK"){
            row.remove();
            alert("Successfully Delete this section");
          }
        });
      }
    });
  // --remove #end

  $(document).on("click",".btn-certification-request",function(){    
    // var this_box = $(this).parent();
    var vid = $(this).data("vid");
    var req_type = $(this).data("req-type");
    var file_name = $(this).data("file-name");
    $("#modal-approval-certification").modal("show");
    $(".set-sp-info").html(`
      <input type="hidden" name="vid" value="${vid}" data-req-type="${req_type}">
      <input type="hidden" name="req_type" value="${req_type}">
      <input type="hidden" name="file_name" value="${file_name}">
    `);
  });

  $("#form_cerfifcation_requrest").submit(function(e){
      e.preventDefault();
      let form_data = new FormData(e.target);
      let file_err_show = $(".file-error");
      let btn_submit = $(".btn-disable-req");
      btn_submit.attr("disabled","disabled");
      fetch(`${BASE_URL}UserController/sp_ajax_work/${e.target.vid.value}/CERTIFICATION_REQUEST`,{
        method:"post",
        body:form_data
      })
      .then(function(req){return req.json();})
      .then(function(res){          
        if(res.MSG == "OK"){
          btn_submit.removeAttr("disabled");
          alert("Certifaction requiest sent.");
          document.location.reload();         
        }
        if(res.MSG == "ERROR_IMG"){
          btn_submit.removeAttr("disabled");
          file_err_show.html(res.ERROR_IMG_DESC);          
        }
      })
      
  });

  $(document).on("click",".btn-approval-request",function(){
    var this_box = $(this).parent();
    var vid = $(this).data("vid");
    var req_type = $(this).data("req-type");
        $.get(`${BASE_URL}UserController/sp_ajax_work/${vid}/TRAINING_REQUEST?req=${req_type}`,function(res){
          let res_json = JSON.parse(res);          
          if(res_json.MSG == "OK"){
            this_box.html("<p class='text-info'>Approval Pending</p>");
          }else{
            alert("Something is wrong.");
          }
        });
    });
  
</script>
