<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Pending Service Provider Approval List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item active">Pending List</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
      <?php 
        $msg = $this->session->flashdata('msg');
        if($msg !="")
        {
  ?>     
    <div class="alert alert-success">
            <?php echo $msg; ?>
    </div>
    <?php 
}
?>
  <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Service Provider List</h3>

                <!-- <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>-->
              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th>Full Name</th>
                      <th>Email</th>
                      <th>Mobile</th>
                      <th>Action</th>
                     
                    </tr>
                  </thead>
                  <tbody>
                    
                 <?php  
                    foreach ($h->result() as $row)  
                {  
                  $email = $row->email;
                  $who = $row->role;
                  $this->db->where('email',$email);
                  $num = $this->db->get('provider_detail_four')->num_rows();
                  if($num>0)
                  {
                      if($who=='individual')
                      {
                  ?>
                        <tr>  
                        <td><?php echo $row->fullname;?></td>  
                        <td><?php echo $row->email;?></td>  
                        <td><?php echo $row->mobile;?></td>  
                        <td><a href="<?php echo base_url().'AdminController/update_sp_approval/'.$row->id;?>" >Approve</a></td>  
                        </tr>  
                  <?php        
                      }
                      else
                      {
                        $this->db->where('email',$email);
                        $num2 = $this->db->get('provider_institute_details')->num_rows();
                        if($num2>0)
                        {

                  ?>
                        <tr>  
                        <td><?php echo $row->fullname;?></td>  
                        <td><?php echo $row->email;?></td>  
                        <td><?php echo $row->mobile;?></td>  
                        <td><a href="<?php echo base_url().'AdminController/updateapproval/'.$row->id;?>" >Approve</a>
                        </td>  
                        </tr> 
                        <?php 
                            $rows = $this->db->get('provider_institute_details');
                            foreach($rows->result() as $rows)
                            {
                        ?>
                                <tr><td colspan='4'>
                                <b>Institute/Group Details</b><br>
                                </td></tr>
                                <tr><td colspan="2">Institute Name</td>
                                <td colspan="2"><?php echo $rows->inst_name; ?></td>
                                </tr>
                                <tr><td colspan="2">Domain of Work</td>
                                <td colspan="2"><?php echo $rows->domain_of_work; ?></td>
                                </tr>
                                <tr><td colspan="2">City</td>
                                <td colspan="2"><?php echo $rows->city; ?></td>
                                </tr>
                                <tr><td colspan="2">State</td>
                                <td colspan="2"><?php echo $rows->state; ?></td>
                                </tr>
                                <tr><td colspan="2">Country</td>
                                <td colspan="2"><?php echo $rows->country; ?></td>
                                </tr>
                        <?php        
                            }
                        ?>
                        <tr><td colspan="4"><b>Level Details</b></td></tr>
                       <?php
                        $rows2 = $this->db->get('provider_detail_first');
                            foreach($rows2->result() as $rows2)
                            {
                              $l1_code = $rows2->l1;
                              $this->db->where('id',$l1_code);
                              $l1 = $this->db->get('provider_level_one');
                              foreach($l1->result() as $l1){
                      ?>
                      <tr><td colspan="2"><b>Selected Level 1</b></td>
                      <td colspan="2"><?php echo $l1->l1; ?></td></tr>
                      <?php          

                              }
                        }
                       ?>
                       <?php
                        $rows2 = $this->db->get('provider_detail_sec');
                            foreach($rows2->result() as $rows2)
                            {
                              $l1_code = $rows2->l2;
                              $this->db->where('id',$l1_code);
                              $l1 = $this->db->get('provider_level_two');
                              foreach($l1->result() as $l1){
                      ?>
                      <tr><td colspan="2"><b>Selected Level 2</b></td>
                      <td colspan="2"><?php echo $l1->l2; ?></td></tr>
                      <?php          

                              }
                        }
                       ?>
                       <?php
                        $rows2 = $this->db->get('provider_detail_three');
                            foreach($rows2->result() as $rows2)
                            {
                              $l1_code = $rows2->l3;
                              $this->db->where('id',$l1_code);
                              $l1 = $this->db->get('provider_level_three');
                              foreach($l1->result() as $l1){
                      ?>
                      <tr><td colspan="2"><b>Selected Level 3</b></td>
                      <td colspan="2"><?php echo $l1->l3; ?></td></tr>
                      <?php          

                              }
                        }
                       ?>
                       <tr><td colspan="4"><b>Level 4 Details</b></td></tr>
                       <?php 
                        }
                      }
                      $this->db->where('email',$email); 
                      $l4 = $this->db->get('provider_detail_three');
                      foreach($l4->result() as $l4)
                      {
                        $l3_id = $l4->l3;
                        $this->db->where('l3_id',$l3_id);
                        $l5 = $this->db->get('provider_level_four');
                        foreach($l5->result() as $l5)
                        {
                      ?>
                            <tr>
                            <th><?php echo $l5->param_one; ?></th>
                            <th><?php echo $l5->param_two; ?></th>
                            <th><?php echo $l5->param_three; ?></th>
                            <th><?php echo $l5->param_four; ?></th>
                          
                          </tr>

                      <?php
                          
                        }
                        $this->db->where('email',$email); 
                        $l6 = $this->db->get('provider_detail_four'); 
                        foreach($l6->result() as $l6)
                        {
                      ?>
                            <tr>
                            <td><?php echo $l6->p1; ?></td>
                            <td><?php echo $l6->p2; ?></td>
                            <td><?php echo $l6->p3; ?></td>
                            <td><?php echo $l6->p4; ?></td>
                          
                          </tr>

                      <?php
                          
                        }
                      }
                  }
                }  
            ?>  
                      
                      
                  
                    
                    
                    
                  </tbody>
                </table>
                
              </div>
             
              
             
</form>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>