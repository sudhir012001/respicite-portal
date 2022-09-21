 <?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Commen_model extends CI_Model
{
    private $query = null;
    public function __construct()
    {
        parent::__construct();
    }

    function wla_ppe_data($code,$solution){
        $this->db->select("a.id as wla_id,a.qno as wla_qno,
        a.category,a.nature,b.qno as ppe_qno, b.solution,b.code,b.ans");
        $this->db->where(['b.code'=>$code,'b.solution'=>$solution]);
        $this->db->join("ppe_part1_test_details b","b.qno = a.qno","left");
        $this->query = $this->db->get("wla_part1_details a");
        return ["num_rows" => $this->query->num_rows(),
        "rows_data"=>$this->query->result()];
    }
    function wpa_ppe_data($code,$solution){
        $this->db->select("a.id as wpa_id,a.qno as wpa_qno,
        a.nature,a.right_ans,b.qno as ppe_qno, b.solution,b.code,b.ans");
        $this->db->where(['b.code'=>$code,'b.solution'=>$solution]);
        $this->db->join("ppe_part1_test_details b","b.qno = a.qno","left");
        $this->query = $this->db->get("wpa_part1 a");
        return ["num_rows" => $this->query->num_rows(),
        "rows_data"=>$this->query->result()];
    }
    function reseller_data_from_code($code){
        $this->db->select("a.reseller_id,a.gender,b.logo");
        $this->db->where(['a.code' => $code]);
        $this->db->join("reseller_homepage b","b.r_email = a.reseller_id","left");
        $this->query = $this->db->get("user_code_list a");
        // echo $this->db->last_query();
        return ["num_rows" => $this->query->num_rows(),
        "rows_data"=>$this->query->row()];
    }

    function ppe_table($code,$solution){
        $this->db->select("*");
        $this->db->where(["code"=>$code,"solution"=>$solution]);
        $this->query = $this->db->get("ppe_part1_test_details");
        return ["num_rows" => $this->query->num_rows(),
        "rows_data"=>$this->query->result()];

    }

    function ecp_score($code,$solution){
        $this->db->select("b.qno as ecp_qno, a.qno as ppe_qno,
         b.set_name,b.cat,b.sub_cat,c.assessment,c.options,
         c.value,d.category,d.score,d.response,d.level");
         $this->db->where(["a.code" => $code,"a.solution" => $solution]);
         $this->db->join("cb_ecp_questions  b","b.qno = a.qno","left");
         $this->db->join("assessment_options c","c.assessment = b.set_name AND c.value = a.ans","left");
         $this->db->join("cb_ecp_score_item d","d.category = b.cat AND d.response = c.options","left");
         $this->db->order_by("b.qno", "ASC");
         $this->query = $this->db->get("ppe_part1_test_details a");
         //echo  $this->db->last_query();
         return ["num_rows" => $this->query->num_rows(),
        "rows_data"=>$this->query->result()];
     }

     function ecp_reco($sub_cat,$level){       
        $this->db->select("sub_category,level,recommendation");
        $this->db->where(["sub_category"=>$sub_cat,"level"=>$level]);
        $this->query = $this->db->get("cb_ecp_reco");
      // echo  $this->db->last_query();
        return ["num_rows" => $this->query->num_rows(),
        "rows_data"=>$this->query->result()];
     }

     function disha_pref_id_arr(){        
        $this->db->select("sub_cat,cat");
        $this->db->distinct('sub_cat');
        $this->db->distinct('cat');
        $this->db->order_by("cat,sub_cat", "DESC");
        $this->query = $this->db->get("ce_pref_que_id");        
        // echo  $this->db->last_query();
        return $this->query->result_array();
     }

     function disha_pref_id_count($code,$solution,$opt_name,$opt_value){
        $this->db->select("a.qno as pp_qno, b.qno 
        as pre_que_qno,b.cat,b.sub_cat,
        c.options,c.value,b.$opt_name,d.cat as pref_cat ,d.sub_cat as pref_sub_cat");
         $this->db->where(["a.code" => $code,"a.solution" => $solution,"c.value"=>$opt_value]);
         $this->db->join("ce_nextmove_pref_que  b","b.qno = a.qno","left");
         $this->db->join("assessment_options c","c.assessment = b.set_name AND c.value = a.ans","left");
         $this->db->join("ce_pref_que_id d","d.item = b.$opt_name","left");
         $this->db->order_by("b.qno", "ASC");
         $this->query = $this->db->get("ppe_part1_test_details a");
        //  echo  $this->db->last_query();
         return $this->query->result_array();
    }

     function disha_cap_count($code,$solution){
          // $this->db->select("a.qno as pp_qno, b.qno as pre_que_qno,b.cat,b.sub_cat,c.options,c.value");
        $this->db->select("b.cat,c.value");
        $this->db->where(["a.code" => $code,"a.solution" => $solution]);
        $this->db->join("ce_nextmove_que  b","b.qno = a.qno","left");
        $this->db->join("assessment_options c","c.assessment = b.set_name AND c.value = a.ans","left");
        $this->db->order_by("b.qno", "ASC");
        $this->query =$this->db->get("ppe_part1_test_details a");
        //  echo  $this->db->last_query();
         return $this->query->result_array();
    }

   
     function priority_check($priority){
         $this->db->select("priority");
        $this->db->distinct('priority');
        $this->db->where(["priority"=>$priority]);
        $this->query = $this->db->get("ce_nextmove_inf");
        // echo $this->db->last_query();
        return $this->query->result_array();
    }

     function priority_heading_check($priority){
        $this->db->select("heading");
        $this->db->distinct('heading');
        $this->db->where(["priority"=>$priority]);
        $this->query = $this->db->get("ce_nextmove_inf");
        // echo $this->db->last_query();
        return $this->query->row();
    }
     function priority_details_check($priority = "Job"){        
        $this->db->select("parameter,details");
        $this->db->where(["priority"=>$priority]);
        $this->query = $this->db->get("ce_nextmove_inf");
        // echo $this->db->last_query();
        return $this->query->result_array();
    }

    function reseller_services($email_id){
        $this->db->select("c_group");
        $this->db->distinct();
        $this->db->where("email",$email_id);
        $q = $this->db->get("reseller_certification");
        return $q->result_array();
    }

    function seo_feildes(){
        $output = [];
        $output["channels"] = $this->db->select("*")->get("user_details_seo_channels")->result();
        $output["local"] = $this->db->select("*")->get("user_cities_all")->result();
        $output["days"] = $this->db->select("id,day_name")->get("days")->result();
        $output["top_skills"] = $this->db->select("*")->get("user_details_seo_top_skills")->result();
        $output["star_rating"] = $this->db->select("id,rating")->get("user_details_seo_all_stars")->result();
        return $output;
    }


    /******************************** 
    MarketPlace Models
    **********************************/
    public function get_marketplace_menu($role, $status=null){

        $this->db->select('*');
        $where = "parent_id='0'";
        $this->db->where($where);
        $this->db->where('role', $role);
        if($status != null){$this->db->where('status',$status);}
        $q = $this->db->get('services_marketplace_menu');
        return $q->result();
        
    }

     public function get_marketplace_submenu($role, $status=null){

        $this->db->select('*');
        $where = "parent_id!='0'";
        $this->db->where($where);
        $this->db->where('role', $role);
        if($status != null){$this->db->where('status',$status);}
        $q = $this->db->get('services_marketplace_menu');
        return $q->result();
        
    }

    public function get_marketplace_field($role, $menu_name, $submenu_name, $field_name)
    {

        //table name
        $tbl = 'services_marketplace_menu';

        //Get Menu id = submenu parent id
        $this->db->select('id');
        $this->db->where(['role'=>$role, 'name'=>$menu_name]);
        $q = $this->db->get($tbl);
        $parent = $q->row_array();
        if($parent == null){return false;}
        else
        {
            $this->db->select($field_name);
            $this->db->where(['parent_id'=>$parent['id'], 'name'=>$submenu_name]);
            $q = $this->db->get($tbl);
            if($q->result_array() == null){return false;}
            else {return $q->row_array();}
        }
    }

    public function update_marketplace_field($role, $menu_name, $submenu_name, $field_name, $parameter)
    {

        //entry msg
        // echo ('entered the model<br>');
        // echo $role."<br>";
        // echo $menu_name."<br>";
        // echo $submenu_name."<br>";
        // echo $field_name."<br>";
        // echo $parameter."<br>";
        // die;

        //table name
        $tbl = 'services_marketplace_menu';

        //Get Menu id = submenu parent id
        $this->db->select('id');
        $this->db->where(['role'=>$role, 'name'=>$menu_name]);
        $q = $this->db->get($tbl);
        $parent = $q->row_array();
        if($parent == null){return false;}
        else
        {
            $this->db->select($field_name);
            $this->db->where(['parent_id'=>$parent['id'], 'name'=>$submenu_name]);
            $q = $this->db->get($tbl);
            if($q->result_array() == null){return false;}
            else 
            {
                $a = ($q->row_array())[$field_name];
                $a = explode(",",$a);
                if(!in_array($parameter,$a))
                {
                    // echo "I am Here<br>";die;
                    $a[]=$parameter;
                    $a = implode(",",$a);
                    $update_arr = ['parameter'=> $a];
                    // print_r($update_arr);die;
                    $this->db->where(['parent_id'=>$parent['id'], 'name'=>$submenu_name]);
                    $this->db->update($tbl, $update_arr);
                    return true;

                }
            }
        }
    }

    public function get_marketplace_metadata($name)
    {
         //table name
         $tbl = 'services_marketplace_metadata';

         //Get Menu id = submenu parent id
         $this->db->select('*');
         $this->db->where(['name'=>$name]);
         $q = $this->db->get($tbl);
         return ($q->result_array()) ;

    }

    public function get_mot_menu($role, $menu_name)
    {
        $this->db->where('role',$role);
        return $this->db->get($menu_name)->result();
    }   

    public function get_reseller_sp($id)
    {
        $tbl = 'user_details';
        // echo $id;die;
        $this->db->where('user_id',$id);
        $this->db->where_in('iam',['reseller','sp']);
        return $this->db->get($tbl)->result_array()[0];
    }

    public function getResellerBookingLink($reseller_id)
    {
        $tbl = 'user_details';
        // echo $id;die;
        $this->db->select('calender_link');
        $this->db->where('id',$reseller_id);
        $this->db->where_in('iam',['reseller','sp']);
        return $this->db->get($tbl)->row_array()['calender_link'];
    }
}
        
        
            
       
        
        

?>