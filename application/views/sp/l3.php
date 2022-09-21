<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1>Complete Your Profile
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Complete Your Profile
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
                <h3 class="profile-username text-center">Complete Your Profile
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
                    <label>Select Level 3</label> <br>
          <!-- <select class="form-control" name="levelone" id="levelone">
          <option value="">Select Services </option> -->
            <?php 
               $i = 1;
               $st = 0;
                foreach ($l->result() as $row)  
                {  
                    
                    $l2 = $row->l2;
                    $this->db->where('id',$l2);
                    $res = $this->db->get('provider_level_two');
                    foreach($res->result() as $res)
                    {
                      echo $res->l2;
                    }
                    
                    $num = $this->Sp_model->provider_level_list_three($l2)->num_rows();
                    if($num>0)
                    {
                      $st++;
                        $level['l'] = $this->Sp_model->provider_level_list_three($l2);
                        foreach($level['l']->result() as $row2)
                        {
                          ?>
                            
                            <div class="icheck-success">
                            <input type="checkbox" id="cb<?php echo $i; ?>" value="<?php echo $row2->id; ?>" name="cb[]">
                            <label for="cb<?php echo $i; ?>">
                            <?php echo $row2->l3; ?>
                            </label>
                          </div>
                          <?php
                          $i++;
                        }
                    }
                    else
                    {
                      echo "<br><b>No SubField Available</b><br>";
                    }
                    
                }
                ?>
          <!-- </select> -->
          <p class="invalid-feedback"><?php echo strip_tags(form_error('cb')); ?></p>
          </div>
          
          
       
        <div class="row">
          <div class="col-8">
          <input type="hidden" name="val" value="<?php echo $st; ?>">
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="save_level3" class="btn btn-primary btn-block">Save & Next</button>
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
  <script
  src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  
