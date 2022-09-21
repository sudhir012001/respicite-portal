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
                    <label>Select Level 4</label> <br>
        </div>
          <!-- <select class="form-control" name="levelone" id="levelone">
          <option value="">Select Services </option> -->
            <?php 
                //lavel first selection
                $this->db->where('email',$user['email']);
                $pdo = $this->db->get('provider_detail_first');
                foreach($pdo->result() as $pdo)
                {
                    $this->db->where('id',$pdo->l1);
                    $pdv = $this->db->get('provider_level_one');
                   
                ?>
                <div class="form-group">
                      <select class="form-control" name="levelone" id="levelone" disabled>
                       
                  <?php 
                       foreach($pdv->result() as $pdv)
                       {  
                    ?>  
                      
                        <option value="<?php echo $pdv->id; ?>"><?php echo $pdv->l1; ?></option>
                    <?php
                      }
                ?>
                      </select>
                      <input type="hidden" name="l1" value="<?php echo $pdv->id; ?>">
                </div>
                <?php    
                }
                
                $this->db->where('email',$user['email']);
                $pdo = $this->db->get('provider_detail_sec');
                ?>
                <div class="form-group">
                <select class="form-control" name="leveltwo" id="leveltwo">
                <option value=''>Select Level Two</option>
                <?php
                foreach($pdo->result() as $pdo)
                {
                    $this->db->where('id',$pdo->l2);
                    $pdv = $this->db->get('provider_level_two');
                       foreach($pdv->result() as $pdv)
                       {  
                    ?>
                        <option value="<?php echo $pdv->id; ?>"><?php echo $pdv->l2; ?></option>
                    <?php
                      }
                  
                }
                ?>
                  </select>
                </div>
                <?php
                $this->db->where('email',$user['email']);
                $pdo = $this->db->get('provider_detail_sec');
                ?>
                <div class="form-group">
                <select class="form-control" name="levelthree" id="levelthree">
                <option value=''>Select Level Three</option>
               
                </select>
                </div>


                <div id="l4">
                
                </div>
          <!-- </select> -->
          <p class="invalid-feedback"><?php echo strip_tags(form_error('cb')); ?></p>
          </div>
          
          
       
        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="save_level4" class="btn btn-primary btn-block">Save</button>
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
  
<script type=text/javascript>
   $(document).ready(function(){
 
    $('#leveltwo').change(function(){ 
        var id=$(this).val();
        $.ajax({
            url : "<?php echo base_url().'SpController/fetch_level';?>",
            method : "POST",
            data : {id: id},
            async : true,
            dataType : 'json',
            success: function(data){
                  
                var htm = '';
                var i;
                
                htm += '<option value="">Select Level Three</option>';
                if(data.length==0)
                {
                  htm += '<option value="0">NA</option>';
                }
                for(i=0; i<data.length; i++){
                    htm += '<option value='+data[i].id+'>'+data[i].l3+'</option>';
                }
                $('#levelthree').html(htm);

            }
        });
        return false;
    }); 

    $('#levelthree').change(function(){ 
        var id=$(this).val();
        var l1=$('#levelone').val();
        var l2=$('#leveltwo').val();
        $.ajax({
            url : "<?php echo base_url().'SpController/fetch_level_five';?>",
            method : "POST",
            data : {
              id: id,
              l1: l1,
              l2: l2,
            },
            async : true,
            dataType : 'html',
            success: function(data){
                  
               
                $('#l4').html(data);

            }
        });
        return false;
    }); 
  
});     
    </script> 