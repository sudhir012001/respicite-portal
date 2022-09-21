
<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">counseling type</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("SpController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">counseling type</li>
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
                

      <form action="<?php echo base_url('SpController/update_counseling_type') ?>" method="post">
        <div class="input-group mb-3">
          <input type="text" name="counseling_type" value="" class="form-control <?php echo (form_error('counseling_type')!="") ? 'is-invalid' : ''; ?>" placeholder="counseling type">
         
          <!--<div class="input-group-append">-->
          <!--  <div class="input-group-text">-->
          <!--    <span class="fas fa-book"></span>-->
          <!--  </div>-->
            
          <!--</div>-->
          <p class="invalid-feedback"><?php echo strip_tags(form_error('book_link')); ?></p>
        </div>
        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="add_services_btn" class="btn btn-primary btn-block">counseling type</button>
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
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <!--<div class="card-header">-->
                <!--<h3 class="card-title">Counseling Type List</h3>-->
              <!--</div> -->
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th>S No</th>
                      <th>Counseling Type</th>
                    </tr>
                  </thead>
                    <tbody>
                        <?php  $data = explode(",",$counseling_type); 
                        if(isset($data)){
                        $i=1;
                        foreach($data as $val){        
                        ?>
                        <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo $val;?></td>
                        </tr>
                        
                        <?php $i++; } } ?>
                   </tbody>
                </table>
              </div>
            </form>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        </div>
    </section>
    
  </div>
    

  