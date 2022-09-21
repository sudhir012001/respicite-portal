  <?php
  // echo APPPATH;
  $page_params['dashboard']=[
    'title'=>'Marketing Optimization Dashboard',
    'bc'=>['1'=>'marketing/dashboard'],
    'img_path'=>'../assets/customers/506/mot/img/',
    'img_name'=>'graphs.webp',
    'tbls'=>[
      'Order Performance'=>[
        'head' =>['Platform'	 , 'Orders',	'cost/Order',	'Spend'],
        'row_1'=>['Google'	   , '30	   ',  '666.7'	   ,   '20000'],
        'row_2'=>['Facebook'	 , '10	   ',  '3000.0'	  ,  '30000'],
        'row_3'=>['Instagram'	,'25	   ' , '2400.0'	   , '60000'],
        'row_4'=>['Amazon'	   , '100	 ' , '500.0'	    ,  '50000'],

      ],
      'Engagement'=>[
      'head'=>['Platform','Google	',  'Facebook',  'Instagram', 'Amazon'],
      'row_1'=>['Orders	  ','30	    ', '10	   ',  '25','100'],
      'row_2'=>['Cost/Order	','666.7	',  '3000.0	   ',  '2400','500'],
      'row_3'=>['Total Spend	','20000',  '30000	   ',  '60000','50000'],
      
      ],  

    ]
    
  ]
  ?>
  
  <!-- Content Wrapper. Contains page content -->
  <style>a{color:black;}</style>
  <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <div class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <!-- Row 1 -->
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;"><?php echo $page_params['dashboard']['title']; ?>
            </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url().$page_params['dashboard']['bc']['1']; ?>">Dashboard</a></li>
              <li class="breadcrumb-item active"></li>
            </ol>
          </div>
          <!-- /.col -->
        </div><!-- /.row -->
        <!-- !Row 1 -->

        <!-- Row 2 -->
        <div class="row mb-2">
          <div class="col-sm-8">
            <h1>Graphical View</h1>
            <img src="<?php echo base_url().$page_params['dashboard']['img_path'].$page_params['dashboard']['img_name'];?>" alt="Visualization">
          </div>
          <div class="col-sm-4">
            <div class="row">
              <div class="col">
                <h1>Platform vs Financials</h1>
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">First</th>
                      <th scope="col">Last</th>
                      <th scope="col">Handle</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th scope="row">1</th>
                      <td>Mark</td>
                      <td>Otto</td>
                      <td>@mdo</td>
                    </tr>
                    <tr>
                      <th scope="row">2</th>
                      <td>Jacob</td>
                      <td>Thornton</td>
                      <td>@fat</td>
                    </tr>
                    <tr>
                      <th scope="row">3</th>
                      <td colspan="2">Larry the Bird</td>
                      <td>@twitter</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <h1>Financials vs Platform</h1>
              <div class="col">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">First</th>
                      <th scope="col">Last</th>
                      <th scope="col">Handle</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th scope="row">1</th>
                      <td>Mark</td>
                      <td>Otto</td>
                      <td>@mdo</td>
                    </tr>
                    <tr>
                      <th scope="row">2</th>
                      <td>Jacob</td>
                      <td>Thornton</td>
                      <td>@fat</td>
                    </tr>
                    <tr>
                      <th scope="row">3</th>
                      <td colspan="2">Larry the Bird</td>
                      <td>@twitter</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- !Row 2 -->

        <!-- Row 3 -->
        <div class="row mb-2">
          <?php
          foreach($page_params['dashboard']['tbls'] as $kt=>$tbl)
          {
            ?>
          <div class="col-sm-6">
            <h1><?php echo $kt ;?></h1> 
            <table class="table">
              <thead>
                <tr>
                  <?php 
                  foreach($tbl['head'] as $v)
                  {
                  ?>
                    <th scope="col"><?php echo $v;?></th>
                  <?php
                  }
                  ;?>
                </tr>
              </thead>

              <tbody>
                <?php
                foreach($tbl as $k=>$v)
                {
                  if(explode("_",$k)[0]=="row")
                  {
                    ?>
                    <tr>
                    <?php
                    foreach($v as $v1)
                    {
                      ?>
                      <td><?php echo $v1;?></td>
                      <?php
                    }
                  ?>
                  </tr>
                  <?php
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
            <?php
          }

          ?>

        </div>
        <!-- !Row 3 -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

       
        