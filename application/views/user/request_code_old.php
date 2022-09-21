<style>a{color:black;}</style>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Code List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Purchase code</li>
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
                <table id="example1" class="table table-bordered table-striped">
              
                  <thead>
                    <tr>
                      <th>Service Name</th>
                      <th>Action</th>
                     
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
                            // $where = "user_id='$code' and email!='$email' and iam='reseller'";
                            $where = "user_id='$code' and email!='$email' and (iam='reseller' or iam='sp')";
                            $this->db->where($where);
                            $row2 = $this->db->get('user_details');
                            foreach($row2->result() as $row2)
                            {
                                $r_email = $row2->email;
                            }

                            $sl = $this->db->get('solutions');
                            foreach($sl->result() as $sl)
                            {
                                $solution = $sl->solution;
                                $dsn = $sl->display_solution_name;
                                
                                $where = "email='$r_email' and solution='$solution'";
                                $this->db->where($where);
                                $list_no = $this->db->get('generated_code_details')->num_rows();
                                if($list_no>0)
                                {
                        ?>
                                <tr>
                                <?
                                
                                            //Added by Sudhir
                                            /*
                                            echo "Input Parameters :<br>";
                                            echo "Email :".$email."<br>";
                                            echo "R_Email :".$r_email."<br>";
                                            echo "Solution :".$solution."<br>";
                                            echo "dsn :".$dsn."<br>";
                                            */
                                            //End of added by Sudhir
                                
                                
                                ?>
                                <td><?php echo $solution; ?></td>
                                <td><a href="<?php echo base_url(); ?>BaseController/user_request_code/<?php echo $email; ?>/<?php echo $r_email; ?>/<?php echo $solution; ?>/<?php echo $dsn; ?>">Request Code</a></td>
                                </tr>
                        <?php
                                }
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
        <script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["excel"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
