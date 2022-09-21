<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Pending Service Provider Approval List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
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
                        <td><a href="<?php echo base_url().'AdminController/updateapproval/'.$row->id;?>" >Approve</a></td>  
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
                        <td><a href="<?php echo base_url().'AdminController/viewsp/'.$row->id;?>" >View</a>
                        <br> <a href="<?php echo base_url().'AdminController/update_sp_approval/'.$row->id;?>" >Approve</a>
                        </td>  
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