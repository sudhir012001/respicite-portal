<?php

// Query to get data of menu and submenu to show in side menu

// //for marketplace main menu
$this->db->select('*');
$q = $this->db->get('landing_page');
$landingpage= $q->result_array();

// ! Query to get data of menu and submenu to show in side menu
?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link" style="height:90px; background:white;" align="center">
    <?php
          $email=$user['email'];
          $this->db->where('email',$email);
          $row = $this->db->get('user_details');
          foreach($row->result() as $row)
          {
            $code = $row->user_id;
          }
          $where = "user_id='$code' and iam='reseller'";
          $this->db->where($where);
          $row2 = $this->db->get('user_details');
          foreach($row2->result() as $row2)
          {
            $user_id=$row2->user_id;
            $email=$row2->email;
          }
          // $where = "r_email='$email' and reseller_id='$user_id' ";
          //   $this->db->where($where);
          //   $row3 = $this->db->get('reseller_homepage');
          //   foreach($row3->result() as $row3)
          //   {
              
          //     $logo=$row3->logo;
          //   }
          $logo = "uploads/mylogo.png";
            ?>
      <img src="<?php echo base_url().$logo; ?>" alt="AdminLTE Logo"  style="height: 70px; ">
      <!-- <span class="brand-text font-weight-light"><?php echo $user_id; ?></span> -->
    </a>

    <!-- Sidebar -->
    
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url().$user['profile_photo']; ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $user['fullname']; ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         
          <li class="nav-item">
            <a href="<?php echo base_url().'UserController/dashboard'; ?>" class="nav-link">
              <i class="nav-icon far fa-image"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Assessment Codes
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url().'/UserController/purchase_code'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Codes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'/UserController/view_reseller_code'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Codes Summary</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'/UserController/unused_code'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Code Details</p>
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
                <a href="<?php echo base_url().'UserController/view_users_list'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Leads</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="<?php echo base_url().'UserController/user_buyer'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Clients</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="<?php echo base_url().'UserController/approve_user_code'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Approve Codes</p>
                </a>
              </li>
              <li class="nav-item">
                  <a href="<?php echo base_url().'payment-gateway/configure'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payment Gateway</p>
                </a>
              </li>
              </ul>

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
                    <a href="<?php echo base_url().'UserController/book_link/'?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Booking Link</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?php echo base_url().'UserController/counseling_type/'?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Add Counseling Type</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?php echo base_url().'UserController/counselingParameters/'?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Counseling Parameters</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?php echo $calendly_url;?>" class="nav-link" target="_blank">
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
                Profile Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url().'UserController/view_reseller_profile'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reseller Profile</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'/UserController/edit_reseller_profile'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Edit Profile</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="<?php echo base_url().'/UserController/edit_profile_photo'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Edit Profile Photo</p>
                </a>
              </li> -->

              <li class="nav-item">
                <a href="<?php echo base_url().'/UserController/change_password'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Change Password</p>
                </a>
              </li>
               <li class="nav-item">
                <!-- <a href="<?php //echo base_url().'shared/payments/PaymentController/paymentType'; ?>" class="nav-link"> -->
                <!-- <a href="<?php echo base_url().'select-payment-gateway'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payment Gateway</p>
                </a> -->
              </li>
              
            
            </ul>
          </li>

         

          <!-- Marketplace -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-envelope"></i>
              <p>Marketplace<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <?php foreach($mainmenu as $row) {
              
                ?>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p><?php echo $row->name; ?><i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                  <?php foreach($submenu as $sub) {
                    if($row->id == $sub->parent_id){
                      // echo "<pre>";
                      // print_r($sub);
                      // echo "</pre>";


                    ?>
                  <li class="nav-item" style="margin-left:30px;">
                  <a href="<?php echo base_url('UserController/marketplace_'.strtolower($row->name).'_'.strtolower($sub->name)); ?>" class="nav-link">
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

          <!-- ! Marketplace -->
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
                  <i class="nav-icon fas fa-id-badge"></i>
                  <p>Premium Landing Page</p>
                </a>
                <ul class="nav nav-treeview">
                    <?php foreach($landing as $row){ ?>
                    <li class="nav-item">
                    <a href="<?php echo base_url().'UserController/landingsPages/'.$row['id']; ?>" class="nav-link">
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
                <a href="<?php echo base_url().'UserController/domain_request'; ?>" class="nav-link">
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
                  <a href="<?php echo base_url().'UserController/page_change_logo'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Change Logo</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'/UserController/page_change_banner'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Banner Details</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'/UserController/company_detail'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Organization Details</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'/UserController/change_contact_details'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Contact Us</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'/UserController/change_social_link'; ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Social Link</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'/UserController/footer_field'; ?>" class="nav-link">
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
              
             
              
              
            
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-id-badge"></i>
              <p>
                Training & Certification
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url().'UserController/certification'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Certification</p>
                </a>
              </li>
              
            
            </ul>
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
                <a href="<?php echo base_url().'UserController/vocational_training_add'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Vocational Training</p>
                </a>
              </li>               
              <li class="nav-item">
                <a href="<?php echo base_url().'UserController/vocational_training'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Trainings</p>
                </a>
              </li>              
            </ul>
          </li>
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
                <a href="<?php echo base_url().'UserController/job_add'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add job</p>
                </a>
              </li>          
              <li class="nav-item">
                <a href="<?php echo base_url().'UserController/posts_job'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Job</p>
                </a>
              </li>              
                            
            </ul>
          </li>
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
                <a href="<?php echo base_url().'UserController/boards'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Boards</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'UserController/career_paths'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Career Paths</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="https://cgcareers.online/careerlibrary/src/" target="_blank" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Professions</p>
                </a>
              </li>
            </ul>
          </li>
         
<!--           
          //Other Services added by Sudhir on 27-Oct-2021 -->
          
    </div>
    <!-- /.sidebar -->
  </aside>
