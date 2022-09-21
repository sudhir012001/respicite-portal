<?php

//Service activation
$is_active['mot'] = true;
$is_active['marketplace'] = false;
$is_active['trainings'] = true;
$is_active['placements'] = true;
$is_active['resources'] = true;

// echo "<pre>";
// print_r($user);
// echo "</pre>";
// die;

if($user['id']==447)
{$is_active['trainings'] = $is_active['placements'] = $is_active['resources'] = false;}


if($is_active['mot'])
{
  //fectch mot_menu
  
}


?>
  
  <!-- Main Sidebar Container -->
  
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="javascript:void(0)" class="brand-link" style="height: 57px;background:white;padding-top: 9px;">
    <!-- customization - wl_admin - side-bar image -->
    <img src="<?php echo base_url("uploads/1631091187.png");?>" alt="AdminLTE Logo"  style="height: 41px;width: 100%;">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-2 pb-2 mb-2 d-flex">
        <div class="image">

          <?php
            $user = $this->session->userdata('user');
            if(!empty($user['profile_photo'])){
              echo "<img src='".base_url().$user['profile_photo']."' class='img-circle elevation-2' alt='User Image'>";
            }else{
              echo "<img src='".base_url().'/assets/dist/img/user2-160x160.jpg'."' class='img-circle elevation-2' alt='User Image'>";
            }
          ?>
          
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $user['fullname']; ?></a>
        </div>
      </div>



      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
            <a href="<?php echo base_url().'SpController/dashboard'; ?>" class="nav-link">
              <i class="nav-icon far fa-image"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i><p>Service Management<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
            <?php
                  $email = $user['email'];
                  
                  $this->db->where('email',$email);
                  $num = $this->db->get('provider_institute_details')->num_rows();
                  if($num==0)
                  {
                    $where = "email='$email' and role!='individual'";
                    $this->db->where($where);
                    $num2 = $this->db->get('user_details')->num_rows();
                    if($num2>0)
                    {
              ?>
                      <li class="nav-item">
                      <a href="<?php echo base_url().'SpController/add_institution'; ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i><p>Basic information</p>
                      </a>
                    </li>
              <?php        
                    }
                }
              ?>
            
              <?php
                  
                  $this->db->where('email',$email);
                  $num = $this->db->get('provider_detail_three')->num_rows();
                  if($num==0)
                  {
              ?>
              <li class="nav-item">
              <a href="<?php echo base_url().'SpController/complete_profile'; ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Add Services</p>
              </a>
              

              </li>
              <?php      
                  }
                 
              ?>
              <?php
                  $this->db->where('email',$email);
                  $num = $this->db->get('provider_detail_three')->num_rows();
                  if($num>0)
                  {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/spprofile'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i><p>Your Services</p>
                </a>
              </li>
              <?php
                  }
              ?>
              </ul>
              </li>
              
          <?php 
          $arr_profile_mgmt =['View'=>'view_sp_profile',
                              'Edit'=>'view_sp_profile',
                              'Change Password'=>'change_password'
                            ];
          ?>  
          <!-- configuration -dynamic menu -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-envelope"></i>
              <p>Profile Management<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/view_sp_profile'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/edit_sp_profile'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Edit</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/change_password'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Change Password</p>
                </a>
              </li>
              
            </ul>
          </li>


          <!-- Marketplace -->
          <?php if($is_active['marketplace']) {
          ?>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon far fa-envelope"></i>
                <p>Marketplace<i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <?php foreach($mainmenu as $row) {?>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p><?php echo $row->name; ?><i class="fas fa-angle-left right"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <?php foreach($submenu as $sub) {
                      if($row->id == $sub->parent_id){
                      ?>
                    <li class="nav-item" style="margin-left:30px;">
                    <a href="<?php echo base_url('SPController/marketplace_'.strtolower($row->name).'_'.strtolower($sub->name)); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p><?php echo $sub->name ?></p>
                      </a>
                    </li>
                  <?php } } ?>
                  </ul>
                </li>
              <?php } ?>
              </ul>
            </li>

          <?php
          };?>

          <!-- ! Marketplace -->


          <!-- MOT - Sudhir -->


          <!-- ! MOT - Sudhir -->

          <li class="nav-item">
            <a href="<?php echo base_url().'SpController/become_partner_counselor'; ?>" class="nav-link">
              <i class="far fa-user nav-icon"></i>
              <p>Training & Certification</p>
            </a>
          </li>

          <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-id-badge"></i>
                <p>
                  Vocation Training
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
            <ul class="nav nav-treeview"> 
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/vocational_training_add'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Vocational Training</p>
                </a>
              </li>             
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/vocational_training'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Trainings</p>
                </a>
              </li>              
                           
            </ul>
          </li>

        <?php 
        $email = $user['email'];
        $where = "email='$email' and p_c='1'";
        $this->db->where($where);
        $num = $this->db->get('partner_counselor_status')->num_rows();
                if($num>0)
                {
        ?>
        <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Code Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/purchase_code'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/view_reseller_code'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/unused_code'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Details</p>
                </a>
              </li>
            </ul>
          <!-- <li class="nav-item">
            <a href="pages/kanban.html" class="nav-link">
              <i class="nav-icon fas fa-columns"></i>
              <p>
                Users
              </p>
            </a>
          </li> -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-user"></i>
              <p>
                User Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/view_users_list'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Leads</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/approve_user_code'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Approve Code</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/user_buyer'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Clients</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/ap_book_view'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Notifications</p>
                </a>
              </li>
              <li class="nav-item">
                  <a href="<?php echo base_url().'payment-gateway/configure'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payment Gateway</p>
                </a>
              </li>
            </ul>
          </li>
         
          <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon far fa-user"></i>
                  <p>Counselling<i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
              <?php $rr = explode(",",$allowed_services);
                    if(in_array("counselling", $rr))
                    {
              ?>
              
                    <li class="nav-item">
                    <a href="<?php echo base_url().'SpController/book_link/'?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Booking Link</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?php echo base_url().'SpController/counseling_type/'?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add Counseling Type</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?php echo base_url().'SpController/counselingParameters/'?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Counseling Parameters</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?php echo $booking_url;?>" class="nav-link" target="_blank">
                      <i class="far fa-circle nav-icon"></i>
                      <p>View Bookings</p>
                    </a>
                    </li>
              
              <?php } ?>
             
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-id-badge"></i>
              <p>
              Landing Page
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon far fa-circle"></i>
                  <p>Premium Landing Page</p>
                </a>
                <ul class="nav nav-treeview">
                    <?php foreach($landing as $row){ ?>
                    <li class="nav-item">
                    <a href="<?php echo base_url().'SpController/landingsPages/'.$row['id']; ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p><?php echo $row['name']; ?></p>
                    </a>
                    </li>
                    <?php } ?>
                </ul>            
              </li>
            <?php 
              $email = $user['email'];
              $this->db->where('r_email',$email);
              
              $row = $this->db->get('reseller_homepage')->num_rows();
              
              if($row=='0')
              {
              ?>
              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/domain_request'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Domain Request</p>
                </a>
              </li>
              <?php
              }
              else{
                $this->db->where('r_email',$email);
                $res = $this->db->get('reseller_homepage');
                foreach($res->result() as $res)
                {
                  $status = $res->status;
                
                }
                if($status==1)
                {
              ?>
                <li class="nav-item">
                  <a href="<?php echo base_url().'SpController/page_change_logo'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Change Logo</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'SpController/page_change_banner'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Banner Details</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'SpController/company_detail'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Organization Details</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'SpController/change_contact_details'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Contact Us</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'SpController/change_social_link'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Social Link</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'SpController/footer_field'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Day Timing</p>
                  </a>
                </li>                
              <?php    
                }
                else
                {
                  ?>
                  <li class="nav-item">
                  <p class="nav-link">Domain Approvel Panding</p>
                  </li>
                 
                <?php
                }
              }
              ?>

              <li class="nav-item">
                <a href="<?php echo base_url().'SpController/seo_inputs'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>SEO Inputs</p>
                </a>
              </li>
            
          </ul>
          </li>


          <?php if($is_active['placements'])
          {
            ?>
            <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-id-badge"></i>
                  <p>
                    Placements
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
              <ul class="nav nav-treeview">   
                <li class="nav-item">
                  <a href="<?php echo base_url().'SpController/job_add'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Add job</p>
                  </a>
                </li>          
                <li class="nav-item">
                  <a href="<?php echo base_url().'SpController/posts_job'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>View Job</p>
                  </a>
                </li>              
                              
              </ul>
            </li>
            <?php
          } 
          ;?>
          
          
          

          
          <?php if($is_active['resources']){
            ?>
            <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-id-badge"></i>
                  <p>
                  Resources
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo base_url().'SpController/boards'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Boards</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'SpController/career_paths'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Career Paths</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="https://respicite.com/documentation/careerlibrary/src/" target="_blank" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Professions</p>
                  </a>
                </li>              
              </ul>
            </li>  

            <?php
          }
          ;?>
          
          
        <?php 
                }
        ?>

          <!-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-plus-square"></i>
              <p>
                Extras
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Login & Register v1
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="pages/examples/login.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Login v1</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="pages/examples/register.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Register v1</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="pages/examples/forgot-password.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Forgot Password v1</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="pages/examples/recover-password.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Recover Password v1</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Login & Register v2
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="pages/examples/login-v2.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Login v2</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="pages/examples/register-v2.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Register v2</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="pages/examples/forgot-password-v2.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Forgot Password v2</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="pages/examples/recover-password-v2.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Recover Password v2</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="pages/examples/lockscreen.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Lockscreen</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/examples/legacy-user-menu.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Legacy User Menu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/examples/language-menu.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Language Menu</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/examples/404.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Error 404</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/examples/500.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Error 500</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/examples/pace.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pace</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/examples/blank.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Blank Page</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="starter.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Starter Page</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-search"></i>
              <p>
                Search
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/search/simple.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Simple Search</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Enhanced</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">MISCELLANEOUS</li>
          <li class="nav-item">
            <a href="iframe.html" class="nav-link">
              <i class="nav-icon fas fa-ellipsis-h"></i>
              <p>Tabbed IFrame Plugin</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="https://adminlte.io/docs/3.1/" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>Documentation</p>
            </a>
          </li>
          <li class="nav-header">MULTI LEVEL EXAMPLE</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-circle nav-icon"></i>
              <p>Level 1</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-circle"></i>
              <p>
                Level 1
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Level 2</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Level 2
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Level 3</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Level 3</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Level 3</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Level 2</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-circle nav-icon"></i>
              <p>Level 1</p>
            </a>
          </li>
          <li class="nav-header">LABELS</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-circle text-danger"></i>
              <p class="text">Important</p>
            </a>counseling_type
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-circle text-warning"></i>
              <p>Warning</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-circle text-info"></i>
              <p>Informational</p>
            </a>
          </li>
        </ul>
      </nav> -->
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
