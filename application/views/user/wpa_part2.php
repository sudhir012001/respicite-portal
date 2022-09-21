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
            
          <div class="col-sm-6">          
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">WPA Part 2</h1>
          </div>
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">part 2
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
                <h3 class="profile-username text-center">Part 2
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
                  $qno[] = $q->qno;
                  $item[] = $q->item;
                }
            ?>
            <div class="col-sm-12">
            <div class="card card-solid hover-effect">
            <div class="card-body pb-0">
            <div class="form-group">
           <p><b><?php echo 'Q. '.$qno[0].'. '.$item[0]; ?></b></p>
        </div>
        
            <div class="form-group clearfix">
            <p><b><?php echo 'Is this relevant in your current role'; ?></b></p>
                            <div class="icheck-success d-inline" style='padding: 10px;'>
                              <input type="radio" name="radio1" value='yes' id="yes1" onchange="ChangeThis('yes1')">
                              <label for="yes1">
                                Yes
                              </label>
                            </div>
                            <div class="icheck-success d-inline" style='padding: 10px;'>
                              <input type="radio" name="radio1" value='no' id="no1" onchange="ChangeThis('no1')">
                              <label for="no1">
                                No
                              </label>
                            </div>
                          <!--inner block -->
                            <div id='f1' style="display:none;"> <hr>
                            <p><b><?php echo 'How proficient you rate yourself in this capability'; ?></b></p>
                            <div class="icheck-success">
                              <input type="radio" name="radiof1" value='5' id="radio_f11" onchange="ChangeThis('radio_f11')">
                              <label for="radio_f11">
                              Excellent
                              </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof1" value='4' id="radio_f12" onchange="ChangeThis('radio_f12')">
                              <label for="radio_f12">
                              Good
                              </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof1" value='3' id="radio_f13" onchange="ChangeThis('radio_f13')">
                              <label for="radio_f13">
                              Average
                            </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof1" value='2' id="radio_f14" onchange="ChangeThis('radio_f14')">
                              <label for="radio_f14">
                              Below average
                              </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof1" value='1' id="radio_f15" onchange="ChangeThis('radio_f15')">
                              <label for="radio_f15">
                              Poor
                              </label>
                            </div>
                            </div>
                    </div>
                  </div>
            </div>
            </div>         <!-- radio -->
                    
       <!--second question -->
       <div class="col-sm-12">
            <div class="card card-solid hover-effect">
            <div class="card-body pb-0">
            <div class="form-group">
           <p><b><?php echo 'Q. '.$qno[1].'. '.$item[1]; ?></b></p>
        </div>
        
            <div class="form-group clearfix">
            <p><b><?php echo 'Is this relevant in your current role'; ?></b></p>
                            <div class="icheck-success d-inline" style='padding: 10px;'>
                              <input type="radio" name="radio2" value='yes' id="yes2" onchange="ChangeThis('yes2')" disabled>
                              <label for="yes2">
                                Yes
                              </label>
                            </div>
                            <div class="icheck-success d-inline" style='padding: 10px;'>
                              <input type="radio" name="radio2" value='no' id="no2" onchange="ChangeThis('no2')" disabled>
                              <label for="no2">
                                No
                              </label>
                            </div>
                            <!-- inner block -->
                            <div id='f2' style="display:none;"> <hr>
                            <p><b><?php echo 'How proficient you rate yourself in this capability'; ?></b></p>
                            <div class="icheck-success">
                              <input type="radio" name="radiof2" value='5' id="radio_f21" onchange="ChangeThis('radio_f21')">
                              <label for="radio_f21">
                              Excellent
                              </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof2" value='4' id="radio_f22" onchange="ChangeThis('radio_f22')">
                              <label for="radio_f22">
                              Good
                              </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof2" value='3' id="radio_f23" onchange="ChangeThis('radio_f23')">
                              <label for="radio_f23">
                              Average
                            </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof2" value='2' id="radio_f24" onchange="ChangeThis('radio_f24')">
                              <label for="radio_f24">
                              Below average
                              </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof2" value='1' id="radio_f25" onchange="ChangeThis('radio_f25')">
                              <label for="radio_f25">
                              Poor
                              </label>
                            </div>
                            </div>
                           
                        
                    </div>
                    
                    
                  </div>
                  
            </div>
            </div> 
            <!--second question -->
       <div class="col-sm-12">
            <div class="card card-solid hover-effect">
            <div class="card-body pb-0">
            <div class="form-group">
           <p><b><?php echo 'Q. '.$qno[2].'. '.$item[2]; ?></b></p>
        </div>
        
            <div class="form-group clearfix">
            <p><b><?php echo 'Is this relevant in your current role'; ?></b></p>
                       
                            <div class="icheck-success d-inline" style='padding: 10px;'>
                              <input type="radio" name="radio3" value='yes' id="yes3" onchange="ChangeThis('yes3')" disabled>
                              <label for="yes3">
                                Yes
                              </label>
                            </div>
                           
                            <div class="icheck-success d-inline" style='padding: 10px;'>
                              <input type="radio" name="radio3" value='no' id="no3" onchange="ChangeThis('no3')" disabled>
                              <label for="no3">
                                No
                              </label>
                            </div>
                            <div id='f3' style="display:none;"><hr>
                            <p><b><?php echo 'How proficient you rate yourself in this capability'; ?></b></p>
                            <div class="icheck-success">
                              <input type="radio" name="radiof3" value='5' id="radio_f31" onchange="ChangeThis('radio_f31')">
                              <label for="radio_f31">
                              Excellent
                              </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof3" value='4' id="radio_f32" onchange="ChangeThis('radio_f32')">
                              <label for="radio_f32">
                              Good
                              </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof3" value='3' id="radio_f33" onchange="ChangeThis('radio_f33')">
                              <label for="radio_f33">
                              Average
                            </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof3" value='2' id="radio_f34" onchange="ChangeThis('radio_f34')">
                              <label for="radio_f34">
                              Below average
                              </label>
                            </div>
                            <div class="icheck-success">
                              <input type="radio" name="radiof3" value='1' id="radio_f35" onchange="ChangeThis('radio_f35')">
                              <label for="radio_f35">
                              Poor
                              </label>
                            </div>
                        
                            </div>
                            </div>
                        
                  </div>
            </div>
            </div>                                     
        <div class="row">
            <div class="col-8">
            </div>
          <!-- /.col -->
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

            <!-- About Me Box -->
           
            <!-- /.card -->
          </div>
         
        </div>
        <!-- /.row -->

               

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  
<script>
    
    function ChangeThis(sender)
    {
        if(sender=='yes1')
        {
            $("#f1").show();
            document.getElementById('yes2').setAttribute("disabled", false);
            document.getElementById('no2').setAttribute("disabled", false);
        }
        else if(sender=='no1')
        {
            $("#f1").hide(); 
            document.getElementById('yes2').removeAttribute('disabled');
            document.getElementById('no2').removeAttribute('disabled');
            
        }
        else if(sender=='yes2')
        {
            $("#f2").show(); 
            document.getElementById('yes3').setAttribute("disabled", false);
            document.getElementById('no3').setAttribute("disabled", false);
        }
        else if(sender=='no2')
        {
            $("#f2").hide(); 
            document.getElementById('yes3').removeAttribute('disabled');
            document.getElementById('no3').removeAttribute('disabled');
        }
        else if(sender=='yes3')
        {
            $("#f3").show(); 
           
        }
        else if(sender=='no3')
        {
            $("#f3").hide(); 
        }

        if(sender=='radio_f11' || sender=='radio_f12' || sender=='radio_f13' || sender=='radio_f14' || sender=='radio_f15')
        {
            document.getElementById('yes2').removeAttribute('disabled');
            document.getElementById('no2').removeAttribute('disabled');
        }
        if(sender=='radio_f21' || sender=='radio_f22' || sender=='radio_f23' || sender=='radio_f24' || sender=='radio_f25')
        {
            document.getElementById('yes3').removeAttribute('disabled');
            document.getElementById('no3').removeAttribute('disabled');
        }
    }
</script>