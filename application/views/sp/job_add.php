<style>
  .hover-effect:hover{
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
  }
  .job-loca{
    display:none;
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
            Add New Job
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="<?php echo base_url();?>SpController/posts_job"
                >Posts Job</a
              >
            </li>
            <li class="breadcrumb-item active">Add New Job</li>
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
                                        
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="job_title">Job Title</label>
                                <div class="text-danger"><?php echo form_error("job_title");?></div>
                                <input type="text"  required class="form-control" id="job_title" name="job_title" />
                            </div>

                            <div class="form-group">
                                <label for="salary">CTC</label>
                                <!-- <div class="text-danger"><?php //echo form_error("salary");?></div> -->
                                <input value="Not Applicable" type="text" placeholder="Enter CTC in INR."  class="form-control" id="salary" name="salary" />
                            </div>

                            <div class="form-group border rounded p-2 border rounded p-2">
                                <label>Job Type</label>
                                <div class="text-danger"><?php echo form_error("job_types");?></div>
                                <div class="custom-control custom-radio d-inline mr-2">
                                  <input class="custom-control-input" type="radio" value="office" id="job_type_office" name="job_types">
                                  <label for="job_type_office" class="custom-control-label">Office</label>
                                </div>
                                <div class="custom-control custom-radio d-inline mr-2">
                                  <input class="custom-control-input" type="radio" value="remote" id="job_type_remote" name="job_types">
                                  <label for="job_type_remote" class="custom-control-label">Remote</label>
                                </div>
                                <div class="custom-control custom-radio d-inline">
                                  <input class="custom-control-input" type="radio" value="hybrid" id="job_type_hybrid" name="job_types">
                                  <label for="job_type_hybrid" class="custom-control-label">Hybrid</label>
                                </div>
                            </div>

                            <div class="form-group border rounded p-2 job-loca">
                                <label for="job_locations">Job Locations</label>
                                <input type="text" name="job_locations" class="form-control" id="job_locations">
                                <!-- <div class="text-danger"><?php //echo form_error("job_locations");?></div>
                                <div class="custom-control custom-radio d-inline mr-2">
                                  <input class="custom-control-input" type="radio" value="in_office" id="job_locations_office" name="job_locations">
                                  <label for="job_locations_office" class="custom-control-label">In case of Office</label>
                                </div>
                                <div class="custom-control custom-radio d-inline">
                                  <input class="custom-control-input" type="radio"  value="hybrid" id="job_locations_hybrid" name="job_locations">
                                  <label for="job_locations_hybrid" class="custom-control-label">Hybrid</label>
                                </div> -->
                            </div>

                            <div class="form-group border rounded p-2">
                                <label>Posting Nature</label>
                                <div class="text-danger"><?php echo form_error("posting_nature");?></div>
                                <div class="custom-control custom-radio d-inline mr-2">
                                  <input class="custom-control-input" type="radio" value="company"  id="posting_nature_company" name="posting_nature">
                                  <label for="posting_nature_company" class="custom-control-label">Company</label>
                                </div>
                                <div class="custom-control custom-radio d-inline">
                                  <input class="custom-control-input" type="radio" value="agency" id="posting_nature_agency" name="posting_nature">
                                  <label for="posting_nature_agency" class="custom-control-label">Agency</label>
                                </div>
                            </div>

                            <div class="form-group border rounded p-2">
                                <label>Job Nature</label>
                                <div class="text-danger"><?php echo form_error("job_nature");?></div>
                                <div class="custom-control custom-radio d-inline mr-2">
                                  <input class="custom-control-input" type="radio" value="full_time"  id="full_time" name="job_nature">
                                  <label for="full_time" class="custom-control-label">Full Time</label>
                                </div>
                                <div class="custom-control custom-radio d-inline">
                                  <input class="custom-control-input" type="radio" value="part_time" id="part_time" name="job_nature">
                                  <label for="part_time" class="custom-control-label">Part Time</label>
                                </div>
                                <div class="custom-control custom-radio d-inline">
                                  <input class="custom-control-input" type="radio" value="project_based" id="project_based" name="job_nature">
                                  <label for="project_based" class="custom-control-label">Project Based</label>
                                </div>
                            </div>

                            <div class="form-group border rounded p-2">
                              <label>Domain</label>
                              <div class="text-danger"><?php echo form_error("domain");?></div>
                              <?php if($job_domain){
                                echo '<select id="select_domain" class="form-control" required name="domain">';
                                echo "<option value=''>--Choose Option --</option>";
                                foreach($job_domain as $v){
                                  echo "<option value='$v->id'>$v->name</option>";
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
                                    placeholder="Type here..."></textarea>
                            </div>


                            <div class="row">
                                <div class="col-8"></div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="submit" name="btn_job" class="btn btn-primary btn-block">
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

  $("[name=job_types]").click(function(){
   let remote =  $(this).val();
   let job_loc = $(".job-loca");
    if(remote == "remote"){
      job_loc.hide();
    }else{
      job_loc.show();
    }
  });
</script>
