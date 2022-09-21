<body class="hold-transition sidebar-mini">
<div id="modalHomeEvents" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;">
        <b>Service Details</b>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
    
        <input type="hidden" name="eventId" id="eventId"/>
        	<span id="idHolder"></span>	
            
        </div>
        <div class="modal-footer">
          
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Certification Contents</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Certification</li>
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
$email = $user['email'];
?>
  <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <?php 
            foreach($h->result() as $row)
            {
        ?>
        <div class="card card-default collapsed-card">
          <div class="card-header">
            <h3 class="card-title"><?php echo $row->c_group; ?> </h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
              </button>
              
            </div>
          </div>
          <!-- /.card-header -->
          
          <div class="card-body">
          <div class="row">
          <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
          <div class="card card-primary h-100">
          
              
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Detail</strong>

                <p class="text-muted">
                <?php echo "Upload Template PDF"; ?>
                </p>
                <hr>              
                <p class="text-muted">
                
                <a href="<?php echo base_url().'AdminController/upload_files/'.$row->c_group.'/template_pdf'; ?>" class="btn btn-primary btn-block"><b>Upload PDF Template</b></a>
                </p>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          
            </div>

            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card card-primary h-100">
          
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Detail</strong>

                <p class="text-muted">
                <?php echo "Upload Logo"; ?>
                </p>

                <hr>
                              
                <p class="text-muted">
                <a href="<?php echo base_url().'AdminController/upload_files/'.$row->c_group.'/logo'; ?>" class="btn btn-primary btn-block"><b>Upload Logo</b></a>
                </p>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          
            </div>

            
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card card-primary h-100">
          
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Detail</strong>

                <p class="text-muted">
                <?php echo "Upload Other Content "; ?>
                </p>

                <hr>
                              
                <p class="text-muted">
                <a href="<?php echo base_url().'AdminController/upload_content/'.$row->c_group; ?>" class="btn btn-primary btn-block"><b>Detail Content</b></a>
                </p>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          
            </div>

            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card card-primary h-100">
          
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Detail</strong>

                <p class="text-muted">
                <?php echo "Upload Sign one"; ?>
                </p>

                <hr>
                              
                <p class="text-muted">
                <a href="<?php echo base_url().'AdminController/upload_files/'.$row->c_group.'/sign_one'; ?>" class="btn btn-primary btn-block"><b>Signature 1</b></a>
                </p>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          
            </div>
          
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card card-primary h-100">
          
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Detail</strong>

                <p class="text-muted">
                <?php echo "Upload Sign Two"; ?>
                </p>

                <hr>
                              
                <p class="text-muted">
                <a href="<?php echo base_url().'AdminController/upload_files/'.$row->c_group.'/sign_two'; ?>" class="btn btn-primary btn-block"><b>Signature 2</b></a>
                </p>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          
            </div>
          
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
            <div class="card card-primary h-100">
          
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Detail</strong>

                <p class="text-muted">
                <?php echo "Upload Sign Three"; ?>
                </p>

                <hr>
                              
                <p class="text-muted">
                <a href="<?php echo base_url().'AdminController/upload_files/'.$row->c_group.'/sign_three'; ?>" class="btn btn-primary btn-block"><b>Signature 3</b></a>
                </p>
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          
            </div>
          
          </div>
          
          <!-- /.card-body -->
          
          </div>
         
        </div>
              <?php
            }
        ?>
           
       
        </div>
     
  </section>
 