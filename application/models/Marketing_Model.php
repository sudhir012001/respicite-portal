<?php
    class Marketing_Model extends CI_Model
    {
        function __construct()  
        {  
         // Call the Model constructor  
            parent::__construct();  
        } 


        public function getPerformanceData($entry_id=null)
        {
            $tbl = 'mot_performancedata';
            if($entry_id){$this->db->where('entry_id', $entry_id);}
            $q = $this->db->get($tbl);
            return $q->result_array();
        }
    }

?>
