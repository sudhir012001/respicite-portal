<style>
  a{color:black}
  
  .profile-box img{
    width: 8rem;
    height: 6.5rem;
    object-fit: contain;
    margin: 10px;
  }

  .border-round{
    border:1px solid #fc9928;
  }

  .color-b{
    background-color:#fc9928;
    color:white;
  }

  /* .btn-my {
    color: #fc9928;
    border-color: #fc9928;
  } */
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
</style>
<body class="hold-transition login-page">
<?php 
    $last = $this->uri->total_segments();
    $record_num = $this->uri->segment($last);
    $code = base64_decode($record_num);
    $email = $user['email'];
    
    $this->db->where('code',$code);
    $rs = $this->db->get('user_code_list')->row();
    $sol = $rs->solution;

    $person_detail_type='';
    $person_detail_nature ='';
    if($sol=='UCE' || $sol=='OCS' || $sol == 'OCSS')
    {
        $s_class = array('8','9','10','11','12');
        $c_detail = array('8th','9th','10th','11th','12th');
        $person_detail_type = "Class";
        $person_detail_nature ='School Student';
    }
    else if($sol=='QCE')
    {
      $s_class = array('3','4','5','6','7');
      $c_detail = array('3rd','4th','5th','6th','7th');
      $person_detail_type = "Class";
      $person_detail_nature ='School Student';
    }
    else if($sol=='PPE')
    {
      $s_class = array('1','2','3','4','5','6','7','8','9','10','11','12');
      $c_detail = array('1st','2nd','3rd','4th','5th','6th','7th','8th','9th','10th','11th','12th');
      $person_detail_type = "Class";
      $person_detail_nature ='School Student';
    }
    else if($sol=='CAT')
    {
      $s_class = array('1','2');
      $c_detail = array('1st','2nd');
      $person_detail_type = "Class";
      $person_detail_nature ='School Student';
    }
    
    
    /** commented by Sudhir
    else if($sol=='SDP1' || $sol=='SDP2' || $sol=='SDP3')
    **/
    
    //Added by Sudhir
    else if($sol=='SDP1' || $sol=='SDP2' || $sol=='SDP3' || $sol =='Disha' || $sol=='DOCCP' || $sol=='DOCCPS')
    //End of Added by Sudhir
    
    {
      $s_class = array('1st Year to Pre-final Year','Final Year','Fresh passout');
      $c_detail = array('1st Year to Pre-final Year','Final Year','Fresh passout');
      $person_detail_type = "Level";
      $person_detail_nature ='College Student';
    }

    else if($sol=='JRAP')
 
    {
      $s_class = array('Final Year','Fresh passout');
      $c_detail = array('Final Year','Fresh passout');
      $person_detail_type = "Level";
      $person_detail_nature ='Job Aspirant';

    }
    else if($sol=='CM' || $sol=='WLA' || $sol=='WLS' || $sol=='WPA')
    {
      $s_class = array('Less than 5 Years','5 - 10 Years ','10 to 15 Years','Greater than 15 Years');
      $c_detail = array('Less than 5 Years','5 - 10 Years ','10 to 15 Years','Greater than 15 Years');
      $person_detail_type = "Experience";
      $person_detail_nature ='Working Professional';
    }
    else if($sol=='ECP')
    {
      $s_class = array('No entreprenurial experience','Experienced as self-employed','Had my own company and employees');
      $c_detail = array('No entreprenurial experience','Experienced as self-employed','Had my own company and employees');
      $person_detail_type = "Entreprenurial Experience";
      $person_detail_nature ='Fresher or Working Professional';
    }
  

    $cnt = count($s_class);
?>
    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Fill Personal Details </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Gender
              </li> -->
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
            <div class="bg-white rounded border-round shadow">
                <div class="card-body box-profile">
                <h3 class="profile-username text-center mb-4">Personal Details</h3>
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
        
        <div class="input-group mb-3">
            <input type="hidden" name="code" value="<?php echo $code; ?>">
          <input type="text" name="full_name" value="<?php echo $user['fullname']; ?>" class="form-control <?php echo (form_error('full_name')!="") ? 'is-invalid' : ''; ?>" placeholder="Full Name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user text-success"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('full_name')); ?></p>
        </div>
        <label for="dt">Date of Birth</label>
        <div class="input-group mb-3">
           
          <input type="date" name="dt" value="<?php echo set_value('dt'); ?>" class="form-control <?php echo (form_error('dt')!="") ? 'is-invalid' : ''; ?>" placeholder="Select Date of Birth">
          <div class="input-group-append">
            <div class="input-group-text">
            <i class="fas fa-calendar-week text-success"></i>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('dt')); ?></p>
        </div>

        <input type="hidden" value="<?= $person_detail_type; ?>" name="person_detail_type">
        <input type="hidden" value="<?= $person_detail_nature; ?>" name="person_detail_nature">
    
        <div class="form-group">
            <select class="form-control" name="gender" id="gender">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
            </select>
        </div>

        <div class="form-group">
            <select class="form-control" name="cls" id="cls">
                    <option value="">Select <?= $person_detail_type; ?></option>
                    <?php
                      for($ct=0;$ct<$cnt;$ct++)
                      {
                       ?>
                       <option value="<?= $s_class[$ct]; ?>,<?= $c_detail[$ct]; ?>"><?= $c_detail[$ct]; ?></option>
                       <?php 
                      }
                    ?>
            </select>

        </div>
        <div class="input-group mb-3">
          <input type="text" name="mobile" value="<?php echo $user['mobile']; ?>" class="form-control <?php echo (form_error('mobile')!="") ? 'is-invalid' : ''; ?>" placeholder="Mobile No">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone text-success"></span>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('mobile')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" name="email" value="<?php echo $user['email']; ?>" class="form-control <?php echo (form_error('email')!="") ? 'is-invalid' : ''; ?>" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
             <i class="fas fa-envelope text-success"></i>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('email')); ?></p>
        </div>
        <div class="input-group mb-3">
          <input type="text" name="addr" value="<?php echo set_value('addr'); ?>" class="form-control <?php echo (form_error('addr')!="") ? 'is-invalid' : ''; ?>" placeholder="Address">
          <div class="input-group-append">
            <div class="input-group-text">
            <i class="fas fa-address-book text-success"></i>
            </div>
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('addr')); ?></p>
        </div>
        <div class="row">
            <div class="col-8">
            </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" id="saveBtn" name="saveBtn" class="btn btn-my btn-block">Save</button>
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
  
