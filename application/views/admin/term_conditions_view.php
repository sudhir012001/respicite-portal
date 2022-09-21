<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section
    class="content-header mb-3"
    style="
      padding: 6px 0.5rem;
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
    "
  >
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em; color: #7f7f7f">
            Terms and Conditions All View
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="<?php echo base_url('AdminController/dashboard');?>"
                >Dashboard</a
              >
            </li>
            <li class="breadcrumb-item active">View T&C</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <!-- Profile Image -->
          <div class="card">
            <div class="card-body box-profile">
              <!-- repeat #START -->
              <?php if(!empty($db_data)){ foreach($db_data as $rows){?>
              <div class="border p-3 rounded">
                <h3 class="pl-2"><?php echo $rows->heading; ?></h3>
                <hr class="p-0 m-0" />
                <h4 class="pl-2">
                  <?php echo (!empty($rows->cat_title))?$rows->cat_title:""; ?>
                </h4>
                <p class="pl-2"><?php echo $rows->description; ?></p>
                <button class="btn btn-sm btn-warning m-1">Edit</button>
                <button class="btn btn-sm btn-danger m-1">Delete</button>
              </div>
              <br />
              <?php } }else{ echo "Not Data Found."; } ?>
              <!-- repeat #END -->
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
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
