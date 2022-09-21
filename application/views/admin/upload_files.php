<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid"> 
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1>Upload File</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Upload File</li>
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

                <p class="text-muted text-center">Update File</p>

              
           
            <!-- Profile Image -->
     
              <?php
                $type = $dd['type'];
                $grp1 = $dd['first'];
                $grp = str_replace('%20',' ',$grp1);
                if($type=='template_pdf')
                {
                    $tp = "Upload Template in PDF formate";
                    $frmt = "pdf";
                }
                else if($type=='logo')
                {
                    $tp = "Upload LOGO in JPG/PNG formate";
                    $frmt = "jpg|png";
                }
                else if($type=='sign_one')
                {
                    $tp = "Upload Signature One in JPG/PNG formate";
                    $frmt = "jpg|png";
                }
                else if($type=='sign_two')
                {
                    $tp = "Upload Signature Two in JPG/PNG formate";
                    $frmt = "jpg|png";
                }
                else if($type=='sign_three')
                {
                    $tp = "Upload Signature three in JPG/PNG formate";
                    $frmt = "jpg|png";
                }
                $msg = $this->session->flashdata('msg');
                if($msg != "")
                {
                    echo "<div class='alert alert-success'>$msg</div>";
                }
            ?>
      <form action="" enctype="multipart/form-data" method="post">        
      <div class="form-group">
                    <input type="hidden" name="c_group" value="<?php echo $grp; ?>">
                    <input type="hidden" name="tp" value="<?php echo $frmt; ?>">
                    <label for="exampleInputFile"><?php echo $tp; ?> </label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="form-control" name="img" id="img">
                        
                      </div>
                     
                    </div>
                  </div>
                  <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="uploadbtn" id="uploadbtn" class="btn btn-primary btn-block">Update</button>
          </div>
          <!-- /.col -->
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