<style>
  a{color:black}
  
  .btn-my {
    color: #fff;
    background-color: #fc9928;
    border-color: #fc9928;
  }

  .btn-my:hover {
    color: #fff;
    background-color: #fc9928;
    border-color: #fc9928;
  }
  .btn-my:active {
    color: #fff;
    background-color: #fc9928;
    border-color: #fc9928;
  }

  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }
</style>
<body class="hold-transition login-page">

    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-12">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">WLS Part 1</h1>
          </div>
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">WLS 1
              </li>
            </ol>
          </div> -->
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
            <div class="bg-white rounded border-round shadow mt-4">
                <div class="card-body box-profile">
                <h3 class="profile-username text-center">Part 1
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
            <?php 
              $i = 1;
                foreach($q->result() as $q)
                {
                  
            ?>
            <div class="col-sm-12">
            <div class="card card-solid hover-effect">
            <div class="card-body pb-0">
            <div class="form-group">
           <p><b><?php echo 'Q. '.$q->qno.'. '.$q->item; ?></b></p>
        </div>
        
            <div class="form-group clearfix">
                        <?php 
                            $assessment = 'wls_part1_1';
                            $this->db->where('assessment',$assessment);
                            $qd = $this->db->get('assessment_options');
                            foreach($qd->result() as $qd)
                            {
                        ?>
                                <div class="icheck-success">
                              <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo $qd->value; ?>' id="<?php echo $q->qno.'o'.$qd->value; ?>">
                              <label for="<?php echo $q->qno.'o'.$qd->value; ?>">
                                <?php echo $qd->options; ?>
                              </label>
                            </div>
                        <?php 
                            }
                        ?>
                        
                    </div>
                  </div>
            </div>
            </div>         <!-- radio -->
                    
                  <?php 
                  $i++;
        }
        ?>
        <div class="row">
            <div class="col-8">
            </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" id="saveBtn" name="saveBtn" class="btn btn-primary btn-block">Save & Next</button>
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
  
