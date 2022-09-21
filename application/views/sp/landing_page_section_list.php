
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Landing Page Settings</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("SpController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Landing Page Settings List</li>
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
                <h3 class="card-title">Landing Page Settings List</h3>

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
            
                <button class="btn btn-primary" onclick="addFlow(<?php //echo $flow[0]['id']?> )" style="float: right;">Add Landing Page Settings </button>
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                    <th>S NO</th>
                      <th>Section Name</th>
                      <th>Configration</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                          <?php //echo "<pre>";print_r($flow);
                          $i=1;
                          if(isset($flow)){
                          foreach($flow as $row2) { ?>
                            <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row2['SectionName'];?></td>  
                            <td><button class="btn btn-info btn-xs" onclick="addFlows('<?php echo $row2['sectionId'] ?>','<?php echo $landingId ?>','<?php echo $resellerId ?>')">Config </button></td>  
                            <td><a class="btn btn-info" href="<?php echo base_url().'SpController/landingSectionDelete/'.$row2['id'];?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a></td>   
                            <!--<td><a class="btn btn-primary" href="#" id="edit" value="<?php echo $row2->id;?>" data-material="">Modify</a>-->
                            <!--    <a class="btn btn-primary" href="<?php //echo base_url().'AdminController/delete_services/'.$row2->id;?>" >Delete</a>-->
                            <!--</td>  -->
        
                         </tr>  
                        <?php $i++; }
                        } else { ?>  
                                    <tr> <td><p>Data Not Found!</p></td></tr>
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
        <!--Add Parameter details Start -->
            <div class="modal fade" id="AddOMRTemplate12" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Landing Page Settings</h5>
                    <button type="button" onclick="addFlowClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="<?php echo base_url("SpController/addParameterValues");?>" method="post" enctype="multipart/form-data">
                        <div id="paramDataSection"></div>
                        
                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="landingId" id="landingId" value="">
                        <input type="hidden" name="resellerId" id="resellerId" value="">
                      <!--<div class="row">-->
                      <!--    <div class="col-md-12">-->
                      <!--      <div class="mb-3">-->
                      <!--        <label class="form-label" for="basic-default-fullname">Landing Page Descripation</label>-->
                      <!--        <input type="text" name="descripation" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Descripation" />-->
                      <!--        <div class="render-template-msg"></div>-->
                      <!--    </div>-->
                      <!--  </div>-->
                      <!--</div>-->
                      <!--<div class="row">-->
                      <!--    <div class="col-md-12">-->
                      <!--      <div class="mb-3">-->
                      <!--        <label class="form-label" for="basic-default-fullname">Landing Page Path</label>-->
                      <!--        <input type="text" name="path" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Path" />-->
                      <!--        <div class="render-template-msg"></div>-->
                      <!--    </div>-->
                      <!--  </div>-->
                      <!--</div>-->
                     
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
         <!--Add Parameter Start -->
            <div class="modal fade" id="AddOMRTemplate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Landing Page Settings</h5>
                    <button type="button" onclick="addFlowClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="<?php echo base_url("SpController/addLandingSectionParameter");?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Landing Page Section Name</label>
                              <select class="form-control" name="section_name" id="" placeholder="Landing Page Section Name">
                                    <option value="">Select Section</option>
                                    <?php foreach($landing_page_section as $row) { ?>
                                      <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                                    <?php } ?>
                               </select>
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                       <input type="hidden" name="landingId" value="<?php  echo $landingId?>">
                       <input type="hidden" name="resellerId" value="<?php  echo $resellerId?>">
                      </div>
                      <!--<div class="row">-->
                      <!--    <div class="col-md-12">-->
                      <!--      <div class="mb-3">-->
                      <!--        <label class="form-label" for="basic-default-fullname">Landing Page Descripation</label>-->
                      <!--        <input type="text" name="descripation" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Descripation" />-->
                      <!--        <div class="render-template-msg"></div>-->
                      <!--    </div>-->
                      <!--  </div>-->
                      <!--</div>-->
                      <!--<div class="row">-->
                      <!--    <div class="col-md-12">-->
                      <!--      <div class="mb-3">-->
                      <!--        <label class="form-label" for="basic-default-fullname">Landing Page Path</label>-->
                      <!--        <input type="text" name="path" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Path" />-->
                      <!--        <div class="render-template-msg"></div>-->
                      <!--    </div>-->
                      <!--  </div>-->
                      <!--</div>-->
                     
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
                    <h5 class="modal-title" id="exampleModalLabel">Landing Page Configration</h5>
                    <button type="button" onclick="addFlowClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="<?php echo base_url("SpController/insertLandingSectinFullDetails");?>" method="post" enctype="multipart/form-data">
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
                function editParameter(id,landingId,resellerId) {
                    console.log(name);

                    let editParam = "";
                    editParam += ` <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Landing Page Parameter</label>
                              <input type="text" name="<?php echo $row['parameter']?>" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Descripation" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Landing Page Value</label>
                              <input type="text" name="values" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Value" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                        </div>
                        <input type="hidden" name="id" value="${id}">
                        <input type="hidden" name="landingId" value="${landingId}">
                        <input type="hidden" name="resellerId" value="${resellerId}">`;
                        
                       
                      
                      $("#paramData").append(editParam);
                      $("#paramData").empty().append(editParam);
                    $("#editParameter").modal("show");
                }
                    
            </script>
        <script>
 
          function addFlow($id){  
            $("#AddOMRTemplate").modal("show");
          }
        function addFlows($id ,$landingId,$resellerId){ 
            $("#id").val($id);
            $("#landingId").val($landingId);
            $("#resellerId").val($resellerId);
            $.ajax({
                  url: "<?php echo base_url(); ?>SpController/section_via_parameter",
                  type : "POST",
                  dataType:"json",
                  data: {
                    sectionId: $id
                  },
                  success : function(data)
                  {
                    //console.log('dffdgdfgdfgd'+data);
                      $("#paramDataSection").append(data);
                      $("#AddOMRTemplate12").modal("show");
                      //location.reload(true);
                    
                  }
                });
            
          }
           function addFlowClose($id){  
            $("#AddOMRTemplate").modal("hide");
          }
        </script>