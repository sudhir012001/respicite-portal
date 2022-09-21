<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Add Level four</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Add Level four</li>
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
                <h3 class="profile-username text-center">Add Level Four</h3>
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
      <select class="form-control" name="levelone" id="levelone">
            <option value="">Service Name</option>
            <?php 
                foreach ($l->result() as $row)  
                {  
                    
                    ?>
                    <option value="<?php echo $row->id; ?>"><?php echo $row->l1; ?></option>
                    <?php
                }
                ?>
          </select>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('levelone')); ?></p>
          </div>
          
          <div class="form-group">

            <select class="form-control" name="leveltwo" id="leveltwo"  >
              <option value="">Select</option>
            </select>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('leveltwo')); ?></p>
          </div>
         
         
        <div class="form-group">

            <select class="form-control" name="levelthree" id="levelthree">
            <option value="">Select</option>
            
            </select>
            <p class="invalid-feedback"><?php echo strip_tags(form_error('levelthree')); ?></p>
        </div>
       

        <div class="input-group mb-3">
          <input type="text" name="param_one"  value="<?php echo set_value('param_one'); ?>" class="form-control <?php echo (form_error('param_one')!="") ? 'is-invalid' : ''; ?>" placeholder="Enter Parameter One">
         
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-book"></span>
            </div>
            
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('param_one')); ?></p>
        </div>
        
        <div class="input-group mb-3">
          <input type="text" name="param_two"  value="<?php echo set_value('param_two'); ?>" class="form-control <?php echo (form_error('param_two')!="") ? 'is-invalid' : ''; ?>" placeholder="Enter Parameter two">
         
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-book"></span>
            </div>
            
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('param_two')); ?></p>
        </div>
       
        <div class="input-group mb-3">
          <input type="text" name="param_three"  value="<?php echo set_value('param_three'); ?>" class="form-control <?php echo (form_error('param_three')!="") ? 'is-invalid' : ''; ?>" placeholder="Enter Parameter three">
         
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-book"></span>
            </div>
            
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('param_three')); ?></p>
        </div>

        <div class="input-group mb-3">
          <input type="text" name="param_four"  value="<?php echo set_value('param_four'); ?>" class="form-control <?php echo (form_error('param_four')!="") ? 'is-invalid' : ''; ?>" placeholder="Enter Parameter four">
         
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-book"></span>
            </div>
            
          </div>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('param_four')); ?></p>
        </div>
        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="saveBtn" class="btn btn-primary btn-block">Add</button>
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
 
    $('#levelone').change(function(){ 
                var id=$(this).val();
                $.ajax({
                    url : "<?php echo base_url().'AdminController/fetch_level_two';?>",
                    method : "POST",
                    data : {id: id},
                    async : true,
                    dataType : 'json',
                    success: function(data){
                         
                        var htm = '';
                        var i;
                        htm += '<option value="">Select Level Two</option>';
                        if(data.length==0)
                        {
                          htm += '<option value="0">NA</option>';
                        }
                        for(i=0; i<data.length; i++){
                            htm += '<option value='+data[i].id+'>'+data[i].l2+'</option>';
                        }
                        $('#leveltwo').html(htm);
 
                    }
                });
                return false;
            }); 
             
            $('#leveltwo').change(function(){ 
                var id=$(this).val();
                $.ajax({
                    url : "<?php echo base_url().'AdminController/fetch_level_three';?>",
                    method : "POST",
                    data : {id: id},
                    async : true,
                    dataType : 'json',
                    success: function(data){
                         
                        var ht = '';
                        var i;
                        ht += '<option value="">Select Level Three</option>';
                        if(data.length==0)
                        {
                          ht += '<option value="0">NA</option>';
                        }
                        for(i=0; i<data.length; i++){
                            ht += '<option value='+data[i].id+'>'+data[i].l3+'</option>';
                        }
                        $('#levelthree').html(ht);
 
                    }
                });
                return false;
            }); 
        });      
 
 
         
            
      
    </script>  
  