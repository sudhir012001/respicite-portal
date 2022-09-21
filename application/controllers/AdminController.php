<?php 
    class AdminController extends CI_Controller
    {
        function __construct() {
            parent::__construct();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            $this->load->model('Admin_model');
            
            
        }

        protected $view_shared =[
            'na'=>'not_shared',
            'global'=>'shared_globally', 
            'admin' => 'shared_admin', 
            'sp' =>'shared_sp', 
            'reseller'=>'shared_reseller', 
            'user' => 'shared_user'
        ];

        protected $controller_role_mapping = [
            'AdminController'=>'admin',
        ];

        public function index()
        {
            echo "No Direct Access allow";
        }
        //admin dashboard
        public function dashboard()
        {
            
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
                      
           $data['calendly_url']='https://calendly.com/';
            $this->load->view('navbar',$data);

            //new
            $this->load->view('admin/sidebar',$data);
            //old
            // $this->load->view('admin/sidebar');
            
            $this->load->view('admin/dashboard');
            $this->load->view('footer');
        }
        //approve reseller code
        public function approve_reseller()
        {
            $this->load->database();
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $unapprove_reseller['h'] = $this->Admin_model->get_unapprove_reseller(true);
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/unapprove_reseller_list',$unapprove_reseller);
            $this->load->view('footer');
        }
        public function updateapproval($resellerId)
        {
            $this->load->model('Admin_model');
            b:
            $cd = $this->Admin_model->generate_reseller_code();
            $check_code = $this->Admin_model->check_reseller_code($cd);
            if($check_code>0)
            {
                goto b; 
            }
            else
            {
                $this->db->set('status','2');
                $this->db->set('user_id',$cd);
                $this->db->where('id',$resellerId);
                $this->db->update('user_details');
                $this->session->set_flashdata("msg","Reseller Approved");
                redirect('/AdminController/approve_reseller','refresh');
            }
            
        }

        //pending approval show
        public function approve_service_code()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $unapprove_code['h'] = $this->Admin_model->get_unapprove_code(true);
            // echo "<pre>";
            // print_r($unapprove_code['h']);
            // echo "</pre>";
            // die;

            
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/unapproved_code_list',$unapprove_code);
            $this->load->view('footer');
        }
        
        //update update code request approval
        public function code_approval($id)
        {
             
            $this->load->model('Admin_model');   
            $row = $this->Admin_model->update_status_code($id); 
            foreach ($row->result() as $row)
            {
                $code = $row->code;
                $email = $row->email;
                $this->db->where('email',$email);
                $this->db->select('*');
                $reseller_details = $this->db->get('user_details');
                foreach($reseller_details->result() as $reseller_row)
                {
                    $user_id = $reseller_row->user_id;
                }

                $this->db->where('code_id',$code);
                $row2 = $this->db->get('reseller_code_record');
                foreach($row2->result() as $row2)
                {
                    $solution = $row2->solution;
                    $qty = $row2->no_of_reports;
                }

                $this->db->where('solution',$solution);
                $rp = $this->db->get('solutions');
                foreach($rp->result() as $rp)
                {
                    $group = $rp->c_group;
                }

                for($i=0;$i<$qty;$i++)
                {
                    a:
                    $cd = $this->Admin_model->generate_code();
                    $cd = 'A'.$user_id.$cd;
                    $check_code = $this->Admin_model->check_code($cd);
                    if($check_code>0)
                    {
                        goto a; 
                    }
                    else
                    {
                       
                        $code_data = array(
                            'email' => $email,
                            'solution' => $solution,
                            'code' => $cd,
                            'type' => 'reseller',
                            'status' => 'UnUsed'
                        );
                        $this->Admin_model->insert_code($code_data);
                    }
                }
                $where = "email='$email' and solution='$solution'";
                $this->db->where($where);
                $num = $this->db->get('reseller_certification')->num_rows();
                if($num==0)
                {   
                    $for_certification = array(
                        'email' => $email,
                        'c_group' => $group,
                        'solution' => $solution,
                        'status' => '1',
                        'c_status' => '0',
                        'per'=>'',
                        'dt' => ''
                    );
                    $this->db->insert('reseller_certification',$for_certification);
                }
                
            }
            $this->session->set_flashdata("msg","Code Approved");
            redirect('/AdminController/approve_service_code','refresh');
        }

        public function view_reseller()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $reseller['h'] = $this->Admin_model->fetch_reseller(true);
            $reseller['landingPages'] = $this->Admin_model->landing_page(true);
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/view_reseller_list',$reseller);
            $this->load->view('footer');
        }

        public function add_services()
        {
            $this->load->library('form_validation');
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_service_form');
            $this->load->view('footer');
            
        }
        public function insert_services()
        {
               
                $this->form_validation->set_rules('solution_name','Solution','required');
                $this->form_validation->set_rules('description','Description','required');
                $this->form_validation->set_rules('package','No of Reports','required');
                //$this->form_validation->set_rules('mrp','mrp','required');
                //$this->form_validation->set_rules('discount','Discount','required');
               
                if($this->form_validation->run() == true)
                {            
                       
                    $solution = $_POST['solution_name'];
                    $package = $_POST['package'];  
                    $code_id = $solution.$package;
                    $this->db->where('code_id',$code_id);
                    $rowcount = $this->db->get('reseller_code_record')->num_rows();
                    echo $rowcount;
                    if($rowcount == 0)
                    {
                        $formArray = array(
                            'code_id'=>$code_id,
                            'solution'=>$_POST['solution_name'],
                            'discription'=>$_POST['description'],
                            'no_of_reports'=>$_POST['package'],
                            'mrp'=>$_POST['mrp'],
                            'discount'=> $_POST['discount'],
                            'status' => 'active'
                        );
                        $formArray2 = array(
                            'solution'=>$_POST['solution_name'],
                            'description'=>$_POST['description']
                        );
                        $this->db->where('solution',$solution);
                        $rowcount2 = $this->db->get('solutions')->num_rows();
                        if($rowcount2 == 0)
                        {
                            // redirect('AdminController/ch');
                            
                            $this->db->insert('solutions',$formArray2);
                            $this->db->insert('reseller_code_record',$formArray);
                        }
                        else
                        {
                            // redirect('AdminController/dch');
                            $this->db->insert('reseller_code_record',$formArray);
                        }
                        $this->session->set_flashdata("msg","Package Saved Successfully");
                        redirect('/AdminController/add_services','refresh');
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","This Package Already Exist");
                        redirect('/AdminController/add_services','refresh');
                    }
                    
                                      // $this->session->set_flashdata("msg","Your account has been registered. You can login now");
                    // redirect('/UserController/registration','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field Required");
                    redirect('/AdminController/add_services','refresh');
                } 
        }
        public function view_services()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $solution['s'] = $this->User_model->solutions_list();
            
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/view_services',$solution);
            $this->load->view('footer');
        }
        public function confg_counseling_link()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $spResellers['spResellers'] = $this->User_model->spResellers();
            
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/view_counseling_link',$spResellers);
            $this->load->view('footer');
        }
        
        public function edit_services()
        {
            $this->load->model('Admin_model');
            if($this->input->is_ajax_request()){
                $edit_id = $this->input->post('edit_id');
                if($post = $this->Admin_model->edit_data($edit_id)){
                    $data = array('responce' => 'success', 'post' => $post);
                }
                else
                {
                    $data = array('responce' => 'error', 'message' => 'failed');
                }
    
            }
            echo json_encode($data);
        }

        public function update_services_data()
        {
            if(isset($_POST['update']))
            {
                $this->form_validation->set_rules('edit_report','No of Reports','required');
                $this->form_validation->set_rules('edit_mrp','MRP','required');
                $this->form_validation->set_rules('edit_dis','Discount','required');
                
                if($this->form_validation->run() == true)
                {
                    $id = $_POST['edit_id'];
                    $solution = $_POST['solution'];
                    $rep=$_POST['edit_report'];
                    $mrp= $_POST['edit_mrp'];
                    $dis= $_POST['edit_dis'];
                    $disc= $_POST['edit_discription'];

                    $code_id = $solution.$rep;
                    $this->db->where('code_id',$code_id);
                    $n = $this->db->get('reseller_code_record')->num_rows();
                    if($n == 0)
                    {
                        $formArray = array(
                            'code_id'=>$code_id,
                            'solution'=>$solution,
                            'discription'=>$disc,
                            'no_of_reports'=>$rep,
                            'mrp'=>$mrp,
                            'discount'=> $dis,
                            'status' => 'active'
                        );
                        $this->db->insert('reseller_code_record',$formArray);

                        $this->db->set('status','inactive');
                        $this->db->where('id',$id);
                        $this->db->update('reseller_code_record');
                        $this->session->set_flashdata("msg","Details Updated");
                        redirect('/AdminController/view_services','refresh');

                    }
                    else
                    {
                        $this->db->set('status','inactive');
                        $this->db->where('id',$id);
                        $this->db->update('reseller_code_record');
                        
                        $this->db->set('mrp',$mrp);
                        $this->db->set('discount',$dis);
                        $this->db->set('discription',$disc);
                        $this->db->set('status','active');
                        $this->db->where('code_id',$code_id);
                        $this->db->update('reseller_code_record');

                        
                        $this->session->set_flashdata("msg","Details Updated");
                        redirect('/AdminController/view_services','refresh');
                    }
                    
                   
                    
                }
            }
            
        } 
        
        public function delete_services($id)
        {
            $this->db->set('status','inactive');
            $this->db->where('id',$id);
            $res = $this->db->update('reseller_code_record');
            if(!$res)
            {
                $this->session->set_flashdata("msg","Something went wrong");
                redirect('/AdminController/view_services','refresh');
            }
            else
            {
                $this->session->set_flashdata("msg","Record Deleted");
                redirect('/AdminController/view_services','refresh');
            }
        }
         public function allowed_services_update($id)
        {
           // echo $id;die;
            $this->load->model('Admin_model');
            $userData = $this->Admin_model->getUserDetailsById($id);
            //echo $userData;die;
            if($userData!=''){
                //$counselling = ($coun=='NA')?'counselling':'';
                $str = $userData;
                 $rr = explode(",",$str);
                if (in_array("counselling", $rr)){
                    if (($key = array_search('counselling', $rr)) !== false) {
                        unset($rr[$key]);
                        $allow = implode(",",$rr);
                    }
                  $this->Admin_model->allowed_services_update($id,$allow);
                }else{
                 $this->Admin_model->allowed_services_update($id,$userData.',counselling');
                }
               
            }else{
                $this->Admin_model->allowed_services_update($id,'counselling');
            }
            //print_r($userData);die;
             redirect('/AdminController/confg_counseling_link','refresh');
        }
         public function allowed_services_offline($id)
        {
           // echo $id;die;
            $this->load->model('Admin_model');
            $userData = $this->Admin_model->getUserDetailsById($id);
            //echo $userData;die;
            if($userData!=''){
                //$counselling = ($coun=='NA')?'counselling':'';
                $str = $userData;
                 $rr = explode(",",$str);
                if (in_array("offline", $rr)){
                    if (($key = array_search('offline', $rr)) !== false) {
                        unset($rr[$key]);
                        $allow = implode(",",$rr);
                    }
                  $this->Admin_model->allowed_services_update($id,$allow);
                }else{
                 $this->Admin_model->allowed_services_update($id,$userData.',offline');
                }
               
            }else{
                $this->Admin_model->allowed_services_update($id,'offline');
            }
            //print_r($userData);die;
             redirect('/payment-gateway/configure','refresh');
        }
        public function edit_reseller_status()
        {
            
            
            $id = $this->input->post('s');
			$act = $this->input->post('act');
            if($act=='active')
            {
                $status = '2';
            }
            else if($act=='inactive')
            {
                $status = '3';
            }
            else if($act=='delete')
            {
                $status = '4';
            }
            $this->db->set('status',$status);
            $this->db->where('id',$id);
            $this->db->update('user_details');
            $data = array('responce'=>'success','message'=>'Data update Successfully');
            echo json_encode($data);
        }
        public function select_landing_page()
        {
            
            
            $resellerid = $this->input->post('resellerid');
			$landId = $this->input->post('landId');
			
			$this->load->model('Admin_model');
            $reseller = $this->Admin_model->updateLanding($resellerid,$landId);
            if($reseller){
                $data = array('response'=>'success','message'=>'Data update Successfully');
            }else{
               $data = array('response'=>'false','message'=>'Not update Data'); 
            }
            
            echo json_encode($data);
        }
        public function view_sp()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $reseller['h'] = $this->Admin_model->fetch_sp(true);
            $reseller['landingPages'] = $this->Admin_model->landing_page(true);
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/view_sp_list',$reseller);
            $this->load->view('footer');
        }
        public function edit_sp_status()
        {
            
            $id = $this->input->post('s');
			$act = $this->input->post('act');
            if($act=='active')
            {
                $status = '1';
            }
            else if($act=='inactive')
            {
                $status = '3';
            }
            else if($act=='delete')
            {
                $status = '4';
            }
            $this->db->set('status',$status);
            $this->db->where('id',$id);
            $this->db->update('user_details');
            $data = array('responce'=>'success','message'=>'Data update Successfully');
            echo json_encode($data);
        }
        public function add_package()
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
            $solution['s'] = $this->User_model->solutions_list();
            
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_package_form',$solution);
            $this->load->view('footer');
            
        }
        public function add_service_detail()
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
            $solution['s'] = $this->User_model->solutions_list();
            
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_service_detail',$solution);
            $this->load->view('footer');

            if(isset($_POST['add_detail_btn']))
            {
                $this->form_validation->set_rules('solution_name','Solution Name','required');
                $this->form_validation->set_rules('dis_solution_name','Display Solution name','required');
                $this->form_validation->set_rules('mrp','MRP','required');
                $this->form_validation->set_rules('start_head','Start Heading','required');
                $this->form_validation->set_rules('start_detail_head','Start Detail Heading','required');
                $this->form_validation->set_rules('start_detail','Start Detail','required');
                $this->form_validation->set_rules('success_head','Success Heading','required');
                $this->form_validation->set_rules('success_detail_head','Success Detail Head','required');
                $this->form_validation->set_rules('success_detail','Success Detail','required');
                $this->form_validation->set_rules('Fail_head','Fail Heading','required');
                $this->form_validation->set_rules('Fail_detail_head','Fail Detail Heading','required');
                $this->form_validation->set_rules('Fail_detail','Fail Detail','required');
                if($this->form_validation->run() == true)
                {
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'jpg|png';
                    $config['max_size'] = 100000;
                    $config['max_width'] = 512;
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
                                redirect('/AdminController/add_service_detail','refresh');
                            }
                        
                    }
                    else
                    {
                        $this->db->where('solution',$_POST['solution_name']);
                        $this->db->set('photo','uploads/'.$new_name);
                        $this->db->set('display_solution_name',$_POST['dis_solution_name']);
                        $this->db->set('mrp',$_POST['mrp']);
                        $this->db->set('start_head',$_POST['start_head']);
                        $this->db->set('start_detail_head',$_POST['start_detail_head']);
                        $this->db->set('start_detail',$_POST['start_detail']);
                        $this->db->set('success_head',$_POST['success_head']);
                        $this->db->set('success_detail_head',$_POST['success_detail_head']);
                        $this->db->set('success_detail',$_POST['success_detail']);
                        $this->db->set('fail_head',$_POST['Fail_head']);
                        $this->db->set('fail_detail_head',$_POST['Fail_detail_head']);
                        $this->db->set('fail_detail',$_POST['Fail_detail']);
                        $this->db->update('solutions');
        
                        $this->session->set_flashdata('msg2','Detail Updated');
                        redirect('/AdminController/add_service_detail','refresh');
                    }              
                        
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/AdminController/add_service_detail','refresh');
                }
             
            }
        }
        public function insert_package()
        {
            $this->form_validation->set_rules('solution_name','Solution','required');
            
            $this->form_validation->set_rules('package','No of Reports','required');
            $this->form_validation->set_rules('mrp','mrp','required');
            $this->form_validation->set_rules('discount','Discount','required');
           
            if($this->form_validation->run() == true)
            {            
                   
                $solution = $_POST['solution_name'];
                $package = $_POST['package'];  
                $code_id = $solution.$package;
                $this->db->where('code_id',$code_id);
                $rowcount = $this->db->get('reseller_code_record')->num_rows();
                echo $rowcount;
                if($rowcount == 0)
                {
                    $formArray = array(
                        'code_id'=>$code_id,
                        'solution'=>$_POST['solution_name'],
                        'discription'=>"",
                        'no_of_reports'=>$_POST['package'],
                        'mrp'=>$_POST['mrp'],
                        'discount'=> $_POST['discount'],
                        'status' => 'active'
                    );
                    $this->db->where('solution',$solution);
                    $rowcount2 = $this->db->get('solutions')->num_rows();
                    if($rowcount2 == 0)
                    {
                        // redirect('AdminController/ch');
                        $this->db->set('solution',$solution);
                        // $this->db->insert('solutions');
                        $this->db->insert('reseller_code_record',$formArray);
                    }
                    else
                    {
                        // redirect('AdminController/dch');
                        $this->db->insert('reseller_code_record',$formArray);
                    }
                    $this->session->set_flashdata("msg","Package Saved Successfully");
                    redirect('/AdminController/add_package','refresh');
                }
                else
                {
                    $this->session->set_flashdata("msg","This Package Already Exist");
                    redirect('/AdminController/add_package','refresh');
                }
                
                                  // $this->session->set_flashdata("msg","Your account has been registered. You can login now");
                // redirect('/UserController/registration','refresh');
                
            }
            else
            {
                $this->session->set_flashdata("msg","All Field Required");
                redirect('/AdminController/add_package','refresh');
            } 
        }
        public function approve_domain()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $unapprove_domain['h'] = $this->Admin_model->get_unapprove_domain(true);
            
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/unapproved_domain_list',$unapprove_domain);
            $this->load->view('footer');
        }

        public function domain_approval($id)
        {
            $this->load->model('Admin_model');
            $this->db->where('id',$id);
            $this->db->set('status','1');
            $this->db->update('reseller_homepage');
            $this->session->set_flashdata('msg','Details Updated');
            redirect(base_url().'/AdminController/approve_domain');
        }

        public function add_level1()
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
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_level_one');
            $this->load->view('footer');

           
                    
        }
        public function insert_level()
        {
           
                $this->form_validation->set_rules('level1_name','Level','required');
               
                if($this->form_validation->run() == true)
                {
                    $level = $_POST['level1_name'];
                  
                        $this->db->where('l1',$level);
                        $rowcount2 = $this->db->get('provider_level_one')->num_rows();
                        if($rowcount2 == 0)
                        {
                            $formArray = array(
                               
                                'l1'=>$_POST['level1_name'],
                                
                            );
                            
                            $this->db->insert('provider_level_one',$formArray);
                            $this->session->set_flashdata("msg2","Added Successfully");
                            redirect('/AdminController/add_level1','refresh');
                            
                        }
                        else
                        {
                            $this->session->set_flashdata("msg","Level One Already Exist");
                            redirect('/AdminController/add_level1','refresh');
                        }
                        
                }
                else
                {
                    $this->session->set_flashdata("msg","Already Exist");
                    redirect('/AdminController/add_level1','refresh');
                }

        }
        public function add_level2()
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
            
         
            $level['l'] = $this->Admin_model->provider_level_list();
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_level_two',$level);
            $this->load->view('footer');
        }

        public function insert_level2()
        {
           
                $this->form_validation->set_rules('level1_name','Level','required');
                $this->form_validation->set_rules('level2_name','Level','required');
                if($this->form_validation->run() == true)
                {
                    $level = $_POST['level2_name'];
                  
                    $this->db->where('l2',$level);
                        $rowcount2 = $this->db->get('provider_level_two')->num_rows();
                        if($rowcount2 == 0)
                        {
                            $formArray = array(
                               
                                'l1'=>$_POST['level1_name'],
                                'l2'=>$_POST['level2_name']
                                
                            );
                            
                            $this->db->insert('provider_level_two',$formArray);
                           
                            
                        }
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/add_level2','refresh');
                }
                else
                {
                    $this->session->set_flashdata("msg","Already Exist");
                    redirect('/AdminController/add_level2','refresh');
                }

        }

        public function add_level3()
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
            
            $level['l'] = $this->Admin_model->provider_level_list();
           
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_level_three',$level);
            $this->load->view('footer');

            if(isset($_POST['request_btn']))
            {
                $this->form_validation->set_rules('levelone','Level 1','required');
                $this->form_validation->set_rules('leveltwo','Level 2','required');
                $this->form_validation->set_rules('levelthree','Level 3','required');
                if($this->form_validation->run() == true)
                {
                    $level = $_POST['levelthree'];
                   
                    $this->db->where('l3',$level);
                        $rowcount2 = $this->db->get('provider_level_three')->num_rows();
                        if($rowcount2 == 0)
                        {
                            $formArray = array(
                               
                                'l1'=>$_POST['levelone'],
                                'l2'=>$_POST['leveltwo'],
                                'l3'=>$_POST['levelthree']
                                
                            );
                            
                            $this->db->insert('provider_level_three',$formArray);
                           
                            $this->session->set_flashdata("msg2","Added Successfully");
                            redirect('/AdminController/add_level3','refresh');
                        }
                        else
                        {
                            $this->session->set_flashdata("msg","Already Exist");
                            redirect('/AdminController/add_level3','refresh');
                        }
                        
                }
                else
                {
                    $this->session->set_flashdata("msg",validation_errors());
                    redirect('/AdminController/add_level3','refresh');
                }
            }
        }
        public function fetch_level_two()
        {   
            $this->load->model('Admin_model');
            $level = $this->input->post('id',TRUE);
            $data = $this->Admin_model->fetch_level_two($level)->result();
            echo json_encode($data);
            
        }
        public function fetch_level_three()
        {   
            $this->load->model('Admin_model');
            $level = $this->input->post('id',TRUE);
            $data = $this->Admin_model->fetch_level_three($level)->result();
            echo json_encode($data);
            
        }

        public function add_level4()
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
            
            $level['l'] = $this->Admin_model->provider_level_list();
            $level['s'] = $this->Admin_model->provider_level3_list();
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_level_four',$level);
            $this->load->view('footer');

            if(isset($_POST['saveBtn']))
            {
                $this->form_validation->set_rules('levelthree','Select Level 3','required');
                $this->form_validation->set_rules('param_one','Parameter 1','required');
                $this->form_validation->set_rules('param_two','Parameter 2','required');
                $this->form_validation->set_rules('param_three','Parameter 3','required');
                $this->form_validation->set_rules('param_four','Parameter 4','required');
                if($this->form_validation->run() == true)
                {
                    $level1 = $_POST['levelone'];
                    $level2 = $_POST['leveltwo'];
                    $level = $_POST['levelthree'];
                    
                        $where = "l1='$level1' and l2='$level2' and l3_id='$level'";
                        $this->db->where($where);
                        $rowcount2 = $this->db->get('provider_level_four')->num_rows();
                        if($rowcount2 == 0)
                        {
                            
                            $count = $this->db->get('provider_level_four')->num_rows();
                            if($count==0)
                            {
                                $last_id = 1;
                            }
                            else
                            {
                                $last = $this->db->order_by('id',"desc")->limit(1)->get('provider_level_four');
                                foreach($last->result() as $last_id)
                                {
                                    
                                    $lid = $last_id->id;
                                    
                                        $last_id = $lid + 1;
                                    
                                    
                                }
                            }
                            
                            
                               
                            $formArray = array(
                               
                                'l1'=>$_POST['levelone'],
                                'l2'=>$_POST['leveltwo'],
                                'l3_id'=>$_POST['levelthree'],
                                'param_id'=>$last_id,
                                'param_one'=>$_POST['param_one'],
                                'param_two'=>$_POST['param_two'],
                                'param_three'=>$_POST['param_three'],
                                'param_four'=>$_POST['param_four']
                                
                            );
                            
                            $this->db->insert('provider_level_four',$formArray);
                           
                            $this->session->set_flashdata("msg2","Added Successfully");
                            redirect('/AdminController/add_level4','refresh');
                        }
                        else
                        {
                            $this->session->set_flashdata("msg","Already Exist");
                            redirect('/AdminController/add_level4','refresh');
                        }
                        
                }
                else
                {
                    $this->session->set_flashdata("msg",validation_errors());
                    redirect('/AdminController/add_level4','refresh');
                }

            }
        }

        public function add_level5()
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
            
            $level['s'] = $this->Admin_model->provider_level_list();
            $level['l'] = $this->Admin_model->provider_level3_list();
           
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_level_five',$level);
            $this->load->view('footer');

            if(isset($_POST['saveBtn']))
            {
                $this->form_validation->set_rules('levelone','Select Level 1','required');
                $this->form_validation->set_rules('leveltwo','Select Level 2','required');
                $this->form_validation->set_rules('levelthree','Select Level 3','required');
                $this->form_validation->set_rules('param_id','Param ID','required');
                $this->form_validation->set_rules('param_one','Value 1','required');
                $this->form_validation->set_rules('param_two','Value 2','required');
                $this->form_validation->set_rules('param_three','Value 3','required');
                $this->form_validation->set_rules('param_four','Value 4','required');
                if($this->form_validation->run() == true)
                {
                    
                        $num = 1;
                        choo:
                        if($num==1)
                        {
                            $param = $_POST['param_one'];
                        }
                        else if($num==2)
                        {
                            $param = $_POST['param_two'];
                        }
                        else if($num==3)
                        {
                            $param = $_POST['param_three'];
                        }
                        else{
                            $param = $_POST['param_four'];
                        }   
                        $array = explode(',',$param);
                        
                        foreach($array as $item)
                        {
                            $formArray = array(
                                'l1'=>$_POST['levelone'],
                                'l2'=>$_POST['leveltwo'],
                                'l3_id'=>$_POST['levelthree'],
                                'param_id'=>$_POST['param_id'],
                                'param_type'=>$num,
                                'param_value'=>$item                               
                            );
                            $this->db->insert('provider_level_five',$formArray);
                        }
                        
                        if($num<4)
                        {
                            $num++;
                            goto choo;
                        }
                                                       
                           
                            $this->session->set_flashdata("msg2","Added Successfully");
                            redirect('/AdminController/add_level5','refresh');
                        
                }
                else
                {
                    $this->session->set_flashdata("msg",validation_errors());
                    redirect('/AdminController/add_level5','refresh');
                }

            }
        }

        public function fetch_level_four()
        {   
            $this->load->model('Admin_model');
            $level = $this->input->post('id',TRUE);
            $l1 = $this->input->post('l1',TRUE);
            $l2 = $this->input->post('l2',TRUE);
            
                $data = $this->Admin_model->fetch_level_four($level,$l1,$l2)->result();
           
            
            echo json_encode($data);  
        }
        
        public function approve_sp()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $unapprove_sp['h'] = $this->Admin_model->get_unapprove_sp(true);
            

            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/unapprove_sp_list',$unapprove_sp);
            $this->load->view('footer');
        }
        public function viewsp($id)
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $unapprove_reseller['h'] = $this->Admin_model->get_unapprove_sp(true);
            

            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/sp_details',$unapprove_reseller);
            $this->load->view('footer');
        }
        public function update_sp_approval($id)
        {
            $this->load->model('Admin_model');
            $this->db->where('id',$id);
            $this->db->set('status','2');
            $this->db->update('user_details');
            $this->session->set_flashdata('msg','Service Proveder Approved');
            redirect(base_url().'/AdminController/approve_sp'); 
        }
        //reseller certification
        public function certification()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $reseller['h'] = $this->Admin_model->fetch_reseller_for_certification(true);
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/view_reseller_list_for_certification',$reseller);
            $this->load->view('footer');
        }
        //edit certification 
        public function edit_reseller_certification()
        {
            
            //email
            $id = $this->input->post('s');
            //id
			$act = $this->input->post('act');
            if($act=='e')
            {
                $status = '1';
                $this->db->set('c_status',$status);
                $this->db->where('email',$id);
                $this->db->update('reseller_certification');
            }
            else if($act=='d')
            {
                $status = '0';
                $this->db->set('c_status',$status);
                $this->db->where('email',$id);
                $this->db->update('reseller_certification');
            }
            else
            {
                $this->db->where('id',$act);
                $r = $this->db->get('reseller_certification');
                foreach($r->result() as $r)
                {
                    if($r->c_status==0)
                    {
                        $status = 1;
                    }
                    else
                    {
                        $status = 0;
                    }
                }
                $this->db->set('c_status',$status);
                $this->db->where('id',$act);
                $this->db->update('reseller_certification');
            }
            
            $data = array('responce'=>'success','message'=>'Data update Successfully');
            echo json_encode($data);
        }
        //certification input page
        public function certification_input()
        {
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $solution['h'] = $this->Admin_model->solution_group_list(true);
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/solution_list',$solution);
            $this->load->view('footer');
        }

        //upload files
        public function upload_files($c,$type)
        {
            $md['dd'] = array('first'=>$c,'type'=>$type);
           
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/upload_files',$md);
            $this->load->view('footer');
            
            if(isset($_POST['uploadbtn']))
            {
                    $grp = $_POST['c_group'];
                    $tp = $_POST['tp'];
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = $tp;
                    $config['max_size'] = 100000;
                    // $config['max_width'] = 900;
                    // $config['max_height'] = 512;
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
                                redirect('/AdminController/upload_files/'.$grp.'/'.$type,'refresh');
                            }
                           
                    }
                    else
                    {
                        $this->db->where('c_group',$grp);
                        $num = $this->db->get('certificate_template')->num_rows();
                        if($num==0)
                        {
                            if($type=='template_pdf')
                            {
                                $formArray = array(
                                    'c_group'=>$grp,
                                    'template'=>'uploads/'.$new_name,
                                    'logo'=>'',
                                    'certificate_name'=>'',
                                    'middle_content'=>'',
                                    'sign_one'=>'',
                                    'sign_two'=>'',
                                    'sign_three'=>'',
                                    'name_one'=>'',
                                    'name_two'=>'',
                                    'name_three'=>'',
                                    'detail_one_one'=>'',
                                    'detail_one_two'=>'',
                                    'detail_one_three'=>'',
                                    'detail_two_one'=>'',
                                    'detail_two_two'=>'',
                                    'detail_two_three'=>'',
                                    'per'=>''
                                );
                            }
                            else if($type=='logo')
                            {
                                $formArray = array(
                                    'c_group'=>$grp,
                                    'template'=>'',
                                    'logo'=>'uploads/'.$new_name,
                                    'certificate_name'=>'',
                                    'middle_content'=>'',
                                    'sign_one'=>'',
                                    'sign_two'=>'',
                                    'sign_three'=>'',
                                    'name_one'=>'',
                                    'name_two'=>'',
                                    'name_three'=>'',
                                    'detail_one_one'=>'',
                                    'detail_one_two'=>'',
                                    'detail_one_three'=>'',
                                    'detail_two_one'=>'',
                                    'detail_two_two'=>'',
                                    'detail_two_three'=>'',
                                    'per'=>''
                                );
                            }
                            else if($type=='sign_one')
                            {
                                $formArray = array(
                                    'c_group'=>$grp,
                                    'template'=>'',
                                    'logo'=>'',
                                    'certificate_name'=>'',
                                    'middle_content'=>'',
                                    'sign_one'=>'uploads/'.$new_name,
                                    'sign_two'=>'',
                                    'sign_three'=>'',
                                    'name_one'=>'',
                                    'name_two'=>'',
                                    'name_three'=>'',
                                    'detail_one_one'=>'',
                                    'detail_one_two'=>'',
                                    'detail_one_three'=>'',
                                    'detail_two_one'=>'',
                                    'detail_two_two'=>'',
                                    'detail_two_three'=>'',
                                    'per'=>''
                                );
                            }
                            else if($type=='sign_two')
                            {
                                $formArray = array(
                                    'c_group'=>$grp,
                                    'template'=>'',
                                    'logo'=>'',
                                    'certificate_name'=>'',
                                    'middle_content'=>'',
                                    'sign_one'=>'',
                                    'sign_two'=>'uploads/'.$new_name,
                                    'sign_three'=>'',
                                    'name_one'=>'',
                                    'name_two'=>'',
                                    'name_three'=>'',
                                    'detail_one_one'=>'',
                                    'detail_one_two'=>'',
                                    'detail_one_three'=>'',
                                    'detail_two_one'=>'',
                                    'detail_two_two'=>'',
                                    'detail_two_three'=>'',
                                    'per'=>''
                                );
                            }
                            else if($type=='sign_two')
                            {
                                $formArray = array(
                                    'c_group'=>$grp,
                                    'template'=>'',
                                    'logo'=>'',
                                    'certificate_name'=>'',
                                    'middle_content'=>'',
                                    'sign_one'=>'',
                                    'sign_two'=>'',
                                    'sign_three'=>'uploads/'.$new_name,
                                    'name_one'=>'',
                                    'name_two'=>'',
                                    'name_three'=>'',
                                    'detail_one_one'=>'',
                                    'detail_one_two'=>'',
                                    'detail_one_three'=>'',
                                    'detail_two_one'=>'',
                                    'detail_two_two'=>'',
                                    'detail_two_three'=>'',
                                    'per'=>''
                                );
                            }
                            $this->db->insert('certificate_template',$formArray);
                        }
                        else
                        {
                            if($type=='template_pdf')
                            {
                                $fld = 'template';
                            }
                            else if($type=='logo')
                            {
                                $fld = 'logo';
                            }   
                            else if($type=='sign_one')
                            {
                                $fld = 'sign_one';
                            } 
                            else if($type=='sign_two')
                            {
                                $fld = 'sign_two';
                            } 
                            else if($type=='sign_three')
                            {
                                $fld = 'sign_three';
                            } 
                            $this->db->where('c_group',$grp);
                            $this->db->set($fld,'uploads/'.$new_name);
                            $this->db->update('certificate_template');
                        }
                        
                        $this->session->set_flashdata('msg2','Saved Successfully');
                        redirect('/AdminController/certification_input','refresh');
                    }
                
            }
        }

        public function upload_content($c)
        {
            $md['dd'] = array('first'=>$c);
           
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/upload_content',$md);
            $this->load->view('footer');
            
            if(isset($_POST['updatebtn']))
            {
                $this->form_validation->set_rules('certificate_name','Certificate Name','required');
                $this->form_validation->set_rules('mc','Middle Content','required');
                $this->form_validation->set_rules('name_one','Name one','required');
                $this->form_validation->set_rules('name_two','Name two','required');
                $this->form_validation->set_rules('name_three','Name three','required');
                $this->form_validation->set_rules('detail_one_one','Detail one one','required');
                $this->form_validation->set_rules('detail_one_two','Detail one two','required');
                $this->form_validation->set_rules('detail_one_three','Detail one three','required');  
                $this->form_validation->set_rules('detail_two_one','Detail two one','required');
                $this->form_validation->set_rules('detail_two_two','Detail two two','required');
                $this->form_validation->set_rules('detail_two_three','Detail two three','required');  
                $this->form_validation->set_rules('per','Percent','required');                                      
                if($this->form_validation->run() == true)
                {   
                    $grp = $_POST['group'];
                    $this->db->where('c_group',$grp);
                    $num = $this->db->get('certificate_template')->num_rows();
                    if($num==0)
                    {
                        $formArray = array(
                            'c_group'=>$grp,
                            'template'=>'',
                            'logo'=>'',
                            'certificate_name'=>$_POST['certificate_name'],
                            'middle_content'=>$_POST['mc'],
                            'sign_one'=>'',
                            'sign_two'=>'',
                            'sign_three'=>'',
                            'name_one'=>$_POST['name_one'],
                            'name_two'=>$_POST['name_two'],
                            'name_three'=>$_POST['name_three'],
                            'detail_one_one'=>$_POST['detail_one_one'],
                            'detail_one_two'=>$_POST['detail_one_two'],
                            'detail_one_three'=>$_POST['detail_one_three'],
                            'detail_two_one'=>$_POST['detail_two_one'],
                            'detail_two_two'=>$_POST['detail_two_two'],
                            'detail_two_three'=>$_POST['detail_two_three'],
                            'per'=>$_POST['per']
                        ); 
                        $this->db->insert('certificate_template',$formArray);
                    }
                    else
                    {
                        $this->db->where('c_group',$grp);
                        $this->db->set('certificate_name',$_POST['certificate_name']);
                        $this->db->set('middle_content',$_POST['mc']);
                        $this->db->set('name_one',$_POST['name_one']);
                        $this->db->set('name_two',$_POST['name_two']);
                        $this->db->set('name_three',$_POST['name_three']);
                        $this->db->set('detail_one_one',$_POST['detail_one_one']);
                        $this->db->set('detail_one_two',$_POST['detail_one_two']);
                        $this->db->set('detail_one_three',$_POST['detail_one_three']);
                        $this->db->set('detail_two_one',$_POST['detail_two_one']);
                        $this->db->set('detail_two_two',$_POST['detail_two_two']);
                        $this->db->set('detail_two_three',$_POST['detail_two_three']);
                        $this->db->set('per',$_POST['per']);
                        $this->db->update('certificate_template');
                    }
                    
                   
                    $this->session->set_flashdata('msg','Detail Updated');
                    redirect('/AdminController/certification_input','refresh');
                }
                else
                {
                    $this->session->set_flashdata('msg','All Field is required');
                    redirect('/AdminController/upload_content/'.$c,'refresh');
                }
                
            }
        }
        public function approve_retest($id)
        {
            $this->db->where('id',$id);
            $row = $this->db->get('reseller_certification')->row();
            $email = $row->email;
            $c_group = $row->c_group;

            $where = "email='$email' and solution='$c_group'";
            $this->db->where($where);
            $this->db->delete('certification_test_result');

            $this->db->where('id',$id);
            $this->db->set('status','1');
            $this->db->set('rqst','0');
            $this->db->update('reseller_certification');

            $this->session->set_flashdata('msg','Approved');
            redirect('/AdminController/certification');
            

        }

        public function approve_partner_test()
        {
            $this->load->database();
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $unapprove_partner['h'] = $this->Admin_model->get_unapprove_partner(true);
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/unapprove_become_partner_list',$unapprove_partner);
            $this->load->view('footer');
        }

        public function update_partner_approval($resellerId)
        {
            $this->load->model('Admin_model');
            $this->db->set('status','Ap');
            $this->db->where('id',$resellerId);
            $this->db->update('partner_counselor_status');
            $this->session->set_flashdata("msg","Test Approved");
            redirect('/AdminController/approve_partner_test','refresh');
        }
        public function schedule_training($resellerId)
        {
            $this->load->model('Admin_model');
            $this->db->set('status','St');
            $this->db->where('id',$resellerId);
            $this->db->update('partner_counselor_status');
            $this->session->set_flashdata("msg","Scheduled");
            redirect('/AdminController/approve_partner_test','refresh');
        }
        public function approve_certification_exam($resellerId,$email,$grp)
        {
            $this->load->model('Admin_model');
            $this->db->set('status','Ac');
            $this->db->where('id',$resellerId);
            $this->db->update('partner_counselor_status');
            
            $grp = str_replace('%20',' ',$grp);
            if($grp=='Positive Parenting')
            {
                $gp = 'ppct';
            }
            else if($grp=='Career Explorer')
            {
                 $gp = 'cect';
            }
            else if($grp=='Career Excellence')
            {
                 $gp = 'cexct';
            }
            else if($grp=='Career Builder')
            {
                 $gp = 'cbct';
            }
            $where = "email='$email' and solution='$gp'";
            $this->db->where($where);
            $this->db->delete('ppe_part1_test_details');
            
            $this->session->set_flashdata("msg","Scheduled");
            redirect('/AdminController/approve_partner_test','refresh');
        }

        public function change_status_training_completed($resellerId)
        {
            $this->load->model('Admin_model');
            $this->db->set('status','Tc');
            $this->db->set('p_c','1');
            $this->db->where('id',$resellerId);
            $this->db->update('partner_counselor_status');
            $this->session->set_flashdata("msg","Status Changed");
            redirect('/AdminController/approve_partner_test','refresh');
        }
        
        
        public function event_input()
        {
            $this->load->library('form_validation');
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/event_form');
            $this->load->view('footer');
        }

        public function insert_event()
        {
               
                // $this->form_validation->set_rules('event_no','Event Number','required');
                // $this->form_validation->set_rules('title','Title','required');
                // $this->form_validation->set_rules('date','Date','required');
                // $this->form_validation->set_rules('start_time','start time','required');
                // $this->form_validation->set_rules('end_time','End time','required');
                // $this->form_validation->set_rules('location','Location','required');
                // $this->form_validation->set_rules('description','Description','required');
               
                // if($this->form_validation->run() == true)
                // {            
                       
                    $event_no = $_POST['event_no'];
                    $title = $_POST['title']; 
                    $date =$_POST['event_date'];
                    $stime=$_POST['start_time'] ;
                    $etime=$_POST['end_time'];
                    $working_hours = " at ".$stime." to ".$etime;
                    $location=$_POST['location'];
                    $description=$_POST['description'];
                    $this->db->where('event_no',$event_no);
                    $rowcount = $this->db->get('events')->num_rows();
                    echo $rowcount;
                    if($rowcount == 0)
                    {
                        $formArray = array(
                            'event_no'=>$event_no,
                            'Title'=>$_POST['title'],
                            'date'=>$_POST['event_date'],
                            'time'=>$working_hours,
                            'location'=>$_POST['location'],
                            'description'=> $_POST['description']
                           
                        );
                      
                      
                        $this->db->insert('events',$formArray);
                        $this->session->set_flashdata("msg","Package Saved Successfully");
                        redirect('/AdminController/event_input','refresh');
                    }
                    else
                    {
                        $this->db->set('Title',$title);
                        $this->db->set('date',$date);
                        $this->db->set('time',$working_hours);
                        $this->db->set('location',$location);
                        $this->db->set('description',$description);
                        $this->db->where('event_no',$event_no);
                        $this->db->update('events');
                      
                        $this->session->set_flashdata("msg","This Event Updated");
                        redirect('/AdminController/event_input','refresh');
                    }
                    
                                      // $this->session->set_flashdata("msg","Your account has been registered. You can login now");
                    // redirect('/UserController/registration','refresh');
                    
                // }
                // else
                // {
                    // $this->session->set_flashdata("msg","All Field Required");
                    // redirect('/AdminController/event_input','refresh');
                // } 
        }

        function term_conditions_view(){
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $this->load->model('Admin_model');
            $view_data['db_data'] = $this->Admin_model->team_and_condition_fetch();
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view("admin/term_conditions_view",$view_data);
            $this->load->view('footer');
        }

        function term_conditions_add(){
            $this->load->model('Admin_model');
            $user = $this->session->userdata('user');
            $data['user'] = $user;           
            $this->form_validation->set_rules("heading","Heading","trim|required");
            $this->form_validation->set_rules("description","Description","trim|required");
            if($this->form_validation->run()){
                $form_data = [
                    "heading"=>$this->input->post("heading",true),
                    "cat_title"=>$this->input->post("cat_title",true),
                    "description"=>$this->input->post("description",true)
                ];
               $inser_check = $this->Admin_model->add_team_and_condition($form_data);
                if($inser_check){
                    $this->session->set_flashdata("INSERT_CHECK_MSG","OK");
                }
                
                if($inser_check == false){
                  $this->session->set_flashdata("INSERT_CHECK_MSG","NO");
                }
            }

            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view("admin/term_conditions_add");
            $this->load->view('footer');
        }

        function FaQ_view(){
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $this->load->model('Admin_model');
            $view_data['db_data'] = $this->Admin_model->FAQ_fetch();
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view("admin/FaQ_view",$view_data);
            $this->load->view('footer');
        }

        function FaQ_add(){
            $this->load->model('Admin_model');
            $user = $this->session->userdata('user');
            $data['user'] = $user;           
            $this->form_validation->set_rules("question","Question","trim|required");
            $this->form_validation->set_rules("answer","Answer","trim|required");
            if($this->form_validation->run()){
                $form_data = [
                    "heading"=>$this->input->post("question",true),
                    "cat_title"=>$this->input->post("cat_title",true),
                    "description"=>$this->input->post("answer",true)
                ];
               $inser_check = $this->Admin_model->add_FAQ($form_data);
                if($inser_check){
                    $this->session->set_flashdata("INSERT_CHECK_MSG","OK");
                }
                
                if($inser_check == false){
                  $this->session->set_flashdata("INSERT_CHECK_MSG","NO");
                }
            }

            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view("admin/FaQ_add");
            $this->load->view('footer');
        }

        public function add_cluster()
        {           
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            
            $this->form_validation->set_rules('cluster_name','Cluster Name','required');
            $this->form_validation->set_rules('cluster_description','Cluster Description','required');
               
            if($this->form_validation->run())
            {  
                
            }

            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_cluster');
            $this->load->view('footer');            
        }

        public function add_path()
        {           
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            
            /* $this->form_validation->set_rules('cluster_name','Cluster Name','required');
            $this->form_validation->set_rules('cluster_description','Cluster Description','required');
               
            if($this->form_validation->run())
            {  
                
            } */

            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_path');
            $this->load->view('footer');            
        }

        public function famous_personalities()
        {
            $this->load->library('form_validation');
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/famous_personalities');
            $this->load->view('footer');            
        }

        public function add_profession()
        {
            $this->load->library('form_validation');
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/add_profession');
            $this->load->view('footer');            
        }

        public function sp_vocation_trainings()
        {
            $data_view = [];
            $this->load->library('form_validation');
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $data_view['all_vocational'] = $this->get_vocational_info("ALL_VOCATIONAL");
            
            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/sp_vocation_trainings',$data_view);
            $this->load->view('footer');            
        }


        public function sp_vocation_training_details($id)
        {
            $data_view = [];
            $this->load->library('form_validation');
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $data_view['vocational_info'] = $this->get_vocational_info("FULL_SINGLE_VIEW",$id);
            
            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/sp_vocation_training_details',$data_view);
            $this->load->view('footer');            
        }

        public function ajax_work($action){
            $this->load->model('Sp_model');
            $json_msg = [];
            switch($action){
                case "ADD_REMARK":
                   $vocation_id = $this->input->post("v_id",true);
                    $remark = $this->input->post("remark",true);
                    $remark_type = $this->input->post("r_type",true);
                   if(!empty($vocation_id) && !empty($remark)){
                       $arr_data = [
                                    "training_id"   => $vocation_id,
                                    "remark"        => $remark,
                                    "types"         => $remark_type
                       ];
                    $this->db->insert("vocational_training_remarks",$arr_data);
                    if($this->db->affected_rows() > 0){
                        if($remark_type == "certification_suspended"){
                            $this->db->set("certification_status","suspended");
                        }else{
                            $this->db->set("training_status",$remark_type);
                        }
                        $this->db->where("id",$vocation_id);
                        $this->db->limit(1);
                        $this->db->update("vocational_training");
                        $json_msg["MSG"] = "OK";                        
                    }else{
                        $json_msg["MSG"] = "S_IS_W";
                    }

                   }else{
                    $json_msg["MSG"] = "REMARK_EMPTY";
                   }
                break;               
                case "REMOVE_REMARK":
                   $remark_id = $this->input->get("r");
                    if(!empty($remark_id)){
                        $this->db->where("id",$remark_id);
                        $this->db->limit(1);
                        $this->db->delete("vocational_training_remarks");
                        if($this->db->affected_rows() > 0){
                            $json_msg["MSG"] = "OK";
                        }else{
                            $json_msg["MSG"] = "S_IS_W";
                        }
                    }else{
                        $json_msg["MSG"] = "REMARK_EMPTY";
                    }
                break;

                case "APPROVED":
                   $vocation_id = $this->input->get("vid");
                    if(!empty($vocation_id)){
                        $this->db->set("training_status","approved");
                        $this->db->where("id",$vocation_id);
                        $this->db->limit(1);
                        $this->db->update("vocational_training");
                        if($this->db->affected_rows() > 0){
                            $json_msg["MSG"] = "OK";
                        }else{
                            $json_msg["MSG"] = "S_IS_W";
                        }
                    }else{
                        $json_msg["MSG"] = "REMARK_EMPTY";
                    }
                break;

                case "CERTIFICATION_APPROVED":
                   $vocation_id = $this->input->get("vid");
                    if(!empty($vocation_id)){
                        $check_id = $this->db->get_where("vocational_training",["id"=>$vocation_id,"training_status"=>"approved"])->row()->id;
                        if(!empty($check_id)){
                            $this->db->set("certification_status","approved");
                            $this->db->where("id",$check_id);
                            $this->db->limit(1);
                            $this->db->update("vocational_training");
                            if($this->db->affected_rows() > 0){
                                $json_msg["MSG"] = "OK";
                            }else{
                                $json_msg["MSG"] = "S_IS_W";
                            }
                        }else{
                            $json_msg["MSG"] = "approval_pending";
                        }
                    }else{
                        $json_msg["MSG"] = "REMARK_EMPTY";
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

            $this->output
            ->set_content_type("application/json")
            ->set_output(json_encode($json_msg));
        }

        protected function get_vocational_info($check,$id = null){
            if($check == "ALL_VOCATIONAL"){
                $this->db->select("a.*,b.fullname,b.email");
                $this->db->join("user_details b","a.sp_id = b.id","inner");
                $this->db->order_by("a.id","desc");
                $q = $this->db->get("vocational_training a");
                return $q->result();
            }

            if($check == "SHORT_VIEW"){
                $this->db->select("*");
                $this->db->where(["sp_id"=>$id]);
                $this->db->order_by("id","desc");
                $q = $this->db->get("vocational_training");
                return $q->result();
            }
    
            if($check == "FULL_SINGLE_VIEW"){
                $data = [];
                $this->db->select("a.*,b.fullname,b.email,b.user_id,b.profile_photo,b.mobile");
                $this->db->join("user_details b","a.sp_id = b.id","inner");
                $this->db->where(["a.id"=>$id]);
                $q = $this->db->get("vocational_training a");
                $data[] = ["training_info"=>$q->row()];
    
                $this->db->select("*");
                $this->db->where(["training_id"=>$id]);
                $q = $this->db->get("vocational_training_sections");
                $data[] =  ["section_info"=>$q->result()];

                $this->db->select("*");
                $this->db->where(["training_id"=>$id,"types"=>"suspended"]);
                $this->db->order_by("remark_date","desc");
                $q = $this->db->get("vocational_training_remarks");
                $data[] = ["remark_info"=>$q->result()];
                
                $this->db->select("*");
                $this->db->where(["training_id"=>$id,"types"=>"approval_remark"]);
                $this->db->order_by("remark_date","desc");
                $q = $this->db->get("vocational_training_remarks");
                $data[] = ["approval_remark"=>$q->result()];

                $this->db->select("*");
                $this->db->where(["training_id"=>$id,"types"=>"certification_suspended"]);
                $this->db->order_by("remark_date","desc");
                $q = $this->db->get("vocational_training_remarks");
                $data[] = ["certification_remark"=>$q->result()];
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
                $this->load->library('form_validation');
                $this->load->model('User_model');
                if($this->User_model->authorized()==false)
                {
                    $this->session->set_flashdata('msg','You are not authorized to access this section');
                    redirect(base_url().'/UserController/login');
                }
                $user = $this->session->userdata('user');
                $data['user'] = $user;

                $data_view['participant_info'] = $this->get_vocational_info("PARTICIPANT_SINGLE_VIEW",$id);
                $this->load->model('Admin_model');
                $this->load->view('navbar',$data);
                $this->load->view('admin/sidebar');
                $this->load->view('admin/participant_details',$data_view);
                $this->load->view('footer');
                  
        }

        // sp jobs #start

        public function sp_jobs()
        {
            $data_view = [];
            $this->load->library('form_validation');
            $this->load->model('User_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $data_view['all_data'] = $this->Sp_model->get_job_data("ALL_SHORT_VIEW");
            
            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/sp_jobs_view',$data_view);
            $this->load->view('footer');            
        }
        public function job_request_user($id)
        {
            $data_view = [];
            $this->load->library('form_validation');
            $this->load->model('User_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data_view['job_request_info'] = $this->Sp_model->get_job_data("JOB_REQUEST_USERS",$id);
            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/job_request_user',$data_view);
            $this->load->view('footer');            
        }

        public function reset_password()
        {
            $this->load->library('form_validation');
            $this->load->model('Admin_model');
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;            
            $data['sp_reseller'] = $this->Admin_model->get_sp_reseller(); 
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/reset_password',$data);
            $this->load->view('footer');
        }

        function reset_password_form(){
            $this->load->model('Admin_model');
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user; 

            $users_ids = $this->input->post("change_id");
            $group_name = $this->input->post("group_name");
            
            
            if(!empty($group_name))
            {
                $form_input = [];
                $user_info = [];
                if($group_name == "sp"){

                    $fun_generat =  $this->generat_user_pass($users_ids);
                    $form_input = $fun_generat['form_input'];
                    $user_info = $fun_generat['user_info'];

                }elseif($group_name == "reseller"){

                    $fun_generat =  $this->generat_user_pass($users_ids);
                    $form_input = $fun_generat['form_input'];
                    $user_info = $fun_generat['user_info'];

                }elseif($group_name == "user"){
                    $fun_generat =  $this->generat_user_pass($users_ids);
                    $form_input = $fun_generat['form_input'];
                    $user_info = $fun_generat['user_info'];
                }elseif($group_name == "individual_users"){
                    $fun_generat =  $this->generat_user_pass($users_ids);
                    $form_input = $fun_generat['form_input'];
                    $user_info = $fun_generat['user_info'];
                }elseif($group_name == "all_end_users"){
                    // $group_name_modified = 'user';
                    $fun_generat =  $this->generat_user_pass($users_ids);
                    $form_input = $fun_generat['form_input'];
                    $user_info = $fun_generat['user_info'];
                }

                $this->db->update_batch('user_details',$form_input, 'id');
                if($this->db->affected_rows() > 0){

                    $arr_user_ids = [];
                    $arr_user_pass = [];
                    foreach($user_info as $v){
                        $arr_user_ids[] = $v['id'];
                        $arr_user_pass[] = $v['pass'];
                    }

                    $db_users = $this->Admin_model->get_sp_reseller($arr_user_ids);
                    $subject = "Respicite Notification - Your Password Has bee reset";
                    $export_data = [];
                    $count_db_users =  sizeof($db_users);
                    // echo "<pre>";
                    for($i = 0; $i < $count_db_users;$i++){
                        $export_data[] = [
                            "sno"       => $i+1,
                            "id"        => $db_users[$i]->id,
                            "user_type" => $db_users[$i]->iam,
                            "name"      => $db_users[$i]->fullname,
                            "email"     => $db_users[$i]->email,
                            "pass"      => $arr_user_pass[$i]
                        ];
                    

                        $msg_to_mail = "Dear <b>".$db_users[$i]->fullname."</b>
                        <br><br>
                        To keep your account safe, your password has been reset by the system.<br>
                        Your new Password : <b>".$arr_user_pass[$i]." </b> <br>
                        Please use the new password to login into your account.<br>
                        We recommend you to change it immediately after your first login.
                        <br><br>
                        Thanking You
                        <br>
                        Team Respicite";
                       //send the mail users #start.
                       $this->Admin_model->send_on_email($db_users[$i]->email,$subject,$msg_to_mail);
                       //send the mail users #end.
                    }

                    //export to excel file #start.
                    $this->fun_export_data($export_data,$group_name);
                    //export to excel file #end.
                    $msg_output = "Password updated successfully.";
                }else{
                    $msg_output = "Something is wrong.";
                }
                $this->session->set_flashdata("download_export",$msg_output);
                

            }else{
                $this->session->set_flashdata("msg_export","Choose any checkbox option for Reset password.");
                redirect(base_url().'AdminController/reset_password','refresh');
            }
        }

        function fun_export_data($arr_user_data,$group_name){
            $fileName = "$group_name-" . date('Y-m-d') . ".xls"; 
            header("Content-Disposition: attachment; filename=\"$fileName\""); 
            header("Content-Type: application/vnd.ms-excel");
           
            echo "<table border>
            <tr> 
                <th>S.No.</th>
                <th>ID</th>
                <th>User Type</th>
                <th>Name</th>
                <th>Email ID</th>
                <th>Passowrd</th>
            </tr>";
            foreach($arr_user_data as $v) { 
                echo "<tr>";
                foreach($v as $v1){
                    echo "<td>$v1</td>";
                }
                echo "</tr>";
            } 
            echo "</table>";                        
        }

        function generat_user_pass($users_id){
            if(!empty($users_id)){
                $count_no = sizeof($users_id);
                $arr_user_info = [];
                $arr_form_input = [];
                for($i = 0; $i < $count_no;$i++){
                $rand_pass  =  $this->randomstring(8);
                $arr_user_info[] =  ["id"=>$users_id[$i],"pass"=>$rand_pass]; 
                $pass_hash = password_hash($rand_pass,PASSWORD_BCRYPT);
                $arr_form_input[] = [
                    "id" => $users_id[$i],
                    "pwd" => $pass_hash,
                    ];
                }
                return ["user_info"=>$arr_user_info,"form_input"=>$arr_form_input];
            }else{
                return ["user_info"=>null,"form_input"=>null];
            }
        }

        function randomstring($len)
        {
            $string = "";
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^*abcdefghijklmnopqrstuvwxyz0123456789";
            for($i=0;$i<$len;$i++)
            $string.=substr($chars,rand(0,strlen($chars)),1);
            // echo $string;
            return $string;
        }

        // sp jobs #end

        function seo_parameter(){     
            $data = [];      
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

            $data["seo_parameters"] = $this->Commen_model->seo_feildes();
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/seo_parameter',$data);
            $this->load->view('footer');
        }

        function seo_channel($id = null){  
            $data = [];   
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

            if($id !== null){
                $data["seo_channels"] = $this->db->where("id",$id)->get("user_details_seo_channels")->row_array();
            }

            $this->form_validation->set_rules("channel_name","Channe Name","required");
            if($this->form_validation->run()){
                $form_data = [
                    "name" => $this->input->post("channel_name"),
                    "description" => $this->input->post("description")
                ];

                
                $pk_id = $this->input->post("tid");
                if(!empty($pk_id) && $pk_id == "add"){
                    $this->db->insert("user_details_seo_channels",$form_data);
                    $last_id = $this->db->insert_id();
                    redirect(base_url()."AdminController/seo_channel/$last_id");
                }

                if(!empty($pk_id) && $pk_id != "add"){
                    $this->db->set($form_data);
                    $this->db->where("id",$pk_id);
                    $this->db->limit(1);
                    $this->db->update("user_details_seo_channels");                    
                    redirect(base_url()."AdminController/seo_channel/$pk_id");
                }
            }

            $data["seo_parameters"] = $this->Commen_model->seo_feildes();
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/channels',$data);
            $this->load->view('footer');
        }

        function seo_location($id = null){  
            $data = [];   
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

            if($id !== null){
                $data["seo_location"] = $this->db->where("id",$id)->get("user_cities_all")->row_array();
            }

            $this->form_validation->set_rules("city_name","City Name","required");
            if($this->form_validation->run()){
                $form_data = [
                    "name" => $this->input->post("city_name")
                ];

                
                $pk_id = $this->input->post("tid");
                if(!empty($pk_id) && $pk_id == "add"){
                    $this->db->insert("user_cities_all",$form_data);
                    $last_id = $this->db->insert_id();
                    redirect(base_url()."AdminController/seo_location/$last_id");
                }

                if(!empty($pk_id) && $pk_id != "add"){
                    $this->db->set($form_data);
                    $this->db->where("id",$pk_id);
                    $this->db->limit(1);
                    $this->db->update("user_cities_all");                    
                    redirect(base_url()."AdminController/seo_location/$pk_id");
                }
            }

            $data["seo_parameters"] = $this->Commen_model->seo_feildes();
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/locations',$data);
            $this->load->view('footer');
        }

        function seo_top_skill($id = null){  
            $data = [];   
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

            if($id !== null){
                $data["seo_top_skill"] = $this->db->where("id",$id)->get("user_details_seo_top_skills")->row_array();
            }

            $this->form_validation->set_rules("skill","Skill","required");
            if($this->form_validation->run()){
                $form_data = [
                    "skill"         => $this->input->post("skill"),
                    "description"   => $this->input->post("description")
                ];

                
                $pk_id = $this->input->post("tid");
                if(!empty($pk_id) && $pk_id == "add"){
                    $this->db->insert("user_details_seo_top_skills",$form_data);
                    $last_id = $this->db->insert_id();
                    redirect(base_url()."AdminController/seo_top_skill/$last_id");
                }

                if(!empty($pk_id) && $pk_id != "add"){
                    $this->db->set($form_data);
                    $this->db->where("id",$pk_id);
                    $this->db->limit(1);
                    $this->db->update("user_details_seo_top_skills");                    
                    redirect(base_url()."AdminController/seo_top_skill/$pk_id");
                }
            }

            $data["seo_parameters"] = $this->Commen_model->seo_feildes();
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/top_skill',$data);
            $this->load->view('footer');
        }

        function seo_remove($types,$id){
            if(empty($types) && !empty($id)){
                show_404();
            }
            $tb_name = "";
            switch($types){
                case "channel":
                    $tb_name = "user_details_seo_channels";
                break;
                case "loaction":
                    $tb_name = "user_cities_all";
                break;
                case "top_skill":
                    $tb_name = "user_details_seo_top_skills";
                break;
            }
            $data = [];   
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            } 
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            
            $this->db->where("id",$id);
            $this->db->limit(1);
            $this->db->delete($tb_name);
            redirect(base_url()."AdminController/seo_parameter");
        }

        public function seo_inputs($id)
        {
            $this->load->model('User_model');
            $this->load->model('Admin_model');
            $this->load->model('Commen_model');
            $data = [];
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            
            

            $services = $this->Commen_model->reseller_services($id);
            
            $service_lists = [];
            foreach($services as $s_v){
                $service_lists[] = $s_v["c_group"];
            }

            $this->form_validation->set_rules('channels[]', 'Channel', 'required');        
            if($this->form_validation->run()){
                $data = [
                            "user_id" => $id,
                            "channels" => implode(",",$this->input->post("channels")),
                            "locations" => implode(",",$this->input->post("locations")),
                            "available_days" => implode(",",$this->input->post("available_days")),
                            "star_rating" => $this->input->post("star_rating"),
                            "experience" => $this->input->post("experience"),
                            "counselling_sessions" => $this->input->post("counselling_sessions"),
                            "top_skills" => implode(",",$this->input->post("top_skills")),
                            "most_relevant_education" => $this->input->post("most_relevant_education"),
                            "services" => implode(",",$service_lists),
                        ];
                $tid = $this->input->post("tid");
                if(!empty($tid) && $tid == "add"){
                    $this->db->insert("user_details_seo",$data);
                    if($this->db->affected_rows() > 0){
                        $this->session->set_flashdata('seo_save', 'SEO Input successfully saved'); 
                    }
                }

                if(!empty($tid) && $tid != "add"){                
                    unset($data["user_id"]);
                    $this->db->set($data);
                    $this->db->where("id",$tid);
                    $this->db->update("user_details_seo");
                    if($this->db->affected_rows() > 0){
                        $this->session->set_flashdata('seo_save', 'SEO Input successfully saved');  
                    }  
                }              
                
            }

            $q =  $this->db->where(["user_id"=>$id])->get("user_details_seo");
            
            $data['user'] = $user;
            $data['seo_data'] = $q->row();
            $data["seo_feildes"] = $this->Commen_model->seo_feildes();
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/seo_inputs',$data);
            $this->load->view('footer');
        }


        public function addMenuMarketplace()
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
            
         
            $menu['mainmenu'] = $this->Admin_model->get_marketplace_menu();
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar', $data);
            $this->load->view('admin/addmenu_configure',$menu);
            $this->load->view('footer');
        }


        public function insertMenu()
        {
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            $this->form_validation->set_rules('role','Role','required');
            $this->form_validation->set_rules('parent_id','Menu ','required');
            $this->form_validation->set_rules('submenu','Sub Menu','required');
            $this->form_validation->set_rules('status','Status','required');
           
            if($this->form_validation->run()== true)
            {
                //echo "if";die();
                $formData= [];
                $formData['role'] = $this->input->post('role',TRUE);
                $formData['parent_id'] = $this->input->post('parent_id',TRUE);
                $formData['name'] = $this->input->post('submenu',TRUE);
                $formData['status'] = $this->input->post('status',TRUE);
                $this->load->model('Admin_model');
                $result = $this->Admin_model->insert_marketplace_menu($formData);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/addMenuMarketplace','refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/AdminController/addMenuMarketplace','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/AdminController/addMenuMarketplace','refresh');
            }

        }


        public function list_menu()
        {
            $this->load->database();
            $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            $this->load->model('Admin_model');
            $menu['list_menu'] = $this->Admin_model->get_all_menus();
            // $menu['mainmenu'] = $this->Admin_model->get_marketplace_menu();
            // $menu['submenu'] = $this->Admin_model->get_marketplace_submenu();
            // echo "<pre>";
            // print_r($menu['submenu']);echo "<pre>";die();

            //print_r($menu);die();
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('admin/list_menu',$menu);
            $this->load->view('footer');
        }


         public function marketplace_metadata_flow()
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $data['submenu_items'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            $this->load->model('Admin_model');
            $metadata['flow']= $this->Admin_model->get_flow_data();

            //create view path
            $controller_name = debug_backtrace();
            $arr_view_path = explode('_',$controller_name[0]['function']);
            $path = implode('/',$arr_view_path);
            //echo $path;die;
            $file ='list';
            $view_full_path = 'admin/'.$path.'/'.$file;
            //echo $view_full_path;die;
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_full_path,$metadata);
            $this->load->view('footer');
        }
        
        
        public function marketplace_notification_notificationtypes()
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            

            $this->load->model('Admin_model');
            $metadata['notification']= $this->Admin_model->get_notification_type_data();

            //create view path
            $controller_name = debug_backtrace();
            $arr_view_path = explode('_',$controller_name[0]['function']);
            $path = implode('/',$arr_view_path);
            $file ='list';
            $view_full_path = 'admin/'.$path.'/'.$file;
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_full_path,$metadata);
            $this->load->view('footer');
        }
        
        public function marketplace_metadata_owners()
        {   //echo "owners";die();
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            $this->load->model('Admin_model');
            $metadata['owners']= $this->Admin_model->get_owners_data();

            //create view path
            $controller_name = debug_backtrace();
            $arr_view_path = explode('_',$controller_name[0]['function']);
            $path = implode('/',$arr_view_path);
            $file ='list';
            $view_full_path = 'admin/'.$path.'/'.$file;
            //echo $view_full_path;die(); 
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            //$this->load->view($view_full_path,$metadata);
            $this->load->view($view_full_path,$metadata);
            $this->load->view('footer');
        }


        public function addParameter()
        {
             // $id = $this->input->post('id',TRUE);
             // echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            $this->form_validation->set_rules('parameter','Parameter','required');
           
            if($this->form_validation->run()== true)
            {
                //echo "if";die();
                $id = $this->input->post('id',TRUE);
                $parameter = $this->input->post('parameter',TRUE);

                $this->load->model('Admin_model');
                $dataById = $this->Admin_model->get_flow_data_byId($id);
                $exploded= explode(",", $dataById[0]['parameter']);
                array_push($exploded, $parameter);
                $new_parameter=implode(",", $exploded);
                $result = $this->Admin_model->update_flow_data_byId($new_parameter, $id);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/marketplace_metadata_flow','refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/AdminController/marketplace_metadata_flow','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/AdminController/marketplace_metadata_flow','refresh');
            }

        }
        
        public function addCategory()
        {
             // $id = $this->input->post('id',TRUE);
             // echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            $this->form_validation->set_rules('parameter','Parameter','required');
           
            if($this->form_validation->run()== true)
            {
                //echo "if";die();
                $id = $this->input->post('id',TRUE);
                $parameter = $this->input->post('parameter',TRUE);
                $description = $this->input->post('description',TRUE);
                //echo $parameter.$description;die();
                $this->load->model('Admin_model');
                $dataById = $this->Admin_model->get_category_data_byId($id);
                
                $exploded_param= explode(",", $dataById[0]['parameter']);
                array_push($exploded_param, $parameter);
                $data['parameter']=implode(",", $exploded_param);
                
                $exploded_desc= explode(",", $dataById[0]['description']);
                array_push($exploded_desc, $description);
                $data['description']=implode(",", $exploded_desc);
                
                //echo $new_parameter.$new_desc;
                // print_r($data);
                // die();
                
                $result = $this->Admin_model->update_category_data_byId($data, $id);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/marketplace_financials_category','refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/AdminController/marketplace_financials_category','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/AdminController/marketplace_financials_category','refresh');
            }

        }
        
        
        public function addCurrency()
        {
             // $id = $this->input->post('id',TRUE);
             // echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            $this->form_validation->set_rules('parameter','Parameter','required');
           
            if($this->form_validation->run()== true)
            {
                //echo "if";die();
                $id = $this->input->post('id',TRUE);
                $parameter = $this->input->post('parameter',TRUE);
                $description = $this->input->post('description',TRUE);
                //echo $parameter.$description;die();
                $this->load->model('Admin_model');
                $dataById = $this->Admin_model->get_category_data_byId($id);
                
                $exploded_param= explode(",", $dataById[0]['parameter']);
                array_push($exploded_param, $parameter);
                $data['parameter']=implode(",", $exploded_param);
                
                $exploded_desc= explode(",", $dataById[0]['description']);
                array_push($exploded_desc, $description);
                $data['description']=implode(",", $exploded_desc);
                
                // echo "<pre>";
                // print_r($data);
                
                // //echo $new_parameter.$new_desc;
                // die();
                
                $result = $this->Admin_model->update_category_data_byId($data, $id);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/marketplace_financials_currency','refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/AdminController/marketplace_financials_currency','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/AdminController/marketplace_financials_currency','refresh');
            }

        }
        
        public function addOwnerParameter()
        {
             // $id = $this->input->post('id',TRUE);
             // echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            $this->form_validation->set_rules('parameter','Parameter','required');
           
            if($this->form_validation->run()== true)
            {
                //echo "if";die();
                $id = $this->input->post('id',TRUE);
                $parameter = $this->input->post('parameter',TRUE);

                $this->load->model('Admin_model');
                $dataById = $this->Admin_model->get_flow_data_byId($id);
                $exploded= explode(",", $dataById[0]['parameter']);
                array_push($exploded, $parameter);
                $new_parameter=implode(",", $exploded);
                $result = $this->Admin_model->update_flow_data_byId($new_parameter, $id);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/marketplace_metadata_owners','refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/AdminController/marketplace_metadata_owners','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/AdminController/marketplace_metadata_owners','refresh');
            }

        }
        
        
        public function addNotificationType()
        {
             // $id = $this->input->post('id',TRUE);
             // echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            $this->form_validation->set_rules('parameter','Parameter','required');
           
            if($this->form_validation->run()== true)
            {
                //echo "if";die();
                $id = $this->input->post('id',TRUE);
                $parameter = $this->input->post('parameter',TRUE);

                $this->load->model('Admin_model');
                $dataById = $this->Admin_model->get_notificationtype_data_byId($id);
                $exploded= explode(",", $dataById[0]['parameter']);
                array_push($exploded, $parameter);
                $new_parameter=implode(",", $exploded);
                $result = $this->Admin_model->update_flow_data_byId($new_parameter, $id);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/marketplace_notification_notificationtypes','refresh');
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/AdminController/marketplace_notification_notificationtypes','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/AdminController/marketplace_notification_notificationtypes','refresh');
            }

        }

         public function marketplace_metadata_spprogressstatuslist()
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            // echo "<pre>";
            // print_r($data);die();

            $this->load->model('Admin_model');
            $metadata['sp']= $this->Admin_model->get_sp_data();
            //print_r($metadata);die();

            //create view path
            $controller_name = debug_backtrace();
            // echo "<pre>";
            // print_r($controller_name);echo "</pre>";die();
            $arr_view_path = explode('_',$controller_name[0]['function']);
            $path = implode('/',$arr_view_path);

            $file ='list';
            $view_full_path = 'admin/'.$path.'/'.$file;
            
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_full_path,$metadata);
            $this->load->view('footer');
        }

        public function addSpParameter()
        {
             // $id = $this->input->post('id',TRUE);
             // echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            $this->form_validation->set_rules('parameter','Parameter','required');
           
            if($this->form_validation->run()== true)
            {
                //echo "if";die();
                $id = $this->input->post('id',TRUE);
                $parameter = $this->input->post('parameter',TRUE);

                $this->load->model('Admin_model');
                $dataById = $this->Admin_model->get_sp_data_byId($id);
                $exploded= explode(",", $dataById[0]['parameter']);
                array_push($exploded, $parameter);
                $new_parameter=implode(",", $exploded);
                $result = $this->Admin_model->update_sp_data_byId($new_parameter, $id);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/marketplace_metadata_spprogressstatuslist','refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/AdminController/marketplace_metadata_spprogressstatuslist','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/AdminController/marketplace_metadata_spprogressstatuslist','refresh');
            }

        }
        

        public function marketplace_metadata_filters()
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            // echo "<pre>";
            // print_r($data);die();

            $this->load->model('Admin_model');
            $metadata['filters']= $this->Admin_model->get_filter_data();
            //print_r($metadata);die();

            //create view path
            $controller_name = debug_backtrace();
            // echo "<pre>";
            // print_r($controller_name);echo "</pre>";die();
            $arr_view_path = explode('_',$controller_name[0]['function']);
            $path = implode('/',$arr_view_path);

            $file ='list';
            $view_full_path = 'admin/'.$path.'/'.$file;
            
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_full_path,$metadata);
            $this->load->view('footer');
        }


         public function addFilterParameter()
        {
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            $this->form_validation->set_rules('parameter','Parameter','required');

            if($this->form_validation->run()== true)
            {
                //echo "if";die();
                $id = $this->input->post('id',TRUE);
                $parameter = $this->input->post('parameter',TRUE);

                $this->load->model('Admin_model');
                $dataById = $this->Admin_model->get_filter_data_byId($id);
                $exploded= explode(",", $dataById[0]['parameter']);
                array_push($exploded, $parameter);
                $new_parameter=implode(",", $exploded);
                $result = $this->Admin_model->update_filter_data_byId($new_parameter, $id);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/marketplace_metadata_filters','refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/AdminController/marketplace_metadata_filters','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/AdminController/marketplace_metadata_filters','refresh');
            }

        }



         public function editStatus($id)
        {
            //echo "edit";die();
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

           
            // if($this->form_validation->run()== true)
            // {

            $this->load->model('Admin_model');
            $status = $this->Admin_model->get_status_byId($id);
            $meta['status']=$status[0]->status;
            $meta['name']=$status[0]->name;
            $meta['id']=$id;

            // print_r($meta);
            // die();

            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view('admin/edit_metadata_status',$meta);
            $this->load->view('footer');

        }
        
        public function editParameter()
        {   //echo "comes in cont";
            $id = base64_decode($this->input->post('id',TRUE));
            $key = base64_decode($this->input->post('k',TRUE));
            $metadata = base64_decode($this->input->post('mdata',TRUE));
            // echo $id.$k.$mdata;
        
            // die();
        
            // $data=$this->input->get();
            // $id=base64_decode($data['id']);
            // $key=base64_decode($data['key']);
            // $metadata=base64_decode($data['metadata']);
            $this->load->database();
            // $this->load->model('User_model');
            // $this->load->model('Commen_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }

            $this->load->helper(array('form', 'url'));

            // $user = $this->session->userdata('user');
            // $data['user'] = $user;
            // $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            // $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            $this->load->model('Admin_model');
            $parameter = $this->Admin_model->get_parameter_byId($id);
            $param_data=(explode(",",$parameter[0]->parameter));
            $param=$param_data[$key];

            $meta['param']=$param;
            $meta['id']=$id;
            $meta['key']=$key;
            $meta['metadata']=$metadata;
            
            // print_r($meta);
            // die();
             //return $meta;
             echo json_encode($meta);
            //$this->response($meta);

            // $this->load->view('navbar',$data);
            // $this->load->view('admin/sidebar',$data);
            // $this->load->view('admin/marketplace/metadata/edit_parameter',$meta);
            // $this->load->view('footer');

        }


        public function updateStatus()
        {

            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

                //echo "if";die();
                $id = $this->input->post('id',TRUE);
                $status = $this->input->post('status',TRUE);
                $name = $this->input->post('name',TRUE);

                //echo "cont".$status.$id.$name;die();

                $this->load->model('Admin_model');
                $result = $this->Admin_model->updateStatusById($id, $status);
               
                if($result)
                {  
                    $this->session->set_flashdata("msg2","Added Successfully");
                    if($name=='Flow'){
                        redirect('/AdminController/marketplace_metadata_flow','refresh');
                    }else if($name=='Filters'){
                        redirect('/AdminController/marketplace_metadata_filters','refresh');
                    }else if($name=='Notification Types'){
                        redirect('/AdminController/marketplace_notification_notificationtypes','refresh');
                    }else{
                        redirect('/AdminController/marketplace_metadata_spprogressstatuslist','refresh');
                    }
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","Opps Unable to add data");
                    if($name=='Flow'){
                        redirect('/AdminController/marketplace_metadata_flow','refresh');
                    }else if($name=='Filters'){
                        redirect('/AdminController/marketplace_metadata_filters','refresh');
                    }else if($name=='Notification Types'){
                        redirect('/AdminController/marketplace_notification_notificationtypes','refresh');
                    }else{
                        redirect('/AdminController/marketplace_metadata_spprogressstatuslist','refresh');
                    }
                }      
        }
        
        
         public function updateParameter()
        {
            //echo "update";die();
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

                $id = base64_decode($this->input->post('id',TRUE));
                $key = base64_decode($this->input->post('key',TRUE));
                $new_param = $this->input->post('parameter',TRUE);
                $name = base64_decode($this->input->post('name',TRUE));
                
                // echo $id.$key.$new_param.$name;die();
                
                // echo $id.$key.$new_param.$name;die();

                $this->load->model('Admin_model');
                $db_data = $this->Admin_model->get_parameter_byId($id);
                //echo "<pre>";
                 
                $param_data=(explode(",",$db_data[0]->parameter));
                
                //print_r($param_data);
                $param_data[$key]=$new_param;
                
                $parameters= implode(",", $param_data);
                $result = $this->Admin_model->updateParameterById($id, $parameters);
                //print_r($parameters);echo "</pre>";die();
               
                if($result)
                {  
                    $this->session->set_flashdata("msg2","Added Successfully");
                    if($name=='Flow'){
                        redirect('/AdminController/marketplace_metadata_flow','refresh');
                    }else if($name=='Filters'){
                        redirect('/AdminController/marketplace_metadata_filters','refresh');
                    }else if($name=='Owners'){
                        redirect('/AdminController/marketplace_metadata_owners','refresh');
                    }else if($name=='Notification Types'){
                        redirect('/AdminController/marketplace_notification_notificationtypes','refresh');
                    }else{
                        redirect('/AdminController/marketplace_metadata_spprogressstatuslist','refresh');
                    }
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","Opps Unable to add data");
                    if($name=='Flow'){
                        redirect('/AdminController/marketplace_metadata_flow','refresh');
                    }else if($name=='Filters'){
                        redirect('/AdminController/marketplace_metadata_filters','refresh');
                    }else if($name=='Owners'){
                        redirect('/AdminController/marketplace_metadata_owners','refresh');
                    }else if($name=='Notification Types'){
                        redirect('/AdminController/marketplace_notification_notificationtypes','refresh');
                    }else{
                        redirect('/AdminController/marketplace_metadata_spprogressstatuslist','refresh');
                    }
                }      
        }
        
        
         public function updateCategory()
        {
            ///echo "updatecat";die();
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

                $id = base64_decode($this->input->post('id',TRUE));
                $key = base64_decode($this->input->post('key',TRUE));
                $new_param = $this->input->post('parameter',TRUE);
                $new_desc = $this->input->post('description',TRUE);
                $name = base64_decode($this->input->post('name',TRUE));
                
                 //echo $id.$key.$new_param.$new_desc.$name;die();
                
                // echo $id.$key.$new_param.$name;die();

                $this->load->model('Admin_model');
                $db_data = $this->Admin_model->get_parameter_byId($id);
                // echo "<pre>";
                //  print_r($db_data);
                //  die();
                $param_data=(explode(",",$db_data[0]->parameter));
                
                $desc_data=(explode(",",$db_data[0]->description));
                
                //print_r($param_data);
                $param_data[$key]=$new_param;
                
                $desc_data[$key]=$new_desc;
                
                $parameters= implode(",", $param_data);
                $description= implode(",", $desc_data);
                $result = $this->Admin_model->updateCategoryById($id, $parameters,$description);
                //print_r($parameters);echo "</pre>";die();
               
                if($result)
                {  
                    $this->session->set_flashdata("msg2","Added Successfully");
                    redirect('/AdminController/marketplace_financials_category','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","Opps Unable to add data");
                    redirect('/AdminController/marketplace_financials_category','refresh');
                }      
        }
        
        public function updateCurrency()
        {
            ///echo "updatecat";die();
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

                $id = base64_decode($this->input->post('id',TRUE));
                $key = base64_decode($this->input->post('key',TRUE));
                $new_param = $this->input->post('parameter',TRUE);
                $new_desc = $this->input->post('description',TRUE);
                $name = base64_decode($this->input->post('name',TRUE));
                
                 //echo $id.$key.$new_param.$new_desc.$name;die();
                
                // echo $id.$key.$new_param.$name;die();

                $this->load->model('Admin_model');
                $db_data = $this->Admin_model->get_parameter_byId($id);
                // echo "<pre>";
                //  print_r($db_data);
                //  die();
                $param_data=(explode(",",$db_data[0]->parameter));
                
                $desc_data=(explode(",",$db_data[0]->description));
                
                //print_r($param_data);
                $param_data[$key]=$new_param;
                
                $desc_data[$key]=$new_desc;
                
                $parameters= implode(",", $param_data);
                $description= implode(",", $desc_data);
                $result = $this->Admin_model->updateCategoryById($id, $parameters,$description);
                //print_r($parameters);echo "</pre>";die();
               
                if($result)
                {  
                    $this->session->set_flashdata("msg2","Added Successfully");
                    redirect('/AdminController/marketplace_financials_currency','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","Opps Unable to add data");
                    redirect('/AdminController/marketplace_financials_currency','refresh');
                }      
        }
        
        
        public function deleteParameter()
        {
            //echo "delete";die();
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

                $id = base64_decode($this->input->get('id',TRUE));
                $key = base64_decode($this->input->get('key',TRUE));
                $new_param = $this->input->get('parameter',TRUE);
                $name = base64_decode($this->input->get('name',TRUE));
                
                //echo "id".$id."<br>"."key".$key."<br>"."paramname".$new_param."<br>"."name".$name;
                
                // echo $id.$key.$new_param.$name;die();

                $this->load->model('Admin_model');
                $db_data = $this->Admin_model->get_parameter_byId($id);
                //echo "<pre>";
                 
                $param_data=(explode(",",$db_data[0]->parameter));
                
                //print_r($param_data);
                unset($param_data[$key]);
               // print_r($param_data);
                //die();
               // unset($param_data[$key]);
                
                $parameters= implode(",", $param_data);
                $result = $this->Admin_model->updateParameterById($id, $parameters);
                
                //print_r($parameters);echo "</pre>";die();
               
                if($result)
                {  
                    $this->session->set_flashdata("msg2","Added Successfully");
                    if($name=='Flow'){
                        redirect('/AdminController/marketplace_metadata_flow','refresh');
                    }else if($name=='Filters'){
                        redirect('/AdminController/marketplace_metadata_filters','refresh');
                    }else if($name=='Owners'){
                        redirect('/AdminController/marketplace_metadata_owners','refresh');
                    }else if($name=='Notification Types'){
                        redirect('/AdminController/marketplace_notification_notificationtypes','refresh');
                    }else{
                        redirect('/AdminController/marketplace_metadata_spprogressstatuslist','refresh');
                    }
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","Opps Unable to add data");
                    if($name=='Flow'){
                        redirect('/AdminController/marketplace_metadata_flow','refresh');
                    }else if($name=='Filters'){
                        redirect('/AdminController/marketplace_metadata_filters','refresh');
                    }else if($name=='Owners'){
                        redirect('/AdminController/marketplace_metadata_owners','refresh');
                    }else if($name=='Notification Types'){
                        redirect('/AdminController/marketplace_notification_notificationtypes','refresh');
                    }else{
                        redirect('/AdminController/marketplace_metadata_spprogressstatuslist','refresh');
                    }
                }      
        }
        
        
        public function deleteCurrency()
        {
            //echo "delete";die();
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

                $id = base64_decode($this->input->get('id',TRUE));
                $key = base64_decode($this->input->get('key',TRUE));
                $new_param = $this->input->get('parameter',TRUE);
                $name = base64_decode($this->input->get('metadata',TRUE));
                
               // echo "id".$id."<br>"."key".$key."<br>"."paramname".$new_param."<br>"."name".$name;
                
                // echo $id.$key.$new_param.$name;
                // die();

                $this->load->model('Admin_model');
                $db_data = $this->Admin_model->get_parameter_byId($id);
                //echo "<pre>";
                 
                $param_data=(explode(",",$db_data[0]->parameter));
                
                $desc_data=(explode(",",$db_data[0]->description));
                
                //print_r($desc_data);
                unset($param_data[$key]);
                unset($desc_data[$key]);
                // print_r($desc_data);
                // die();
               // unset($param_data[$key]);
                
                $parameters= implode(",", $param_data);
                $description= implode(",", $desc_data);
                $result = $this->Admin_model->updateCurrencyById($id, $parameters, $description);
                
                // print_r($parameters);
                // print_r($description);
                // echo "</pre>";die();
               
                if($result)
                {  
                    $this->session->set_flashdata("msg2","Added Successfully");
                    if($name=='Currency'){
                        redirect('/AdminController/marketplace_financials_currency','refresh');
                    }else if($name=='Category'){
                        redirect('/AdminController/marketplace_financials_category','refresh');
                    }else if($name=='Owners'){
                        redirect('/AdminController/marketplace_metadata_owners','refresh');
                    }else if($name=='Notification Types'){
                        redirect('/AdminController/marketplace_notification_notificationtypes','refresh');
                    }else{
                        redirect('/AdminController/marketplace_metadata_spprogressstatuslist','refresh');
                    }
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","Opps Unable to add data");
                    if($name=='Currency'){
                        redirect('/AdminController/marketplace_financials_currency','refresh');
                    }else if($name=='Category'){
                        redirect('/AdminController/marketplace_financials_category','refresh');
                    }else if($name=='Owners'){
                        redirect('/AdminController/marketplace_metadata_owners','refresh');
                    }else if($name=='Notification Types'){
                        redirect('/AdminController/marketplace_notification_notificationtypes','refresh');
                    }else{
                        redirect('/AdminController/marketplace_metadata_spprogressstatuslist','refresh');
                    }
                }      
        }


        public function marketplace_services_sync()
        {   $this->load->model('Admin_model');
             $l1_data = $this->Admin_model->get_all_l1_data('Marketplace Services');
             foreach($l1_data as $k)
             {
                //print_r($k->id);
                $l2_data = $this->Admin_model->get_all_l2_data($k->id);
                    foreach($l2_data as $v){
                        ///echo $v->id;
                        $l3_data = $this->Admin_model->get_all_l3_data($k->id, $v->id);
                        foreach($l3_data as $h){
                            $data=[];
                            $data['l1']= $k->id ;
                            $data['l2']= $v->id ;
                            $data['l3']= $h->id ;
                            $data['l1_name']= $k->l1 ;
                            $data['l2_name']= $v->l2 ;
                            $data['category']= $h->l3 ;
                            $result = $this->Admin_model->insertProviderLevelData($data);
                        }
                    }
             }

            //  href="<?php echo base_url().'AdminController/approve_sp';

            //prepare data
            $debug_arr = debug_backtrace();
            $class_name = $debug_arr[0]['class'];
            $function_name = 'dashboard';

            $data['controller_name'] = '/'.$class_name.'/'.$function_name;
            
            $view_path = $this->create_path($debug_arr, 'completed','admin');
            $this->load->view($view_path, $data);
 
        }

        public function marketplace_services_view()
        {


            //create data for view
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            //get marketplace services data
            $data['service_list'] = $this->Admin_model->get_marketplace_services();


            //get actions
            $data['actions'] = $_GET['actions'];
            // echo "<pre>";
            // print_r($data['actions']);
            // echo "</pre>";
            // die;
            

            //create controller name
            $debug_arr = debug_backtrace();
            $class_name = $debug_arr[0]['class'];
            $function_name = $debug_arr[0]['function'];
            $data['source_controller']='/'.$class_name.'/'.$function_name;


            //create view path
            // $view_nature ='not_shared';
            $view_nature ='shared_globally';
            $view_file ='list';
            $view_path = $this->create_path($this->view_shared, $view_nature, $view_file,debug_backtrace(),'admin');
        


            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_path, $data);
            $this->load->view('footer');
      


        }
        
        public function marketplace_services_view_details()
        {
             //create controller name
            $debug_arr = debug_backtrace();
            $class_name = $debug_arr[0]['class'];
            $function_name = $debug_arr[0]['function'];
            $data['source_controller']='/'.$class_name.'/'.$function_name;

            //create field details list
            $menu_items = explode("_",$function_name);
            $data['menu_items'] = array_map('ucfirst',$menu_items);
            $role = $this->controller_role_mapping[$class_name];
            $menu_name = $data['menu_items'][1];
            $submenu_name = $data['menu_items'][2];
            $field_name ='parameter';

            //create data for view
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            //get marketplace services data
            $data['service_list'] = $this->Admin_model->get_marketplace_services();

            $data[$data['menu_items'][3]] = $this->Commen_model->get_marketplace_field($role, $menu_name, $submenu_name, $field_name);

            //GET parameters - service_id
            if(!empty($_GET['service_id'])){
                $service_id = $data['service_id'] = $_GET['service_id'];
                $view_nature ='not_shared';
                $view_file ='edit';
                $view_path = $this->create_path($this->view_shared, $view_nature, $view_file,debug_backtrace(),'admin');
        
                $this->load->view('navbar3',$data);
                $this->load->view('admin/sidebar',$data);
                $this->load->view($view_path, $data);
                $this->load->view('footer');
            }


            //Get parameters - editDetails
            if(!empty($_GET['editDetails'])){
                $editDetails = $data['editDetails'] = $_GET['editDetails'];
                if($editDetails == true)
                {
                    $parameter = $_GET['parameter'];
                    // echo $parameter."<br>";die;
                    $update_status = $this->Commen_model->update_marketplace_field($role, $menu_name, $submenu_name, $field_name, $parameter);
                    if($update_status=true){$this->dashboard();}
                 }

            }
          
            //create view path
            // $view_nature ='not_shared';
            $view_nature ='not_shared';
            $view_file ='edit';
            $view_path = $this->create_path($this->view_shared, $view_nature, $view_file,debug_backtrace(),'admin');
        
            //define target controller
            $data['target_controller'] = $data['source_controller'].ucFirst($view_file);


            // echo "<pre>";
            // print_r($getVars);
            // echo "</pre>";
            // die;

            //load view
            // $this->load->view('navbar3',$data);
            // $this->load->view('admin/sidebar',$data);
            // $this->load->view($view_path, $data);
            // $this->load->view('footer');
      


        }

        public function marketplace_services_view_flow()
        {
            //create data for view
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            //get marketplace services data
            $data['service_list'] = $this->Admin_model->get_marketplace_services();


            //Get data
            $data['service_id'] = $_GET['service_id'];
           
            

            //create controller name
            $debug_arr = debug_backtrace();
            $class_name = $debug_arr[0]['class'];
            $function_name = $debug_arr[0]['function'];
            $data['source_controller']='/'.$class_name.'/'.$function_name;

            //Get details list
            $metadata_field = 'Flow';
            $data[$metadata_field] = $this->Commen_model->get_marketplace_metadata($metadata_field);

            
            //create view path
            // $view_nature ='not_shared';
            $view_nature ='not_shared';
            $view_file ='list';
            $view_path = $this->create_path($this->view_shared, $view_nature, $view_file,debug_backtrace(),'admin');
            // print data array
            // echo "<pre>";
            // print_r($data);
            // print_r($view_path);
            // echo "</pre>";
            // die;
            
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_path, $data);
            $this->load->view('footer');
      
        }

        public function marketplace_services_configure()
        {

            //create data for view
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            //load view
            $view_path = $this->create_path(debug_backtrace(), 'list','admin');
            $this->load->view('navbar3',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_path, $data);
            $this->load->view('footer');
        }

        public function marketplace_services_standardserviceflow()
        {
            //create data for view
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            //load view
            $view_path = $this->create_path(debug_backtrace(), 'list','admin');
            $this->load->view('navbar3',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_path, $data);
            $this->load->view('footer');

        }


        // public function create_path($a, $b, $c)
        // {
        //     $controller_name = $a;
        //     $arr_view_path = explode('_',$controller_name[0]['function']);
        //     $path = implode('/',$arr_view_path);
        //     $file =$b;
        //     $view_full_path = $c.'/'.$path.'/'.$file;
        //     return $view_full_path;
        // }

        public function create_path($view_shared=null, $view_nature=null, $view_file, $a, $c)
        {
            if($view_nature == $view_shared['global'])
            {
                $view_full_path = 'shared'.'/'.$view_file;
            }
            elseif($view_nature == $view_shared['na'])
            {
                $controller_name = $a;
                $arr_view_path = explode('_',$controller_name[0]['function']);
                $path = implode('/',$arr_view_path);
                $view_full_path = $c.'/'.$path.'/'.$view_file;
            }
            return $view_full_path;
        }
        
        
        public function marketplace_notification_add_triggers()
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

           
            // if($this->form_validation->run()== true)
            // {

            $this->load->model('Admin_model');
            $notification = $this->Admin_model->get_notification_types();
            //$notification_data=(explode(",",$notification[0]->parameter));

            $status = $this->Admin_model->get_status_list();
            //$notification_data=(explode(",",$status[0]->parameter));
            
            $activity = $this->Admin_model->get_activity_list();
            
            $db_data['notification']=explode(",",$notification[0]->parameter);
            $db_data['status']=explode(",",$status[0]->parameter);
            $db_data['activity']=explode(",",$activity[0]->parameter);
            // $meta['id']=$id;
            
            // echo "<pre>";
            // print_r($db_data);
            // echo "</pre>";
            // die();

            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view('admin/marketplace/notification/triggers/add_notification_trigger',$db_data);
            $this->load->view('footer');

        }
        
        
        
        public function marketplace_notification_triggers_insert()
        {
            //echo "save";die();
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            
            $status = $this->Admin_model->get_status_list();
            //print_r($status[0]->parameter);
            $count=count(explode(",",$status[0]->parameter));
            $i=1;
            for($i=1;$i<=$count;$i++){
                $formData['activity']=$this->input->post('activity');
                $formData['status']=$this->input->post('status'.$i);
                $notification=$this->input->post('notification'.$i);
                //print_r($notification);die();
                if($notification==null){
                    $formData['notification']="";
                }else{
                    $formData['notification']=implode(",",$notification);
                }
                
                
                $result = $this->Admin_model->insert_notification_trigger($formData);
            }

            if($result)
                {  
                    $this->session->set_flashdata("msg","Added Successfully");
                    redirect('/AdminController/marketplace_notification_triggers','refresh');
                    
                }
            else
                {
                    $this->session->set_flashdata("msg","Opps Unable to add data");
                    redirect('/AdminController/marketplace_notification_triggers','refresh');
                } 

        }
        
        
         public function marketplace_notification_mrp_insert()
        {
            //echo "save";die();
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

                $formData['name']=$this->input->post('name');
                $formData['from']=$this->input->post('from');
                $formData['to']=$this->input->post('to');
                $formData['value']=$this->input->post('value');
                $formData['unit']=$this->input->post('unit');
                
                //print_r($formData);die();
                
                $result = $this->Admin_model->insert_marketplace_mrp($formData);
            

            if($result)
                {  
                    $this->session->set_flashdata("msg","Added Successfully");
                    redirect('/AdminController/marketplace_financials_mrp','refresh');
                    
                }
            else
                {
                    $this->session->set_flashdata("msg","Opps Unable to add data");
                    redirect('/AdminController/marketplace_financials_mrp','refresh');
                } 

        }
        
        
        
        
        public function marketplace_notification_triggers()
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $data['submenu_items'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            $this->load->model('Admin_model');
            $metadata['trigger']= $this->Admin_model->get_trigger_data();
            // echo "<pre>";
            // print_r($metadata['trigger']);die();

            //create view path
            $controller_name = debug_backtrace();
            $arr_view_path = explode('_',$controller_name[0]['function']);
            $path = implode('/',$arr_view_path);
            $file ='list';
            $view_full_path = 'admin/'.$path.'/'.$file;
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_full_path,$metadata);
            $this->load->view('footer');
        }
        
         public function editNotificationTrigger($id)
        {
            //echo "edit".$id;die();
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

           
            // if($this->form_validation->run()== true)
            // {

            $this->load->model('Admin_model');
            
            $notification = $this->Admin_model->get_notification_types();

            $status = $this->Admin_model->get_status_list();
            
            $activity = $this->Admin_model->get_activity_list();
            
            $db_data['notification']=explode(",",$notification[0]->parameter);
            $db_data['status']=explode(",",$status[0]->parameter);
            $db_data['activity']=explode(",",$activity[0]->parameter);
            
            $db_data['trigger']=$this->Admin_model->get_notification_trigger_byId($id);
            
            //print_r($db_data['trigger'][0]->notification);die();
            

            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view('admin/marketplace/notification/triggers/edit_notification_trigger',$db_data);
            $this->load->view('footer');

        }
        
        
        public function marketplace_financials_category()
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $data['submenu_items'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            $this->load->model('Admin_model');
            $c_data['category']= $this->Admin_model->get_financials_categoty_data();
            //$c_data= $this->Admin_model->get_financials_categoty_data();
            
            // echo "<pre>";
            // print_r($c_data['category']['0']['parameter']);
            $parameter= explode(",",$c_data['category']['0']['parameter']);
            $description= explode(",",$c_data['category']['0']['description']);
            
            $count= count($parameter);
            
            
            $new_array = array();
            for($i=0;$i<$count;$i++){
                $new_array[$i]=['parameter'=>$parameter[$i], 'desc'=>$description[$i], 'key'=>$i];
            }
            
            $c_data['paramdata']=$new_array;
            
            // foreach($new_array as $key=>$val){ // Loop though one array
            //   // print_r($val);
            //     echo $val['parameter'];
            //     echo $val['desc'];
            // }
            
            // print_r($parameter);
            // print_r($description);
            //  echo "<pre>";
            //   print_r($c_data);
            
            
            // die();

            //create view path
            $controller_name = debug_backtrace();
            $arr_view_path = explode('_',$controller_name[0]['function']);
            $path = implode('/',$arr_view_path);
            $file ='list';
            $view_full_path = 'admin/'.$path.'/'.$file;
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_full_path,$c_data);
            $this->load->view('footer');
        }
        
        
        public function marketplace_financials_currency()
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $data['submenu_items'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            $this->load->model('Admin_model');
            $c_data['currency']= $this->Admin_model->get_financials_currency_data();
            
            $parameter= explode(",",$c_data['currency']['0']['parameter']);
            $description= explode(",",$c_data['currency']['0']['description']);
            
            $count= count($parameter);
            
            $new_array = array();
            for($i=0;$i<$count;$i++){
                $new_array[$i]=['parameter'=>$parameter[$i], 'desc'=>$description[$i], 'key'=>$i];
            }
            
            $c_data['paramdata']=$new_array;
            // echo "<pre>";
            // print_r($c_data['paramdata']);die();

            //create view path
            $controller_name = debug_backtrace();
            $arr_view_path = explode('_',$controller_name[0]['function']);
            $path = implode('/',$arr_view_path);
            $file ='list';
            $view_full_path = 'admin/'.$path.'/'.$file;
            
            //echo $view_full_path;die();
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_full_path,$c_data);
            $this->load->view('footer');
        }
        public function marketplace_resellers_landingpage()
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $data['submenu_items'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            $this->load->model('Admin_model');
            $metadata['flow']= $this->Admin_model->get_landing_data();
            $controller_name = debug_backtrace();
            $file ='list';

            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view('admin/landing_page_list',$metadata);
            $this->load->view('footer');
        }
        
           
       
        public function fetch_section()
        {   
            $this->load->model('Admin_model');
            $level = $this->input->post('id',TRUE);
            $data = $this->Admin_model->fetch_section($level)->result();
            echo json_encode($data);
            
        }
        public function addLandingTemplateSectionPar()
        {
            //   $id = $this->input->post('section',TRUE);
            //   echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            $this->form_validation->set_rules('section','Section','required');
            if($this->form_validation->run()== true)
            {
                //echo "if";die();
                $id = $this->input->post('id',TRUE);
                $section = $this->input->post('section',TRUE);
                 $parameter = $this->input->post('parameter',TRUE);
                 $values = $this->input->post('values',TRUE);
                $data=[];
                 $data['landingId']=  $id;
                 $data['name']= $section;
                 $data['parameter']=  $parameter;
                 $data['values']= $values;
                $this->load->model('Admin_model');
               
               
               $result = $this->Admin_model->insert_landing_section_data($data);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/marketplace_resellers_landingpage','refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/AdminController/marketplace_resellers_landingpage','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/AdminController/marketplace_resellers_landingpage','refresh');
            }

        }
        public function section_details_landingpage()
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $data['submenu_items'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            $this->load->model('Admin_model');
            $metadata['flow']= $this->Admin_model->get_landing_data();
            //$metadata['flow']= $this->Admin_model->get_landing_data();
          //echo "heko";die;
            //create view path
            $controller_name = debug_backtrace();
            //$arr_view_path = explode('_',$controller_name[0]['function']);
            //$path = implode('/',$arr_view_path);
            $file ='list';
            //$view_full_path = 'admin/'.$path.'/'.$file;
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view('admin/landing_page_details',$metadata);
            $this->load->view('footer');
        }
        public function addLandingSectionNameDetail()
        {
            //   $id = $this->input->post('sectionName',TRUE);
            //   echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            $this->form_validation->set_rules('sectionName','Section','required');
           
            if($this->form_validation->run()== true)
            {
                //echo "if";die();
               $id = $this->input->post('hiddenlandsection',TRUE);
                $section = $this->input->post('sectionName',TRUE);
                $parameter = $this->input->post('parameter',TRUE);
                 $values = $this->input->post('values',TRUE);
                 $details = $this->input->post('details',TRUE);
                // echo $id;die;details
                $data=[];
                 $data['landingPageId']=  $id;
                 $data['section_id']= $section;
                 $data['parameter']=  $parameter;
                 $data['type']= $values;
                 $data['details']=$details;
                $this->load->model('Admin_model');
               $result = $this->Admin_model->insert_landing_details_data($data);
//echo $result;die;
                    if($result)
                    {  
                        
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/page_details_landingpage/'.$id,'refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/AdminController/page_details_landingpage/'.$id,'refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/AdminController/page_details_landingpage/'.$id,'refresh');
            }

        }
        public function  page_details_landingpage($id){
            //echo $id;die;
                        $this->load->database();
                        $this->load->model('User_model');
                        $this->load->model('Commen_model');
                        
                        $user = $this->session->userdata('user');
                        $data['user'] = $user;
                        $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
                        $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
                        $data['submenu_items'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            
                        $this->load->model('Admin_model');
                        $metadata['landingDetailsData']= $this->Admin_model->get_landing_page_details_data($id);
                        $this->load->model('Admin_model');
                        $metadata['flow']= $this->Admin_model->get_landing_data();
                        $controller_name = debug_backtrace();
                        $file ='list';
                        //$metadata['flow']= $this->Admin_model->get_landing_data();
                      //echo "heko";die;
                        //create view path
                        $controller_name = debug_backtrace();
                        
                        $this->load->view('navbar',$data);
                        $this->load->view('admin/sidebar',$data);
                        $this->load->view('admin/landing_page_list',$metadata);
                        $this->load->view('footer');
        }
        public function addLandingParameter()
        {
             // $id = $this->input->post('id',TRUE);
             // echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            $this->form_validation->set_rules('name','Name','required');
            $this->form_validation->set_rules('descripation','Descripation','required');
            $this->form_validation->set_rules('path','path','required');
           
            if($this->form_validation->run()== true)
            {
                //echo "if";die();
                $id = $this->input->post('id',TRUE);
                $name = $this->input->post('name',TRUE);
                $descripation = $this->input->post('descripation',TRUE);
                $path = $this->input->post('path',TRUE);
                $data=[];
                $data['name']=  $name;
                $data['descripation']= $descripation;
                $data['path']= $path;

                $this->load->model('Admin_model');
                $result = $this->Admin_model->insert_landing_data($data);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/marketplace_resellers_landingpage','refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/AdminController/marketplace_resellers_landingpage','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/AdminController/marketplace_resellers_landingpage','refresh');
            }

        }
        public function landingDelete($id)
        {
            //echo "edit";die();
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
            $this->load->model('Admin_model');
            $result = $this->Admin_model->landingDelete($id);
            if($result==True){
                $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/AdminController/marketplace_resellers_landingpage','refresh');
        }else{
            $this->session->set_flashdata('msg','Something Wrong');
            redirect('/AdminController/marketplace_resellers_landingpage','refresh');
        }
            $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/AdminController/marketplace_resellers_landingpage','refresh');

        }
        public function landingDetailsDelete($id)
        {
            //echo "edit";die();
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
            $this->load->model('Admin_model');
            $result = $this->Admin_model->landingDetailsDelete($id);
            if($result==True){
                $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/AdminController/page_details_landingpage/'.$id,'refresh');
        }else{
            $this->session->set_flashdata('msg','Something Wrong');
            redirect('/AdminController/page_details_landingpage/'.$id,'refresh');
        }
            $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/AdminController/page_details_landingpage/'.$id,'refresh');

        }
        public function updateLandingParameter()
        {
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
                $descripation = $this->input->post('descripation',TRUE);
                $path = $this->input->post('path',TRUE);
                $name = $this->input->post('name',TRUE);
                $id = $this->input->post('id',TRUE);
                
                // echo $id.$key.$new_param.$name;die();
                
                // echo $id;die();

                $this->load->model('Admin_model');
               // $db_data = $this->Admin_model->get_land_byId($id);
                //echo "<pre>";
                 
                
                
                $parameters= [
                    'name'=>$name,
                    'descripation'=>$descripation,
                    'path'=>$path,
                    ];
                $result = $this->Admin_model->updatLandingById($id, $parameters);
                //print_r($parameters);echo "</pre>";die();
               
                if($result)
                {  
                    $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/marketplace_resellers_landingpage','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","Opps Unable to add data");
                                            redirect('/AdminController/marketplace_resellers_landingpage','refresh');
                    
                }      
        }
        public function updateLandingDetailsParameter()
        {
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
                $parameter = $this->input->post('parameter',TRUE);
                $values = $this->input->post('values',TRUE);
                 $section = $this->input->post('section',TRUE);
                 $id = $this->input->post('id',TRUE);
                 $landingPageId = $this->input->post('landingPageId',TRUE);
                
                // echo $id.$key.$new_param.$name;die();
                
                // echo $id;die();

                $this->load->model('Admin_model');
               // $db_data = $this->Admin_model->get_land_byId($id);
                //echo "<pre>";
                 
                
                
                $parameters= [
                    'parameter'=>$parameter,
                    'type'=>$values,
                    'id'=>$id,
                    'landingPageId'=>$landingPageId,
                    'section_id'=>$section,
                    ];
                $result = $this->Admin_model->updatLandingDetailsById($id, $parameters);
                //print_r($parameters);echo "</pre>";die();
               
                if($result)
                {  
                    $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/AdminController/page_details_landingpage/'.$landingPageId,'refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","Opps Unable to add data");
                                            redirect('/AdminController/page_details_landingpage/'.$landingPageId,'refresh');
                    
                }      
        }
        // public function add_services_config()
        // {
        //     $this->load->library('form_validation');
        //     $this->load->model('User_model');
        //     if($this->User_model->authorized()==false)
        //     {
        //         $this->session->set_flashdata('msg','You are not authorized to access this section');
        //         redirect(base_url().'/UserController/login');
        //     }
        //     $user = $this->session->userdata('user');
        //     $data['user'] = $user;

        //     $this->load->model('Admin_model');
        //     $this->load->view('navbar',$data);
        //     $this->load->view('admin/sidebar');
        //     $this->load->view('admin/add_service_form_config');
        //     $this->load->view('footer');
            
        // }
        // public function insert_services_configer()
        // {
               
        //         $this->form_validation->set_rules('solution_name','Solution','required');
        //         $this->form_validation->set_rules('description','Description','required');
        //         $this->form_validation->set_rules('package','No of Reports','required');
        //         $this->form_validation->set_rules('mrp','mrp','required');
        //         $this->form_validation->set_rules('discount','Discount','required');
               
        //         if($this->form_validation->run() == true)
        //         {            
                       
        //             $solution = $_POST['solution_name'];
        //             $package = $_POST['package'];  
        //             $code_id = $solution.$package;
        //             $this->db->where('code_id',$code_id);
        //             $rowcount = $this->db->get('reseller_code_record')->num_rows();
        //             echo $rowcount;
        //             if($rowcount == 0)
        //             {
        //                 $formArray = array(
        //                     'code_id'=>$code_id,
        //                     'solution'=>$_POST['solution_name'],
        //                     'discription'=>$_POST['description'],
        //                     'no_of_reports'=>$_POST['package'],
        //                     'mrp'=>$_POST['mrp'],
        //                     'discount'=> $_POST['discount'],
        //                     'status' => 'active'
        //                 );
        //                 $formArray2 = array(
        //                     'solution'=>$_POST['solution_name'],
        //                     'description'=>$_POST['description']
        //                 );
        //                 $this->db->where('solution',$solution);
        //                 $rowcount2 = $this->db->get('solutions')->num_rows();
        //                 if($rowcount2 == 0)
        //                 {
        //                     // redirect('AdminController/ch');
                            
        //                     $this->db->insert('solutions',$formArray2);
        //                     $this->db->insert('reseller_code_record',$formArray);
        //                 }
        //                 else
        //                 {
        //                     // redirect('AdminController/dch');
        //                     $this->db->insert('reseller_code_record',$formArray);
        //                 }
        //                 $this->session->set_flashdata("msg","Package Saved Successfully");
        //                 redirect('/AdminController/add_services_config','refresh');
        //             }
        //             else
        //             {
        //                 $this->session->set_flashdata("msg","This Package Already Exist");
        //                 redirect('/AdminController/add_services_config','refresh');
        //             }
                    
        //                               // $this->session->set_flashdata("msg","Your account has been registered. You can login now");
        //             // redirect('/UserController/registration','refresh');
                    
        //         }
        //         else
        //         {
        //             $this->session->set_flashdata("msg","All Field Required");
        //             redirect('/AdminController/add_services_config','refresh');
        //         } 
        // }
        
        
        public function marketplace_financials_mrp()
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $data['submenu_items'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            $this->load->model('Admin_model');
            $c_data['mrp']= $this->Admin_model->get_mrp_data();
            //print_r($c_data);die();

            //create view path
            $controller_name = debug_backtrace();
            $arr_view_path = explode('_',$controller_name[0]['function']);
            $path = implode('/',$arr_view_path);
            $file ='list';
            $view_full_path = 'admin/'.$path.'/'.$file;
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view($view_full_path,$c_data);
            $this->load->view('footer');
        }
        
        public function addMRP()
        {
            //echo "addMRP";die();
            
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $data['submenu_items'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            $this->load->model('Admin_model');
            $currency= $this->Admin_model->get_currency();
            
            
            $c_data['currency']=explode(",",$currency['0']['parameter']);
            //print_r($c_data); die();

            //create view path
            $controller_name = debug_backtrace();
            // $arr_view_path = explode('_',$controller_name[0]['function']);
            // $path = implode('/',$arr_view_path);
            // $file ='list';
            // $view_full_path = 'admin/'.$path.'/'.$file;
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view('admin/marketplace/financials/mrp/add_mrp',$c_data);
            $this->load->view('footer');

        }
        
        
        public function editMRP($id)
        {
            //echo "editMRP".$id;die();
            
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            $data['submenu_items'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');

            $this->load->model('Admin_model');
            $currency= $this->Admin_model->get_currency();
            //$mrp_data= $this->Admin_model->get_mrp_by_Id($id);
            
            $c_data['currency']=explode(",",$currency['0']['parameter']);
            $c_data['mrp_data']=$this->Admin_model->get_mrp_by_Id($id);
            //echo "<pre>";
            //print_r($c_data); die();

            //create view path
            $controller_name = debug_backtrace();
            // $arr_view_path = explode('_',$controller_name[0]['function']);
            // $path = implode('/',$arr_view_path);
            // $file ='list';
            // $view_full_path = 'admin/'.$path.'/'.$file;
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar',$data);
            $this->load->view('admin/marketplace/financials/mrp/edit_mrp',$c_data);
            $this->load->view('footer');

        }
        
        public function marketplace_mrp_update()
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
                $id=$this->input->post('id');
                $formData['name']=$this->input->post('name');
                $formData['from']=$this->input->post('from');
                $formData['to']=$this->input->post('to');
                $formData['value']=$this->input->post('value');
                $formData['unit']=$this->input->post('unit');
                
                //print_r($formData);die();
                
                $result = $this->Admin_model->update_marketplace_mrp($formData, $id);
            

            if($result)
                {  
                    $this->session->set_flashdata("msg","Updated Successfully");
                    redirect('/AdminController/marketplace_financials_mrp','refresh');
                    
                }
            else
                {
                    $this->session->set_flashdata("msg","Opps Unable to Updated data");
                    redirect('/AdminController/marketplace_financials_mrp','refresh');
                } 
        }

}
?>