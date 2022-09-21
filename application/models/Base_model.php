<?php
    class Base_model extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
        }
        public function code_list($email)
        {
            $where = "user_id='$email' and status!='pending'";
            $this->db->where($where);
            return $row = $this->db->get('user_code_list');
        }
        public function ppe_part1($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('ps_details');
        }
        public function update_code_status($code)
        {
            $user = $this->session->userdata('user');
            $email = $user['email'];
            $where="user_id='$email' and code='$code'";
            $this->db->where($where);
            $this->db->set('status','Rp');
            $this->db->update('user_code_list');

        }
        public function solution_list($email,$solution,$code)
        {
            $where = "user_id='$email' and solution='$solution' and code='$code'";
            $this->db->where($where);
            return $solutions = $this->db->get('user_assessment_info');
        }
        public function ppe_part3($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('sha_details');
        }
        public function ppe_part4($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('sdq_detail');
        }
        public function wla_part1($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('wla_part1_details');
        }
        public function wla_part2($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('wla_part2_details');
        }
        public function wls_part1_1($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('wls_part1_1');
        }
        public function wls_part1_2($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('wls_part1_2');
        }
        public function wls_part2_2($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('wls_part2_2_detail');
        }
        public function wls_part2_3($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('wls_part2_3_details');
        }
        public function wpa_part1($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('wpa_part1');
        }
        public function wpa_part2($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(3)->get('wpa_part2');
        }
        public function count_option($qno)
        {
            $where = "qno>=$qno";
            $this->db->where($where);
            return $this->db->get('ppe_part1_test_details');
        }
        public function sdp1_part3($num)
        {
            $where = "qno>='$num' and mwp='MWP-1'";
            $this->db->where($where);
            return $this->db->limit(3)->get('wpa_part2');
        }
        public function sdp2_part2($num)
        {
            $where = "qno>='$num' and mwp='MWP-2'";
            $this->db->where($where);
            return $this->db->limit(3)->get('wpa_part2');
        }
        public function sdp3_part2($num)
        {
            $where = "qno>='$num' and mwp='MWP-3'";
            $this->db->where($where);
            return $this->db->limit(3)->get('wpa_part2');
        }
        public function jrap_part3($num)
        {
            $where = "qno>='$num' and mwp!='MWP-4'";
            $this->db->where($where);
            return $this->db->limit(3)->get('wpa_part2');
        }
        public function uce_part1_1($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part1');
        }
        
        public function get_questions($num,$tbl)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get($tbl);
        }
        
        public function uce_part1_2($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part1_2');
        }
        public function uce_part1_3($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part1_3');
        }
        public function uce_part1_4_1($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part1_4');
        }
        public function uce_part1_4_2($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part1_4_2');
        }
        public function uce_part1_5($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part1_5');
        }
        public function uce_part2($num)
        {
            $where = "qno>=$num and part='part1'";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part2');
        }
        public function uce_part2_2($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part2_2');
        }
        public function uce_part2_3($num)
        {
            $where = "qno>=$num and part='part3'";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part2');
        }
        public function uce_part2_4($num)
        {
            $where = "qno>=$num and part='part4'";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part2');
        }
        public function uce_part2_5($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part5');
        }
        public function uce_part2_6($num)
        {
            $where = "qno>=$num and part='part6'";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part2');
        }
        public function uce_part2_7($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('uce_part2_7');
        }
        public function qce_part2($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('qce_part1_question');
        }

        public function cat_part1($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('cat_part1');
        }

        public function cat_part2($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(1)->get('cat_part2_correct_ans');
        }
        
        
        //Added by Sudhir
        public function ce_disha_part_1($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('ce_nextmove_que');
        }
        public function cb_ecp_part_3($num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get('cb_ecp_questions');
        }
        
        
        public function fetch_que($tbl, $num)
        {
            $where = "qno>=$num";
            $this->db->where($where);
            return $this->db->limit(5)->get($tbl);
        }
        
        public function balance_ques($tbl, $n)
        {
            $where = "qno>=$n";
            $this->db->where($where);
            return $this->db->limit(5)->get($tbl)->num_rows();
        }

        public function get_mot_menu($menu_name)
        {
            $this->db->where('role','user');
            return $this->db->get($menu_name)->result();
        }
         public function getUserDetailsByIds($id){
            //echo $id;die;
            $this->db->select('allowed_services');
            $this->db->from('user_details');
            $this->db->where('user_id',$id);
            $this->db->where_in('iam',array('reseller','sp'));
            $row = $this->db->get()->row();
            if (isset($row)) {
                return $row->allowed_services;
            } else {
                return false;
            }
            
        }
         public function getUserConsLink($id){
            //echo $id;die;
            $this->db->select('calender_link');
            $this->db->from('user_details');
            $this->db->where('user_id',$id);
            $this->db->where_in('iam',array('reseller','sp'));
            $row =$this->db->get()->row();
           //echo  $this->db->last_query();die;
            if (isset($row)) {
                return $row->calender_link;
            } else {
                return false;
            }
            
        }
        public function getresellerid($id){
            //echo $id;die;
            $this->db->select('id');
            $this->db->from('user_details');
            $this->db->where('user_id',$id);
            $this->db->where_in('iam',array('reseller','sp'));
            $row = $this->db->get()->row();
            if (isset($row)) {
                return $row->id;
            } else {
                return false;
            }
            
        }
        public function getresellerCrd(){
            //echo $id;die;
            $this->db->select('*');
            $this->db->from('paymentcrd');
            $this->db->where('crd_status',1);
            $row = $this->db->get()->row();
            if (isset($row)) {
                return $row;
            } else {
                return false;
            }
            
        }
        public function getpayment_name()
        {
            $this->db->select("payCrd.*,pd.*");
            //$this->db->where('landingPageId',$id);
            $this->db->from("paymentcrd as payCrd");
            $this->db->join("payment_gatway_details as pd","pd.id=payCrd.payment_type");
            //$this->db->where('payCrd.user_id',$id);
            $this->db->where('payCrd.crd_status',1);
            $query = $this->db->get();
            return $query->result_array();
        }
        //End of Added by Sudhir
    }
?>
