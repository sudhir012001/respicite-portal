<?php 
class Home_model extends CI_Model{
    
    function services_list(){
        $this->db->select("c_group");
        $this->db->distinct();
        $q = $this->db->get("solutions");
        return $q->result();
    }

    function cities_list(){
        $this->db->select("*");
        $this->db->order_by("name","asc");
        $q = $this->db->get("user_cities_all");
        return $q->result();
    }

    function days(){
        $this->db->select("*");
        $q = $this->db->get("days");
        return $q->result();        
    }

    function u_seo_channels_lists(){
        $this->db->select("*");
        $q = $this->db->get("user_details_seo_channels");
        return $q->result();
    }
    function top_skills($ids){
        $this->db->select("skill");
        $this->db->where_in("id",$ids);
        $q = $this->db->get("user_details_seo_top_skills");
        $output = [];
        foreach($q->result_array() as $v){
            $output[] = $v["skill"];
        }
        return $output;
    }
    
    function user_seo_lists($service_name,$city,$days,$channel){ 
        $this->db->where('a.status', 'active');       
        $this->db->select("b.fullname,b.email,b.mobile,b.profile_photo,a.*");

        $w_service_name = [];
        $w_city = [];
        $w_days = [];
        $w_channel = [];

        if($service_name != "null" ){            
            $w_service_name = ["a.services" => $service_name];
        } 
        if($city != "null" ){                
            $w_city = ["a.locations"=>$city];
        }
        if($days != "null" ){
            $w_days = ["a.available_days"=>$days];
        }
        if($channel != "null" ){
            $w_channel = ["a.channels"=>$channel];
        }
        
        $this->db->join("user_details b","b.id = a.user_id");
        $this->db->like(array_merge($w_service_name,$w_city,$w_days,$w_channel));
        $q = $this->db->get("user_details_seo a");
        return $q->result_array();            
       
    }
}
?>