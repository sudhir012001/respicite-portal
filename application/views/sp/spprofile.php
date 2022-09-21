<?php 
  $user = $this->session->userdata('user');
  $email = $user['email'];
  $this->db->where('email',$email);
  $record = $this->db->get('sp_profile_detail');
  foreach($record->result() as $record)
  {
    $about_us = $record->about_us;
    $key_services = $record->key_services;
    $address = $record->address;
    $mobo = $record->contact;
    $fb = $record->fb_url;
    $twt = $record->twitter_url;
    $insta = $record->insta_url;
    $linke = $record->linkedin_url;
    $heading1 = $record->heading1;
    $content1 = $record->content1;
    $heading2 = $record->heading2;
    $content2 = $record->content2;
    $heading3 = $record->heading3;
    $content3 = $record->content3;
  }
?>
<div id="modalHomeEvents" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;">
        <label>Contact Details</label>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <form action="<?php echo base_url('SpController/contact'); ?>" enctype="multipart/form-data" method="post">
                          <div class="input-group mb-3">
                            
                            <input type="email" class="form-control <?php echo (form_error('email')!="") ? 'is-invalid' : ''; ?>" name="email" value="<?php echo $email; ?>" placeholder="Email" disabled>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('email')); ?></p>
                        </div>
                        <div class="form-group">
                        
                            <textarea class="form-control" name="addr" value="<?php echo set_value('addr'); ?>" rows="2" placeholder="Enter Address..."><?php echo $address; ?></textarea>
                        </div>
                        <div class="input-group mb-3">
                            <input type="tel" class="form-control <?php echo (form_error('mobile')!="") ? 'is-invalid' : ''; ?>" name="mobile" value="<?php echo $mobo; ?>" placeholder="Mobile No.">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                                </div>
                            </div>
                            <p class="invalid-feedback"><?php echo strip_tags(form_error('mobile')); ?></p>
                        </div>    
        
            
        </div>
        <div class="modal-footer">
          <button type="submit" name="savebtn" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>

    </div>
</div>

  <div id="modalfbEvents" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;">
        <label>Link</label>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <form action="<?php echo base_url('SpController/fb_link'); ?>"  method="post">
                          <div class="input-group mb-3">
                            
                          <input type="text" class="form-control <?php echo (form_error('fb_link')!="") ? 'is-invalid' : ''; ?>" name="fb_link" id ="fb_link" value="<?php echo $fb; ?>" placeholder="Facebook Link">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        
        </div>
        <div class="modal-footer">
          <button type="submit" name="savebtn" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>

    </div>
  </div>

  <div id="modaltwitterEvents" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;">
        <label>Link</label>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <form action="<?php echo base_url('SpController/tw_link'); ?>"  method="post">
                          <div class="input-group mb-3">
                            
                          <input type="text" class="form-control <?php echo (form_error('tw_link')!="") ? 'is-invalid' : ''; ?>" name="tw_link" id ="tw_link" value="<?php echo $twt; ?>" placeholder="Twitter Link">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
     
        </div>
        <div class="modal-footer">
          <button type="submit" name="savebtn" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>

    </div>
  </div>
  <div id="modalinstaEvents" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;">
        <label>Link</label>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <form action="<?php echo base_url('SpController/insta_link'); ?>"  method="post">
                          <div class="input-group mb-3">
                            
                          <input type="text" class="form-control <?php echo (form_error('insta_link')!="") ? 'is-invalid' : ''; ?>" name="insta_link" id ="insta_link" value="<?php echo $insta; ?>" placeholder="Instagram Link">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
     
        </div>
        <div class="modal-footer">
          <button type="submit" name="savebtn" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>

    </div>
  </div>

  <div id="modallinkeEvents" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;">
        <label>Link</label>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <form action="<?php echo base_url('SpController/linke_link'); ?>"  method="post">
                          <div class="input-group mb-3">
                            
                          <input type="text" class="form-control <?php echo (form_error('insta_link')!="") ? 'is-invalid' : ''; ?>" name="linke_link" id ="linke_link" value="<?php echo $linke; ?>" placeholder="Linkedin Link">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
     
        </div>
        <div class="modal-footer">
          <button type="submit" name="savebtn" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>

    </div>
  </div>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Service Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url('SpController/dashboard');?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Your Service</li>
            </ol>
          </div>
          
        </div>
        <?php 
                    $msg = $this->session->flashdata('msg');
                    if($msg !="")
                    {
                    ?>     
                    <div class="alert alert-danger">
                        <?php echo $msg; ?>
                    </div>
                    <?php 
                    }
                     $msg2 = $this->session->flashdata('msg2');
                    if($msg2 !="")
                    {
                    ?>
                    <div class="alert alert-success">
                    <?php echo $msg2; ?>
                    </div>
                    <?php 
                    }
                    ?> 
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-sm-6">
              <!-- <h3 class="d-inline-block d-sm-none">bbcbcxbcbcxbcxbx</h3> -->
              <div class="col-12">
                <img src="<?php echo base_url().$user['profile_photo']; ?>" class="product-image" alt="Profile Image">
              </div>
              <br>
              <div class="col-12 ">
              <form action="<?php echo base_url('SpController/do_upload'); ?>" enctype="multipart/form-data" method="post">  
              <div class="form-group">
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img" id="img">
                        <label class="custom-file-label" for="img">Choose file 500*500px. </label>
                      </div>
                      <div class="input-group-append">
                      <button type="submit" name="uploadbtn" id="uploadbtn" class="btn btn-primary btn-block">Upload</button>
                      </div>
                    </div>
                  </div>
                  
</form>
             </div>
            </div>
            <div class="col-12 col-sm-6">
              <form action="<?php echo base_url().'SpController/sp_detail'; ?>"  method="post"> 
              <h3 class="my-3">About Us (200 words)</h3>
              <div class="form-group">
                <textarea class="form-control" rows="7" name="aboutus" id="aboutus" placeholder="Enter ..."><?php echo $about_us; ?></textarea>
              </div>
              
              <hr>
              <h4>Key Services</h4>
              <div class="form-group">
                <input type="text" class="form-control" name="key" id="key" value="<?php echo $key_services; ?>" placeholder="Enter ...">
              </div>
            

              <div class="form-group" align="right">
                
              <button type="submit" name="savebtn" class="btn btn-primary">Save</button>              
                
              </div>
              </form>
              <div class="mt-4">
              <a style="cursor: pointer;" class="open-homeEvents" data-id=" "  data-toggle="modal" data-target="#modalHomeEvents">
                <div class="btn btn-primary btn-lg btn-flat">
                  <!-- <i class="fas fa-cart-plus fa-lg mr-2"></i> -->
                  Get Contact
                </div>
              </a>
                <!-- <div class="btn btn-default btn-lg btn-flat">
                  <i class="fas fa-heart fa-lg mr-2"></i> 
                  Schedule Appointment
                </div> -->
              </div>

              <div class="mt-4 product-share">
                <a href="#" style="cursor: pointer;" class="text-gray open-homeEvents" data-id=" "  data-toggle="modal" data-target="#modalfbEvents">
                  <i class="fab fa-facebook-square fa-2x"></i>
                </a>
                <a href="#" style="cursor: pointer;" class="text-gray open-homeEvents" data-id=" "  data-toggle="modal" data-target="#modaltwitterEvents">
                  <i class="fab fa-twitter-square fa-2x"></i>
                </a>
                <!-- <a href="#" style="cursor: pointer;" class="text-gray open-homeEvents" data-id=" "  data-toggle="modal" data-target="#modaliconEvents">
                  <i class="fas fa-envelope-square fa-2x"></i>
                </a> -->
                <!-- <a href="#" style="cursor: pointer;" class="text-gray open-homeEvents" data-id=" "  data-toggle="modal" data-target="#modaliconEvents">
                  <i class="fab fa-pinterest-square fa-2x"></i>
                </a> -->
                <a href="#" style="cursor: pointer;" class="text-gray open-homeEvents" data-id=" "  data-toggle="modal" data-target="#modalinstaEvents">
                  <i class="fab fa-instagram-square fa-2x"></i>
                </a>
                <a href="#" style="cursor: pointer;" class="text-gray open-homeEvents" data-id=" "  data-toggle="modal" data-target="#modallinkeEvents">
                  <i class="fab fa-linkedin fa-2x"></i>
                </a>
              </div>

            </div>
          </div>
          <div class="row mt-4">
            <nav class="w-100">
              <div class="nav nav-tabs" id="product-tab" role="tablist">
                <!-- <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Services</a> -->
                <!-- <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Reviews</a> -->
                <a class="nav-item nav-link active" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">More About us</a>
              </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent">
              <div class="tab-pane fade" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
              <table class="table table-striped" border="1">
              <tbody>
              <form method="post" action="<?php echo base_url().'SpController/update_price'; ?>">
              <?php 
              $i = 1;
                    $email = $user['email'];
                    
                    foreach($l->result() as $row)  
                    {
                      ?> 
              <tr>
                    
                    <?php
                    echo "<td>";
                    
                      $l1 = $row->l1;
                      $this->db->where('id',$l1);
                      $l1_d = $this->db->get('provider_level_one');
                      foreach($l1_d->result() as $l1_d)
                      {
                        echo $l1_d->l1;
                        echo "->"; 
                      }
                      $l2 = $row->l2;
                     
                        $this->db->where('id',$l2);
                        $l2_d = $this->db->get('provider_level_two');
                        foreach($l2_d->result() as $l2_d)
                        {
                          echo $l2_d->l2;
                          echo "->"; 
                        }
                      
                      
                      $l3 = $row->l3_id;
                      
                        $this->db->where('id',$l3);
                        $l3_d = $this->db->get('provider_level_three');
                        foreach($l3_d->result() as $l3_d)
                        {
                          echo $l3_d->l3;
                           
                        }
                        $where = "l1='$l1' and l2='$l2' and l3_id='$l3'";
                        $this->db->where($where);
                        $para = $this->db->get('provider_level_four');
                        foreach($para->result() as $para)
                        {
                          $para1 = $para->param_one;
                          $para2 = $para->param_two;
                          $para3 = $para->param_three;
                          $para4 = $para->param_four;

                        }
                      echo "<br>";
                      echo $para1;
                      echo "(".$row->p1.")";
                      echo ", ";
                      echo $para2;
                      echo " (".$row->p2.")";
                      echo ", ";
                      echo $para3;
                      echo " (".$row->p3.")";
                      echo ", ";
                      echo $para4;
                      echo " (".$row->p4.")";
                     
                      echo "</td>"; 
                     
                    ?>
                    <td>
                        <input type="hidden" name="pid<?php echo $i; ?>" value="<?php echo $row->id; ?>">
                      <input type="text" name="price<?php echo $i; ?>" value="<?php echo $row->price; ?>" placeholder="Price">
                    </td>
                  <?php                                    
                 echo "</tr>";
                 $i++;
                  }
                  ?>  
                    
                  </tbody>
                </table>
                  <?php
                  if($i!=1)
                  {
                    echo '<button type="submit" name="UpdatePrice" class="btn btn-primary">Save</button>';
                  }
                  ?>
               
                </form>
              </div>
              <!-- <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">  </div> -->
              <div class="tab-pane fade show active" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab"> 
              <form action="<?php echo base_url('SpController/sp_more_detail'); ?>"  method="post"> 
              <div class="form-group">
              
                <input type="text" value="<?php echo $heading1; ?>" class="form-control" name="heading1" id="heading1"  placeholder="Enter Heading One...">
              </div>
              <div class="form-group">
                <textarea class="form-control" rows="3" cols="100" name="content1" id="content1" placeholder="Enter Content One..."><?php echo $content1; ?></textarea>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" value="<?php echo $heading2; ?>" name="heading2" id="heading2"  placeholder="Enter Heading Two...">
              </div>
              <div class="form-group">
                <textarea class="form-control" rows="3" cols="100" name="content2" id="content2" placeholder="Enter Content Two..."><?php echo $content2; ?></textarea>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" value="<?php echo $heading3; ?>" name="heading3" id="heading3"  placeholder="Enter Heading Three...">
              </div>
              <div class="form-group">
                <textarea class="form-control" rows="3" cols="100" name="content3" id="content3" placeholder="Enter Content Three..."><?php echo $content3; ?></textarea>
              </div>
              <button type="submit" name="savebtn" class="btn btn-primary">Save</button>
</form>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
 
 