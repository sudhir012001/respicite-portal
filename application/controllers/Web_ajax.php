<?php
header('Access-Control-Allow-Origin: https://respicite.com');
class Web_ajax extends CI_Controller{
    function __construct(){
        parent::__construct();
    }

    public function partner_counselors($action = null){
        $json_msg = [];
        switch($action){
            case "GET_DETAILS":
                $offset = $this->input->get("p_no");
                if(empty($offset)){
                    $offset = 0;
                }

                $limit = 3;
                $this->db->select("a.email,b.id,b.fullname,b.profile_photo,c.about_us,c.key_services,d.reseller_id");
                $this->db->distinct("a.email");           
                $this->db->limit($limit,$offset);
                $this->db->where(["a.exam_passed"=>"pass","a.web_status" =>1]);
                $this->db->join("user_details b","b.email = a.email","inner");
                $this->db->join("sp_profile_detail c","c.email = b.email","inner");
                $this->db->join("reseller_homepage d","d.r_email = b.email and d.status = 1","left");
                $q = $this->db->get("partner_counselor_status a")->result_array();
                if(!empty($q)){
                    $filter_data = [];
                    foreach($q as $v){
                        $filter_data[] = [
                            "id"            =>  $v['id'],
                            "email"         =>  $v['email'],
                            "fullname"      =>  $v['fullname'],
                            "profile_photo" =>  base_url($v['profile_photo']),
                            "about_us"      =>  word_limiter($v['about_us'],30),
                            "web_link"      =>  (empty($v["reseller_id"]))?null:"https://$v[reseller_id].respicite.com",
                            "key_services"  =>  $v['key_services']
                        ];
                    }
                    $json_msg["msg"] = "DATA_OK";
                    $json_msg["data"] = $filter_data;
                }else{
                    $json_msg["msg"] = "D_NOT_F";
                }
            break;
            case "FULL_DETAILS":
                $user_id = base64_decode(urldecode($this->input->get("uid",true)));
                $this->db->select("a.email,b.id,b.fullname,b.profile_photo,c.about_us,c.key_services,
                c.contact,c.address,c.fb_url,c.twitter_url,c.insta_url,c.linkedin_url,
                c.heading1,c.content1,c.heading2,c.content2,c.heading3,c.heading3,d.reseller_id");
                $this->db->distinct("a.email");
                $this->db->where(["a.exam_passed"=>"pass","a.web_status" =>1,"b.id"=>$user_id]);
                $this->db->join("user_details b","b.email = a.email","inner");
                $this->db->join("sp_profile_detail c","c.email = b.email","inner");
                $this->db->join("reseller_homepage d","d.r_email = b.email and d.status = 1","left");
                $q = $this->db->get("partner_counselor_status a")->row_array();
                if(!empty($q)){
                      
                    $filter_data = [
                        "id"            =>  $q['id'],
                        "email"         =>  $q['email'],
                        "fullname"      =>  $q['fullname'],
                        "profile_photo" =>  base_url($q['profile_photo']),
                        "about_us"      =>  $q['about_us'],
                        "key_services"  =>  $q['key_services'],
                        "contact"       =>  $q['contact'],
                        "address"       =>  (empty($q["address"]))?null:$q['address'],
                        "fb_url"        =>  (empty($q["fb_url"]))?null:$q['fb_url'],
                        "twitter_url"   =>  (empty($q["twitter_url"]))?null:$q['twitter_url'],
                        "insta_url"     =>  (empty($q["insta_url"]))?null:$q['insta_url'],
                        "linkedin_url"  =>  (empty($q["linkedin_url"]))?null:$q['linkedin_url'],
                        "heading1"      =>  (empty($q["heading1"]))?"":$q['heading1'],
                        "content1"      =>  (empty($q["content1"]))?"":$q['content1'],
                        "heading2"      =>  (empty($q["heading2"]))?"":$q['heading2'],
                        "content2"      =>  (empty($q["content2"]))?"":$q['content2'],
                        "heading3"      =>  (empty($q["heading3"]))?"":$q['heading3'],
                        "content3"      =>  (empty($q["content4"]))?"":$q['content4'],
                        "web_link"      =>  (empty($q["reseller_id"]))?null:"https://$q[reseller_id].respicite.com",
                    ];
                   
                    $json_msg["msg"] = "DATA_OK";
                    $json_msg["data"] = $filter_data;
                }else{
                    $json_msg["msg"] = "D_NOT_F";
                }
            break;
        }

        $this->output
        ->set_content_type("application/json")
        ->set_output(json_encode($json_msg));
    }

    public function skill_development($action = null){
        $json_msg = [];
        switch($action){
            case "GET_DETAILS":
                $offset = $this->input->get("p_no");
                if(empty($offset)){
                    $offset = 0;
                }

                $limit = 3;

                $this->db->select("a.id,a.sp_id,a.training_name,a.training_img,a.training_desc,a.certification_status,b.email,b.fullname,b.profile_photo");
                $this->db->distinct("a.email");           
                $this->db->limit($limit,$offset);
                $this->db->order_by("a.id","desc");
                $this->db->where(["a.training_status"=>"approved"]);
                $this->db->join("user_details b","b.id = a.sp_id","inner");
                $q = $this->db->get("vocational_training a");
                $q = $q->result_array();
                if(!empty($q)){
                    $filter_data = [];
                    foreach($q as $v){
                        $filter_data[] = [
                            "t_id"                        =>  $v['id'],
                            "sp_id"                     =>  $v['sp_id'],
                            "email"                     =>  $v['email'],
                            "fullname"                  =>  $v['fullname'],
                            "training_img"              => ($v['training_img'] != null)? base_url("uploads/vocational_training/$v[training_img]"): base_url("uploads/vocational_training/default-training-img.svg"), 
                            "training_name"             =>  word_limiter($v['training_name'],15),
                            "training_desc"             =>  word_limiter($v['training_desc'],60),
                            "certification_status"      =>  ($v['certification_status'] == "approved")?"approved":null,
                        ];
                    }
                    $json_msg["msg"] = "DATA_OK";
                    $json_msg["data"] = $filter_data;
                }else{
                    $json_msg["msg"] = "D_NOT_F";
                }
            break;
            case "FULL_DETAILS":
                $filter_data = [];
                $training_id = base64_decode(urldecode($this->input->get("tid",true)));
                $this->db->select("a.id,a.sp_id,a.training_name,a.training_img,a.training_desc,
                a.certification_status,b.user_id,b.fullname,b.profile_photo");
                $this->db->where(["a.training_status"=>"approved","a.id"=>$training_id]);
                $this->db->join("user_details b","b.id = a.sp_id","inner");
                $q = $this->db->get("vocational_training a");
                $q = $q->row_array();

                if(!empty($q)){
                    $filter_data["training"] = [
                        "t_id"                      =>  $q['id'],
                        "sp_id"                     =>  $q['sp_id'],
                        "user_id"                   =>  (!empty($q['user_id']))?$q['user_id']:"merak",
                        "fullname"                  =>  $q['fullname'],
                        "training_img"              => ($q['training_img'] != null)? base_url("uploads/vocational_training/$q[training_img]"): base_url("uploads/vocational_training/default-training-img.svg"), 
                        "training_name"             =>  $q['training_name'],
                        "training_desc"             =>  $q['training_desc'],
                        "certification_status"      =>  ($q['certification_status'] == "approved")?"approved":null,
                    ];

                    // vocational training sections #Start.
                             $this->db->select("section_name,section_desc");
                    $sub_q = $this->db->get_where("vocational_training_sections",["training_id"=>$q['id']]);
                    $sub_q = $sub_q->result_array();
                    if(!empty($sub_q)){
                        $filter_data["training_section"] = $sub_q;
                    }else{
                        $filter_data["training_section"] = null;
                    }
                    // vocational training sections #End.
                    $json_msg["msg"] = "DATA_OK";
                    $json_msg["data"] = $filter_data;
                }
            break;
            case "APPLY_NOW":
                $user_email = $this->input->post("form_email",true);
                $training_id = base64_decode(urldecode($this->input->post("tid",true)));
                if(!empty($user_email)){
                $q = $this->db->get_where("user_details",["email"=>$user_email]);
                    if($q->num_rows() > 0){
                                
                        $q_sub = $this->db->get_where("vocational_training_apply_user",["training_id"=>$training_id,
                                                                        "user_email"=>$user_email,
                                                                        "training_status"=>"approval_pending"]);

                        if($q_sub->num_rows() == 0){
                            $form_data = [
                                            "training_id" => $training_id,
                                            "user_email" => $user_email,
                                            ];
                            $this->db->insert("vocational_training_apply_user",$form_data);
                            if($this->db->affected_rows() > 0){
                                $json_msg["msg"] = "APPLIED";
                            }else{
                                $json_msg["msg"] = "NOT_APPLIED";
                            }
                            
                        }else{
                            $json_msg["msg"] = "approval_pending";
                        }
                    }else{
                        $json_msg["msg"] = "USER_NOT_EXIST";  
                    }
                }else{
                    $json_msg["msg"] = "EMAIL_EMPTY";
                }
            break;
        }

        $this->output
        ->set_content_type("application/json")
        ->set_output(json_encode($json_msg));
    }

    public function job_placements($action = null){
        $json_msg = [];
        switch($action){
            case "GET_DETAILS":
                $offset = $this->input->get("p_no");
                if(empty($offset)){
                    $offset = 0;
                }

                $limit = 3;
                $this->db->select("a.id,a.job_title,a.job_description,b.name as domain");
                $this->db->where(['a.status'=>'published']);
                $this->db->join("job_domain b","b.id = a.domain","left");
                $this->db->order_by("a.id","desc");
                $this->db->limit($limit,$offset);
                $q = $this->db->get("placement_jobs a");
                if($q->num_rows() > 0){
                    $json_msg["msg"] = "data_found";
                    $data_res = [];
                    foreach($q->result_array() as $k => $v){                        
                        $data_res[] = [
                            "id" => $v['id'],
                            "job_title" => $v['job_title'],
                            "job_description" => word_limiter($v['job_description'],45),
                            "domain" => $v['domain']
                        ];
                    }
                    $json_msg["data"] = $data_res;
                }else{
                    $json_msg["msg"] = "D_NOT_F";
                }
            break;
            case "FULL_DETAILS":
                $jid =  base64_decode(urldecode($this->input->get("jid",true)));
                $this->db->select("a.id,a.job_title,a.job_description,
                a.salary,a.job_post_date,a.specialization,a.job_type,a.job_locations,
                a.posting_nature,a.job_nature,b.name as domain");
                $this->db->where(['a.status'=>'published','a.id'=>$jid]);
                $this->db->join("job_domain b","b.id = a.domain","left");
                $this->db->order_by("a.id","desc");
                $q = $this->db->get("placement_jobs a");
                if($q->num_rows() > 0){
                    $data = $q->row();
                    $json_msg["msg"] = "data_found";
                    $json_msg["data"] = $data;

                    if(!empty($data->specialization)){
                        $where_in = explode(",",$data->specialization);
                        $this->db->select("id,name");
                        $this->db->where_in("id",$where_in);
                        $q_sub = $this->db->get("job_specialization");
                        if($q_sub->num_rows() > 0){
                            $json_msg["specialization"] = $q_sub->result();
                        }else{
                            $json_msg["specialization"] = null;
                        }
                    }
                }else{
                    $json_msg["msg"] = "D_NOT_F";
                }
            break;
            case "APPLY_NOW":
                $user_email = $this->input->post("form_email",true);
                $job_id = urldecode(base64_decode($this->input->post("jid",true)));
                if(!empty($user_email)){
                $q = $this->db->get_where("user_details",["email"=>$user_email]);
                    if($q->num_rows() > 0){

                        $q_sub = $this->db->get_where("jobs_apply_user",["job_id"=>$job_id,
                                                                        "user_email"=>$user_email,
                                                                        "job_status"=>"apply"]);
                        
                        if($q_sub->num_rows() == 0){
                            $json_msg["msg"] = "apply_now";
                        }else{
                            $json_msg["msg"] = "job_status";
                            $json_msg["data"] = $q_sub->row()->job_status;
                        }
                    }else{
                        $json_msg["msg"] = "USER_NOT_EXIST";  
                    }
                }else{
                    $json_msg["msg"] = "EMAIL_EMPTY";
                }
            break;
        }

        $this->output
        ->set_content_type("application/json")
        ->set_output(json_encode($json_msg));
    }

    public function common($action = null){
        $json_msg = [];
        switch($action){
            case "TAKE_ASSESMENTS":
                $user_email = urldecode(base64_decode($this->input->get("e",true)));
                if(!empty($user_email)){
                    $q = $this->db->get_where("user_details",["email"=>$user_email]);
                    if($q->num_rows() > 0){
                        $json_msg["msg"] = "LOGIN";
                    }else{
                        $json_msg["msg"] = "REGISTRATION";
                    }
                }else{
                    $json_msg["msg"] = "EMAIL_EMPTY";
                }
            break;
        }
        $this->output
        ->set_content_type("application/json")
        ->set_output(json_encode($json_msg));
    }
}
?>