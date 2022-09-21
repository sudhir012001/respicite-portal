<?php

$headings =[];
$services =[];
$actions = explode(',',$actions);
foreach($service_list[0] as $k=>$v)
{
    if($k=='id'){$headings['id']='id';}
    if($k=='l2_name'){$headings['Service']='Service';}
    if($k=='category' || $k == 'status' || $k == 'details'){$headings[$k]=ucFirst($k);}
}
$headings['actions']='actions';

$services=[];
foreach($service_list as $k=>$v)
{
  foreach($v as $k1=>$v1)
  {
    if($k1=='id'){$services[$k]['id']=$v1;}
    if($k1=='l2_name'){$services[$k]['Service']=$v1;}
    if($k1=='category' || $k1 == 'status' || $k1 == 'details'){$services[$k][ucFirst($k1)]=$v1;}
    $services[$k]['actions'] =['Update Details', 'Update Status', 'Update Flow'];
  }
}

// echo "<pre>";
// print_r($source_controller);
// print_r($service_list);
// print_r($headings);
// print_r($services);
// print_r($actions);
// echo "</pre>";
// die;

;?>
<div class="container">
    <div class="row mt-4 mx-auto">
        <div class="col-3"></div>
        <div class="col-8">
            <table class="table table-hover .table-responsive table-sm table-bordered">
              <thead class='thead-light'>
                <tr>
                    <?php
                    foreach($headings as $heading)
                    {
                      ;?>
                    <th scope="col"><?php echo $heading;?></th>
                    <?php
                    };
                  ?>
                </tr>
              </thead>

              <tbody>
                <?php
                    foreach($services as $service_no=>$service)
                    {
                ;?>
                  <tr>
                <?php
                      foreach($headings as $heading_name)
                      {
                        $var1 = $var2 ='';
                        if($heading_name !='actions')
                        {
                         
                ;?>
                          <td scope="col"><?php echo $service[$heading_name];?></td>
                <?php
                        }
                        if($heading_name == 'actions')
                        {
                          foreach($actions as $ind_service)
                          {
                            $target_controller = $source_controller.'_'.strtolower(str_replace(' ','',$ind_service));
                ;?>
                            <td scope="col"><a href="<?php echo base_url($target_controller);?>?service_id=<?php echo $service['id'];?>" ><?php echo $ind_service;?></a></td>
                <?php
                          }
                        }
                ;?>

                <?php
                      }
                ;?>
                  </tr>
                <?php
                    }
                ;?>
                              
              </tbody>
            </table>
        </div>
        <div class="col-1"></div>
    </div>
</div>




