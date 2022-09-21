<?php
    class Api_model extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function get_items($id=null)
        {
            if(!empty($id)){$this->db->where('id',$id);}
            return $this->db->get('test_rest_api')->result();
        }

    }
?>
