<?php

$headings =[];
$services =[];
foreach($Flow as $k=>$v)
{
    if($k=='id'){$headings['id']='id';}
    if($k=='l2_name'){$headings['Service']='Service';}
    if($k=='category' || $k == 'status' || $k == 'details'){$headings[$k]=ucFirst($k);}
}
$headings['actions']='actions';

$services=[];
foreach($Flow as $k=>$v)
{
  foreach($v as $k1=>$v1)
  {
    if($k1=='id'){$services[$k]['id']=$v1;}
    if($k1=='l2_name'){$services[$k]['Service']=$v1;}
    if($k1=='category' || $k1 == 'status' || $k1 == 'details'){$services[$k][ucFirst($k1)]=$v1;}
    $services[$k]['actions'] =['Delete', 'Deactivate', 'Activate'];
  }
}

// echo "<pre>";
// print_r($source_controller);
// // print_r($headings);
// // print_r($services);
// echo "</pre>";
// die;

;?>
<div class="container">
    <div class="row mt-4 mx-auto">
        <div class="col-2"></div>
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
                        if($heading_name !='actions')
                        {
                ;?>
                          <td scope="col"><?php echo $service[$heading_name];?></td>
                <?php
                        }
                        if($heading_name == 'actions')
                        {
                          foreach($service[$heading_name] as $ind_service)
                          {
                            $target_controller = $source_controller.'_'.strtolower(str_replace(' ','',$ind_service));
                ;?>
                            <td scope="col"><a href="<?php echo base_url($target_controller);?>" ><?php echo $ind_service;?></a></td>
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
        <div class="col-2"><button class='btn btn-large btn-primary'>Add</button></div>
    </div>
</div>

