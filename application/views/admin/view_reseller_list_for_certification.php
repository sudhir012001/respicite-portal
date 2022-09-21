<body class="hold-transition sidebar-mini">
<div class="wrapper">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header  mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Certification</h1>
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
?>
  <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Reseller List</h3>

              </div> 
              <!-- /.card-header -->
              
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap">
                  <thead>
                    <tr>
                    
                      <th>Email</th>
                      <th>Solutions</th>
                      <th>Status</th>
                      <th>Action</th>
                      <th>Status/Action</th>
                      
                     
                    </tr>
                  </thead>
                  <tbody>
                    
                 <?php  
                 $i = 1;
                foreach ($h->result() as $row)  
                {  
                 ?><tr>  
            <td><?php echo $row->email;?></td>  
            <?php 
                // $solution = array();
                $status = array();
                $t_status = array();
                $t_id = array();
                $this->db->where('email',$row->email);
                $r = $this->db->get('reseller_certification');
                foreach($r->result() as $r)
                {
                    $this->db->where('email',$row->email);
                    $cr = $this->db->get('reseller_certification');
                  //  array_push($solution,$r->c_group); 
                  //  array_push($status,$r->c_status);
                   array_push($t_id,$r->id);
                   array_push($t_status,$r->status);

                }
            ?>
            <td><?php 
                $this->db->where('email',$row->email);
                 $this->db->select('DISTINCT(c_group),id');
                //  $this->db->distinct();
                 $cr = $this->db->get('reseller_certification');
                foreach($cr->result() as $s)
                {
                    echo $s->c_group."<br>";
                    $this->db->where('id',$s->id);
                    $st = $this->db->get('reseller_certification')->row();
                    array_push($status,$st->c_status);

                }
            ?></td> 
            <td>
            <?php 
                foreach($status as $st)
                {
                    if($st=="0")
                    {
                        echo "Disabled<br>";
                    }
                    else
                    {
                        echo "Enabled<br>";
                    }
                }
            ?>
            </td>
            <td><div class="row">
                    <div class="col-sm-12">

                      <!-- select -->

                      <div class="form-group">
                          <input type="hidden" name="email" id="email<?php echo $i; ?>" value="<?php echo $row->email; ?>">
                        <select class="form-control" name="act" id="act<?php echo $i; ?>" onchange="changeThis(<?php echo $i; ?>)">
                        <option value="">Change Status</option>
                          <option value="e">Enable All Certification Test</option>
                          <option value="d">Disable All Certification Test</option>
                            <?php 
                            $this->db->where('email',$row->email);
                            $this->db->select('DISTINCT(c_group),id');
                            $r = $this->db->get('reseller_certification');
                                foreach($r->result() as $rp)
                                {
                                    if($rp->c_status==0)
                                    {
                                        $op = 'Enable '.$rp->c_group.' Certification Test';
                                    }
                                    else
                                    {
                                        $op = 'Disable '.$rp->c_group.' Certification Test'; 
                                    }
                            ?>
                                    <option value="<?php echo $rp->id; ?>"><?php echo $op; ?></option>
                            <?php      
                                   
                                }
                            ?>
                        </select>
                      </div>
                    </div>
                    </div></td>
                    <td>
                      <?php 
                        $this->db->where('email',$row->email);
                        $this->db->select('DISTINCT(c_group),id,rqst,status');
                        $rgp = $this->db->get('reseller_certification');
                        foreach($rgp->result() as $rgp)
                        {
                            if($rgp->status=='2' && $rgp->rqst==1)
                            {
                              echo "Completed but fail and requested for reassesment. ";
                              echo "<a href='".base_url().'AdminController/approve_retest/'.$rgp->id."'>Approve</a><br>";
                                
                            }
                            else if($rgp->status=='1')
                            {
                                echo "Not Completed<br>";
                            }
                            else if($rgp->status=='2')
                            {
                              echo "Completed<br>";
                            }
                        }
                      ?>
                    </td>
            </tr>  
                <?php
                $i++; 
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
          var r = 'email'.concat(s);
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
                  url: "<?php echo base_url(); ?>AdminController/edit_reseller_certification",
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
        </script>