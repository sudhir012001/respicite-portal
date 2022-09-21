<?php
    require APPPATH . 'libraries/REST_Controller.php';
    class Marketing extends REST_Controller 
    {
        /**
         * * Get All Data from this method.
         * @return Response
        */

        public function __construct() 
        {
            parent::__construct();
            $this->load->model('Marketing_Model');
        }
        
          
        /**
            * Get All Data from this method.
            * @return Response
        */
        public function index_get()
        {
            // if(!empty($id)){$data = $this->db->get_where("items", ['id' => $id])->row_array();}
            // else{$data = $this->db->get("items")->result();}
            $id = $this->input->get('id', TRUE);
            $data = $this->Marketing_Model->getPerformanceData($id);
            $this->response($data, REST_Controller::HTTP_OK);

        }
            
         
        /**
            * Get All Data from this method.
            * @return Response
        */
        public function index_post()
        {
            $input = $this->input->post();
            $this->db->insert('items',$input);
            $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
        } 
            
        
        /**
            * Get All Data from this method.
            *
            * @return Response
        */
        public function index_put($id)
        {
            $input = $this->put();
            $this->db->update('items', $input, array('id'=>$id));
            $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
        }
            
        
        /**
            * Get All Data from this method.
            *
            * @return Response
        */
        public function index_delete($id)
        {
            $this->db->delete('items', array('id'=>$id));
            $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
        }
            
           
   }