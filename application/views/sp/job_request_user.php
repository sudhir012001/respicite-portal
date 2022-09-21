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
            Job Request User
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="<?php echo base_url("UserController/posts_job");?>"
                >Posts Job</a
              >
            </li>
            <li class="breadcrumb-item active">Job Request User</li>
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
                      <th>Name</th>
                      <th>CV Download</th>
                      <th>Apply Date</th>
                      <th>Job Approval Status</th>
                      <th>Forward</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($job_request_info)){ foreach($job_request_info as $v){ ?>
                      <tr>
                        <td><?php echo $v->user_email?></td>
                        <td><?php echo $v->fullname?></td>
                        <td><a class="btn btn-sm btn-outline-primary m-1" href="<?php echo base_url("uploads/jobs_cv/").$v->cv_path; ?>">Click here</a></td>
                        <td><?php echo $v->apply_date?></td>
                        <td><?php
                          if($v->job_status == "apply"){
                            echo "<button class='btn btn-sm btn-outline-primary btn-change-status m-1' data-jid='$v->id' data-req-type='shortlisted'>Shortlisted</button>";
                            echo "<button class='btn btn-sm btn-outline-info btn-change-status m-1' data-jid='$v->id' data-req-type='rejected'>Rejected</button>";
                          }elseif($v->job_status == "shortlisted"){
                            echo "<p class='text-success m-1 p-0 d-inline-block'>Shortlisted</p>";
                            echo "<button class='btn btn-sm btn-outline-info btn-change-status m-1' data-jid='$v->id' data-req-type='rejected'>Rejected</button>";
                          }elseif($v->job_status == "rejected"){
                            echo "<p class='text-danger m-1 p-0 d-inline-block'>Rejected</p>";
                            echo "<button class='btn btn-sm btn-outline-info btn-change-status m-1' data-jid='$v->id' data-req-type='shortlisted'>Shortlisted</button>";
                          }
                        ?></td>
                        <td><button data-rid="<?php echo $v->id;?>" class="btn btn-sm btn-outline-primary m-1 btn-forward">Click here</button></td>
                        
                      </tr>                      
                    <?php } }else{
                      echo "<tr><td class='text-center' colspan='6'>Data Not Found.</td></tr>";
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

  <div class="modal fade" id="modal_box" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form  method="post" id="modal_form" enctype="multipart/form-data">

            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="modal_form" class="btn btn-primary"></button>
          </div>
        </div>
      </div>
    </div>
</div>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

<script>
  const BASE_URL = '<?php echo base_url();?>';
  $(document).on("click",".btn-change-status",function(){    
    let jid = $(this).data("jid");
    let req_type = $(this).data("req-type");
    let this_tag = $(this).parent();

   $.get(`${BASE_URL}SpController/ajax_work/${jid}/JOB_STATUS_REQUEST?req_type=${req_type}`,function(res){
    let res_json = JSON.parse(res);    
    if(res_json.MSG == "OK"){
      if(req_type == "shortlisted"){
        this_tag.html(`<p class='text-success m-1 p-0 d-inline-block'>Shortlisted</p>
        <button class='btn btn-sm btn-outline-info btn-change-status m-1' data-jid='${jid}' data-req-type='rejected'>Click to Rejected</button>`);
      }
      if(req_type == "rejected"){
        this_tag.html(`<p class='text-danger m-1 p-0 d-inline-block'>Rejected</p>
        <button class='btn btn-sm btn-outline-info btn-change-status m-1' data-jid='${jid}' data-req-type='shortlisted'>Click to Shortlisted</button>`);
      }
    }
    if(res_json.MSG == "ERROR" || res_json.MSG == "EMPTY"){
      alert("Something is wrong.");
    }
   }); 

  });

  $(document).on("click",".btn-forward",function(){
        let rid = $(this).data("rid");
        let cv_modal = $("#modal_box");
        cv_modal.find(".modal-title").text("Forward user details");
        cv_modal.find("#modal_form").html(`
        <input type="hidden" name="rid" value="${rid}">
          <div class="form-group">
            <label for="forward_email">Email address</label>
            <input type="email" name="email_id" class="form-control" id="forward_email" placeholder="Enter email">
          </div>
        <div class="show-error text-danger"></div>
        `);
        cv_modal.find("[form=modal_form]").text("Send");
        cv_modal.modal("show");
    });

    $("#modal_form").submit(function(event){
      event.preventDefault();
      let form_data = new FormData(event.target);
      fetch(`${BASE_URL}SpController/ajax_work/0/FORWARD_DETAILS`,{
        method:"post",
        body:form_data
      })
      .then(function(req){return req.json();})
      .then(function(res){
        let show_err = $(".show-error");
        if(res.msg == "id_not_found"){
          show_err.html("User ID is not found, try again later.");
        }
        if(res.msg == "email_not_found"){
          show_err.html("The forward email id field is empty.");
        }
        if(res.msg == "send_faild"){
          show_err.html("Mail forward failed, try again later.");
        }
        if(res.msg == "send_seccuss"){
          alert("Mail sent successfully.");
          $("#modal_box").modal("hide");
        }
      })
    });
</script>
