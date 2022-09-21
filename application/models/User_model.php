<?php
    class User_model extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
        }

        //insert registration form data in this part
        public function create($formArray){
            $this->db->insert('user_details',$formArray);
        }

        //checking email address
        public function checkUser($email){
            $this->db->where('email',$email);
            return $row = $this->db->get('user_details')->row_array();
        }
        public function landingId($id){
            $this->db->select('landing_id');
            $this->db->where('id',$id);
            return $row = $this->db->get('user_details')->row_array();
        }
        //session checking
        function authorized(){
            $user = $this->session->userdata('user');
            if(!empty($user))
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        //send otp in email
        function send_otp_in_email($email)
        {
            
            $otp = rand(100001,999999);
            
            $this->load->library('email');

            // $this->email->from('admin@cgcareers.online', 'CG Careers');
            $this->email->from('sales@respicite.com', 'respicite.com');
            $this->email->to($email);
            // $this->email->cc('another@another-example.com');
            // $this->email->bcc('them@their-example.com');

            $this->email->subject('OTP');
            $this->email->message('OTP is '.$otp);

            $this->email->send();
            $this->load->helper('cookie');
            set_cookie('today_get_otp',$otp, time() + (60 * 5)); 
            set_cookie('my_email_today',$email, time() + (60 * 5)); 
        }
        
        //update status function in this saction
        function update_status($email)
        {
            $this->db->set('status','1');
            $this->db->where('email',$email);
            $this->db->update('user_details');

        }

        //insert request
        function insert_request_code($data)
        {
            $this->db->insert('purchase_reseller_code_record',$data);
        }
        public function get_unused_code($email)
        {
            $this->db->where('email',$email);
            return $row = $this->db->get('generated_code_details');
        }

        public function solutions_list(){
            return $solutions = $this->db->get('solutions');
        }
        public function spResellers(){
            $sql="SELECT * FROM  user_details  WHERE iam IN('sp','reseller')";    
            $query = $this->db->query($sql);
            return $query->result_array();

        }
        public function fetch_purchase_code($solution)
        {
            $where = "status='active' and solution='$solution'";
            $this->db->where($where);
            return $row = $this->db->get('reseller_code_record');
        }

        public function get_homepage_data($email)
        {
            $this->db->where('r_email',$email);
            return $row = $this->db->get('reseller_homepage');
        }
        public function homepage_insertion($formArray){
            $this->db->insert('reseller_homepage',$formArray);
        }
        public function check_duplicacy($domain)
        {
            $this->db->where('reseller_id',$domain);
            return $row = $this->db->get('reseller_homepage')->num_rows();
        }
        public function code_list($email)
        {
            $where = "reseller_id='$email' and status!='pending'";
            $this->db->where($where);
           return $row = $this->db->get('user_code_list');
        }
        public function certification_list($email)
        {
            $where = "email='$email' and c_status!='0'";
            $this->db->where($where);
           return $row = $this->db->get('reseller_certification');
        }
        public function ppe_test($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('certification_test_ppe');
        }

        public function check_email_id($email){
            $this->db->select("id");
            $this->db->where('email',$email);
            $row = $this->db->get('user_details');
            return ["num_rows"=>$row->num_rows(),"db_data"=>$row->row()];
        }

        public function otp_update($id,$otp){
            $this->db->set("email_otp",$otp);
            $this->db->where('id',$id);
            $this->db->limit(1);
            $this->db->update('user_details');
            return $this->db->affected_rows();  
            // return $this->db->last_query();
        }
        public function otp_update_by_email($email,$otp){
            $this->db->set("email_otp",$otp);
            $this->db->where('email',$email);
            $this->db->limit(1);
            $this->db->update('user_details');
            return $this->db->affected_rows();  
            // return $this->db->last_query();
        }

        public function check_otp_email($data){
            $this->db->select("id");
            $this->db->where(['email'=>$data['email_id'],"email_otp"=>$data["otp_code"]]);            
            $user_id = $this->db->get('user_details');
            $db_res = "";
            if($user_id->num_rows() > 0){           
                $check_update = $this->otp_update($user_id->row()->id,null);
                if($check_update > 0){
                    $this->db->set("pwd",$data['new_pass']);
                    $this->db->where('id',$user_id->row()->id);
                    $this->db->limit(1);
                    $this->db->update('user_details');
                    if($this->db->affected_rows() > 0){
                        $db_res = true;
                    }
                }
            }else{
                $db_res = false;
            }            
            return $db_res;
        }

        public function check_otp_email_reverify($email,$otp){
            $this->db->select("id");
            $this->db->where(['email'=>$email,"email_otp"=>$otp]);
            $user_id = $this->db->get('user_details');
            $db_res = "";
            if($user_id->num_rows() > 0){           
                $this->otp_update($user_id->row()->id,null);
                $this->update_status($email);
                $db_res = true;
            }else{
                $db_res = false;
            }            
            return $db_res;
        }
        
        public function check_user_status($email){
            $this->db->select("id");
            $this->db->where(['email'=>$email,"status"=>"0"]);
            $user_id = $this->db->get('user_details');
            // echo $this->db->last_query();
            if(!empty($user_id->row()->id)){
               $id = $user_id->row()->id;
            }else{
                $id = null;
            }
            return ["num_rows"=>$user_id->num_rows(),"id"=>$id];
        }

        function otp_send_on_email($email,$subject,$msg)
        {
            $this->load->library('email');
            // $this->email->from('admin@cgcareers.online', 'CG Careers');
            $this->email->from('sales@respicite.com', 'respicite.com');
            $this->email->to($email);
            // $this->email->cc('another@another-example.com');
            // $this->email->bcc('them@their-example.com');
            $this->email->subject($subject);
            $this->email->message($msg);
            $this->email->set_mailtype("html");
            $this->email->send();
        }

        function jobs_info($check,$id = null){            
            if($check == "POST_JOB_LIST"){
                if($id != null){
                    $order_by = "ORDER BY a.id = $id desc";
                }else{
                    $order_by = "ORDER BY a.id desc";
                }
                $q = $this->db->query("SELECT a.id,a.job_title,a.salary,b.name as domain 
                FROM placement_jobs a
                LEFT JOIN job_domain b ON b.id = a.domain
                $order_by");
                return $q->result();
            }

            if($check == "APPLY_JOB_LIST"){
                $user = $this->session->userdata('user');
                $user = $user['email'];
                if($id != null){
                    $order_by = "ORDER BY b.id = $id desc";
                }else{
                    $order_by = "ORDER BY a.id DESC";
                }
                $q = $this->db->query("SELECT b.id,b.job_title,b.salary,c.name as domain, a.job_status
                FROM jobs_apply_user a
                INNER JOIN placement_jobs b ON b.id = a.job_id
                LEFT JOIN job_domain c ON c.id = b.domain
                WHERE a.user_email = '$user'
                $order_by");
                return $q->result();
            }
        }

        function purchase_code_history_list($email_id){
            $this->db->select("id,user_id,code,solution,status,payment_mode,payment_status");
            $this->db->order_by("id","desc");
            $this->db->where(['user_id'=>$email_id]);
            $q = $this->db->get("user_code_list");
            return $q->result();
        }

        public function get_user_details($action = null,$id = null){
            switch($action){
                case "DETAILS_BY_EMAIL":
                    $this->db->select("id,fullname,mobile,email");
                    $this->db->where("email",$id);
                    $q = $this->db->get("user_details");
                    // echo $this->db->last_query();
                    return $q->row_array();
                break;
                case "SOLUTION_BY_NAME":
                    $this->db->select("id,solution,display_solution_name,mr_price,mrp");
                    $this->db->where("id",$id);
                    $q = $this->db->get("solutions");
                    // echo $this->db->last_query();
                    return $q->row_array();
                break;
            }
        }
        public function get_landing_section_data($id,$resellerId)
        {
        //   $this->db->select('lps.*, lpsec.name');
        //     $this->db->from('landing_page_settings as lps');
        //     $this->db->join('landing_page_section as lpsec', 'lpsec.landingId=lps.landingPageId', 'inner');
        //     $this->db->where("lps.resellerId",$resellerId);
        //     $this->db->where("lps.landingPageId",$id);
        //     $query = $this->db->get();
        //     return $query->result_array();
        
        // $this->db->select('ls.name as sectionName, lp.*');
        // $this->db->from('landing_page_settings as lp');
        //  $this->db->where("lp.resellerId",$resellerId);
        // $this->db->where("lp.landingPageId",$id);
        // $this->db->join('landing_page_section as ls', ' ls.id = lp.sectionId');
        //$query = $this->db->query('SELECT landing_page_settings.*,landing_page_section.name as sectionName FROM landing_page_settings INNER JOIN landing_page_section ON landing_page_section.id = landing_page_settings.sectionId WHERE landing_page_settings.resellerId = "$resellerId" AND landing_page_settings.landingPageId = ".$id."');
        //echo $query;die;
        //$query =$this->db->get();
        // $this->db->last_query();die;
            //echo $resellerId;die;
            $this->db->select("lps.*,lp.name as SectionName");
            //$this->db->where('landingPageId',$id);
            $this->db->from("landing_page_settings as lps");
            $this->db->join("landing_page_section as lp","lp.id=lps.sectionId");
           $this->db->where("lps.resellerId",$resellerId);
           $this->db->where("lps.landingPageId",$id);
            $query = $this->db->get();
            return $query->result_array();
            // $this->db->select("*");
            // $this->db->where("resellerId",$resellerId);
            // $this->db->where("landingPageId",$id);
            // $this->db->from("landing_page_settings");
            // $query = $this->db->get();
            // return $query->result_array();
        }
        public function getCounselingPara($resellerId){
            $this->db->select("*");
            $this->db->where("resellerId",$resellerId);
            $this->db->from("counseling_parameters");
            $query = $this->db->get();
            return $query->result_array();
        }
        public function get_template_data()
        {
            $this->db->select("*");
            $this->db->from("landing_page_full_details");
            $query = $this->db->get();
            return $query->result_array();
        }
        public function get_landing_section_via_data($id)
        {
            $this->db->select("*");
            $this->db->where("landingId",$id);
            $this->db->from("landing_page_section");
            $query = $this->db->get();
            return $query->result_array();
        }
        public function get_landing_details_section_via_data($id)
        {
            $this->db->select("*");
            $this->db->where("landingPageId",$id);
            $this->db->from("landing_page_details");
            $query = $this->db->get();
            return $query->result_array();
        }
        public function insert_landing_section_data($data)
        {
            $tbl = "landing_page_settings";
            //insert entries
            $this->db->insert($tbl,$data);
            if($this->db->affected_rows() > 0){return true;}
            else{return false;}
                
        }
        public function insert_counseling_para_data($data)
        {
            $tbl = "counseling_parameters";
            //insert entries
            $this->db->insert($tbl,$data);
            if($this->db->affected_rows() > 0){return true;}
            else{return false;}
                
        }
        public function insert_landing_section_full_data($data)
        {
            $tbl = "landing_page_full_details";
            //insert entries
            $this->db->insert($tbl,$data);
            if($this->db->affected_rows() > 0){return true;}
            else{return false;}
                
        }
        public function landingSectionDelete($id)
        {
            $this->db->where('id',$id);
            $result = $this->db->delete('landing_page_settings');
            return $result;
        }
        public function counselingDelete($id)
        {
            $this->db->where('id',$id);
            $result = $this->db->delete('counseling_parameters');
            return $result;
        }
        public function sectionViaPArameter($id){
            $this->db->select("*");
            $this->db->where("section_id",$id);
            $this->db->from("landing_page_details");
            $query = $this->db->get();
            return $query->result_array();
        }
        public function counselingUpdate($id,$para_name,$counseling_type,$mrp,$duration){
            $this->db->set('name',$para_name);
            $this->db->set('type',$counseling_type);
            $this->db->set('mrp',$mrp);
            $this->db->set('duration',$duration);
            $this->db->where('id',$id);
            if ($this->db->update('counseling_parameters')) {  
             return true; 
            }else{
             return false;
            }
        }
        public function book_link_update($id,$allow){
            $this->db->set('calender_link',$allow);
            $this->db->where('id',$id);
            if ($this->db->update('user_details')) {  
             return true; 
            }else{
             return false;
            }
        }
        public function counseling_type_update($id,$allow){
            $this->db->set('counseling_type',$allow);
            $this->db->where('id',$id);
            if ($this->db->update('user_details')) {  
             return true; 
            }else{
             return false;
            }
        }
        public function getcalenderlinkById($id){
            //echo $id;die;
            $this->db->select('calender_link');
            $this->db->from('user_details');
            $this->db->where('id',$id);
            $row = $this->db->get()->row();
            if (isset($row)) {
                return $row->calender_link;
            } else {
                return false;
            }
            
        }
        public function getcounselingTypeById($id){
            //echo $id;die;
            $this->db->select('counseling_type');
            $this->db->from('user_details');
            $this->db->where('id',$id);
            $row = $this->db->get()->row();
            if (isset($row)) {
                return $row->counseling_type;
            } else {
                return false;
            }
            
        }
        public function getCounselingParaDetails($id){
            $this->db->select('*');
            $this->db->from('counseling_parameters');
            $this->db->where('id',$id);
            $row = $this->db->get()->row();
            if (isset($row)) {
                return $row;
            } else {
                return false;
            }
        }
        
    }
?>