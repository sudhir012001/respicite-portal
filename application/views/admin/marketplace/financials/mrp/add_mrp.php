<?php //print_r($currency);die(); ?>
<?php //foreach($currency as $row){ echo $row;die(); }?>
<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Add MRP</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Add MRP</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          
        <div class="row">
            <div class="col-md-1"></div>
          <div class="col-md-10">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                 <p style="float: right;"><a class="btn btn-primary my-3 mx-3"  href="<?php echo base_url("AdminController/marketplace_financials_mrp");?>">MRP List</a></p>
                <div class="card-body box-profile">
                
                <?php
                    $msg = $this->session->flashdata('msg');
                    if($msg != "")
                    {
                        echo "<div class='alert alert-success'>$msg</div>";
                    }
                ?>  
                
        <form action="<?= base_url('AdminController/marketplace_notification_mrp_insert')?>" method="post">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="form-label">Name</label>
                        <input type="text" name="name" value="<?php //echo $trigger[0]->status ?>" class="form-control" placeholder="Name">
                    </div>
                </div>
          </div>
       
            <div class="row" >
                <div class="col">
                    <label for="exampleFormControlInput1" class="form-label">From</label>
                    <div class="form-group">
                        <select name="from" class="form-control">
                            <option value="" >Select Role</option>
                            <option value="Admin" >Admin</option>
                            <option value="Licensee" >Licensee</option>
                            <option value="Sub-associate" >Sub-associate</option>
                            <option value="Customer" >Customer</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label for="exampleFormControlInput1" class="form-label">To</label>
                    <div class="form-group">
                        <select name="to" class="form-control">
                            <option value="" >Select Role</option>
                            <option value="Admin" >Admin</option>
                            <option value="Licensee" >Licensee</option>
                            <option value="Sub-associate" >Sub-associate</option>
                            <option value="Customer" >Customer</option>
                        </select>
                    </div>
                </div>
            </div>
                
            <div class="row" >
                    <div class="col">
                        <label for="exampleFormControlInput1" class="form-label">Value</label>
                        <div class="form-group">
                            <input type="text" name="value" value="<?php //echo $trigger[0]->status ?>" class="form-control" placeholder="Value">
                        </div>
                    </div>
                    <div class="col">
                        <label for="exampleFormControlInput1" class="form-label">Unit</label>
                        <div class="form-group">
                            
                            <select name="unit" class="form-control">
                                <option value="" >Select Unit</option>
                                <?php foreach($currency as $row){ ?>
                                <option value="<?php echo $row ?>" ><?php echo $row ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            
            <div class="row">
              <div class="col-8">
                
              </div>
              <!-- /.col -->
              <div class="col-4" style="margin-top:30px;">
                <button type="submit" name="add_services_btn" class="btn btn-primary btn-block">Submit</button>
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

  