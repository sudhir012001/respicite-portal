<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Landing Page List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Landing Page List</li>
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
                <h3 class="card-title">Landing Page List</h3>
                <button class="btn btn-primary" onclick="addFlow(<?php echo $flow[0]['id']?> )" style="float: right;">Add Landing Page </button>
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered">
                  <colgroup>
                    <col class="col-lg-2">
                    <col class="col-lg-6">
                    <col class="col-lg-2">
                    <col class="col-lg-2">
                  </colgroup>
                  <thead>
                    <tr>
                    <th>Name</th>
                      <th>Descripation</th>
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  foreach($flow as $row2) { ?>
                    <tr>
                    <td><?php echo $row2['name'];?></td>
                    <td><?php echo $row2['descripation'];?></td>  
                    <td>
                      <button class="btn btn-info btn-xs" onclick="editParameter('<?php echo $row2['name'] ?>','<?php echo $row2['descripation'] ?>','<?php echo $row2['path'] ?>','<?php echo $row2['id'] ?>')">Edit </button>&nbsp;
                      <button class="btn btn-info btn-xs" onclick="addFlowSection('<?php echo $row2['id'] ?>')">Add Section </button>&nbsp;
                      <button class="btn btn-info btn-xs" onclick="addManageSection('<?php echo $row2['id'] ?>')">Manage Section </button>&nbsp;
                      <a class="btn btn-info btn-xs" href="<?php echo base_url().'AdminController/page_details_landingpage/'.$row2['id'];?>" >View Section</a></td>  
                    <td><a class="btn btn-info btn-xs" href="<?php echo base_url().'AdminController/landingDelete/'.$row2['id'];?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a></td>   
                    <!--<td><a class="btn btn-primary" href="#" id="edit" value="<?php echo $row2->id;?>" data-material="">Modify</a>-->
                    <!--    <a class="btn btn-primary" href="<?php echo base_url().'AdminController/delete_services/'.$row2->id;?>" >Delete</a>-->
                    <!--</td>  -->

            </tr>  
                <?php }
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
        <div class="row">
          <div class="col-12">
            <div class="card">
              <?php $temptname = isset($landingDetailsData[0]['name'])?$landingDetailsData[0]['name']:''; ?>
              <div class="card-header">
                <h3 class="card-title"><b><?php echo $temptname;?></b> Landing Page Details List </h3>

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
            
                <!--<button class="btn btn-primary" onclick="addFlow(<?php echo $flow[0]['id']?> )" style="float: right;">Add Landing Page </button>-->
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th>Parameter Name</th>
                      <th>Section Name</th>
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                 // echo "<pre>";print_r($landingDetailsData);die;
                  if(isset($landingDetailsData)){
                  foreach($landingDetailsData as $row2) { ?>
                    <tr>
                    <td><?php echo $row2['parameter'];?></td>
                    <td><?php echo $row2['SectionName'];?></td>
                    <td><button class="btn btn-info btn-xs" onclick="editParameter('<?php echo $row2['parameter'] ?>','<?php echo $row2['type'] ?>','<?php echo $row2['landingPageId'] ?>','<?php echo $row2['section_id'] ?>','<?php echo $row2['id'] ?>')">Edit </button></td>  
                    <td><a class="btn btn-info btn-xs" href="<?php echo base_url().'AdminController/landingDetailsDelete/'.$row2['id'];?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a></td>   
                    <!--<td><a class="btn btn-primary" href="#" id="edit" value="<?php echo $row2->id;?>" data-material="">Modify</a>-->
                    <!--    <a class="btn btn-primary" href="<?php //echo base_url().'AdminController/delete_services/'.$row2->id;?>" >Delete</a>-->
                    <!--</td>  -->

            </tr>  
                <?php } }
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

         <!--Add Parameter Start -->
            <div class="modal fade" id="AddOMRTemplate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Landing Page</h5>
                    <button type="button" onclick="addFlowClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="<?php echo base_url("AdminController/addLandingParameter");?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Landing Page Name</label>
                              <input type="text" name="name" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Name" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                       <input type="hidden" name="id" value="<?php echo $flow[0]['id']?>">
                      </div>
                      <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Landing Page Descripation</label>
                              <input type="text" name="descripation" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Descripation" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Landing Page Path</label>
                              <input type="text" name="path" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Path" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
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
             
             <!--Add Parameter template Start -->
            <div class="modal fade" id="AddOMRTemplate1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Landing Sections</h5>
                    <button type="button" onclick="addFlowSectionClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="<?php echo base_url("AdminController/addLandingTemplateSectionPar");?>" method="post" enctype="multipart/form-data">
                      <!--  <div class="row">-->
                      <!--    <div class="col-md-12">-->
                      <!--      <div class="mb-3">-->
                      <!--        <label class="form-label" for="basic-default-fullname">Nav-Bar</label>-->
                      <!--        <input type="text" name="section[]" class="form-control" id="basic-default-fullname" required placeholder="Nav-Bar" />-->
                      <!--        <div class="render-template-msg"></div>-->
                      <!--    </div>-->
                      <!--  </div>-->
                       
                      <!--</div>-->
                      <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Section Name</label>
                              <input type="text" name="section" class="form-control" id="basic-default-fullname" required placeholder="Section Name"  />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                        <input type="hidden" name="id"  id="hiddenland" value="">
                      </div>
                      
                     
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="addFlowSectionClose()" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary btn-submit"/>
                  </div>
                </form>
                </div>
              </div>
            </div>
             <!-- Add Parameter Closed -->
              <!--Add manage section Start -->
            <div class="modal fade" id="AddOMRTemplate2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Landing Sections</h5>
                    <button type="button" onclick="addManageSectionClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="<?php echo base_url("AdminController/addLandingSectionNameDetail");?>" method="post" enctype="multipart/form-data">
                      <!--  <div class="row">-->
                      <!--    <div class="col-md-12">-->
                      <!--      <div class="mb-3">-->
                      <!--        <label class="form-label" for="basic-default-fullname">Nav-Bar</label>-->
                      <!--        <input type="text" name="section[]" class="form-control" id="basic-default-fullname" required placeholder="Nav-Bar" />-->
                      <!--        <div class="render-template-msg"></div>-->
                      <!--    </div>-->
                      <!--  </div>-->
                       
                      <!--</div>-->
                      <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname" >Section Name</label>
                              <select  id="parameter" class="form-control" name="sectionName">
                                  
                              </select>
                              
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                        <input type="hidden" name="hiddenlandsection"  id="hiddenlandsection" value="">
                      </div>
                      <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Landing Page Parameter</label>
                              <input type="text" name="parameter" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Parameter" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Landing Page Type</label>
                              <input type="text" name="values" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Type" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Landing Page Details</label>
                              <input type="text" name="details" class="form-control" id="basic-default-fullname" required placeholder="Landing Page Details" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                      </div>
                     
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="addManageSectionClose()" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Edit Landing Page</h5>
                    <button type="button" onclick="addFlowClose()" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="<?php echo base_url("AdminController/updateLandingParameter");?>" method="post" enctype="multipart/form-data">
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
                function editParameter(name,des,path,id) {
                    console.log(name);

                    let editParam = "";
                    editParam += ` <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Landing Page Name</label>
                              <input type="text" name="name" class="form-control" id="basic-default-fullname" value="${name}" required placeholder="Landing Page Name" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">Landing Page Descripation</label>
                              <input type="text" name="descripation" class="form-control" id="basic-default-fullname" value="${des}" required placeholder="Landing Page Descripation" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="mb-3">
                              <label class="form-label" for="basic-default-fullname">landing page path</label>
                              <input type="text" name="path" class="form-control" id="basic-default-fullname" value="${path}" required placeholder="landing page path" />
                              <div class="render-template-msg"></div>
                          </div>
                        </div>
                       <input type="hidden" name="nameff" value="${name}">
                       <input type="hidden" name="id" value="${id}">
                       <input type="hidden" name="descripationff" value="${des}">
                       <input type="hidden" name="pathfff" value="${path}">
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
          function addFlowSection($id){  
              $("#hiddenland").val($id);
            $("#AddOMRTemplate1").modal("show");
          }

           function addFlowSectionClose(){  
            $("#AddOMRTemplate1").modal("hide");
          }
          // function viewSection($id){
          //   alert('dgdfgdf')
          //   // window.location = <?php //echo base_url()?>'/AdminController/page_details_landingpage/' + $id;
          // }
          function addManageSection($id){  
              $("#hiddenlandsection").val($id);
                 $.ajax({
                    url : "<?php echo base_url().'AdminController/fetch_section';?>",
                    method : "POST",
                    data : {id: $id},
                    async : true,
                    dataType : 'json',
                    success: function(data){
                         
                        var htm = '';
                        htm += '<option value="">Select Section</option>';
                        if(data.length==0)
                        {
                          htm += '<option value="0">NA</option>'; 
                        }
                        var i;
                        for(i=0; i<data.length; i++){
                            htm += '<option value='+data[i].id+'>'+data[i].name+'</option>';
                        }
                        $('#parameter').html(htm);
 
                    }
                });
            $("#AddOMRTemplate2").modal("show");
          }

           function addManageSectionClose(){  
            $("#AddOMRTemplate2").modal("hide");
          }
        </script>