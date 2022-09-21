<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Add Payment Gatway Type</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Add Payment Gatway Type</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-3"></div>
          <div class="col-md-6">

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
                

      <form action="<?php echo base_url('payment-gateway/saveparameter') ?>" method="post">
        <div class="input-group mb-3">
          <input type="text" name="name" value="<?php echo set_value('name'); ?>" class="form-control <?php echo (form_error('name')!="") ? 'is-invalid' : ''; ?>" placeholder="Payment Gatway Name">
         
          <!--<div class="input-group-append">-->
          <!--  <div class="input-group-text">-->
          <!--    <span class="fas fa-book"></span>-->
          <!--  </div>-->
            
          <!--</div>-->
          <p class="invalid-feedback"><?php echo strip_tags(form_error('solution_name')); ?></p>
        </div>
    
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('public_key')!="") ? 'is-invalid' : ''; ?>" name="public_key" value="<?php echo set_value('public_key'); ?>" placeholder="Public Key">
          <!--<div class="input-group-append">-->
          <!--  <div class="input-group-text">-->
          <!--    <span class="fas fa-book"></span>-->
          <!--  </div>-->
          <!--</div>-->
          <p class="invalid-feedback"><?php echo strip_tags(form_error('public_key')); ?></p>
        </div>
        
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('package')!="") ? 'is-invalid' : ''; ?>" name="api_key" value="<?php echo set_value('api_key'); ?>" placeholder="API KEY">
          <!--<div class="input-group-append">-->
          <!--  <div class="input-group-text">-->
          <!--    <span class="fas fa-book"></span>-->
          <!--  </div>-->
          <!--</div>-->
          <p class="invalid-feedback"><?php echo strip_tags(form_error('package')); ?></p>
        </div>

        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('controller')!="") ? 'is-invalid' : ''; ?>" name="controller" value="<?php echo set_value('controller'); ?>" placeholder="Controller Name">
          <!--<div class="input-group-append">-->
          <!--  <div class="input-group-text">-->
          <!--    <span class="fas fa-book"></span>-->
          <!--  </div>-->
          <!--</div>-->
          <p class="invalid-feedback"><?php echo strip_tags(form_error('controller')); ?></p>
        </div>

        <!--<div class="input-group mb-3">-->
        <!--  <input type="text" class="form-control <?php echo (form_error('discount')!="") ? 'is-invalid' : ''; ?>" name="discount" value="<?php echo set_value('discount'); ?>" placeholder="Discount">-->
          <!--<div class="input-group-append">-->
          <!--  <div class="input-group-text">-->
          <!--    <span class="fas fa-book"></span>-->
          <!--  </div>-->
          <!--</div>-->
        <!--  <p class="invalid-feedback"><?php echo strip_tags(form_error('discount')); ?></p>-->
        <!--</div>-->
        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="add_services_btn" class="btn btn-primary btn-block">Add payment type</button>
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

  