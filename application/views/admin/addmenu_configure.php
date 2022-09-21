<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-1">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Add Menu
            </h1>
             
          </div>
          <div class="col-sm-5">
             <a class="btn btn-primary" href="<?php echo base_url().'AdminController/list_menu'; ?>" style="float: left;">List Menu</a>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Add Menu
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
                <h3 class="profile-username text-center">Add
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
               

      <form action="<?php echo base_url('AdminController/insertMenu') ?>" method="post">
        <div class="form-group">

            <select class="form-control" name="role" >
              <option value="">Select Role</option>
              <option value="admin">Admin</option>
              <option value="serviceprovider">Service Provider</option>
              <option value="reseller">Reseller</option>
            </select>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('role')); ?></p>
          </div>
        <div class="form-group">

          <select class="form-control" name="parent_id" id="parent_id">
            <option value="">Select Main Menu</option>
            <?php 
                foreach ($mainmenu as $row)  
                 {  
                    
                    ?>
                    <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option> 
                    <?php
                 }
                ?>
          </select>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('parent_id')); ?></p>
          </div>
          <div class="form-group">

           <input type="text" class="form-control" name="submenu" id="submenu" placeholder="Enter Sub Menu">
          <p class="invalid-feedback"><?php echo strip_tags(form_error('submenu')); ?></p>
          </div>
          <div class="form-group">
            <select class="form-control" name="status" id="status"  >
              <option value="">Select Status</option>
              <option value="dev">Development</option>
              <option value="active">Active</option>
              <option value="deactive">Deactive</option>
            </select>
          <p class="invalid-feedback"><?php echo strip_tags(form_error('status')); ?></p>
          </div>
           <div class="form-group">

           <input type="text" class="form-control" name="controller" id="controller" placeholder="Controller">
          <p class="invalid-feedback"><?php echo strip_tags(form_error('controller')); ?></p>
          </div>
           <div class="form-group">

           <input type="text" class="form-control" name="parameter" id="parameter" placeholder="Parameter">
          <p class="invalid-feedback"><?php echo strip_tags(form_error('parameter')); ?></p>
          </div>
          </div>
         
       
        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="request_btn" class="btn btn-primary btn-block">Add</button>
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
  
<!-- <script type=text/javascript>
    
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
                        for(i=0; i<data.length; i++){
                            htm += '<option value='+data[i].id+'>'+data[i].l2+'</option>';
                        }
                        $('#leveltwo').html(htm);
 
                    }
                });
                return false;
            }); 
             
        });      
    </script>   -->