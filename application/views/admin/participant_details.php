<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Participant  Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/sp_vocation_trainings");?>">Back to Vocation Trainings</a></li>
              <li class="breadcrumb-item active">Participant  Details</li>
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
                <h3 class="card-title">Participant Trainings Details</h3>
              </div> 
              <!-- /.card-header -->
              
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
                            echo "<p class='m-0 p-0 text-primary'>Approval Pending</p>";
                          }elseif($v->training_status == "approved"){
                            echo "<p class='m-0 p-0 text-info'>Approved</p>";
                          }elseif($v->training_status == "completed"){
                            echo "<p class='m-0 p-0 text-success m-0 p-0'>Completed</p>";
                          }
                        ?></td>
                      </tr>                      
                    <?php } }else{
                      echo "<tr><td colspan='5'>Data Not Found.</td></tr>";
                    } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>