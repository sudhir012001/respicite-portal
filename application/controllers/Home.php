<?php
date_default_timezone_set("Asia/Kolkata");
class Home extends Ci_controller{
    function __construct(){
        parent::__construct();
        $this->load->model("Home_model");
    }

    function service_provider($service_name = null,$city = null,$days = null,$channel = null){
        $service_name = urldecode($service_name);
        $city = urldecode($city);
        $days = urldecode($days);
        $channel = urldecode($channel);
        $data = [];
        $data["s_lists"] = $this->Home_model->services_list();//table='solutions'
        $data["c_lists"] = $this->Home_model->cities_list(); //table='user_cities_all'
        $data["channels_lists"] = $this->Home_model->u_seo_channels_lists();//table='user_details_seo_channels'
        $data["days"] = $this->Home_model->days();//days
        $seo_lists = $this->filter_data($service_name,$city,$days,$channel);//table='user_details_seo'
        $data["seo_lists"] = $seo_lists["seo_lists"];
        $data["seo_filter"] = $seo_lists["seo_filter"];
        $this->load->view("home/sp_pages/all_sp",$data);
    }

    function counsellors($service_name = null,$city = null,$days = null,$channel = null){
        $service_name = urldecode($service_name);
        $city = urldecode($city);
        $days = urldecode($days);
        $channel = urldecode($channel);
        $data = [];
        $data["s_lists"] = $this->Home_model->services_list();//table='solutions'
        $data["c_lists"] = $this->Home_model->cities_list(); //table='user_cities_all'
        $data["channels_lists"] = $this->Home_model->u_seo_channels_lists();//table='user_details_seo_channels'
        $data["days"] = $this->Home_model->days();//days
        $seo_lists = $this->filter_data($service_name,$city,$days,$channel);//table='user_details_seo'
        $data["seo_lists"] = $seo_lists["seo_lists"];
        $data["seo_filter"] = $seo_lists["seo_filter"];

        //view
        $this->load->view("home/counsellors/header",$data);
        $this->load->view("home/counsellors/list",$data);
        $this->load->view("home/counsellors/footer",$data);
    }

    
    function filter_data($service_name,$city,$days,$channel){
        $seo_lists = $this->Home_model->user_seo_lists($service_name,$city,$days,$channel);        
        $_count = sizeof($seo_lists);
        $seo_filter = false;
        if($_count == 0){
            $seo_lists =   $this->Home_model->user_seo_lists("null","null","null","null");
            $_count = sizeof($seo_lists);
            $seo_filter = true;
        }
        
        for($i = 0; $i < $_count;$i++){
            $seo_lists[$i]["profile_photo"] = base_url($seo_lists[$i]["profile_photo"]);
            $seo_lists[$i]["locations"] = explode(",",$seo_lists[$i]["locations"]);
            $seo_lists[$i]["most_relevant_education"] = explode(",",$seo_lists[$i]["most_relevant_education"]);
            $seo_lists[$i]["top_skills"] = $this->Home_model->top_skills(explode(",",$seo_lists[$i]["top_skills"]));
        }

        
        return ["seo_lists" => $seo_lists,"seo_filter" => $seo_filter];
    }

    function work_ajax(){
        $json_msg = [];
        $form_data = [
            "appointment_type"=>$this->input->post("action_name"),
            "name"=>$this->input->post("name"),    
            "email"=>$this->input->post("email"),
            "phone_no"=>$this->input->post("phone_no"),    
            "location"=>$this->input->post("location"),
            "message"=>htmlspecialchars($this->input->post("message")),
            "user_id"=>$this->input->post("user_id"),
            "created_at"  => date('Y-m-d H:i:s'),
        ];

        $this->db->insert("user_book_appointment",$form_data);
        if($this->db->affected_rows() > 0){
            $json_msg["message_type"] = "book_succ";
            $json_msg["message"] = "Thanks";
        }


        $this->output->set_content_type('application/json')
     ->set_output(json_encode($json_msg));
    }    
}
?>