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
</style>
<body class="hold-transition login-page">

    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Training Status</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item ">Training Status</li>
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
                <td>Trainer Name</td>
                <td>Training Title</td>
                <td>Apply Date</td>
                <td>Apply Status</td>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($apply_training)){ foreach($apply_training as $v){?>
              <tr>
                <td><?php echo $v["fullname"];?></td>
                <td style="width:50%"><p class="m-0 p-0"><?php echo $v["training_name"];?></p></td>
                <td><?php echo $v["apply_date"];?></td>
                <td>
                <?php 
                  if($v['training_status'] == "approval_pending"){
                    echo "<p class='text-info m-0 p-0'>Approval Pending</p>";
                  }elseif($v['training_status'] == "approved"){
                    echo "<p class='text-success m-0 p-0'>approved</p>";
                  }elseif($v['training_status'] == "completed"){
                    echo "<p class='text-success m-0 p-0'>Completed</p>";
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
  
  