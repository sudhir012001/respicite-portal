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

  /* .btn-my {
    color: #fc9928;
    border-color: #fc9928;
  } */
  .btn-my {
    color: #fff;
    background-color: #fc9928;
    border-color: #fc9928;
  }

  .btn-my:hover {
    color: #fff;
    background-color: #fc9928;
    border-color: #fc9928;
  }
  .btn-my:active {
    color: #fff;
    background-color: #fc9928;
    border-color: #fc9928;
  }
</style>
<body class="hold-transition login-page">
<?php 
    $field = $this->uri->segment(2);
    $this->db->where('solution',$field);
    $detail = $this->db->get('solution_instruction')->row();
?>
   <div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <section class="content-header bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
         <!-- instruction modal -->
         <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Instruction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <?php echo $detail->detail_instruction; ?>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-my" data-dismiss="modal">Close</button>
                   
                  </div>
                </div>
              </div>
            </div>
        <div class="row mb-2">
            
          <div class="col-sm-6">
          <h1 class="m-0 pt-2" style="font-size: 1.2em;"><b><?php echo $detail->top_display; ?></b>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <button type="button" class="btn btn-sm btn-my mt-1" data-toggle="modal" data-target="#exampleModalLong">
              Read insturction
            </button>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-3"></div>
          <div class="col-md-6">

            <!-- Profile Image -->
            <div class="bg-white rounded border-round shadow mt-4">
                <div class="card-body box-profile">
                <h3 class="profile-username text-center">Part 1
                </h3>
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
               

      <form action="" method="post">
      <div class="col-sm-12">
            <div class="card card-solid shadow">
                <div class="card-body pb-0">
                <div class="form-group"><p class="bm-0 top-discription">
                <?php echo $detail->top_discription; ?>
                  </p>
                </div>
            <!-- /.card-body -->
                </div>
            </div>
        </div>        
        
      <div class="card-body">   
 <?php
            $i=1;
            foreach($r->result() as $r)
            {
                if($i==1 || $i==6 || $i==11 || $i==16 || $i==21)
                {
             ?>       
             
                    <ul class="todo-list" data-widget="todo-list">
            <?php
                }
                
      ?>              
                  <li>
                    <!-- drag handle -->
                    <span class="handle">
                      
                    
                    <!-- checkbox -->
                    
                    <!-- todo text -->
                    <span class="text"><?php echo $r->qno.'. '.$r->item; ?> </span>
                    
                    <input type="hidden" name="grp[]" value="<?php echo $r->grp; ?>">
                    <input type="hidden" value="<?php echo $r->qno; ?>" name="list[]">
                    </span>
                   
                    <!-- Emphasis label -->
                    <!-- General tools such as edit or delete-->
                  </li>
                  
                
       <?php
       if($i==5 || $i==10 || $i==15 || $i==20 || $i==25)
       {
    ?>       
           
       </ul>
      
       <hr>
      
   <?php
       }
       $i++;
            }
      ?>
       </div>            <!-- /.card-body -->
              <div class="card-footer clearfix">
                <button type="submit" name="saveBtn" class="btn btn-my btn-block float-right">Save And Next</button>
              </div>
            </div>
          <!-- /.col -->
        </div>
      </form>

                <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
           
            <!-- /.card -->
          </div>
         
        </div>
        <!-- /.row -->

               

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  
