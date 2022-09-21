<style>
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }

  .btn-more{
    background-color: #2794ff;
    color: white;
    padding: 2px 8px 4px 8px;
    border-radius: 14px;
    cursor: pointer;
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
    ">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em; color: #7f7f7f">
            View Job 
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="<?php echo base_url();?>UserController/dashboard"
                >Dashboard</a
              >
            </li>
            <li class="breadcrumb-item active">View Job </li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
   
  <section class="content ">
    <div class="container-fluid">      
          <p class="text-right"><a class="btn btn-sm btn-outline-primary" href="<?php echo base_url('UserController/job_add');?>">Add New Job</a></p>
         
        <!-- main content #start -->
        <div class="">
          <?php 
            if(!empty($this->session->flashdata("check_inset"))){
              if($this->session->flashdata("check_inset") == "OK"){
                echo "<div class='alert alert-success'>Successfully saved.</div>";
              }
              if($this->session->flashdata("check_inset") == "ERROR"){
                echo "<div class='alert alert-danger'>Something is wrong.</div>";
              }
            }
          ?>
        </div>  
        <div class="card">
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th>Job Title</th>
                      <th>CTC</th>
                      <th>Job Type</th>
                      <th>Job Locations</th>
                      <th>Posting Nature</th>
                      <th>Job Description</th>
                      <th class="text-center">Action</th>
                      <th>Status</th>
                      <th>Job Request</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    if(!empty($short_data)){
                      foreach($short_data as $v){
                      ?>
                      <tr>
                        <td style="white-space: break-spaces;"><p style="min-width: 15rem;" class="m-0 p-0"><?php echo $v->job_title;?></p></td>
                        <td><?php echo $v->salary;?></td>
                        <td><?php echo $v->job_type;?></td>
                        <td><?php echo $v->job_locations;?></td>
                        <td><?php echo $v->posting_nature;?></td>
                        <td style="white-space: break-spaces;"><p class="m-0 p-0" style="min-width: 23rem;"><?php echo word_limiter($v->job_description,25);?><span class="btn-more btn-click-more"  data-desc="<?php echo $v->job_description; ?>">More</span></p>
                        </td>
                        <td>
                            <a class='btn btn-sm btn-outline-info m-1' href='<?php echo base_url('UserController/job_edit/'.$v->id);?>'>Edit</a>
                            <button data-jid='<?php echo $v->id;?>' class='btn btn-sm btn-outline-danger m-1 btn-delete-this'>Delete</button>
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
                        <a class='btn btn-sm btn-outline-info m-1' href='<?php echo base_url('UserController/job_request_user/'.$v->id);?>'>click here</a>
                        </td>
                      </tr>
                      <?php 
                      }
                    }else{
                      echo '<tr><td colspan="9" class="text-center">Data Not Found.</td></tr>';
                    }
                    ?>
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
   const BASE_URL = '<?php echo base_url();?>';
  // --remove #start
  $(document).on("click",".btn-delete-this",function(){
    var jid = $(this).data("jid");
    var row = $(this).parent().parent();
    if(confirm("Are you sure you want to delete?")){
        $.get(`${BASE_URL}UserController/delete_itme/${jid}/REMOVE_JOB`,function(res){
          let res_json = JSON.parse(res);
          if(res_json.MSG == "OK"){
            row.remove();
            alert("Successfully Delete this section");
          }
        });
      }
    });
  // --remove #end

  $(document).on("click",".btn-status-this",function(){
    var jid = $(this).data("jid");
    var this_box = $(this).parent();
        $.get(`${BASE_URL}UserController/sp_ajax_work/${jid}/JOB_STATUS`,function(res){
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
