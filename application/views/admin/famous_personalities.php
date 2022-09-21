<body class="hold-transition login-page">

    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Famous Personalities</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Famous Personalities</li>
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
            <div class="card ">
                <div class="card-body box-profile">
                
                <?php
                    $msg = $this->session->flashdata('msg');
                    if($msg != "")
                    {
                        echo "<div class='alert alert-success'>$msg</div>";
                    }
                ?>  
                

      <form action="<?php echo base_url('') ?>" method="post">
        <label for=""></label>
    
        <div class="form-group">
            <label for="cluster_name">Aptitude</label>
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
            <label for="cluster_name">Personality</label>
            <div class="d-flex">
              <select class="form-control m-2">
                  <option>E</option>
                  <option>I</option>
                  <option>Not Known</option>
              </select>
              <select class="form-control m-2">
                <option>S</option>
                <option>N</option>
                <option>Not Known</option>
              </select>
            </div>

          <div class="d-flex">
            <select class="form-control m-2">
                <option>T</option>
                <option>F</option>
                <option>Not Known</option>
              </select>
              <select class="form-control m-2">
                <option>J</option>
                <option>P</option>
                <option>Not Known</option>
              </select>
            </div>
              
        </div>

        <div class="form-group">
            <label for="cluster_name">Interest</label>
            <div class="d-flex">
              <select class="form-control m-2">                  
                  <option>R</option>
                  <option>I</option>
                  <option>A</option>
                  <option>S</option>
                  <option>E</option>
                  <option>C</option>
                  <option>Not Known</option>
              </select>
              <select class="form-control m-2">                  
                  <option>R</option>
                  <option>I</option>
                  <option>A</option>
                  <option>S</option>
                  <option>E</option>
                  <option>C</option>
                  <option>Not Known</option>
              </select>
              <select class="form-control m-2">                  
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

        <div class="form-group">
            <label for="cluster_name">Attachment File Upload </label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFile">
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
        </div>

        <!-- <label for="">Cluster Description</label> -->
        <!-- textarea -->
        <div class="form-group">
        <label for="cluster_name">Heading 1</label>
        <input type="text" class="form-control" id="cluster_name">
        </div>

        <div class="form-group">
        <label>Description 1</label>
        <textarea class="form-control" rows="3" placeholder="Type here..."></textarea>
        </div>

        <div class="form-group">
        <label for="cluster_name">Heading 2</label>
        <input type="text" class="form-control" id="cluster_name">
        </div>

        <div class="form-group">
        <label>Description 2</label>
        <textarea class="form-control" rows="3" placeholder="Type here..."></textarea>
        </div>

        <div class="form-group">
        <label for="cluster_name">Heading 3</label>
        <input type="text" class="form-control" id="cluster_name">
        </div>

        <div class="form-group">
        <label>Description 3</label>
        <textarea class="form-control" rows="3" placeholder="Type here..."></textarea>
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

  