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
            Vocational Training Details
          </h1>
        </div>
        
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right pt-1">
            <li class="breadcrumb-item">
              <a href="https://users.respicite.com/SpController/vocational_training"
                >Vocational Training</a
              >
            </li>
            <li class="breadcrumb-item active">Vocational Training Details</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
        <!--  Vocational Training #start -->
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Vocational Training</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <?php if(!empty($vocational_info)){
                  $vocational_data = $vocational_info[0]['training_info'];
                  ?>
                  
                    <p class="mb-0"><b>Training Name</b></p>
                    <p><?php echo $vocational_data->training_name;?></p>
                    <p class="mb-0"><b>Training Description</b></p>
                    <p><?php echo $vocational_data->training_desc;?></p>
                                
                <?php } ?>
                <!-- <div class="m-2 p-2 border shadow rounded">
                </div> -->
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
            <div class="row">              
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
                              <p class="mb-0"><b>Section Name : </b></p>
                              <p><?php echo $v->section_name;?></p>
                              <p class="mb-0"><b>Section Description : </b></p>
                              <p><?php echo $v->section_desc;?></p>
                            </div>
                          </div>
                        <!-- card #end  -->
                    </div>
                    <?php $count_i++; } }?> 
                  </div>
                </div>
        </div>
        <!-- All Sub Sections #end -->

        <!-- Update Training Content file #start  -->
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Training Content file</h3>
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
                              <h3 class="card-title">Training Content file</h3>
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
                      </div>
                    </div>
                  </div>
              </div>
          </div>
        </div>
        <!-- Training Content file #end  -->  
        
        <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Remarks</h3>
              <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                  </button>
              </div>
          </div>
          <div class="card-body">           
            <div class="row">
              <?php if(!empty($vocational_info[2]['remark'])){
                $remark = $vocational_info[2]['remark'];
                ?>
                  <div class="col-md-12">
                    <div class="m-2">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <td>Remark</td>
                            <td>Date</td>
                            <td>Status</td>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach($remark as $v){
                            echo "<tr>";
                            echo "<td>$v->remark</td>";
                            echo "<td>".date("d-m-Y", strtotime($v->remark_date))."</td>";
                            echo "<td><p class='text-primary'>$v->types</p></td>";                           
                          }?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                <?php } ?>
             </div>
            </div>
        </div>
        <!-- Remark Training #End -->

         <!-- Certification Content file #start  -->
         <div class="card card-default collapsed-card">
          <div class="card-header">
              <h3 class="card-title">Certification Content file</h3>
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
                              <h3 class="card-title">Certification Content file</h3>
                            </div>
                                  
                        <div class="card-body">
                        <?php if(!empty($vocational_info)){
                          $vocational_data = $vocational_info[0]['training_info'];
                          if(!empty($vocational_data->certification_file_loc)){
                            $file_content = base_url("uploads/vocational_training/$vocational_data->certification_file_loc");
                            echo "<a href='$file_content' target='_black'>Click here to Download.</a>";
                          }else{
                            echo "<p>Certification request pending by SP.</P>";
                          }
                        }?>
                      </div>
                    </div>
                  </div>
              </div>
          </div>
        </div>
        <!-- Certification Content file #end  -->   

    </div> <!--main row #end-->
  </div>
</section>
</div>
