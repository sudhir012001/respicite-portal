<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1>PPE Part 1
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">part 1
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
                $j = 1;
                foreach($q->result() as $q)
                {
                 

            ?>
            <div class="col-sm-12">
            <div class="card card-solid">
            <div class="card-body pb-0">
            <div class="form-group">
           <p><b><?php echo 'Q. '.$q->qno.'. '.$q->Observation; ?></b></p>
            </div>
            <input type="hidden" name="right_ans<?php echo $j; ?>" value='<?php echo $q->Correct_Option; ?>'>
            <div class="form-group clearfix">
                        
                <div class="icheck-success">
                    <input type="radio" name="<?php echo 'radio'.$j; ?>" value='A' id="<?php echo $q->qno.'o'.'A'; ?>">
                    <label for="<?php echo $q->qno.'o'.'A'; ?>">
                    <?php echo $q->OptionA; ?>
                </label>
            </div>

            <div class="form-group clearfix">
                        
                <div class="icheck-success">
                    <input type="radio" name="<?php echo 'radio'.$j; ?>" value='B' id="<?php echo $q->qno.'o'.'B'; ?>">
                    <label for="<?php echo $q->qno.'o'.'B'; ?>">
                    <?php echo $q->OptionB; ?>
                </label>
            </div>

            <div class="form-group clearfix">
                
                <div class="icheck-success">
                    <input type="radio" name="<?php echo 'radio'.$j; ?>" value='C' id="<?php echo $q->qno.'o'.'C'; ?>">
                    <label for="<?php echo $q->qno.'o'.'C'; ?>">
                    <?php echo $q->OptionC; ?>
                </label>
            </div>

            <div class="form-group clearfix">
                
                <div class="icheck-success">
                    <input type="radio" name="<?php echo 'radio'.$j; ?>" value='D' id="<?php echo $q->qno.'o'.'D'; ?>">
                    <label for="<?php echo $q->qno.'o'.'D'; ?>">
                    <?php echo $q->OptionD; ?>
                </label>
            </div>
                        
            </div>        
            </div>
            </div>
            </div>
            </div>  
            </div>
            </div>       <!-- radio -->
                    
                  <?php 
                 
                  $j++;
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
  
