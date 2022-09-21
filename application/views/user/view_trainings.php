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

  .list-group-item {
    border:1px solid #fc9928;    
  }

  .list-group-unbordered>.list-group-item{
    padding-left: 7px;
    padding-right: 7px;
  }
</style>
<body class="hold-transition login-page">

    <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;">View Training</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
              <li class="breadcrumb-item ">View Training</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td>Trainer Name</td>
                <td>Training Image</td>
                <td>Training Title</td>
                <td>Apply Now</td>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($training_info)){ foreach($training_info as $v){?>
              <tr>
                <td><?php echo $v["fullname"];?></td>
                <td><img style="width:6rem" src="<?php echo $v["training_img"];?>" alt=""></td>
                <td style="width:65%"><p class="m-0 p-0"><?php echo $v["training_name"];?></p></td>
                <td>
                <button data-tid="<?php echo $v["t_id"];?>" data-uid="<?php echo $user["email"];?>" class="btn btn-sm btn-outline-primary btn-apply-now">Click here</button>
                </td>
              </tr>
              <?php } }else{
                echo "<tr><td colspan='4' class='text-center'>Data Not Found.</td></tr>";
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
  
  <script>
    let BASE_URL = '<?php echo base_url();?>';
    $(document).ready(function() {
      $(document).on("click",".btn-apply-now",function(){
        let tid = btoa($(this).data("tid"));
        let uid = $(this).data("uid");
        $.post(`${BASE_URL}Web_ajax/skill_development/APPLY_NOW`,{
          "form_email":uid,
          "tid":tid
        },function(res){
            if(res.msg == "APPLIED"){
              alert("Thank you for applying.");
              document.location.href = `${BASE_URL}BaseController/user_training`;
            }

            if(res.msg == "approval_pending"){
              alert("Approval Pending.");
            }
            
        })
    })
    })
  </script>