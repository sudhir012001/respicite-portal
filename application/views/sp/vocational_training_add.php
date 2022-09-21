<style>
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
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
            Add New Vocational Training
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="https://users.respicite.com/SpController/vocational_training"
                >Vocational Training</a
              >
            </li>
            <li class="breadcrumb-item active">Add New Vocational Training</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
      <section class="content">
        <div class="container-fluid">
         
        <!-- main content #start -->
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body box-profile">  
                    <div class="">
                      <?php 
                        if(!empty($check_inset)){
                          if($check_inset == "OK"){
                            echo "<div class='alert alert-success'>Successfully saved.</div>";
                          }
                          if($check_inset == "ERROR"){
                            echo "<div class='alert alert-danger'>Something is wrong.</div>";
                          }
                        }
                      ?>
                     </div>                      
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="vocational_name">Training Name</label>
                                <div class="text-danger"><?php echo form_error("vocational_name");?></div>
                                <input type="text" required class="form-control" id="vocational_name" name="vocational_name"  />
                            </div>

                            <div class="form-group">
                                <label for="vocational_description">Training Description</label>
                                <div class="text-danger"><?php echo form_error("vocational_description");?></div>
                                <textarea class="form-control" required id="vocational_description" name="vocational_description" rows="3"
                                    placeholder="Type here..."></textarea>
                            </div>
                            
                            <label for="">Add Training Section</label>
                            
                            <div class="p-1 border rounded">
                              <div class="m-1">                                
                                <p class="p-0 m-0 text-bold">Training Section : 1</p>
                                <div class="form-group">
                                  <label for="training_section_name">Name</label>
                                  <input type="text" required class="form-control" id="training_section_name" name="training_section_name[]" />
                                </div>
                                <div class="form-group">
                                    <label for="training_section_desc">Description</label>
                                    
                                    <textarea class="form-control" required id="training_section_desc" name="training_section_desc[]" rows="3"
                                        placeholder="Type here..."></textarea>
                                </div>
                              </div>
                              <div class="repeat-section"></div>
                              <div class="text-right"><button type="button" class="btn btn-sm btn-outline-primary btn-repeat-section">Add Section</button></div>
                            </div>
                            
                            <div class="form-group">
                              <br>
                                <label for="cluster_name mt-1">*Upload Training Content, The only zip file, and file size should be 2MB.</label>
                                  <div class="text-danger">
                                    <?php 
                                      if(!empty($imageError)){
                                        echo $imageError;
                                      }
                                    ?>
                                  </div>
                                <div class="custom-file">
                                    <input type="file" required class="custom-file-input" name="up_file" id="customFile">
                                    <label class="custom-file-label" for="customFile" >Choose file</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-8"></div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="submit" name="btn_vocational" class="btn btn-primary btn-block">
                                        Add 
                                    </button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>

                        <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        <!-- main content #end -->

        </div>
      </section>
    </div>
  </section>
</div>
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js');?>"></script>
<script>
   var count_i = 2; 
 $(".btn-repeat-section").click(function(){ 
  $(".repeat-section").append(
    `<div class="m-1 mt-2 border p-1 rounded">
      <div class="text-right"><button type="button" class="btn btn-sm btn-outline-danger btn-close-section"><i class="fas fa-times"></i></button></div>
      <p class="p-0 m-0 text-bold">Training Section : ${count_i}</p>
      <div class="form-group">
        <label for="add_name_${count_i}">Name</label>
        <input required type="text" class="form-control" id="add_name_${count_i}" name="training_section_name[]" />
      </div>
      <div class="form-group">
          <label for="add_desc_${count_i}">Description</label>
          <textarea required class="form-control" id="add_desc_${count_i}" name="training_section_desc[]" rows="3"
              placeholder="Type here..."></textarea>
      </div>     
    </div>`);
    count_i++;
 })
  
  $(document).on("click",".btn-close-section",function(){
    $(this).parent().parent().remove();
  })
</script>