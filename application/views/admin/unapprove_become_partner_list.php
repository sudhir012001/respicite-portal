<?php 
  $arr_1 = array('ua','Ap','Rp','St','Tc','Ca','Ac','CRp','CRf','cP','Cc','cdP');

  $arr_2 = array('UnApprove Onboarding Exam',
                'Onboarding Assessment Pending',
                'Onboarding Assessment',
                 'Training Scheduled',
                 'Training Completed',
                 'Requested For Certification',
                 'Certification Exam Pending',
                 'Certification Exam Pass',
                 'Certification Exam Failed',
                 'Download Certificate'
                 );
    $arr_3 = array('Approve','Wait...','View Report','Mark Training as Completed','Approve Certification Exam','Approve Certification Exam','','View Certificate','');
   
    
  $arr_status = array('requested for ob', 'ob completed', 'schedule trng', 'tng comp', 'ce failed','cer pass');
  $arr_action = array('approve ob exam', 'scheudle trng',
  'mark trng comp','approvie cer exam','approve ce', 'download cert')

?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Pending Become Counselor Approval List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">PC Onboarding</li>
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
                <h3 class="card-title">Counselor List</h3>

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
                      <th>Email</th>
                      <th>Group</th>
                      <th>Status</th>
                      <th>Action</th>
                     
                    </tr>
                  </thead>
                  <tbody>
                    
                 <?php  
                    foreach ($h->result() as $row)  
                {  
                 ?><tr>  
              <td><?php echo $row->email;?></td>  
              <td><?php echo $row->grp;?></td>  
              <td><?php 
                  foreach($arr_1 as $key=>$ar)
                  {
                    if($ar==$row->status)
                    {
                      $status = $row->status;
                      echo $arr_2[$key];
                      if($key==2)
                      {
                        echo  $row->exam_passed;
                      }
                     
                      $next_link = $arr_3[$key];
                      break;
                    }
                  }
             ?></td>  
             <td>
                  <?php 
                      if($status=='Rp')
                      {
                  ?>
                <!-- <a href="<?php echo base_url().'AdminController/update_partner_approval/'.$row->id;?>" ><?= $next_link; ?></a> /  -->
                <a href="<?php echo base_url().'AdminController/schedule_training/'.$row->id;?>" ><?= 'Schedule Training'; ?></a>
                  <?php
                      }
                      else if($status=='ua')
                      {
                  ?>
                  <a href="<?php echo base_url().'AdminController/update_partner_approval/'.$row->id;?>" ><?= $next_link; ?></a>
                  <?php  
                      }
                      else if($status=='St')
                      {
                  ?>
                  <a href="<?php echo base_url().'AdminController/change_status_training_completed/'.$row->id;?>" ><?= $next_link; ?></a>
                  <?php  
                      }
                      else if($status=='Tc')
                      {
                  ?>
                  
                  <?php  
                      }
                      else if($status=='Ca')
                      {
                  ?>
                 <a href="<?php echo base_url().'AdminController/approve_certification_exam/'.$row->id.'/'.$row->email.'/'.$row->grp;?>" ><?= $next_link; ?></a>
                  <?php  
                      }
                      else if($status=='CRp')
                      {
                  ?>
                      <a href="<?php echo base_url().'AdminController/view_ce_exam_certificate/'.$row->id;?>" ><?= $next_link; ?></a>
                  <?php  
                      }
                      else if($status=='CRf')
                      {
                  ?>
                      <!-- <a href="<?php echo base_url().'AdminController/view_ce_exam_certificate/'.$row->id;?>" ><?= $next_link; ?></a> -->
                  <?php  
                      }  
                      
                  ?>
                 
                  

                      
             
            </td>  
            </tr>  
                <?php 
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