<?php 
    class UserController extends CI_Controller
    {
        public function index()
        {
            redirect(base_url().'UserController/login');
        }
        // login function in this part
        public function login()
        {
            $this->load->model('User_model');
            $page = $this->input->get("page");
            $user_id = $this->input->get("uid");
            if(!empty($page)){
                $playload_ref = [
                    "user_id"=> urldecode(base64_decode($user_id)),
                    "page" => urldecode($page),
                    "condition_id" => urldecode(base64_decode($this->input->get("c_id")))
                ];
                $this->session->set_userdata("ref_data",$playload_ref);
            }
            if($this->User_model->authorized()==true)
            {
                $user = $this->session->userdata('user');
                if($user['iam']=='reseller')
                {
                    if($user['status']=='1')
                    {
                        $this->session->set_flashdata('msg','Sorry You are not approved by admin please wait for approval');
                        redirect(base_url().'UserController/login');
                    }
                    else
                    {
                        redirect(base_url().'UserController/dashboard');
                    }
                    
                }
                else if($user['iam']=='admin')
                {
                    
                    redirect(base_url().'AdminController/dashboard');
                }
                else if($user['iam']=='sp')
                {
                    
                    redirect(base_url().'SpController/dashboard');
                }
                else
                {
                   
                    redirect(base_url().'BaseController/dashboard');
                }
                
                
            }
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email','Email','required|valid_email');
            $this->form_validation->set_rules('password','Password','required');
            if($this->form_validation->run()==true)
            {
                $email = $this->input->post('email');
                $user = $this->User_model->checkUser($email);
                if(!empty($user)){
                    $password = $this->input->post('password');
                    if(password_verify($password,$user['pwd'])==true && $user['status']=='1' || password_verify($password,$user['pwd'])==true && $user['status']=='2'){
                        $sessArray['id'] = $user['id'];
                        $sessArray['fullname']=$user['fullname'];
                        $sessArray['email']=$user['email'];
                        $sessArray['mobile']=$user['mobile'];
                        $sessArray['user_id']=$user['user_id'];
                        $sessArray['iam']=$user['iam'];
                        $sessArray['profile_photo']=$user['profile_photo'];
                        //$sessArray['landing_id']=$user['landing_id'];
                        if($user['iam']=='reseller')
                        {
                            if($user['status']=='1')
                            {
                                $this->session->set_flashdata('msg','Sorry You are not approved by admin please wait for approval');
                                redirect(base_url().'UserController/login');
                            }
                            else
                            {
                                $this->session->set_userdata('user',$sessArray);
                                redirect(base_url().'UserController/dashboard');
                            }
                            
                        }
                        else if($user['iam']=='admin')
                        {
                            $this->session->set_userdata('user',$sessArray);
                            redirect(base_url().'AdminController/dashboard');
                        }
                        else if($user['iam']=='sp')
                        {
                            $this->session->set_userdata('user',$sessArray);
                            redirect(base_url().'SpController/dashboard');
                        }
                        else
                        {
                            $this->session->set_userdata('user',$sessArray);
                            redirect(base_url().'BaseController/dashboard');
                        }
                       
                    }
                    else
                    {
                        if(password_verify($password,$user['pwd'])==true && $user['status']=='0' && ($user['iam'] == "user" || $user['iam'] == "reseller" || $user['iam'] == "sp")){
                            $OTP_code = rand(1000,1000000);
                            if($this->User_model->otp_update($user['id'],$OTP_code) > 0){
                                
                                $subject = "Welcome from Respicite LLP - Verify your Email id";
                                $body_msg  = "Dear ".$user['email']." <br/> <br/> Please complete your registration with Respicite
                                by using the following OTP - <b>".$OTP_code."</b><br/>
                                <br/> Team Respicite <br/> <a href='https://respicite.com'>https://respicite.com</a> ";
                                $this->User_model->otp_send_on_email($user['email'],$subject,$body_msg);
                                $this->session->set_userdata("reverify_email_id",["email"=>$user['email'],"id"=>$user['id']]);
                                redirect(base_url().'UserController/reverify_otp');
                            } 
                        }else{
                            $this->session->set_flashdata('msg','Either email or password is incorrect, please try again');
                            redirect(base_url().'UserController/login');
                        }
                    }
                }else{
                    $this->session->set_flashdata('msg','Either email or password is incorrect, please try again');
                    redirect(base_url().'UserController/login');
                }
            }
            else
            {
                $this->load->view('login');  
            }
            
        }
        //end login function here 
        
        // start registration form
        public function registration()
        { 
            $this->load->model('User_model');
            $page = $this->input->get("page");
            $user_id = $this->input->get("uid");
            if(!empty($page)){
                $playload_ref = [
                    "user_id"=> urldecode(base64_decode($user_id)),
                    "page" => urldecode($page),
                    "condition_id" => urldecode(base64_decode($this->input->get("c_id")))
                ];
                $this->session->set_userdata("ref_data",$playload_ref);
            }

            if(isset($_POST['regbtn']))
            {
                $this->form_validation->set_rules('full_name','Fullname','required|min_length[3]');
                $this->form_validation->set_rules('email','Email','required|valid_email|is_unique[user_details.email]');
                $this->form_validation->set_rules('mobile','Mobile','required|min_length[10]');
                $this->form_validation->set_rules('role','Role','required');
                $this->form_validation->set_rules('iam','I Am','required');
                $this->form_validation->set_rules('password','Password','required|min_length[8]|max_length[25]|callback_check_strong_password');
                $this->form_validation->set_rules('terms','Terms','required');
                $this->form_validation->set_rules('cpassword','Confirm Password','required|matches[password]');
                if($this->form_validation->run() == true)
                {
                    $row = $this->db->select("*")->limit(1)->order_by('id',"DESC")->get("user_details")->row();
                    $lastid = $row->id;
                    $uid = $lastid + 1;
                    // if($_POST['iam'=='user']) 
                    if($_POST['iam'] == 'user')
                    {
                        $uid='merak';
                    }
                    $formArray = array(
                        'user_id'=>$uid,
                        'fullname'=>$_POST['full_name'],
                        'email'=>$_POST['email'],
                        'mobile'=>$_POST['mobile'],
                        'role'=>$_POST['role'],
                        'iam'=>$_POST['iam'],
                        'pwd'=> password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                        'status'=>'0',
                        'profile_photo'=>'uploads/default.png'

                    );
                    $email = $_POST['email'];
                    $this->User_model->create($formArray);
                    // if($_POST['iam']='sp')
                    if($_POST['iam'] == 'sp')
                    {
                        $formArray2 = array(
                            'email'=>$_POST['email'],
                            'about_us'=>'',
                            'key_services'=>'',
                            'address'=>'',
                            'contact'=>'',
                            'fb_url'=>'',
                            'twitter_url'=> '',
                            'insta_url'=>'',
                            'linkedin_url'=>'',
                            'heading1'=>'',
                            'content1'=>'',
                            'heading2'=>'',
                            'content2'=>'',
                            'heading3'=>'',
                            'content3'=>''
    
                        );
                        $this->db->insert('sp_profile_detail',$formArray2);
                    }
                    // $this->User_model->send_otp_in_email($email);
                    
                        $OTP_code = rand(1000,1000000);
                        if($this->User_model->otp_update_by_email($email,$OTP_code) > 0){
                            
                            $subject = "Welcome from Respicite LLP - Verify your Email id";
                            $body_msg  = "Dear ".$email." <br/> <br/> Please complete your registration with Respicite
                            by using the following OTP - <b>".$OTP_code."</b><br/>
                            <br/> Team Respicite <br/> <a href='https://respicite.com'>https://respicite.com</a> ";
                            $this->User_model->otp_send_on_email($email,$subject,$body_msg);
                            $this->session->set_userdata("verify_email",$email);
                        } 

                    redirect('/UserController/validate_otp');
                    // $this->session->set_flashdata("msg","Your account has been registered. You can login now");
                    // redirect('/UserController/registration','refresh');
                   
                }
                else
                {
                    $this->load->view('registration');  
                }
                
            }
            else
            {
                $this->load->view('registration');
            }
            
        }
        //end registration form
        public function initializer(){
            $this->load->model('User_model');
            $this->load->model('Admin_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $landing = $this->User_model->landingId($user['id']);
            //echo "<pre>";print_r($landing);die;
            $data['user'] = $user;
            $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        //echo $allowed_services;die;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            
            $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
            $data['calendly_url']='https://calendly.com/';
            return $data;
        }
        //start dashboad 
        public function dashboard() // this is reseller dashboard 
        {
        //     $this->load->model('User_model');
        //     $this->load->model('Admin_model');
        //     $this->load->model('Commen_model');
        //     if($this->User_model->authorized()==false)
        //     {
        //         $this->session->set_flashdata('msg','You are not authorized to access this section');
        //         redirect(base_url().'/UserController/login');
        //     }
        //     $user = $this->session->userdata('user');
        //     $landing = $this->User_model->landingId($user['id']);
        //     //echo "<pre>";print_r($landing);die;
        //     $data['user'] = $user;
        //     $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        // //echo $allowed_services;die;
        //     $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
        //     $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            
        //     $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
            // $data['landing']= $this->Admin_model->get_landing_data_by_user($user['landing_id']);
           // $data['landing']= $this->Admin_model->get_landing_data();
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            // die;
            $data = $this->initializer();
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data);
            $this->load->view('dashboard1');
            $this->load->view('footer');
        }
        public function book_link()
        {
            $this->load->library('form_validation');
            $this->load->model('User_model');
            $this->load->model('Admin_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        //echo $allowed_services;die;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            $landing = $this->User_model->landingId($user['id']);
            $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
            $userData['calender_link'] = $this->User_model->getcalenderlinkById($user['id']);
            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data);
            $this->load->view('book_link',$userData);
            $this->load->view('footer');
            
        }
        public function update_book_link()
        {
                $this->load->model('User_model');
                $this->form_validation->set_rules('book_link','Book Link','required');
               
                if($this->form_validation->run() == true)
                {            
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;   
                    $book_link = $_POST['book_link'];
                    $updateData= $this->User_model->book_link_update($user['id'],$book_link);
                    $this->session->set_flashdata("msg","update successfully!!");
                    redirect('/UserController/book_link','refresh');
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field Required");
                    redirect('/UserController/book_link','refresh');
                } 
        }
        public function counseling_type()
        {
            $this->load->library('form_validation');
            $this->load->model('User_model');
            $this->load->model('Admin_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        //echo $allowed_services;die;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            $landing = $this->User_model->landingId($user['id']);
            $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
            $userData['counseling_type'] = $this->User_model->getcounselingTypeById($user['id']);
            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data);
            $this->load->view('counseling_type',$userData);
            $this->load->view('footer');
            
        }
        public function update_counseling_type()
        {
                $this->load->model('User_model');
                $this->form_validation->set_rules('counseling_type','counseling type','required');
               
                if($this->form_validation->run() == true)
                {            
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;   
                    $book_links = $_POST['counseling_type'];
                    $counseling_type = $this->User_model->getcounselingTypeById($user['id']);
                    if($counseling_type!=''){
                        $book_link = $counseling_type.','.$book_links;
                    }else{
                        $book_link = $book_links;
                    }
                    $updateData= $this->User_model->counseling_type_update($user['id'],$book_link);
                    $this->session->set_flashdata("msg","update successfully!!");
                    redirect('/UserController/counseling_type','refresh');
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field Required");
                    redirect('/UserController/counseling_type','refresh');
                } 
        }
        public function counselingParameters()
        {
            
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Admin_model');
            
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        //echo $allowed_services;die;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            $landing = $this->User_model->landingId($user['id']);
            $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);

            //$this->load->model('User_model');
            
            //$metadata['landingId']= $id;
            $metadata['resellerId']= $user['id'];
            $metadata['flow']= $this->User_model->getCounselingPara($user['id']);
            $data['counseling_type'] = $this->User_model->getcounselingTypeById($user['id']);
            
            //$metadata['landing_page_section']= $this->User_model->get_landing_section_via_data($id);
            //$metadata['landing_page_details']= $this->User_model->get_landing_details_section_via_data($id);
            //create view path
            $controller_name = debug_backtrace();
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data);
            $this->load->view('counseling_parameters_list',$metadata);
            $this->load->view('footer');
        }
         public function addCounselingPara()
        {
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            $this->form_validation->set_rules('para_name','Parameter Name','required');
            if($this->form_validation->run()== true)
            {
                $para_name = $this->input->post('para_name',TRUE);
                $counseling_type = $this->input->post('counseling_type',TRUE);
                $resellerId = $this->input->post('resellerId',TRUE);
                $mrp = $this->input->post('mrp',TRUE);
                $duration = $this->input->post('duration',TRUE);
                $data=[];
                $data['name']=  $para_name;
                $data['type']=  $counseling_type;
                $data['duration']=  $duration;
                $data['resellerId']= $resellerId;
                $data['mrp']= $mrp;

                $this->load->model('User_model');
                $result = $this->User_model->insert_counseling_para_data($data);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                    redirect('/UserController/counselingParameters/','refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                         redirect('/UserController/counselingParameters/','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                 redirect('/UserController/counselingParameters/','refresh');
            }

        }
        //end dashboard function
        
        //logout function
        public function logout()
        {
            $this->session->unset_userdata('user');
            $this->session->unset_userdata('ref_data');
            redirect(base_url().'/UserController/login');
        }
        // end logout function

        public function change_password()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data = $this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            
    
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('change_password',$data); 
            $this->load->view('footer'); 

            if(isset($_POST['changepwd']))
            {
                $old_pwd = $this->input->post('old_psw');
                $user = $this->User_model->checkUser($email);
                if(password_verify($old_pwd,$user['pwd'])==true)
                {
                    $this->load->library('form_validation');
                    $this->form_validation->set_rules('npsw','Password','required|min_length[8]|max_length[25]|callback_check_strong_password');
                    $this->form_validation->set_rules('cnpsw','Confirm Password','required|matches[npsw]');
                    if($this->form_validation->run() == true)
                    {                        
                       
                        $this->db->set('pwd',password_hash($this->input->post('npsw'),PASSWORD_BCRYPT));
                        $this->db->where('email',$email);
                        $this->db->update('user_details');
                        $this->session->set_flashdata('msg2','Password Changed Successfully');
                        redirect(base_url().'/UserController/change_password');
                    
                    }
                    else
                    {
                        $this->session->set_flashdata('msg','Please Check Password and Confirm Password are same or not');
                        redirect(base_url().'/UserController/change_password');
                    }
                    
                }
                else
                {
                    $this->session->set_flashdata('msg','Wrong OLD Password');
                    redirect(base_url().'/UserController/change_password');
                   
                }
                
            }
        }
        // password checking is this strong or not
        public function check_strong_password($str)
        {
            if (preg_match('#[0-9]#', $str) && preg_match('#[a-z]#', $str) && preg_match('#[A-Z]#', $str)) {
            return TRUE;
            }
            $this->form_validation->set_message('check_strong_password', 'The password field must be contains at least one capital letter, one small letter, and one digit.');
            return FALSE;
        }

        //function for validate otp 
        // public function validate_otp(){
        //     $this->load->helper('cookie');
        //     $this->load->view('validate-otp');
        //     $this->load->model('User_model');
        //     if(isset($_POST['validate-btn']))
        //     {
        //         $rotp = get_cookie('today_get_otp');
        //         $email = get_cookie('my_email_today');
        //         $otp = $_POST['otp'];
                
        //         if($rotp==$otp)
        //         {
        //             $this->User_model->update_status($email);
        //             $this->session->set_flashdata("msg2","Your account has been registered. You can login now");
        //             redirect('/UserController/login','refresh');
        //         }
        //         else
        //         {
        //             $this->session->set_flashdata("msg","Wrong OTP");
        //             redirect('/UserController/validate_otp');
        //         }
        //     }
        // } //Old

        public function validate_otp(){           
            
            $this->load->model('User_model');
            if(isset($_POST['validate-btn']))
            {
                if(!empty($this->input->post("otp"))){
                    if($this->User_model->check_otp_email_reverify($this->session->userdata("verify_email"),$this->input->post("otp")))
                    {
                        $this->session->set_flashdata("msg2","Your account has been registered. You can login now");
                        redirect('/UserController/login','refresh');
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Wrong OTP");
                        redirect('/UserController/validate_otp');
                    }
                }else{
                    $this->session->set_flashdata("msg","OTP is empty");
                }
            }
            $this->load->view('validate-otp');
        }

        //define function for purchase code by reseller 
        public function purchase_code()
        {
           
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            $data = $this->initializer();
            $solution['s'] = $this->User_model->solutions_list();
            
    
            $this->load->view('navbar',$data);
            $iam = $user['iam'];
            if($iam=='sp')
            {
                $this->load->view('sp/sidebar',$data); 
            }
            else
            {
                $this->load->view('sidebar',$data); 
            }
            
            $this->load->view('purchasecode',$solution,$data); 
            $this->load->view('footer'); 

            
        }

        public function save_request_code()
        {
            if(isset($_POST['purchase']))
            {   
                error_reporting(0);
                $count = $this->db->get('solutions')->num_rows();
                for($i=0;$i<=$count;$i++)
                {
                    if($i==0)
                    {
                        $op[] = '';
                        //nothing
                    }
                    else
                    {
                        $code = 'ccb'.$i;
                        $op[] = $_POST[$code];
                        if($op[$i]!='')
                        {
                            $value = '1';
                        }
                    }
                    
                }
                $user = $this->session->userdata('user');
                $email = $user['email'];
                $status = 'pending';
                // $id = 'ccb1';
                // $op[] = $_POST[$id]; 
                // $op[] = $_POST['ccb2'];
                // $op[] = $_POST['ccb3'];
                // $op[] = $_POST['ccb4'];
                if($value!='1')
                {
                    echo $value;
                    $this->session->set_flashdata("msg","Please Select One of these");
                    redirect('/UserController/purchase_code','refresh');
                }
                else
                {
                    $this->load->helper('date');
                    $datestring = '%d / %m / %Y - %h:%i %a';
                    $time = time();
                    $dt = mdate($datestring, $time);
                    for($i=1;$i<=$count;$i++)
                    {
                        $code_id = $op[$i];
                        if($code_id!='')
                        {
                                $rq = 'cb'.$i;
                                $cb = $_POST[$rq];
                                
                                if($cb=='')
                                {
                                    $rqst = '';
                                    
                                }
                                else
                                {
                                    $rqst = 'Yes';
                                }
                                $data = array(
                                    'dt' => $dt,
                                    'code' => $code_id,
                                    'email' => $email,
                                    'request'=> $rqst,
                                    'status' => $status
                                );
                                
                                $this->load->model('User_model');
                                $this->User_model->insert_request_code($data);
                        }
                            
                            

                            
                    }
                    $this->session->set_flashdata("msg","Purchase Request has been sent to admin please wait for approval");
                    redirect('/UserController/purchase_code','refresh');
                }
                    
            }
        }
        
    
        //show unused code
        public function unused_code()
        {
            // $this->load->model('User_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data = $this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
           
            $unused_code_list['h'] = $this->User_model->get_unused_code($email);

            $this->load->view('navbar',$data);
             $iam = $user['iam'];
            if($iam=='sp')
            {
                $this->load->view('sp/sidebar',$data); 
            }
            else
            {
                $this->load->view('sidebar',$data); 
            }
            $this->load->view('unused_code_list',$unused_code_list); 
            $this->load->view('footer');  
        }

        //view reseller profile
        public function view_reseller_profile()
        {
            // $this->load->model('User_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data = $this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('reseller_profile',$data); 
            $this->load->view('footer');
        }
        public function edit_reseller_profile()
        {

            // $this->load->model('User_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data = $this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email=$user['email'];
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('edit_reseller_profile',$data); 
            $this->load->view('footer'); 

            if(isset($_POST['updatebtn']))
            {
                $this->form_validation->set_rules('full_name','Fullname|min_length[3]','required');
                $this->form_validation->set_rules('mobile','Mobile','required|min_length[10]');
                if($this->form_validation->run() == true)
                {   
                    $this->db->set('fullname',$_POST['full_name']);
                    $this->db->set('mobile',$_POST['mobile']);
                    $this->db->where('email',$email);
                    $this->db->update('user_details');

                    $sessArray['id'] = $user['id'];
                    $sessArray['fullname']=$_POST['full_name'];
                    $sessArray['email']=$user['email'];
                    $sessArray['mobile']=$_POST['mobile'];
                    $sessArray['user_id']=$user['user_id'];
                    $sessArray['profile_photo']=$user['profile_photo'];
                    $sessArray['iam']=$user['iam'];
                    $this->session->set_userdata('user',$sessArray);
                    $this->session->set_flashdata('msg2','Detail Updated');
                    redirect('/UserController/edit_reseller_profile','refresh');
                }
            }
        }

        public function edit_profile_photo()
        {
            $this->load->model('User_model');
                if($this->User_model->authorized()==false)
                {
                    $this->session->set_flashdata('msg','You are not authorized to access this section');
                    redirect(base_url().'/UserController/login');
                }
                $user = $this->session->userdata('user');
                $data['user'] = $user;
                $email=$user['email'];
                $this->load->view('navbar',$data);
                $this->load->view('sidebar'); 
                $this->load->view('edit_profile_photo',$data); 
                $this->load->view('footer'); 
                
                
        }
    
        public function do_upload()
        {
               
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'jpg|png';
                $config['max_size'] = 100000;
    
                $new_name = time().'.jpg';
                $config['file_name'] = $new_name;         
                $this->load->library('upload', $config);
    
                
                if ( ! $this->upload->do_upload('img'))
                {
                        $error = array('error' => $this->upload->display_errors());
                        foreach($error as $error)
                        {
                            $this->session->set_flashdata('msg','$error');
                            redirect('/UserController/edit_reseller_profile','refresh');
                        }
                       
                }
                else
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $email=$user['email'];
                    $this->db->where('email',$email);
                    $this->db->set('profile_photo','uploads/'.$new_name);
                    $this->db->update('user_details');
    
                    $sessArray['id'] = $user['id'];
                    $sessArray['fullname']=$user['fullname'];
                    $sessArray['email']=$user['email'];
                    $sessArray['mobile']=$user['mobile'];
                    $sessArray['user_id']=$user['user_id'];
                    $sessArray['iam']=$user['iam'];
                    $sessArray['profile_photo']='uploads/'.$new_name;
                    $this->session->set_userdata('user',$sessArray);
                    $this->session->set_flashdata('msg2','Profile Updated');
                    redirect('/UserController/edit_reseller_profile','refresh');
                }
        }
    
        public function sign_upload()
        {
               
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|png';
            $config['max_size'] = 100000;
            $config['max_width'] = 1903;
            $config['max_height'] = 882;
            $dname = explode(".", $_FILES['img']['name']);
            $ext = end($dname);
            $new_name = time().'.'.$ext;
           
            
            $config['file_name'] = $new_name;
            $this->load->library('upload', $config);
                
                if ( ! $this->upload->do_upload('img'))
                {
                        $error = array('error' => $this->upload->display_errors());
                        foreach($error as $error)
                        {
                            $this->session->set_flashdata('msg','$error');
                            redirect('/UserController/edit_reseller_profile','refresh');
                        }
                       
                }
                else
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $email=$user['email'];
                    $this->db->where('r_email',$email);
                    $this->db->set('reseller_signature','uploads/'.$new_name);
                    $this->db->update('reseller_homepage');
    
                   
                    $this->session->set_flashdata('msg2','Signature Updated');
                    redirect('/UserController/edit_reseller_profile','refresh');
                }
        }

        public function view_reseller_code()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data = $this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            $solution['s'] = $this->User_model->solutions_list();
            
            
            $this->load->view('navbar',$data);
            $iam = $user['iam'];
            if($iam=='sp')
            {
                $this->load->view('sp/sidebar',$data); 
            }
            else
            {
                $this->load->view('sidebar',$data); 
            }
            $this->load->view('view_code_list',$solution,$data); 
            $this->load->view('footer'); 
        }

        public function view_users_list()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data = $this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            

            $this->load->view('navbar',$data);
             $iam = $user['iam'];
            if($iam=='sp')
            {
                $this->load->view('sp/sidebar',$data); 
            }
            else
            {
                $this->load->view('sidebar',$data); 
            } 
            $this->load->view('view_users',$data); 
            $this->load->view('footer');
            
        }
        
        public function page_change_logo()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data = $this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            
            
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('page_change_logo',$data); 
            $this->load->view('footer'); 
            
            if(isset($_POST['savebtn']))
            {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'jpg|png';
                $config['max_size'] = 100000;
                $config['max_width'] = 900;
                $config['max_height'] = 512;
                $dname = explode(".", $_FILES['img']['name']);
                $ext = end($dname);
                $new_name = time().'.'.$ext;
               
                
                $config['file_name'] = $new_name;
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('img'))
                {
                   
                        $error = array('error' => $this->upload->display_errors());
                        foreach($error as $error)
                        {
                            $this->session->set_flashdata('msg',$error);
                            redirect('/UserController/page_change_logo','refresh');
                        }
                       
                }
                else
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('r_email',$uid);
                    $this->db->set('logo','uploads/'.$new_name);
                    $this->db->update('reseller_homepage');
                    
                    $this->session->set_flashdata('msg2','Saved Successfully');
                    redirect('/UserController/page_change_logo','refresh');
                }
            }
        }

        public function page_change_banner()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data = $this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            

            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('change_banner_details',$data); 
            $this->load->view('footer'); 
            
            if(isset($_POST['savebtn']))
            {
                $this->form_validation->set_rules('ban_head','Banner Heading','required');
                $this->form_validation->set_rules('ban_msg','Banner Message','required');
                $this->form_validation->set_rules('about','About','required');
               
                if($this->form_validation->run() == true)
                {
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'jpg|png';
                    $config['max_size'] = 100000;
                    $config['max_width'] = 1903;
                    $config['max_height'] = 882;
                    $dname = explode(".", $_FILES['ban_img']['name']);
                    $ext = end($dname);
                    $new_name = time().'.'.$ext;
                   
                    
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('ban_img'))
                    {
                            $error = array('error' => $this->upload->display_errors());
                            foreach($error as $error)
                            {
                                $this->session->set_flashdata('msg',$error);
                                redirect('/UserController/page_change_banner','refresh');
                            }
                        
                    }
                    else
                    {
                        $user = $this->session->userdata('user');
                        $data['user'] = $user;
                        $uid=$user['email'];
                        $this->db->where('r_email',$uid);
                        $this->db->set('banner_img','uploads/'.$new_name);
                        $this->db->set('banner_head',$_POST['ban_head']);
                        $this->db->set('banner_msg',$_POST['ban_msg']);
                        $this->db->set('about_us',$_POST['about']);
                        $this->db->update('reseller_homepage');
        
                        $this->session->set_flashdata('msg2','Detail Updated');
                        redirect('/UserController/page_change_banner','refresh');
                    }              
                        
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/UserController/page_change_banner','refresh');
                }
             
            }
        }

        public function change_contact_details()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            

            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('change_contact_details',$data); 
            $this->load->view('footer'); 
            
            if(isset($_POST['savebtn']))
            {
                $this->form_validation->set_rules('email','Email','required|valid_email');
                $this->form_validation->set_rules('addr','Address','required');
                $this->form_validation->set_rules('mobile','Mobile','required|min_length[10]|max_length[10]');
               
                if($this->form_validation->run() == true)
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('r_email',$uid);
                    $this->db->set('email',$_POST['email']);
                    $this->db->set('address',$_POST['addr']);
                   
                    $this->db->set('contact',$_POST['mobile']);
                    $this->db->update('reseller_homepage');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/UserController/change_contact_details','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/UserController/change_contact_details','refresh');
                }
            }
        }
        public function change_social_link()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            

            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('change_social_link',$data); 
            $this->load->view('footer'); 
            
            if(isset($_POST['savebtn']))
            {
                
                
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    // var_dump($data);die;
                    $uid=$user['email'];
                    $this->db->where('r_email',$uid);
                    $this->db->set('fb_url',$_POST['fb_link']);
                    $this->db->set('twt_url',$_POST['twt_link']);
                    $this->db->set('link_url',$_POST['link_link']);
                    $this->db->set('insta_url',$_POST['insta_link']);
                    $this->db->set('ftr',$_POST['footer']);
                    $this->db->update('reseller_homepage');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/UserController/change_social_link','refresh');
                    
                
            }
        }
        public function company_detail()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            

            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('company_detail',$data); 
            $this->load->view('footer'); 
            
            if(isset($_POST['savebtn']))
            {
                
                
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('r_email',$uid);
                    $this->db->set('company_head1',$_POST['head1']);
                    $this->db->set('company_head2',$_POST['head2']);
                    $this->db->set('company_head3',$_POST['head3']);
                    $this->db->set('company_detail1',$_POST['detail1']);
                    $this->db->set('company_detail2',$_POST['detail2']);
                    $this->db->set('company_detail3',$_POST['detail3']);
                    $this->db->update('reseller_homepage');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/UserController/company_detail','refresh');
                    
                
            }
        }
        
        public function domain_request()
        {
            $this->load->library('form_validation');
            $this->load->model('User_model');
            $this->load->model('Admin_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            
            $this->load->view('navbar',$data);
             $iam = $user['iam'];
            if($iam=='sp')
            {
                $this->load->view('sp/sidebar'); 
            }
            else
            {
                $this->load->view('sidebar'); 
            }
            $this->load->view('domain_request');
            $this->load->view('footer');
            if (isset($_POST['request_btn']))
            {
                
                $this->form_validation->set_rules('domain_name','Domain Name','required');
               
                if($this->form_validation->run() == true)
                {
                    $domain_name= $_POST['domain_name'];
                    $count = $this->User_model->check_duplicacy($domain_name);
                    if($count==0)
                    {
                        $formArray = array(
                            'reseller_id' => $_POST['domain_name'],
                            'r_email' => $user['email'],
                            'logo' => '',
                            'banner_img'=> '',
                            'banner_head' => '',
                            'banner_msg' => '',
                            'about_us' => '',
                            'email' => '',
                            'address' => '',
                            'contact' => '',
                            'fb_url' => '',
                            'twt_url' => '',
                            'ftr' => '',
                            'status' => '0'
                        );
                        $this->User_model->homepage_insertion($formArray);
                       
                        $this->session->set_flashdata("msg2","Saved Successfully");
                        redirect('/UserController/login','refresh');

                    }
                    else
                    {
                        $this->session->set_flashdata("msg","This Domain Name is Already Exist");
                        redirect('/UserController/domain_request','refresh');

                    }
                    
                    
                }
                else{
                    $this->session->set_flashdata("msg",form_error('domain_name'));
                    redirect('/UserController/domain_request','refresh');
                }
            
            }
        }

        public function approve_user_code()
        {
            // echo "here<br>";die;
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data = $this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            

            $this->load->view('navbar',$data);
            $iam = $user['iam'];
            // echo $iam;die;
            if($iam=='sp')
            {
                $this->load->view('sp/sidebar',$data); 
            }
            else
            {
                $this->load->view('sidebar',$data); 
            }
            $this->load->view('unapprove_user_code',$data); 
            $this->load->view('footer'); 
                      
        }
        public function view_counseling($counselling_link='https://calendly.com/iquery-demo')
        {
            $this->load->model('User_model');
            $this->load->model('Admin_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $allowed_services['allowed_services'] = $this->Admin_model->getUserDetailsById($$user['id']);
            $data['counselling_link'] = $counselling_link;
            $email = $user['email'];
            

            $this->load->view('navbar',$data);
            $iam = $user['iam'];
            if($iam=='sp')
            {
                $this->load->view('sp/sidebar',$allowed_services); 
            }
            else
            {
                $this->load->view('sidebar',$allowed_services); 
            }
            $this->load->view('view_counseling',$data); 
            $this->load->view('footer'); 
                      
        }
        public function code_approvel_for_user($id,$solution)
        {
            
            $user = $this->session->userdata('user');
            $email = $user['email'];
            //echo $email.$solution;die();
            $where = "email='$email' and solution='$solution' and status='UnUsed'";
            $this->db->where($where);
            $num_row = $this->db->get('generated_code_details')->num_rows();
            
            //  echo "No of rows fetched :".$num_row."<br>";
            //  die();
            //echo "No of Rows ".$num_row;
            if($num_row>0)
            {
                $where = "email='$email' and solution='$solution' and status='UnUsed'";
                $this->db->where($where);
                $row = $this->db->limit(1)->get('generated_code_details');
                foreach($row->result() as $row)
                {
                    $code = $row->code;
                    
                    $this->db->where('id',$id);
                    $this->db->set('code',$code);
                    $this->db->set('status','Ap');
                    $this->db->update('user_code_list');
                }
                if($this->db->where('code',$code)->set('status','allocated')->update('generated_code_details'))
                {
                        $this->db->where('code',$code);
                        $query = $this->db->get('user_code_list');
                        $ret = $query->row();
                        $user_id = $ret->user_id;
                        $this->db->where('solution',$solution);
                        $sl = $this->db->get('services_list');
                        foreach($sl->result() as $sl)
                        {
                            $formArray = array(
                                'user_id'=>$user_id,
                                'code'=>$code,
                                'solution'=>$sl->solution,
                                'dis_solution'=>$sl->dorp,
                                'part'=>$sl->part,
                                'link'=>$sl->current_link,
                                'details'=>$sl->details,
                                'status'=>'Ap',
                                'remain_time'=>$sl->duration
                            );
                            $this->db->insert('user_assessment_info',$formArray);
                        }
                        $this->session->set_flashdata("msg","Code Approved");
                        redirect('/UserController/approve_user_code','refresh');                  
                }
                
            }else{
                $this->session->set_flashdata("check_code_available","You have no more codes, Please purchase new codes.");
            }
        }
        public function user_buyer()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // $this->load->model('Base_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data = $this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            
            $code['s'] = $this->User_model->code_list($user['email']);

            $this->load->view('navbar',$data);
            $iam = $user['iam'];
            if($iam=='sp')
            {
                $this->load->view('sp/sidebar',$data); 
            }
            else
            {
                $this->load->view('sidebar',$data); 
            } 
            $this->load->view('view_approve_user_code',$code); 
            $this->load->view('footer'); 
        }
        public function update_code_status()
        {
            
            $id = $this->input->post('s');
			$status = $this->input->post('act');
           
                $this->db->where('id',$id);
                $this->db->set('status',$status);
                $this->db->update('user_code_list');
           
                $data = array('responce'=>'success','message'=>'Data update Successfully');
                echo json_encode($data);
        }
        public function certification()
        {
            // $this->load->model('User_model');
            // $this->load->model('Base_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            
            $code['s'] = $this->User_model->certification_list($user['email']);
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('certification_test',$code); //replace with view_approve_code 
            $this->load->view('footer');          
        }
        //certification test
        public function certification_test()
        {
            
            $this->load->model('User_model');
            $this->load->model('Base_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            $where = "email='$email' and solution='Positive Parenting'";
            $this->db->where($where);
            $qno = $this->db->get('certification_test_result')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('certification_test_result'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->User_model->ppe_test($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('sidebar'); 
            $this->load->view('ppe_test',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=66)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $c_ans = 'right_ans'.$i;
                                if($_POST[$ans]==$_POST[$c_ans])
                                {
                                    $score = 1;
                                }
                                else
                                {
                                    $score = 0;
                                }
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'Positive Parenting',
                                    'put_ans'=>$_POST[$ans],
                                    'score'=>$score
                                );
                                $this->db->insert('certification_test_result',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'UserController/certification_test');
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'UserController/certification_test'); 
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $c_ans = 'right_ans'.$i;
                                if($_POST[$ans]==$_POST[$c_ans])
                                {
                                    $score = 1;
                                }
                                else
                                {
                                    $score = 0;
                                }
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'Positive Parenting',
                                    'put_ans'=>$_POST[$ans],
                                    'score'=>$score
                                );
                                $this->db->insert('certification_test_result',$formArray);
                            $i++;
                            
                        }
                        $dt = date('d-m-Y');
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where2 = "email='$email' and solution='Positive Parenting' and score='1'";
                        $this->db->where($where2);
                        $scr = $this->db->get('certification_test_result')->num_rows();
                        $per = ($scr*100)/66;
                        $where = "email='$email' and solution='PPE'";
                        $this->db->where($where);
                        $this->db->set('status','2');
                        $this->db->set('per',$per);
                        $this->db->set('dt',$dt);
                        $this->db->update('reseller_certification');
                        $this->session->set_flashdata('msg','Test Completed Please Download Certificate');
                        redirect(base_url().'UserController/certification');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'UserController/certification_test');   
                    }
                    
                   
                }
                
            }
            
        }
        public function request_for_retest($id)
        {
            $this->db->where('id',$id);
            $this->db->set('rqst','1');
            $this->db->update('reseller_certification');
            $this->session->set_flashdata('msg  ','Request send successfully Please wait for approval.');
            redirect(base_url().'UserController/certification');
        }
        public function counselingUpdate()
        {
            $this->load->model('User_model');
                if($this->User_model->authorized()==false)
                {
                    $this->session->set_flashdata('msg','You are not authorized to access this section');
                    redirect(base_url().'/UserController/login');
                }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $this->load->library('form_validation');
            $this->form_validation->set_rules('para_name','Parameter Name','required');
            if($this->form_validation->run()== true)
            {
                $para_name = $this->input->post('para_name',TRUE);
                $counseling_type = $this->input->post('counseling_type',TRUE);
                $id = $this->input->post('id',TRUE);
                $mrp = $this->input->post('mrp',TRUE);
                $duration = $this->input->post('duration',TRUE);
                $update=$this->User_model->counselingUpdate($id,$para_name,$counseling_type,$mrp,$duration);
                if($update){
                $this->session->set_flashdata('msg  ','updated successfully');
                redirect(base_url().'UserController/counselingParameters','refresh');
                }else{
                    $this->session->set_flashdata('msg  ','something wrong');
                redirect(base_url().'UserController/counselingParameters','refresh');
                }
            }else{
                $this->session->set_flashdata('msg  ','all Field Required');
                redirect(base_url().'UserController/counselingParameters','refresh');
            }
        }
        public function footer_field()
        {
            // $this->load->model('User_model');
            //     if($this->User_model->authorized()==false)
            //     {
            //         $this->session->set_flashdata('msg','You are not authorized to access this section');
            //         redirect(base_url().'/UserController/login');
            //     }
                $data =$this->initializer();
                $user = $this->session->userdata('user');
                $data['user'] = $user;
                $email=$user['email'];
                $this->load->view('navbar',$data);
                $this->load->view('sidebar',$data); 
                $this->load->view('footer_field',$data); 
                $this->load->view('footer'); 

                if(isset($_POST['updatebtn']))
                {
                    $this->form_validation->set_rules('Todays_Day','Days','required');
                    $this->form_validation->set_rules('status','Staus','required');
                    if($this->form_validation->run()==true)
                    {
                        if($_POST['status']=='open')
                        {
                            if($_POST['startTime']!='' && $_POST['endTime']!='')
                            {
                                $formArray = Array(
                                    'email'=>$email,
                                    'day'=>$_POST['Todays_Day'],
                                    'status'=>$_POST['status'],
                                    'start_tm'=>$_POST['startTime'],
                                    'end_tm'=>$_POST['endTime']
                                );
                            }
                            else
                            {
                                $this->session->set_flashdata('msg','Start Time or End Time both are required');
                                redirect(base_url().'/UserController/footer_field');
                            }
                        }
                        else
                        {
                            $formArray = Array(
                                'email'=>$email,
                                'day'=>$_POST['Todays_Day'],
                                'status'=>$_POST['status'],
                                'start_tm'=>$_POST['startTime'],
                                'end_tm'=>$_POST['endTime']
                            );
                        }

                        
                        $this->db->insert('day_description',$formArray);
                        $this->session->set_flashdata('msg2','Saved');
                        redirect(base_url().'/UserController/footer_field');
                    }
                    
                }
                
            
        }
        
        public function Update_Counsellor_remarks($code)
        {
            $code = base64_decode($code);
            $this->load->model('User_model');
            $this->load->model('Base_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            
            $this->load->view('navbar',$data);
            $this->load->view('sidebar'); 
            $this->load->view('Update_Counsellor_remarks',$code); //replace with view_approve_code 
            $this->load->view('footer');   
            
            
            
        }
        public function SaveCounsellorRemarks()
        {
            $this->form_validation->set_rules('remarks','Remarks','required');
                if($this->form_validation->run()==true)
                {
                    $dt = date("d-m-Y h:m"); 
                    $this->db->where('code',$_POST['code']);
                    $this->db->set('c_remark',$_POST['remarks']);
                    $this->db->set('remark_update_last',$dt);
                    $this->db->update('user_code_list');
                    $this->session->set_flashdata('msg','Detail Updated');
                    redirect(base_url().'/UserController/user_buyer');
                }
                else
                {
                    $this->session->set_flashdata('msg','Error');
                    redirect(base_url().'/UserController/Update_Counsellor_remarks/'.base64_encode($_POST['code']));
                }
        }

        public function forgot_password(){           
            $this->load->view('forgot_password');
        } 

        public function ajax_work(){
            $res_msg['msg_code'] = "OPPs";          
            $this->load->model('User_model');
            switch($this->input->get_post('action')){
                case "FORGOT_PASSWORD":
                    $email_id =  $this->security->xss_clean($this->input->post("email_id"));
                    $check_email_id = $this->User_model->check_email_id($email_id);
                    if($check_email_id['num_rows'] > 0){  
                    $OTP_code = rand(1000,1000000);
                        if($this->User_model->otp_update($check_email_id['db_data']->id,$OTP_code) > 0){
                            $subject = "Hello from Respicite - Password Reset Request.";
                            $html_msg = "Dear $email_id <br> <br>
                            Please complete your password reset request by using the following OTP - $OTP_code
                            <br><br> Team Respicite <br> <a href='https://respicite.com'>https://respicite.com</a>";
                            $this->User_model->otp_send_on_email($email_id,$subject,$html_msg);
                            $res_msg['msg_code'] = "SEND_OPT";
                        } 
                    }else{                
                        $res_msg['msg_code'] = "USER_NOT_FOUND";
                    }
                break;
                case "NEW_PASSWORD":
                    
                    $this->form_validation->set_rules('new_pass','New Password','required|min_length[8]|max_length[25]|callback_check_strong_password');
                    $this->form_validation->set_rules('otp_code','OTP','required');
                    $this->form_validation->set_rules('email_id','Email','required');

                    if($this->form_validation->run())
                    {
                        $post_data = [
                            "otp_code"=>$this->input->post("otp_code",true),
                            "new_pass"=>password_hash($this->input->post("new_pass",true),PASSWORD_BCRYPT),
                            "email_id"=>$this->input->post("email_id",true)
                         ];
                         if($this->User_model->check_otp_email($post_data)){
                            $res_msg['msg_code'] = "UPDATE_DONE";
                         }else{
                            $res_msg['msg_code'] = "OTP_INVALID";
                         }
                        
                    }else{
                        $res_msg['msg_code'] = "ERROR_VALIDATION";
                        $res_msg['msg_content'] = validation_errors();
                    }


                break;               
                case "RESEND_OTP":
                    $email_id = $this->input->post("email_id",true);
                    $check_user_status = $this->User_model->check_user_status($email_id);
                    if($check_user_status['num_rows'] > 0){
                        $OTP_code = rand(1000,1000000);
                        $this->User_model->otp_update($check_user_status['id'],$OTP_code);

                        $subject = "Welcome from Respicite LLP - Verify your Email id";
                        $body_msg  = "Dear ".$email_id." <br/> <br/> Please complete your registration with Respicite
                        by using the following OTP - <b>".$OTP_code."</b><br/>
                        <br/> Team Respicite <br/> <a href='https://respicite.com'>https://respicite.com</a> ";

                        $this->User_model->otp_send_on_email($email_id,$subject,$body_msg);
                        $res_msg['msg_code'] = "OTP_RESEND";
                    }else{
                        $res_msg['msg_code'] = "ALREADY_UPDATE_STATUS";
                    }
                break;
                case "JOB_STATUS":
                    $check = null;
                    $check = $this->Sp_model->update_status($action,$id);
                    if($check){
                        $json_msg = ["MSG"=>"OK"];
                    }else{
                        $json_msg = ["MSG"=>"ERROR"];
                    }
                break;
            }
            
            $this->output->set_content_type("application/json")->set_output(json_encode($res_msg));
        }

        function reverify_otp(){
           
            if(empty($this->session->userdata("reverify_email_id"))){
                redirect(base_url().'/UserController/login');
            }
            $this->load->model('User_model');
            $this->form_validation->set_rules('otp','OTP','required');
            if($this->form_validation->run()){
               if($this->User_model->check_otp_email_reverify($this->session->userdata("reverify_email_id")['email'],$this->input->post("otp"))){
                    $this->session->set_flashdata("otp_msg","OK");
                    $this->session->unset_userdata('reverify_email_id');
                }else{
                   $this->session->set_flashdata("otp_msg","INVELID_OTP");
               }
            }

            $this->load->view('revalidate-otp');

        }

        public function boards()
        {
                // $this->load->model('User_model');
                // if($this->User_model->authorized()==false)
                // {
                //     $this->session->set_flashdata('msg','You are not authorized to access this section');
                //     redirect(base_url().'/UserController/login');
                // }
                $data =$this->initializer();
                $user = $this->session->userdata('user');
                $data['user'] = $user;            
                $this->load->view('navbar',$data);
                $this->load->view('sidebar',$data); 
                $this->load->view('boards'); 
                $this->load->view('footer');
        }
    
        public function career_paths()
        {
                // $this->load->model('User_model');
                // if($this->User_model->authorized()==false)
                // {
                //     $this->session->set_flashdata('msg','You are not authorized to access this section');
                //     redirect(base_url().'/UserController/login');
                // }
                $data =$this->initializer();
                $user = $this->session->userdata('user');
                $data['user'] = $user;            
                $this->load->view('navbar',$data);
                $this->load->view('sidebar',$data); 
                $this->load->view('career-paths'); 
                $this->load->view('footer');
        }

        public function vocational_training(){
            $data_view = [];
            // $this->load->model('User_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data_view['vocational_info'] = $this->get_vocational_info("SHORT_VIEW",$user['id']);
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('vocational_training',$data_view); 
            $this->load->view('footer');
        }

        public function vocational_training_add()
        {
                $data_view = [];
                // $this->load->model('User_model');
                // if($this->User_model->authorized()==false)
                // {
                //     $this->session->set_flashdata('msg','You are not authorized to access this section');
                //     redirect(base_url().'/UserController/login');
                // }
                $data =$this->initializer();
                $user = $this->session->userdata('user');
                $data['user'] = $user;

                if(isset($_POST['btn_vocational']))
                {
                    $this->form_validation->set_rules('vocational_name','Name','required');
                    $this->form_validation->set_rules('vocational_description','Description','required');
                    if($this->form_validation->run())
                    {
                        //image Upload #start.
                        $upload_path="uploads/vocational_training/"; 
                        $new_name = time() . '-' . $_FILES["up_file"]['name'];
                        $config = array(
                        'upload_path' => $upload_path,
                        'file_name'=>$new_name,
                        'allowed_types' => "zip",
                        'max_size' => "2048000", //2.048mb
                        'max_height' => "0",
                        'max_width' => "0"
                        );
                        
                        $this->load->library('upload', $config);                    
                        if($this->upload->do_upload('up_file'))
                        { 
                            $imageDetailArray = $this->upload->data();
                            if(!empty($imageDetailArray['image_type'])){
                                $img_type =  $imageDetailArray['image_type'];
                            }else{
                                $img_type =  $imageDetailArray['file_type'];
                            }

                            $insert_data = [
                                            "sp_id"=> $user['id'],
                                            "training_name"=> $this->input->post('vocational_name'),
                                            "training_desc"=> $this->input->post('vocational_description'),
                                            "training_file_loc"=> $imageDetailArray['file_name'],
                                            "file_mine"=> $img_type
                                            ];

                            $this->db->insert("vocational_training",$insert_data);
                            $last_id = $this->db->insert_id();

                            // sub section #start
                                $section_name = $this->input->post("training_section_name");
                                $section_desc = $this->input->post("training_section_desc");
                                $arr_size = sizeof($section_name);
                                $arr_section_data = [];
                                for($i = 0;$i < $arr_size;$i++){
                                    $arr_section_data[] = [
                                        "training_id"=>$last_id,
                                        "section_name"=> $section_name[$i],
                                        "section_desc"=> $section_desc[$i]
                                    ]; 
                                }

                                $this->db->insert_batch("vocational_training_sections",$arr_section_data);
                                if($this->db->affected_rows() > 0){
                                    $data_view['check_inset'] = "OK";
                                }else{
                                    $data_view['check_inset'] = "ERROR";
                                }
                            // sub section #end
                        }
                        else
                        {
                            $data_view['imageError'] =  $this->upload->display_errors();                        
                        }
                    // image Upload #end.
                    }
                }       
               
                $this->load->view('navbar',$data);
                $this->load->view('sidebar',$data);
                $this->load->view('vocational_training_add',$data_view); 
                $this->load->view('footer');
        }

        public function vocational_training_details($id)
        {
                $data_view = [];
                $this->load->model('User_model');
                if($this->User_model->authorized()==false)
                {
                    $this->session->set_flashdata('msg','You are not authorized to access this section');
                    redirect(base_url().'/UserController/login');
                }
                $user = $this->session->userdata('user');
                $data['user'] = $user;

                $data_view['vocational_info'] = $this->get_vocational_info("FULL_SINGLE_VIEW",$id);
                $this->load->view('navbar',$data);
                $this->load->view('sidebar'); 
                $this->load->view('vocational_training_details',$data_view); 
                $this->load->view('footer');
        }

        public function vocational_training_edit($id,$action_path = null){
            $data_view = [];
            if(empty($id)){
                show_404();
            }
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');           
            $data['user'] = $user;
    
            if($action_path == "VOC_TRAI"){
                if(isset($_POST['btn_vocational']))
                {
                    $this->form_validation->set_rules('vocational_name','Name','required');
                    $this->form_validation->set_rules('vocational_description','Description','required');
                    if($this->form_validation->run())
                    {
                        $this->db->set("training_name",$this->input->post("vocational_name",true));
                        $this->db->set("training_desc",$this->input->post("vocational_description",true));
                        $this->db->where(["id"=>$id]);
                        $this->db->limit(1);
                        $this->db->update("vocational_training");
                        if($this->db->affected_rows() > 0){
                            $data_view['VOC_TRAI_MSG'] = "OK";
                        }else{
                            $data_view['VOC_TRAI_MSG'] = "ERROR";
                        }
                    }
                }
            }
    
            if($action_path == "SUB_SECTION"){
                if(isset($_POST['btn_sub_section']))
                {
                    
                    $this->form_validation->set_rules('sub_section_name','Name','required');
                    $this->form_validation->set_rules('sub_section_description','Description','required');
                    if($this->form_validation->run())
                    {
                        $section_id = $this->input->get("sno");
                        if(!empty($section_id)){
                            $this->db->set("section_name",$this->input->post("sub_section_name",true));
                            $this->db->set("section_desc",$this->input->post("sub_section_description",true));
                            $this->db->where(["id"=>$section_id]);
                            $this->db->limit(1);
                            $this->db->update("vocational_training_sections");
                            if($this->db->affected_rows() > 0){
                                $data_view['SUB_SECTION_MSG'] = "OK";
                            }else{
                                $data_view['SUB_SECTION_MSG'] = "ERROR";
                            }
                        }   
                    }
                }
            }
    
            if($action_path == "ADD_SUB_SECTION"){
                if(isset($_POST['btn_add_more_sectoin']))
                {
                    $add_more_section_name = $this->input->post("add_sub_section_name");
                    $add_more_section_desc = $this->input->post("add_sub_section_description");
                    if(!empty($add_more_section_name)){
                        $arr_size = sizeof($add_more_section_name);
                        $arr_section_data = [];
                        for($i = 0;$i < $arr_size;$i++){
                            $arr_section_data[] = [
                                "training_id"=>$id,
                                "section_name"=> $add_more_section_name[$i],
                                "section_desc"=> $add_more_section_desc[$i]
                            ]; 
                        }
    
                        $this->db->insert_batch("vocational_training_sections",$arr_section_data);
                        if($this->db->affected_rows() > 0){
                            $data_view['ADD_SUB_SECTION_MSG'] = "OK";
                        }else{
                            $data_view['ADD_SUB_SECTION_MSG'] = "ERROR";
                        }
                    }
                }
            }
    
            if($action_path == "CONTENT_UPLOAD"){
                if(!empty($_FILES["content_file"]['name']))
                {
                    //image Upload #start.
                    $upload_path="uploads/vocational_training/"; 
                    $new_name = time() . '-' . $_FILES["content_file"]['name'];
                    $config = array(
                    'upload_path' => $upload_path,
                    'file_name'=>$new_name,
                    'allowed_types' => "zip",
                    'max_size' => "2048000", //2.048mb
                    'max_height' => "0",
                    'max_width' => "0"
                    );
                    
                    $this->load->library('upload', $config);                    
                    if($this->upload->do_upload('content_file'))
                    { 
                        $arr_file_upload = $this->upload->data();                    
                        if(!empty($arr_file_upload['image_type'])){
                            $img_type =  $arr_file_upload['image_type'];
                        }else{
                            $img_type =  $arr_file_upload['file_type'];
                        }
                        $this->db->set("training_file_loc",$arr_file_upload['file_name']);
                        $this->db->set("file_mine",$img_type);
                        $this->db->where(["id"=>$id]);
                        $this->db->limit(1);
                        $this->db->update("vocational_training");
                        if($this->db->affected_rows() > 0){
                          $old_file_name = "uploads/vocational_training/".$this->input->post("old_file_name");
                          if (is_file($old_file_name)) {
                                unlink($old_file_name);
                            }                        
                            $data_view['CONTENT_UPLOAD_MSG'] = "OK";
                        }else{
                            $data_view['CONTENT_UPLOAD_MSG'] = "ERROR";
                        }
                    }else{
                        $data_view['imageError'] =  $this->upload->display_errors();
                    }
                }
            }
            
            $data_view['vocational_info'] = $this->get_vocational_info("FULL_SINGLE_VIEW",$id);
            $this->load->view('navbar',$data);
            $this->load->view('sidebar');
            $this->load->view('vocational_training_edit',$data_view); 
            $this->load->view('footer');
        }

        protected function get_vocational_info($check,$id){
            if($check == "SHORT_VIEW"){
                $this->db->select("*");
                $this->db->where(["sp_id"=>$id]);
                $this->db->order_by("id","desc");
                $q = $this->db->get("vocational_training");
                return $q->result();
            }
    
            if($check == "FULL_SINGLE_VIEW"){
                $data = [];
                $this->db->select("*");
                $this->db->where(["id"=>$id]);
                $q = $this->db->get("vocational_training");
                $data[] = ["training_info"=>$q->row()];

                $this->db->select("*");
                $this->db->where(["training_id"=>$id]);
                $q = $this->db->get("vocational_training_sections");
                $data[] =  ["section_info"=>$q->result()];

                $this->db->select("*");
                $this->db->where(["training_id"=>$id]);
                $this->db->order_by("id","desc");
                $this->db->order_by("remark_date","desc");
                $q = $this->db->get("vocational_training_remarks");
                $data[] = ["remark"=>$q->result()];
    
                return $data;
            }
    
            if($check == "PARTICIPANT_SINGLE_VIEW"){
                $this->db->select("a.id,a.user_email,b.fullname,a.apply_date,a.training_status");
                $this->db->where(["a.training_id"=>$id]);
                $this->db->order_by("a.id","desc");
                $this->db->order_by("a.apply_date");
                $this->db->join("user_details b","b.email = a.user_email","inner");
                $q = $this->db->get("vocational_training_apply_user a");
                return $q->result();
            }
        }

        public function participant_details($id)
        {
                $data_view = [];
                $this->load->model('User_model');
                if($this->User_model->authorized()==false)
                {
                    $this->session->set_flashdata('msg','You are not authorized to access this section');
                    redirect(base_url().'/UserController/login');
                }
                $user = $this->session->userdata('user');
                $data['user'] = $user;
               
                $data['user'] = $user;
                $data_view['participant_info'] = $this->get_vocational_info("PARTICIPANT_SINGLE_VIEW",$id);
                $this->load->view('navbar',$data);
                $this->load->view('sidebar'); 
                $this->load->view('participant_details',$data_view); 
                $this->load->view('footer');
        }

        function delete_itme($id,$action = null){
            $json_msg = [];
            switch($action){
                case "REMOVE_SUB_SECTION":
                    $this->db->limit(1);
                    $this->db->delete("vocational_training_sections",["id"=>$id]);
                    if($this->db->affected_rows() > 0){
                        $json_msg = ["MSG"=>"OK"];
                    }else{
                        $json_msg = ["MSG"=>"ERROR"];
                    }
                break;
                case "REMOVE_VOCATIONAL":
                    $this->db->select("training_file_loc");
                    $this->db->where(["id"=>$id]);
                    $this->db->limit(1);
                    $file_name = $this->db->get("vocational_training")->row()->training_file_loc;
    
                    $this->db->delete("vocational_training",["id"=>$id]);
                    $this->db->limit(1);
                    $this->db->delete("vocational_training_sections",["training_id"=>$id]);
                    if($this->db->affected_rows() > 0){
                        $file_path = "uploads/vocational_training/$file_name";
                        if (is_file($file_path)) {
                              unlink($file_path);
                          }  
                        $json_msg = ["MSG"=>"OK"];
                    }else{
                        $json_msg = ["MSG"=>"ERROR"];
                    }
                break;
                case "REMOVE_JOB":
                    $this->db->limit(1);
                    $this->db->delete("placement_jobs",["id"=>$id]);
                    if($this->db->affected_rows() > 0){
                        $json_msg = ["MSG"=>"OK"];
                    }else{
                        $json_msg = ["MSG"=>"ERROR"];
                    }
                break;
            }
            echo json_encode($json_msg);
        }

        function sp_ajax_work($id,$action = null){
            $json_msg = [];
            $this->load->model('Sp_model');
            switch($action){
                case "CERTIFICATION_REQUEST":
                    $vocational_id = $this->input->post("vid");
                    $request_type = $this->input->post("req_type");
                    $old_file_name = $this->input->post("file_name");               
                    
                    $upload_path="uploads/vocational_training/"; 
                    $new_name = time() . '-certification-' . $_FILES["file_content"]['name'];
                    $config = array(
                    'upload_path' => $upload_path,
                    'file_name'=>$new_name,
                    'allowed_types' => "zip",
                    'max_size' => "2048000", //2.048mb
                    'max_height' => "0",
                    'max_width' => "0"
                    );
    
                    $this->load->library('upload', $config);                    
                    if($this->upload->do_upload('file_content'))
                    { 
                        $arr_file_upload = $this->upload->data();
                        $this->db->set("certification_status",$request_type);
                        $this->db->set("certification_file_loc",$arr_file_upload['file_name']);
                        $this->db->where(["id"=>$vocational_id]);
                        $this->db->limit(1);
                        $this->db->update("vocational_training");
                        if($this->db->affected_rows() > 0){
                            if(!empty($old_file_name)){
                                $old_file_name = "uploads/vocational_training/".$old_file_name;
                                if (is_file($old_file_name)) {
                                    unlink($old_file_name);
                                }
                            }
                            $json_msg["MSG"] = "OK";
                        }else{
                            $json_msg["MSG"] = "ERROR";
                        }
                    }else{
                        $json_msg["MSG"] = "ERROR_IMG";
                        $json_msg["ERROR_IMG_DESC"] =  $this->upload->display_errors();
                    }
                break;
                case "TRAINING_REQUEST":
                    $training_status_type = $this->input->get("req",true);
                    if(!empty($training_status_type)){
                        $this->db->set("training_status",$training_status_type);
                        $this->db->where(["id"=>$id]);
                        $this->db->limit(1);
                        $this->db->update("vocational_training");
                        if($this->db->affected_rows() > 0){
                            $json_msg = ["MSG"=>"OK"];
                        }else{
                            $json_msg = ["MSG"=>"ERROR"];
                        }
                    }else{
                        $json_msg = ["MSG"=>"EMPTY"];
                    }
                break;
                case "PARTICIPANT_REQUEST":
                    $status_type = $this->input->get("req_type",true);
                    if(!empty($status_type)){
                        $this->db->set("training_status",$status_type);
                        $this->db->where(["id"=>$id]);
                        $this->db->limit(1);
                        $this->db->update("vocational_training_apply_user");
                        if($this->db->affected_rows() > 0){
                            $json_msg = ["MSG"=>"OK"];
                        }else{
                            $json_msg = ["MSG"=>"ERROR"];
                        }
                    }else{
                        $json_msg = ["MSG"=>"EMPTY"];
                    }
                break;
                case "JOB_STATUS":
                    $check = null;
                    $check = $this->Sp_model->update_status($action,$id);
                    if($check){
                        $json_msg = ["MSG"=>"OK"];
                    }else{
                        $json_msg = ["MSG"=>"ERROR"];
                    }
                break;
                case "JOB_STATUS_REQUEST":
                    $check = null;
                    $check = $this->Sp_model->update_status($action,$id);
                    if($check){
                        $json_msg = ["MSG"=>"OK"];
                    }else{
                        $json_msg = ["MSG"=>"ERROR"];
                    }
                break;
            }
            echo json_encode($json_msg);
        }

        // jobs #Start
        public function posts_job(){
            $data_view = [];
            // $this->load->model('User_model');
            // $this->load->model('Base_model');
            $this->load->model('Sp_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');           
            $data['user'] = $user;
    
            $data_view['short_data'] = $this->Sp_model->get_job_data("SHORT_VIEW",$user['id']);
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('posts_job',$data_view); 
            $this->load->view('footer');
        }

        public function job_add(){
            $data_view = [];
            // $this->load->model('User_model');
            // $this->load->model('Base_model');
             $this->load->model('Sp_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');           
            $data['user'] = $user;
    
            if(isset($_POST['btn_job']))
            {
                $this->form_validation->set_rules('job_title','Job Title','required');
                // $this->form_validation->set_rules('salary','CTC','required');
                $this->form_validation->set_rules('job_types','Job Types','required');
                // $this->form_validation->set_rules('job_locations','Job Locations','required');
                $this->form_validation->set_rules('posting_nature','Job Nature','required');
                $this->form_validation->set_rules('job_description','Job Description','required');
                $this->form_validation->set_rules('job_nature','Job Nature','required');
                 if($this->form_validation->run())
                {
                   
                    $domain = $this->input->post("domain",true);
                    $specialization_potion = $this->input->post("specialization[]",true);
                    if(!empty($specialization_potion)){
                        $specialization_potion = implode(",",$specialization_potion);
                    }
                    $new_domain = $this->input->post("add_new_domain",true);
                    if(!empty($new_domain)){
                        $this->db->insert("job_domain",["name"=>$new_domain]);
                        $domain = $this->db->insert_id();
                    }
                    $new_specialization = $this->input->post("add_new_specialization",true);
                    if(!empty($new_domain)){
                        $specialization_v = explode(",",$new_specialization);
                        $s_v_count = sizeof($specialization_v);
                        $s_id = [];
                        for($i = 0; $i < $s_v_count; $i++){
                            $s_data = [
                                        'name'=>$specialization_v[$i],
                                        'job_domain_id'=>$domain
                                    ];
                            $this->db->insert("job_specialization",$s_data);
                            $s_id[] = $this->db->insert_id();
                        }
    
                        $specialization_potion = implode(",",$s_id);
                    }
    
                    $arr_form_data = [
                        "sp_id"             => $user['id'],
                        "job_title"         => $this->input->post("job_title",true),
                        "salary"            => $this->input->post("salary",true),
                        "job_type"          => $this->input->post("job_types",true),
                        "job_locations"     => $this->input->post("job_locations",true),
                        "posting_nature"    => $this->input->post("posting_nature",true),
                        "job_description"   => $this->input->post("job_description",true),
                        "job_nature"        => $this->input->post("job_nature",true),
                        "domain"            => $domain,
                        "specialization"    => $specialization_potion
                    ]; 
    
                    $this->db->insert("placement_jobs",$arr_form_data);
                    if($this->db->affected_rows() > 0){
                        $this->session->set_flashdata('check_inset','OK');
                        redirect(base_url().'UserController/posts_job');
                    }else{
                        $this->session->set_flashdata('check_inset','ERROR');
                       redirect(base_url().'UserController/posts_job');                    
                    }
                }
            }
            $data_view['job_domain'] = $this->Sp_model->get_job_data("ALL_JOB_DOMAIN");
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data); 
            $this->load->view('job_add',$data_view); 
            $this->load->view('footer');
        }

        public function job_edit($id){        
            $data_view = [];
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');           
            $data['user'] = $user;
            if(isset($_POST['btn_job_edit']))
            {
                $this->form_validation->set_rules('job_title','Job Title','required');
                // $this->form_validation->set_rules('salary','Salary','required');
                $this->form_validation->set_rules('job_types','Job Types','required');
                // $this->form_validation->set_rules('job_locations','Job Locations','required');
                $this->form_validation->set_rules('posting_nature','Job Nature','required');
                $this->form_validation->set_rules('job_description','Job Description','required');
                if($this->form_validation->run())
                {
                    $domain = $this->input->post("domain",true);
                    $specialization_potion = $this->input->post("specialization[]",true);
                    if(!empty($specialization_potion)){
                        $specialization_potion = implode(",",$specialization_potion);
                    }
                    $new_domain = $this->input->post("add_new_domain",true);
                    if(!empty($new_domain)){
                        $this->db->insert("job_domain",["name"=>$new_domain]);
                        $domain = $this->db->insert_id();
                    }
                    $new_specialization = $this->input->post("add_new_specialization",true);
                    if(!empty($new_domain)){
                        $specialization_v = explode(",",$new_specialization);
                        $s_v_count = sizeof($specialization_v);
                        $s_id = [];
                        for($i = 0; $i < $s_v_count; $i++){
                            $s_data = [
                                        'name'=>$specialization_v[$i],
                                        'job_domain_id'=>$domain
                                    ];
                            $this->db->insert("job_specialization",$s_data);
                            $s_id[] = $this->db->insert_id();
                        }
    
                        $specialization_potion = implode(",",$s_id);
                    }
    
                    $arr_form_update = [
                        "job_title"         => $this->input->post("job_title",true),
                        "salary"            => $this->input->post("salary",true),
                        "job_type"          => $this->input->post("job_types",true),
                        "job_locations"     => $this->input->post("job_locations",true),
                        "posting_nature"    => $this->input->post("posting_nature",true),
                        "job_description"   => $this->input->post("job_description",true),
                        "domain"            => $domain,
                        "specialization"    => $specialization_potion,
                        "updated_on"        => date("Y-m-d h:i:sA")
                    ];
                   
                    $this->db->set($arr_form_update);
                    $this->db->where(["sp_id" => $user['id'], "id"=>$id]);
                    $this->db->limit(1);
                    $this->db->update("placement_jobs");
                    if($this->db->affected_rows() > 0){
                        $data_view['check_inset'] = "OK";
                    }else{
                        $data_view['check_inset'] = "ERROR";
                    }
                }
            }
            $data_view['short_data'] = $this->Sp_model->get_job_data("EDIT_BY_ID",$id);
            $data_view['job_specialization'] = $this->Sp_model->get_job_data("SPECIALIZATION",$data_view['short_data']->domain);
            $data_view['job_domain'] = $this->Sp_model->get_job_data("ALL_JOB_DOMAIN");        
            $this->load->view('navbar',$data);
            $this->load->view('sidebar'); 
            $this->load->view('job_edit',$data_view); 
            $this->load->view('footer');
        }

        public function job_request_user($id)
        {
            $data_view = [];
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
           
            $data['user'] = $user;
            $data_view['job_request_info'] = $this->Sp_model->get_job_data("JOB_REQUEST_USERS",$id);
            $this->load->view('navbar',$data);
            $this->load->view('sidebar'); 
            $this->load->view('job_request_user',$data_view); 
            $this->load->view('footer');
        }
         public function landingsPagesTemplate()
        {
            $data_view = [];
            // $this->load->model('User_model');
            // $this->load->model('Base_model');
            $this->load->model('Sp_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $user = $this->session->userdata('user');
           
            $data['user'] = $user;
            $data = $this->initializer();
            $data['template'] = $this->User_model->get_template_data();
            //$data_view['job_request_info'] = $this->Sp_model->get_job_data("JOB_REQUEST_USERS",$id);
            //$this->load->view('navbar',$data); landing_page_full_details
            //$this->load->view('sidebar'); 
            $this->load->view('shared/landing-page/one/index',$data); 
            //$this->load->view('footer');
        }
        
 public function landingsPages($id)
        {
            //echo $id;die;
            // $this->load->database();
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            
            // $this->load->model('Commen_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data = $this->initializer();
            $user = $this->session->userdata('user');
           // echo "<pre>";print_r($user);die;
            $data['user'] = $user;
            //echo $user['landing_id'];
            // $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            // $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            // $data['submenu_items'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            //$this->load->model('User_model');
            
            $metadata['landingId']= $id;
            $metadata['resellerId']= $user['id'];
            $metadata['flow']= $this->User_model->get_landing_section_data($id,$user['id']);
            //echo "<pre>";print_r($metadata);die;
            
            //$metadata['flow']= $this->User_model->get_landing_section_data();['flow']
            $landing = $this->User_model->landingId($user['id']);
            $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
            $metadata['landing_page_section']= $this->User_model->get_landing_section_via_data($id);
            $metadata['landing_page_details']= $this->User_model->get_landing_details_section_via_data($id);
          //echo "heko";die;
            //create view path
            $controller_name = debug_backtrace();
            //$arr_view_path = explode('_',$controller_name[0]['function']);
            //$path = implode('/',$arr_view_path);
            $file ='list';
            //$view_full_path = 'admin/'.$path.'/'.$file;
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('sidebar',$data);
            $this->load->view('landing_page_section_list',$metadata);
            $this->load->view('footer');
        }
        public function addLandingSectionParameter()
        {
             // $id = $this->input->post('id',TRUE);
             // echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            //echo "dgdgdfkgjdfgkjdf";die;

            $this->form_validation->set_rules('section_name','Name','required');
            //$this->form_validation->set_rules('descripation','Descripation','required');
            //$this->form_validation->set_rules('path','path','required');
           
            if($this->form_validation->run()== true)
            {
               /// echo "dgdgdfkgjdfgkjdffgfgfgffgf";die;
                //echo "if";die();
                $landingId = $this->input->post('landingId',TRUE);
                $name = $this->input->post('section_name',TRUE);
                $resellerId = $this->input->post('resellerId',TRUE);
                //$path = $this->input->post('path',TRUE);
                $data=[];
                $data['sectionId']=  $name;
                $data['resellerId']= $resellerId;
                $data['landingPageId']= $landingId;

                $this->load->model('User_model');
                $result = $this->User_model->insert_landing_section_data($data);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/UserController/landingsPages/'.$landingId,'refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/UserController/landingsPages/'.$landingId,'refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/UserController/landingsPages/'.$landingId,'refresh');
            }

        }
         public function addParameterValues()
        {
             // $id = $this->input->post('id',TRUE);
             // echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            //echo "dgdgdfkgjdfgkjdf";die;

            $this->form_validation->set_rules('values','Value','required');
            //$this->form_validation->set_rules('descripation','Descripation','required');
            //$this->form_validation->set_rules('path','path','required');
           $landingId = $this->input->post('landingId',TRUE);
            $section_id = $this->input->post('id',TRUE);
            $resellerId = $this->input->post('resellerId',TRUE);
            // if($this->form_validation->run()== true)
            // {
              $this->load->model('User_model');
              $values = $this->input->post('values',TRUE);
                $count = count($values);
                if ($count > 0) {
                   for ($i = 0; $i < $count; $i++) {
                       if (!empty($values[$i])) {
                          $data = array(
                            'landing_page_id' => $landingId,
                            'parameter' => $this->input->post('parameterName')[$i],
                            'value' => $this->input->post('values')[$i],
                            'section_id' => $section_id,
                            'reseller_id'=>$resellerId,
                            );
                
                          $result = $this->User_model->insert_landing_section_full_data($data);
                        }
                      } 
                   }
              //$array = array_merge($values,$para);
             // $i=0;
            //   foreach ($values as $value){
            //       //echo "<pre>";print_r($value);die;
            //       //echo $value;die;
            //         $data=[];
            //         $data['landing_page_id']=  $landingId;
            //         $data['value']=  $value;
            //         //$data['parameter'] = $value[$i]:'';
            //         $data['section_id']= $section_id;
            //         $data['reseller_id']= $resellerId;
            //         //$i++;
            //          $result = $this->User_model->insert_landing_section_full_data($data);
                   
            //   }
                //echo "<pre>";print_r($data);die;
                   

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/UserController/landingsPages/'.$landingId,'refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/UserController/landingsPages/'.$landingId,'refresh');
                    }      
            // }
            // else
            // {   
            //     $this->session->set_flashdata("msg","Enter all the required fields");
            //     redirect('/UserController/landingsPages/'.$landingId,'refresh');
            // }

        }
        public function counselingDelete($id)
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

            $this->load->helper(array('form', 'url'));

            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            
            //$this->load->model('Admin_model');
            $result = $this->User_model->counselingDelete($id);
            if($result==True){
                $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/UserController/counselingParameters/','refresh');
        }else{
            $this->session->set_flashdata('msg','Something Wrong');
            redirect('/UserController/counselingParameters/','refresh');
        }
            $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/UserController/counselingParameters/','refresh');

        }
        public function landingSectionDelete($id)
        {
            //echo "edit";die();
            //echo $status;die;
            //$status = ($status==1)?0:1;
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

            $this->load->helper(array('form', 'url'));

            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            
            //$this->load->model('Admin_model');
            $result = $this->User_model->landingSectionDelete($id);
            if($result==True){
                $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/UserController/landingsPages/'.$id,'refresh');
        }else{
            $this->session->set_flashdata('msg','Something Wrong');
            redirect('/UserController/landingsPages','refresh');
        }
            $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/UserController/landingsPages/'.$id,'refresh');

        }
        public function updateLandingSectinParameter()
        {
            $this->session->set_flashdata("msg","Coming Soon");
                                            redirect('/UserController/landingsPages','refresh');
            //echo "update";die();
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

                //$id = base64_decode($this->input->post('id',TRUE));
                //$key = base64_decode($this->input->post('name',TRUE));
                //$descripation = $this->input->post('descripation',TRUE);
                //$path = $this->input->post('path',TRUE);
                $name = $this->input->post('section_name',TRUE);
                $id = $this->input->post('id',TRUE);
                
                // echo $id.$key.$new_param.$name;die();
                
                // echo $id;die();

               // $this->load->model('Admin_model');
               // $db_data = $this->Admin_model->get_land_byId($id);
                //echo "<pre>";
                 
                
                
                $parameters= [
                    'section_name'=>$name,
                    ];
                $result = $this->User_model->updatLandingSectionById($id, $parameters);
                //print_r($parameters);echo "</pre>";die();
               
                if($result)
                {  
                    $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/UserController/landingsPages','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","Opps Unable to add data");
                                            redirect('/UserController/landingsPages','refresh');
                    
                }      
        }
        public function section_via_parameter()
        {
            
            
            $sectionId = $this->input->post('sectionId');
            
            $this->load->model('User_model');
            $landing_page_details = $this->User_model->sectionViaPArameter($sectionId);
            //echo "<pre>";print_r($reseller);die;
                $html ='';
                    foreach ($landing_page_details as $row) {
                      $html .='<div class="row">';
                        $html .= '<div class="col-md-6">';
                            $html .= '<div class="mb-3">';
                             $html .= ' <label class="form-label" for="basic-default-fullname">Parameter</label>';
                               $html .= '<h2>'.$row["parameter"].'</h2>';
                               $html .= '<input type="hidden" name="parameterName[]" class="form-control" id="basic-default-fullname" value="'.$row["parameter"].'"  />';
                          $html .='</div>';
                        $html .='</div>';
                        $html .='<div class="col-md-6">';
                          $html .='<div class="mb-3">';
                            $html .='<label class="form-label" for="basic-default-fullname">Value</label>';
                              $html .='<input type="text" name="values[]" class="form-control" id="basic-default-fullname" required placeholder="Value" />';
                              $html .='<div class="render-template-msg"></div>';
                          $html .='</div>';
                        $html .='</div>';
                        $html .='</div>';
                    }
           
            //echo $html;exit;
            echo json_encode($html);
        }
        // jobs #End

}

?>