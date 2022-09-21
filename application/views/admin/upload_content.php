<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1>Edit Content</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Content</li>
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
                

                <h3 class="profile-username text-center"></h3>

                <p class="text-muted text-center">Update Content</p>

              
              <?php
                $grp1 = $dd['first'];
                $grp = str_replace('%20',' ',$grp1);
                $msg = $this->session->flashdata('msg');
                if($msg != "")
                {
                    echo "<div class='alert alert-success'>$msg</div>";
                }
            ?>
      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" name="certificate_name" value="" class="form-control <?php echo (form_error('certificate_name')!="") ? 'is-invalid' : ''; ?>" placeholder="Certificate name">
         
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
            
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('certificate_name')); ?></p>
        </div>
        
        <div class="input-group mb-3">
          <input type="hidden" class="form-control <?php echo (form_error('group')!="") ? 'is-invalid' : ''; ?>" name="group" value="<?php echo $grp; ?>" placeholder="Group">
          
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('mc')!="") ? 'is-invalid' : ''; ?>" name="mc" value="" placeholder="Middle Content">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('mc')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('name_one')!="") ? 'is-invalid' : ''; ?>" name="name_one" value="" placeholder="Name One">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('name_one')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('name_two')!="") ? 'is-invalid' : ''; ?>" name="name_two" value="" placeholder="Name Two">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('name_two')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('name_three')!="") ? 'is-invalid' : ''; ?>" name="name_three" value="" placeholder="Name Three">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('name_three')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('detail_one_one')!="") ? 'is-invalid' : ''; ?>" name="detail_one_one" value="" placeholder="First row Detail for one">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('detail_one_one')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('detail_one_two')!="") ? 'is-invalid' : ''; ?>" name="detail_one_two" value="" placeholder="First row Detail for two">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('detail_one_two')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('detail_one_three')!="") ? 'is-invalid' : ''; ?>" name="detail_one_three" value="" placeholder="First row Detail for three">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('detail_one_three')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('detail_two_one')!="") ? 'is-invalid' : ''; ?>" name="detail_two_one" value="" placeholder="Second row Detail for one">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('detail_two_one')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('detail_two_two')!="") ? 'is-invalid' : ''; ?>" name="detail_two_two" value="" placeholder="Second row Detail for two">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('detail_two_two')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('detail_two_three')!="") ? 'is-invalid' : ''; ?>" name="detail_two_three" value="" placeholder="Second row Detail for three">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('detail_two_three')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?php echo (form_error('per')!="") ? 'is-invalid' : ''; ?>" name="per" value="" placeholder="Passing %">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('per')); ?></p>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              
            </div>
           
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="updatebtn" class="btn btn-primary btn-block">Update</button>
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

  