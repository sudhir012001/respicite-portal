<style>
  .card
  {
    background-color = rgb(252,156,47);
  }
</style>

<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Services Provider</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="https://respicite.com">Home</a></li>
              <li class="breadcrumb-item active">Service Providers</li>
            </ol>
          </div>
        </div>
          
            <form action="<?php echo base_url().'SpController/search_result_public'; ?>"  id="form" method="post">
            <div class="form-row">
              <div class="col-md-4 mb-3 ">
                <select class="form-control" name="levelone" id="levelone">
                  <option value="">Service Name</option>
                  <?php 
                      foreach ($lk->result() as $row)  
                      {  
                          ?>
                          <option value="<?php echo $row->id; ?>"><?php echo $row->l1; ?></option>
                          <?php
                      }
                      ?>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <select class="form-control" name="leveltwo" id="leveltwo"  >
                  <option value="">Select</option>
                </select>
              </div>          
              <div class="col-md-3 mb-3">

                <select class="form-control" name="levelthree" id="levelthree">
                <option value="">Select</option>
                
                </select>
              </div>
              <div class="col-md-1 mb-3">
                <button type="submit" name="saveBtn" id="add" class="btn btn-primary btn-block">Search</button>
              </div>
            </div>
            <?php 
                    $msg = $this->session->flashdata('msg');
                    if($msg !="")
                    {
                    ?>     
                    <div class="alert alert-danger">
                        <?php echo $msg; ?>
                    </div>
                    <?php 
                    }
                     $msg2 = $this->session->flashdata('msg2');
                    if($msg2 !="")
                    {
                    ?>
                    <div class="alert alert-success">
                    <?php echo $msg2; ?>
                    </div>
                    <?php 
                    }
                    ?> 

      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <div class="container">
    <section class="content">
          
      <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row">
            <?php  
              foreach ($l->result() as $row)  
                {  
                  $e = $row->email;
            ?>
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
              <div class="card card-style-resp d-flex flex-fill">
                <div class="card-header text-muted border-bottom-0">
                <?php
                  
                 
                  $this->db->where('email',$e);
                  $l1=$this->db->get('provider_detail_first');
                  foreach($l1->result() as $l1)
                  {
                    $l=$l1->l1;
                    $this->db->where('id',$l);
                    $lev = $this->db->get('provider_level_one');
                 
                  foreach($lev->result() as $row2)  
                {  ?>
                  <?php echo $row2->l1;
                  } 
                  }?>
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                 
                    <div class="col-7">
                      <h2 class="lead"><b><?php echo $row->fullname;?></b></h2>
                      
                      
                      <p class="text-muted "><b>Services: </b> 
                      <?php
                      $this->db->where('email',$e);
                     $l2 = $this->db->get('provider_detail_sec');
                     foreach($l2->result() as $l2)
                     {
                         $l=$l2->l2;
                         $this->db->where('id',$l);
                         $leve = $this->db->get('provider_level_two');
                     
                    foreach($leve->result() as $row3)  
                    {  
                 ?>                
                      <?php echo $row3->l2;?> 
                      <?php
                      }?><br>
                      <?php 
                     }
                      ?>                                     
                       </p> 
                     
                     <?php 
                    $this->db->where('email',$e);
                    $l3= $this->db->get('provider_detail_three');
                    foreach($l3->result() as $l3)
                    {
                        $l=$l3->l3;
                        $this->db->where('id',$l);
                        $m = $this->db->get('provider_level_three');
                     
                    foreach ($m->result() as $row4)  
                {  
                  

                  
                 ?>                
                      <?php echo $row4->l3;?> 
                      <?php
                     } }
                      ?>  
                     
                      </div>
                      <div class="col-5 text-center">
                      <img src="<?php echo base_url().$row->profile_photo; ?>" alt="" class="img-circle img-fluid">
                    </div>
                    <div class="col-12">
                    <p class="text-muted text-sm"><b>About Us: </b> 
                      <?php
                      $this->db->where('email',$e);
                      $la = $this->db->get('sp_profile_detail');
                      $string = '';
                    foreach($la->result() as $la)
                     {
                         $string=$la->about_us;
                   
                    }  
                    
                  ?>
                  <?php
                   if($string!='')
                   {
                    if (strlen($string) > 150) {
                      $stringCut = substr($string, 0, 75);// change 15 top what ever text length you want to show.
                      $endPoint = strrpos($stringCut, ' ');
                      $string = $endPoint? substr($stringCut, 0, $endPoint):substr($stringCut, 0);
                      $string .= '... <a style="cursor: pointer;" href="'.base_url().'SpController/view_sp_full_detail_public/'.base64_encode($row->email).'" >Read More</a>';
                  }
                  echo $string;
                   }
                    
                  ?> 
                    </div>
                     
                   
                  </div>
                </div>
                <div class="card-footer">

                  <div class="text-right">
                    <!-- <a href="#" class="btn btn-sm bg-teal">
                      <i class="fas fa-comments"></i>
                    </a> -->
                    <a style="cursor: pointer;" class="btn btn-sm btn-primary" href="tel:<?php echo $row->mobile; ?>" >
                      <i class="fas fa-phone"></i>Contact Us
                    </a>
                    <a style="cursor: pointer;" class="btn btn-sm btn-primary" href="mailto:<?php echo $row->email; ?>" >
                      <i class="fas fa-envelope"></i>Send Mail
                    </a>
                    <a style="cursor: pointer;" class="btn btn-sm btn-primary open-homeEvents" href="<?php echo base_url().'service-provider/'.$row->email; ?>" >
                      <i class="fas fa-user"></i> View Profile
                    </a>
                    <!-- <a style="cursor: pointer;" class="btn btn-sm btn-primary open-homeEvents" href="<?php echo base_url().'SpController/view_sp_full_detail_public_updated?email='.$row->email; ?>" >
                      <i class="fas fa-user"></i> View Profile
                    </a> -->
                  </div>
                </div>
              </div>
            </div>
            <?php
            }
            ?>
        
           
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer" align="center">
          <?php
          
            $lid1 = $row->id + 1;
            $where = "iam='sp' and status!='0'";
            $this->db->where($where);
            $this->db->limit('1');
            $this->db->order_by('id','DESC');
            $last = $this->db->get('user_details');
            foreach($last->result() as $ls)
            {
              $last_id = $ls->id;
            }
            if($last_id!=$row->id)
            {
          
                $where2 = "iam='sp' and status!='0'";
                $this->db->where($where2);
                $this->db->limit('6');
                $this->db->order_by('id','ASC');
                $last2 = $this->db->get('user_details');
                $last_id2 = array();
                foreach($last2->result() as $ls2)
                {
                  array_push($last_id2,$ls2->id);
                }
                if($row->id!=$last_id2[5])
                {
                  $where3 = "iam='sp' and status!='0' and id<'$row->id'";
                  $this->db->where($where3);
                  $this->db->limit(11);
                  $this->db->order_by('id','DESC');
                  $last3 = $this->db->get('user_details');
                  
                  foreach($last3->result() as $ls3)
                  {
                    $lp = $ls3->id;
                  }
                    $lid = $lp;
            ?>
                    
                      <a style="cursor: pointer; width:100px;" class="btn btn-sm btn-primary" href="<?php echo base_url().'SpController/all_sp_provider/'.$lid; ?>" >
                                    Previous
                      </a>
                      
                     
            <?php
              } 
             ?>
            <a style="cursor: pointer; width:100px;" class="btn btn-sm btn-primary" href="<?php echo base_url().'SpController/all_sp_provider/'.$lid1; ?>" >
                          Next 
            </a>
            <?php 
            }
            else
            {
              $where4 = "iam='sp' and status!='0' and id<'$row->id'";
              $this->db->where($where4);
              $this->db->limit('6');
              $this->db->order_by('id','DESC');
              $last4 = $this->db->get('user_details');
              foreach($last4->result() as $ls4)
              {
                $last_id4 = $ls4->id;
              }
              $lid4 = $last_id4;
          ?>
              
              <a style="cursor: pointer; width:100px;" class="btn btn-sm btn-primary" href="<?php echo base_url().'SpController/all_sp_provider/'.$lid4; ?>" >
                            Previous 1
              </a>
          <?php
            }

          ?>
        
          <!-- <nav aria-label="Contacts Page Navigation">
            <ul class="pagination justify-content-center m-0">
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">4</a></li>
              <li class="page-item"><a class="page-link" href="#">5</a></li>
              <li class="page-item"><a class="page-link" href="#">6</a></li>
              <li class="page-item"><a class="page-link" href="#">7</a></li>
              <li class="page-item"><a class="page-link" href="#">8</a></li>
            </ul>
          </nav> -->
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->

    </section>
    </div>
    <!-- /.content -->
  </div>
  
  <script
  src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  
<script type=text/javascript>
    
   $(document).ready(function(){
 
    $('#levelone').change(function(){ 
                var id=$(this).val();
                $.ajax({
                    url : "<?php echo base_url().'AdminController/fetch_level_two';?>",
                    method : "POST",
                    data : {id: id},
                    async : true,
                    dataType : 'json',
                    success: function(data){
                         
                        var htm = '';
                        var i;
                        htm += '<option value="">Select Level Two</option>';
                        for(i=0; i<data.length; i++){
                            htm += '<option value='+data[i].id+'>'+data[i].l2+'</option>';
                        }
                        $('#leveltwo').html(htm);
                        var ht = '';
                        
                        ht += '<option value="">Select Level Three</option>';
                       
                        $('#levelthree').html(ht);
 
                    }
                });
                return false;
            }); 
             
            $('#leveltwo').change(function(){ 
                var id=$(this).val();
                $.ajax({
                    url : "<?php echo base_url().'AdminController/fetch_level_three';?>",
                    method : "POST",
                    data : {id: id},
                    async : true,
                    dataType : 'json',
                    success: function(data){
                         
                        var ht = '';
                        var i;
                        ht += '<option value="">Select Level Three</option>';
                        if(data.length==0)
                        {
                          ht += '<option value="0">NA</option>';
                        }
                        for(i=0; i<data.length; i++){
                            ht += '<option value='+data[i].id+'>'+data[i].l3+'</option>';
                        }
                        $('#levelthree').html(ht);
 
                    }
                });
                return false;
            }); 
        });      
 
 
            
     
    </script>  
  