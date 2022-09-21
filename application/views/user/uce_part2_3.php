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
<?php 
    $field = $this->uri->segment(2);
    $this->db->where('solution',$field);
    $detail = $this->db->get('solution_instruction')->row();
    
    $code = base64_decode($this->uri->segment(3));
    $where = "code='$code' and link='uce_part2'";
    $this->db->where($where);
    $row = $this->db->get('user_assessment_info')->row();
    $remain_time = $row->remain_time;
    $minutes_to_add = $remain_time;
        // echo "Remaining Time :".$minutes_to_add."<br>";
    //Added by Sudhir
    if($minutes_to_add <0){
        $minutes_to_add =0;
    }
    //End of Added by Sudhir
    // echo "Remaining Time Modified :".$minutes_to_add."<br>";
    
    date_default_timezone_set("Asia/Kolkata");
    $d = date('Y-m-d H:i:s');
    $time = new DateTime($d);
    $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
    
    $stamp = $time->format('M d, Y H:i:s');
?>
    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
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
                    <button type="button" class="btn btn-my" data-dismiss="modal">Close</button>
                   
                  </div>
                </div>
              </div>
            </div>
        <div class="row mb-2">
            
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;"><?php echo $detail->top_display; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <button type="button" class="btn btn-sm border mt-1" data-toggle="modal" data-target="#">
            <div id="demo" class="text-dark"></div>
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
      <div class="col-sm-12">
            <div class="card card-solid shadow">
                <div class="card-body pb-0">
                <div class="form-group"><p class="bm-0 top-discription">
                <?php echo $detail->top_discription; ?>
                  </p>
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
            <div class="card card-solid hover-effect">
            <div class="card-body pb-0">
            <div class="form-group">
           <p><b><?php echo 'Q. '.$q->qno; ?></b></p>
           <?php 
                if($q->q_type=='image')
                {
                    ?>
                    <img width="400px" src="<?php echo base_url().$q->question; ?>" alt="">
                <?php 
                }
           ?>
        </div>
        
        <div class="form-group clearfix">
                        
                        <div class="icheck-success d-inline" style='padding: 10px;'>
                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 1; ?>' id="<?php echo $q->qno.'o'.'1'; ?>">
                          <label for="<?php echo $q->qno.'o'.'1'; ?>">
                          <img width="100px" height="100px"  src="<?php echo base_url().$q->optionA; ?>" alt="">
                          </label>
                        </div>

                        <div class="icheck-success d-inline" style='padding: 10px;'>
                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 2; ?>' id="<?php echo $q->qno.'o'.'2'; ?>">
                          <label for="<?php echo $q->qno.'o'.'2'; ?>">
                          <img width="100px" height="100px" src="<?php echo base_url().$q->optionB; ?>" alt="">
                            
                          </label>
                        </div>
        </div>
        <div class="form-group clearfix">

                        <div class="icheck-success d-inline" style='padding: 10px;'>
                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 3; ?>' id="<?php echo $q->qno.'o'.'3'; ?>">
                          <label for="<?php echo $q->qno.'o'.'3'; ?>">
                          <img width="100px" height="100px" src="<?php echo base_url().$q->optionC; ?>" alt="">
                          </label>
                        </div>
                   
                        <div class="icheck-success d-inline" style='padding: 10px;'>
                          <input type="radio" name="<?php echo 'radio'.$i; ?>" value='<?php echo 4; ?>' id="<?php echo $q->qno.'o'.'4'; ?>">
                          <label for="<?php echo $q->qno.'o'.'4'; ?>">
                          <img width="100px" height="100px" src="<?php echo base_url().$q->optionD; ?>" alt="">
                          </label>
                        </div>
                   
        </div>    
                
              </div>
        </div>
        </div>         <!-- radio -->
                
              <?php 
              $i++;
    }
    ?>      <div class="row">
            <div class="col-8">
            </div>
          <!-- /.col -->
          <div class="col-4">
          <input type="hidden" name="code" id='code' value='<?= $code; ?>'>
        <input type="hidden" name="remain_time" id="remain_time" value=''>
            <button type="submit" id="saveBtn" name="saveBtn" class="btn btn-my btn-block">Save & Next</button>
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
  <?php 
    include('timer.php');
  ?>
