<style>
  a{color:black}
  
  .profile-box img{
    width: 8rem;
    height: 6.5rem;
    object-fit: contain;
    margin: 10px;
  }

  .border-round{
    border:1px solid #fc9928;
  }

  .list-group-item {
    border:1px solid #fc9928;    
  }

  .list-group-unbordered>.list-group-item{
    padding-left: 7px;
    padding-right: 7px;
  }
  .highlight-job{
    background: #fc9928;
    color: white;
  }
</style>
<body class="hold-transition login-page">

    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Jobs</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item ">Jobs</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td>Job Title</td>
                <td>Specialization</td>
                <td>Salary</td>
                <td>Apply Now</td>
              </tr>
            </thead>
            <tbody>
              <?php
              if(!empty($job_lists)){ foreach($job_lists as $v){?>
              <tr class="<?php echo ($v->id == $highlight)?'highlight-job':'';?>">
                <td><a style="color:#0056b3;" href="https://respicite.com/job-details.php?jid=<?php echo base64_encode($v->id);?>" target="_black"><?php echo $v->job_title;?></a></td>
                <td><?php echo $v->domain;?></td>
                <td><?php echo $v->salary;?></td>
                <td>
                <button data-jid="<?php echo $v->id;?>" data-uid="<?php echo $user["email"];?>" class="btn btn-sm btn-outline-primary btn-job-apply-now">Click here</button>
                </td>
              </tr>
              <?php } }else{
                echo "<tr><td colspan='5' class='text-center'>Data Not Found.</td></tr>";
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <!-- /.content -->

    <div class="modal fade" id="cv_upload" style="display: none;" aria-hidden="true">
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
  <script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
  
<script>
  let BASE_URL = '<?php echo base_url();?>';
  $(document).ready(function() {
    $(document).on("click",".btn-job-apply-now",function(){
        let jid = $(this).data("jid");
        let uid = $(this).data("uid");
        let cv_modal = $("#cv_upload");
        cv_modal.find(".modal-title").text("Upload your resume");
        cv_modal.find("#modal_form").html(`
        <input type="hidden" name="jid" value="${jid}">
        <input type="hidden" name="uid" value="${uid}">
        <p class="text-info">zip file upload and should be a maximum of 2MB only.</p>
        <div class="form-group">
          <div class="custom-file">
            <input type="file" name="cv_file" accept="zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed" class="custom-file-input" id="customFile">
            <label class="custom-file-label"  for="customFile">Choose file</label>
          </div>
        </div>
        <div class="img-error text-danger"></div>
        `);
        cv_modal.find("[form=modal_form]").text("Apply Now");
        cv_modal.modal("show");
    });

    $(document).on("change","[name=cv_file]",function(){
      let file_name = $(this).get(0).files[0];
      $('[for="customFile"]').text(file_name.name);
    });

    $('#modal_form').submit(function(event){
      event.preventDefault();
          let form_data = new FormData(event.target);
      fetch(`${BASE_URL}BaseController/ajax_work/apply_jobs_with_cv`,{
        method:"post",
        body:form_data
      })
      .then(function(req){return req.json();})
      .then(function(res){ 
        let img_err_show = $(".img-error");
        img_err_show.html(null);
            if(res.msg == "APPLIED"){
              alert("Thank you for applying.");
              document.location.href = `${BASE_URL}BaseController/apply_jobs`;
            }

            if(res.msg == "job_status"){
              if(res.data == "apply"){
                alert("Job Status : Applied");
              }else{
                alert("Job Status : "+res.data);
              }
              $("#cv_upload").modal("hide");
            }
            
            if(res.msg == "ERROR_IMG"){
              img_err_show.html(res.ERROR_IMG_DESC);
            }
      });
    });

  });
</script>