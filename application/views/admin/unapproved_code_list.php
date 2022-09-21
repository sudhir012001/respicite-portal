<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Pending Code Approval List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Pending List</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
      <?php 
        $msg = $this->session->flashdata('msg');
        if($msg !="")
        {
  ?>     
    <div class="alert alert-success">
            <?php echo $msg; ?>
    </div>
    <?php 
}
?>
  <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pending Code List</h3>

                <!-- <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>-->
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                    <th>Date/Time</th>
                      <th>Email</th>
                      <th>Solution</th>
                      <th>No. of Reports</th>
                      <th>Request Aditional Voucher</th>
                      <th>Action</th>
                     
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  foreach ($h->result() as $row)  
                  {    echo "<tr>";
                    echo "<td>";
                    echo $row->dt;
                    echo "</td>";
                      echo "<td>";
                        echo $email = $row->email;
                        echo "</td>";
                      $code = $row->code;
                      $code_details['cd'] = $this->Admin_model->code_details($code);
                      foreach ($code_details['cd']->result() as $row2)
                      {
                        echo "<td>";
                         echo $solution = $row2->solution;
                          echo "</td>";
                          echo "<td>";
                          echo $qty = $row2->no_of_reports;
                          echo "</td>";
                          echo "<td>";
                          echo $qty = $row->request;
                          echo "</td>";
                      }
                      ?>
                     <td><a href="<?php echo base_url().'AdminController/code_approval/'.$row->id;?>" >Approve</a></td>
                      <?php echo "</tr>";
                  }
                      
                   ?>   
                  
                    
                    
                    
                  </tbody>
                </table>
                
              </div>
             
              
             
</form>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>