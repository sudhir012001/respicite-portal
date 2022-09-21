<?php

class Marketing extends CI_Controller
{
        

        protected $user;
        protected $is_live = false;
        protected $view_prefix_false = 'demo_';

        public function __construct()
        {
            parent::__construct();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            $this->load->model("Base_model");
            $this->load->model("Marketing_Model");
            
            // var_dump($mot_menu);die;
           
        }

        public function initialize()
        {
            
            
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
        public function dashboard()
        {
            
            $data = $this->initialize();
            

            //page parametes
            if(!$this->is_live){$data['page_params'] = $this->config->item('page_params_demo');}
            else{$data['page_params'] = json_decode(file_get_contents('http://users.respicite.com/api/marketing'));}


            $view_prefix = $this->is_live ? "" : $this->view_prefix_false;
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar',$data); 
            $this->load->view('user/marketing/'.$view_prefix.'dashboard',$data); 
            $this->load->view('footer');
        }
            

        public function order_performance()
        {
            
            $data = $this->initialize();
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar',$data); 
            $this->load->view('user/marketing/order_performance'); 
            $this->load->view('footer');
        }

        public function engagement()
        {
            
            $data = $this->initialize();
            $this->load->view('navbar',$data);
            $this->load->view('user/sidebar',$data); 
            $this->load->view('user/marketing/engagement'); 
            $this->load->view('footer');
        }

        public function view_loader($arr_view)
        {
            

        }

        public function api_wrapper($url)
        {
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $url);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_POST, 1);
            $buffer = curl_exec($curl_handle);
            curl_close($curl_handle);
            $user = json_decode($buffer);
            if(isset($user->status) && $user->status == 'success')
            {echo 'User has been updated.';}
            else{echo 'Something has gone wrong';}
            return $user;

        }
}
