<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Add Events</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Add Events</li>
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
                <h3 class="profile-username text-center">Add Events</h3>

      <form action="<?php echo base_url('AdminController/insert_event') ?>" method="post">
      
      <div class="input-group mb-3">

<select class="form-control" name="event_no" id="event_no"  >
  <option value="1">1 </option>
  <option value="2">2</option>
  <option value="3">3</option>
</select>
<p class="invalid-feedback"><?php echo strip_tags(form_error('event_no')); ?></p>
</div>

      <!-- <div class="input-group mb-3">
          <input type="text" name="solution_name" value="<?php echo set_value('solution_name'); ?>" class="form-control <?php echo (form_error('solution_name')!="") ? 'is-invalid' : ''; ?>" placeholder="Solution Name">
         
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-book"></span>
            </div>
            
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('solution_name')); ?></p>
        </div> -->
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('title')!="") ? 'is-invalid' : ''; ?>" name="title" value="<?php echo set_value('title'); ?>" placeholder="Title">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-book"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('title')); ?></p>
        </div>

        <div class="input-group mb-3">
       
       <input type="date" name="event_date" class="form-control" placeholder="Date" >
            
     </div>
        
        
        <div class="input-group mb-3">
        <div class="input-group-append">
            <div class="input-group-text">
              <span >At</span>
            </div>
            </div>
          <input type="time" class="form-control <?php echo (form_error('start_time')!="") ? 'is-invalid' : ''; ?>" name="start_time" value="<?php echo set_value('start_time'); ?>" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span >to</span>
            </div>
          </div>
          <input type="time" class="form-control <?php echo (form_error('end_time')!="") ? 'is-invalid' : ''; ?>" name="end_time" value="<?php echo set_value('end_time'); ?>" >

        </div>

       

        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('location')!="") ? 'is-invalid' : ''; ?>" name="location" value="<?php echo set_value('location'); ?>" placeholder="Location">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-book"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('location')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('description')!="") ? 'is-invalid' : ''; ?>" name="description" value="<?php echo set_value('description'); ?>" placeholder="Description">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-book"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('description')); ?></p>
        </div>
        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="add_services_btn" class="btn btn-primary btn-block">Save</button>
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

  