<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Reports extends CI_Controller{

    public function __construct(){
         parent::__construct();        
         $this->load->library('Report_PDF');
         // $this->load->model("Commen_model");
        }

        function ecp($code){
           $code =  base64_decode($code);
            // $this->Commen_model->wla_ppe_data();
           $this->report_pdf->ecp_report_pdf($code);
        }

        function disha($code){
           $code =  base64_decode($code);
            // $this->Commen_model->wla_ppe_data();
           $this->report_pdf->disha_report_pdf($code);
        }
}
?>