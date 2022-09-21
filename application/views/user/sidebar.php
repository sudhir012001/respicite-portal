<?php

$is_active['trainings'] = true;
$is_active['placements'] = true;
$is_active['marketplace'] = false;

if($reseller_sp['id']==447)
{$is_active['trainings'] = $is_active['placements'] = false;}


$is_active['mot_menu'] = false;
if($user['user_id'] == 506){$is_active['mot_menu'] = true;}//MOT user
$menu['mot']='';
if($is_active['mot_menu']){$menu['mot'] = $mot_menu;}
?>
<style>
  .user_logo{background-color: white;}

  [class*=sidebar-dark-]{
    background-color:white;
  }

  [class*=sidebar-dark] .user-panel,
  [class*=sidebar-dark] .brand-link {
    border-bottom: none;
  }

  .text-black,
  [class*=sidebar-dark-] .sidebar a,
  .navbar-light .navbar-nav .nav-link,
  [class*=sidebar-dark-] .user-panel a:hover,
  .nav-pills .nav-link:not(.active):hover,
  .nav-item a,
  [class*=sidebar-dark-] .nav-sidebar>.nav-item.menu-open>.nav-link,
  .nav-sidebar .nav-link p
  {
    color:black;
  }

  .main-sidebar, .main-sidebar::before{
    width: 251px;
    border-right: 1px solid #e2e6e9;
  }

  [class*=sidebar-dark-] .nav-sidebar>.nav-item>.nav-link:focus,
  .nav-item:hover {  
    color: black;
}

.img-circlues{
  width: 2.8rem !important;
  height: 2.5rem !important;
  border-radius: 50%;
}
</style>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="#" class="brand-link user_logo p-1 text-center" style="width: 85%;">
      <?php
          $email=$user['email'];
          //echo $email;die();
          $this->db->where('email',$email);
          $row = $this->db->get('user_details');

          // echo "<pre>";
          // print_r($row->result_array());
          // echo "</pre>";
          // die;


          foreach($row->result() as $row)
          {
            $code = $row->user_id;
          }
          // $where = "user_id='$code' and iam='reseller'";
          $where = "user_id='$code' and (iam='sp' or iam='reseller')";
          // $where = "user_id='$code' and iam='user'";
          $this->db->where($where);
          $row2 = $this->db->get('user_details');
          foreach($row2->result() as $row2)
          {
            $user_id=$row2->user_id;
            $email=$row2->email;
           
          }        
            //echo $user_id.$email;
          // $where = "r_email='$email' and reseller_id='$user_id' ";          
            $this->db->where(["r_email"=>$email,"reseller_id"=>$user_id]);
            $row3 = $this->db->get('reseller_homepage');
            
            // print_r($row3->result());
            $logo='';
            foreach($row3->result() as $row3)
            {              
              $logo=$row3->logo;  
              //print_r($row3);
              
            }
            // echo $logo."<br>";
            // echo base_url();
            // die();

            //Only for MOT development
            if($is_active['mot_menu']){$logo = 'assets/devtest/default-logo.png';}
            ?>
      <img src="<?php echo base_url().$logo;  ?>" alt="Reseller Logo" style="width: 100%;max-width: 5re;object-fit: fill;height: 56px;">
      <!-- <span class="brand-text font-weight-light"><?php echo $user_id; ?></span> -->
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-2 pb-2 mb-1 d-flex">
        <div class="image">
          <img src="<?php echo base_url().$user['profile_photo']; ?>" class="img-circlues elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block text-black"><?php echo $user['fullname']; ?></a>
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
      <nav>
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
 
        
          <li class="nav-item border-bottom border-top">
            <a href="<?php echo base_url().'BaseController/dashboard'; ?>" class="nav-link">
              <i class="nav-icon fa fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item border-bottom">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-briefcase"></i>
              <p>
                Services
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url().'BaseController/request_code'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase code</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'BaseController/purchase_code_history'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase code history</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'BaseController/view_code'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Take Assessment</p>
                </a>
              </li>
              <?php $rr = explode(",",$allowed_services);
                    if(in_array("counselling", $rr)){?>
              <li class="nav-item">
                <a href="<?php echo base_url().'BaseController/counselingParameters'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Book Counseling</p>
                </a>
                
              </li>
              <?php } ?>
            <!--<li class="nav-item">-->
            <!--    <a href="<?php echo base_url().'BaseController/counselingParameters'; ?>" class="nav-link">-->
            <!--      <i class="far fa-circle nav-icon"></i>-->
            <!--      <p>Book Counseling</p>-->
            <!--    </a>-->
            <!--  </li>-->
             
            </ul>
          </li>

          <!--Query to get data of menu and submenu to show in side menu #start -->
          <?php 

              //for main menu
              // $this->db->select('*');
              // $where = "parent_id='0'";
              // $this->db->where($where);
              // $this->db->where('role','reseller');
              // $q = $this->db->get('services_marketplace_menu');
              // $mainmenu= $q->result();

              // //for submenu
              // $this->db->select('*');
              // $where = "parent_id!='0'";
              // $this->db->where($where);
              // $q = $this->db->get('services_marketplace_menu');
              // $submenu= $q->result();
          ?>
          <!--Query to get data of menu and submenu to show in side menu #end -->

          <!-- Marketplace -->
          <?php if($is_active['marketplace'])
          {
          ?>
          <li class="nav-item border-bottom">
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
                    <a href="<?php echo base_url().'BaseController/list_menu'; ?>" class="nav-link">
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

          <!-- MOT -->
          <?php
          if($is_active['mot_menu'])
          {
            ?>
            <li class="nav-item border-bottom">
              <a href="#" class="nav-link">
                <i class="nav-icon far fa-envelope"></i>
                <p>MOT<i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <?php foreach($menu['mot'] as $row) {?>
                  <?php
                    if($row->parent_id == 0 && $row->status == 'active')
                    {
                      ?>
                      <li class="nav-item">
                        <a href="<?php echo base_url().$row->controller; ?>" class="nav-link" target="_blank">
                          <i class="far fa-circle nav-icon"></i>
                          <p><?php echo $row->name; ?><i class="fas fa-angle-left right"></i></p>
                          <ul class="nav nav-treeview">
                    <?php
                    foreach($menu['mot'] as $row1)
                    {
                      
                      if($row1->parent_id == $row->id && $row1->status == 'active')
                      {
                        ?>
                        <li class="nav-item" style="margin-left:30px;">
                          <a href="<?php echo base_url().$row1->controller; ?>" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p><?php echo $row1->name ?></p>
                          </a>
                        </li>

                      <?php
                      }
                    }
                    ?>
                      </ul>
                    </a>
                  </li>
                  <?php
                }
                ?>
             <?php } ?>
            </ul>
          </li>
          <?php
        }
        ?>

                        
                    

          <!-- !MOT -->

                  
          

         
         
          <li class="nav-item border-bottom">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-edit"></i>
              <p>
                Profile
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo base_url().'BaseController/view_user_profile'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Profile</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'BaseController/edit_user_profile'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Edit Profile</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo base_url().'/BaseController/change_password'; ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Change Password</p>
                </a>
              </li>
            </ul>
          </li>
          <!--
          <li class="nav-item">
            <a href="<?php echo base_url().'SpController/view_sp'; ?>" target="_blank" class="nav-link">
              <i class="nav-icon far fa-user"></i>
              <p>
                All Service Providers
              </p>
            </a>
          </li>
          -->
          </li>


          <?php if($is_active['trainings'])
          {
            ?>
            <li class="nav-item border-bottom">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>
                  Skill Development
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo base_url().'BaseController/view_trainings';?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>View Trainings</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'BaseController/user_training';?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Training Status</p>
                  </a>
                </li>
              </ul>
            </li>
            <?php
          }
          ;?>





          
          
          <?php if($is_active['trainings'])
          {
          ?>
            <li class="nav-item border-bottom">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-briefcase"></i>
                <p>
                  Placements
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo base_url().'BaseController/all_jobs';?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>View Jobs</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url().'BaseController/apply_jobs';?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Job Status</p>
                  </a>
                </li>
              </ul>
            </li>
          <?php
          }
          ;?>




      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
