<?php
    require_once APPPATH.'third_party/razorpay-php/Razorpay.php';
    use Razorpay\Api\Api;
    class BaseController extends CI_Controller
    {
        
        protected $uce_part_2_time = 0;
        
       
        public function __construct()
        {
            parent::__construct();
            $this->load->model('Commen_model');
            
            // var_dump($mot_menu);die;
           
        }

        public function login($code)
        {

            $this->load->model("Sp_model");
            $code = base64_decode($code);
            $data['code'] = $code;
            $this->load->model('User_model');
            if($this->User_model->authorized()==true)
            {
                $user = $this->session->userdata('user');
                   
                redirect(base_url().'BaseController/dashboard');                
                
            }

           
            if(!empty($code)){
               $data["sp_logo"] = $sp_logo = $this->Sp_model->get_sp_logo($code);
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
                    if(password_verify($password,$user['pwd'])==true && $user['status']=='1'){
                        $sessArray['id'] = $user['id'];
                        $sessArray['fullname']=$user['fullname'];
                        $sessArray['email']=$user['email'];
                        $sessArray['mobile']=$user['mobile'];
                        $sessArray['user_id']=$user['user_id'];
                        $sessArray['iam']=$user['iam'];
                        $sessArray['profile_photo']=$user['profile_photo'];
                        
                        $this->session->set_userdata('user',$sessArray);
                        redirect(base_url().'BaseController/dashboard');
                        
                       
                    }
                    else
                    {
                        $this->session->set_flashdata('msg','Either email or password is incorrect, please try again');
                        redirect(base_url().'UserController/login');
                    }
                }else{
                    $this->session->set_flashdata('msg','Either email or password is incorrect, please try again');
                    redirect(base_url().'UserController/login');
                }
            }
            else
            {
                $this->load->view('user/login',$data);  
            }
            
        }

        public function registration($code)
        { 
            $this->load->model("Sp_model");
            $data['code'] = base64_decode($code);
            $this->load->model('User_model');
            
            if(!empty($data['code'])){
                $data["sp_logo"] =  $this->Sp_model->get_sp_logo($data['code']);
             }
             
            if(isset($_POST['regbtn']))
            {
                $this->form_validation->set_rules('full_name','Fullname|min_length[3]','required');
                $this->form_validation->set_rules('email','Email','required|valid_email|is_unique[user_details.email]');
                $this->form_validation->set_rules('mobile','Mobile','required|min_length[10]');
              
                $this->form_validation->set_rules('password','Password','required|min_length[8]|max_length[25]|callback_check_strong_password');
                $this->form_validation->set_rules('terms','Terms','required');
                $this->form_validation->set_rules('cpassword','Confirm Password','required|matches[password]');
                if($this->form_validation->run() == true)
                {
                    $uid = $this->input->post('code');
                    $this->db->where('reseller_id',$uid);
                    $row = $this->db->get('reseller_homepage');
                    foreach($row->result() as $row)
                    {
                        $uid2 = $row->r_email;
                    }
                    $this->db->where('email',$uid2);
                    $row2 = $this->db->get('user_details');
                    foreach($row2->result() as $row2)
                    {
                        $uid3 = $row2->user_id;
                    }
                    $formArray = array(
                        'user_id'=>$uid3,
                        'fullname'=>$_POST['full_name'],
                        'email'=>$_POST['email'],
                        'mobile'=>$_POST['mobile'],
                        'role'=>'individual',
                        'iam'=>'user',
                        'pwd'=> password_hash($this->input->post('password'),PASSWORD_BCRYPT),
                        'status'=>'0',
                        'profile_photo'=>'uploads/default.png'

                    );
                    $email = $_POST['email'];
                    $this->User_model->create($formArray);
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
                    redirect('/BaseController/validate_otp');
                    
                }
                else
                {
                    $this->load->view('user/registration',$data);
                }
                
            }
            else
            {
                $this->load->view('user/registration',$data);
            }
            
        }



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

        public function initializer()
        {
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            $this->load->model("Base_model");
            
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['mot_menu'] = $this->Commen_model->get_mot_menu('user','mot_sidebar');
            $data['mot_navbar'] = $this->Commen_model->get_mot_menu('user','mot_navbar');

            $data['allowed_services'] = $this->Base_model->getUserDetailsByIds($user['user_id']);
            $data['user'] = $user;
            // echo "<pre>";
            // var_dump($data['user']);echo "</pre>";
            $data['reseller_sp'] = $this->Commen_model->get_reseller_sp($data['user']['user_id']);
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            $data['calendly_url']='https://calendly.com/';
            $ref_data =  $this->session->userdata("ref_data");
            if(!empty($ref_data)){
                if($ref_data["page"] == "jpostlist"){
                    redirect(base_url('BaseController/all_jobs'));
                }
                if($ref_data["page"] == "julist"){
                    redirect(base_url('BaseController/apply_jobs'));
                }
                if($ref_data["page"] == "take-ass"){
                   $query_check =  $this->db->get_where("user_code_list",["user_id"=>$ref_data['user_id'],"status"=>"ap"]);
                    if($query_check->num_rows() > 0){
                        $this->session->set_flashdata("url_msg","view_code");
                        redirect(base_url('BaseController/view_code'));
                    }else{
                        $this->session->set_flashdata("url_msg","request_code");
                        redirect(base_url('BaseController/request_code'));
                    }
                }
            }

            return $data;

        }
        
        public function dashboard() // this is user dashboard 
        {
          
            $data = $this->initializer();
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar',$data); 
            $this->load->view('user/dashboard'); 
            $this->load->view('footer');
        }
         public function counselingParameters()
        {
            
             //$user = $this->session->userdata('user');
             
            
            $data = $this->initializer();
            $user =$data['user'];
            $resellId = $this->Base_model->getresellerid($user['user_id']);
            $metadata['flow']= $this->User_model->getCounselingPara($resellId);
            $metadata['paymentGateway']= $this->Base_model->getpayment_name();
            $booking_link = $this->Commen_model->getResellerBookingLink($resellId);
            $metadata['counsling_booking_url']= 'https://calendly.com/'.$booking_link;
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar',$data);
            $this->load->view('user/counseling_parameters_view',$metadata);
            $this->load->view('footer');
        }
        public function view_user_profile()
        {
            // $this->load->model('User_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            // $user = $this->session->userdata('user');
            // $data['user'] = $user;
            // $data['allowed_services'] = $this->Base_model->getUserDetailsByIds($user['user_id']);
            $data= $this->initializer();
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/user_profile',$data); 
            $this->load->view('footer');
        }
        
        public function edit_user_profile()
        {

            // $this->load->model('User_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            // $user = $this->session->userdata('user');
            // $data['allowed_services'] = $this->Base_model->getUserDetailsByIds($user['user_id']);
            // $data['user'] = $user;
            $data = $this->initializer();
            $user = $data['user'];
            $email=$user['email'];
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/edit_user_profile',$data); 
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
                    $this->session->set_userdata('user',$sessArray);
                    $this->session->set_flashdata('msg2','Detail Updated');
                    redirect('/BaseController/edit_user_profile','refresh');
                }
            }
        }

        
        public function change_password()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            // $user = $this->session->userdata('user');
            // $data['allowed_services'] = $this->Base_model->getUserDetailsByIds($user['user_id']);
            // $data['user'] = $user;
            $data = $this->initializer();
            $user = $data['user'];
            $email = $user['email'];
            
    
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/change_user_password',$data); 
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
                        redirect(base_url().'/BaseController/change_password');
                    
                    }
                    else
                    {
                        $this->session->set_flashdata('msg','Please Check Password and Confirm Password are same or not');
                        redirect(base_url().'/BaseController/change_password');
                    }
                    
                }
                else
                {
                    $this->session->set_flashdata('msg','Wrong OLD Password');
                    redirect(base_url().'/BaseController/change_password');
                   
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

        public function request_code()
        {
            //$this->load->model('Admin_model');
            //  $this->load->model('Base_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $user = $this->session->userdata('user');
            // $data['user'] = $user;
            // // echo "<pre>";
            // // print_r($data['user']);
            // // echo "</pre>";
            // // die;
            // $data['reseller_sp'] = $this->Commen_model->get_reseller_sp($data['user']['user_id']);
            // $data['allowed_services'] = $this->Base_model->getUserDetailsByIds($user['user_id']);
            $data = $this->initializer();
            $data['paymentGateway']= $this->Base_model->getpayment_name();
            // $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
            $data['allowed_services'] = $this->Base_model->getUserDetailsByIds($user['user_id']);
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar',$data); 
            $this->load->view('user/request_code',$data); 
            $this->load->view('footer');
            $this->session->unset_userdata("ref_data");
        }

        public function user_request_code($email,$r_email,$solution,$sl_name)
        {   date_default_timezone_set("Asia/Kolkata");
            $date= date_create(date('d-m-Y H:i:sP'));
            $today_date= date_format($date, 'd-m-Y H:i:s');
            $slname = str_replace("%20"," ",$sl_name);

            $formArray = array(
                'user_id' => $email,
                'reseller_id' => $r_email,
                'code' => '',
                'solution' => $solution,
                'display_solution_name' => $slname,
                'asignment_registration_date' => $today_date,
                'status' => 'pending',
                'payment_mode' => 'offline'
            );
            if($this->db->insert('user_code_list',$formArray))
            {
                $this->session->set_flashdata('msg','Request Sent Successfully');
                redirect(base_url().'/BaseController/request_code'); 
            }

        }
        public function view_code()
        {   //echo "view code";die;
            // $this->load->model('User_model');
            // $this->load->model('Base_model');
            // $this->load->model('Commen_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            // $data['reseller_sp'] = $this->Commen_model->get_reseller_sp($data['user']['user_id']);
            // $data['allowed_services'] = $this->Base_model->getUserDetailsByIds($user['user_id']);
            $data = $this->initializer();
            $code['s'] = $this->Base_model->code_list($user['email']);
            // echo "<pre>";
            // print_r($code);die();
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/view_approve_code_test',$code); //replace with view_approve_code 
            $this->load->view('footer');
            $this->session->unset_userdata("ref_data");      
        }
        
        public function fetch_service_detail()
        {
            if($_POST['rowid']) {
                $id = $_POST['rowid']; //escape string
             }
          
        }
        public function change_profile_photo()
        {
                // $this->load->model('User_model');
                // if($this->User_model->authorized()==false)
                // {
                //     $this->session->set_flashdata('msg','You are not authorized to access this section');
                //     redirect(base_url().'/UserController/login');
                // }
                // $user = $this->session->userdata('user');
                // $data['allowed_services'] = $this->Base_model->getUserDetailsByIds($user['user_id']);
                $data = $this->initializer();
                $data['user'] = $user;
                $email=$user['email'];
                $this->load->view('navbar',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/edit_profile_photo',$data); 
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
                            $this->session->set_flashdata('msg',$error);
                            redirect('/BaseController/edit_user_profile','refresh');
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
                    redirect('/BaseController/edit_user_profile','refresh');
                }
        }
        public function ppe_part1($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
            // $this->load->model('Base_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $user = $this->session->userdata('user');
            //$data['allowed_services'] = $this->Base_model->getUserDetailsByIds($user['user_id']);
            $data = $this->initializer();
            $data['user'] = $user;
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='ppe_part1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->ppe_part1($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/ppe_part1',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=61)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ppe_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ppe_part1/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ppe_part1/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ppe_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $this->session->set_flashdata('msg','Part 1 Completed Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ppe_part1/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        public function ppe_part2($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='ppe_part2'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->ppe_part1($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/ppe_part2',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=61)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ppe_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ppe_part2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ppe_part2/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ppe_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $this->session->set_flashdata('msg','Part 2 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ppe_part2/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        public function ppe_part3($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='ppe_part3'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->ppe_part3($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/ppe_part3',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=31)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ppe_part3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ppe_part3/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ppe_part3/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ppe_part3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 3'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $this->session->set_flashdata('msg','Part 3 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ppe_part3/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        public function ppe_part4($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='ppe_part4'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->ppe_part4($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/ppe_part4',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=21)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ppe_part4',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ppe_part4/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ppe_part4/'.base64_encode($code));   
                    }
                    
                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ppe_part4',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 4'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        date_default_timezone_set("Asia/Kolkata");
                        $dt = date("d-m-Y H:i");
                        $dt = $dt.' (GMT + 5:30)';
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Test Compeleted Please Download Your Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ppe_part4/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        public function wla_part1($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "user_id='$email' and code='$code' and gender!=''";
            $this->db->where($where);
            $num = $this->db->get('user_code_list')->num_rows();
            if($num>0)
            {
                redirect(base_url().'BaseController/wla_part1_test/'.base64_encode($code));
            }
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/gender'); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                $this->form_validation->set_rules('gender','Gender','required');
                if($this->form_validation->run()==true)
                    {
                        $gender = $_POST['gender'];
                        $where = "user_id='$email' and code='$code'";
                        $this->db->where($where);
                        $this->db->set('gender',$gender);
                        $this->db->update('user_code_list');
                        // $this->session->set_flashdata('msg',$gender);
                        // redirect(base_url().'BaseController/wla_part1/'.base64_encode($code));
                        redirect(base_url().'BaseController/wla_part1_test/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wla_part1/'.base64_encode($code));   
                    }
            }
        }
        public function wla_part1_test($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='wla_part1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wla_part1($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wla_part1',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=56)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'wla_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/wla_part1/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wla_part1/'.base64_encode($code));   
                    }
                    

                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'wla_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $this->session->set_flashdata('msg','Part 1 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wla_part1/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        public function wla_part2($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='wla_part2'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wla_part2($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wla_part2',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=46)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'wla_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/wla_part2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wla_part2/'.base64_encode($code));   
                    }
                    

                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'wla_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $where2 = "user_id='$email' and code='$code'";
                        $dt = date("d-m-Y h:m");
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Test Compeleted Please Download Your Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wla_part2/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        public function wls_part1($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='wls_part1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->wls_part1_1($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part1_1',$qlist); 
                $this->load->view('footer');
            }
            else if($qno<10)
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->wls_part1_1($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part1_1',$qlist); 
                $this->load->view('footer'); 
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->wls_part1_2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part1_2',$qlist); 
                $this->load->view('footer'); 
            }

           
            if(isset($_POST['saveBtn']))
            {
                if($num!=31)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'wls_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/wls_part1/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wls_part1/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'wls_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $this->session->set_flashdata('msg','Part 1 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wls_part1/'.base64_encode($code));   
                    }    
                }
            
            
            }
                
        }
        public function wls_part2($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where="email='$email' and code='$code'";
            $this->db->where($where);
            $this->db->order_by('id','desc')->limit(1);
            $num = $this->db->get('wls_part2_rank_ordring')->num_rows();
            if($num==0)
            {
                $grp = 1;
            }
            else
            {
                $where="email='$email' and code='$code'";
                $this->db->where($where);
                $this->db->order_by('id','desc')->limit(1);
                $row = $this->db->get('wls_part2_rank_ordring');
                foreach($row->result() as $row)
                {
                    $grp = $row->grp;
                    $grp = $grp + 1;    
                }    
            }
            if($grp>21)
            {
                redirect(base_url().'BaseController/wls_part2_2/'.base64_encode($code));
            }
            $where2 = "grp>=$grp";
            $this->db->where($where2);
            $r['r']= $this->db->limit(25)->get('wls_part2_1_detail');
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wls_part2_1',$r); 
            $this->load->view('footer');        
            
            if(isset($_POST['saveBtn']))
            {
                $j=1;
                $list = $_POST['list'];
                $grop = $_POST['grp'];
                $count = count($list);
                for($i=0;$i<=$count-1;$i++)
                {
                    if($j>5)
                    {
                        $j=1;
                    }
                    $formArray = array(
                        'email'=>$email,
                        'solution'=>'wls_part2_1',
                        'code'=>$code,
                        'grp'=>$grop[$i],
                        'qno'=>$j,
                        'ordr'=>$list[$i]
                    );  
                    $this->db->insert('wls_part2_rank_ordring',$formArray);
                    $j++;
                }
                redirect(base_url().'BaseController/wls_part2/'.base64_encode($code));
            }
        }

        public function wls_part2_2($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='wls_part2_2'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->wls_part2_2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part2_2',$qlist); 
                $this->load->view('footer');
            }
            else if($qno<21)
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->wls_part2_2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part2_2',$qlist); 
                $this->load->view('footer'); 
            }
            else
            {
                redirect(base_url().'BaseController/wls_part2_3/'.base64_encode($code));
                // $where = "email='$email' and code='$code' and solution='wls_part2_3'";
                // $this->db->where($where);
                // $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                // foreach($qno->result() as $qno)
                // {
                //     $qno = $qno->qno;
                //     $num = $qno + 1;
                // }
                // $qlist['q'] = $this->Base_model->wls_part2_3($num);
                // $this->load->view('navbar3',$data);
                // $this->load->view('user/sidebar'); 
                // $this->load->view('user/wls_part2_3',$qlist); 
                // $this->load->view('footer'); 
            }

           
            if(isset($_POST['saveBtn']))
            {
                if($num!=21)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'wls_part2_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/wls_part2_2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wls_part2_2/'.base64_encode($code));   
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'wls_part2_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/wls_part2_2/'.base64_encode($code));
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        // $where = "user_id='$email' and code='$code' and part='Part 2'";
                        // $this->db->where($where);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_assessment_info');
                        // $this->session->set_flashdata('msg','Part 2 Compeleted Please Take Next Assessment');
                        // redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wls_part2_2/'.base64_encode($code));   
                    }    
                }
            
            
            }
                
        }
           
        public function wls_part2_3($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='wls_part2_3'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->wls_part2_3($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part2_3',$qlist); 
                $this->load->view('footer');
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->wls_part2_3($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part2_3',$qlist); 
                $this->load->view('footer'); 
            }

            if(isset($_POST['saveBtn']))
            {
                if($num!=6)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'wls_part2_3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/wls_part2_3/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wls_part2_3/'.base64_encode($code));   
                    }
                    

                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'wls_part2_3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $dt = date("d-m-Y h:m");
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Part 2 Compeleted Please Download Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wls_part2_3/'.base64_encode($code));   
                    }
                }    
            }
        }
        public function wpa_part1($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='wpa_part1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wpa_part1($num);

            if(isset($_POST['saveBtn']))
            {
                if($num!=41)
                {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                                $qnm = 'qnm'.$i;
                                $qnm = $_POST[$qnm];
                                $ans = 'q'.$i;
                                $type = 'type'.$i;
                                $type = $_POST[$type];
                                if($type!='cb')
                                {
                                    if(!isset($_POST[$ans]))
                                    {
                                        $ans = '0';
                                    }
                                    else
                                    {
                                        $ans = $_POST[$ans];
                                    }
                                }
                                else
                                {
                                   
                                        $this->db->where('qno',$qnm);
                                        $c_num = $this->db->get('wpa_part1_options')->num_rows();
                                       
                                        $as = '';
                                        $l = 0;
                                        for($b=1;$b<=$c_num;$b++){
                                            $answ = $ans.$b;
                                            
                                            if(isset($_POST[$answ]))
                                            {
                                                if($l!=0)
                                                {
                                                    $answ = $_POST[$answ];
                                                    $as = $as.','.$answ;
                                                    
                                                }
                                                else
                                                {
                                                    $answ = $_POST[$answ];
                                                    $as = $as.$answ;
                                                    $l=1;
                                                }
                                               
                                            }
                                            
                                        }
                                        $ans = $as;
                                    
                                }
                               
                                $where = "qno='$qnm' and email='$email' and code='$code' and solution='wpa_part1'";
                                $this->db->where($where);
                                $c_num = $this->db->get('ppe_part1_test_details')->num_rows();
                                if($c_num==0)
                                {
                                    $formArray = Array(
                                        'email'=>$email,
                                        'solution'=>'wpa_part1',
                                        'code'=>$code,
                                        'qno'=>$qnm,
                                        'ans'=>$ans  
                                    );
                                    $this->db->insert('ppe_part1_test_details',$formArray);
                                    $ans = '';
                                }
                                else
                                {
                                    $this->db->set('ans',$ans);
                                    $this->db->where($where);
                                    $this->db->update('ppe_part1_test_details');
                                }
                                
                            $i++;
                              
                        }
                        redirect(base_url().'BaseController/wpa_part1/'.base64_encode($code));
                    

                }
                else
                {
                   
                    $i=1;
                    foreach($qlist['q']->result() as $q)
                    {   
                            $qnm = 'qnm'.$i;
                            $qnm = $_POST[$qnm];
                            $ans = 'q'.$i;
                            $type = 'type'.$i;
                            $type = $_POST[$type];
                            if($type!='cb')
                            {
                                if(!$_POST[$ans])
                                {
                                    $ans = '0';
                                }
                                else
                                {
                                    $ans = $_POST[$ans];
                                }
                            }
                            else
                            {
                               
                                    $this->db->where('qno',$qnm);
                                    $c_num = $this->db->get('wpa_part1_options')->num_rows();
                                   
                                    $as = '';
                                    $l = 0;
                                    for($b=1;$b<=$c_num;$b++){
                                        $answ = $ans.$b;
                                        if($_POST[$answ])
                                        {
                                            if($l!=0)
                                            {
                                                $answ = $_POST[$answ];
                                                $as = $as.','.$answ;
                                                
                                            }
                                            else
                                            {
                                                $answ = $_POST[$answ];
                                                $as = $as.$answ;
                                                $l=1;
                                            }
                                           
                                        }
                                        
                                    }
                                    $ans = $as;
                                
                            }
                           
                            $where = "qno='$qnm' and email='$email' and code='$code' and solution='wpa_part1'";
                            $this->db->where($where);
                            $c_num = $this->db->get('ppe_part1_test_details')->num_rows();
                            if($c_num==0)
                            {
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'wpa_part1',
                                    'code'=>$code,
                                    'qno'=>$qnm,
                                    'ans'=>$ans  
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                                $ans = '';
                            }
                            else
                            {
                                $this->db->set('ans',$ans);
                                $this->db->where($where);
                                $this->db->update('ppe_part1_test_details');
                            }
                            
                        $i++;
                          
                    }
                        $where = "user_id='$email' and code='$code' and part='Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                       
                        $this->session->set_flashdata('msg','Part 1 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                            
                    
                }    
            }
            if(isset($_POST['saveBtn2']))
            {
                $where = "email='$email' and code='$code' and solution='wpa_part1'";
                $this->db->where($where);
                $qno = $this->db->get('ppe_part1_test_details')->num_rows();
                if($qno>0)
                {
                    $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                    foreach($qno->result() as $qno)
                    {
                        $qno = $qno->qno;
                        $num = $qno - 4;
                    }
                    $qlist['q'] = $this->Base_model->wpa_part1($num);
                }
            }

            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wpa_part1',$qlist);
            $this->load->view('footer'); 
          
        }
        public function wpa_part2($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='wpa_part2'";
            $this->db->where($where);
            $qno = $this->db->get('wpa_part2_user_ans')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->wpa_part2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wpa_part2',$qlist); 
                $this->load->view('footer');
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('wpa_part2_user_ans'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->wpa_part2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wpa_part2',$qlist); 
                $this->load->view('footer'); 
            }

            if(isset($_POST['saveBtn']))
            {
                if($num!=100)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $ans2 = '';
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                if($_POST[$ans]=='yes')
                                {
                                    $ans2 = 'radiof'.$i;
                                    $ans2 = $_POST[$ans2];
                                }
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'wpa_part2',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>$_POST[$ans],
                                    'sec_ans'=>$ans2
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;
                            $ans2 = '';
                            
                        }
                        redirect(base_url().'BaseController/wpa_part2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wpa_part2/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                
                    if($this->form_validation->run()==true)
                    {
                        $ans2 = '';
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                if($_POST[$ans]=='yes')
                                {
                                    $ans2 = 'radiof'.$i;
                                    $ans2 = $_POST[$ans2];
                                }
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'wpa_part2',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>$_POST[$ans],
                                    'sec_ans'=>$ans2
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;
                            $ans2 = '';
                            
                        }
                     
                        
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $dt = date("d-m-Y h:m");
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->set('status','Rp');
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Part 2 Compeleted Please Download Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wpa_part2/'.base64_encode($code));   
                    }
                }    
            }
        }
        //ps part1 wpa part 1
        public function ps_part1($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='ps_part1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wpa_part1($num);

            if(isset($_POST['saveBtn']))
            {
                if($num!=41)
                {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                                $qnm = 'qnm'.$i;
                                $qnm = $_POST[$qnm];
                                $ans = 'q'.$i;
                                $type = 'type'.$i;
                                $type = $_POST[$type];
                                if($type!='cb')
                                {
                                    if(!isset($_POST[$ans]))
                                    {
                                        $ans = '0';
                                    }
                                    else
                                    {
                                        $ans = $_POST[$ans];
                                    }
                                }
                                else
                                {
                                   
                                        $this->db->where('qno',$qnm);
                                        $c_num = $this->db->get('wpa_part1_options')->num_rows();
                                       
                                        $as = '';
                                        $l = 0;
                                        for($b=1;$b<=$c_num;$b++){
                                            $answ = $ans.$b;
                                            
                                            if(isset($_POST[$answ]))
                                            {
                                                if($l!=0)
                                                {
                                                    $answ = $_POST[$answ];
                                                    $as = $as.','.$answ;
                                                    
                                                }
                                                else
                                                {
                                                    $answ = $_POST[$answ];
                                                    $as = $as.$answ;
                                                    $l=1;
                                                }
                                               
                                            }
                                            
                                        }
                                        $ans = $as;
                                    
                                }
                               
                                $where = "qno='$qnm' and email='$email' and code='$code' and solution='ps_part1'";
                                $this->db->where($where);
                                $c_num = $this->db->get('ppe_part1_test_details')->num_rows();
                                if($c_num==0)
                                {
                                    $formArray = Array(
                                        'email'=>$email,
                                        'solution'=>'ps_part1',
                                        'code'=>$code,
                                        'qno'=>$qnm,
                                        'ans'=>$ans  
                                    );
                                    $this->db->insert('ppe_part1_test_details',$formArray);
                                    $ans = '';
                                }
                                else
                                {
                                    $this->db->set('ans',$ans);
                                    $this->db->where($where);
                                    $this->db->update('ppe_part1_test_details');
                                }
                                
                            $i++;
                              
                        }
                        redirect(base_url().'BaseController/ps_part1/'.base64_encode($code));
                    

                }
                else
                {
                   
                    $i=1;
                    foreach($qlist['q']->result() as $q)
                    {   
                            $qnm = 'qnm'.$i;
                            $qnm = $_POST[$qnm];
                            $ans = 'q'.$i;
                            $type = 'type'.$i;
                            $type = $_POST[$type];
                            if($type!='cb')
                            {
                                if(!$_POST[$ans])
                                {
                                    $ans = '0';
                                }
                                else
                                {
                                    $ans = $_POST[$ans];
                                }
                            }
                            else
                            {
                               
                                    $this->db->where('qno',$qnm);
                                    $c_num = $this->db->get('wpa_part1_options')->num_rows();
                                   
                                    $as = '';
                                    $l = 0;
                                    for($b=1;$b<=$c_num;$b++){
                                        $answ = $ans.$b;
                                        if($_POST[$answ])
                                        {
                                            if($l!=0)
                                            {
                                                $answ = $_POST[$answ];
                                                $as = $as.','.$answ;
                                                
                                            }
                                            else
                                            {
                                                $answ = $_POST[$answ];
                                                $as = $as.$answ;
                                                $l=1;
                                            }
                                           
                                        }
                                        
                                    }
                                    $ans = $as;
                                
                            }
                           
                            $where = "qno='$qnm' and email='$email' and code='$code' and solution='ps_part1'";
                            $this->db->where($where);
                            $c_num = $this->db->get('ppe_part1_test_details')->num_rows();
                            if($c_num==0)
                            {
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'ps_part1',
                                    'code'=>$code,
                                    'qno'=>$qnm,
                                    'ans'=>$ans  
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                                $ans = '';
                            }
                            else
                            {
                                $this->db->set('ans',$ans);
                                $this->db->where($where);
                                $this->db->update('ppe_part1_test_details');
                            }
                            
                        $i++;
                          
                    }
                        $where = "user_id='$email' and code='$code' and part='WPA Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                       
                        $this->session->set_flashdata('msg','Part 1 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                            
                    
                }    
            }
            if(isset($_POST['saveBtn2']))
            {
                $where = "email='$email' and code='$code' and solution='ps_part1'";
                $this->db->where($where);
                $qno = $this->db->get('ppe_part1_test_details')->num_rows();
                if($qno>0)
                {
                    $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                    foreach($qno->result() as $qno)
                    {
                        $qno = $qno->qno;
                        $num = $qno - 4;
                    }
                    $qlist['q'] = $this->Base_model->wpa_part1($num);
                }
            }

            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wpa_part1',$qlist);
            $this->load->view('footer'); 
          
        }
        //ps part 2 wpa part 2 combine
        public function ps_part2($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='ps_part2'";
            $this->db->where($where);
            $qno = $this->db->get('wpa_part2_user_ans')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->wpa_part2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wpa_part2',$qlist); 
                $this->load->view('footer');
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('wpa_part2_user_ans'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->wpa_part2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wpa_part2',$qlist); 
                $this->load->view('footer'); 
            }

            if(isset($_POST['saveBtn']))
            {
                if($num!=100)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $ans2 = '';
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                if($_POST[$ans]=='yes')
                                {
                                    $ans2 = 'radiof'.$i;
                                    $ans2 = $_POST[$ans2];
                                }
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'ps_part2',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>$_POST[$ans],
                                    'sec_ans'=>$ans2
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;
                            $ans2 = '';
                            
                        }
                        redirect(base_url().'BaseController/ps_part2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part2/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                
                    if($this->form_validation->run()==true)
                    {
                        $ans2 = '';
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                if($_POST[$ans]=='yes')
                                {
                                    $ans2 = 'radiof'.$i;
                                    $ans2 = $_POST[$ans2];
                                }
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'ps_part2',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>$_POST[$ans],
                                    'sec_ans'=>$ans2
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;
                            $ans2 = '';
                            
                        }
                     
                        
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='WPA Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        
                        $this->session->set_flashdata('msg','Part 2 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part2/'.base64_encode($code));   
                    }
                }    
            }
        }
        //ps part 3 WLA part 1
        public function ps_part3($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and gender!=''";
            $this->db->where($where);
            $num = $this->db->get('user_details')->num_rows();
            if($num>0)
            {
                redirect(base_url().'BaseController/ps_part3_test/'.base64_encode($code));
            }
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/gender'); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                $this->form_validation->set_rules('gender','Gender','required');
                if($this->form_validation->run()==true)
                    {
                       
                        $this->db->where('email',$email);
                        $this->db->set('gender',$_POST['gender']);
                        $this->db->update('user_details');
                    
                        redirect(base_url().'BaseController/ps_part3_test/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part3/'.base64_encode($code));   
                    }
            }
        }
        //ps part 3 wla part 1 test
        public function ps_part3_test($code)
        {
            $code = base64_decode($code);
            // $this->load->model('User_model');
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
            $where = "email='$email' and code='$code' and solution='ps_part3'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wla_part1($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wla_part1',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=56)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ps_part3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ps_part3/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part3/'.base64_encode($code));   
                    }
                    

                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ps_part3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='WLA Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $this->session->set_flashdata('msg','Part 3 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part3/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        //ps part 4 wla part 2 test
        public function ps_part4($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='ps_part4'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wla_part2($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wla_part2',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=46)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ps_part4',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ps_part4/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part4/'.base64_encode($code));   
                    }
                    

                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ps_part4',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='WLA Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                       
                        $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part4/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        //ps part 5 wls part1 test
        public function ps_part5($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='ps_part5'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->wls_part1_1($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part1_1',$qlist); 
                $this->load->view('footer');
            }
            else if($qno<10)
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->wls_part1_1($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part1_1',$qlist); 
                $this->load->view('footer'); 
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->wls_part1_2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part1_2',$qlist); 
                $this->load->view('footer'); 
            }

           
            if(isset($_POST['saveBtn']))
            {
                if($num!=31)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ps_part5',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ps_part5/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part5/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ps_part5',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='WLS Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $this->session->set_flashdata('msg','Part 1 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part5/'.base64_encode($code));   
                    }    
                }
            
            
            }
                
        }
        //ps part 6
        public function ps_part6($code)
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
            $email = $user['email'];
            $where="email='$email' and code='$code'";
            $this->db->where($where);
            $this->db->order_by('id','desc')->limit(1);
            $num = $this->db->get('wls_part2_rank_ordring')->num_rows();
            if($num==0)
            {
                $grp = 1;
            }
            else
            {
                $where="email='$email' and code='$code'";
                $this->db->where($where);
                $this->db->order_by('id','desc')->limit(1);
                $row = $this->db->get('wls_part2_rank_ordring');
                foreach($row->result() as $row)
                {
                    $grp = $row->grp;
                    $grp = $grp + 1;    
                }    
            }
            if($grp>21)
            {
                redirect(base_url().'BaseController/ps_part6_2/'.base64_encode($code));
            }
            $where2 = "grp>=$grp";
            $this->db->where($where2);
            $r['r']= $this->db->limit(25)->get('wls_part2_1_detail');
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wls_part2_1',$r); 
            $this->load->view('footer');        
            
            if(isset($_POST['saveBtn']))
            {
                $j=1;
                $list = $_POST['list'];
                $grop = $_POST['grp'];
                $count = count($list);
                for($i=0;$i<=$count-1;$i++)
                {
                    if($j>5)
                    {
                        $j=1;
                    }
                    $formArray = array(
                        'email'=>$email,
                        'solution'=>'ps_part6_1',
                        'code'=>$code,
                        'grp'=>$grop[$i],
                        'qno'=>$j,
                        'ordr'=>$list[$i]
                    );  
                    $this->db->insert('wls_part2_rank_ordring',$formArray);
                    $j++;
                }
                redirect(base_url().'BaseController/ps_part6/'.base64_encode($code));
            }
        }
        //ps part 6 2  ps_part6_2
        public function ps_part6_2($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='ps_part6_2'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->wls_part2_2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part2_2',$qlist); 
                $this->load->view('footer');
            }
            else if($qno<21)
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->wls_part2_2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part2_2',$qlist); 
                $this->load->view('footer'); 
            }
            else
            {
                redirect(base_url().'BaseController/ps_part6_3/'.base64_encode($code));
                
            }

           
            if(isset($_POST['saveBtn']))
            {
                if($num!=21)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ps_part6_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ps_part6_2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part6_2/'.base64_encode($code));   
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ps_part6_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ps_part6_2/'.base64_encode($code));
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        // $where = "user_id='$email' and code='$code' and part='Part 2'";
                        // $this->db->where($where);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_assessment_info');
                        // $this->session->set_flashdata('msg','Part 2 Compeleted Please Take Next Assessment');
                        // redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part6_2/'.base64_encode($code));   
                    }    
                }
            }     
        }
        //ps part 6 3 ps _part6_3
        public function ps_part6_3($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='ps_part6_3'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->wls_part2_3($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part2_3',$qlist); 
                $this->load->view('footer');
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->wls_part2_3($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/wls_part2_3',$qlist); 
                $this->load->view('footer'); 
            }

            if(isset($_POST['saveBtn']))
            {
                if($num!=6)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ps_part6_3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ps_part6_3/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ps_part6_3/'.base64_encode($code));   
                    }
                    

                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ps_part6_3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='WLS Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $dt = date("d-m-Y h:m");
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Part 6 Compeleted Now You Can Download Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/wls_part2_3/'.base64_encode($code));   
                    }
                }    
            }
        }
        //sdp1 part1 wpa part1
        public function sdp1_part1($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='sdp1_part1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wpa_part1($num);

            if(isset($_POST['saveBtn']))
            {
                if($num!=41)
                {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                                $qnm = 'qnm'.$i;
                                $qnm = $_POST[$qnm];
                                $ans = 'q'.$i;
                                $type = 'type'.$i;
                                $type = $_POST[$type];
                                if($type!='cb')
                                {
                                    if(!isset($_POST[$ans]))
                                    {
                                        $ans = '0';
                                    }
                                    else
                                    {
                                        $ans = $_POST[$ans];
                                    }
                                }
                                else
                                {
                                   
                                        $this->db->where('qno',$qnm);
                                        $c_num = $this->db->get('wpa_part1_options')->num_rows();
                                       
                                        $as = '';
                                        $l = 0;
                                        for($b=1;$b<=$c_num;$b++){
                                            $answ = $ans.$b;
                                            
                                            if(isset($_POST[$answ]))
                                            {
                                                if($l!=0)
                                                {
                                                    $answ = $_POST[$answ];
                                                    $as = $as.','.$answ;
                                                    
                                                }
                                                else
                                                {
                                                    $answ = $_POST[$answ];
                                                    $as = $as.$answ;
                                                    $l=1;
                                                }
                                               
                                            }
                                            
                                        }
                                        $ans = $as;
                                    
                                }
                               
                                $where = "qno='$qnm' and email='$email' and code='$code' and solution='sdp1_part1'";
                                $this->db->where($where);
                                $c_num = $this->db->get('ppe_part1_test_details')->num_rows();
                                if($c_num==0)
                                {
                                    $formArray = Array(
                                        'email'=>$email,
                                        'solution'=>'sdp1_part1',
                                        'code'=>$code,
                                        'qno'=>$qnm,
                                        'ans'=>$ans  
                                    );
                                    $this->db->insert('ppe_part1_test_details',$formArray);
                                    $ans = '';
                                }
                                else
                                {
                                    $this->db->set('ans',$ans);
                                    $this->db->where($where);
                                    $this->db->update('ppe_part1_test_details');
                                }
                                
                            $i++;
                              
                        }
                        redirect(base_url().'BaseController/sdp1_part1/'.base64_encode($code));
                    

                }
                else
                {
                   
                    $i=1;
                    foreach($qlist['q']->result() as $q)
                    {   
                            $qnm = 'qnm'.$i;
                            $qnm = $_POST[$qnm];
                            $ans = 'q'.$i;
                            $type = 'type'.$i;
                            $type = $_POST[$type];
                            if($type!='cb')
                            {
                                if(!$_POST[$ans])
                                {
                                    $ans = '0';
                                }
                                else
                                {
                                    $ans = $_POST[$ans];
                                }
                            }
                            else
                            {
                               
                                    $this->db->where('qno',$qnm);
                                    $c_num = $this->db->get('wpa_part1_options')->num_rows();
                                   
                                    $as = '';
                                    $l = 0;
                                    for($b=1;$b<=$c_num;$b++){
                                        $answ = $ans.$b;
                                        if($_POST[$answ])
                                        {
                                            if($l!=0)
                                            {
                                                $answ = $_POST[$answ];
                                                $as = $as.','.$answ;
                                                
                                            }
                                            else
                                            {
                                                $answ = $_POST[$answ];
                                                $as = $as.$answ;
                                                $l=1;
                                            }
                                           
                                        }
                                        
                                    }
                                    $ans = $as;
                                
                            }
                           
                            $where = "qno='$qnm' and email='$email' and code='$code' and solution='sdp1_part1'";
                            $this->db->where($where);
                            $c_num = $this->db->get('ppe_part1_test_details')->num_rows();
                            if($c_num==0)
                            {
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'sdp1_part1',
                                    'code'=>$code,
                                    'qno'=>$qnm,
                                    'ans'=>$ans  
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                                $ans = '';
                            }
                            else
                            {
                                $this->db->set('ans',$ans);
                                $this->db->where($where);
                                $this->db->update('ppe_part1_test_details');
                            }
                            
                        $i++;
                          
                    }
                        $where = "user_id='$email' and code='$code' and part='SDP1 Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                       
                        $this->session->set_flashdata('msg','Part 1 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                            
                    
                }    
            }
            if(isset($_POST['saveBtn2']))
            {
                $where = "email='$email' and code='$code' and solution='sdp1_part1'";
                $this->db->where($where);
                $qno = $this->db->get('ppe_part1_test_details')->num_rows();
                if($qno>0)
                {
                    $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                    foreach($qno->result() as $qno)
                    {
                        $qno = $qno->qno;
                        $num = $qno - 4;
                    }
                    $qlist['q'] = $this->Base_model->wpa_part1($num);
                }
            }

            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wpa_part1',$qlist);
            $this->load->view('footer'); 
          
        }
        //sdp1 part2 -> wla part1
        public function sdp1_part2($code)
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
            $email = $user['email'];
            $where = "email='$email' and gender!=''";
            $this->db->where($where);
            $num = $this->db->get('user_details')->num_rows();
            if($num>0)
            {
                redirect(base_url().'BaseController/sdp1_part1_test/'.base64_encode($code));
            }
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/gender'); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                $this->form_validation->set_rules('gender','Gender','required');
                if($this->form_validation->run()==true)
                    {
                       
                        $this->db->where('email',$email);
                        $this->db->set('gender',$_POST['gender']);
                        $this->db->update('user_details');
                    
                        redirect(base_url().'BaseController/sdp1_part1_test/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/sdp1_part2/'.base64_encode($code));   
                    }
            }
        }
        public function sdp1_part1_test($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='sdp1_part2'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wla_part1($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wla_part1',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=56)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'sdp1_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/sdp1_part2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/sdp1_part2/'.base64_encode($code));   
                    }
                    

                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'sdp1_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='SDP1 Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $this->session->set_flashdata('msg','Part 2 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/sdp1_part2/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        //sdp1 part2 -> wpa part2
        public function sdp1_part3($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='sdp1_part3'";
            $this->db->where($where);
            $qno = $this->db->get('wpa_part2_user_ans')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->sdp1_part3($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/sdp1_part3',$qlist); 
                $this->load->view('footer');
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('wpa_part2_user_ans'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->sdp1_part3($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/sdp1_part3',$qlist); 
                $this->load->view('footer'); 
            }

            if(isset($_POST['saveBtn']))
            {
                if($num!=36)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'sdp1_part3',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>'yes',
                                    'sec_ans'=>$_POST[$ans]
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;
                            
                            
                        }
                        redirect(base_url().'BaseController/sdp1_part3/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/sdp1_part3/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'sdp1_part3',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>'yes',
                                    'sec_ans'=>$_POST[$ans]
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;                            
                        }
                     
                        $where = "user_id='$email' and code='$code' and part='SDP1 Part 3'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $dt = date("d-m-Y h:m");
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Part 3 Compeleted Please Download Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/sdp1_part3/'.base64_encode($code));   
                    }
                }    
            }
        }
        //sdp2 part1 wla part2
        public function sdp2_part1($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='sdp2_part1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wla_part2($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wla_part2',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=46)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'sdp2_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/sdp2_part1/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/sdp2_part1/'.base64_encode($code));   
                    }
                    

                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'sdp2_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='SDP2 Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                
                        $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/sdp2_part1/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        //sdp2 part2 sdp1_part3
        public function sdp2_part2($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='sdp2_part2'";
            $this->db->where($where);
            $qno = $this->db->get('wpa_part2_user_ans')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->sdp2_part2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/sdp1_part3',$qlist); 
                $this->load->view('footer');
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('wpa_part2_user_ans'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->sdp2_part2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/sdp1_part3',$qlist); 
                $this->load->view('footer'); 
            }
            if(isset($_POST['saveBtn']))
            {
                if($num!=34)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'sdp2_part2',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>'yes',
                                    'sec_ans'=>$_POST[$ans]
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;
                            
                            
                        }
                        redirect(base_url().'BaseController/sdp2_part2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/sdp2_part2/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'sdp2_part2',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>'yes',
                                    'sec_ans'=>$_POST[$ans]
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;                            
                        }
                     
                        $where = "user_id='$email' and code='$code' and part='SDP2 Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $where2 = "user_id='$email' and code='$code'";
                        $dt = date("d-m-Y h:m");
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Part 2 Compeleted Please Download Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/sdp2_part2/'.base64_encode($code));   
                    }
                }    
            }
        }
        //sdp3 part1 wls_part2
        public function sdp3_part1($code)
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
            $email = $user['email'];
            $where="email='$email' and code='$code'";
            $this->db->where($where);
            $this->db->order_by('id','desc')->limit(1);
            $num = $this->db->get('wls_part2_rank_ordring')->num_rows();
            if($num==0)
            {
                $grp = 1;
            }
            else
            {
                $where="email='$email' and code='$code'";
                $this->db->where($where);
                $this->db->order_by('id','desc')->limit(1);
                $row = $this->db->get('wls_part2_rank_ordring');
                foreach($row->result() as $row)
                {
                    $grp = $row->grp;
                    $grp = $grp + 1;    
                }    
            }
           
            $where2 = "grp>=$grp";
            $this->db->where($where2);
            $r['r']= $this->db->limit(25)->get('wls_part2_1_detail');
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wls_part2_1',$r); 
            $this->load->view('footer');        
            
            if(isset($_POST['saveBtn']))
            {
                if($grp!=21)
                {
                    $j=1;
                    $list = $_POST['list'];
                    $grop = $_POST['grp'];
                    $count = count($list);
                    for($i=0;$i<=$count-1;$i++)
                    {
                        if($j>5)
                        {
                            $j=1;
                        }
                        $formArray = array(
                            'email'=>$email,
                            'solution'=>'sdp3_part1',
                            'code'=>$code,
                            'grp'=>$grop[$i],
                            'qno'=>$j,
                            'ordr'=>$list[$i]
                        );  
                        $this->db->insert('wls_part2_rank_ordring',$formArray);
                        $j++;
                    }
                    redirect(base_url().'BaseController/sdp3_part1/'.base64_encode($code));
                }
                else
                {
                    $j=1;
                    $list = $_POST['list'];
                    $grop = $_POST['grp'];
                    $count = count($list);
                    for($i=0;$i<=$count-1;$i++)
                    {
                        if($j>5)
                        {
                            $j=1;
                        }
                        $formArray = array(
                            'email'=>$email,
                            'solution'=>'sdp3_part1',
                            'code'=>$code,
                            'grp'=>$grop[$i],
                            'qno'=>$j,
                            'ordr'=>$list[$i]
                        );  
                        $this->db->insert('wls_part2_rank_ordring',$formArray);
                        $j++;
                    }
                        $where = "user_id='$email' and code='$code' and part='SDP3 Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                       
                        $this->session->set_flashdata('msg','Part 1 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                }
                
            }

        }
        //sdp3 part2 
        public function sdp3_part2($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='sdp3_part2'";
            $this->db->where($where);
            $qno = $this->db->get('wpa_part2_user_ans')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->sdp3_part2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/sdp1_part3',$qlist); 
                $this->load->view('footer');
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('wpa_part2_user_ans'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->sdp3_part2($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/sdp1_part3',$qlist); 
                $this->load->view('footer'); 
            }
            if(isset($_POST['saveBtn']))
            {
                if($num!=57)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'sdp3_part2',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>'yes',
                                    'sec_ans'=>$_POST[$ans]
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;
                            
                            
                        }
                        redirect(base_url().'BaseController/sdp3_part2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/sdp3_part2/'.base64_encode($code));   
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
                                
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'sdp3_part2',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>'yes',
                                    'sec_ans'=>$_POST[$ans]
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;                            
                        }
                     
                        $where = "user_id='$email' and code='$code' and part='SDP3 Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $dt = date("d-m-Y h:m");
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Part 2 Compeleted Please Download Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/sdp3_part2/'.base64_encode($code));   
                    }
                }    
            }
        }
        //jrap part1 
        public function jrap_part1($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='jrap_part1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wpa_part1($num);

            if(isset($_POST['saveBtn']))
            {
                if($num!=41)
                {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                                $qnm = 'qnm'.$i;
                                $qnm = $_POST[$qnm];
                                $ans = 'q'.$i;
                                $type = 'type'.$i;
                                $type = $_POST[$type];
                                if($type!='cb')
                                {
                                    if(!isset($_POST[$ans]))
                                    {
                                        $ans = '0';
                                    }
                                    else
                                    {
                                        $ans = $_POST[$ans];
                                    }
                                }
                                else
                                {
                                   
                                        $this->db->where('qno',$qnm);
                                        $c_num = $this->db->get('wpa_part1_options')->num_rows();
                                       
                                        $as = '';
                                        $l = 0;
                                        for($b=1;$b<=$c_num;$b++){
                                            $answ = $ans.$b;
                                            
                                            if(isset($_POST[$answ]))
                                            {
                                                if($l!=0)
                                                {
                                                    $answ = $_POST[$answ];
                                                    $as = $as.','.$answ;
                                                    
                                                }
                                                else
                                                {
                                                    $answ = $_POST[$answ];
                                                    $as = $as.$answ;
                                                    $l=1;
                                                }
                                               
                                            }
                                            
                                        }
                                        $ans = $as;
                                    
                                }
                               
                                $where = "qno='$qnm' and email='$email' and code='$code' and solution='jrap_part1'";
                                $this->db->where($where);
                                $c_num = $this->db->get('ppe_part1_test_details')->num_rows();
                                if($c_num==0)
                                {
                                    $formArray = Array(
                                        'email'=>$email,
                                        'solution'=>'jrap_part1',
                                        'code'=>$code,
                                        'qno'=>$qnm,
                                        'ans'=>$ans  
                                    );
                                    $this->db->insert('ppe_part1_test_details',$formArray);
                                    $ans = '';
                                }
                                else
                                {
                                    $this->db->set('ans',$ans);
                                    $this->db->where($where);
                                    $this->db->update('ppe_part1_test_details');
                                }
                                
                            $i++;
                              
                        }
                        redirect(base_url().'BaseController/jrap_part1/'.base64_encode($code));
                    

                }
                else
                {
                   
                    $i=1;
                    foreach($qlist['q']->result() as $q)
                    {   
                            $qnm = 'qnm'.$i;
                            $qnm = $_POST[$qnm];
                            $ans = 'q'.$i;
                            $type = 'type'.$i;
                            $type = $_POST[$type];
                            if($type!='cb')
                            {
                                if(!$_POST[$ans])
                                {
                                    $ans = '0';
                                }
                                else
                                {
                                    $ans = $_POST[$ans];
                                }
                            }
                            else
                            {
                               
                                    $this->db->where('qno',$qnm);
                                    $c_num = $this->db->get('wpa_part1_options')->num_rows();
                                   
                                    $as = '';
                                    $l = 0;
                                    for($b=1;$b<=$c_num;$b++){
                                        $answ = $ans.$b;
                                        if($_POST[$answ])
                                        {
                                            if($l!=0)
                                            {
                                                $answ = $_POST[$answ];
                                                $as = $as.','.$answ;
                                                
                                            }
                                            else
                                            {
                                                $answ = $_POST[$answ];
                                                $as = $as.$answ;
                                                $l=1;
                                            }
                                           
                                        }
                                        
                                    }
                                    $ans = $as;
                                
                            }
                           
                            $where = "qno='$qnm' and email='$email' and code='$code' and solution='jrap_part1'";
                            $this->db->where($where);
                            $c_num = $this->db->get('ppe_part1_test_details')->num_rows();
                            if($c_num==0)
                            {
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'jrap_part1',
                                    'code'=>$code,
                                    'qno'=>$qnm,
                                    'ans'=>$ans  
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                                $ans = '';
                            }
                            else
                            {
                                $this->db->set('ans',$ans);
                                $this->db->where($where);
                                $this->db->update('ppe_part1_test_details');
                            }
                            
                        $i++;
                          
                    }
                        $where = "user_id='$email' and code='$code' and part='JRAP Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                       
                        $this->session->set_flashdata('msg','Part 1 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                            
                    
                }    
            }
            if(isset($_POST['saveBtn2']))
            {
                $where = "email='$email' and code='$code' and solution='jrap_part1'";
                $this->db->where($where);
                $qno = $this->db->get('ppe_part1_test_details')->num_rows();
                if($qno>0)
                {
                    $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                    foreach($qno->result() as $qno)
                    {
                        $qno = $qno->qno;
                        $num = $qno - 4;
                    }
                    $qlist['q'] = $this->Base_model->wpa_part1($num);
                }
            }

            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wpa_part1',$qlist);
            $this->load->view('footer'); 
          
        }
        //jrap part 2 sdp3-part 1
        public function jrap_part2($code)
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
            $email = $user['email'];
            $where="email='$email' and code='$code'";
            $this->db->where($where);
            $this->db->order_by('id','desc')->limit(1);
            $num = $this->db->get('wls_part2_rank_ordring')->num_rows();
            if($num==0)
            {
                $grp = 1;
            }
            else
            {
                $where="email='$email' and code='$code'";
                $this->db->where($where);
                $this->db->order_by('id','desc')->limit(1);
                $row = $this->db->get('wls_part2_rank_ordring');
                foreach($row->result() as $row)
                {
                    $grp = $row->grp;
                    $grp = $grp + 1;    
                }    
            }
           
            $where2 = "grp>=$grp";
            $this->db->where($where2);
            $r['r']= $this->db->limit(25)->get('wls_part2_1_detail');
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wls_part2_1',$r); 
            $this->load->view('footer');        
            
            if(isset($_POST['saveBtn']))
            {
                if($grp!=21)
                {
                    $j=1;
                    $list = $_POST['list'];
                    $grop = $_POST['grp'];
                    $count = count($list);
                    for($i=0;$i<=$count-1;$i++)
                    {
                        if($j>5)
                        {
                            $j=1;
                        }
                        $formArray = array(
                            'email'=>$email,
                            'solution'=>'jrap_part2',
                            'code'=>$code,
                            'grp'=>$grop[$i],
                            'qno'=>$j,
                            'ordr'=>$list[$i]
                        );  
                        $this->db->insert('wls_part2_rank_ordring',$formArray);
                        $j++;
                    }
                    redirect(base_url().'BaseController/jrap_part2/'.base64_encode($code));
                }
                else
                {
                    $j=1;
                    $list = $_POST['list'];
                    $grop = $_POST['grp'];
                    $count = count($list);
                    for($i=0;$i<=$count-1;$i++)
                    {
                        if($j>5)
                        {
                            $j=1;
                        }
                        $formArray = array(
                            'email'=>$email,
                            'solution'=>'jrap_part2',
                            'code'=>$code,
                            'grp'=>$grop[$i],
                            'qno'=>$j,
                            'ordr'=>$list[$i]
                        );  
                        $this->db->insert('wls_part2_rank_ordring',$formArray);
                        $j++;
                    }
                        $where = "user_id='$email' and code='$code' and part='JRAP Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                       
                        $this->session->set_flashdata('msg','Part 2 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                }
                
            }
        }
        //jrap part3 
        public function jrap_part3($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='jrap_part3'";
            $this->db->where($where);
            $qno = $this->db->get('wpa_part2_user_ans')->num_rows();
            if($qno==0)
            {
                $num = '1';
                $qlist['q'] = $this->Base_model->jrap_part3($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/sdp1_part3',$qlist); 
                $this->load->view('footer');
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('wpa_part2_user_ans'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
                $qlist['q'] = $this->Base_model->jrap_part3($num);
                $this->load->view('navbar3',$data);
                $this->load->view('user/sidebar'); 
                $this->load->view('user/sdp1_part3',$qlist); 
                $this->load->view('footer'); 
            }
            if(isset($_POST['saveBtn']))
            {
                if($num!=55)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'jrap_part3',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>'yes',
                                    'sec_ans'=>$_POST[$ans]
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;
                            
                            
                        }
                        redirect(base_url().'BaseController/jrap_part3/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/jrap_part3/'.base64_encode($code));   
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
                                
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'jrap_part3',
                                    'code'=>$code,
                                    'qno'=>$q->qno,
                                    'first_ans'=>'yes',
                                    'sec_ans'=>$_POST[$ans]
                                    
                                );
                                $this->db->insert('wpa_part2_user_ans',$formArray);
                            $i++;                            
                        }
                     
                        $where = "user_id='$email' and code='$code' and part='JRAP Part 3'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $where2 = "user_id='$email' and code='$code'";
                        $dt = date("d-m-Y h:m");
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Part 3 Compeleted Please Download Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/jrap_part3/'.base64_encode($code));   
                    }
                }    
            }
        }
        public function fill_personal_detail($code)
        {
            //dev1 working here 23-11-21
            $code = base64_decode($code);
            $this->load->model('User_model');
            $this->load->model('Base_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data = $this->initializer();
            $data['user'] = $user;
            
            $email = $user['email'];
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/fill_detail',$data); 
            $this->load->view('footer'); 

            if(isset($_POST['saveBtn']))
            {
                $code = $_POST['code'];
                $this->form_validation->set_rules('code','Code','required');
                $this->form_validation->set_rules('full_name','Full Name','required');
                $this->form_validation->set_rules('dt','Date of Birth','required');
                $this->form_validation->set_rules('gender','Gender','required');
                $this->form_validation->set_rules('cls','Class','required');
                $this->form_validation->set_rules('mobile','Mobile','required');
                $this->form_validation->set_rules('email','Email','required');
                $this->form_validation->set_rules('addr','Address','required');
                if($this->form_validation->run()==true)
                {
                    $cls_f = explode(',',$_POST['cls']);
                    $cls = $cls_f[0];
                    $cls_detail = $cls_f[1];
                    $where = "user_id='$email' and code='$code'";
                    
                    $this->db->where($where);
                    $this->db->set('name',$_POST['full_name']);
                    $this->db->set('dob',$_POST['dt']);
                    $this->db->set('gender',$_POST['gender']);
                    $this->db->set('mobile',$_POST['mobile']);
                    $this->db->set('email',$_POST['email']);
                    $this->db->set('cls',$cls);
                    $this->db->set('cls_detail',$cls_detail);
                    $this->db->set('cls_nature',$_POST['person_detail_nature']);
                    $this->db->set('cls_type',$_POST['person_detail_type']);
                    $this->db->set('address',$_POST['addr']);
                    $this->db->update('user_code_list');
                    
                    $this->session->set_flashdata('msg','Personal Details Saved Successfully Now please Continue Your Assessment');
                    redirect(base_url().'BaseController/view_code/'.base64_encode($code));
                }
                else
                {
                    $this->session->set_flashdata('msg',validation_errors());
                    redirect(base_url().'BaseController/fill_personal_detail/'.base64_encode($code));
                }
            }
        }
        public function uce_part1($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part1_1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else if($qno>=27)
            {
                redirect(base_url().'BaseController/uce_part1_2/'.base64_encode($code));
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part1_1($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part1_1',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=26)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part1_1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/uce_part1/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part1/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part1_1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        // $where = "user_id='$email' and code='$code' and part='Part 1'";
                        // $this->db->where($where);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part1/'.base64_encode($code));   
                        // $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        // redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part1/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        public function uce_part1_2($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part1_2'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else if($qno>=56)
            {
                redirect(base_url().'BaseController/uce_part1_3/'.base64_encode($code));
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part1_2($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part1_2',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=56)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part1_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/uce_part1_2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part1_2/'.base64_encode($code));   
                    }
                    

                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part1_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        // $where = "user_id='$email' and code='$code' and part='Part 1'";
                        // $this->db->where($where);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part1_2/'.base64_encode($code));   
                        // $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        // redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part1_2/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        public function uce_part1_3($code)
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
            $email = $user['email'];
            $where="email='$email' and code='$code'";
            $this->db->where($where);
            $this->db->order_by('id','desc')->limit(1);
            $num = $this->db->get('wls_part2_rank_ordring')->num_rows();
            if($num==0)
            {
                $grp = 1;
            }
            else
            {
                $where="email='$email' and code='$code'";
                $this->db->where($where);
                $this->db->order_by('id','desc')->limit(1);
                $row = $this->db->get('wls_part2_rank_ordring');
                foreach($row->result() as $row)
                {
                    $grp = $row->grp;
                    $grp = $grp + 1;    
                }    
            }
            if($grp>21)
            {
                redirect(base_url().'BaseController/uce_part1_4/'.base64_encode($code));
            }
            $where2 = "grp>=$grp";
            $this->db->where($where2);
            $r['r']= $this->db->limit(25)->get('wls_part2_1_detail');
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part1_3',$r); 
            $this->load->view('footer');        
            
            if(isset($_POST['saveBtn']))
            {
                $j=1;
                $list = $_POST['list'];
                $grop = $_POST['grp'];
                $count = count($list);
                for($i=0;$i<=$count-1;$i++)
                {
                    if($j>5)
                    {
                        $j=1;
                    }
                    $formArray = array(
                        'email'=>$email,
                        'solution'=>'uce_part1_3',
                        'code'=>$code,
                        'grp'=>$grop[$i],
                        'qno'=>$j,
                        'ordr'=>$list[$i]
                    );  
                    $this->db->insert('wls_part2_rank_ordring',$formArray);
                    $j++;
                }
                redirect(base_url().'BaseController/uce_part1_3/'.base64_encode($code));
            }
        }
        public function uce_part1_4($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part1_4_1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else if($qno>=26)
            {
                redirect(base_url().'BaseController/uce_part1_4_2/'.base64_encode($code));
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part1_4_1($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part1_4_1',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=26)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part1_4_1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/uce_part1_4/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part1_4/'.base64_encode($code));   
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part1_4_1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        // $where = "user_id='$email' and code='$code' and part='Part 1'";
                        // $this->db->where($where);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part1_4_2/'.base64_encode($code));   
                        // $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        // redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part1_4/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        public function uce_part1_4_2($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part1_4_2'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else if($qno>=24)
            {
                redirect(base_url().'BaseController/uce_part1_5/'.base64_encode($code));
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part1_4_2($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part1_4_2',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=21)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part1_4_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/uce_part1_4_2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part1_4_2/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part1_4_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        // $where = "user_id='$email' and code='$code' and part='Part 1'";
                        // $this->db->where($where);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part1_5/'.base64_encode($code));   
                        // $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        // redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part1_4_2/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        public function uce_part1_5($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part1_5'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows(); 
            // foreach($qno->result() as $qno)
            // {
            //     $qno = $qno->qno;
                
            // }
            if($qno==0)
            {
                $num = '1';
            }
            else if($qno>=16)
            {
                redirect(base_url().'BaseController/uce_part2/'.base64_encode($code));
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part1_5($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part1_5',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=16)
                {
                    // $this->form_validation->set_rules('radio11','First Question','required');
                    // $this->form_validation->set_rules('radio21','Second Question','required');
                    // $this->form_validation->set_rules('radio33','Third Question','required');
                    // $this->form_validation->set_rules('radio41','Fourth Question','required');
                    // $this->form_validation->set_rules('radio51','Fifth Question','required');
                    // if($this->form_validation->run()==true)
                    // {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            $l=0;
                            $as = '';
                            $ans = 'radio'.$i;
                            for($b=1;$b<=4;$b++){
                                $answ = $ans.$b;
                                
                                if(isset($_POST[$answ]))
                                {
                                    if($l!=0)
                                    {
                                        $answ = $_POST[$answ];
                                        $as = $as.','.$answ;
                                        
                                    }
                                    else
                                    {
                                        $answ = $_POST[$answ];
                                        $as = $as.$answ;
                                        $l=1;
                                    }
                                   
                                }
                                
                            }
                            $ans = $as;
                        
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part1_5',
                                    'code'=>$code,
                                    'ans'=>$ans
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/uce_part1_5/'.base64_encode($code));
                    // }
                    // else
                    // {
                    //     $this->session->set_flashdata('msg',validation_errors());
                    //     redirect(base_url().'BaseController/uce_part1_5/'.base64_encode($code));   
                    // }
                }
                else
                {
                    // $this->form_validation->set_rules('radio1','First Question','required');
                    
                     $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            $l=0;
                            $as = '';
                            $ans = 'radio'.$i;
                            for($b=1;$b<=4;$b++){
                                $answ = $ans.$b;
                                
                                if(isset($_POST[$answ]))
                                {
                                    if($l!=0)
                                    {
                                        $answ = $_POST[$answ];
                                        $as = $as.','.$answ;
                                        
                                    }
                                    else
                                    {
                                        $answ = $_POST[$answ];
                                        $as = $as.$answ;
                                        $l=1;
                                    }
                                   
                                }
                                
                            }
                            $ans = $as;
                        
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part1_5',
                                    'code'=>$code,
                                    'ans'=>$ans
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        // redirect(base_url().'BaseController/uce_part1_5/'.base64_encode($code));   
                        $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                   
                    
                   
                }
                
            }
        }
        public function uce_part2($code)
        {
            $initial_time = microtime(true);
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part2'";
            $this->db->where($where);
            //time check #start 22-1-22
               
           /*  $final_time = microtime(true);
            $duration = $final_time-$initial_time;
                $hours = (int)($duration/60/60);
                $minutes = (int)($duration/60)-$hours*60;
                echo "<h1>duration:$duration , Hours : $hours, Mit : $minutes</h1>";
                //$seconds = (int)$duration-$hours*60*60-$minutes*60;
                $this->uce_part_2_time += $minutes;
                echo "<script>alert('time lap $this->uce_part_2_time')</script>";
            if ($this->uce_part_2_time >1){
                $this->uce_part_2_time = 0;
                redirect(base_url().'BaseController/uce_part2_2/'.base64_encode($code));

            } */
          //   die("work");
            //time check #end 22-1-22
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();           
            if($qno==0)
            {
                $num = '1';
            }
            else if($qno>=18)
            {
                redirect(base_url().'BaseController/uce_part2_2/'.base64_encode($code));
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part2($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part2',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {

                if($num!=16)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='uce_part2'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','First Question','required');
                    $this->form_validation->set_rules('radio3','First Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='uce_part2'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        // $where = "user_id='$email' and code='$code' and part='Part 1'";
                        // $this->db->where($where);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part2_2/'.base64_encode($code));   
                        // $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        // redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2/'.base64_encode($code));   
                    }
                    
                   
                }
                
              
            } //saveBtn #end.
 
        }
        public function uce_part2_2($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part2_2'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno == 0){
                $num = '1';
            }
            else if($qno>=24)
            {
                redirect(base_url().'BaseController/uce_part2_3/'.base64_encode($code));
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part2_2($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part2_2',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=16)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='uce_part2'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part2_2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_2/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );

                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='uce_part2'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        // $where = "user_id='$email' and code='$code' and part='Part 1'";
                        // $this->db->where($where);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part2_3/'.base64_encode($code));   
                        // $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        // redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_2/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }

        public function uce_part2_3($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part2_3'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else if($qno>=20)
            {
                redirect(base_url().'BaseController/uce_part2_4/'.base64_encode($code));
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part2_3($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part2_3',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=16)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='uce_part2'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part2_3/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_3/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','First Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='uce_part2'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        // $where = "user_id='$email' and code='$code' and part='Part 1'";
                        // $this->db->where($where);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part2_4/'.base64_encode($code));   
                        // $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        // redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_3/'.base64_encode($code));   
                    }
                   
                }
                
            }
        }
        public function uce_part2_4($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part2_4'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else if($qno>=40)
            {
                redirect(base_url().'BaseController/uce_part2_5/'.base64_encode($code));
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part2_4($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part2_4',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=36)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_4',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='uce_part2'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part2_4/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_4/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','First Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fourth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_4',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='uce_part2'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        // $where = "user_id='$email' and code='$code' and part='Part 1'";
                        // $this->db->where($where);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part2_5/'.base64_encode($code));   
                        // $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        // redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_4/'.base64_encode($code));   
                    }
                   
                }
                
            }
        }
        public function uce_part2_5($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part2_5'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else if($qno>=90)
            {
                redirect(base_url().'BaseController/uce_part2_6/'.base64_encode($code));
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part2_5($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part2_5',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=96)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_5',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='uce_part2'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part2_5/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_5/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','First Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_5',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='uce_part2'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part2_6/'.base64_encode($code));   
   
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_5/'.base64_encode($code));   
                    }
                   
                }
                
            }
        }
        public function uce_part2_6($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part2_6'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            // else if($qno>=42)
            // {
            //     redirect(base_url().'BaseController/uce_part2_7/'.base64_encode($code));
            // }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part2_6($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part2_6',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=41)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_6',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='uce_part2'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/uce_part2_6/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_6/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','First Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_6',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        // $where3 = "code='$code' and link='uce_part2'";
                        // $this->db->where($where3);
                        // $this->db->set('remain_time',$_POST['remain_time']);
                        // $this->db->update('user_assessment_info');
                        // //that code used after fourth asssessment 
                        // // $this->Base_model->update_code_status($code);
                        // $where = "user_id='$email' and code='$code' and part='Part 2'";
                        // $this->db->where($where);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_assessment_info');
                        // $dt = date("d-m-Y h:m"); 
                        // $where2 = "user_id='$email' and code='$code'";
                        // $this->db->where($where2);
                        // $this->db->set('asignment_submission_date',$dt);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_code_list');

                        // $soc_code = $this->db->get('career_data');
                        // foreach($soc_code->result() as $soc)
                        // {
                        //     $cd = $soc->profession_id;
                            
                        //     $formArray3 = Array(
                        //         'soc_code'=>$cd,
                        //         'code'=>$code
                        //     );
                        //     $this->db->insert('career_all_user_record',$formArray3);
                            
                        // }
                        $where = "user_id='$email' and code='$code' and part='Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_6/'.base64_encode($code));   
                    }
                   
                }
                
            }
        }
        public function uce_part2_7($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='uce_part2_7'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part2_7($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/uce_part2_7',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=31)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_7',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/uce_part2_7/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_7/'.base64_encode($code));   
                    }
                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','First Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fourth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'uce_part2_7',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 3'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        date_default_timezone_set("Asia/Kolkata");
                        $dt = date("d-m-Y H:i");
                        $dt = $dt.' (GMT + 5:30)';
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');

                        $soc_code = $this->db->get('career_sui_temp_latest');
                        foreach($soc_code->result() as $soc)
                        {
                            $formArray3 = Array(
                                'Cluster'=>$soc->Cluster,
                                'Path'=>$soc->Path,
                                'G_1'=>$soc->G_1,
                                'G_2'=>$soc->G_2,
                                'profession_name'=>$soc->profession_name,
                                'code'=>$code
                            );
                            $this->db->insert('career_sui_latest',$formArray3);
                            
                        }
                        // redirect(base_url().'BaseController/uce_part2_6/'.base64_encode($code));   
                        $this->session->set_flashdata('msg','Test Compeleted Please Download Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/uce_part2_7/'.base64_encode($code));   
                    }
                   
                }
                
            }
        }
        public function qce_part1($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='qce_part1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->ppe_part4($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/qce_part1',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=21)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'qce_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/qce_part1/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/qce_part1/'.base64_encode($code));   
                    }
                    
                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'qce_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $where2 = "user_id='$email' and code='$code'";
                        // $this->db->where($where2);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/qce_part1/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        public function qce_part2($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='qce_part2'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->qce_part2($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/qce_part2',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=81)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'qce_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/qce_part2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/qce_part2/'.base64_encode($code));   
                    }
                    
                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Third Question','required');
                    
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'qce_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        // $where2 = "user_id='$email' and code='$code'";
                        // $this->db->where($where2);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/qce_part2/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }

        public function qce_part3($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='qce_part3'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows(); 
            // foreach($qno->result() as $qno)
            // {
            //     $qno = $qno->qno;
                
            // }
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->uce_part1_5($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/qce_part3',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=16)
                {
                    // $this->form_validation->set_rules('radio11','First Question','required');
                    // $this->form_validation->set_rules('radio21','Second Question','required');
                    // $this->form_validation->set_rules('radio33','Third Question','required');
                    // $this->form_validation->set_rules('radio41','Fourth Question','required');
                    // $this->form_validation->set_rules('radio51','Fifth Question','required');
                    // if($this->form_validation->run()==true)
                    // {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            $l=0;
                            $as = '';
                            $ans = 'radio'.$i;
                            for($b=1;$b<=4;$b++){
                                $answ = $ans.$b;
                                
                                if(isset($_POST[$answ]))
                                {
                                    if($l!=0)
                                    {
                                        $answ = $_POST[$answ];
                                        $as = $as.','.$answ;
                                        
                                    }
                                    else
                                    {
                                        $answ = $_POST[$answ];
                                        $as = $as.$answ;
                                        $l=1;
                                    }
                                   
                                }
                                
                            }
                            $ans = $as;
                        
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'qce_part3',
                                    'code'=>$code,
                                    'ans'=>$ans
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/qce_part3/'.base64_encode($code));
                    // }
                    // else
                    // {
                    //     $this->session->set_flashdata('msg',validation_errors());
                    //     redirect(base_url().'BaseController/uce_part1_5/'.base64_encode($code));   
                    // }
                }
                else
                {
                    // $this->form_validation->set_rules('radio1','First Question','required');
                    
                     $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            $l=0;
                            $as = '';
                            $ans = 'radio'.$i;
                            for($b=1;$b<=4;$b++){
                                $answ = $ans.$b;
                                
                                if(isset($_POST[$answ]))
                                {
                                    if($l!=0)
                                    {
                                        $answ = $_POST[$answ];
                                        $as = $as.','.$answ;
                                        
                                    }
                                    else
                                    {
                                        $answ = $_POST[$answ];
                                        $as = $as.$answ;
                                        $l=1;
                                    }
                                   
                                }
                                
                            }
                            $ans = $as;
                        
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'qce_part3',
                                    'code'=>$code,
                                    'ans'=>$ans
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 3'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        date_default_timezone_set("Asia/Kolkata");
                        $dt = date("d-m-Y H:i");
                        $dt = $dt.' (GMT + 5:30)';
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');

                        $soc_code = $this->db->get('career_sui_temp_latest');
                        foreach($soc_code->result() as $soc)
                        {
                            $formArray3 = Array(
                                'Cluster'=>$soc->Cluster,
                                'Path'=>$soc->Path,
                                'G_1'=>$soc->G_1,
                                'G_2'=>$soc->G_2,
                                'profession_name'=>$soc->profession_name,
                                'code'=>$code
                            );
                            $this->db->insert('career_sui_latest',$formArray3);
                            
                        }
                        // redirect(base_url().'BaseController/uce_part1_5/'.base64_encode($code));   
                        $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                }
                
            }
            
        }
        public function cat_part1($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='cat_part1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->cat_part1($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/cat_part1',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=6)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'cat_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        $where3 = "code='$code' and link='cat_part1'";
                        $this->db->where($where3);
                        $this->db->set('remain_time',$_POST['remain_time']);
                        $this->db->update('user_assessment_info');
                        redirect(base_url().'BaseController/cat_part1/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/cat_part1/'.base64_encode($code));   
                    }
                    
                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                  
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'cat_part1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $where2 = "user_id='$email' and code='$code'";
                        // $this->db->where($where2);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Test Compeleted Please Download Your Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/cat_part1/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }

        public function cat_part2($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='cat_part2'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->cat_part2($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/cat_part2',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=36)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'cat_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/cat_part2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/cat_part2/'.base64_encode($code));   
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'cat_part2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $where2 = "user_id='$email' and code='$code'";
                        // $this->db->where($where2);
                        // $this->db->set('status','Rp');
                        // $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Test Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/cat_part2/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }

        public function cat_part3($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='cat_part3'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->ppe_part4($num);
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/cat_part3',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=21)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'cat_part3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/cat_part3/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/cat_part3/'.base64_encode($code));   
                    }
                    
                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'cat_part3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 3'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        date_default_timezone_set("Asia/Kolkata");
                        $dt = date("d-m-Y H:i");
                        $dt = $dt.' (GMT + 5:30)';
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Test Compeleted Please Download Your Report');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/cat_part3/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        
        
        public function ce_disha_part_1($code)
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
            
            
            // Get total questions in assessment

            $max_responses = $this->db->get('ce_nextmove_que')->num_rows();
            $itr_ctr =0;
            
            
            // Get no of responses for a particular code from database
            
            $data['user'] = $user;
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='ce_disha_part_1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            
            // If no responses in database
            if($qno==0)
            {
                $num = '1';
                //echo "No responses found!!<br>";
            }
            else
            if($qno==$max_responses)
            {
                redirect(base_url().'BaseController/ce_disha_part_2/'.base64_encode($code));
            }
            
            // If responses in database
            else
            {
                
                //echo "Responses found!!<br>";
                $this->db->where($where);
                
                // Last question no
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                    //echo $qno;
                }
            }
            
            $que_cnt = $this->Base_model->balance_ques('ce_nextmove_que',$num);
            echo "No of questions fetched :".$que_cnt."<br>";
            $qlist['q'] = $this->Base_model->ce_disha_part_1($num);
            
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/ce_disha_part_1',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                echo "Total no of responses expected :".$max_responses."<br>";
                if($que_cnt==5)
                {
                    
                    //echo "Entered assessment loop<br>";
                    echo $max_responses."<br>";
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ce_disha_part_1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ce_disha_part_1/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ce_disha_part_1/'.base64_encode($code));   
                    }
                    
                }
                else
                {

                    
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    /*
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    */
                    
                    
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ce_disha_part_1',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        date_default_timezone_set("Asia/Kolkata");
                        $dt = date("d-m-Y H:i");
                        $dt = $dt.' (GMT + 5:30)';
                        
                        /** ************************************
                         * Commented by Sudhir
                         
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        
                        
                        ****************************************/
                        
                        
                        $this->session->set_flashdata('msg','Assessment completed. Please move to the next');
                        $itr_ctr = $itr_ctr + 1;
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ce_disha_part_1/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }

        
        
         public function ce_disha_part_2($code)
        {
            $code = base64_decode($code);
            $this->load->model('User_model');
            $this->load->model('Base_model');
            
            $db_tbl = 'ce_nextmove_pref_que';
            $sln_name ='ce_disha_part_2';
            
            $db_tbl_resp = 'ppe_part1_test_details';
            
            $cur_view = 'user/'.$sln_name;
            $cur_ctrl = 'BaseController/'.$sln_name;
            
            $msg_unauthorized = 'You are not authorized to access this section';
            
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg', $msg_unauthorized);
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            
            
            // Get total questions in assessment

            $max_responses = $this->db->get($db_tbl)->num_rows();
            $itr_ctr =0;
            
            
            // Get no of responses for a particular code from database
            
            $data['user'] = $user;
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='$sln_name'";
            $this->db->where($where);
            $qno = $this->db->get($db_tbl_resp)->num_rows();
            
            // If no responses in database
            if($qno==0)
            {
                $num = '1';
                //echo "No responses found!!<br>";
            }
            
            // If responses in database
            else 
            if($qno == $max_responses){
                
            }
            else
            {
                
                //echo "Responses found!!<br>";
                $this->db->where($where);
                
                // Last question no
                $qno = $this->db->limit(1)->order_by('id','desc')->get($db_tbl_resp); 
                
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                    //echo $qno;
                }
            }
            
            
            $que_cnt = $this->Base_model->balance_ques($db_tbl,$num);
            echo "No of questions fetched :".$que_cnt."<br>";
            $qlist['q'] = $this->Base_model->fetch_que($db_tbl,$num);
            
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view($cur_view,$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                echo "Total no of responses expected :".$max_responses."<br>";
                if($que_cnt==5)
                {
                    
                    //echo "Entered assessment loop<br>";
                    echo $max_responses."<br>";
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>$sln_name,
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert($db_tbl_resp,$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ce_disha_part_2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().$cur_ctrl.base64_encode($code));   
                    }
                    
                }
                else
                {

                    
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    /*
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    */
                    
                    
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>$sln_name,
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert($db_tbl_resp,$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='Part 2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        date_default_timezone_set("Asia/Kolkata");
                        $dt = date("d-m-Y H:i");
                        $dt = $dt.' (GMT + 5:30)';
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Test Compeleted Please Download Your Report');
                        $itr_ctr = $itr_ctr + 1;
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().$cur_ctrl.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }
        
        
        public function finish_time()
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

            if($this->input->is_ajax_request()){
               
                
                    $code = $this->input->post('code');
                    $part = $this->input->post('part');
                    $this->db->where('current_link',$part);
                    $row = $this->db->get('services_list')->row();
                    $db_name = $row->db_name;

                    $num1 = $this->db->get($db_name)->num_rows();
                    $where = "code='$code' and solution='$part'";
                    $this->db->where($where);
                    $num2 = $this->db->get('ppe_part1_test_details')->num_rows();
                    $num3 = $num1 - $num2;
                    for($i=1;$i<=$num3;$i++)
                    {
                        $num2 = $num2 + 1;
                        $formArray = Array(
                            'email'=>$email,
                            'qno'=>$num2,
                            'solution'=>$part,
                            'code'=>$code,
                            'ans'=>0
                        );
                        $this->db->insert('ppe_part1_test_details',$formArray);
                        
                    }
                    $where2= "code='$code' and link='$part'";
                    $this->db->where($where2);
                    $this->db->set('status','Rp');
                    $this->db->update('user_assessment_info');
                    $data = array('responce'=>'success','message'=>'success');
                
            }
            else{
                echo "No direct script access allowed";
            }
            echo json_encode($data);
        }
        //work here by Danish. #STAR here

        public function ecp_part_1($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='ecp_part_1'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wpa_part1($num);

            if(isset($_POST['saveBtn']))
            {
                if($num!=41)
                {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                                $qnm = 'qnm'.$i;
                                $qnm = $_POST[$qnm];
                                $ans = 'q'.$i;
                                $type = 'type'.$i;
                                $type = $_POST[$type];
                                if($type!='cb')
                                {
                                    if(!isset($_POST[$ans]))
                                    {
                                        $ans = '0';
                                    }
                                    else
                                    {
                                        $ans = $_POST[$ans];
                                    }
                                }
                                else
                                {
                                   
                                        $this->db->where('qno',$qnm);
                                        $c_num = $this->db->get('wpa_part1_options')->num_rows();
                                       
                                        $as = '';
                                        $l = 0;
                                        for($b=1;$b<=$c_num;$b++){
                                            $answ = $ans.$b;
                                            
                                            if(isset($_POST[$answ]))
                                            {
                                                if($l!=0)
                                                {
                                                    $answ = $_POST[$answ];
                                                    $as = $as.','.$answ;
                                                    
                                                }
                                                else
                                                {
                                                    $answ = $_POST[$answ];
                                                    $as = $as.$answ;
                                                    $l=1;
                                                }
                                               
                                            }
                                            
                                        }
                                        $ans = $as;
                                    
                                }
                               
                                $where = "qno='$qnm' and email='$email' and code='$code' and solution='ecp_part_1'";
                                $this->db->where($where);
                                $c_num = $this->db->get('ppe_part1_test_details')->num_rows();
                                if($c_num==0)
                                {
                                    $formArray = Array(
                                        'email'=>$email,
                                        'solution'=>'ecp_part_1',
                                        'code'=>$code,
                                        'qno'=>$qnm,
                                        'ans'=>$ans  
                                    );
                                    $this->db->insert('ppe_part1_test_details',$formArray);
                                    $ans = '';
                                }
                                else
                                {
                                    $this->db->set('ans',$ans);
                                    $this->db->where($where);
                                    $this->db->update('ppe_part1_test_details');
                                }
                                
                            $i++;
                              
                        }
                        redirect(base_url().'BaseController/ecp_part_1/'.base64_encode($code));
                    

                }
                else
                {
                   
                    $i=1;
                    foreach($qlist['q']->result() as $q)
                    {   
                            $qnm = 'qnm'.$i;
                            $qnm = $_POST[$qnm];
                            $ans = 'q'.$i;
                            $type = 'type'.$i;
                            $type = $_POST[$type];
                            if($type!='cb')
                            {
                                if(!$_POST[$ans])
                                {
                                    $ans = '0';
                                }
                                else
                                {
                                    $ans = $_POST[$ans];
                                }
                            }
                            else
                            {
                               
                                    $this->db->where('qno',$qnm);
                                    $c_num = $this->db->get('wpa_part1_options')->num_rows();
                                   
                                    $as = '';
                                    $l = 0;
                                    for($b=1;$b<=$c_num;$b++){
                                        $answ = $ans.$b;
                                        if($_POST[$answ])
                                        {
                                            if($l!=0)
                                            {
                                                $answ = $_POST[$answ];
                                                $as = $as.','.$answ;
                                                
                                            }
                                            else
                                            {
                                                $answ = $_POST[$answ];
                                                $as = $as.$answ;
                                                $l=1;
                                            }
                                           
                                        }
                                        
                                    }
                                    $ans = $as;
                                
                            }
                           
                            $where = "qno='$qnm' and email='$email' and code='$code' and solution='ecp_part_1'";
                            $this->db->where($where);
                            $c_num = $this->db->get('ppe_part1_test_details')->num_rows();
                            if($c_num==0)
                            {
                                $formArray = Array(
                                    'email'=>$email,
                                    'solution'=>'ecp_part_1',
                                    'code'=>$code,
                                    'qno'=>$qnm,
                                    'ans'=>$ans  
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                                $ans = '';
                            }
                            else
                            {
                                $this->db->set('ans',$ans);
                                $this->db->where($where);
                                $this->db->update('ppe_part1_test_details');
                            }
                            
                        $i++;
                          
                    }
                        $where = "user_id='$email' and code='$code' and part='part_1'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                       
                        $this->session->set_flashdata('msg','Part 1 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                            
                    
                }    
            }
            
            if(isset($_POST['saveBtn2']))
            {
                $where = "email='$email' and code='$code' and solution='ecp_part_1'";
                $this->db->where($where);
                $qno = $this->db->get('ppe_part1_test_details')->num_rows();
                if($qno>0)
                {
                    $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                    foreach($qno->result() as $qno)
                    {
                        $qno = $qno->qno;
                        $num = $qno - 4;
                    }
                    $qlist['q'] = $this->Base_model->wpa_part1($num);
                }
            }

            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wpa_part1',$qlist);
            $this->load->view('footer'); 
          
        } //ecp_part_1 function #END.

        public function ecp_part_2($code)
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
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='ecp_part_2'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Base_model->wla_part1($num);
           
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/wla_part1',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=56)
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ecp_part_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'BaseController/ecp_part_2/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ecp_part_2/'.base64_encode($code));   
                    }
                    

                }
                else
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ecp_part_2',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "user_id='$email' and code='$code' and part='part_2'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        $this->session->set_flashdata('msg','Part 2 Compeleted Please Take Next Assessment');
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ecp_part_3/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }

        
        public function ecp_part_3($code)
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
            
            
            // Get total questions in assessment

            $max_responses = $this->db->get('cb_ecp_questions')->num_rows();
            $itr_ctr =0;
            
            
            // Get no of responses for a particular code from database
            
            $data['user'] = $user;
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='ecp_part_3'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            
            // If no responses in database
            if($qno==0)
            {
                $num = '1';
                //echo "No responses found!!<br>";
            }
            else
            if($qno==$max_responses)
            {
                redirect(base_url().'BaseController/ecp_part_3/'.base64_encode($code));
            }
            
            // If responses in database
            else
            {
                
                //echo "Responses found!!<br>";
                $this->db->where($where);
                
                // Last question no
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                    //echo $qno;
                }
            }
            
            $que_cnt = $this->Base_model->balance_ques('cb_ecp_questions',$num);
            // echo "No of questions fetched :".$que_cnt."<br>";
            $qlist['q'] = $this->Base_model->cb_ecp_part_3($num);
            
            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/cb_ecp_part_3',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                //echo "Total no of responses expected :".$max_responses."<br>";
                if($que_cnt==5)
                {
                    
                    //echo "Entered assessment loop<br>";
                    echo $max_responses."<br>";
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
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ecp_part_3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }

                        $where = "email='$email' and code='$code' and solution='ecp_part_3'";
                        $this->db->where($where);
                        $last_qno_set = $this->db->get('ppe_part1_test_details')->num_rows();
                       
                        if($last_qno_set==$max_responses){
                        $where = "user_id='$email' and code='$code' and part='part_3'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        date_default_timezone_set("Asia/Kolkata");
                        $dt = date("d-m-Y H:i");
                        $dt = $dt.' (GMT + 5:30)';
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Test Compeleted Please Download Your Report');
                        $itr_ctr = $itr_ctr + 1;
                        redirect(base_url().'BaseController/view_code');   
                        } 
                       // redirect(base_url().'BaseController/ecp_part_3/'.base64_encode($code));
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ecp_part_3/'.base64_encode($code));   
                    }
                    
                }
                else
                {

                    
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    // $this->form_validation->set_rules('radio3','Third Question','required');
                    /*
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    */
                    
                    
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>'ecp_part_3',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment                        
                        

                        $where = "user_id='$email' and code='$code' and part='part_3'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('user_assessment_info');
                        date_default_timezone_set("Asia/Kolkata");
                        $dt = date("d-m-Y H:i");
                        $dt = $dt.' (GMT + 5:30)';
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        $this->session->set_flashdata('msg','Test Compeleted Please Download Your Report');
                        $itr_ctr = $itr_ctr + 1;
                        redirect(base_url().'BaseController/view_code');   
                        
                        /** ************************************
                         * Commented by Sudhir
                         
                        $where2 = "user_id='$email' and code='$code'";
                        $this->db->where($where2);
                        $this->db->set('status','Rp');
                        $this->db->set('asignment_submission_date',$dt);
                        $this->db->update('user_code_list');
                        
                        
                        ****************************************/
                        
                        
                        $this->session->set_flashdata('msg','Assessment completed. Please move to the next');
                        $itr_ctr = $itr_ctr + 1;
                        redirect(base_url().'BaseController/view_code');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/ecp_part_3/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
        }

        public function view_trainings()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data = $this->initializer();
            $data["training_info"] = $this->sp_vocation_training_info("SHORT_INFO");
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar',$data); 
            $this->load->view('user/view_trainings',$data); 
            $this->load->view('footer');
        }

        public function user_training()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data["apply_training"] = $this->sp_vocation_training_info("APPLY_TRAINING");
            $data = $this->initializer();
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar',$data); 
            $this->load->view('user/user_training',$data); 
            $this->load->view('footer');
        }

        protected function sp_vocation_training_info($action){
            $user = $this->session->userdata('user');
            switch($action){
                case "SHORT_INFO":
                    $this->db->select("a.id,a.sp_id,a.training_name,a.training_img,a.training_desc,a.certification_status,b.email,b.fullname,b.profile_photo");
                    $this->db->distinct("a.email");           
                    // $this->db->limit($limit,$offset);
                    $this->db->order_by("a.id","desc");
                    $this->db->where(["a.training_status"=>"approved"]);
                    $this->db->join("user_details b","b.id = a.sp_id","inner");
                    $q = $this->db->get("vocational_training a");
                    $q = $q->result_array();
                    if(!empty($q)){
                        $filter_data = [];
                        foreach($q as $v){
                            $filter_data[] = [
                                "t_id"                      =>  $v['id'],
                                "sp_id"                     =>  $v['sp_id'],
                                "email"                     =>  $v['email'],
                                "fullname"                  =>  $v['fullname'],
                                "training_img"              => ($v['training_img'] != null)? base_url("uploads/vocational_training/$v[training_img]"): base_url("uploads/vocational_training/default-training-img.svg"), 
                                "training_name"             =>  $v['training_name'],
                                "training_desc"             =>  word_limiter($v['training_desc'],60),
                                "certification_status"      =>  ($v['certification_status'] == "approved")?"approved":null,
                            ];
                        }
                        return $filter_data;
                    }

                break;
                case "APPLY_TRAINING":
                    $this->db->select("a.training_status,a.apply_date,b.training_name,b.sp_id,c.fullname");
                    $this->db->where(["a.user_email"=>$user['email']]);
                    $this->db->order_by("a.id","desc");
                    $this->db->order_by("a.id","desc");
                    $this->db->join("vocational_training b","b.id = a.training_id","inner");
                    $this->db->join("user_details c","c.id = b.sp_id","inner");
                    $q = $this->db->get("vocational_training_apply_user a");
                    return $q->result_array();
                break;
            }
        }

        public function all_jobs()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $ref_data =  $this->session->userdata("ref_data");
            $data["highlight"] = null;
            if(!empty($ref_data)){
              $data["highlight"] = $ref_data["condition_id"];
            }
            $data["job_lists"] = $this->User_model->jobs_info("POST_JOB_LIST",$data["highlight"]);
            $data = $this->initializer();
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar',$data); 
            $this->load->view('user/all_jobs',$data); 
            $this->load->view('footer');
            $this->session->unset_userdata("ref_data");
        }

        public function apply_jobs()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $ref_data =  $this->session->userdata("ref_data");
            // print_r($ref_data);
            $data["highlight"] = null;
            if(!empty($ref_data)){
              $data["highlight"] = $ref_data["condition_id"];
            }
            $data["job_apply_lists"] = $this->User_model->jobs_info("APPLY_JOB_LIST",$data["highlight"]);
            $data = $this->initializer();
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar',$data); 
            $this->load->view('user/apply_jobs',$data); 
            $this->load->view('footer');
            $this->session->unset_userdata("ref_data");
        }

        function ajax_work($action){
            $json_msg = [];
            switch($action){
                case "JOB_APPLY":
                   $job_id =  $this->input->post("jid");
                   if(!empty($job_id)){
                        $user_email = $this->input->post("uid");              
                       $q_sub = $this->db->get_where("jobs_apply_user",["job_id"=>$job_id,
                       "user_email"=>$user_email,
                       "job_status"=>"apply"]);
                        if($q_sub->num_rows() == 0){
                            $this->db->insert("jobs_apply_user",["job_id"=>$job_id,"user_email"=>$user_email]);
                            if($this->db->affected_rows() > 0){
                                $json_msg["msg"] = "APPLIED";
                            }
                        }else{
                            $json_msg["msg"] = "job_status";
                            $json_msg["data"] = $q_sub->row()->job_status;
                        }
                   }
                break;
                case "apply_jobs_with_cv":
                    $user_email = $this->input->post("uid");
                    $job_id = $this->input->post("jid");
                    $upload_path="uploads/jobs_cv/"; 
                    $new_name = time() . '-cv-' . $_FILES["cv_file"]['name'];
                    $config = array(
                    'upload_path' => $upload_path,
                    'file_name'=>$new_name,
                    'allowed_types' => "zip",
                    'max_size' => "2048000", //2.048mb
                    'max_height' => "0",
                    'max_width' => "0"
                    );
                       
                    $q_sub = $this->db->get_where("jobs_apply_user",["job_id"=>$job_id,
                       "user_email"=>$user_email,
                       "job_status"=>"apply"]);
                        if($q_sub->num_rows() == 0){

                            $this->load->library('upload', $config);
                            if($this->upload->do_upload('cv_file'))
                            { 
                                $arr_file_upload = $this->upload->data();

                                $this->db->insert("jobs_apply_user",[
                                    "job_id"=>$job_id,
                                    "user_email"=>$user_email,
                                    "cv_path"=>$arr_file_upload['file_name']
                                ]);
                                if($this->db->affected_rows() > 0){
                                    $json_msg["msg"] = "APPLIED";
                                }                            
                            }else{
                                $json_msg["msg"] = "ERROR_IMG";
                                $json_msg["ERROR_IMG_DESC"] =  $this->upload->display_errors();
                            } 

                        }else{
                            $json_msg["msg"] = "job_status";
                            $json_msg["data"] = $q_sub->row()->job_status;
                        } 
                break;
            }

            $this->output
            ->set_content_type("application/json")
            ->set_output(json_encode($json_msg));
        }
        //work here by Danish. #END here

        public function purchase_code_history()
        {
            // $this->load->model('User_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            // $user = $this->session->userdata('user');
            // $data['user'] = $user;
            // $data['reseller_sp'] = $this->Commen_model->get_reseller_sp($data['user']['user_id']);
            $data = $this->initializer();
            $data['purchase_history'] = $this->User_model->purchase_code_history_list($data['user']['email']);
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar'); 
            $this->load->view('user/purchase_code_history',$data); 
            $this->load->view('footer');
        }

        function user_request_code_online($email,$r_email,$solution_id){
            $this->load->model("Sp_model");
            $this->load->model("User_model");
            $purchase_code_details = [
                "u_email"           => urldecode($email),
                "r_email"           => urldecode($r_email),
                "solution_id"       => urldecode($solution_id)
            ];

        $sp_details = $this->Sp_model->get_sp_details("DETAILS_BY_EMAIL",$purchase_code_details['r_email']);
        $user_details = $this->User_model->get_user_details("DETAILS_BY_EMAIL",$purchase_code_details['u_email']);
        $solution_details = $this->User_model->get_user_details("SOLUTION_BY_NAME",$purchase_code_details['solution_id']);
            $user = $this->session->userdata('user');
            $data['user'] = $user;

           
            //echo $reseller_id;die;
            // $this->db->select('user_id, content, date');
            //  $query = $this->db->get('user_details')
            //initialize payment api by razarpay
            $payment_api = new Api(API_KEY, API_SECRET);        
            $order_data = [
                'receipt'         => "rcptid_".$user_details["id"]."_".rand(1000,100000),
                'amount'          => $solution_details['mr_price']*100, // 39900 rupees in paise
                'currency'        => 'INR'
            ];

            //create payment 
            $create_order = $payment_api->order->create($order_data);

            //insert all required info in DB.
            $save_data = [
                "r_email"                   => $sp_details->email,
                "u_email"                   => $user_details['email'],
                "solution_id"               => $solution_details['id'],
                "solution_name"             => $solution_details['solution'],
                "solution_price"            => $solution_details['mr_price'],
                "transaction_id"            => $create_order['id'],
                "amount_paise"              => $create_order['amount'],
                "currency"                  => $create_order['currency'],
                "receipt"                   => $create_order['receipt'],
                "transaction_status"        => $create_order['status'],
                "transaction_created_at"    => $create_order['created_at']
            ];

            $this->db->insert("user_payment_history",$save_data);
            
            $purchase_code_details = [
                "transaction_id"    => $create_order['id'],
                "u_email"           => urldecode($email),
                "r_email"           => urldecode($r_email),
                "solution_id"       => urldecode($solution_id)
            ];

            $this->session->set_userdata("purchase_code_details",$purchase_code_details);
            redirect(base_url().'payment/checkout');
        }
        
        
        public function ocs_part($part,$sol,$code)
        {  
            //$sol='ocs';
            if($this->is_base64_encoded($code)){
                $code =  base64_decode($code);
            }
    
            $db_tbl_write = 'ppe_part1_test_details';
            $data = $this->initializer();
            $db_tbl = $this->config->item('asmt_map')['sol_tbl'][$sol][$part];
            
            
            $q_redirect = $this->config->item('asmt_map')['sol_tbl']['all_options'][$db_tbl]['q_cnt'];
            $view_name = $this->config->item('asmt_map')['sol_tbl']['all_options'][$db_tbl]['opt'];
            //echo $view_name;die;
            
            $len_tbl = sizeof($this->config->item('asmt_map')['sol_tbl'][$sol]);
            //echo $len_tbl."<br>".$part;die();
            //echo $len_tbl;die();
            if($part<$len_tbl)
            {
                $db_tbl_next = $this->config->item('asmt_map')['sol_tbl'][$sol][$part+1]; 
                //$solution_next = $sol."_part/".($part +1 );
                $solution_next = "ocs_part/".($part +1 )."/".$sol; 
                //echo $solution_next;die();
            }
            
            $solution_name = $db_tbl;
            //$solution_name = $sol."_part/".$part;
            $solution_name = $sol."_part/".$part."/".$sol;
            $db_solution_name=$sol."_part/".$part;
            $soln_name = "ocs_part/".$part."/".$sol;
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='$db_solution_name'";
            $this->db->where($where);
            $qno = $this->db->get($db_tbl_write)->num_rows();
            //echo "qno1".$qno;die();
            if($qno==0)
            {
                $num = '1';
            }
            else if($qno>=$q_redirect)
            {   //echo $solution_next;
                //echo "Change Status";die();
                $asmt_part="Part ".$part;

                //echo $asmt_part.$email.$code;die();
                $where = "user_id='$email' and code='$code' and part='$asmt_part'";
                $this->db->where($where);
                $this->db->set('status','Rp');
                $this->db->update('user_assessment_info');
                
                if($part == $len_tbl)
                {
                    date_default_timezone_set("Asia/Kolkata");
                    $dt = date("d-m-Y H:i");
                    $dt = $dt.' (GMT + 5:30)';
                    $where2 = "user_id='$email' and code='$code'";
                    $this->db->where($where2);
                    $this->db->set('status','Rp');
                    $this->db->set('asignment_submission_date',$dt);
                    $this->db->update('user_code_list');
                    
                    redirect(base_url().'BaseController/view_code');
                    
                }

                redirect(base_url().'BaseController/'.$solution_next."/".base64_encode($code));
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get($db_tbl_write); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
  
            $qlist['q'] = $this->Base_model->get_questions($num,$db_tbl);

            
            $this->load->view('navbar3',$data);
            $this->load->view('user/sidebar'); 
            if($view_name=='uce_part2_2')
            {
                $this->load->view('user/'.$view_name,$qlist);
            }
            else
            {
                $this->load->view('user/ocs_part',$qlist);
            }
             
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                //echo "here<br>";
                //echo $num."<br>";
                if($num!=($q_redirect))
                {

                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    //$this->form_validation->set_rules('radio4','Fourth Question','required');
                    //$this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {   
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>$db_solution_name,
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert($db_tbl_write,$formArray);
                            $i++;
                            
                        }
                        if($solution_name){
                            
                            //echo "in if sol name got";die();
                            redirect(base_url().'BaseController/'.$soln_name.'/'.base64_encode($code));
                        }else{
                            //echo "in else sol name not got";die();
                            
                            
                            redirect(base_url().'BaseController/view_code');
                        }
                    }
                    else
                    {
                        
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/'.$soln_name.'/'.base64_encode($code));   
                    }
                    

                }
                else
                {
                    
                    // echo "condition2<br>";
                    $aaa = $q_redirect - $num + 1 ;
                    // echo $aaa."<br>";die;
                    $arrMsg = ['First ','Second ','Third ','Fourth ','Fifth '];
                    for ($i=0;$i<$aaa;$i++)
                    {
                        $this->form_validation->set_rules('radio1', $arrMsg[$i].'Question','required');
                    }
                    
                    
                    
                    // $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->qno,
                                    'solution'=>$db_solution_name,
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert($db_tbl_write,$formArray);
                            $i++;
                            
                        }

                        redirect(base_url().'BaseController/'.$solution_name.'/'.base64_encode($code));   
     
                    }
                    else
                    {   
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'BaseController/'.$solution_name.'/'.base64_encode($code));   
                    }
                    
                   
                }
                
            }
            
               
        }
        
            public function is_base64_encoded($data)
                {
                    if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
                       return TRUE;
                    } else {
                       return FALSE;
                    }
                }
        
        
    } //class #END. here

?>