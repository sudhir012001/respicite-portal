<?php
require_once APPPATH.'third_party/razorpay-php/Razorpay.php';
use Razorpay\Api\Api;
class Payment extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->model("Sp_model");
        $this->load->model("User_model");
         $this->load->model("Base_model");
         $this->load->model("Commen_model");
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
            redirect(base_url().'BaseController/request_code');
        }


        $sp_details = $this->Sp_model->get_sp_details("DETAILS_BY_EMAIL",$purchase_code_details['r_email']);
        $user_details = $this->User_model->get_user_details("DETAILS_BY_EMAIL",$purchase_code_details['u_email']);
        $solution_details = $this->User_model->get_user_details("SOLUTION_BY_NAME",$purchase_code_details['solution_id']);

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
        
        $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['reseller_sp'] = $this->Commen_model->get_reseller_sp($data['user']['user_id']);
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            
        
        //send checkout detail in view page. 
        $order_json = [
                "key"               => API_KEY,
                "amount"            => $pay_data->amount_paise,
                "name"              => $sp_details->fullname,
                "description"       => $solution_details['display_solution_name'],
                "image"             => $sp_logo,
                "prefill"           => [
                "name"              => $user_details['fullname'],
                "email"             => $user_details['email'],
                "contact"           => $user_details['mobile'],
                ],
                "notes"             => [
                "address"           => "Respicite"
                ],
                "theme"             => [
                "color"             => "#fc9928"
                ],
                "order_id"          => $pay_data->transaction_id,
                "callback_url"      => base_url("payment/payment_status")
        ];        
        
        //sp detail
        /* $data["sp"] = [
            "fullname"  =>  $sp_details->fullname,
            "email"     =>  $sp_details->email,
            "logo"      =>  $sp_logo
        ]; */

        $data["user_detail"] = $user_details;
        $data["solution"] = $solution_details;
        $data["api_pay"] = json_encode($order_json);
        $data['user'] = $this->session->userdata('user');

        $this->load->view('navbar',$data);
        $this->load->view('user/sidebar'); 
        $this->load->view("user/payment_ui",$data);
        $this->load->view('footer');
    }

    function payment_status(){
        $purchase_code_details = $this->session->userdata("purchase_code_details");
        $solution_details = $this->User_model->get_user_details("SOLUTION_BY_NAME",$purchase_code_details['solution_id']);
        $user = $this->session->userdata('user');
            $data['user'] = $user;

            
        // $msg = 
        $api = new Api(API_KEY,API_SECRET);
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
            
             
            $check_payment_id = $this->db->get_where("razorpay_transaction_history",["razorpay_payment_id"=>$razorpay_payment_id]);
            if($check_payment_id->num_rows() <= 0){
                $this->db->trans_start();
                if ($success === true)
                {
                    $res_data = array(
                        'razorpay_payment_id' =>  $razorpay_payment_id,
                        'razorpay_order_id' => $razorpay_order_id,
                        'razorpay_signature' => $razorpay_signature,
                        'payment_status' => "success"
                    );
                    
                    $this->db->insert("razorpay_transaction_history",$res_data);
                    if($this->db->affected_rows() > 0){

                        $purchase_details = array(
                            'user_id' => $purchase_code_details['u_email'],
                            'reseller_id' => $purchase_code_details['r_email'],
                            'solution' => $solution_details['solution'],
                            'display_solution_name' => $solution_details['display_solution_name'],
                            'status' => 'pending',
                            'payment_mode' => 'online'
                        );

                        //insert code in user_code_list.
                        $this->db->insert('user_code_list',$purchase_details);  
                        if($this->db->affected_rows() > 0){
                            //update transaction status
                            $insert_id = $this->db->insert_id();
                            $this->db->set("code_purchase_id",$insert_id);
                            $this->db->set("transaction_status","success");
                            $this->db->where([
                                "transaction_id" => $this->input->post("razorpay_order_id"),
                                "u_email"        => $purchase_code_details['u_email'],
                                "r_email"        => $purchase_code_details['r_email']
                            ]);
                            $this->db->limit(1);
                            $this->db->update("user_payment_history");
                            
                            //update user_code_list table in db
                            $this->db->set("payment_status","success");
                            $this->db->where("id",$insert_id);
                            $this->db->limit(1);
                            $this->db->update("user_code_list");
                            $data_view["roder_id"] = $razorpay_order_id;
                            $this->load->view("user/payment_success",$data_view); 
                            $this->session->unset_userdata('purchase_code_details');                     
                        }else{
                            $this->db->trans_rollback();
                        }                        
                    }else{
                        $this->db->trans_rollback();
                    }
                }
                else
                {
                    $res_data = array(
                        'razorpay_payment_id' =>  $razorpay_payment_id,
                        'razorpay_order_id' => $razorpay_order_id,
                        'razorpay_signature' => $razorpay_signature,
                        'payment_status' => "failed"
                    );

                    $this->db->insert("razorpay_transaction_history",$res_data);
                    $data_view["roder_id"] = $razorpay_order_id;
                    $this->load->view("user/payment_failed",$data_view); 
                    $this->session->unset_userdata('purchase_code_details'); 
                } 
                $this->db->trans_complete();
            }else{
                echo "<div style='text-align:center;padding:10px'>
                <a href='".bace_url("BaseController/purchase_code_history")."' style='text-decoration: none;color: white;padding: 13px;border-radius: 33px;background: #f46b36;'>Back to Purchase Code History</a></div>";
                show_404();
            }            
        }else{
            echo "<div style='text-align:center;padding:10px'>
            <a href='".bace_url("BaseController/purchase_code_history")."' style='text-decoration: none;color: white;padding: 13px;border-radius: 33px;background: #f46b36;'>Back to Purchase Code History</a></div>";
            show_404();
        }
 
    }

    /* function test(){
        $this->load->view("user/payment_failed");
    } */

}
?>