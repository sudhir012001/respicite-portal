<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Trigger Notification List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Trigger Notification List</li>
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
                <h3 class="card-title">Trigger Notification List</h3>
                <p style="float: right;"><a class="btn btn-primary"  href="<?php echo base_url("AdminController/marketplace_notification_add_triggers");?>">Trigger Notification</a></p>
              
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th width="30%">Activity</th>
                      <th>Status</th>
                      <th>Notification</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                     <?php foreach($trigger as $row) { ?>
               <tr>  
              
                    <td><?php echo $row['activity'];?></td>  
                    <td><?php echo $row['status'];?></td> 
                    <td><?php echo $row['notification'];?></td> 
                    <td><a class="btn btn-info" href="<?php echo base_url().'AdminController/editNotificationTrigger/'.$row['id'];?>">Edit Status</a>
                    </td> 
                  </tr>  
                <?php } ?>

                  </tbody>
                </table>
              </div>
            </form>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

         <!--Add Parameter Start -->
            <div class="modal fade" id="AddOMRTemplate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Flow Parameter</h5>
                    <button type="button" onclick="addFlowClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="<?php echo base_url("AdminController/addParameter");?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Parameter Name</label>
                              <input type="text" name="parameter" class="form-control" id="basic-default-fullname" required placeholder="Parameter Name" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                       <input type="hidden" name="id" value="<?php echo $flow[0]['id']?>">
                      </div>
                     
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="addFlowClose()" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary btn-submit"/>
                  </div>
                </form>
                </div>
              </div>
            </div>
             <!-- Add Parameter Closed -->
             
              <!--Edit Parameter Start -->
            <div class="modal fade" id="editParameter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Parameter</h5>
                    <button type="button" onclick="addFlowClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="<?php echo base_url("AdminController/updateParameter");?>" method="post" enctype="multipart/form-data">
                      <!--  <div class="row">-->
                      <!--    <div class="col-md-12">-->
                      <!--      <div class="mb-3">-->
                      <!--        <label class="form-label" for="basic-default-fullname">Parameter Name</label>-->
                      <!--        <input type="text" name="parameter" class="form-control" id="basic-default-fullname" required placeholder="Parameter Name" />-->
                      <!--        <div class="render-template-msg"></div>-->
                      <!--    </div>-->
                      <!--  </div>-->
                      <!-- <input type="hidden" name="id" value="<?php //echo $flow[0]['id']?>">-->
                      <!--</div>-->
                      <div id="paramData"></div>
                     
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="addFlowClose()" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary btn-submit"/>
                  </div>
                </form>
                </div>
              </div>
            </div>
             <!-- Edit Parameter Closed -->
            <script src="<?php echo base_url().'assets/custom.js/list_flow.js'; ?>"></script>
            
            <script>
                function editParameter(id,k,mdata,v) {

                    let editParam = "";
                    editParam += ` <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Parameter Name</label>
                              <input type="text" name="parameter" class="form-control" id="basic-default-fullname" value="${v}" required placeholder="Parameter Name" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                       <input type="hidden" name="id" value="${id}">
                       <input type="hidden" name="key" value="${k}">
                       <input type="hidden" name="name" value="${mdata}">
                      </div>`;
                      
                      $("#paramData").append(editParam);
                      $("#paramData").empty().append(editParam);
                    $("#editParameter").modal("show");
                }
                    
            </script>
        <script>
 
          function addFlow($id){  
            $("#AddOMRTemplate").modal("show");
          }

           function addFlowClose($id){  
            $("#AddOMRTemplate").modal("hide");
          }
        </script>