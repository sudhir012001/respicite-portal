<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  
class PaymentController extends CI_Controller {
  
    public function __construct()
    {
        parent::__construct();
             
     }
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
    public function paymentType()
    {   
       $data['payment_gateways'] = $this->config->item('payment_gateways');



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
        $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
        $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
        $data['payment_details'] = $this->Admin_model->getpayment_details_data($user['id']);
          $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']); 
          //echo  "<pre>";print_r($data['allowed_services']);die;
            $this->load->view('navbar',$data);

            if($user['fullname']=='Admin'){
                $this->load->view('admin/sidebar',$data);
            }else if($user['iam']=='sp'){
                $data['booking_url']='https://calendly.com/';
               $this->load->view('sp/sidebar',$data);
            }
            else{
                $data = $this->initializer();
                $this->load->view('sidebar',$data);}

            $data['payment_details'] = $this->Admin_model->getpayment_details();
            $this->load->view('shared/payment/paymentView',$data);
            $this->load->view('footer');
    }  
    public function addPaymentParameter()
    {   
      
         $this->load->model('User_model');
         $this->load->model('Commen_model');
         $this->load->library('form_validation');
            //$this->load->model('User_model');
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
            $this->load->view('navbar',$data);
            $this->load->view('admin/sidebar');
            $this->load->view('shared/payment/addpaymentView');
            $this->load->view('footer');
            // $this->load->view('shared/payment/addpaymentView',$data);
            // $this->load->view('footer');
    }
     public function savePaymentParameter()
        {
              // echo 'fdsfd';die;
                $this->form_validation->set_rules('name','Payment Gatway Type','required');
                $this->form_validation->set_rules('public_key','Public Key','required');
                $this->form_validation->set_rules('api_key','No of Reports','required');
                $this->form_validation->set_rules('controller','Contriller Name','required');
                //$this->form_validation->set_rules('discount','Discount','required');
               
                if($this->form_validation->run() == true)
                {     
                   // echo 'sdfsdfsdf';die;
                    $this->load->model('Admin_model');
                    $user = $this->session->userdata('user');
                    $name = $_POST['name'];
                    $public_key = $_POST['public_key']; 
                    $api_key = $_POST['api_key'];
                    $controller = $_POST['controller'];
                    $data =[
                        'payment_type_name'=>$name,
                        'controller_name'=>$controller,
                        ];
                    $paymentId=$this->Admin_model->savePaymetDetails($data);
                    $formArray = array(
                                'api_key'=>$api_key,
                                'secret_key'=>$public_key,
                                'user_id'=>$user['id'],
                                'payment_type'=>$paymentId
                                );
                    $paymentId=$this->Admin_model->savepaymentcrd($formArray);
                     $this->session->set_flashdata("msg","save data successfully");
                    redirect('/payment-gateway/configure','refresh');
                    
                }else
                {
                    $this->session->set_flashdata("msg","All Field Required");
                    redirect('/payment-gateway/configure','refresh');
                } 
        }
    
}
