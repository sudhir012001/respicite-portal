<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1>Details for Group/Institution
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Details for Institution
              </li>
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
                <h3 class="profile-username text-center">Details for Institution
                </h3>
                <?php 
                    $msg = $this->session->flashdata('msg');
                    if($msg !="")
                    {
                    ?>     
                    <div class="alert alert-danger">
                        <?php echo $msg; ?>
                    </div>
                    <?php 
                    }
                     $msg2 = $this->session->flashdata('msg2');
                    if($msg2 !="")
                    {
                    ?>
                    <div class="alert alert-success">
                    <?php echo $msg2; ?>
                    </div>
                    <?php 
                    }
                    ?> 
               

      <form action="" method="post">
        <div class="form-group">
            <input type="text" class="form-control" name="inst_name" id="inst_name" placeholder="Name of Institution">
                <p class="invalid-feedback"><?php echo strip_tags(form_error('inst_name')); ?></p>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="domain" id="domain" placeholder="Domain of Work">
            <p class="invalid-feedback"><?php echo strip_tags(form_error('domain')); ?></p>
        </div>
        <div class="form-group">

            <input type="text" class="form-control" name="city" id="city" placeholder="City">
            <p class="invalid-feedback"><?php echo strip_tags(form_error('city')); ?></p>
        </div>
        <div class="form-group">

            <input type="text" class="form-control" name="state" id="state" placeholder="State">
            <p class="invalid-feedback"><?php echo strip_tags(form_error('state')); ?></p>
        </div>  
        <div class="form-group">

            <input type="text" class="form-control" name="country" id="country" placeholder="Country">
            <p class="invalid-feedback"><?php echo strip_tags(form_error('country')); ?></p>
        </div>
       
        <div class="row">
            <div class="col-8">
            </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" id="saveBtn" name="saveBtn" class="btn btn-primary btn-block">Save</button>
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
         
        </div>
        <!-- /.row -->

               

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  
