<style>
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }
  .book-call,.book-msg{
    display: inline-block;
    background: #5cb85c;
    color: white;
    padding: 0px 10px;
    border-radius: 18px;
  }
  .book-msg{
    background: #2e6da4;
  }
</style>
<div class="content-wrapper bg-white" style="min-height: 706px">
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
          Notifications
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="https://users.respicite.com/SpController/dashboard"
                >Dashboard</a
              >
            </li>
            <li class="breadcrumb-item active">Notifications</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
    

  <section class="content ">
    <div class="container-fluid"> 
        <!-- main content #start -->
        <div class="card">              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th>Message</th>
                        <th>Appointment Type</th>
                        <th>AP Request Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($ap_book_data)){ foreach($ap_book_data as $v){ ?>                    
                        <tr>                            
                            <td><?php echo $v->name;?></td>
                            <td><?php echo $v->email;?></td>
                            <td><?php echo $v->phone_no;?></td>
                            <td><?php echo $v->location;?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary btn-view-msg" data-msg="<?php echo htmlspecialchars_decode($v->message,ENT_NOQUOTES);?>">View</button>
                            </td>
                            <td><?php if($v->appointment_type == "interested_call_back"){
                                echo "<p class='book-call'>Interested for Call Back</p>";
                            }elseif($v->appointment_type == "message"){
                                echo "<p class='book-msg'>Message</p>";
                            }?></td>
                            <td><?php echo date('d-m-Y h:i a', strtotime($v->created_at));?></td>
                        </tr>
                    <?php } }else{ echo "<tr><td colspan='8' class='text-center'>Data Not Found.</td></tr>";} ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
        <!-- main content #end -->

    </div>
  </section>
</div>

<div class="modal fade" id="modal_view_message" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Message</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>

</div>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>

<script>
  const BASE_URL = '<?php echo base_url();?>';
  $(document).on("click",".btn-view-msg",function(){
    let msg = $(this).data("msg");
    $("#modal_view_message").modal("show");;
    $("#modal_view_message").find(".modal-body").html(msg);
  });
</script>
