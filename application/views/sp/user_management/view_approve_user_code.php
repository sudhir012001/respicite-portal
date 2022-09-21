<body class="hold-transition sidebar-mini">
<div id="modalHomeEvents" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;">
        <b>Service Details</b>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
    
        <input type="hidden" name="eventId" id="eventId"/>
        	<span id="idHolder"></span>	
            
        </div>
        <div class="modal-footer">
          
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Clients</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("SpController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Clients</li>
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
                    <th>Email</th>
                      <th>Service Name</th>
                      <th>Code</th>
                      <th>Status</th>
                      <th>Action</th>
                     
                    </tr>
                  </thead>
                  <tbody>
                          <?php 
                          if(!empty($s)){
                            foreach($s->result() as $row)
                            {
                             
                        ?>
                                <tr>
                                <td><?php echo $row->user_id; ?></td>
                                <td><?php echo $row->solution; ?></td>
                                <td><?php echo $row->code; ?></td>
                                <?php 
                                    $status = $row->status;
                                   
                                ?>
                                <td>
                                <?php
                                  
                                 if($status=='Ap')
                                    {
                                     echo "Assessment Pending";
                                    }
                                    else if($status=='Rp')
                                    {
                                      echo "Report Pending";
                                    }
                                    else
                                    {
                                      echo "Report Downloaded";
                                    }?>
                                    </td>
                                <td><div class="row">
                    <div class="col-sm-12">
                     <!-- select  -->
                      <div class="form-group">
                                <?php 
                                    if($status=='Ap')
                                    {
                                     echo "";
                                    }
                                    else if($status=='Rp')
                                    {
                                      ?>
                                      <a href="<?php echo base_url().'OtherAjax/download_report.php?code='.base64_encode($row->code); ?>">View Report</a><br>
                                      <a href="<?php echo base_url().'UserController/Update_Counsellor_remarks/'.base64_encode($row->code); ?>">Counsellor Remarks</a>

                                    <?php 
                                    }
                                    else
                                    {
                                      ?>
                                      <a href="<?php echo base_url().'OtherAjax/download_report.php?code='.base64_encode($row->code); ?>">View Report</a><br>
                                      <a href="<?php echo base_url().'UserController/Update_Counsellor_remarks/'.base64_encode($row->code); ?>">Counsellor Remarks</a>
                                    <?php 
                                    }

                                ?>
                       
                        <!-- <select class="form-control" name="act" id="act<?php echo $row->id; ?>" onchange="changeThis(<?php echo $row->id; ?>)">
                          <option value="">Change Status</option>
                          <option value="Ap"><?php echo "Assessment Pending"; ?></option>
                          <option value="Rp"><?php echo "Report Pending"; ?></option>
                          <option value="Cp"><?php echo "Counseling Pending"; ?></option>
                          <option value="Fp"><?php echo "FeedBack Pending"; ?></option>
                        </select> -->
                      </div>
                    </div>
                    </div></td> 
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
         <!-- jQuery -->
<script src="<?php echo base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url().'assets/plugins/bootstrap/js/bootstrap.bundle.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables/jquery.dataTables.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables-responsive/js/dataTables.responsive.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/dataTables.buttons.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/jszip/jszip.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/pdfmake/pdfmake.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/pdfmake/vfs_fonts.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.html5.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.print.min.js';?>"></script>
<script src="<?php echo base_url().'assets/plugins/datatables-buttons/js/buttons.colVis.min.js';?>"></script>
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
      "info": false,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
        <script
  src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  
        <script>
$(document).on("click", ".open-homeEvents", function () {
     var eventId = $(this).data('id');
     $.ajax({
            type : 'post',
            url : '<?php echo base_url()."OtherAjax/fetch_record.php"; ?>', //Here you will fetch records 
            data :  'rowid='+ eventId, //Pass $id
            success : function(data){
            $('#idHolder').html(data);//Show fetched data from database
            }
        });
     
});
function changeThis(s) { 
          var s = s
          var a = 'act'.concat(s);
	        var act = document.getElementById(a).value;
              if(s=="")
              {
                alert("Something Went Wrong");
              }
              else
              {
                $.ajax({
                  url: "<?php echo base_url(); ?>UserController/update_code_status",
                  type : "POST",
                  dataType:"json",
                  data: {
                    s: s,
                    act : act
                  },
                  success : function(data)
                  {
                    if(data.responce == "success")
                    {
                      toastr["success"](data.message)

                      toastr.options = {
                      "closeButton": true,
                      "debug": false,
                      "newestOnTop": false,
                      "progressBar": true,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "300",
                      "hideDuration": "1000",
                      "timeOut": "5000",
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut"
                        }
                        location.reload(true)
                    }
                    else
                    {
                      alert("something went wrong")
                    }
                    
                  }
                 
                });

              }
            }; 
</script>
        