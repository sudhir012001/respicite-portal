<body class="hold-transition login-page">

    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header mb-3" style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
      <div class="container-fluid">
        <div class="row mb-2">
            
          <div class="col-sm-6">
            <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Page Setup</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right pt-1">
              <li class="breadcrumb-item"><a href="<?php echo base_url("SpController/dashboard");?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Footer Field</li>
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
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                       src="<?php echo base_url().$user['profile_photo']; ?>"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center"></h3>

                <p class="text-muted text-center">Opening Hours</p>

              
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
            
         <form action="" enctype="multipart/form-data" method="post">    
         <div class="form-group">
         <!-- <label for="startTime">Start Day: </label> -->
         <div class="form-group clearfix">
           <div class="row">
                    <div class="col-12 mb-4">
                    <label for="startTime">Day: </label>

                    <select name="Todays_Day" class="form-control">
                     <option value="" selected>Select Day</option>   
                        <!-- <option value="Monday-Tuesday">Monday-Tuesday</option>
                        <option value="Monday-Wednesday">Monday-Wednesday </option> -->
                        <option value="Monday-Thursday">Monday-Thursday</option>
                        <!-- <option value="Monday-Friday">Monday-Friday </option> -->
                        <!-- <option value="Monday-Saturday">Monday-Saturday </option>
                        <option value="Monday-Sunday">Monday-Sunday </option> -->
                        <!-- <option value="Tuesday-Wednesday">Tuesday-Wednesday </option>
                        <option value="Tuesday-Thursday">Tuesday-Thursday</option>
                        <option value="Tuesday-Friday">Tuesday-Friday </option>
                        <option value="Tuesday-Saturday">Tuesday-Saturday </option>
                        <option value="Tuesday-Sunday">Tuesday-Sunday </option> -->
                        <!-- <option value="Tuesday-Monday">Tuesday-Monday </option> -->
                        
                        <!-- <option value="Wednesday-Thursday">Wednesday-Thursday</option>
                        <option value="Wednesday-Friday">Wednesday-Friday </option>
                        <option value="Wednesday-Saturday">Wednesday-Saturday </option>
                        <option value="Wednesday-Sunday">Wednesday-Sunday </option>
                        <option value="Wednesday-Monday">Wednesday-Monday </option>
                        <option value="Wednesday-Tuesday">Wednesday-Tuesday </option>

                        <option value="Thursday-Friday">Thursday-Friday </option>
                        <option value="Thursday-Saturday">Thursday-Saturday </option>
                        <option value="Thursday-Sunday">Thursday-Sunday </option>
                        <option value="Thursday-Monday">Thursday-Monday </option>
                        <option value="Thursday-Tuesday">Thursday-Tuesday </option>

                        
                        <option value="Friday-Saturday">Friday-Saturday </option>
                        <option value="Friday-Sunday">Friday-Sunday </option>
                        <option value="Friday-Monday">Friday-Monday </option>
                        <option value="Friday-Tuesday">Friday-Tuesday </option>
                        <option value="Friday-Wednesday">Friday-Wednesday </option>
                        <option value="Friday-Thursday">Friday-Thursday </option> -->

                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                    </div>
                  </div>
                  <!-- <div class="col-4">
                      <div class="icheck-success">
                              <input type="radio" name="radio" value="1" id="radio1">
                              <label for="radio1">Monday-Tuesday </label>
                            </div>   
                  </div>
                  <div class="col-4">
                            <div class="icheck-success">
                              <input type="radio" name="radio" value="2" id="radio2">
                              <label for="radio2">Monday-Wednesday </label>
                            </div>   
                  </div>
                  <div class="col-4">
                            <div class="icheck-success">
                              <input type="radio" name="radio" value="3" id="radio3">
                              <label for="radio3">Monday-Thursday </label>
                            </div> 
                  </div>                       
                  <div class="col-4">  
                            <div class="icheck-success">
                              <input type="radio" name="radio" value="4" id="radio4">
                              <label for="radio4">Monday-Friday </label>
                            </div> 
                  </div>                       
                  <div class="col-4"> 
                            <div class="icheck-success">
                              <input type="radio" name="radio" value="5" id="radio5">
                              <label for="radio5">Monday-Saturday </label>
                            </div>   
                  </div>                       
                  <div class="col-4"> 
                            <div class="icheck-success">
                              <input type="radio" name="radio" value="6" id="radio6">
                              <label for="radio6">Monday-Sunday </label>
                            </div>   
                  </div>   
                  </div>
                  <div class="form-group" id="level_one">
         <label for="startTime">Day: </label>
         <div class="form-group clearfix">
           <div class="row">
                  
                  
                  <div class="col-4">
                            <div class="icheck-success">
                              <input type="radio" name="radio11" value="11" id="radio11" disabled>
                              <label for="radio11">Wednesday-Thursday </label>
                            </div> 
                  </div>                       
                  <div class="col-4">  
                            <div class="icheck-success">
                              <input type="radio" name="radio11" value="12" id="radio12" disabled>
                              <label for="radio12">Wednesday-Friday </label>
                            </div> 
                  </div>                       
                  <div class="col-4"> 
                            <div class="icheck-success">
                              <input type="radio" name="radio11" value="13" id="radio13" disabled>
                              <label for="radio13">Wednesday-Saturday </label>
                            </div>   
                  </div>                       
                  <div class="col-4"> 
                            <div class="icheck-success">
                              <input type="radio" name="radio11" value="14" id="radio14" disabled>
                              <label for="radio14">Wednesday-Sunday </label>
                            </div>   
                  </div>   
                  </div>
                  <div class="form-group" id="level_two">
         <label for="startTime">Day: </label>
         <div class="form-group clearfix">
           <div class="row">
                  
                  
                  <div class="col-4">
                            <div class="icheck-success">
                              <input type="radio" name="radio" value='3' id="radio21">
                              <label for="">Wednesday-Thursday </label>
                            </div> 
                  </div>                       
                  <div class="col-4">  
                            <div class="icheck-success">
                              <input type="radio" name="radio" value='' id="radio22">
                              <label for="">Wednesday-Friday </label>
                            </div> 
                  </div>                       
                  <div class="col-4"> 
                            <div class="icheck-success">
                              <input type="radio" name="radio" value='' id="radio23">
                              <label for="">Wednesday-Saturday </label>
                            </div>   
                  </div>                       
                  <div class="col-4"> 
                            <div class="icheck-success">
                              <input type="radio" name="radio" value='' id="radio24">
                              <label for="">Wednesday-Sunday </label>
                            </div>   
                  </div>   
                  </div> -->
         <!-- <div class="form-group" id="level_one">
               
                <label for="startTime">Day: </label>
                <select name="Todays_Day">
                    <option value="" selected></option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
                <div class="em-date-range">From
                     <input class="em-date-input-loc em-date-start" type="text" name="ticket_start_pub" />
                     <input class="em-date-input" type="hidden" name="ticket_start" />
                     to
                     <input class="em-date-input-loc em-date-end" type="text" name="ticket_end_pub" />
                     <input class="em-date-input" type="hidden" name="ticket_end" />
                </div>
                </div> -->
                <div class="row">
                  <div class="col-12 mb-2">
                <div class="form-group">
                  <label for="">Status</label>
                  <select name="status" id="status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="open">Open</option>
                    <option value="close">Close</option>
                  </select>
                </div>
                </div>
                </div>
                <div class="form-group">
                <label for="startTime">Start time: </label>
                 <input type="time" name="startTime" id="startTime">
                  <!-- <p>
                      Value of the <code>time</code> input: <code>
                          "<span id="value">n/a</span>"</code>.
                         </p> -->
                         <label for="endTime">End time: </label>
                         <input type="time" name="endTime" id="endTime">
                         <!-- <label for="appt-time">Choose an appointment time: </label>
                         <input id="appt-time" type="time" name="appt-time" step="2">

                        <label for="appt-time">Choose an appointment time (opening hours 12:00 to 18:00): </label>
                        <input id="appt-time" type="time" name="appt-time"  min="12:00" max="18:00">
                        <span class="validity"></span> -->

                            <!-- <label for="exampleInputFile">Change Profile Photo</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="img" id="img">
                        <label class="custom-file-label" for="img">Choose file </label>
                      </div>
                      <div class="input-group-append">
                      <button type="submit" name="uploadbtn" id="uploadbtn" class="btn btn-primary btn-block">Upload</button>
                      </div>
                    </div>
                  </div>    
            -->
            <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              
            </div>
            <p class="invalid-feedback"><?php echo strip_tags(form_error('terms')); ?></p>
          </div>
          <!-- /.col -->
          <div class="col-4  mt-3">
            <button type="submit" name="updatebtn" class="btn btn-primary btn-block">Save</button>
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
          <!-- /.col -->
          
          <!-- /.col -->
        </div>
        <!-- /.row -->

               

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
//     $(function() {
//     var td = new Date().getDay();
//     td = (td == 0) ? 7 : td;
//     $('select[name=Todays_Day]').find('option').eq( td ).prop('selected', true)
//     .end().change();
// });
    </script>
    <script>
$(document).ready(function(){
$("input[type='radio']").change(function(){
if($(this).val()=="1")
{
// $("#level_one").show();
// alert("chala re baba");
    document.getElementById('radio11').removeAttribute("disabled");
    document.getElementById('radio12').removeAttribute("disabled");
    document.getElementById('radio13').removeAttribute("disabled");
    document.getElementById('radio14').removeAttribute("disabled");
}
if($(this).val()=="2")
{
  
    document.getElementById('radio11').removeAttribute("disabled");
    document.getElementById('radio12').removeAttribute("disabled");
    document.getElementById('radio13').removeAttribute("disabled");
    document.getElementById('radio14').removeAttribute("disabled");

}
if($(this).val()=="3")
{
  $("#level_three").show();
$("#level_two").hide();
}
});
});
</script>
  