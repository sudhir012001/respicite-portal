<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'third_party/razorpay-php/Razorpay.php';
use Razorpay\Api\Api;
  
class StripController extends CI_Controller {
  
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
            $data['get_payment_details_id_vias'] = $this->Admin_model->get_payment_details_id_vias($id);
            $user = $this->session->userdata('user');
            $data['user']=$user;
            $this->load->view('navbar',$data);
            if($user['fullname']=='Admin'){
            $this->load->view('admin/sidebar',$data);
            }else if($user['iam']=='sp'){
               $this->load->view('sp/sidebar',$data);
            }else{
                $data = $this->initializer();
            $this->load->view('sidebar',$data);    
            }
            $this->load->view('shared/payment/stripCrdView',$data); 
            $this->load->view('footer'); 

            if(isset($_POST['updatebtn']))
            {
                $this->form_validation->set_rules('api_key','API KEY|min_length[3]','required');
                $this->form_validation->set_rules('api_public','API SECRET','required|min_length[3]');
                if($this->form_validation->run() == true)
                {  
                    $count = $this->Admin_model->get_payment_details_id_vias($id);
                     $formArray = array(
                                'api_key'=>$_POST['api_key'],
                                'secret_key'=>$_POST['api_public'],
                                'user_id'=>$user['id'],
                                'payment_type'=>$id
                                );
                    //echo "<pre>";print_r($formArray);die;                
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
                redirect('/payment-gateway/stripe/keys/'.$id,'refresh');
                }
            }
    }
    public function stripView()
    {
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $data = $this->initializer();
        //$this->load->view('navbar',$data);
        //$this->load->view('user/sidebar',$data);
        $this->load->view('shared/payment/stripView'); 
        //$this->load->view('footer');     
    }
    public function checkout()
    {
        //check whether stripe token is not empty
        if(!empty($_POST['stripeToken']))
        {
            //get token, card and user info from the form
            $token  = $_POST['stripeToken'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $card_num = $_POST['card_num'];
            $card_cvc = $_POST['cvc'];
            $card_exp_month = $_POST['exp_month'];
            $card_exp_year = $_POST['exp_year'];
            
            //include Stripe PHP library
            require_once APPPATH."third_party/stripe/init.php";
            $user = $this->session->userdata('user');
            $data['user'] = $user;

            
            //set api key
            $stripe = array(
              "secret_key"      => 'sk_test_51LUwbSSJzzi9nvKCyxxWaTAbYEC4vW3K5kNkWedFDtVwkNfi7ytG48l2CJXPwAj3FtKdBPCPjkVVUewtprQX8u91001rnGJNvq',
              "publishable_key" => 'pk_test_51LUwbSSJzzi9nvKCkU4WQ8chYyhrzrbaQpTOLlkFDneMqJD1GOoSF8EsyewXsu6V8hMfGhl0UJ2B2iOPqMOskbpr00UOGRNnZ8'
            );
            
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            
            //add customer to stripe
            $customer = \Stripe\Customer::create(array(
                'email' => $email,
                'source'  => $token
            ));
            //retrieve charge details
            $chargeJson = $customer->jsonSerialize();
            //item information
            $itemName = "Stripe Donation";
            $itemNumber = "PS123456";
            $itemPrice = 500;
            $currency = "inr";
            $orderID = "SKA92712382139";
            
            //charge a credit or a debit card
            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $itemPrice,
                'currency' => $currency,
                'description' => $itemNumber,
                'metadata' => array(
                    'item_id' => $itemNumber
                )
            ));
            
            //retrieve charge details
            $chargeJson = $charge->jsonSerialize();

            //check whether the charge is successful
            if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1)
            {
                //order details 
                $amount = $chargeJson['amount'];
                $balance_transaction = $chargeJson['balance_transaction'];
                $currency = $chargeJson['currency'];
                $status = $chargeJson['status'];
                $date = date("Y-m-d H:i:s");
            
                
                //insert tansaction data into the database
                $dataDB = array(
                    'name' => $name,
                    'email' => $email, 
                    'card_num' => $card_num, 
                    'card_cvc' => $card_cvc, 
                    'card_exp_month' => $card_exp_month, 
                    'card_exp_year' => $card_exp_year, 
                    'item_name' => $itemName, 
                    'item_number' => $itemNumber, 
                    'item_price' => $itemPrice, 
                    'item_price_currency' => $currency, 
                    'paid_amount' => $amount, 
                    'paid_amount_currency' => $currency, 
                    'txn_id' => $balance_transaction, 
                    'payment_status' => $status,
                    'created' => $date,
                    'modified' => $date
                );

                if ($status == 'succeeded') {
                    //if($this->db->insert_id() && $status == 'succeeded'){
                        //$data['insertID'] = $this->db->insert_id();
                        //$this->load->view('payment_success', $data);
                         redirect('shared/payment/StripController/payment_success','refresh');
                    // }else{
                    //     echo "Transaction has been failed";
                    // }
                }
                else
                {
                    echo "not inserted. Transaction has been failed";
                }

            }
            else
            {
                echo "Invalid Token";
                $statusMsg = "";
            }
        }
    }   
    public function payment_success()
    {
        $this->load->view('shared/payment/payment_success');
    }

    public function payment_error()
    {
        $this->load->view('shared/payment/payment_error');
    }
    public function stripeStatus(){
        //echo $_GET['id'];die;
        $id = $_GET['id'];
        $status= $_GET['status'];
        $this->load->model('Admin_model');
        $this->load->model('User_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $status = ($status==0) ? 1:0;
        //echo "hello";die;
        $updatedStatus = $this->Admin_model->paymentGatwayActiveDeactive($id,$status);
        //echo $updatedStatus;die;
        if ($updatedStatus==true) {
          $this->session->set_flashdata('msg','Status Change ');
            redirect(base_url().'payment-gateway/configure');
        }else{
          $this->session->set_flashdata('msg','something wrong');
            redirect(base_url().'payment-gateway/configure');
        }
    }
}
