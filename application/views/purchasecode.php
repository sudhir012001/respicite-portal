<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Purchase Code</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
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
              <form action="<?= base_url('UserController/save_request_code') ?>" method="post">
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
                  <!-- <tbody>
                    
                    <tr>
                      <td rowspan="3" valign="center" align="center">UCE</td>
                      <td>25</td>
                      <td>60000</td>
                      <td>30000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="UCE25" id="ccb1" value="25" name="ccb1" onchange="changeThis(this)">
                          <label for="ccb1" class="custom-control-label">Select for Purchase</label>
                        </div>

                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb1" value="request" name="cb1" disabled>
                          <label for="cb1" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>50</td>
                      <td>100000</td>
                      <td>50000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" id="ccb2" value="UCE50" name="ccb1" onchange="changeThis(this)">
                          <label for="ccb2" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb2" value="request" name="cb2" disabled>
                          <label for="cb2" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      
                      <td>100</td>
                      <td>180000</td>
                      <td>90000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="UCE100" name="ccb1" id="ccb3" onchange="changeThis(this)">
                          <label for="ccb3" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb3" value="request" name="cb3" disabled>
                          <label for="cb3" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td rowspan="3" valign="center" align="center">QCE</td>
                      <td>25</td>
                      <td>40000</td>
                      <td>20000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="QCE25" name="ccb2" id="ccb4" onchange="changeThis(this)">
                          <label for="ccb4" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb4" value="request" name="cb4" disabled>
                          <label for="cb4" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                    <td>50</td>
                      <td>56000</td>
                      <td>28000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="QCE50" name="ccb2" id="ccb5" onchange="changeThis(this)">
                          <label for="ccb5" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb5" value="request" name="cb5" disabled>
                          <label for="cb5" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      
                    <td>100</td>
                      <td>80000</td>
                      <td>40000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="QCE100" name="ccb2" id="ccb6" onchange="changeThis(this)">
                          <label for="ccb6" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb6" value="request" name="cb6" disabled>
                          <label for="cb6" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td rowspan="3" valign="center" align="center">CAT</td>
                      <td>25</td>
                      <td>12000</td>
                      <td>6000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="CAT25" name="ccb3" id="ccb7" onchange="changeThis(this)">
                          <label for="ccb7" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb7" value="request" name="cb7" disabled>
                          <label for="cb7" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                    <td>50</td>
                      <td>18000</td>
                      <td>9000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="CAT50" name="ccb3" id="ccb8" onchange="changeThis(this)">
                          <label for="ccb8" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb8" value="request" name="cb8" disabled>
                          <label for="cb8" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      
                    <td>100</td>
                    <td>30000</td>
                     <td>15000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="CAT100" name="ccb3" id="ccb9" onchange="changeThis(this)">
                          <label for="ccb9" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb9" value="request" name="cb9" disabled>
                          <label for="cb9" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td rowspan="3" valign="center" align="center">PPE</td>
                      <td>25</td>
                      <td>60000</td>
                      <td>30000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="PPE25" name="ccb4" id="ccb10" onchange="changeThis(this)">
                          <label for="ccb10" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb10" value="request" name="cb10" disabled>
                          <label for="cb10" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                    <td>50</td>
                      <td>100000</td>
                      <td>50000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="PPE50" name="ccb4" id="ccb11" onchange="changeThis(this)">
                          <label for="ccb11" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb11" value="request" name="cb11" disabled>
                          <label for="cb11" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      
                    <td>100</td>
                      <td>180000</td>
                      <td>90000</td>
                      <td>
                      <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="PPE100" name="ccb4" id="ccb12" onchange="changeThis(this)">
                          <label for="ccb12" class="custom-control-label">Select for Purchase</label>
                        </div>
                      </td>
                      <td>
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="cb12" value="request" name="cb12" disabled>
                          <label for="cb12" class="custom-control-label">Request</label>
                        </div>
                      </td>
                    </tr>
                    
                    
                  </tbody> -->
                </table>
                
              </div>
              <div class="card-footer clearfix">
                <div class="float-right">
                <button type="submit" class="btn btn-primary" name="purchase" id="purchase">Request for Code</button>
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