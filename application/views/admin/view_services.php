<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="<?php echo base_url('AdminController/update_services_data'); ?>" method="post" id="edit_form">
					<input type="hidden" name="edit_id" id="edit_id" value="">
          <input type="hidden" name="solution" id="solution" value="">
					<div class="form-group">
					<label for="">No. of Reports</label>
					<input type="text" name="edit_report" id="edit_report" class="form-control">
					</div>
					<div class="form-group">
					<label for="">MRP</label>
					<input type="text" name="edit_mrp" id="edit_mrp" class="form-control">
					</div>
          <div class="form-group">
					<label for="">Discount</label>
					<input type="text" name="edit_dis" id="edit_dis" class="form-control">
					</div>
          <div class="form-group">
					<label for="">Discription</label>
					<input type="text" name="edit_discription" id="edit_discription" class="form-control">
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" name="update" id="update">Update</button>
        </form>
			</div>
			</div>
		</div>
		</div>

    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Services List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Service List</li>
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
                <h3 class="card-title">Services List</h3>

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
                    <th>Solution</th>
                      <th>No. of Reports</th>
                      <th>MRP</th>
                      <th>Discount</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php  
                          $i = 0;
                          $j = 0;
                            foreach ($s->result() as $row)  
                            {  
                              $i++;
                             
                              $solution = $row->solution;
                              $services['h'] = $this->User_model->fetch_purchase_code($solution);
                              foreach($services['h']->result() as $row2)
                              {
                                $j++;
                                echo "<tr>";
                                ?>
                                <td><?php echo $row2->solution;?></td>
                                <td><?php echo $row2->no_of_reports;?></td>  
                                <td><?php echo $row2->mrp;?></td>  
                                <td><?php echo $row2->discount;?></td>   
            <td><a class="btn btn-primary" href="#" id="edit" value="<?php echo $row2->id;?>" data-material="">Modify</a>
                <a class="btn btn-primary" href="<?php echo base_url().'AdminController/delete_services/'.$row2->id;?>" >Delete</a>
            </td>  

            </tr>  
                <?php }  
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
  <script
  src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  

<script>
$(document).on("click","#edit", function(e){
	e.preventDefault();
	var edit_id = $(this).attr("value");
	if(edit_id==""){
		alert("Edit ID is required");
	}
	else
	{
    $.ajax({
			url: "<?php echo base_url(); ?>AdminController/edit_services",
			type : "POST",
			dataType:"json",
			data: {
				edit_id: edit_id
			},
			success : function(data){
				if(data.responce == "success"){
					$('#editModal').modal('show');
					$("#edit_id").val(data.post.id);
          $("#solution").val(data.post.solution);
					$("#edit_report").val(data.post.no_of_reports);
          $("#edit_mrp").val(data.post.mrp);
          $("#edit_dis").val(data.post.discount);
          $("#edit_discription").val(data.post.discription);
				}
				else
				{
					alert("something went wrong")
				}
			}
		});

  }
});
  
</script>
        