
<?php
$headings =['Name'];
$details = explode(",",$Details['parameter']);

;?>


<!-- ! Add block -->

    <div class="row mt-4 mx-auto">
        <div class="col-3">

        </div>
        <div class="col-lg-6">
            <table class="table table-hover .table-responsive table-sm table-bordered">
              <thead class='thead-light'>
                <!-- <tr><th scope="col"><button type="button" class="btn btn-lg btn-primary">Add New Parameter </button></th>   </tr> -->
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
                    foreach($details as $detail)
                    {
                ;?>
                  <tr><td scope="col"><?php echo $detail;?></td></tr>
                <?php
                    }
                ;?>
              </tbody>
            </table>
        </div>
        <div class="col-lg-3">
        <!-- <a href='<?php //echo base_url($source_controller);?>?editDetails=true;'> -->
        <!-- <button type="button" class="btn btn-lg btn-primary">Add New Parameter
        </button> -->
        <button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#addParameterModal">
          Add New Parameter
        </button>
        <!-- </a>  -->
        </div>
    </div>
</div>


<!-- Modal -->
<!-- Button trigger modal -->


<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="addParameterModal" tabindex="-1" role="dialog" aria-labelledby="addParameterModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="inputs">
        <label class="control-label" for="parameterName">Parameter Name</label>
        <input type="text" class="form-control required" id="parameterName" name="parameterName">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" id='btnAddParameter' class="btn btn-primary">Add Parameter</button>
      </div>
    </div>
  </div>
</div>

                              

<!-- Script  -->
<script src='<?php //echo base_url('application/views/admin/marketplace/services/view/details/edit.js');?>'></script>
<!-- <script src='<?php //echo base_url('edit.js');?>'></script> -->
<script>
$(document).ready(function () {
    // alert_me();
    $("#btnAddParameter").click(function() {
        var form = $("#inputs")
        // $("#inputs").each(function() {
            
        // });

        var parameter = $("#parameterName").val();
        
        window.location.replace("<?php echo base_url($source_controller);?>?editDetails=true&parameter="+parameter);
      
        
    });
});






</script>