<body class="hold-transition login-page">
<?php 
   $field = 'ppe_part1';
    $this->db->where('current_link',$field);
    $detail = $this->db->get('services_list')->row();
?>
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
          <!-- instruction modal -->
          <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Instruction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <?php echo $detail->detail_instruction; ?>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                   
                  </div>
                </div>
              </div>
            </div>
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1><b><?php echo $detail->top_display; ?></b>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
              Read insturction
            </button>
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
      <div class="col-sm-12">
            <div class="card card-solid" style="background-color: #808080;">
                <div class="card-body pb-0">
                <div class="form-group"><b>
                <?php echo $detail->top_discription; ?>
                </b>
                </div>
            <!-- /.card-body -->
                </div>
            </div>
        </div>
            <?php 
              $i = 1;
                foreach($q->result() as $q)
                {
                  
            ?>
            <div class="col-sm-12">
            <div class="card card-solid">
            <div class="card-body pb-0">
            <div class="form-group">
           <p><b><?php echo 'Q. '.$q->org_qno.'. '.$q->QUIZ; ?></b></p>
        </div>
        
            <div class="form-group clearfix">
                       
                            <div class="icheck-success">
                              <input type="radio" name="<?php echo 'radio'.$i; ?>" value='1' id="<?php echo $q->qno.'o1'; ?>">
                              <label for="<?php echo $q->qno.'o1'; ?>">
                                <?php echo $q->A; ?>
                              </label>
                            </div>

                            <div class="icheck-success">
                              <input type="radio" name="<?php echo 'radio'.$i; ?>" value='2' id="<?php echo $q->qno.'o2'; ?>">
                              <label for="<?php echo $q->qno.'o2'; ?>">
                                <?php echo $q->B; ?>
                              </label>
                            </div>
                            <?php 
                              if($q->C != '')
                              {
                            ?>
                            <div class="icheck-success">
                              <input type="radio" name="<?php echo 'radio'.$i; ?>" value='3' id="<?php echo $q->qno.'o3'; ?>">
                              <label for="<?php echo $q->qno.'o3'; ?>">
                                <?php echo $q->C; ?>
                              </label>
                            </div>
                            <?php 
                              }
                              if($q->D != '')
                              {
                            ?>
                            <div class="icheck-success">
                              <input type="radio" name="<?php echo 'radio'.$i; ?>" value='4' id="<?php echo $q->qno.'o4'; ?>">
                              <label for="<?php echo $q->qno.'o4'; ?>">
                                <?php echo $q->D; ?>
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
  
