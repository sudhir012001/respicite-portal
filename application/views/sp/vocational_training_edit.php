<style>
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }
</style>
<?php
       if(!empty($vocational_info)){
        $check_status = $vocational_info[0]['training_info'];
          if($check_status->training_status == "approval_pending"){
            echo "<script>alert('Training status : Approval Pending'); window.location = '".base_url('SpController/vocational_training')."';</script>"; 
          } 
       }
?>
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
            Vocational Training Edit
          </h1>
        </div>
        
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="https://users.respicite.com/SpController/vocational_training"
                >Vocational Training</a
              >
            </li>
            <li class="breadcrumb-item active">Vocational Training Edit</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
        <!-- Eidt Vocational Training #start -->
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Eidt Vocational Training</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-3"></div>
              <div class="col-md-6">
              <div class="m-2 border shadow rounded">
                      <div class="card-header">
                        <h3 class="card-title">Eidt Vocational Training</h3>
                      </div>
                      <div class="card-body">
                        <?php 
                          if(!empty($VOC_TRAI_MSG)){
                            if($VOC_TRAI_MSG == "OK"){
                              echo "<div class='alert alert-success'>Successfully Update.</div>";
                            }
                            if($VOC_TRAI_MSG == "ERROR"){
                              echo "<div class='alert alert-danger'>Something is wrong.</div>";
                            }
                          }
                        ?>
                        <?php if(!empty($vocational_info)){
                          $vocational_data = $vocational_info[0]['training_info'];
                          ?>

                          <form action="<?php echo base_url('SpController/vocational_training_edit/'.$vocational_data->id.'/VOC_TRAI');?>" method="post">
                            <div class="form-group">
                                <label for="vocational_name">Training Name</label>
                                <div class="text-danger"><?php echo form_error("vocational_name");?></div>
                                <input type="text" required class="form-control" value="<?php echo $vocational_data->training_name;?>" id="vocational_name" name="vocational_name" />
                            </div>

                            <div class="form-group">
                                <label for="vocational_description">Training Description</label>
                                <div class="text-danger"><?php echo form_error("vocational_description");?></div>
                                <textarea class="form-control" required id="vocational_description" name="vocational_description" rows="3"
                                    placeholder="Type here..."><?php echo $vocational_data->training_desc;?></textarea>
                            </div>                  
                            <div class="d-flex justify-content-end"><button type="submit" name="btn_vocational" class="btn btn-primary btn-block w-50">Edit</button></div>
                            
                          </form>
                        <?php } ?>
                      </div>
                </div>
          
              </div>
              </div>
            </div>
        </div>
        <!-- Eidt Vocational Training #End -->

        <!-- All Sub Sections #start -->
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">All Sub Sections</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
                <?php 
                  if(!empty($SUB_SECTION_MSG)){
                    if($SUB_SECTION_MSG == "OK"){
                      echo "<div class='alert alert-success'>Successfully Update.</div>";
                    }
                    if($SUB_SECTION_MSG == "ERROR"){
                      echo "<div class='alert alert-danger'>Something is wrong.</div>";
                    }
                  }
                ?>
            <div class="row">
              <div class="col-md-12">
                <?php 
                  if(!empty($ADD_SUB_SECTION_MSG)){
                    if($ADD_SUB_SECTION_MSG == "OK"){
                      echo "<div class='alert alert-success'>Successfully saved.</div>";
                    }
                    if($ADD_SUB_SECTION_MSG == "ERROR"){
                      echo "<div class='alert alert-danger'>Something is wrong.</div>";
                    }
                  }
                ?>
              </div>
              <?php if(!empty($vocational_info)){
                  $count_i = 1;
                  $section_data = $vocational_info[1]['section_info'];
                  foreach($section_data as $v){ ?>
                  <div class="col-md-6">
                        <div class="card m-2 border shadow rounded">
                          <div class="card-header">
                            <h3 class="card-title">Section <?php echo $count_i?></h3>
                          </div>
                          <div class="card-body">
                          <form action="<?php echo base_url('SpController/vocational_training_edit/'.$v->training_id.'/SUB_SECTION?sno='.$v->id);?>" method="post">
                            <div class="form-group">
                              <label for="sub_section_name_<?php echo $count_i?>">Section Name</label>
                              <div class="text-danger"><?php echo form_error("sub_section_name");?></div>
                              <input type="text" required class="form-control" value="<?php echo $v->section_name;?>" id="sub_section_name_<?php echo $count_i?>" name="sub_section_name" />
                            </div>

                            <div class="form-group">
                                <label for="sub_section_description_<?php echo $count_i?>">Section Description</label>
                                <div class="text-danger"><?php echo form_error("sub_section_description");?></div>
                                <textarea class="form-control" required id="sub_section_description_<?php echo $count_i?>" name="sub_section_description" rows="3"
                                    placeholder="Type here..."><?php echo $v->section_desc;?></textarea>
                            </div>
                            <div class="d-flex justify-content-center">
                              <button type="submit" name="btn_sub_section" class="btn btn-primary btn-block w-50 m-1">Edit</button>
                              <button type="button" data-section-id="<?php echo $v->id;?>"  class="btn btn-secondary btn-block w-50 m-1 btn-delete">Delete</button>
                            </div>
                          </form> 
                          </div>
                        </div>
                      <!-- card #end  -->
                  </div>

                  <?php $count_i++; } }?>   
 
                  <form action="<?php echo base_url('SpController/vocational_training_edit/'.$vocational_data->id.'/ADD_SUB_SECTION');?>" method="post" class="col-md-12 row repeat-section"  id="form_add_more">                  
                    
                  </form>
                  </div>
                  <div class="d-flex mt-5 align-items-center justify-content-center">
                    <button type="submit" name="btn_add_more_sectoin" form="form_add_more" class="btn btn-outline-primary m-2">Submit</button>
                    <button type="button" class="btn btn-outline-secondary btn-repeat-section m-2">ADD More</button>
                  </div>
                </div>
        </div>
        <!-- All Sub Sections #end -->

        <!-- Update Training Content file #start  -->
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Update Training Content file</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
                    <div class="row">
                      <div class="col-3"></div>
                      <div class="col-md-6"> 
                        <div class="m-2 border shadow rounded">
                            <div class="card-header">
                              <h3 class="card-title">Update Training Content file</h3>
                            </div>
                                  
                        <div class="card-body">
                        <?php if(!empty($vocational_info)){
                          $vocational_data = $vocational_info[0]['training_info'];
                          $file_mine = $vocational_data->file_mine;
                          $file_name = $vocational_data->training_file_loc;
                          $file_content = base_url("uploads/vocational_training/$vocational_data->training_file_loc");
                          if($file_mine == "png" || $file_mine == "jpg" || $file_mine == "jpeg" || $file_mine == "gif"){
                            echo "<img class='w-100' src='$file_content' alt=''>";
                          }else{
                            echo "<a href='$file_content' target='_black'>Click here to Download.</a>";
                          }
                        }?>
                        <br>
                        <br>
                        <?php 
                          if(!empty($CONTENT_UPLOAD_MSG)){
                            if($CONTENT_UPLOAD_MSG == "OK"){
                              echo "<div class='alert alert-success'>Successfully Update.</div>";
                            }
                            if($CONTENT_UPLOAD_MSG == "ERROR"){
                              echo "<div class='alert alert-danger'>Something is wrong.</div>";
                            }
                          }

                          if(!empty($imageError)){
                            echo "<div class='alert alert-danger'>$imageError</div>";
                          }
                        ?>
                        <form action="<?php echo base_url('SpController/vocational_training_edit/'.$vocational_data->id.'/CONTENT_UPLOAD');?>" method="post" enctype="multipart/form-data">        
                            <div class="form-group">
                              <label for="exampleInputFile">Upload Training Content, The only zip file, and file size should be 2MB.</label>
                              <div class="input-group">
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" name="content_file" id="content_file">
                                  <label class="custom-file-label" for="content_file">Choose file </label>
                                </div>
                                <div class="input-group-append">
                                  <input type="hidden" value='<?php echo $file_name;?>' name="old_file_name">
                                  <button type="submit" name="btn_content" id="btn_content" class="btn btn-primary btn-block">Upload</button>
                                </div>
                              </div>
                            </div>
                        </form>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
        </div>
        <!-- Update Training Content file #end  -->        
    </div> <!--main row #end-->
  </div>
</section>
</div>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<script>
 var count_i = <?php echo (empty($count_i))?1:$count_i;?>; 
 $(".btn-repeat-section").click(function(){ 
  $(".repeat-section").append(
    `<div class="col-md-6">
            <div style="position: absolute;right: 0;margin-right: 29px;top: 16px;z-index: 1;">
              <button type="button" class="btn btn-sm btn-outline-danger btn-close-section">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <div class="card m-2 border shadow rounded">
              <div class="card-header">
                <h3 class="card-title">Section ${count_i}</h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="sub_section_name_${count_i}">Section Name</label>
                  <div class="text-danger"></div>
                  <input type="text" required="" class="form-control"  id="sub_section_name_${count_i}" name="add_sub_section_name[]">
                </div>

                <div class="form-group">
                    <label for="sub_section_description_${count_i}">Section Description</label>
                    <div class="text-danger"></div>
                    <textarea class="form-control" required="" id="sub_section_description_${count_i}" name="add_sub_section_description[]" rows="3" placeholder="Type here..."></textarea>
                </div>
                <div class="d-flex justify-content-center">
                </div>
              </div>
            </div>
          <!-- card #end  -->
    </div>`);
    count_i++;
 })
  
  $(document).on("click",".btn-close-section",function(){
    $(this).parent().parent().remove();
  })

  const BASE_URL = '<?php echo base_url();?>';
  // --remove #start
  $(document).on("click",".btn-delete",function(){
    var section_id = $(this).data("section-id");
    var box = $(this).parent().parent().parent().parent().parent();
    if(confirm("Are you sure you want to delete?")){
      $.get(`${BASE_URL}SpController/delete_itme/${section_id}/REMOVE_SUB_SECTION`,function(res){
        let res_json = JSON.parse(res);
        if(res_json.MSG == "OK"){
          box.remove();
          alert("Successfully Delete this section");
        }
      });
    }
  })
  // --remove #end
</script>
