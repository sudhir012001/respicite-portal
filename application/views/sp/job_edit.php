<style>
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }
  .job-loca{
    display:none;
  }
</style>
<?php if(empty($short_data)){
    show_404();
} ?>
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
            Edit Job
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="<?php echo base_url();?>SpController/posts_job"
                >Posts Job</a
              >
            </li>
            <li class="breadcrumb-item active">Edit Job</li>
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
                            echo "<div class='alert alert-success'>Successfully Update.</div>";
                          }
                          if($check_inset == "ERROR"){
                            echo "<div class='alert alert-danger'>Something is wrong.</div>";
                          }
                        }
                      ?>
                     </div> 
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="job_title">Job Title</label>
                                <div class="text-danger"><?php echo form_error("job_title");?></div>
                                <input type="text"  value="<?php echo $short_data->job_title;?>" required class="form-control" id="job_title" name="job_title" />
                            </div>

                            <div class="form-group">
                                <label for="salary">CTC</label>
                                <!-- <div class="text-danger"><?php //echo form_error("salary");?></div> -->
                                <input type="text" value="<?php echo $short_data->salary;?>" placeholder="Enter salary in INR." required class="form-control" id="salary" name="salary" />
                            </div>

                            <div class="form-group border rounded p-2 border rounded p-2">
                                <label>Job Type</label>
                                <div class="text-danger"><?php echo form_error("job_types");?></div>
                                <div class="custom-control custom-radio d-inline mr-2">
                                  <input class="custom-control-input" type="radio" value="office" id="job_type_office" <?php echo ($short_data->job_type == "office")?'checked':'';?> name="job_types">
                                  <label for="job_type_office" class="custom-control-label">Office</label>
                                </div>
                                <div class="custom-control custom-radio d-inline mr-2">
                                  <input class="custom-control-input" type="radio" value="remote" id="job_type_remote" <?php echo ($short_data->job_type == "remote")?'checked':'';?> name="job_types">
                                  <label for="job_type_remote" class="custom-control-label">Remote</label>
                                </div>
                                <div class="custom-control custom-radio d-inline">
                                  <input class="custom-control-input" type="radio" value="hybrid" id="job_type_hybrid" <?php echo ($short_data->job_type == "hybrid")?'checked':'';?> name="job_types">
                                  <label for="job_type_hybrid" class="custom-control-label">Hybrid</label>
                                </div>
                            </div>

                            <div class="form-group border rounded p-2 job-loca">                                
                              <label for="job_locations">Job Locations</label>
                              <input type="text" name="job_locations" class="form-control" id="job_locations" value="<?php echo $short_data->job_locations?>">
                                <!-- <div class="text-danger"><?php //echo form_error("job_locations");?></div>
                                <div class="custom-control custom-radio d-inline mr-2">
                                  <input class="custom-control-input" type="radio" value="in_office" id="job_locations_office" <?php //echo ($short_data->job_locations == "in_office")?'checked':'';?> name="job_locations">
                                  <label for="job_locations_office" class="custom-control-label">In case of Office</label>
                                </div>
                                <div class="custom-control custom-radio d-inline">
                                  <input class="custom-control-input" type="radio"  value="hybrid" id="job_locations_hybrid" <?php //echo ($short_data->job_locations == "hybrid")?'checked':'';?> name="job_locations">
                                  <label for="job_locations_hybrid" class="custom-control-label">Hybrid</label>
                                </div> -->
                            </div>

                            <div class="form-group border rounded p-2">
                                <label>Posting Nature</label>
                                <div class="text-danger"><?php echo form_error("posting_nature");?></div>
                                <div class="custom-control custom-radio d-inline mr-2">
                                  <input class="custom-control-input" <?php echo ($short_data->posting_nature == "company")?'checked':'';?> type="radio" value="company"  id="posting_nature_company"  name="posting_nature">
                                  <label for="posting_nature_company" class="custom-control-label">Company</label>
                                </div>
                                <div class="custom-control custom-radio d-inline">
                                  <input class="custom-control-input" type="radio" value="agency" id="posting_nature_agency" <?php echo ($short_data->posting_nature == "agency")?'checked':''; ?> name="posting_nature">
                                  <label for="posting_nature_agency" class="custom-control-label">Agency</label>
                                </div>
                            </div>

                            <div class="form-group border rounded p-2">
                              <label>Domain</label>
                              <div class="text-danger"><?php echo form_error("domain");?></div>
                              <?php if($job_domain){
                                echo '<select id="select_domain" class="form-control" required name="domain">';
                                echo "<option value=''>--Choose Option --</option>";
                                foreach($job_domain as $v){
                                  ?>
                                  <option value='<?php echo $v->id;?>' <?php echo ($short_data->domain == $v->id)?"selected":"";?> ><?php echo $v->name;?></option>
                                  <?php
                                }
                                echo "<option value='add_new_domain'>Add New</option>";
                                echo "</select>";
                              }else{
                                echo "<select id='select_domain' class='form-control' required name='domain'>
                                      <option value=''>--Choose Option --</option>
                                      <option value='add_new_domain'>Add New</option>
                                      </select>";
                              }?>
                              <div class="mt-2 add-new-box" style="display:none;">
                                <input type="text"   placeholder="*Type here New Domain."   class="form-control"  name="add_new_domain" />
                              </div>
                            </div>

                            <div class="form-group border rounded p-2">
                              <label>Specialization</label>

                              <select class="form-control specialization-select"  name="specialization[]" multiple>
                                <option value=''>----Choose Multiple Option --</option>
                                <?php 
                                if(!empty($short_data->specialization)){
                                  $ex_v = explode(",",$short_data->specialization);
                                    $ex_count = sizeof($ex_v);
                                    $i = 0;
                                  foreach($job_specialization as $v1){
                                    if($ex_v[$i] == $v1->id){
                                      ?>
                                    <option value='<?php echo $v1->id;?>' selected ><?php echo $v1->name;?></option>
                                    <?php
                                     $i++;
                                  }else{
                                    ?>
                                      <option value='<?php echo $v1->id;?>'><?php echo $v1->name;?></option>
                                      <?php
                                    }
                                   
                                  }
                                }?>
                              </select>
                              <div class="mt-2 add-new-box" style="display:none;">
                                <label>*Add Specialization with comma separated.</label>
                                <input type="text"  placeholder="abc,xyz,etc"  class="form-control"  name="add_new_specialization" />
                               </div>
                            </div>

                            <div class="form-group">
                                <label for="job_description">Job Description</label>
                                <div class="text-danger"><?php echo form_error("job_description");?></div>
                                <textarea class="form-control" required id="job_description" name="job_description" rows="3"
                                    placeholder="Type here..."><?php echo $short_data->job_description;?></textarea>
                            </div>


                            <div class="row">
                                <div class="col-8"></div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="submit" name="btn_job_edit" class="btn btn-primary btn-block">
                                        Save
                                    </button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
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
  const BASE_URL = "<?php echo base_url();?>";
  $("#select_domain").change(function(){
    let this_elem =  $(this);
      $(".add-new-box").hide();
      $(".specialization-select").show();
      if(this_elem.val() == "add_new_domain"){
        $(".add-new-box").show();
        $(".specialization-select").hide();
      }

      if(this_elem.val() != "" && this_elem.val() != "add_new_domain"){
       $.get(`${BASE_URL}/SpController/ajax_work/${this_elem.val()}/SPECIALIZATION`,function(res){
        let op = $(".specialization-select");
        let json_res = JSON.parse(res);
        if(json_res.MSG == "OK"){
          op.html(null);
          $.each(json_res.data,function(k,v){
            op.append(`<option value="${v.id}">${v.name}</option>`);
          });
        }
       });
      }
  });

  let job_type = $("[name=job_types]:checked");
  let job_loc = $(".job-loca");
  if(job_type.val() == "remote"){
    job_loc.hide();
  }else{
    job_loc.show();
  }

  $("[name=job_types]").click(function(){
   let remote =  $(this).val();   
    if(remote == "remote"){
      job_loc.hide();
    }else{
      job_loc.show();
    }
  });
</script>
