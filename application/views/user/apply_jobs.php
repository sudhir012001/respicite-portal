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
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Job Status</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item "><a href="<?php echo base_url().'BaseController/all_jobs'; ?>">View Jobs</a></li>
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
                <td>Status</td>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($job_apply_lists)){ foreach($job_apply_lists as $v){?>
              <tr class="<?php echo ($v->id == $highlight)?'highlight-job':'';?>">
                <td><a style="color:#0056b3;" href="https://respicite.com/job-details.php?jid=<?php echo base64_encode($v->id);?>" target="_black"><?php echo $v->job_title;?></a></td>
                <td><?php echo $v->domain;?></td>
                <td><?php echo $v->salary;?></td>
                <td>
                <?php 
                  if($v->job_status == "apply"){
                    echo "<p class='text-info m-0 p-0'>Applied</p>";
                  }elseif($v->job_status == "shortlisted"){
                    echo "<p class='text-success m-0 p-0'>Shortlisted</p>";
                  }elseif($v->job_status == "rejected"){
                    echo "<p class='text-danger m-0 p-0'>Rejected</p>";
                  }
                ?>
                </td>
              </tr>
              <?php } }else{
                echo "<tr><td colspan='4' class='text-center'>Data Not Found.</td></tr>";
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
  
  