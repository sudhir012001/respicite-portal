<style>
  .offline,.online{
    display: inline-block;
    background: red;
    color: white;
    padding: 0px 6px;
    border-radius: 18px;
  }
  .online{
    background: green;
  }
</style>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Approve Code</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("SpController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Approve Code</li>
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
$email = $user['email'];
?>
  <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Code List</h3>
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>S.No.</th>
                      <th>Global ID</th>
                      <th>Email</th>
                      <th>Service Name</th>
                      <th>Payment Mode</th>
                      <th>Payment status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                          <?php 
                            $where = "reseller_id='$email' and status='pending'";
                            $this->db->where($where);
                            $row = $this->db->get('user_code_list');
                            $row_i = 1;
                            foreach($row->result() as $row)
                            {
                                /*
                                echo $row->id."<br>";
                                echo $row->solution."<br>";
                                */
                              
                                ?>
                                <tr>
                                <td><?php echo $row_i; ?></td>
                                <td><?php echo $row->id; ?></td>
                                <td><?php echo $row->user_id; ?></td>
                                <td><?php echo $row->solution; ?></td>
                                <td><div>
                                  <?php if($row->payment_mode == "offline"){
                                    echo "<span class='offline'>Offline<span>";
                                  } 
                                  
                                  if($row->payment_mode == "online"){
                                    echo "<span class='online'>Online<span>";
                                  } 

                                  ?>
                                </div></td>
                                <td><?php 
                                  if($row->payment_status == null){
                                    echo "N/A";
                                  }elseif($row->payment_status == "success"){
                                    echo "Success";
                                  }elseif($row->payment_status == "failed"){
                                    echo "Failed";
                                  }
                                  ?></td>
                                
                                <td><a href="<?php echo base_url(); ?>UserController/code_approvel_for_user/<?php echo $row->id; ?>/<?php echo $row->solution; ?>">Approve Code</a></td>
                                </tr>
                        <?php
                        $row_i++;
                                }
                            
                          ?>  
                  </tbody>
                  
                </table>
                  
                </table>
                
              </div>
              
              
             

              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
          <!-- jQuery -->
<script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url().'assets/plugins/bootstrap/js/bootstrap.bundle.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables/jquery.dataTables.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables-responsive/js/dataTables.responsive.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js';?>"></script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["excel"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info":false ,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
        