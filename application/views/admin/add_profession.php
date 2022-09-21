<body class="hold-transition login-page">

    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Add Profession</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Add Profession</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          <!-- Basic Input #Start -->
          <div class="card card-default collapsed-card">
            <div class="card-header">
              <h3 class="card-title">
                 Basic Input
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                  <div class="card">
                    <!-- <div class="card-header">
                      <h4 class="card-title">Basic Input</h4>
                    </div> -->
                      <div class="card-body box-profile">
                      <form action="<?php echo base_url('') ?>" method="post">

                        <div class="form-group">
                        <label for="cluster_name">Profession Name</label>
                        <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                            <label for="cluster_name">Cluster</label>
                            <select class="form-control">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>Not Known</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cluster_name">Path</label>
                            <select class="form-control">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>Not Known</option>
                            </select>
                        </div>

                        <div class="form-group">
                          <div class="d-flex">
                              <div class="m-2">
                                <label for="cluster_name">J1</label>
                                <select class="form-control">
                                  <option>R</option>
                                  <option>I</option>
                                  <option>A</option>
                                  <option>S</option>
                                  <option>E</option>
                                  <option>C</option>
                                  <option>Not Known</option>
                                </select>
                              </div>

                              <div class="m-2">
                                <label for="cluster_name">J2</label>
                                <select class="form-control">
                                  <option>R</option>
                                  <option>I</option>
                                  <option>A</option>
                                  <option>S</option>
                                  <option>E</option>
                                  <option>C</option>
                                  <option>Not Known</option>
                                </select>
                              </div>

                              <div class="m-2">
                                <label for="cluster_name">J3</label>
                                <select class="form-control">
                                  <option>R</option>
                                  <option>I</option>
                                  <option>A</option>
                                  <option>S</option>
                                  <option>E</option>
                                  <option>C</option>
                                  <option>Not Known</option>
                                </select>
                              </div>
                            </div>
                        </div>


                        <div class="row">
                          <div class="col-8">
                            
                          </div>
                          <!-- /.col -->
                          <div class="col-4">
                            <button type="submit" name="add_services_btn" class="btn btn-primary btn-block">Add</button>
                          </div>
                          <!-- /.col -->
                        </div>
                      </form>
                      <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
                    </div>
                    <!-- /.card-body -->
                  </div>           
                </div>
              </div>
            </div>
          </div>
          <!-- Basic Input #End -->

          <!-- Add Aptitude  #Start -->
          <div class="card card-default collapsed-card">
            <div class="card-header">
              <h3 class="card-title">
                  Add Aptitude
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                  <div class="card">
                      <div class="card-body box-profile">
                      <form action="<?php echo base_url('') ?>" method="post">
                        <div class="form-group">
                            <label for="cluster_name">Cluster</label>
                            <select class="form-control">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>Not Known</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cluster_name">Path</label>
                            <select class="form-control">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>Not Known</option>
                            </select>
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">Profession Name</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">Overall</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">Aptitude</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">AR_v</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">VR_v</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">SA_v</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">COM_v</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">NC_v</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">OM_v</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">AR_i</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">VR_i</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">SA_i</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">COM_i</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">NC_i</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">OM_i</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">AR_w</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">VR_w</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">SA_w</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">COM_w</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">NC_w</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">OM_w</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="row">
                          <div class="col-8">
                            
                          </div>
                          <!-- /.col -->
                          <div class="col-4">
                            <button type="submit" name="add_services_btn" class="btn btn-primary btn-block">Add</button>
                          </div>
                          <!-- /.col -->
                        </div>
                      </form>
                    </div>
                    <!-- /.card-body -->
                  </div>           
                </div>
              </div>
            </div>
          </div>
          <!-- Add Aptitude  #End -->

          <!-- Add Education  #Start -->
          <div class="card card-default collapsed-card">
            <div class="card-header">
              <h3 class="card-title">
                	<!-- Add Interest  -->
                  Add Education
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                  <div class="card">
                      <div class="card-body box-profile">
                      <form action="<?php echo base_url('') ?>" method="post">
                        <div class="form-group">
                            <label for="cluster_name">Cluster</label>
                            <select class="form-control">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>Not Known</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cluster_name">Path</label>
                            <select class="form-control">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>Not Known</option>
                            </select>
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">Education</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">11th,12th</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                          <label for="cluster_name">11th,12th remarks</label>
                          <input type="text" class="form-control" id="cluster_name">
                        </div>

                        <div class="form-group">
                            <label for="cluster_name">Display Priority</label>
                            <select class="form-control">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                            </select>
                        </div>

                        <div class="row">
                          <div class="col-8">
                            
                          </div>
                          <!-- /.col -->
                          <div class="col-4">
                            <button type="submit" name="add_services_btn" class="btn btn-primary btn-block">Add</button>
                          </div>
                          <!-- /.col -->
                        </div>
                      </form>
                    </div>
                    <!-- /.card-body -->
                  </div>           
                </div>
              </div>
            </div>
          </div>
          <!-- Add Education  #End -->
      
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  