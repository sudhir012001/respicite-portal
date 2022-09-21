<?php
date_default_timezone_set("Asia/Kolkata");
    class ExpiryController extends CI_Controller
    {
        
 
        public function __construct()
        {
            parent::__construct();
            //$this->load->model('Commen_model');
           
        }

        public function checkExpiry()
        {

            $this->load->model("Sp_model");
            $code = base64_decode($code);
            $data['code'] = $code;
            $this->load->model('User_model');
           
            
        }

       
        
        
    } //class #END. here

?>