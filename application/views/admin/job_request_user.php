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
              <a href="<?php echo base_url("AdminController/sp_jobs");?>"
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
                      <th>Apply Date</th>
                      <th>Job Approval Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($job_request_info)){ foreach($job_request_info as $v){ ?>
                      <tr>
                        <td><?php echo $v->user_email?></td>
                        <td><?php echo $v->fullname?></td>
                        <td><?php echo $v->apply_date?></td>
                        <td><?php
                          if($v->job_status == "apply"){
                            echo "<p class='p-0 m-0 text-info'>Applied</p>";
                          }elseif($v->job_status == "shortlisted"){
                            echo "<p class='p-0 m-0 text-success'>Shortlisted</p>";
                          }elseif($v->job_status == "rejected"){
                            echo "<p class='p-0 m-0 text-danger'>Rejected</p>";
                          }
                        ?></td>
                      </tr>                      
                    <?php } }else{
                      echo "<tr><td colspan='4' class='text-center'>Data Not Found.</td></tr>";
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
