<style>
  .btn-more {
    background-color: #2794ff;
    color: white;
    padding: 2px 8px 4px 8px;
    border-radius: 14px;
    cursor: pointer;
}
</style>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Sp Posts Job</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Sp Posts Job</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
      <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Sp Posts Job</h3>
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                    <thead>
                      <tr>
                        <th>Sp Name</th>
                        <th>Sp Email</th>
                        <th>Job Title</th>
                        <th>CTC</th>
                        <th>Job Type</th>
                        <th>Job Locations</th>
                        <th>Posting Nature</th>
                        <th>Job Description</th>
                        <th>Status</th>
                        <th>Job Request</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      if(!empty($all_data)){
                        foreach($all_data as $v){
                        ?>
                        <tr>
                          <td><?php echo $v->fullname;?></td>
                          <td><?php echo $v->email;?></td>
                          <td style="white-space: break-spaces;"><p class="m-0 p-0" style="width:23rem"><?php echo $v->job_title;?></p></td>
                          <td><?php echo $v->salary;?></td>
                          <td><?php echo $v->job_type;?></td>
                          <td><?php echo $v->job_locations;?></td>
                          <td><?php echo $v->posting_nature;?></td>
                          <td style="white-space: break-spaces;"><p class="m-0 p-0" style="min-width: 23rem;"><?php echo word_limiter($v->job_description,25);?><span class="btn-more btn-click-more"  data-desc="<?php echo $v->job_description; ?>">More</span></p>
                          </td>
                          <td>
                          <?php 
                          if($v->status == "draft"){
                            echo "<button data-jid='$v->id' class='btn btn-sm btn-outline-info m-1 btn-status-this'>Draft ( Click to Published )</button>";
                          }elseif($v->status == "published"){
                            echo "<p class='p-0 m-0 text-success'>Published</p>";
                          }?>
                          </td>
                          <td>
                          <a class='btn btn-sm btn-outline-info m-1' href='<?php echo base_url('AdminController/job_request_user/'.$v->id);?>'>click here</a>
                          </td>
                        </tr>
                        <?php 
                        }
                      }else{
                        echo '<tr><td colspan="11" class="text-center">Data Not Found.</td></tr>';
                      }
                      ?>
                    </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

 <div class="modal fade" id="modal-xl" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
      <p id="msg-body" style="white-space: break-spaces;"></p>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<script>
  $(document).on("click",".btn-status-this",function(){
    var jid = $(this).data("jid");
    var this_box = $(this).parent();
      $.get(`${BASE_URL}AdminController/ajax_work/${jid}/JOB_STATUS`,function(res){
        let res_json = JSON.parse(res);
        if(res_json.MSG == "OK"){
        this_box.html("<p class='p-0 m-0 text-success'>Published</p>");
        }
      });
  });

  $(document).on("click",".btn-click-more",function(){
      let desc =  $(this).data("desc");
      $("#modal-xl").modal("show");
      $("#msg-body").html(desc);

    });
</script>