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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Requested User</a></li>
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
                <h3 class="card-title">Code List</h3>
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                    
                      <th>Service Name</th>
                      <th>Code</th>
                      <th>Status</th>
                      <th>Action</th>
                     
                    </tr>
                  </thead>
                  <tbody>
                          <?php 
                            foreach($s->result() as $row)
                            {
                              
                        ?>
                                <tr>
                                <td><?php echo $row->solution; ?></td>
                                <td><?php echo $row->code; ?></td>
                                <?php 
                                    $status = $row->status;
                                    if($status=='Ap')
                                    {
                                ?>
                                      <td>Assessment  Pending</td>
                                      <td><a style="cursor: pointer;" class="open-homeEvents" data-id="<?php echo $row->id; ?>"  data-toggle="modal" data-target="#modalHomeEvents">Take Assessment</a></td>
                                <?php      
                                    }
                                    else if($status=='Rp')
                                    {
                                ?>
                                      <td>Report  Pending</td>
                                      <td><a href="<?php echo base_url().'BaseController/download_report/'.base64_encode($row->code); ?>">Download Report</a></td>
                                <?php      
                                    }
                                    else if($status=='Cp')
                                    {
                                ?>
                                      <td>Counseling  Pending</td>
                                      <td>Counseling</td>
                                <?php      
                                    }
                                    else
                                    {
                                ?>
                                      <td>Feedback  Pending</td>
                                      <td>Give Feedback</td>
                                <?php 
                                    }
                                ?>
                                
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
  
</script>
        