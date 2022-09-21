<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Service Provider List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Service Provider List</li>
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
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10%">Service Provider ID</th>
                      <th style="width: 10%">Full Name</th>
                      <th style="width: 10%">Email</th>
                      <th style="width: 10%">Mobile</th>
                      <th style="width: 10%">Status</th>
                      <th style="width: 40%">Action</th>

                    </tr>
                  </thead>
                  <tbody>
                    
                 <?php  
                    $i=1;
                    foreach ($h->result() as $row)  
                {  
                 ?><tr>  
                     <!-- <td><?php //echo $row->user_id;?></td>  -->
                     <td>
                        <?php 
                          echo $i;
                          $i +=1;
                          
                          ?>
                     </td> 
                    <td><?php echo $row->fullname;?></td>  
                    <td><?php echo $row->email;?></td>  
                    <td><?php echo $row->mobile;?></td>
                    <td><?php $status = $row->status;
                      if($status=='3')
                      {
                        $cst = "active";
                        $ccst = "ACTIVE";
                        echo "INACTIVE";
                      }
                      else
                      {
                        $ccst = "INACTIVE";
                        $cst = "inactive";
                        echo "ACTIVE";
                      }
                    ?></td>  
            <td><div class="row">
                    <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
                        <input type="hidden" name="rid" id='rid<?php echo $row->id; ?>' value="<?php echo $row->id; ?>">
                        <select class="form-control" name="act" id="act<?php echo $row->id; ?>" onchange="changeThis(<?php echo $row->id; ?>)">
                        <option value="">Change Status</option>
                          <option value="<?php echo $cst; ?>"><?php echo $ccst; ?></option>
                          <option value="delete">DELETE</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
                        <input type="hidden" name="resellerid" id='resellerid_<?php echo $row->id; ?>'  value="<?php echo $row->id; ?>">  
                        <select class="form-control" name="landing_page" id="landing_page_<?php echo $row->id; ?>" onchange="configLanding('<?php echo $row->id; ?>')">
                        <option value="">config Landing Page</option>
                        <?php  if(isset($landingPages)){ foreach($landingPages as $page) {   
                        if($row->landing_id == $page['id'] ){
                        $selected = "selected";
                        }else{$selected = "";
                        } ?>
                          <option value="<?php echo $page['id']; ?>" <?php echo $selected;?>><?php echo $page['name']; ?></option>
                          <?php  } } ?>
                        </select>
                      </div>
                    </div>
                    </div></td>
            </tr>  
                <?php }  
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
        <script
  src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
        function changeThis(sender) { 
          var s = sender
          var r = 'rid'.concat(s);
          var a = 'act'.concat(s);
          var rid = document.getElementById(r).value;
	        var act = document.getElementById(a).value;
              if(s=="")
              {
                alert("Something Went Wrong");
              }
              else
              {
                $.ajax({
                  url: "<?php echo base_url(); ?>AdminController/edit_reseller_status",
                  type : "POST",
                  dataType:"json",
                  data: {
                    s: rid,
                    act : act
                  },
                  success : function(data)
                  {
                    if(data.responce == "success")
                    {
                      toastr["success"](data.message)

                      toastr.options = {
                      "closeButton": true,
                      "debug": false,
                      "newestOnTop": false,
                      "progressBar": true,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "300",
                      "hideDuration": "1000",
                      "timeOut": "5000",
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut"
                        }
                      location.reload(true)
                    }
                    else
                    {
                      alert("something went wrong")
                    }
                  }
                });

              }
            };
            function configLanding(id) {
          var resellerid = document.getElementById('resellerid_'+id).value;
          var select = document.getElementById('landing_page_'+id);
           var value = select.options[select.selectedIndex].value;
           //var select =  $("#landing_page option:selected").val();
            console.log(resellerid);
            console.log(value);
              if(resellerid=="" && select=="")
              {
                alert("Something Went Wrong");
              }
              else
              {
                $.ajax({
                  url: "<?php echo base_url(); ?>AdminController/select_landing_page",
                  type : "POST",
                  dataType:"json",
                  data: {
                    resellerid: resellerid,
                    landId : value
                  },
                  success : function(data)
                  {
                      console.log(data.response);
                    if(data.response == "success")
                    {
                      
                      location.reload(true);
                    }
                    else
                    {
                      alert("something went wrong")
                    }
                  }
                });

              }
            };        
        </script>