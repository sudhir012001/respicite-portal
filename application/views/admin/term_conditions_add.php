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
            Tearm and Conditions add page
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="<?php echo base_url('AdminController/dashboard');?>"
                >Dashboard</a
              >
            </li>
            <li class="breadcrumb-item active">Add T&C</li>
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
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <!-- Profile Image -->
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <!-- show message #SATRT -->
              <?php if(!empty($this->session->flashdata("INSERT_CHECK_MSG"))){
              $INSERT_CHECK_MSG = $this->session->flashdata("INSERT_CHECK_MSG");
              if($INSERT_CHECK_MSG == "OK"){ ?>
              <div class="alert alert-success pb-0">
                <p>Your record has been added successfully</p>
              </div>
              <?php }
                    if($INSERT_CHECK_MSG == "NO"){
              ?>
              <div class="alert alert-danger pb-0">
                <p>Your record is not added, try again.</p>
              </div>
              <?php } } ?>
              <!-- show message #END-->
              <form action="" method="post">
                <div class="input-group mb-0 text-danger">
                  <?php echo form_error("heading");?>
                </div>
                <div class="input-group mb-3">
                  <input
                    type="text"
                    name="heading"
                    class="form-control"
                    placeholder="Heading *"
                  />
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-book"></span>
                    </div>
                  </div>
                  <p class="invalid-feedback"></p>
                </div>
                <div class="input-group mb-3">
                  <input
                    type="text"
                    class="form-control"
                    name="cat_title"
                    placeholder="Category Title"
                  />
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-book"></span>
                    </div>
                  </div>
                  <p class="invalid-feedback"></p>
                </div>

                <div class="input-group mb-0 text-danger">
                  <?php echo form_error("description");?>
                </div>

                <div class="input-group mb-3">
                  <textarea
                    name="description"
                    class="form-control"
                    cols="30"
                    rows="10"
                    placeholder="Description *"
                  ></textarea>

                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-book"></span>
                    </div>
                  </div>
                  <p class="invalid-feedback"></p>
                </div>

                <div class="row">
                  <div class="col-8"></div>
                  <!-- /.col -->
                  <div class="col-4">
                    <button
                      type="submit"
                      name="add_tc_btn"
                      class="btn btn-primary btn-block"
                    >
                      Add T&C
                    </button>
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
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
