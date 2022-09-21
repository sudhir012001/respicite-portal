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
<body class="hold-transition login-page" style="background-image: 'http://localhost/users.cgcareers.online/uploads/bg.PNG';">
<?php 
    $field = $this->uri->segment(2);
    $this->db->where('current_link',$field);
    $detail = $this->db->get('services_list')->row();
?>
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Instruction</h5>
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
    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
              <!-- instruction modal -->
              
        
        <div class="row mb-2">
        
          <div class="col-sm-3 col-lg-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;"><?php echo $detail->top_display; ?></h1>
            
          </div>
          <div class="col-sm-3 col-lg-6">
          
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->
              <!-- <li class="breadcrumb-item active">WPA 1 -->
              <!-- </li> -->
              <button type="button" class="btn btn-sm btn-my mt-1"  data-toggle="modal" data-target="#exampleModalCenter">
              Read insturction
            </button>

            
              <!-- <button type="submit" id="saveBtn" name="saveBtn" class="btn btn-primary btn-block">Read insturction</button> -->
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
               
                <?php 
                    $email = $user['email'];
                    $last = $this->uri->total_segments();
                    $r = $this->uri->segment($last);
                    $code = base64_decode($r);
                    $ck = '';
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
                                <!-- /.card -->
                                <?php
                                $i = 1;
                                foreach($q->result() as $q)
                                {
                                    $qno = $q->qno;
                                    $where = "qno='$qno' and email='$email' and code='$code' and solution='wpa_part1'";
                                    $this->db->where($where);
                                    $c_s = $this->db->get('ppe_part1_test_details');
                                    foreach($c_s->result() as $c_s)
                                    {
                                        $ck = $c_s->ans;
                                    }

                                ?>
                            <div class="col-sm-12">
                                <div class="card card-solid hover-effect" >
                                    <div class="card-body pb-0">
                                        <div class="form-group"><b>
                                        <P><?php echo $q->item; ?></P></b>
                                        <input type="hidden" name="qnm<?php echo $i; ?>" value="<?php echo $q->qno; ?>">
                                        <input type="hidden" name="type<?php echo $i; ?>" value="<?php echo $q->o_type; ?>">
                                        </div>
                                        <?php 
                                            if($q->type=='image')
                                            {
                                        ?>
                                                <img width="400px" src="<?php echo base_url().$q->img_url; ?>" alt="">
                                        <?php        
                                            }
                                            if($q->o_type=='radio')
                                            {
                                                    $type = "radio";
                                                    $this->db->where('qno',$q->qno);
                                                    $r = $this->db->get('wpa_part1_options');
                                                    $j = 1;
                                                    foreach($r->result() as $r)
                                                    {
                                            ?>
                                                        <div class="form-group clearfix">
                                                        <div class="icheck-success">
                                                            <input type="<?php echo $type; ?>" name="q<?php echo $i; ?>" id="<?php echo $r->id; ?>" value="<?php echo $j; ?>" <?php if($j==$ck){ echo "checked"; } ?>>
                                                            <label for="<?php echo $r->id; ?>">
                                                            <?php echo $r->option; ?> 
                                                            </label>
                                                        </div>
                                                        
                                                        </div>
                                            <?php
                                                   $j++;
                                                    }
                                            }
                                            else if($q->o_type=='cb')
                                            {
                                                $type = "checkbox";
                                                $j = 1;
                                                $arr = explode(',',$ck);
                                                $ca = count($arr);
                                                $this->db->where('qno',$q->qno);
                                                $r = $this->db->get('wpa_part1_options');

                                                foreach($r->result() as $r)
                                                {

                                        ?>
                                                    <div class="form-group clearfix">
                                                    <div class="icheck-success">
                                                        <input type="<?php echo $type; ?>" name="<?php echo 'q'.$i.$j; ?>" id="<?php echo $r->id; ?>" value="<?php echo $j; ?>"
                                                        <?php 
                                                        for($p=0;$p<$ca;$p++)
                                                        {
                                                          if($j==$arr[$p])
                                                          {
                                                            echo "checked";
                                                          }
                                                        }
                                                        ?>
                                                        >
                                                        <label for="<?php echo $r->id; ?>">
                                                        <?php echo $r->option; ?>
                                                        </label>
                                                    </div>
                                                    
                                                    </div>
                                        <?php
                                                $j++;
                                               
                                                }
                                            }
                                        ?>
                                        
                                    </div>
                                </div>
                            
                            </div>
                            <!-- /.card-body -->
                            <?php 
                               $i++;
                                 } 
                            ?>
                                <!-- /.card-body -->     
                        <div class="row">
                        <div class="col-4">
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                       
                        <!-- <button id="saveBtn2" onclick="goBack()" name="saveBtn2" class="btn btn-primary btn-block">Previous Page</button> -->
                      </div>
                        
                        <div class="col-4">
                       
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

                      


           
         
        </div>
        <!-- /.row -->

               

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <script>
    
    function goBack(){
      window.history.back();
    }

  </script>
