<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Vocation Trainings</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Vocation Trainings</li>
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
                <h3 class="card-title">Vocation Trainings</h3>
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th>Training ID</th>
                      <th>SP Email ID</th>
                      <th>Training Name</th>
                      <th>Submission Date</th>
                      <th>Training Status</th>
                      <th>Certification Status</th>
                      <th>Full Details</th>
                      <th>Participants Details</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(!empty($all_vocational)){
                      foreach($all_vocational as $v){
                    ?>
                      <tr>  
                        <td><?php echo $v->id;?></td>
                        <td><?php echo $v->email;?></td>
                        <td style="min-width: 19rem !important;white-space: normal;">
                          <p class="p-0 m-0"><?php echo $v->training_name;?></p>
                        </td> 
                        <td>11-1-22</td>
                        <td>
                          <?php
                            if($v->training_status == "in_creation"){
                                echo "<p class='m-0 p-0 text-success'>In Creation</p>";
                            } elseif($v->training_status == "approval_pending"){
                                echo "<p class='m-0 p-0 text-info'>Approval Pending</p>";
                            }elseif($v->training_status == "approved"){
                                echo "<p class='m-0 p-0 text-success'>Approval</p>";
                            }elseif($v->training_status == "suspended"){
                                echo "<p class='m-0 p-0 text-danger'>Suspended</p>";
                            }elseif($v->training_status == "approval_remark"){
                                echo "<p class='m-0 p-0 text-success'>Approval Remark</p>";
                            }
                          ?>  
                        </td>
                        <td><?php echo $v->certification_status;?></td>
                        <td><a class="btn btn-sm btn-outline-primary" href="<?php echo base_url("AdminController/sp_vocation_training_details/").$v->id;?>">Click Here</a></td>  
                        <td>
                          <a href="<?php echo base_url("AdminController/participant_details/").$v->id;?>" class="btn btn-sm btn-outline-info">Click Here</a> 
                        </td>   
                      </tr> 
                    <?php
                      }
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