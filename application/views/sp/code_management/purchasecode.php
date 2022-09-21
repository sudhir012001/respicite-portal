<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Purchase Code</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("SpController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Purchase Code</li>
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
                <h3 class="card-title">Code List</h3>
              </div> 
              <!-- /.card-header -->
              <form action="<?=base_url('SpController/save_request_code')?>" method="post">
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                      <th>Solution</th>
                      <th>No of Reports</th>
                      <th>MRP (Overall)</th>
                      <th>Discounted cost (Overall)</th>
                      <th>Select code for purchase</th>
                      <th>Request additional voucher</th>
                    </tr>
                  </thead>
                  <tbody>
                          <?php  
                          $i = 0;
                          $j = 0;
                          
                            foreach ($s->result() as $row)  
                            {  
                              $i++;
                             
                              $solution = $row->solution;
                              $services['h'] = $this->User_model->fetch_purchase_code($solution);
                              foreach($services['h']->result() as $row2)
                              {
                                $j++;
                                echo "<tr>";
                                ?>
                                <td><?php echo $row2->solution;?></td>
                                <td><?php echo $row2->no_of_reports;?></td>  
                                <td><?php echo $row2->mrp;?></td>  
                                <td><?php echo $row2->discount;?></td>  
                                <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="ccb<?php echo $j; ?>" value="<?php echo $row2->code_id; ?>" name="ccb<?php echo $i; ?>" onchange="changeThis(<?php echo $j; ?>)">
                          <label for="ccb<?php echo $j; ?>" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="radio" id="cb<?php echo $j; ?>" value="request" name="cb<?php echo $i; ?>" disabled>
                          <label for="cb<?php echo $j; ?>" class="custom-control-label">Request</label>
                        </div>
                      </td>
                      </tr>
                              <?php
                              }
                             
                        ?><tr>  

                    </tr>  
                        <?php }  
                    ?>  
                  </tbody>
             
                </table>
                
              </div>
                <div class="card-footer clearfix">
                    <div class="float-right">
                    <button type="submit" class="btn btn-primary" name="purchase" id="purchase" >Request for Code</button>
                    </div>
                </div>
              
             
</form>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <script>
        function changeThis(sender) { 
          var s = sender
          var i = 1
          var j = "<?php echo $j; ?>"
          for(i=1;i<=j;i++)
            {
              var ccb = "ccb".concat(i);
              var cb = "cb".concat(i);
             
              if(document.getElementById(ccb).checked){
               
              document.getElementById(cb).removeAttribute('disabled');
              
              }
            }
              
            }
        
        </script>