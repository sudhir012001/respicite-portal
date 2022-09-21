<style>
  a{color:black}
  
  .profile-box img{
    width: 8rem;
    height: 6.5rem;
    object-fit: contain;
    margin: 10px;
  }

  .border-round{
    border:1px solid #fc9928;
  }

  .color-b{
    background-color:#fc9928;
    color:white;
  }

  .offline,.online{
    display: inline-block;
    background: red;
    color: white;
    padding: 0px 6px;
    border-radius: 18px;
  }
  .online{
    background: green;
  }
</style>
<body class="hold-transition login-page">

    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">Purchase Code History</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Purchase Code History</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">        
       <table class="table table-bordered">
         <tr>
           <th>S.No.</th>
           <th>Global ID</th>
           <th>Solution</th>
           <th>Code</th>
           <th>Payment Mode</th>
           <th>Payment status</th>
           <th>Request status</th>
          </tr>
          <?php if(!empty($purchase_history)){
            $row_i = 1;
            foreach($purchase_history as $v){
              ?>
              <tr>
                <td><?php echo $row_i;?></td>
                <td><?php echo $v->id;?></td>
                <td><?php echo $v->solution;?></td>
                <td><?php echo $v->code;?></td>
                <td>
                  <div class="text-center">
                  <?php 
                  if($v->payment_mode == "offline"){
                    echo "<span class='offline'>Offline<span>";
                  }elseif($v->payment_mode == "online"){
                    echo "<span class='online'>Online<span>";
                  }
                  ?>
                  </div>
                </td>
                <td><?php 
                if($v->payment_status == null){
                  echo "N/A";
                }elseif($v->payment_status == "success"){
                  echo "Success";
                }elseif($v->payment_status == "failed"){
                  echo "Failed";
                }
                ?></td>
                <td><?php
                switch($v->status){
                  case "Ap":
                    echo "Approval Pending";
                  break;
                  case "Rp":
                    echo "Report Pending";
                  break;
                  case "pending":
                    echo "Pending";
                  break;
                  default:
                  $v->status;
                }
                ?></td>
              </tr>
              <?php
              $row_i++;
            }
          } ?>
       </table>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  