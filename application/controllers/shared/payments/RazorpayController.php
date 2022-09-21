<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'third_party/razorpay-php/Razorpay.php';
use Razorpay\Api\Api;
  
class RazorpayController extends CI_Controller {
  
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Sp_model");
        $this->load->model("User_model");
             
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
    public function insertCreditial($id)
    {   
            $this->load->model('User_model');
            $this->load->model('Admin_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            //echo "<pre>";print_r($user);die;
            $data['get_payment_details_id_vias'] = $this->Admin_model->get_payment_details_id_vias($id);
            //echo "<pre>";print_r($data['get_payment_details_id_vias']);die;
            $data['user'] = $user;
            $this->load->view('navbar',$data);
            if($user['fullname']=='Admin'){
            $this->load->view('admin/sidebar',$data);
            }else if($user['iam']=='sp'){
               $this->load->view('sp/sidebar',$data);
            }else{
                $data = $this->initializer();
            $this->load->view('sidebar',$data);    
            }
            $this->load->view('shared/payment/razorpayCrdView',$data); 
            $this->load->view('footer'); 

            if(isset($_POST['updatebtn']))
            {
                $this->form_validation->set_rules('api_key','API KEY|min_length[3]','required');
                $this->form_validation->set_rules('api_secret','API SECRET','required|min_length[3]');
                if($this->form_validation->run() == true)
                {  
                    $count = $this->Admin_model->get_payment_details_id_vias($id);
                     $formArray = array(
                                'api_key'=>$_POST['api_key'],
                                'secret_key'=>$_POST['api_secret'],
                                'user_id'=>$user['id'],
                                'payment_type'=>$id
                                );
                if(!isset($count)){
                    $this->Admin_model->inserCrd($formArray); 
                }else{
                   $this->Admin_model->updatePaymentCrd($formArray,$id);
                }
               
                $sessArray['id'] = $user['id'];
                $sessArray['fullname']=$user['fullname'];
                $sessArray['email']=$user['email'];
                $sessArray['mobile']=$user['mobile'];
                $sessArray['user_id']=$user['user_id'];
                $sessArray['profile_photo']=$user['profile_photo'];
                $sessArray['iam']=$user['iam'];
                $this->session->set_userdata('user',$sessArray);
                $this->session->set_flashdata('msg2','Detail Updated');
                redirect('/payment-gateway/razorypay/keys/'.$id,'refresh');
                }
            }
    }  
    public function razorypayStatus(){
        //echo $_GET['status'];die;
        $id = $_GET['id'];
        $status= $_GET['status'];
        $this->load->model('Admin_model');
        $this->load->model('User_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        //$allowed_services = $this->Admin_model->getUserDetailsById($user['id']);
        //$rr = explode(",",$allowed_services); 
        //if(in_array("offline", $rr)){     
            $status = ($status==0) ? 1:0;
            $updatedStatus = $this->Admin_model->paymentGatwayActiveDeactive($id,$status);
        // }else{

        // }
        if ($updatedStatus==true) {
          $this->session->set_flashdata('msg','Status Change ');
            redirect(base_url().'payment-gateway/configure');
        }else{
          $this->session->set_flashdata('msg','something wrong');
            redirect(base_url().'payment-gateway/configure');
        }
    }
    function counseling_payment_online($email,$couseling_id){
            $this->load->model("Sp_model");
            $this->load->model("User_model");
            $this->load->model('Base_model');
            $this->load->model('Admin_model');
            $couseling_code_details = [
                "u_email"           => urldecode($email),
                "solution_id"       => urldecode($couseling_id)
            ];

            $getCounselingParaDetails= $this->User_model->getCounselingParaDetails($couseling_id);
            //$user_details = $this->User_model->get_user_details("DETAILS_BY_EMAIL",$purchase_code_details['u_email']);
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
            $creditial = $this->Base_model->getresellerCrd();
            
            //initialize payment api by razarpay
            $payment_api = new Api( "rzp_test_paaTQ8BBFWTNOI","K01B0oKJaUEmTxcZq8GvGMpo");        
            $order_data = [
                'receipt'         => "rcptid_".$user["id"]."_".rand(1000,100000),
                'amount'          => $getCounselingParaDetails->mrp*100, // 39900 rupees in paise
                'currency'        => 'INR'
            ];

            //create payment 
            $create_order = $payment_api->order->create($order_data);

            //insert all required info in DB.
            $save_data = [
                "r_email"                   => $email,
                "u_email"                   => $email,
                "solution_id"               => $couseling_id,
                "solution_name"             => $getCounselingParaDetails->name,
                "solution_price"            => $getCounselingParaDetails->mrp,
                "transaction_id"            => $create_order['id'],
                "amount_paise"              => $create_order['amount'],
                "currency"                  => $create_order['currency'],
                "receipt"                   => $create_order['receipt'],
                "transaction_status"        => $create_order['status'],
                "transaction_created_at"    => $create_order['created_at']
            ];

            $this->db->insert("user_payment_history",$save_data);
            $couseling_code_details = [
                "transaction_id"    => $create_order['id'],
                "u_email"           => urldecode($email),
                "solution_id"       => urldecode($couseling_id)
            ];

            $this->session->set_userdata("purchase_code_details",$couseling_code_details);
            redirect(base_url().'payment-gateway/checkout');
        }
        function checkout(){
        $data = [];
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }

        //get all details in data DB.
        $purchase_code_details = $this->session->userdata("purchase_code_details");

        if(empty($purchase_code_details)){
            redirect(base_url().'BaseController/counselingParameters');
        }


        // $sp_details = $this->Sp_model->get_sp_details("DETAILS_BY_EMAIL",$purchase_code_details['r_email']);
        // $user_details = $this->User_model->get_user_details("DETAILS_BY_EMAIL",$purchase_code_details['u_email']);
        // $solution_details = $this->User_model->get_user_details("SOLUTION_BY_NAME",$purchase_code_details['solution_id']);

         $pay_data = $this->db->get_where("user_payment_history",["transaction_id"=>$purchase_code_details['transaction_id']])->row();
        // print_r($pay_data);
        //find log, if logo not exist then use respicite log by default.
        if(!empty($sp_details->logo)){
            if(file_exists("./$sp_details->logo")){
                $sp_logo = base_url($sp_details->logo);
            }else{
                $sp_logo = base_url("uploads/1631091187.png"); 
            }
        }else{
            $sp_logo = base_url("uploads/1631091187.png");
        }
         $this->load->model('Commen_model');
         $this->load->model('Base_model');
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
            $creditial = $this->Base_model->getresellerCrd();
            $getCounselingParaDetails= $this->User_model->getCounselingParaDetails($purchase_code_details['solution_id']);
        //send checkout detail in view page. 
        $order_json = [
                "key"               => 'rzp_test_paaTQ8BBFWTNOI',
                "amount"            => $pay_data->amount_paise,
                "name"              => $getCounselingParaDetails->name,
                "description"       =>$getCounselingParaDetails->type, //$solution_details['display_solution_name'],
                "image"             => $sp_logo,
                "prefill"           => [
                "name"              => $getCounselingParaDetails->name,//$user_details['fullname'],
                "email"             => $purchase_code_details['u_email'],
                "contact"           => "8319451775",//$user_details['mobile'],
                ],
                "notes"             => [
                "address"           => "Respicite"
                ],
                "theme"             => [
                "color"             => "#fc9928"
                ],
                "order_id"          => $pay_data->transaction_id,
                "callback_url"      => base_url("payment-gateway/status")
        ];        
        
        //user detail
        //  $data = [
        //     "fullname"  =>  $getCounselingParaDetails->name,
        //     "type"     =>  $getCounselingParaDetails->type,
        //     "mrp"      =>  $getCounselingParaDetails->mrp
        // ]; 

        $data["fullname"] = $getCounselingParaDetails->name;
        $data["type"] = $getCounselingParaDetails->type;
        $data["mrp"] = $getCounselingParaDetails->mrp;
        $data["api_pay"] = json_encode($order_json);
        $data['user'] = $this->session->userdata('user');

        $this->load->view('navbar',$data);
        $this->load->view('user/sidebar',$data); 
        $this->load->view("shared/payment/razor_payment_ui",$data);
        $this->load->view('footer');
    }
    function payment_status(){
        $purchase_code_details = $this->session->userdata("purchase_code_details");
       // $solution_details = $this->User_model->get_user_details("SOLUTION_BY_NAME",$purchase_code_details['solution_id']);
        $user = $this->session->userdata('user');
            $data['user'] = $user;

             //$creditial = $this->Base_model->getresellerCrd();
        // $msg = 
        $api = new Api('rzp_test_paaTQ8BBFWTNOI','K01B0oKJaUEmTxcZq8GvGMpo');
        $success = false;
        $error ;

        $razorpay_payment_id = $this->input->post("razorpay_payment_id");
        $razorpay_order_id = $this->input->post("razorpay_order_id");
        $razorpay_signature = $this->input->post("razorpay_signature");
        
        if (!empty($razorpay_payment_id)){
            try
            {
                $attributes = array(
                    'razorpay_payment_id' =>  $razorpay_payment_id,
                    'razorpay_order_id' => $razorpay_order_id,
                    'razorpay_signature' => $razorpay_signature
                );
               
                $api->utility->verifyPaymentSignature($attributes);
                $success = true;
            }
            catch(SignatureVerificationError $e)
            {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
                $this->db->trans_start();
                if ($success === true)
                {
                    $this->db->set("payment_status","success");
                            $this->db->where("id",$purchase_code_details['solution_id']);
                            $this->db->limit(1);
                            $this->db->update("counseling_parameters");
                    $data_view["roder_id"] = $razorpay_order_id;
                    $this->load->view("shared/payment/razor_payment_success",$data_view); 
                            $this->session->unset_userdata('purchase_code_details'); 
                }
                else
                {
                 
                    $data_view["roder_id"] = $razorpay_order_id;
                    $this->load->view("user/payment_failed",$data_view); 
                    $this->session->unset_userdata('purchase_code_details'); 
                } 
                
                    
        }else{
            echo "<div style='text-align:center;padding:10px'>
            <a href='".bace_url("BaseController/purchase_code_history")."' style='text-decoration: none;color: white;padding: 13px;border-radius: 33px;background: #f46b36;'>Back to Purchase Code History</a></div>";
            show_404();
        }
 
    }
    
}
