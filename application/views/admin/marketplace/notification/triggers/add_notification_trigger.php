<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/css/bootstrap-select.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/js/bootstrap-select.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script>
   $('select').selectpicker(); 
    
</script>
<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Add Triggers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Add Triggers</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          
        <div class="row">
            <div class="col-md-1"></div>
          <div class="col-md-10">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                
                <?php
                    $msg = $this->session->flashdata('msg');
                    if($msg != "")
                    {
                        echo "<div class='alert alert-success'>$msg</div>";
                    }
                ?>  
                
        <form action="<?= base_url('AdminController/marketplace_notification_triggers_insert')?>" method="post">
            <div class="row">
                <div class="col">
                   <div class="form-group">
                        <select class="form-control" name="activity" >
                          <option value="">Select Activity</option>
                          <?php foreach($activity as $v){ ?>
                          <option value="<?php echo $v ?>"><?php echo $v ?></option>
                          <?php } ?>
                        </select>
                    </div>
                </div>
          </div>
          <?php 
          $i=1;
          
          foreach($status as $v1){ ?>
           <div class="row" style="margin-top:20px;">
                <div class="col">
                    <input type="text" name="status" readonly value="<?php echo $v1 ?>" class="form-control" placeholder="Status">
                </div>
                
                    <div class="col" style="width:150px;">
                        <select name="<?php echo 'notification'.$i.'[]' ?>" class="selectpicker form-control" multiple data-live-search="true">
                            <?php foreach($notification as $v2){ ?>
                            <option value="<?php echo $v2 ?>"><?php echo $v2 ?></option>
                            <?php } ?>
                        </select>
                    </div>
                
            </div>
            <?php $i++;} ?>
            
            <div class="row">
              <div class="col-8">
                
              </div>
              <!-- /.col -->
              <div class="col-4" style="margin-top:30px;">
                <button type="submit" name="add_services_btn" class="btn btn-primary btn-block">Submit</button>
              </div>
              <!-- /.col -->
            </div>
          </form>

                <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
           
            <!-- /.card -->
          </div>
          <!-- /.col -->
          
          <!-- /.col -->
        </div>
        <!-- /.row -->

               

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  