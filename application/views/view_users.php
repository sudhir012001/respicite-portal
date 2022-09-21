<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item active">User List</li>
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
                <h3 class="card-title">User List</h3>
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>User Name</th>
                      <th>User Email</th>
                      <th>Mobile</th>
                      <th>Solution Purchased</th>

                    </tr>
                  </thead>
                  <tbody>
                          <?php 
                          
                            $this->db->where('email',$email);
                            $row = $this->db->get('user_details');
                            foreach($row->result() as $row)
                            {
                              $code = $row->user_id;
                            }
                            $where = "user_id='$code' and email!='$email' and status='1'";
                            $this->db->where($where);
                            $row2 = $this->db->get('user_details');
                            foreach($row2->result() as $row2)
                            {
                              $coma_count = 0;
                              ?>
                              <tr>
                                <td><?php echo $row2->fullname; ?></td>
                                <td><?php echo $row2->email; ?></td>
                                <td><?php echo $row2->mobile; ?></td>
                                <td>
                                  <?php 
                                    $solu = $this->db->get('solutions');
                                    foreach($solu->result() as $solu)
                                    {
                                      $sl = $solu->solution;
                                      $where5 = "user_id='$row2->email' and solution='$sl'";
                                      $this->db->where($where5);
                                      $ul_no = $this->db->get('user_code_list')->num_rows();
                                      if($ul_no>0)
                                      {
                                        if($coma_count==0)
                                        {
                                          echo $sl;
                                          $coma_count = 1;
                                        }
                                        else
                                        {
                                          echo ", ".$sl;
                                        }
                                        
                                      }
                                      
                                    }
                                  ?>
                                </td>
                                </tr>
                              <?php
                            }
                          ?>  
                  </tbody>
                  
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
