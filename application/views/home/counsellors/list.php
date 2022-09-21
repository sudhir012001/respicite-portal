<?php
// echo "<pre>";
// print_r($seo_lists);
// echo "</pre>";
// die;

$contact_no = '9584675111';
$email = 'sales@respicite.com';
$msg = [
    'register'=>'Register as a User',
    'banner-1'=>'Our Counsellors',
    'banner-2'=>'Browse, Choose, Get Counselling'
];
$img = [
    'bg'=>'https://respicite.com/images/1920x820-2.jpg',
    'data-bg'=>'https://respicite.com/images/1920x820-2.jpg'
];

?>
<?php
 function star_rating($stars){
     $render_ster = ' <i style="color: #CFB53B;" class="fa fa-star" aria-hidden="true"></i> ';
     $output = "";
    for($i = 0; $i < $stars;$i++){
        $output .= $render_ster;
    }
    return $output;
 }
?>

<body class="" data-new-gr-c-s-check-loaded="14.1062.0" data-gr-ext-installed="">
    <div id="wrapper" class="clearfix">
        <!-- Header -->
        <header id="header" class="header">
            <div class="header-top bg-theme-colored2 sm-text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="widget text-white">
                                <ul class="list-inline xs-text-center text-white">
                                    <li class="m-0 pl-10 pr-10"> <a href="#" class="text-white"><i
                                                class="fa fa-phone text-white"></i>
                                            <?php echo $contact_no;?></a> </li>
                                    <li class="m-0 pl-10 pr-10">
                                        <a href="#" class="text-white"><i
                                                class="fa fa-envelope-o text-white mr-5"></i><?php echo $email;?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 pr-0">
                            <div class="widget">
                                <ul class="styled-icons icon-sm pull-right flip sm-pull-none sm-text-center mt-5">
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <ul class="list-inline sm-pull-none sm-text-center text-right text-white mb-sm-20 mt-10">

                                <li class="m-0 pl-0 pr-10">
                                    <a href="https://users.respicite.com/BaseController/registration/bWVyYWs="
                                        class="text-white"><i class="fa fa-edit mr-5"></i><?php echo $msg['register'];?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>            
        </header>

        <!-- Start main-content -->
        <div class="main-content">
            <!-- Section: inner-header -->
            <section class="inner-header divider layer-overlay overlay-theme-colored-7"
                data-bg-img="<?php echo $img['data-bg'];?>"
                style="background-image: url(<?php echo $img['bg'];?>);">
                <div class="container pt-120 pb-60">
                    <!-- Section Content -->
                    <div class="section-content">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="text-theme-colored2 font-36"><?php echo $msg['banner-1'];?></h2>
                                <ol class="breadcrumb text-left mt-10 white">

                                    <li><a href="#"><?php echo $msg['banner-2'];?></a></li>

                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- content #start -->

            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="sidebar sidebar-left mt-sm-30">
                                <div class="widget border">
                                   
                                    <ul class="list list-divider list-border">
                                        <li>
                                            <a class="btn btn-sm relevant-education" href="<?php echo base_url("home/counsellors");?>">Filter Clear</a>                                            
                                        </li>
                                        <li>
                                            <p>Service Name</p>
                                            <select data-name="service" class="form-control filter-event">
                                                <option value="null">--select--</option>
                                                <?php foreach($s_lists as $v){ ?>
                                                    <option value="<?php echo $v->c_group;?>" <?php echo (urldecode($this->uri->segment(3)) == $v->c_group)?"selected":"";?>><?php echo $v->c_group;?></option>                                                    
                                                <?php } ?>
                                            </select>
                                        </li>
                                        <li>
                                            <p>By City</p>
                                            <select data-name="city" class="form-control filter-event">
                                                <option value="null">--select--</option>
                                                <?php foreach($c_lists as $v){ ?>
                                                    <option value="<?php echo $v->name;?>" <?php echo (urldecode($this->uri->segment(4)) == $v->name)?"selected":"";?>><?php echo $v->name;?></option>                                                    
                                                <?php } ?>                                                
                                            </select>
                                        </li>
                                        <li>
                                            <p>Day Availibility</p>
                                            <select data-name="days" class="form-control filter-event" >
                                                <option value="null">--select--</option>
                                                <?php foreach($days as $v){ ?>
                                                    <option value="<?php echo $v->day_name;?>" <?php echo (urldecode($this->uri->segment(5)) == $v->day_name)?"selected":"";?>><?php echo $v->day_name;?></option>                                                    
                                                <?php } ?>
                                            </select>
                                        </li>
                                        <li>
                                            <p>Channel</p>
                                            <select data-name="channel" class="form-control filter-event">
                                                <option value="null">--select--</option>
                                                <?php foreach($channels_lists as $v){ ?>
                                                    <option value="<?php echo $v->name;?>" <?php echo (urldecode($this->uri->segment(6)) == $v->name)?"selected":"";?>><?php echo $v->name;?></option>                                                    
                                                <?php } ?>                                                
                                            </select>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9 blog-pull-right">
                                <?php if($seo_filter === true){
                                    echo "<p class='filter-seo-mes bg-primary'>As we could not find as per your search criteria, we are displaying all our partners.</p>";
                                }?>
                                <?php foreach($seo_lists as $v){?>
                                    <div class="course-list-block clearfix mb-30 hover-effect">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-8 border-right">
                                                    <div class="col-md-3">
                                                        <div class="pro-box">
                                                            <img src="<?php echo $v['profile_photo'];?>" class="pro-img" alt="Profile Image">
                                                            <div><p></p></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="details-box">
                                                            <a href="https://<?php echo $v['domain'];?>.respicite.com/" 
                                                            <!-- <a href="https://respicite.com/counselors-view.php?uid=<?php //echo base64_encode($v["user_id"]);?>" target="_blank"><strong><?php echo $v['fullname'];?></strong></a> 
                                                            <span>
                                                                <?php echo star_rating($v['star_rating']);?>
                                                            </span>
                                                            <p><?php echo $v["services"];?></p>
                                                            <?php for($v_i = 0; $v_i < sizeof($v["locations"]);$v_i++){
                                                               echo "<span class='border border-right'>".ucFirst($v["locations"][$v_i])."</span>";
                                                            }?>
                                                            <br>
                                                            <?php for($v_i = 0; $v_i < sizeof($v["most_relevant_education"]);$v_i++){
                                                               echo "<button class='btn btn-sm relevant-education'>".$v["most_relevant_education"][$v_i]."</button>";
                                                            }?>
                                                            <br>
                                                            Channel : <?php echo $v["channels"];?>
                                                            <br>
                                                            <span>Experience : <?php echo $v["experience"];?></span> | <span>Counselling : <?php echo $v["counselling_sessions"];?></span>
                                                            <br>
                                                            <span>Experience : <?php echo $v["experience"];?></span> | <span>Available On : <?php echo $v["available_days"];?></span>
                                                            <a href="#" class="see-more"> See More »</a> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="cart-righ-bottom-box">
                                                        <div>
                                                            <?php for($v_i = 0; $v_i < sizeof($v["top_skills"]);$v_i++){
                                                                    echo "<p>".$v["top_skills"][$v_i]."</p>";
                                                            }?>
                                                        </div>
                                                        <div>
                                                            <!-- <p class="text-right book-appoi btn-appointment" data-name="book_appointment" style="cursor:pointer" data-user-id="<?php echo $v["user_id"];?>">Book Appointment</p> -->
                                                            
                                                            <p class="btn btn-success d-block w-100 mb-5 mt-5 btn-appointment" data-name="interested_call_back" data-user-id="<?php echo $v["user_id"];?>">Interested for Call Back</p>
                                                            <p class="btn btn-primary d-block w-100 mb-5 mt-5 btn-appointment" data-name="message" data-user-id="<?php echo $v["user_id"];?>">Message</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                        </div>                        
                    </div>

                </div>
            </section>

            <!-- content #end -->
        </div>
        <!-- end main-content -->

        <!-- Footer -->
        <footer id="footer" class="footer" data-bg-color="#212331" style="background: rgb(33, 35, 49) !important;">
            <div class="container pt-70 pb-40">
                <div class="row">
                    <div class="col-sm-6 col-md-4">
                        <div class="widget dark">
                            <img class="mt-5 mb-20" alt="" src="https://respicite.com/images/logo-600x107.png">

                            <ul class="list-inline mt-5">

                                <li class="m-0 pl-10 pr-10"> <i class="fa fa-home text-theme-colored2 mr-5"></i>
                                    <a class="text-gray" href="#">
                                        Bhopal, Madhya Pradesh, India.
                                    </a>
                                </li>



                                <li class="m-0 pl-10 pr-10"> <i class="fa fa-phone text-theme-colored2 mr-5"></i> <a
                                        class="text-gray" href="#">Mobile - 9584675111</a> </li>
                                <li class="m-0 pl-10 pr-10"> <i class="fa fa-envelope-o text-theme-colored2 mr-5"></i>
                                    <a class="text-gray" href="#">sales@respicite.com</a>
                                </li>
                                <li class="m-0 pl-10 pr-10"> <i class="fa fa-globe text-theme-colored2 mr-5"></i> <a
                                        class="text-gray" href="/">respicite.com</a> </li>
                            </ul>
                            <ul class="styled-icons icon-sm icon-bordered icon-circled clearfix mt-10">
                                <li>
                                    <a href="https://www.facebook.com/respicite"><i class="fa fa-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/RespiciteL"><i class="fa fa-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/company/respicite-llp"><i
                                            class="fa fa-linkedin"></i></a>
                                </li>
                                <!--<li><a href="#"><i class="fa fa-instagram"></i></a></li>-->
                                <!--<li><a href="#"><i class="fa fa-google-plus"></i></a></li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="widget dark">
                            <h4 class="widget-title line-bottom-theme-colored-2">Useful Links</h4>
                            <ul class="angle-double-right list-border">
                                <li><a href="/">Home Page</a></li>
                                <!-- <li><a href="#">About Us</a></li> -->
                                <!-- <li><a href="#">Our Mission</a></li> -->
                                <li><a href="/terms-and-conditions.php" target="_black">Terms and Conditions</a></li>
                                <li><a href="/frequently-asked-questions.php" target="_black">FAQ</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <div class="widget dark">
                            <h4 class="widget-title line-bottom-theme-colored-2">Opening Hours</h4>
                            <div class="opening-hours">
                                <ul class="list-border">
                                    <li class="clearfix"> <span> Monday - Thursday : </span>
                                        <div class="value pull-right"> 9:30 am - 06.00 pm </div>
                                    </li>
                                    <li class="clearfix"> <span> Friday :</span>
                                        <div class="value pull-right"> 9:30 am - 06.00 pm </div>
                                    </li>
                                    <li class="clearfix"> <span> Saturday : </span>
                                        <div class="value pull-right"> 9:30 am - 06.00 pm </div>
                                    </li>
                                    <li class="clearfix"> <span> Sunday : </span>
                                        <div class="value pull-right bg-theme-colored2 text-white closed"> Closed </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </footer>
        <a class="scrollToTop" href="#" style="display: none;"><i class="fa fa-angle-up"></i></a>
    </div>
    <!-- Footer Scripts -->

    <div class="modal fade" id="appointment_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
        <div class="modal-content p-30 pt-10">
            <h3 class="text-center text-theme-colored2 mb-20 title-show">Title</h3>
            <form id="appointment_book" method="post">
            <div class="row">
                <div class="col-md-12">
                <div class="form-group">
                    <label for="name">Name<small> *</small></label>
                    <input name="name" type="text" id="name" class="form-control" required>
                    <br>
                    <label for="email">Email ID.<small> *</small></label>
                    <input name="email" type="email" class="form-control" required>
                    <br>
                    <label for="phone_no">Phone Number<small> *</small></label>
                    <input name="phone_no" type="number" class="form-control" required>
                    <br>
                    <label for="location">Location<small> *</small></label>
                    <input name="location" type="text" class="form-control" required>
                    <br>                    
                    <label for="message">Message<small> *</small></label>
                    <textarea rows="3" name="message" id="message" class="form-control" required></textarea>
                   
                    <input type="hidden" name="user_id">
                    <input type="hidden" name="action_name">
                </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                    <button type="submit" class="btn btn-block btn-sm mt-20 pt-10 pb-10 btn-submit-modal" data-loading-text="Please wait...">Book Now</button>
                    </div>
                </div>
                </div>
            </form>
        </div>
        </div>
    </div>
    
    <input type="hidden" id="segment_1" value="<?php echo $this->uri->segment(3);?>">
    <input type="hidden" id="segment_2" value="<?php echo $this->uri->segment(4);?>">
    <input type="hidden" id="segment_3" value="<?php echo $this->uri->segment(5);?>">
    <input type="hidden" id="segment_4" value="<?php echo $this->uri->segment(6);?>">
    <script>
        let segment1 = $("#segment_1").val();
        let segment2 = $("#segment_2").val();
        let segment3 = $("#segment_3").val();
        let segment4 = $("#segment_4").val();
        const BASE_URL = "<?php echo base_url();?>";
        $(document).on("change",".filter-event",function(){
            let sn = $(this).val();
            switch($(this).data("name")){
                case "service":
                    window.open(`${BASE_URL}home/service-provider/${sn}/${(segment2 != "")?segment2:null}/${(segment3 != "")?segment3:null}/${(segment4 != "")?segment4:null}`,"_self");
                break;
                case "city":
                    window.open(`${BASE_URL}home/service-provider/${(segment1 != "")?segment1:null}/${sn}/${(segment3 != "")?segment3:null}/${(segment4 != "")?segment4:null}`,"_self");
                break;
                case "days":
                    window.open(`${BASE_URL}home/service-provider/${(segment1 != "")?segment1:null}/${(segment2 != "")?segment2:null}/${sn}/${(segment4 != "")?segment4:null}`,"_self");
                break;
                case "channel":
                    window.open(`${BASE_URL}home/service-provider/${(segment1 != "")?segment1:null}/${(segment2 != "")?segment2:null}/${(segment3 != "")?segment3:null}/${sn}`,"_self");
                break;
            }
        });
    </script>

    <script>
        let ap_modal = $("#appointment_modal");
        $(document).on("click",".btn-appointment",function(){
            let _this = $(this);
            let action_name = _this.data("name");
            let user_id     = _this.data("user-id");
            let title = "";
            
            if(action_name == "book_appointment")
            {
                title = "Book Appointment";
            }else if(action_name == "interested_call_back"){
                title = "Interested Call Back";
            }else if(action_name == "message"){
                title = "Message";
            }
            ap_modal.find(".title-show").html(title);
            ap_modal.find("[name=user_id]").val(null);
            ap_modal.find("[name=user_id]").val(user_id);
            ap_modal.find("[name=action_name]").val(null);
            ap_modal.find("[name=action_name]").val(action_name);
            ap_modal.modal("show");
        });

        $("#appointment_book").submit(function(event){
            event.preventDefault();
            let formData = new FormData(event.target);
            fetch(`${BASE_URL}home/work_ajax`,{
                method:"post",
                body:formData
            })
            .then((req)=>req.json())
            .then(res=>{               
                if(res.message_type == "book_succ"){
                    event.target.reset();
                    ap_modal.modal("hide");
                    swal("Good job!", res.message, "success");
                }
            })
        });
    </script>
</body>


