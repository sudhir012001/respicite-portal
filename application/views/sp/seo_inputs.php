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
            SEO Inputs
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="<?php echo base_url();?>UserController/posts_job"
                >Vies Jobs</a
              >
            </li>
            <li class="breadcrumb-item active">SEO Inputs</li>
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
         <?php //print_r($_POST);?>
        <!-- main content #start -->
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body box-profile">
                      <?php 
                      $seo_save = $this->session->flashdata('seo_save');
                      if(!empty($seo_save)){ ?>
                        <div class="alert alert-success alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                           <?php echo $seo_save; ?>
                        </div>
                      <?php } ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="channel">Channel *</label>
                                <?php echo form_error("channels[]"); ?>
                                <select multiple required class="form-control" name="channels[]">
                                  <?php foreach($seo_feildes["channels"] as $v){ ?>
                                    <option 
                                      <?php  if(!empty($seo_data->channels)){
                                          foreach(explode(",",$seo_data->channels) as $c_v){
                                            if(!empty(trim($c_v)) && trim($c_v) == trim($v->name)){
                                                  echo 'selected';
                                              }
                                            }
                                          }
                                          ?>
                                          ><?php echo $v->name;?></option>;
                                  <?php }?>
                                </select>
                                <input type="hidden" name="tid" value="<?php echo (!empty($seo_data->id))?$seo_data->id:"add";?>">
                            </div>
                            <div class="form-group">
                                <label for="locations">Locations *</label>
                                <select multiple name="locations[]" id="locations" required class="form-control">
                                  <?php foreach($seo_feildes["local"] as $v){ ?>
                                  <option 
                                    <?php if(!empty($seo_data->locations)){
                                        foreach(explode(",",$seo_data->locations) as $l_v){
                                          if(!empty(trim($l_v)) && trim($l_v) == trim($v->name)){
                                              echo 'selected';
                                          }
                                        }
                                      }
                                    ?>
                                  >
                                    <?php echo trim($v->name);?>
                                  </option>
                                 <?php  }?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="available_days">Available Days *</label>
                                <select multiple required class="form-control"  name="available_days[]" id="available_days">
                                  <?php foreach($seo_feildes["days"] as $v){ ?>
                                    <option <?php if(!empty($seo_data->available_days)){
                                        foreach(explode(",",$seo_data->available_days) as $days_v){                                      
                                          if(!empty($days_v) && $days_v == $v->day_name){
                                              echo 'selected';
                                          }
                                        }
                                      }
                                      ?> >
                                    <?php echo $v->day_name;?></option>
                                  <?php }?>
                                </select>
                            </div>

                              <div class="form-group">
                                  <label for="star_rating">Star Rating *</label>
                                  <select required class="form-control" name="star_rating" id="star_rating" >                                  
                                    <?php foreach($seo_feildes["star_rating"] as $v){?>
                                      <option 
                                      <?php                              
                                        if(!empty($seo_data->star_rating) && $seo_data->star_rating == $v->rating){
                                            echo 'selected';
                                        }
                                      ?> 
                                      ><?php echo  $v->rating?></option>
                                    <?php } ?>
                                  </select>
                                  <!-- <input type="text" class="form-control"  value="<?php echo (!empty($seo_data->star_rating))?$seo_data->star_rating:"";?>"> -->
                              </div>

                              <div class="form-group">
                                  <label for="experience">Experience *</label>
                                  <input type="text" name="experience" id="experience" class="form-control"  value="<?php echo (!empty($seo_data->experience))?$seo_data->experience:"";?>">
                              </div>

                              <div class="form-group">
                                  <label for="counselling_sessions">Counselling Sessions *</label>
                                  <input type="text" name="counselling_sessions" id="counselling_sessions" class="form-control"  value="<?php echo (!empty($seo_data->counselling_sessions))?$seo_data->counselling_sessions:"";?>">
                              </div>

                              <div class="form-group">
                                <label for="top_skills">Top Skills *</label>
                                <select required multiple class="form-control" id="top_skills"  name="top_skills[]">
                                  <?php foreach($seo_feildes["top_skills"] as $v){ ?>
                                    <option 

                                    <?php if(!empty($seo_data->top_skills)){
                                        foreach(explode(",",$seo_data->top_skills) as $t_s_v){                                      
                                          if(!empty($t_s_v) && $t_s_v == $v->skill){
                                              echo 'selected';
                                          }
                                        }
                                      }
                                      ?> >
                                    <?php echo $v->skill;?></option>;
                                  <?php }?>
                                </select>
                              </div>
                              
                              <div class="form-group">
                                <label for="most_relevant_education">Most Relevant Education *</label>
                                <input type="text" id="most_relevant_education" name="most_relevant_education" class="form-control" value="<?php echo (!empty($seo_data->most_relevant_education))?$seo_data->most_relevant_education:"";?>">
                              </div>
                              

                              <div class="form-group">
                                <label for="services">Services *</label>
                                <input type="text" id="services" disabled class="form-control" value="<?php echo (!empty($seo_data->services))?$seo_data->services:"";?>">
                              </div>
                                
                            <button type="submit" name="btn_job" class="btn btn-primary btn-block">Save</button>
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
  })

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
