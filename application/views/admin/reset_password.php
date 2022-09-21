<style>
  .sp-r-heading{
    background: #e5e4e4;
    color: #343a40;
    margin: 1px 8px;
    padding: 10px;
    border-radius: 0.25rem 0.25rem 0 0;
  }

  .user-lists{
    overflow-y: scroll;
    max-height: 22rem;
  }
</style>
<body class="hold-transition login-page">

  <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Reset Password</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("AdminController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Reset Password</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php 
        $msg_export = $this->session->flashdata("msg_export");
        if(!empty($msg_export)){
          /* $download_export = $this->session->flashdata("download_export");
          base_url('AdminController/fun_export_data/'.$download_export); */
          ?>
            <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?php echo $msg_export; ?>
            </div>
          <?php
          }?>
         
        <form action="<?php echo base_url('AdminController/reset_password_form')?>" method="post">
          <div class="row">
            <div class="col-md-6">
              <div>
                <p class="sp-r-heading">Choose Individual Users</p>
                <div class="user-lists">
                  <?php if(!empty($sp_reseller)){
                    foreach($sp_reseller as $v){
                      ?>
                      <div class="icheck-primary rounded border p-2 m-2">
                        <input type="checkbox" disabled class="render-user-lists" data-users="<?php echo $v->iam; ?>"  value="<?php echo $v->id; ?>" name="change_id[]" id="checkbox_item_<?php echo $v->id; ?>">
                        
                        <label for="checkbox_item_<?php echo $v->id; ?>">
                          <p class="p-0 m-0 ml-2"><?php echo $v->fullname; ?> (<?php echo $v->iam; ?>)</p>
                          <p class="p-0 m-0 ml-2"><?php echo $v->email; ?></p>
                        </label>
                      </div>
                  <?php }
                  }?>

                </div>
              </div>
            </div>
            <div class="col-md-6">
              <p class="sp-r-heading">Choose Groups</p>
              <div class="border rounded p-3 mt-1">
                <div class="form-group">
                      <div class="icheck-primary rounded border p-2 m-2" >
                        <input type="radio" class="btn-select-user-types" id="checkbox_btn_4" name="group_name" value="individual_users">
                        <label for="checkbox_btn_4">
                          <p class="p-0 m-0 ml-2">Individual Users</p>
                        </label>
                      </div>


                      <div class="icheck-primary rounded border p-2 m-2">
                        <input type="radio" class="btn-select-user-types" id="checkbox_btn_1" name="group_name" value="sp">
                        <label for="checkbox_btn_1">
                          <p class="p-0 m-0 ml-2">All Service Providers</p>
                        </label>
                      </div>

                      <div class="icheck-primary rounded border p-2 m-2">
                        <input type="radio" class="btn-select-user-types" id="checkbox_btn_2" name="group_name" value="reseller">
                        <label for="checkbox_btn_2">
                          <p class="p-0 m-0 ml-2">All Resellers</p>
                        </label>
                      </div>

                      <div class="icheck-primary rounded border p-2 m-2" >
                        <input type="radio" class="btn-select-user-types" id="checkbox_btn_5" name="group_name" value="user">
                        <label for="checkbox_btn_5">
                          <p class="p-0 m-0 ml-2">All End Users</p>
                        </label>
                      </div>
                      
                      <div class="icheck-primary rounded border p-2 m-2">
                        <input type="radio" class="btn-select-user-types" id="checkbox_btn_3" name="group_name" value="all_end_users">
                        <label for="checkbox_btn_3">
                          <p class="p-0 m-0 ml-2">All Users</p>
                        </label>
                      </div>

                      
                  <!-- <label for="exampleInputPassword1">Password</label>
                  <input type="password" name="pass" required class="form-control" id="exampleInputPassword1" placeholder="Password"> -->
                </div>
                <button type="submit" style="display:none;" class="btn btn-primary ml-2 w-100 btn-change-pass">Change</button>
                
              </div>
            </div>
          </div>
        </form>
      <div>
    </section>
  </div>
  <script src="https://users.respicite.com/assets/plugins/jquery/jquery.min.js"></script>
<script>
 
  $(document).on("click",".btn-select-user-types",function(){
    let u_types = $(this).val();

    $(".btn-change-pass").hide();
    $(".render-user-lists").each(function() {
      $(this).removeAttr("checked");
      $(this).attr("disabled","");
    });

    if(u_types == "sp"){
      $("[data-users=sp]").each(function() {
        $(this).attr("checked","checked");
        $(this).removeAttr("disabled");
      });
      $(".btn-change-pass").show();
    }

    if(u_types == "reseller"){
      $("[data-users=reseller]").each(function() {
        $(this).attr("checked","checked");
        $(this).removeAttr("disabled");
      });
      $(".btn-change-pass").show();
    }

    if(u_types == "user"){
      $("[data-users=user]").each(function() {
        $(this).attr("checked","checked");
        $(this).removeAttr("disabled");
      });
      $(".btn-change-pass").show();
    }

    if(u_types == "individual_users"){
       $(".render-user-lists").each(function() {
        $(this).removeAttr("checked");
        $(this).removeAttr("disabled");
      });
      $(".btn-change-pass").show();
    }

    if(u_types == "all_end_users"){
      $(".render-user-lists").each(function() {
        $(this).attr("checked","checked");
        $(this).removeAttr("disabled");
      });
      $(".btn-change-pass").show();
    }
    
  })
</script>
  