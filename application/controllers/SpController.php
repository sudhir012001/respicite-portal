<?php
class SpController extends CI_Controller
{
    public function initializer()
    {
        $this->load->model('User_model');
        $this->load->model('Admin_model');
        $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            
            $data['user'] = $user;
            
            $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
            $data['reseller_sp'] = $this->Commen_model->get_reseller_sp($data['user']['user_id']);

            $this->db->select('iam');
            $this->db->where('id', $user['id']);
            $qry = $this->db->get('user_details');
            $data['user']['iam'] = $qry->row_array()['iam'];
            $landing = $this->User_model->landingId($user['id']);
            $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            $data['mot_menu'] = $this->Commen_model->get_mot_menu('sp','mot_sidebar');
            $data['mot_navbar'] = $this->Commen_model->get_mot_menu('sp','mot_navbar');
            $data['booking_url'] = 'https://calendly.com/';
            return $data;
    }
    
    public function dashboard()
    {
        
        // $this->load->model('User_model');
        // $this->load->model('Admin_model');
        // $this->load->model('Commen_model');
        //     if($this->User_model->authorized()==false)
        //     {
        //         $this->session->set_flashdata('msg','You are not authorized to access this section');
        //         redirect(base_url().'/UserController/login');
        //     }
        //     $user = $this->session->userdata('user');
            
        //     $data['user'] = $user;
            
        //     $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        //     $data['reseller_sp'] = $this->Commen_model->get_reseller_sp($data['user']['user_id']);

        //     $this->db->select('iam');
        //     $this->db->where('id', $user['id']);
        //     $qry = $this->db->get('user_details');
        //     $data['user']['iam'] = $qry->row_array()['iam'];
        //     $landing = $this->User_model->landingId($user['id']);
        //     $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
        //     $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
        //     $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
        //     $data['mot_menu'] = $this->Commen_model->get_mot_menu('sp','mot_sidebar');
        //     $data['mot_navbar'] = $this->Commen_model->get_mot_menu('sp','mot_navbar');
        $data = $this->initializer();
            $this->load->view('navbar',$data);
            // $this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/dashboard',$data);
            $this->load->view('footer');
    }
    public function change_password()
    {
        // $this->load->model('User_model');
        // $this->load->model('Admin_model');
        // if($this->User_model->authorized()==false)
        // {
        //     $this->session->set_flashdata('msg','You are not authorized to access this section');
        //     redirect(base_url().'/UserController/login');
        // }
        $data =$this->initializer();
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email = $user['email'];
        
    
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar',$data); 
        $this->load->view('change_password',$data); 
        $this->load->view('footer'); 
    
        if(isset($_POST['changepwd']))
        {
            $old_pwd = $this->input->post('old_psw');
            $user = $this->User_model->checkUser($email);
            if(password_verify($old_pwd,$user['pwd'])==true)
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('npsw','Password','required|min_length[8]|max_length[25]|callback_check_strong_password');
                $this->form_validation->set_rules('cnpsw','Confirm Password','required|matches[npsw]');
                if($this->form_validation->run() == true)
                {                        
                   
                    $this->db->set('pwd',password_hash($this->input->post('npsw'),PASSWORD_BCRYPT));
                    $this->db->where('email',$email);
                    $this->db->update('user_details');
                    $this->session->set_flashdata('msg2','Password Changed Successfully');
                    redirect(base_url().'/SpController/change_password');
                
                }
                else
                {
                    $this->session->set_flashdata('msg','Please Check Password and Confirm Password are same or not');
                    redirect(base_url().'/SpController/change_password');
                }
                
            }
            else
            {
                $this->session->set_flashdata('msg','Wrong OLD Password');
                redirect(base_url().'/SpController/change_password');
               
            }
            
        }
    }

    public function check_strong_password($str)
    {
        if (preg_match('#[0-9]#', $str) && preg_match('#[a-z]#', $str) && preg_match('#[A-Z]#', $str)) {
        return TRUE;
        }
        $this->form_validation->set_message('check_strong_password', 'The password field must be contains at least one capital letter, one small letter, and one digit.');
        return FALSE;
    }
   

    public function view_sp_profile()
    {
        // $this->load->model('User_model');
        // if($this->User_model->authorized()==false)
        // {
        //     $this->session->set_flashdata('msg','You are not authorized to access this section');
        //     redirect(base_url().'/UserController/login');
        // }
        $data =$this->initializer();
        $user = $this->session->userdata('user');
        $data['user'] = $user;

        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar',$data); 
        $this->load->view('sp/sp_profile',$data); 
        $this->load->view('footer');
    }
    public function edit_sp_profile()
    {

        // $this->load->model('User_model');
        // if($this->User_model->authorized()==false)
        // {
        //     $this->session->set_flashdata('msg','You are not authorized to access this section');
        //     redirect(base_url().'/UserController/login');
        // }
        $data =$this->initializer();
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email=$user['email'];
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar',$data); 
        $this->load->view('sp/edit_sp_profile',$data); 
        $this->load->view('footer'); 

        if(isset($_POST['updatebtn']))
        {
            $this->form_validation->set_rules('full_name','Fullname|min_length[3]','required');
            $this->form_validation->set_rules('mobile','Mobile','required|min_length[10]');
            if($this->form_validation->run() == true)
            {   
                $this->db->set('fullname',$_POST['full_name']);
                $this->db->set('mobile',$_POST['mobile']);
                $this->db->where('email',$email);
                $this->db->update('user_details');

                $sessArray['id'] = $user['id'];
                $sessArray['fullname']=$_POST['full_name'];
                $sessArray['email']=$user['email'];
                $sessArray['mobile']=$_POST['mobile'];
                $sessArray['user_id']=$user['user_id'];
                $sessArray['profile_photo']=$user['profile_photo'];
                $this->session->set_userdata('user',$sessArray);
                $this->session->set_flashdata('msg','Detail Updated');               
                redirect(base_url().'SpController/edit_sp_profile','refresh');
            }
        }
    }
    public function edit_profile_photo()
    {
        $this->load->model('User_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email=$user['email'];
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar'); 
        $this->load->view('edit_profile_photo',$data); 
        $this->load->view('footer'); 
        
        
    }

    
    public function complete_profile()
    {
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email = $user['email'];
        $this->db->where('email',$email);
        $num = $this->db->get('provider_detail_first')->num_rows();
       
        if($num==0)
        {
            $level['l'] = $this->Sp_model->provider_level_list();
            $page = 'sp/complete_profile';
        }
        else
        {
            $l1 = $this->db->get('provider_detail_first');
            foreach($l1->result() as $l1)
            {
                $l1 = $l1->l1;
            }
            
            $this->db->where('email',$email);
            $num = $this->db->get('provider_detail_sec')->num_rows();
            if($num==0)
            {
                $level['p'] = $this->Sp_model->provider_level_list_sec($l1);
                $page = 'sp/l2';
            }
            else
            {
                
                $this->db->where('email',$email);
                $num = $this->db->get('provider_detail_three')->num_rows();
                if($num==0)
                {
                    $this->db->where('email',$email);
                    $level['l'] = $this->db->get('provider_detail_sec');
                    $page = 'sp/l3'; 
                }
                else
                {
                    redirect(base_url().'/SpController/l4');
                }
               
            }

        }
       
       
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar');
        $this->load->view($page,$level);
        $this->load->view('footer');

        if(isset($_POST['request_btn']))
        {
            $this->form_validation->set_rules('levelone','Level 1','required');
            if($this->form_validation->run() == true)
            {
                   
                        $formArray = array(
                            'email'=>$user['email'],
                            'l1'=>$_POST['levelone'] 
                        );
                        
                        $this->db->insert('provider_detail_first',$formArray);
                        $this->session->set_flashdata("msg2","Details Saved");
                        redirect('/SpController/complete_profile');
                    
            }
            else
            {
                $this->session->set_flashdata("msg",validation_errors());
                redirect('/SpController/complete_profile','refresh');
            }
        }
        if(isset($_POST['save_level2']))
        {
            $this->form_validation->set_rules('cb[]','Level 2','required');
            if($this->form_validation->run() == true)
            {
                $cb = $_POST['cb'];
                for($i=0;$i<count($cb);$i++)
                {
                    $formArray = array(
                        'email'=>$user['email'],
                        'l2'=>$cb[$i]
                    );
                    $this->db->insert('provider_detail_sec',$formArray);
                }       
                
                        $this->session->set_flashdata("msg2","Details Saved");
                        redirect('/SpController/complete_profile');
                    
            }
            else
            {
                $this->session->set_flashdata("msg",validation_errors());
                redirect('/SpController/complete_profile','refresh');
            }

        }
        if(isset($_POST['save_level3']))
        {
            $val = $this->input->post('val');
            $this->db->where('email',$user['email']);
            $l1_id = $this->db->get('provider_detail_first');
            foreach($l1_id->result() as $l1_id)
            {
                $l1 = $l1_id->l1;
            }
            if($val==0)
            {
               
                $this->db->where('email',$user['email']);
                $l2_id = $this->db->get('provider_detail_sec');
                foreach($l2_id->result() as $l2_id)
                {
                    $l2_id = $l2_id->l2;
                    $formArray = array(
                        'email'=>$user['email'],
                        'l1'=>$l1,
                        'l2'=>$l2_id,
                        'l3'=>'0'
                    );
                    $this->db->insert('provider_detail_three',$formArray);
                    
                    //if market_place service, make entry into services_marketplace table also

                }
                $this->session->set_flashdata("msg2","Details Saved");
                redirect(base_url().'/SpController/l4');   
            }
            else
            {
                $this->form_validation->set_rules('cb[]','Level 2','required');
                if($this->form_validation->run() == true)
                {
                    $cb = $_POST['cb'];
                    for($i=0;$i<count($cb);$i++)
                    {
                        $this->db->where('id',$cb[$i]);
                        $l2_id = $this->db->get('provider_level_three');
                        foreach($l2_id->result() as $l2_id)
                        {
                            $l2_id = $l2_id->l2;
                        }
                        $formArray = array(
                            'email'=>$user['email'],
                            'l1'=>$l1,
                            'l2'=>$l2_id,
                            'l3'=>$cb[$i]
                        );
                        $this->db->insert('provider_detail_three',$formArray);
                    }       
                    
                            $this->session->set_flashdata("msg2","Details Saved");
                            redirect(base_url().'/SpController/spprofile');
                        
                }
                else
                {
                    $this->db->where('email',$user['email']);
                    $l2_id = $this->db->get('provider_detail_sec');
                    foreach($l2_id->result() as $l2_id)
                    {
                        $l2_id = $l2_id->l2;
                        $formArray = array(
                            'email'=>$user['email'],
                            'l1'=>$l1,
                            'l2'=>$l2_id,
                            'l3'=>'0'
                        );
                        $this->db->insert('provider_detail_three',$formArray); 
                    }
                    $this->session->set_flashdata("msg2","Details Saved");
                    redirect(base_url().'/SpController/l4');  
                }
            }
            
            
        }
    }


   
    public function fetch_level_two()
    {        
        $this->load->model('Admin_model');
        $this->load->model('Sp_model');
        $level = $this->input->post('id',TRUE);
        $data = $this->Sp_model->fetch_level_two($level)->result();
        echo json_encode($data);
        
    }
    public function add_institution()
    {
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $data['user'] = $user;

        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar');
        $this->load->view('sp/add_institution');
        $this->load->view('footer');

        if(isset($_POST['saveBtn']))
        {
            $email = $user['email'];
            $this->load->library('form_validation');
            $this->form_validation->set_rules('inst_name','Institute Name','required');
            $this->form_validation->set_rules('domain','Domain of Work','required');
            $this->form_validation->set_rules('city','City','required');
            $this->form_validation->set_rules('state','State','required');
            $this->form_validation->set_rules('country','Country','required');
            if($this->form_validation->run() == true)
                {    
                    $formArray = array(
                        'email'=>$email,
                        'inst_name'=>$_POST['inst_name'],
                        'domain_of_work'=>$_POST['domain'],
                        'city'=>$_POST['city'],
                        'state'=>$_POST['state'],
                        'country'=>$_POST['country'],
                       
                    );
                    
                    $this->Sp_model->insert_institute_details($formArray);
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/SpController/add_institution','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg",validation_errors());
                    redirect('/SpController/add_institution','refresh');
                }
        }
    }

    public function select_l3()
    {
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $data['user'] = $user;

        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar');
        $this->load->view('sp/l3_selection');
        $this->load->view('footer');  
    }

    public function l4()
    {
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email = $user['email'];
        $this->db->where('email',$email);
        $level['l'] = $this->db->get('provider_detail_three');

        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar');
        $this->load->view('sp/l4',$level);
        $this->load->view('footer');

        if(isset($_POST['save_level4']))
        {
            $i = 2;
            $email = $user['email'];
            $this->load->library('form_validation');
            $this->form_validation->set_rules('11','Parameter One','required');
            $this->form_validation->set_rules('12','Parameter Two','required');
            $this->form_validation->set_rules('13','Parameter Three','required');
            $this->form_validation->set_rules('14','Parameter Four','required');
           
            if($this->form_validation->run() == true)
                {    
                    $p1 = $_POST['11']; 
                    $p2 = $_POST['12'];
                    $p3 = $_POST['13'];
                    $p4 = $_POST['14'];
                    $where = "email='$email' and p1='$p1' and p2='$p2' and p3='$p3' and p4='$p4'";
                    $this->db->where($where);
                    $cnt = $this->db->get('provider_detail_four')->num_rows();
                    if($cnt>0)
                    {
                        $this->session->set_flashdata("msg","Combination One Already Exist");
                        redirect('/SpController/l4','refresh');
                    }
                    else
                    {
                        $status = "ok";
                        $sdt = 1;
                    }
                    start:
                    if($i==2)
                    {
                        $s1 = '21';
                        $s2 = '22';
                        $s3 = '23';
                        $s4 = '24';

                    }
                    else if($i==3)
                    {
                        $s1 = '31';
                        $s2 = '32';
                        $s3 = '33';
                        $s4 = '34';
                    }
                    else
                    {
                        $s1 = '41';
                        $s2 = '42';
                        $s3 = '43';
                        $s4 = '44';
                    }
                    
                    $level3 = $_POST['levelthree'];
                    if($_POST[$s1]!='' || $_POST[$s2]!='' || $_POST[$s3]!='' || $_POST[$s4]!='')
                    {
                        if($_POST[$s1]!='' && $_POST[$s2]!='' && $_POST[$s3]!='' && $_POST[$s4]!='')
                        {
                            
                            $pp1 = $_POST[$s1]; 
                            $pp2 = $_POST[$s2];
                            $pp3 = $_POST[$s3];
                            $pp4 = $_POST[$s4];
                            if($p1==$pp1 && $p2==$pp2 && $p3==$pp3 && $p4==$pp4)
                            {
                                $this->session->set_flashdata("msg","Sorry You Select Same Combination");
                                redirect('/SpController/l4','refresh');
                            }
                            $where = "email='$email' and p1='$pp1' and p2='$pp2' and p3='$pp3' and p4='$pp4'";
                            $this->db->where($where);
                            $cnt = $this->db->get('provider_detail_four')->num_rows();
                            if($cnt>0)
                            {
                                $ecode =$i;
                                $this->session->set_flashdata("msg","Combination $ecode Already Exist");
                                redirect('/SpController/l4','refresh');
                            }
                            else
                            {
                                $status = "ok";
                                $sdt++;
                            }
                            if($i!=4)
                            {
                                $i++;
                                goto start;
                            }
                             
                        }
                        else
                        {
                            $ecode = $i;
                            $this->session->set_flashdata("msg","Please Fill All Details in combination $ecode.");
                            redirect(base_url().'/SpController/l4');
                        }
                        
                    }

                    if($sdt>1)
                    {
                        
                        for($j=1;$j<=$sdt-1;$j++)
                        {  
                            
                            $vr = $j+1;
                            $s1 = $vr.'1';
                            $s2 = $vr.'2';
                            $s3 = $vr.'3';
                            $s4 = $vr.'4';
                            $formArray = array(
                                'email'=>$email,
                                'l1'=>$_POST['l1'],
                                'l2'=>$_POST['leveltwo'],
                                'l3_id'=>$level3,
                                'p1'=>$_POST[$s1],
                                'p2'=>$_POST[$s2],
                                'p3'=>$_POST[$s3],
                                'p4'=>$_POST[$s4],
                                'price'=>''  
                            );
                            $this->db->insert('provider_detail_four',$formArray);
                        }
                    }
                    
                    $fa = array(
                        'email'=>$email,
                        'l1'=>$_POST['l1'],
                        'l2'=>$_POST['leveltwo'],
                        'l3_id'=>$level3,
                        'p1'=>$p1,
                        'p2'=>$p2,
                        'p3'=>$p3,
                        'p4'=>$p4,
                        'price'=>''  
                    );
                    $this->db->insert('provider_detail_four',$fa);
                    
                    $this->session->set_flashdata("msg2","Details Saved");
                    redirect(base_url().'/SpController/l4');
                }
                else
                {
                    $this->session->set_flashdata("msg",validation_errors());
                    redirect('/SpController/l4','refresh');
                }
        }
    }
    public function fetch_level()
    {   
        $this->load->model('Sp_model');
        $level = $this->input->post('id',TRUE);
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $email = $user['email'];



        $d = $this->Sp_model->fetch_level($level,$email);
        foreach($d->result() as $row)
        {
            $l = $row->l3;  
        }
        $data = $this->Sp_model->fetch_l3($l)->result();
        echo json_encode($data);
        
    }
    public function fetch_level_five(){
        $this->load->model('Admin_model');
        $level = $this->input->post('id',TRUE);
        $l1 = $this->input->post('l1',TRUE);
        $l2 = $this->input->post('l2',TRUE);
        $where = "l1='$l1' and l2='$l2' and l3_id='$level'";
        $this->db->where($where);
        $param = $this->db->get('provider_level_four');
        $data = '';
        foreach($param->result() as $param)
        {
            $param_id = $param->param_id;
            for($j=1;$j<=4;$j++)
            {
                
                $data .="<b>Select Combination ".$j."</b>";
                for($i=1;$i<=4;$i++)
                {
                    if($i==1)
                    {
                        $head = $param->param_one;
                        
                    }
                    else if($i==2)
                    {
                        $head = $param->param_two;
                    }
                    else if($i==3)
                    {
                        $head = $param->param_three;
                    }
                    else
                    {
                        $head = $param->param_four;
                    }
                    $data .= "<div class='form-group'>";
                    $data .= '<select class="form-control" name="'.$j.$i.'" id="level4">';
                    $data .= '<option value="">Select '.$head.'</option>';
                    $this->db->where('param_id',$param_id);
                    $v = $this->db->get('provider_level_five');
                    foreach($v->result() as $v)
                    {
                        if($v->param_type==$i)
                        {
                            $data .= '<option value="'.$v->param_value.'">'.$v->param_value.'</option>';
                        }
                        
                    }
                    
                    $data .= '</select>';
                    $data .= '</div>';
                }
            }
            
            

        }
        
        echo $data;  
    }
    public function spprofile()
    {
        $this->load->library('form_validation');
        // $this->load->model('User_model');
         $this->load->model('Sp_model');
        // $this->load->model('Admin_model');
        // if($this->User_model->authorized()==false)
        // {
        //     $this->session->set_flashdata('msg','You are not authorized to access this section');
        //     redirect(base_url().'/UserController/login');
        // }
         $data =$this->initializer();
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email = $user['email'];
        // $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        // $landing = $this->User_model->landingId($user['id']);
        // $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
        
        $this->db->where('email',$email);
        $level1['l']=$this->db->get('provider_detail_four');
      
        // foreach($l1->result() as $l1)
        // {
        //     $l=$l1->l1;
        //     $this->db->where('id',$l);
        //     $level1['l'] = $this->db->get('provider_level_one');
        // }
       

       $solution['s'] = $this->User_model->solutions_list();
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar',$data);
        $this->load->view('sp/spprofile',$level1);
        $this->load->view('footer');
    }
    
    /*Old public function do_upload()
    {
        
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|png';
        $config['max_size'] = 100000;
        $config['max_width'] = 500;
        $config['max_height'] = 500;
        $new_name = time().'.jpg';
        $config['file_name'] = $new_name;         
        $this->load->library('upload', $config);

        
        if ( ! $this->upload->do_upload('img'))
        {
                $error = array('error' => $this->upload->display_errors());
                foreach($error as $error)
                {
                    
                    $this->session->set_flashdata('msg',$error);
                    redirect('/SpController/spprofile','refresh');
                }
                
        }
        else
        {
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email=$user['email'];
            $this->db->where('email',$email);
            $this->db->set('profile_photo','uploads/'.$new_name);
            $this->db->update('user_details');

            $sessArray['id'] = $user['id'];
            $sessArray['fullname']=$user['fullname'];
            $sessArray['email']=$user['email'];
            $sessArray['mobile']=$user['mobile'];
            $sessArray['user_id']=$user['user_id'];
            $sessArray['profile_photo']='uploads/'.$new_name;
            $this->session->set_userdata('user',$sessArray);
            $this->session->set_flashdata('msg','Profile Updated');
            redirect('/SpController/spprofile','refresh');
        }
    } Old*/

    public function do_upload()
        {
               
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'jpg|png';
                $config['max_size'] = 100000;
    
                $new_name = time().'.jpg';
                $config['file_name'] = $new_name;         
                $this->load->library('upload', $config);
    
                
                if ( ! $this->upload->do_upload('img'))
                {
                        $error = array('error' => $this->upload->display_errors());
                        foreach($error as $error)
                        {
                            $this->session->set_flashdata('msg',$error);
                            redirect(base_url().'SpController/edit_sp_profile','refresh');
                        }
                }
                else
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $email=$user['email'];
                    $this->db->where('email',$email);
                    $this->db->set('profile_photo','uploads/'.$new_name);
                    $this->db->update('user_details');
    
                    $sessArray['id'] = $user['id'];
                    $sessArray['fullname']=$user['fullname'];
                    $sessArray['email']=$user['email'];
                    $sessArray['mobile']=$user['mobile'];
                    $sessArray['user_id']=$user['user_id'];
                    $sessArray['iam']=$user['iam'];
                    $sessArray['profile_photo']='uploads/'.$new_name;
                    $this->session->set_userdata('user',$sessArray);
                    $this->session->set_flashdata('msg2','Profile Updated');
                    redirect(base_url().'SpController/edit_sp_profile','refresh');
                }
        }


    public function sp_detail()
    {
        if(isset($_POST['savebtn']))
            {
                $this->form_validation->set_rules('aboutus','About us','required');
                $this->form_validation->set_rules('key','Key Services','required');
                if($this->form_validation->run() == true)
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('email',$uid);
                   
                    $this->db->set('about_us',$_POST['aboutus']);
                    $this->db->set('key_services',$_POST['key']);
                    $this->db->update('sp_profile_detail');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/SpController/spprofile','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/SpController/spprofile','refresh');
                }
            }
       
    }
    public function contact()
    {
       
            if(isset($_POST['savebtn']))
            {
                
                $this->form_validation->set_rules('addr','Address','required');
                $this->form_validation->set_rules('mobile','Mobile','required|min_length[10]|max_length[10]');
               
                if($this->form_validation->run() == true)
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('email',$uid);
                   
                    $this->db->set('address',$_POST['addr']);
                    $this->db->set('contact',$_POST['mobile']);
                    $this->db->update('sp_profile_detail');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/SpController/spprofile','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/SpController/spprofile','refresh');
                }
            }
    }
    public function fb_link()
    {
        if(isset($_POST['savebtn']))
            {
                $this->form_validation->set_rules('fb_link','fb link','required');
               
                if($this->form_validation->run() == true)
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('email',$uid);
                   
                    $this->db->set('fb_url',$_POST['fb_link']);
                    $this->db->update('sp_profile_detail');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/SpController/spprofile','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/SpController/spprofile','refresh');
                }
            }

    }
    public function tw_link()
    {
        if(isset($_POST['savebtn']))
            {
                $this->form_validation->set_rules('tw_link','Twitter link','required');
               
                if($this->form_validation->run() == true)
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('email',$uid);
                   
                    $this->db->set('twitter_url',$_POST['tw_link']);
                    $this->db->update('sp_profile_detail');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/SpController/spprofile','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/SpController/spprofile','refresh');
                }
            }

    }
    public function insta_link()
    {
        if(isset($_POST['savebtn']))
            {
                $this->form_validation->set_rules('insta_link','Instagram link','required');
               
                if($this->form_validation->run() == true)
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('email',$uid);
                   
                    $this->db->set('insta_url',$_POST['insta_link']);
                    $this->db->update('sp_profile_detail');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/SpController/spprofile','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/SpController/spprofile','refresh');
                }
            }

    }
    public function linke_link()
    {
        if(isset($_POST['savebtn']))
            {
                $this->form_validation->set_rules('linke_link','Linkedin link','required');
               
                if($this->form_validation->run() == true)
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('email',$uid);
                   
                    $this->db->set('linkedin_url',$_POST['linke_link']);
                    $this->db->update('sp_profile_detail');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/SpController/spprofile','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/SpController/spprofile','refresh');
                }
            }

    }
    public function sp_more_detail()
    {
        if(isset($_POST['savebtn']))
            {
                $this->form_validation->set_rules('heading1','Heading One','required');
                $this->form_validation->set_rules('content1','Content One','required');
                $this->form_validation->set_rules('heading2','Heading Two','required');
                $this->form_validation->set_rules('content2','Content Two','required');
                $this->form_validation->set_rules('heading3','Heading Three','required');
                $this->form_validation->set_rules('content3','Content Three','required');
               
                if($this->form_validation->run() == true)
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('email',$uid);
                   
                    $this->db->set('heading1',$_POST['heading1']);
                    $this->db->set('content1',$_POST['content1']);
                    $this->db->set('heading2',$_POST['heading2']);
                    $this->db->set('content2',$_POST['content2']);
                    $this->db->set('heading3',$_POST['heading3']);
                    $this->db->set('content3',$_POST['content3']);
                    $this->db->update('sp_profile_detail');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/SpController/spprofile','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/SpController/spprofile','refresh');
                }
            }
    }
    public function view_sp()
    {
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email = $user['email'];

        $level['lk'] = $this->Admin_model->provider_level_list();
        $level['sk'] = $this->Admin_model->provider_level3_list();
 
        // $level['s'] = $this->User_model->solutions_list();
        $where = "iam='sp' and status!='0'";
        $this->db->where($where);
        $level['l'] = $this->db->get('user_details');

        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar');
        $this->load->view('sp/view_resellerpage',$level);
        $this->load->view('footer');
       
    }
    public function search_result()
    {
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        if(isset($_POST['saveBtn']))
        {
            $level1 = $_POST['levelone'];
            $level2 = $_POST['leveltwo'];
            $level3 = $_POST['levelthree'];
            $where = "iam='sp' and status!='0'";
            $this->db->where($where);
            $row = $this->db->get('user_details');
            foreach($row->result() as $row)
            {
                $email = $row->email;
                $where = "email='$email' and l1='$level1' and l2='$level2' and l3='$level3' ";
                $this->db->where($where);
                $num = $this->db->get('provider_detail_three')->num_rows();
                if($num>0)
                {
                    $this->db->where('email',$email);
                    $sp['s'] = $this->db->get('user_details');
                }   
            }
            
            
            
        }
        $this->load->view('navbar',$data);
        $this->load->view('sp/sidebar');
        //$this->load->view('sidebar',$data);
        if($sp['s']!='')
        {
            $this->load->view('sp/search_sp',$sp);
        }
        else
        {
            $this->session->set_flashdata('msg','No Record Found');
            redirect(base_url().'/SpController/view_sp');
        }

        $this->load->view('footer');
    }
    public function update_price()
    {
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email = $user['email'];
        $this->db->where('email',$email);
        $val_num = $this->db->get('provider_detail_four')->num_rows();
        for($i=1;$i<=$val_num;$i++)
        {
            $idc = 'pid'.$i;
            $id = $_POST[$idc];
            $pricec = 'price'.$i;
            $price = $_POST[$pricec];
            $this->db->where('id',$id);
            $this->db->set('price',$price);
            $this->db->update('provider_detail_four');
        }
        $this->session->set_flashdata('msg','Price Set');
        redirect(base_url().'/SpController/spprofile');
      
    }
    public function view_sp_full_detail($email)
    {
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email = base64_decode($email);
        
        $this->db->where('email',$email);
        $level1['s']=$this->db->get('user_details');
        $this->db->where('email',$email);
        $level1['l']=$this->db->get('provider_detail_four');
      
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar');
        $this->load->view('sp/sp_detail_profile',$level1);
        $this->load->view('footer');
    }
    public function all_sp_providers()
    {
        redirect('/SpController/all_sp_provider/1','refresh');
    }
    public function all_sp_provider($num)
    {
       
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
       

        $level['lk'] = $this->Admin_model->provider_level_list();
        $level['sk'] = $this->Admin_model->provider_level3_list();
 
        // $level['s'] = $this->User_model->solutions_list();
        $where = "iam='sp' and status!='0' and id>=$num";
        $this->db->where($where);
        $this->db->limit('6');
        $level['l'] = $this->db->get('user_details');

       
        // $this->load->view('navbar');
        $this->load->view('sp/header3');
        $this->load->view('sp/all_sp',$level);
        $this->load->view('footer');
    }

    public function view_sp_full_detail_public($email)
    {
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
       
        $email = base64_decode($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            show_404();
        }
        
        $this->db->where('email',$email);
        $level1['s']=$this->db->get('user_details');
        $this->db->where('email',$email);
        $level1['l']=$this->db->get('provider_detail_four');
      
        $this->load->view('sp/sp_detail_profile_public',$level1);
        $this->load->view('footer');
    }
    public function search_result_public()
    {
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
        
        if(isset($_POST['saveBtn']))
        {
            $level1 = $_POST['levelone'];
            $level2 = $_POST['leveltwo'];
            $level3 = $_POST['levelthree'];
            $where = "iam='sp' and status!='0'";
            $this->db->where($where);
            $row = $this->db->get('user_details');
            foreach($row->result() as $row)
            {
                $email = $row->email;
                $where = "email='$email' and l1='$level1' and l2='$level2' and l3='$level3' ";
                $this->db->where($where);
                $num = $this->db->get('provider_detail_three')->num_rows();
                if($num>0)
                {
                    $this->db->where('email',$email);
                    $sp['s'] = $this->db->get('user_details');
                }   
            }
            
            
            
        }
        $this->load->view('sp/header3');
         $this->load->view('sp/sidebar');
        if($sp['s']!='')
        {
            $this->load->view('sp/search_sp_public',$sp);
        }
        else
        {
            $this->session->set_flashdata('msg','No Record Found');
            redirect(base_url().'/SpController/all_sp_providers');
        }

        $this->load->view('footer');
    }

    public function become_partner_counselor()
    {
            // $this->load->model('User_model');
            // $this->load->model('Base_model');
            $this->load->model('Sp_model');
            // $this->load->model('Commen_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            
            $code['s'] = $this->Sp_model->solution_list();
            // $this->db->select('iam');
            // $this->db->where('id', $user['id']);
            // $qry = $this->db->get('user_details');
            // $data['user']['iam'] = $qry->row_array()['iam'];
            // $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
            // $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
            $this->load->view('navbar',$data);
            $this->load->view('sp/sidebar',$data); 
            $this->load->view('sp/view_solutions_for_become_partner_counseler',$code); //replace with view_approve_code 
            $this->load->view('footer');
    }
    public function request_onboarding_exam($grp)
    {
           
            $grp = str_replace('%20',' ',$grp);
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            
            $formArray = array(
                'email'=>$user['email'],
                'grp'=> $grp,
                'status'=>'ua',
                'score'=>'0'
            );

            $this->Sp_model->insert_partner_request($formArray);
            $this->session->set_flashdata('msg','Request Send Successfully Please wait for approval');
            
            redirect(base_url().'/SpController/become_partner_counselor');
    }

    public function request_certification_exam($grp)
    {
            $grp = str_replace('%20',' ',$grp);
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            $where = "email='$email' and grp='$grp'";
            $this->db->where($where);
            $this->db->set('status','Ca');
            $this->db->update('partner_counselor_status');
            
            $this->session->set_flashdata('msg','Request Send Successfully Please wait for approval');
            redirect(base_url().'/SpController/become_partner_counselor');
    }

    public function onboarding_exam($grp)
    {
        
        $this->load->model('User_model');
        $this->load->model('Base_model');
        $this->load->model('Sp_model');
        $grp = str_replace('%20',' ',$grp);
        
        //echo $grp;die();

        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email = $user['email'];
        $ob_no_ce = 1;
        if($grp == 'Career Explorer')
        {
            redirect(base_url().'/SpController/career_explorer_onboarding_test/'.$ob_no_ce); 
        }
        else if($grp == 'Positive Parenting')
        {
            redirect(base_url().'/SpController/positive_parenting_onboarding_test');
          
        }
        else if($grp == 'Career Excellence')
        {
            $ob_no_ce = 1;
            redirect(base_url().'/SpController/career_excellence_onboarding_test/'.$ob_no_ce);
            
        }
        else if($grp == 'Career Builder')
        {
            $ob_no_ce = 1;
            redirect(base_url().'/SpController/career_builder_onboarding_test/'.$ob_no_ce);
           
        }

        else if ($grp == 'Overseas Companion')
        {
            //echo "in else if";die();
            $ob_no_ce = 1;
            redirect(base_url().'/SpController/overseas_companion_onboarding_test/'.$ob_no_ce);
        }

        // var_dump($grp);
        // echo "reached here<br>";die;
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar'); 
        $this->load->view('sp/view_solutions_for_become_partner_counseler'); //replace with view_approve_code 
        $this->load->view('footer');
    }

    public function positive_parenting_onboarding_test()
    {
        $code = 'ppot';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='ppot'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
          
            $this->db->where('Modified_Nature','O1');
            $count = $this->db->get('partner_counselor_parenting')->num_rows();
            // echo $count;
            $mod = fmod($count,5);
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            if($mod==0)
            {
                $count_value = $count - 4;
            }
            $qlist['q'] = $this->Sp_model->ppot_question($num);
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=$count_value)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'ppot',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/positive_parenting_onboarding_test');
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/positive_parenting_onboarding_test');
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'ppot',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='Positive Parenting'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('partner_counselor_status');
                        redirect(base_url().'SpController/score_calculation/ppot/O1'); 
                        // $this->session->set_flashdata('msg','Assessment Compeleted Please Wait for Report');
                        // redirect(base_url().'SpController/become_partner_counselor');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/positive_parenting_onboarding_test');
                    }
                    
                   
                }
                
            }
    }
    
    public function career_explorer_onboarding_test($ob_no)
    {
        
            $ob = 'O'.$ob_no;
            
            $code = 'ceot';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];

            $where2 = "email='$email' and grp='Career Explorer'";
            $this->db->where($where2);
            $r = $this->db->get('partner_counselor_status')->row();
            if($r->nature!='')
            {
                $ob = $r->nature;
               
            }
            else
            {
                $where2 = "email='$email' and grp='Career Explorer'";
               $this->db->where($where2);
               $this->db->set('nature',$ob);
               $this->db->update('partner_counselor_status'); 
            }
            
            $this->db->where('Modified_Nature',$ob);
            $count = $this->db->get('partner_counselor_career_explorer')->num_rows();
            // echo $count;
            $mod = fmod($count,5);
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            if($mod==0)
            {
                $count_value = $count - 4;
            }
            $where = "email='$email' and code='$code' and solution='ceot'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Sp_model->ceot_question($ob,$num);
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=$count_value)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'ceot',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/career_explorer_onboarding_test/'.$ob_no);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_explorer_onboarding_test/'.$ob_no);
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    // $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'ceot',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='Career Explorer'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('partner_counselor_status');
                        
                        redirect(base_url().'SpController/score_calculation/ceot/'.$ob); 
                        // $this->session->set_flashdata('msg','Assessment Compeleted Please Wait for Report');
                        // redirect(base_url().'SpController/become_partner_counselor');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_explorer_onboarding_test/'.$ob_no);
                    }
                    
                   
                }
                
            }
    }

    public function career_builder_onboarding_test($ob)
    {
        $ob = 'O'.$ob;
        $code = 'cbot';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];

            $where2 = "email='$email' and grp='Career Builder'";
            $this->db->where($where2);
            $r = $this->db->get('partner_counselor_status')->row();
            if($r->nature!='')
            {
                $ob = $r->nature;
               
            }
            else
            {
                $where2 = "email='$email' and grp='Career Builder'";
               $this->db->where($where2);
               $this->db->set('nature',$ob);
               $this->db->update('partner_counselor_status'); 
            }
            
            $this->db->where('Modified_Nature',$ob);
            $count = $this->db->get('partner_counselor_cmb')->num_rows();
            // echo $count;
            $mod = fmod($count,5);
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            if($mod==0)
            {
                $count_value = $count - 4;
            }


            $where = "email='$email' and code='$code' and solution='cbot'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Sp_model->cexot_question($ob,$num);
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=$count_value)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cbot',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/career_builder_onboarding_test/'.$ob);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_builder_onboarding_test/'.$ob);
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    // $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cbot',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='Career Builder'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('partner_counselor_status');
                        
                        redirect(base_url().'SpController/score_calculation/cbot/'.$ob);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_builder_onboarding_test/'.$ob);
                    }
                    
                   
                }
                
            }
    }

    public function career_excellence_onboarding_test($ob)
    {
        $ob = 'O'.$ob;
        $code = 'cexot';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];

            $where2 = "email='$email' and grp='Career Excellence'";
            $this->db->where($where2);
            $r = $this->db->get('partner_counselor_status')->row();
            if($r->nature!='')
            {
                $ob = $r->nature;
               
            }
            else
            {
                $where2 = "email='$email' and grp='Career Excellence'";
               $this->db->where($where2);
               $this->db->set('nature',$ob);
               $this->db->update('partner_counselor_status'); 
            }
            
            $this->db->where('Modified_Nature',$ob);
            $count = $this->db->get('partner_counselor_cmb')->num_rows();
            // echo $count;
            $mod = fmod($count,5);
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            if($mod==0)
            {
                $count_value = $count - 4;
            }

            $where = "email='$email' and code='$code' and solution='cexot'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Sp_model->cexot_question($ob,$num);
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=$count_value)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cexot',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/career_excellence_onboarding_test/'.$ob);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_excellence_onboarding_test');
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    // $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cexot',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='Career Excellence'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('partner_counselor_status');
                        
                        redirect(base_url().'SpController/score_calculation/cexot/'.$ob);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_excellence_onboarding_test');
                    }
                    
                   
                }
                
            }
    }
    
    
    public function overseas_companion_onboarding_test($ob)
    {
        $ob = 'C'.$ob;
        $code = 'ocoxt';
        $solution='ocoxt';
        $tbl_exam='partner_counselor_oc';
        $grp='Overseas Companion';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            
            //echo $email;die();

            $where2 = "email='$email' and grp='$grp'";
            $this->db->where($where2);
            $r = $this->db->get('partner_counselor_status')->row();
            // echo "test";
            // print_r($r);die();
            if($r->nature!='')
            {
                $ob = $r->nature;
               
            }
            else
            {
                $where2 = "email='$email' and grp='$grp'";
                $this->db->where($where2);
                $this->db->set('nature',$ob);
                $this->db->update('partner_counselor_status'); 
            }
            
            $this->db->where('Modified_Nature',$ob);
            $count = $this->db->get($tbl_exam)->num_rows();
            // echo $count;
            $mod = fmod($count,5);
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            if($mod==0)
            {
                $count_value = $count - 4;
            }

            $where = "email='$email' and code='$code' and solution='$solution'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Sp_model->ocoxt_question($ob,$num, $solution);
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=$count_value)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>$solution,
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/overseas_companion_onboarding_test/'.$ob);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/overseas_companion_onboarding_test');
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    // $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>$solution,
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='$grp'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('partner_counselor_status');
                        
                        redirect(base_url().'SpController/score_calculation/'.$solution.'/'.$ob);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/overseas_companion_onboarding_test');
                    }
                    
                   
                }
                
            }
    }
    
    
    
    public function overseas_companion_certification_test_old($ob)
    {
        $ob = 'C'.$ob;
        $code = 'occxt';
        $solution='occxt';
        $tbl_exam='partner_counselor_oc_cer';
        $grp='Overseas Companion';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            
            //echo $email;die();

            $where2 = "email='$email' and grp='$grp'";
            $this->db->where($where2);
            $r = $this->db->get('partner_counselor_status')->row();
            // echo "test";
            // print_r($r);die();
            if($r->nature!='')
            {
                $ob = $r->nature;
               
            }
            else
            {
                $where2 = "email='$email' and grp='$grp'";
                $this->db->where($where2);
                $this->db->set('nature',$ob);
                $this->db->update('partner_counselor_status'); 
            }
            
            $this->db->where('Modified_Nature',$ob);
            $count = $this->db->get($tbl_exam)->num_rows();
            // echo $count;
            $mod = fmod($count,5);
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            if($mod==0)
            {
                $count_value = $count - 4;
            }

            $where = "email='$email' and code='$code' and solution='$solution'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Sp_model->ocoxt_question($ob,$num, $solution);
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=$count_value)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>$solution,
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/overseas_companion_certification_test/'.$ob);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/overseas_companion_certification_test');
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    // $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>$solution,
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='$grp'";
                        $this->db->where($where);
                        $this->db->set('status','Rp');
                        $this->db->update('partner_counselor_status');
                        
                        redirect(base_url().'SpController/score_calculation/'.$solution.'/'.$ob);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/overseas_companion_certification_test');
                    }
                    
                   
                }
                
            }
    }
    

    public function score_calculation($grp,$ob)
    {
            $code = $grp;
            $temp_score = 0;
            if($code=='ppot')
            {
                $table = 'partner_counselor_parenting';
                $group = 'Positive Parenting';
            }
            else if($code=='ceot')
            {
                $table = 'partner_counselor_career_explorer';
                $group = 'Career Explorer';
            }
            else if($code=='cexot')
            {
                $table = 'partner_counselor_cmb';
                $group = 'Career Excellence';
            }
            else if($code=='cbot')
            {
                $table = 'partner_counselor_cmb';
                $group = 'Career Builder';
            }
            else if($code=='ocoxt')
            {
                $table = 'partner_counselor_oc';
                $group = 'Overseas Companion';
            }
            else if($code=='occxt')
            {
                $table = 'partner_counselor_oc';
                $group = 'Overseas Companion';
            }
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='$code'";
            $this->db->where($where);
            $row = $this->db->get('ppe_part1_test_details');
            $qnm = 0;
            foreach($row->result() as $r)
            {
                $qno = $r->qno;
                $where3 = "org_qno='$qno' and Modified_Nature='$ob'";
                $this->db->where($where3);
                $row2 = $this->db->get($table)->row();
                $correct_ans = $row2->Correct_Option;
                if($r->ans == $correct_ans)
                {
                    $temp_score = $temp_score + 1;
                }
               $qnm = $qnm + 1;
            }

            $final_score = round($temp_score/$qnm*100);
            $this->db->where('c_group',$group);
            $ob_score = $this->db->get('certification_details')->row();
            $cutoff_score = $ob_score->pass_per_ob;
            if($final_score>=$cutoff_score)
            {
                $exam_passed = 'Pass';
            } 
            else
            {
                $exam_passed ='Failed';
            }
            $dt = date('d-m-Y');
            $where = "email='$email' and grp='$group'";
            $this->db->where($where);
            $this->db->set('score',$final_score);
            $this->db->set('ob_time',$dt);
            $this->db->set('exam_passed',$exam_passed);
            $this->db->update('partner_counselor_status');

            $this->session->set_flashdata('msg','Assessment Compeleted Please Wait for Report');
            redirect(base_url().'SpController/become_partner_counselor');     
    }

    public function ce_score_calculation($grp,$ob)
    {
            $code = $grp;
            $temp_score = 0;
            if($code=='ppct')
            {
                $table = 'partner_counselor_parenting';
                $group = 'Positive Parenting';
            }
            else if($code=='cect')
            {
                $table = 'partner_counselor_career_explorer';
                $group = 'Career Explorer';
            }
            else if($code=='cexct')
            {
                $table = 'partner_counselor_cmb';
                $group = 'Career Excellence';
            }
            else if($code=='cbct')
            {
                $table = 'partner_counselor_cmb';
                $group = 'Career Builder';
            }
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='$code'";
            $this->db->where($where);
            $row = $this->db->get('ppe_part1_test_details');
            $qnm = 0;
            foreach($row->result() as $r)
            {
                $qno = $r->qno;
                $where3 = "org_qno='$qno' and Modified_Nature='$ob'";
                $this->db->where($where3);
                $row2 = $this->db->get($table)->row();
                $correct_ans = $row2->Correct_Option;
                if($r->ans == $correct_ans)
                {
                    $temp_score = $temp_score + 1;
                }
               $qnm = $qnm + 1;
            }

            $final_score = round($temp_score/$qnm*100);
            $this->db->where('c_group',$group);
            $ob_score = $this->db->get('certification_details')->row();
            $cutoff_score = $ob_score->pass_per_ob;
            if($final_score>=$cutoff_score)
            {
                $exam_passed = 'Pass';
                $status = 'CRp';
            } 
            else
            {
                $exam_passed ='Failed';
                $status = 'CRf';
            }
            $dt = date('d-m-Y');
            $where = "email='$email' and grp='$group'";
            $this->db->where($where);
            $this->db->set('score_ce',$final_score);
            $this->db->set('ce_result',$exam_passed);
            $this->db->set('ce_time',$dt);
            $this->db->set('status',$status);
            $this->db->update('partner_counselor_status');

            $this->session->set_flashdata('msg','Assessment Compeleted Please Wait for Report');
            redirect(base_url().'SpController/become_partner_counselor');     
    }

    public function ce_exam($grp)
    {
        $this->load->model('User_model');
        $this->load->model('Base_model');
        $this->load->model('Sp_model');
        $grp = str_replace('%20',' ',$grp);

        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $email = $user['email'];
        $ob_no_ce = 1;
        if($grp == 'Career Explorer')
        {
            //echo $ob_no_ce."<br>";
            redirect(base_url().'/SpController/career_explorer_ce_test/'.$ob_no_ce); 
        }
        else if($grp == 'Positive Parenting')
        {
            redirect(base_url().'/SpController/positive_parenting_ce_test');
          
        }
        else if($grp == 'Career Excellence')
        {
            $ob_no_ce = 1;
            redirect(base_url().'/SpController/career_excellence_ce_test/'.$ob_no_ce);
            
        }
        else if($grp == 'Career Builder')
        {
            $ob_no_ce = 1;
            redirect(base_url().'/SpController/career_builder_ce_test/'.$ob_no_ce);
           
        }
        else if($grp == 'Overseas Companion')
        {
            $ob_no_ce = 1;
            redirect(base_url().'/SpController/overseas_companion_ce_test2/'.$ob_no_ce);
           
        }

        
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar'); 
        $this->load->view('sp/view_solutions_for_become_partner_counseler'); //replace with view_approve_code 
        $this->load->view('footer');
    }

    public function positive_parenting_ce_test()
    {
            $code = 'ppct';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            $where = "email='$email' and code='$code' and solution='ppct'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $this->db->where('Modified_Nature','T1');
            $count = $this->db->get('partner_counselor_parenting')->num_rows();
            $mod = fmod($count,5);
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            if($mod==0)
            {
                $count_value = $count - 4;
            }
            
            $qlist['q'] = $this->Sp_model->ppct_question($num);
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            
            
            if(isset($_POST['saveBtn']))
            {
                 
               if($num!=$count_value)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                     $this->form_validation->set_rules('radio4','Fourth Question','required');
                     $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'ppct',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/positive_parenting_ce_test');
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/positive_parenting_ce_test');
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'ppct',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='Positive Parenting'";
                        $this->db->where($where);
                        $this->db->set('status','Cc');
                        $this->db->update('partner_counselor_status');
                        redirect(base_url().'SpController/ce_score_calculation/ppct/T1'); 
                        // $this->session->set_flashdata('msg','Assessment Compeleted Please Wait for Report');
                        // redirect(base_url().'SpController/become_partner_counselor');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/positive_parenting_ce_test');
                    }
                    
                   
                }
                
            }
    }

    public function career_explorer_ce_test($ob_no)
    {
        
            $ob = 'C'.$ob_no;
            // echo "<h1>career_explorer_ce_test </h1>";
            $code = 'cect';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];

            $where2 = "email='$email' and grp='Career Explorer'";
            $this->db->where($where2);
            $r = $this->db->get('partner_counselor_status')->row();
            if($r->ce_nature!='')
            {
                $ob = $r->ce_nature;
               
            }
            else
            {
                $where2 = "email='$email' and grp='Career Explorer'";
               $this->db->where($where2);
               $this->db->set('ce_nature',$ob);
               $this->db->update('partner_counselor_status'); 
            }
            
            $this->db->where('Modified_Nature',$ob);
            $count = $this->db->get('partner_counselor_career_explorer')->num_rows();
            // echo $count;
            $mod = fmod($count,5);
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            if($mod==0)
            {
                $count_value = $count - 4;
            }
            $where = "email='$email' and code='$code' and solution='cect'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Sp_model->cect_question($ob,$num);
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=$count_value)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cect',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/career_explorer_ce_test/'.$ob_no);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_explorer_ce_test/'.$ob_no);
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    // $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cect',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='Career Explorer'";
                        $this->db->where($where);
                        $this->db->set('status','CRp');
                        $this->db->update('partner_counselor_status');
                        
                        redirect(base_url().'SpController/ce_score_calculation/cect/'.$ob); 
                        // $this->session->set_flashdata('msg','Assessment Compeleted Please Wait for Report');
                        // redirect(base_url().'SpController/become_partner_counselor');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_explorer_ce_test/'.$ob_no);
                    }
                    
                   
                }
                
            }
    }
    
    
    public function overseas_companion_ce_test($ob_no)
    {
        
            $ob = 'C'.$ob_no;
            // echo "<h1>career_explorer_ce_test </h1>";
            $code = 'cect';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];

            $where2 = "email='$email' and grp='Career Explorer'";
            $this->db->where($where2);
            $r = $this->db->get('partner_counselor_status')->row();
            if($r->ce_nature!='')
            {
                $ob = $r->ce_nature;
               
            }
            else
            {
                $where2 = "email='$email' and grp='Career Explorer'";
               $this->db->where($where2);
               $this->db->set('ce_nature',$ob);
               $this->db->update('partner_counselor_status'); 
            }
            
            $this->db->where('Modified_Nature',$ob);
            $count = $this->db->get('partner_counselor_career_explorer')->num_rows();
            // echo $count;
            $mod = fmod($count,5);
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            if($mod==0)
            {
                $count_value = $count - 4;
            }
            $where = "email='$email' and code='$code' and solution='cect'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Sp_model->cect_question($ob,$num);
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=$count_value)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cect',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/career_explorer_ce_test/'.$ob_no);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_explorer_ce_test/'.$ob_no);
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    // $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cect',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='Career Explorer'";
                        $this->db->where($where);
                        $this->db->set('status','CRp');
                        $this->db->update('partner_counselor_status');
                        
                        redirect(base_url().'SpController/ce_score_calculation/cect/'.$ob); 
                        // $this->session->set_flashdata('msg','Assessment Compeleted Please Wait for Report');
                        // redirect(base_url().'SpController/become_partner_counselor');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_explorer_ce_test/'.$ob_no);
                    }
                    
                   
                }
                
            }
    }

    public function career_excellence_ce_test($ob_no)
    {
        
            $ob = 'C'.$ob_no;
            
            $code = 'cexct';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];

            $where2 = "email='$email' and grp='Career Excellence'";
            $this->db->where($where2);
            $r = $this->db->get('partner_counselor_status')->row();
            if($r->ce_nature!='')
            {
                $ob = $r->ce_nature;               
            }
            else
            {
                $where2 = "email='$email' and grp='Career Excellence'";
               $this->db->where($where2);
               $this->db->set('ce_nature',$ob);
               $this->db->update('partner_counselor_status'); 
            }
            
            $this->db->where('Modified_Nature',$ob);
            $count = $this->db->get('partner_counselor_cmb')->num_rows();
            // echo $count;
           
            $mod = fmod($count,5);
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            if($mod==0)
            {
                $count_value = $count - 4;
            }
            $where = "email='$email' and code='$code' and solution='cexct'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Sp_model->cexct_question($ob,$num);
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=$count_value)
                {
                    // echo "<h1> num != count_value</h1>";
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cexct',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/career_excellence_ce_test/'.$ob_no);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_excellence_ce_test/'.$ob_no);
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    // $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cexct',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='Career Excellence'";
                        $this->db->where($where);
                        $this->db->set('status','CRp');
                        $this->db->update('partner_counselor_status');
                        
                        redirect(base_url().'SpController/ce_score_calculation/cexct/'.$ob); 
                        // $this->session->set_flashdata('msg','Assessment Compeleted Please Wait for Report');
                        // redirect(base_url().'SpController/become_partner_counselor');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_excellence_ce_test/'.$ob_no);
                    }
                    
                   
                }
                
            }
    }

    public function career_builder_ce_test($ob_no)
    {
        
            $ob = 'C'.$ob_no;
            
            $code = 'cbct';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];

            $where2 = "email='$email' and grp='Career Builder'";
            $this->db->where($where2);
            $r = $this->db->get('partner_counselor_status')->row();
            if($r->ce_nature!='')
            {
                $ob = $r->ce_nature;
               
            }
            else
            {
                $where2 = "email='$email' and grp='Career Builder'";
               $this->db->where($where2);
               $this->db->set('ce_nature',$ob);
               $this->db->update('partner_counselor_status'); 
            }
            
            $this->db->where('Modified_Nature',$ob);
            $count = $this->db->get('partner_counselor_cmb')->num_rows();
            // echo $count;
            $mod = fmod($count,5);
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            if($mod==0)
            {
                $count_value = $count - 4;
            }
            $where = "email='$email' and code='$code' and solution='cbct'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            $qlist['q'] = $this->Sp_model->cexct_question($ob,$num);
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=$count_value)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cbct',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/career_builder_ce_test/'.$ob_no);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_builder_ce_test/'.$ob_no);
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    // $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>'cbct',
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='Career Builder'";
                        $this->db->where($where);
                        $this->db->set('status','CRp');
                        $this->db->update('partner_counselor_status');
                        
                        redirect(base_url().'SpController/ce_score_calculation/cbct/'.$ob); 
                        // $this->session->set_flashdata('msg','Assessment Compeleted Please Wait for Report');
                        // redirect(base_url().'SpController/become_partner_counselor');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/career_builder_ce_test/'.$ob_no);
                    }
                    
                   
                }
                
            }
    }
    
    //overseas companion certification start
    public function overseas_companion_ce_test2($ob_no)
    {
        
            $ob = 'C'.$ob_no;
            $grp='Overseas Companion';
            $tbl_exam='partner_counselor_oc_cer';
            $solution='occxt';
            $code = 'occxt';
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];

            $where2 = "email='$email' and grp='$grp'";
            $this->db->where($where2);
            $r = $this->db->get('partner_counselor_status')->row();
            if($r->ce_nature!='')
            {
                $ob = $r->ce_nature;
               
            }
            else
            {
                $where2 = "email='$email' and grp='$grp'";
                $this->db->where($where2);
                $this->db->set('ce_nature',$ob);
                $this->db->update('partner_counselor_status'); 
            }
            
            $this->db->where('Modified_Nature',$ob);
            $count = $this->db->get($tbl_exam)->num_rows();
            //echo "count".$count."<br>";
            // echo $count;
            $mod = fmod($count,5);
            echo $mod."<br>";
            $count_value = $count - $mod;
            $count_value = $count_value + 1;
            //echo $count_value."<br>";
            if($mod==0)
            {
                $count_value = $count - 4;
            }
            //echo $count_value."<br>";
            $where = "email='$email' and code='$code' and solution='$solution'";
            $this->db->where($where);
            $qno = $this->db->get('ppe_part1_test_details')->num_rows();
            //echo $qno."<br>";
            if($qno==0)
            {
                $num = '1';
            }
            else
            {
                $this->db->where($where);
                $qno = $this->db->limit(1)->order_by('id','desc')->get('ppe_part1_test_details'); 
                foreach($qno->result() as $qno)
                {
                    $qno = $qno->qno;
                    $num = $qno + 1;
                }
            }
            echo $num."<br>";
            //die;
            $qlist['q'] = $this->Sp_model->ocoxt_question($ob,$num,$solution);
            //echo "<pre>";
            //print_r($qlist);die();
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/ppot',$qlist); 
            $this->load->view('footer'); 
            if(isset($_POST['saveBtn']))
            {
                if($num!=$count_value)
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    $this->form_validation->set_rules('radio2','Second Question','required');
                    $this->form_validation->set_rules('radio3','Third Question','required');
                    $this->form_validation->set_rules('radio4','Fourth Question','required');
                    $this->form_validation->set_rules('radio5','Fifth Question','required');
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>$solution,
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        redirect(base_url().'SpController/overseas_companion_ce_test2/'.$ob_no);
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/overseas_companion_ce_test2/'.$ob_no);
                    }
                    

                }
                else
                {
                    $this->form_validation->set_rules('radio1','First Question','required');
                    // $this->form_validation->set_rules('radio2','Second Question','required');
                   
                    if($this->form_validation->run()==true)
                    {
                        $i=1;
                        foreach($qlist['q']->result() as $q)
                        {   
                            
                                $ans = 'radio'.$i;
                                $formArray = Array(
                                    'email'=>$email,
                                    'qno'=>$q->org_qno,
                                    'solution'=>$solution,
                                    'code'=>$code,
                                    'ans'=>$_POST[$ans]
                                );
                                $this->db->insert('ppe_part1_test_details',$formArray);
                            $i++;
                            
                        }
                        //that code used after fourth asssessment 
                        // $this->Base_model->update_code_status($code);
                        $where = "email='$email' and grp='$grp'";
                        $this->db->where($where);
                        $this->db->set('status','CRp');
                        $this->db->update('partner_counselor_status');
                        
                        redirect(base_url().'SpController/overseas_companion_ce_test2/occxt/'.$ob); 
                        // $this->session->set_flashdata('msg','Assessment Compeleted Please Wait for Report');
                        // redirect(base_url().'SpController/become_partner_counselor');     
                    }
                    else
                    {
                        $this->session->set_flashdata('msg',validation_errors());
                        redirect(base_url().'SpController/overseas_companion_ce_test2/'.$ob_no);
                    }
                    
                   
                }
                
            }
    }

    // Add by new function by Danish #START here.
      public function page_change_logo()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            

            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data); 
            $this->load->view('sp/landing_page/page_change_logo',$data); 
            $this->load->view('footer'); 
           
            
            if(isset($_POST['savebtn']))
            {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'jpg|png';
                $config['max_size'] = 100000;
                $config['max_width'] = 900;
                $config['max_height'] = 512;
                $dname = explode(".", $_FILES['img']['name']);
                $ext = end($dname);
                $new_name = time().'.'.$ext;
                

                $config['file_name'] = $new_name;
                $this->load->library('upload', $config);
                if (! $this->upload->do_upload('img'))
                {
                   
                        $error = array('error' => $this->upload->display_errors());
                        foreach($error as $error)
                        {
                            $this->session->set_flashdata('msg',$error);
                            redirect('/SpController/page_change_logo','refresh');
                        }
                       
                }
                else
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('r_email',$uid);
                    $this->db->set('logo','uploads/'.$new_name);
                    $this->db->update('reseller_homepage');
                    
                    $this->session->set_flashdata('msg2','Saved Successfully');
                    redirect('/SpController/page_change_logo','refresh');
                }
            }
        }

        public function page_change_banner()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            
            $this->load->view('navbar3',$data);
            $this->load->view('sp/sidebar',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/landing_page/change_banner_details',$data); 
            $this->load->view('footer'); 
            
            if(isset($_POST['savebtn']))
            {
                $this->form_validation->set_rules('ban_head','Banner Heading','required');
                $this->form_validation->set_rules('ban_msg','Banner Message','required');
                $this->form_validation->set_rules('about','About','required');
               
                if($this->form_validation->run() == true)
                {
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'jpg|png';
                    $config['max_size'] = 100000;
                    $config['max_width'] = 1903;
                    $config['max_height'] = 882;
                    $dname = explode(".", $_FILES['ban_img']['name']);
                    $ext = end($dname);
                    $new_name = time().'.'.$ext;
                   
                    
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('ban_img'))
                    {
                            $error = array('error' => $this->upload->display_errors());
                            foreach($error as $error)
                            {
                                $this->session->set_flashdata('msg',$error);
                                redirect('/SpController/page_change_banner','refresh');
                            }
                        
                    }
                    else
                    {
                        $user = $this->session->userdata('user');
                        $data['user'] = $user;
                        $uid=$user['email'];
                        $this->db->where('r_email',$uid);
                        $this->db->set('banner_img','uploads/'.$new_name);
                        $this->db->set('banner_head',$_POST['ban_head']);
                        $this->db->set('banner_msg',$_POST['ban_msg']);
                        $this->db->set('about_us',$_POST['about']);
                        $this->db->update('reseller_homepage');
        
                        $this->session->set_flashdata('msg2','Detail Updated');
                        redirect('/SpController/page_change_banner','refresh');
                    }              
                        
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/SpController/page_change_banner','refresh');
                }
             
            }
        }

        public function company_detail()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data); 
            $this->load->view('sp/landing_page/company_detail',$data); 
            $this->load->view('footer'); 
            
            if(isset($_POST['savebtn']))
            {
                
                
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('r_email',$uid);
                    $this->db->set('company_head1',$_POST['head1']);
                    $this->db->set('company_head2',$_POST['head2']);
                    $this->db->set('company_head3',$_POST['head3']);
                    $this->db->set('company_detail1',$_POST['detail1']);
                    $this->db->set('company_detail2',$_POST['detail2']);
                    $this->db->set('company_detail3',$_POST['detail3']);
                    $this->db->update('reseller_homepage');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/SpController/company_detail','refresh');
                    
                
            }
        }

        public function change_contact_details()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            

            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data); 
            $this->load->view('sp/landing_page/change_contact_details',$data); 
            $this->load->view('footer'); 
            
            if(isset($_POST['savebtn']))
            {
                $this->form_validation->set_rules('email','Email','required|valid_email');
                $this->form_validation->set_rules('addr','Address','required');
                $this->form_validation->set_rules('mobile','Mobile','required|min_length[10]|max_length[10]');
               
                if($this->form_validation->run() == true)
                {
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('r_email',$uid);
                    $this->db->set('email',$_POST['email']);
                    $this->db->set('address',$_POST['addr']);
                   
                    $this->db->set('contact',$_POST['mobile']);
                    $this->db->update('reseller_homepage');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/SpController/change_contact_details','refresh');
                    
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field is Required");
                    redirect('/SpController/change_contact_details','refresh');
                }
            }
        }

        public function change_social_link()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data); 
            $this->load->view('sp/landing_page/change_social_link',$data); 
            $this->load->view('footer'); 
            
            if(isset($_POST['savebtn']))
            {
                
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;
                    $uid=$user['email'];
                    $this->db->where('r_email',$uid);
                    $this->db->set('fb_url',$_POST['fb_link']);
                    $this->db->set('twt_url',$_POST['twt_link']);
                    $this->db->set('link_url',$_POST['link_link']);
                    $this->db->set('insta_url',$_POST['insta_link']);
                    $this->db->set('ftr',$_POST['footer']);
                    $this->db->update('reseller_homepage');    
                    
                    $this->session->set_flashdata("msg2","Saved Successfully");
                    redirect('/SpController/change_social_link','refresh');
                 
            }
        }

        public function footer_field()
        {
            // $this->load->model('User_model');
            //     if($this->User_model->authorized()==false)
            //     {
            //         $this->session->set_flashdata('msg','You are not authorized to access this section');
            //         redirect(base_url().'/UserController/login');
            //     }
            $data =$this->initializer();
                $user = $this->session->userdata('user');
                $data['user'] = $user;
                $email=$user['email'];
               
                $this->load->view('navbar3',$data);
                $this->load->view('sp/sidebar',$data); 
                $this->load->view('sp/landing_page/footer_field',$data); 
                $this->load->view('footer'); 

                if(isset($_POST['updatebtn']))
                {
                    $this->form_validation->set_rules('Todays_Day','Days','required');
                    $this->form_validation->set_rules('status','Staus','required');
                    if($this->form_validation->run()==true)
                    {
                        if($_POST['status']=='open')
                        {
                            if($_POST['startTime']!='' && $_POST['endTime']!='')
                            {
                                $formArray = Array(
                                    'email'=>$email,
                                    'day'=>$_POST['Todays_Day'],
                                    'status'=>$_POST['status'],
                                    'start_tm'=>$_POST['startTime'],
                                    'end_tm'=>$_POST['endTime']
                                );
                            }
                            else
                            {
                                $this->session->set_flashdata('msg','Start Time or End Time both are required');
                                redirect(base_url().'/SpController/footer_field');
                            }
                        }
                        else
                        {
                            $formArray = Array(
                                'email'=>$email,
                                'day'=>$_POST['Todays_Day'],
                                'status'=>$_POST['status'],
                                'start_tm'=>$_POST['startTime'],
                                'end_tm'=>$_POST['endTime']
                            );
                        }

                        $this->db->select("id,email");
                        $q_check = $this->db->get_where("day_description",["email"=>$email,"day"=>$_POST['Todays_Day']]);
                        if($q_check->num_rows() > 0){                            
                            $this->db->set("start_tm",$_POST['startTime']);
                            $this->db->set("end_tm",$_POST['endTime']);
                            $this->db->set("status",$_POST['status']);
                            $this->db->where("id",$q_check->row()->id);
                            $this->db->update("day_description");
                            $this->session->set_flashdata('msg2','Update');
                            redirect(base_url().'/SpController/footer_field');
                        }else{
                            $this->db->insert('day_description',$formArray);
                            $this->session->set_flashdata('msg2','Saved');
                            redirect(base_url().'/SpController/footer_field');
                        }
                    }
                    
                }
                
        }

        public function view_users_list()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            

            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/user_management/view_users',$data); 
            $this->load->view('footer');
            
        }

        public function approve_user_code()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];     
            // $data['iam'] = $user['iam'];       

            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/user_management/unapprove_user_code',$data); 
            $this->load->view('footer');          
                      
        }

        public function user_buyer()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // $this->load->model('Base_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            
            $code['s'] = $this->User_model->code_list($user['email']);

            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/user_management/view_approve_user_code',$code); 
            $this->load->view('footer');  

        }

        public function purchase_code()
        {   //echo "purchase";die();
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
           
            $solution['s'] = $this->User_model->solutions_list(); 
            // echo "<pre>";
            // print_r($solution);
            // echo "</pre>";
            // die;           
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/code_management/purchasecode',$solution); 
            $this->load->view('footer');
        }

        public function save_request_code()
        {   //echo "save";die();
            if(isset($_POST['purchase']))
            {   
                error_reporting(0);
                $count = $this->db->get('solutions')->num_rows();
                for($i=0;$i<=$count;$i++)
                {
                    if($i==0)
                    {
                        $op[] = '';
                        //nothing
                    }
                    else
                    {
                        $code = 'ccb'.$i;
                        $op[] = $_POST[$code];
                        if($op[$i]!='')
                        {
                            $value = '1';
                        }
                    }
                    
                }
                $user = $this->session->userdata('user');
                $email = $user['email'];
                $status = 'pending';
                // $id = 'ccb1';
                // $op[] = $_POST[$id]; 
                // $op[] = $_POST['ccb2'];
                // $op[] = $_POST['ccb3'];
                // $op[] = $_POST['ccb4'];
                if($value!='1')
                {
                    echo $value;
                    $this->session->set_flashdata("msg","Please Select One of these");
                    redirect('/SpController/purchase_code','refresh');
                }
                else
                {
                    $this->load->helper('date');
                    $datestring = '%d / %m / %Y - %h:%i %a';
                    $time = time();
                    $dt = mdate($datestring, $time);
                    for($i=1;$i<=$count;$i++)
                    {
                        $code_id = $op[$i];
                        if($code_id!='')
                        {
                                $rq = 'cb'.$i;
                                $cb = $_POST[$rq];
                                
                                if($cb=='')
                                {
                                    $rqst = '';
                                    
                                }
                                else
                                {
                                    $rqst = 'Yes';
                                }
                                $data = array(
                                    'dt' => $dt,
                                    'code' => $code_id,
                                    'email' => $email,
                                    'request'=> $rqst,
                                    'status' => $status
                                );
                                
                                $this->load->model('User_model');
                                $this->User_model->insert_request_code($data);
                        }
                          
                    }
                    $this->session->set_flashdata("msg","Purchase Request has been sent to admin please wait for approval");
                    redirect('/SpController/purchase_code','refresh');
                }
                    
            }
        }
        

        public function view_reseller_code()
        {
            // $this->load->model('User_model');
            // $this->load->model('Admin_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
            $solution['s'] = $this->User_model->solutions_list();
            
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/code_management/view_code_list',$solution,$data);
            $this->load->view('footer');
        }

        //show unused code
        public function unused_code()
        {
            // $this->load->model('User_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $email = $user['email'];
           
            $unused_code_list['h'] = $this->User_model->get_unused_code($email);

            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/code_management/unused_code_list',$unused_code_list); 
            $this->load->view('footer');
            
        }

        public function domain_request()
        {
            $this->load->library('form_validation');
            $this->load->model('User_model');
            $this->load->model('Admin_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
         
            $this->load->view('navbar3',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar');
            $this->load->view('sp/landing_page/domain_request');
            $this->load->view('footer');

            if (isset($_POST['request_btn']))
            {
                
                $this->form_validation->set_rules('domain_name','Domain Name','required');
               
                if($this->form_validation->run() == true)
                {
                    $domain_name= $_POST['domain_name'];
                    $count = $this->User_model->check_duplicacy($domain_name);
                    if($count==0)
                    {
                        $formArray = array(
                            'reseller_id' => $_POST['domain_name'],
                            'r_email' => $user['email'],
                            'logo' => '',
                            'banner_img'=> '',
                            'banner_head' => '',
                            'banner_msg' => '',
                            'about_us' => '',
                            'email' => '',
                            'address' => '',
                            'contact' => '',
                            'fb_url' => '',
                            'twt_url' => '',
                            'ftr' => '',
                            'status' => '0'
                        );
                        $this->User_model->homepage_insertion($formArray);
                       
                        $this->session->set_flashdata("msg2","Saved Successfully");
                        redirect('/SpController/domain_request','refresh');

                    }
                    else
                    {
                        $this->session->set_flashdata("msg","This Domain Name is Already Exist");
                        redirect('/SpController/domain_request','refresh');

                    }
                    
                    
                }
                else{
                    $this->session->set_flashdata("msg",form_error('domain_name'));
                    redirect('/SpController/domain_request','refresh');
                }
            
            }
        }

    public function boards()
    {
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;            
            $this->load->view('navbar',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/boards'); 
            $this->load->view('footer');
    }

    public function career_paths()
    {
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            $data['user'] = $user;            
            $this->load->view('navbar',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/career-paths'); 
            $this->load->view('footer');
    }


    public function vocational_training(){
            $data_view = [];
            // $this->load->model('User_model');
            // $this->load->model('Base_model');
            $this->load->model('Sp_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');           
            $data['user'] = $user;

            $data_view['vocational_info'] = $this->get_vocational_info("SHORT_VIEW",$user['id']);
            $this->load->view('navbar',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data); 
            $this->load->view('sp/vocational_training',$data_view); 
            $this->load->view('footer');
    }

   

    public function vocational_training_add()
    {
            $data_view = [];
            // $this->load->model('User_model');
            // $this->load->model('Base_model');
            $this->load->model('Sp_model');
            // if($this->User_model->authorized()==false)
            // {
            //     $this->session->set_flashdata('msg','You are not authorized to access this section');
            //     redirect(base_url().'/UserController/login');
            // }
            $data =$this->initializer();
            $user = $this->session->userdata('user');
           
            $data['user'] = $user;
            if(isset($_POST['btn_vocational']))
            {
                $this->form_validation->set_rules('vocational_name','Name','required');
                $this->form_validation->set_rules('vocational_description','Description','required');
                if($this->form_validation->run())
                {
                    //image Upload #start.
                    $upload_path="uploads/vocational_training/"; 
                    $new_name = time() . '-' . $_FILES["up_file"]['name'];
                    $config = array(
                    'upload_path' => $upload_path,
                    'file_name'=>$new_name,
                    'allowed_types' => "zip",
                    'max_size' => "2048000", //2.048mb
                    'max_height' => "0",
                    'max_width' => "0"
                    );
                    
                    $this->load->library('upload', $config);                    
                    if($this->upload->do_upload('up_file'))
                    { 
                        $imageDetailArray = $this->upload->data();
                        if(!empty($imageDetailArray['image_type'])){
                            $img_type =  $imageDetailArray['image_type'];
                        }else{
                            $img_type =  $imageDetailArray['file_type'];
                        }

                        $insert_data = [
                                        "sp_id"=> $user['id'],
                                        "training_name"=> $this->input->post('vocational_name'),
                                        "training_desc"=> $this->input->post('vocational_description'),
                                        "training_file_loc"=> $imageDetailArray['file_name'],
                                        "file_mine"=> $img_type
                                        ];

                        $this->db->insert("vocational_training",$insert_data);
                        $last_id = $this->db->insert_id();

                        // sub section #start
                            $section_name = $this->input->post("training_section_name");
                            $section_desc = $this->input->post("training_section_desc");
                            $arr_size = sizeof($section_name);
                            $arr_section_data = [];
                            for($i = 0;$i < $arr_size;$i++){
                                $arr_section_data[] = [
                                    "training_id"=>$last_id,
                                    "section_name"=> $section_name[$i],
                                    "section_desc"=> $section_desc[$i]
                                ]; 
                            }

                            $this->db->insert_batch("vocational_training_sections",$arr_section_data);
                            if($this->db->affected_rows() > 0){
                                $data_view['check_inset'] = "OK";
                            }else{
                                $data_view['check_inset'] = "ERROR";
                            }
                        // sub section #end
                    }
                    else
                    {
                        $data_view['imageError'] =  $this->upload->display_errors();                        
                    }
                // image Upload #end.
                }
            }       
            $this->load->view('navbar',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data); 
            $this->load->view('sp/vocational_training_add',$data_view); 
            $this->load->view('footer');
    }

    public function vocational_training_edit($id,$action_path = null){
        $data_view = [];
        if(empty($id)){
            show_404();
        }
        $this->load->model('User_model');
        $this->load->model('Base_model');
        $this->load->model('Sp_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');           
        $data['user'] = $user;

        if($action_path == "VOC_TRAI"){
            if(isset($_POST['btn_vocational']))
            {
                $this->form_validation->set_rules('vocational_name','Name','required');
                $this->form_validation->set_rules('vocational_description','Description','required');
                if($this->form_validation->run())
                {
                    $this->db->set("training_name",$this->input->post("vocational_name",true));
                    $this->db->set("training_desc",$this->input->post("vocational_description",true));
                    $this->db->where(["id"=>$id]);
                    $this->db->limit(1);
                    $this->db->update("vocational_training");
                    if($this->db->affected_rows() > 0){
                        $data_view['VOC_TRAI_MSG'] = "OK";
                    }else{
                        $data_view['VOC_TRAI_MSG'] = "ERROR";
                    }
                }
            }
        }

        if($action_path == "SUB_SECTION"){
            if(isset($_POST['btn_sub_section']))
            {
                
                $this->form_validation->set_rules('sub_section_name','Name','required');
                $this->form_validation->set_rules('sub_section_description','Description','required');
                if($this->form_validation->run())
                {
                    $section_id = $this->input->get("sno");
                    if(!empty($section_id)){
                        $this->db->set("section_name",$this->input->post("sub_section_name",true));
                        $this->db->set("section_desc",$this->input->post("sub_section_description",true));
                        $this->db->where(["id"=>$section_id]);
                        $this->db->limit(1);
                        $this->db->update("vocational_training_sections");
                        if($this->db->affected_rows() > 0){
                            $data_view['SUB_SECTION_MSG'] = "OK";
                        }else{
                            $data_view['SUB_SECTION_MSG'] = "ERROR";
                        }
                    }   
                }
            }
        }

        if($action_path == "ADD_SUB_SECTION"){
            if(isset($_POST['btn_add_more_sectoin']))
            {
                $add_more_section_name = $this->input->post("add_sub_section_name");
                $add_more_section_desc = $this->input->post("add_sub_section_description");
                if(!empty($add_more_section_name)){
                    $arr_size = sizeof($add_more_section_name);
                    $arr_section_data = [];
                    for($i = 0;$i < $arr_size;$i++){
                        $arr_section_data[] = [
                            "training_id"=>$id,
                            "section_name"=> $add_more_section_name[$i],
                            "section_desc"=> $add_more_section_desc[$i]
                        ]; 
                    }

                    $this->db->insert_batch("vocational_training_sections",$arr_section_data);
                    if($this->db->affected_rows() > 0){
                        $data_view['ADD_SUB_SECTION_MSG'] = "OK";
                    }else{
                        $data_view['ADD_SUB_SECTION_MSG'] = "ERROR";
                    }
                }
            }
        }

        if($action_path == "CONTENT_UPLOAD"){
            if(!empty($_FILES["content_file"]['name']))
            {
                //image Upload #start.
                $upload_path="uploads/vocational_training/"; 
                $new_name = time() . '-' . $_FILES["content_file"]['name'];
                $config = array(
                'upload_path' => $upload_path,
                'file_name'=>$new_name,
                'allowed_types' => "zip",
                'max_size' => "2048000", //2.048mb
                'max_height' => "0",
                'max_width' => "0"
                );
                
                $this->load->library('upload', $config);                    
                if($this->upload->do_upload('content_file'))
                { 
                    $arr_file_upload = $this->upload->data();                    
                    if(!empty($arr_file_upload['image_type'])){
                        $img_type =  $arr_file_upload['image_type'];
                    }else{
                        $img_type =  $arr_file_upload['file_type'];
                    }
                    $this->db->set("training_file_loc",$arr_file_upload['file_name']);
                    $this->db->set("file_mine",$img_type);
                    $this->db->where(["id"=>$id]);
                    $this->db->limit(1);
                    $this->db->update("vocational_training");
                    if($this->db->affected_rows() > 0){
                      $old_file_name = "uploads/vocational_training/".$this->input->post("old_file_name");
                      if (is_file($old_file_name)) {
                            unlink($old_file_name);
                        }                        
                        $data_view['CONTENT_UPLOAD_MSG'] = "OK";
                    }else{
                        $data_view['CONTENT_UPLOAD_MSG'] = "ERROR";
                    }
                }else{
                    $data_view['imageError'] =  $this->upload->display_errors();
                }
            }
        }
        
        $data_view['vocational_info'] = $this->get_vocational_info("FULL_SINGLE_VIEW",$id);
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar');
        $this->load->view('sp/vocational_training_edit',$data_view); 
        $this->load->view('footer');
    }

    public function vocational_training_details($id)
    {
            $data_view = [];
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
           
            $data['user'] = $user;
            $data_view['vocational_info'] = $this->get_vocational_info("FULL_SINGLE_VIEW",$id);
            $this->load->view('navbar',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/vocational_training_details',$data_view); 
            $this->load->view('footer');
    }

    function delete_itme($id,$action = null){
        $json_msg = [];
        switch($action){
            case "REMOVE_SUB_SECTION":
                $this->db->limit(1);
                $this->db->delete("vocational_training_sections",["id"=>$id]);
                if($this->db->affected_rows() > 0){
                    $json_msg = ["MSG"=>"OK"];
                }else{
                    $json_msg = ["MSG"=>"ERROR"];
                }
            break;
            case "REMOVE_VOCATIONAL":
                $this->db->select("training_file_loc");
                $this->db->where(["id"=>$id]);
                $this->db->limit(1);
                $file_name = $this->db->get("vocational_training")->row()->training_file_loc;

                $this->db->delete("vocational_training",["id"=>$id]);
                $this->db->limit(1);
                $this->db->delete("vocational_training_sections",["training_id"=>$id]);
                if($this->db->affected_rows() > 0){
                    $file_path = "uploads/vocational_training/$file_name";
                    if (is_file($file_path)) {
                          unlink($file_path);
                      }  
                    $json_msg = ["MSG"=>"OK"];
                }else{
                    $json_msg = ["MSG"=>"ERROR"];
                }
            break;
            case "REMOVE_JOB":
                $this->db->limit(1);
                $this->db->delete("placement_jobs",["id"=>$id]);
                if($this->db->affected_rows() > 0){
                    $json_msg = ["MSG"=>"OK"];
                }else{
                    $json_msg = ["MSG"=>"ERROR"];
                }
            break;
        }
        echo json_encode($json_msg);
    }

    function ajax_work($id = null,$action = null){
        $this->load->model('Sp_model');
        $json_msg = [];
        switch($action){
            case "CERTIFICATION_REQUEST":
                $vocational_id = $this->input->post("vid");
                $request_type = $this->input->post("req_type");
                $old_file_name = $this->input->post("file_name");               
                
                $upload_path="uploads/vocational_training/"; 
                $new_name = time() . '-certification-' . $_FILES["file_content"]['name'];
                $config = array(
                'upload_path' => $upload_path,
                'file_name'=>$new_name,
                'allowed_types' => "zip",
                'max_size' => "2048000", //2.048mb
                'max_height' => "0",
                'max_width' => "0"
                );

                $this->load->library('upload', $config);                    
                if($this->upload->do_upload('file_content'))
                { 
                    $arr_file_upload = $this->upload->data();
                    $this->db->set("certification_status",$request_type);
                    $this->db->set("certification_file_loc",$arr_file_upload['file_name']);
                    $this->db->where(["id"=>$vocational_id]);
                    $this->db->limit(1);
                    $this->db->update("vocational_training");
                    if($this->db->affected_rows() > 0){
                        if(!empty($old_file_name)){
                            $old_file_name = "uploads/vocational_training/".$old_file_name;
                            if (is_file($old_file_name)) {
                                unlink($old_file_name);
                            }
                        }
                        $json_msg["MSG"] = "OK";
                    }else{
                        $json_msg["MSG"] = "ERROR";
                    }
                }else{
                    $json_msg["MSG"] = "ERROR_IMG";
                    $json_msg["ERROR_IMG_DESC"] =  $this->upload->display_errors();
                }
            break;
            case "TRAINING_REQUEST":
                $training_status_type = $this->input->get("req",true);
                if(!empty($training_status_type)){
                    $this->db->set("training_status",$training_status_type);
                    $this->db->where(["id"=>$id]);
                    $this->db->limit(1);
                    $this->db->update("vocational_training");
                    if($this->db->affected_rows() > 0){
                        $json_msg = ["MSG"=>"OK"];
                    }else{
                        $json_msg = ["MSG"=>"ERROR"];
                    }
                }else{
                    $json_msg = ["MSG"=>"EMPTY"];
                }
            break;
            case "PARTICIPANT_REQUEST":
                $status_type = $this->input->get("req_type",true);
                if(!empty($status_type)){
                    $this->db->set("training_status",$status_type);
                    $this->db->where(["id"=>$id]);
                    $this->db->limit(1);
                    $this->db->update("vocational_training_apply_user");
                    if($this->db->affected_rows() > 0){
                        $json_msg = ["MSG"=>"OK"];
                    }else{
                        $json_msg = ["MSG"=>"ERROR"];
                    }
                }else{
                    $json_msg = ["MSG"=>"EMPTY"];
                }
            break;
            case "JOB_STATUS":
                $check = null;
                $check = $this->Sp_model->update_status($action,$id);
                if($check){
                    $json_msg = ["MSG"=>"OK"];
                }else{
                    $json_msg = ["MSG"=>"ERROR"];
                }
            break;
            case "JOB_STATUS_REQUEST":
                $check = null;
                $check = $this->Sp_model->update_status($action,$id);
                if($check){
                    $json_msg = ["MSG"=>"OK"];
                }else{
                    $json_msg = ["MSG"=>"ERROR"];
                }
            break;
            case "SPECIALIZATION":
                $check = $this->Sp_model->get_job_data($action,$id);
                if($check){
                    $json_msg["MSG"] = "OK";
                    $json_msg["data"] = $check;
                }else{
                    $json_msg = ["MSG"=>"ERROR"];
                }
            break;
            case "FORWARD_DETAILS":
                $request_user_id = $this->input->post("rid");
                $forward_email_id = $this->input->post("email_id",true);
                if(!empty($request_user_id))
                {
                    if(!empty($forward_email_id))
                    {
                        $subject = "Forward user details for job";
                        $data_template["user_details"] = $this->Sp_model->get_job_data("JOB_REQUEST_USER",$request_user_id); 
                        $msg_body = $this->load->view("sp/mail_template/forward_user_details.php",$data_template,true);
                        if($this->Sp_model->send_on_email($forward_email_id,$subject,$msg_body)){
                            $json_msg["msg"] = "send_seccuss";                            
                        }else{
                            $json_msg["msg"] = "send_faild";
                        }
                    }else{
                        $json_msg["msg"] = "email_not_found";
                    }
                }else{
                    $json_msg["msg"] = "id_not_found";
                }
            break;
        }
        echo json_encode($json_msg);
    }

    function mail_template(){
        // $data['user_data'] = ["rid"=>9,"email_id"=>"abc@gmail.com"];
        $this->load->model("Sp_model");
        $data['user_details'] = $this->Sp_model->get_job_data("JOB_REQUEST_USER",9); 
        $this->load->view("sp/mail_template/forward_user_details.php",$data);
    }

    protected function get_vocational_info($check,$id){
        if($check == "SHORT_VIEW"){
            $this->db->select("*");
            $this->db->where(["sp_id"=>$id]);
            $this->db->order_by("id","desc");
            $q = $this->db->get("vocational_training");
            return $q->result();
        }

        if($check == "FULL_SINGLE_VIEW"){
            $data = [];
            $this->db->select("*");
            $this->db->where(["id"=>$id]);
            $q = $this->db->get("vocational_training");
            $data[] = ["training_info"=>$q->row()];

            $this->db->select("*");
            $this->db->where(["training_id"=>$id]);
            $q = $this->db->get("vocational_training_sections");
            $data[] =  ["section_info"=>$q->result()];

            $this->db->select("*");
            $this->db->where(["training_id"=>$id]);
            $this->db->order_by("id","desc");
            $this->db->order_by("remark_date","desc");
            $q = $this->db->get("vocational_training_remarks");
            $data[] = ["remark"=>$q->result()];

            return $data;
        }

        if($check == "PARTICIPANT_SINGLE_VIEW"){
            $this->db->select("a.id,a.user_email,b.fullname,a.apply_date,a.training_status");
            $this->db->where(["a.training_id"=>$id]);
            $this->db->order_by("a.id","desc");
            $this->db->order_by("a.apply_date");
            $this->db->join("user_details b","b.email = a.user_email","inner");
            $q = $this->db->get("vocational_training_apply_user a");
            return $q->result();
        }
    }

    public function participant_details($id)
    {
            $data_view = [];
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
           
            $data['user'] = $user;
            $data_view['participant_info'] = $this->get_vocational_info("PARTICIPANT_SINGLE_VIEW",$id);
            $this->load->view('navbar',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/participant_details',$data_view); 
            $this->load->view('footer');
    }

    //Jops #Start

    public function posts_job(){
        $data_view = [];
        $this->load->model('User_model');
        $this->load->model('Base_model');
        $this->load->model('Sp_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');           
        $data['user'] = $user;

        $data_view['short_data'] = $this->Sp_model->get_job_data("SHORT_VIEW",$user['id']);
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar'); 
        $this->load->view('sp/posts_job',$data_view); 
        $this->load->view('footer');
    }

    public function job_add(){
        $data_view = [];
        $this->load->model('User_model');
        $this->load->model('Base_model');
        $this->load->model('Sp_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');           
        $data['user'] = $user;

        if(isset($_POST['btn_job']))
        {
            $this->form_validation->set_rules('job_title','Job Title','required');
            $this->form_validation->set_rules('salary','CTC','required');
            $this->form_validation->set_rules('job_types','Job Types','required');
            // $this->form_validation->set_rules('job_locations','Job Locations','required');
            $this->form_validation->set_rules('posting_nature','Job Nature','required');
            $this->form_validation->set_rules('job_description','Job Description','required');
            $this->form_validation->set_rules('job_nature','Job Nature','required');
             if($this->form_validation->run())
            {
               
                $domain = $this->input->post("domain",true);
                $specialization_potion = $this->input->post("specialization[]",true);
                if(!empty($specialization_potion)){
                    $specialization_potion = implode(",",$specialization_potion);
                }
                $new_domain = $this->input->post("add_new_domain",true);
                if(!empty($new_domain)){
                    $this->db->insert("job_domain",["name"=>$new_domain]);
                    $domain = $this->db->insert_id();
                }
                $new_specialization = $this->input->post("add_new_specialization",true);
                if(!empty($new_domain)){
                    $specialization_v = explode(",",$new_specialization);
                    $s_v_count = sizeof($specialization_v);
                    $s_id = [];
                    for($i = 0; $i < $s_v_count; $i++){
                        $s_data = [
                                    'name'=>$specialization_v[$i],
                                    'job_domain_id'=>$domain
                                ];
                        $this->db->insert("job_specialization",$s_data);
                        $s_id[] = $this->db->insert_id();
                    }

                    $specialization_potion = implode(",",$s_id);
                }

                $arr_form_data = [
                    "sp_id"             => $user['id'],
                    "job_title"         => $this->input->post("job_title",true),
                    "salary"            => $this->input->post("salary",true),
                    "job_type"          => $this->input->post("job_types",true),
                    "job_locations"     => $this->input->post("job_locations",true),
                    "posting_nature"    => $this->input->post("posting_nature",true),
                    "job_description"   => $this->input->post("job_description",true),
                    "job_nature"        => $this->input->post("job_nature",true),
                    "domain"            => $domain,
                    "specialization"    => $specialization_potion
                ]; 

                $this->db->insert("placement_jobs",$arr_form_data);
                if($this->db->affected_rows() > 0){
                    $this->session->set_flashdata('check_inset','OK');
                    redirect(base_url().'SpController/posts_job');
                }else{
                    $this->session->set_flashdata('check_inset','ERROR');
                   redirect(base_url().'SpController/posts_job');                    
                }
            }
        }
        $data_view['job_domain'] = $this->Sp_model->get_job_data("ALL_JOB_DOMAIN");
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar'); 
        $this->load->view('sp/job_add',$data_view); 
        $this->load->view('footer');
    }

    public function job_edit($id){        
        $data_view = [];
        $this->load->model('User_model');
        $this->load->model('Base_model');
        $this->load->model('Sp_model');
        if($this->User_model->authorized()==false)
        {
            $this->session->set_flashdata('msg','You are not authorized to access this section');
            redirect(base_url().'/UserController/login');
        }
        $user = $this->session->userdata('user');           
        $data['user'] = $user;
        if(isset($_POST['btn_job_edit']))
        {
            $this->form_validation->set_rules('job_title','Job Title','required');
            $this->form_validation->set_rules('salary','Salary','required');
            $this->form_validation->set_rules('job_types','Job Types','required');
            // $this->form_validation->set_rules('job_locations','Job Locations','required');
            $this->form_validation->set_rules('posting_nature','Job Nature','required');
            $this->form_validation->set_rules('job_description','Job Description','required');
            if($this->form_validation->run())
            {
                $domain = $this->input->post("domain",true);
                $specialization_potion = $this->input->post("specialization[]",true);
                if(!empty($specialization_potion)){
                    $specialization_potion = implode(",",$specialization_potion);
                }
                $new_domain = $this->input->post("add_new_domain",true);
                if(!empty($new_domain)){
                    $this->db->insert("job_domain",["name"=>$new_domain]);
                    $domain = $this->db->insert_id();
                }
                $new_specialization = $this->input->post("add_new_specialization",true);
                if(!empty($new_domain)){
                    $specialization_v = explode(",",$new_specialization);
                    $s_v_count = sizeof($specialization_v);
                    $s_id = [];
                    for($i = 0; $i < $s_v_count; $i++){
                        $s_data = [
                                    'name'=>$specialization_v[$i],
                                    'job_domain_id'=>$domain
                                ];
                        $this->db->insert("job_specialization",$s_data);
                        $s_id[] = $this->db->insert_id();
                    }

                    $specialization_potion = implode(",",$s_id);
                }

                $arr_form_update = [
                    "job_title"         => $this->input->post("job_title",true),
                    "salary"            => $this->input->post("salary",true),
                    "job_type"          => $this->input->post("job_types",true),
                    "job_locations"     => $this->input->post("job_locations",true),
                    "posting_nature"    => $this->input->post("posting_nature",true),
                    "job_description"   => $this->input->post("job_description",true),
                    "domain"            => $domain,
                    "specialization"    => $specialization_potion,
                    "updated_on"        => date("Y-m-d h:i:sA")
                ];
               
                $this->db->set($arr_form_update);
                $this->db->where(["sp_id" => $user['id'], "id"=>$id]);
                $this->db->limit(1);
                $this->db->update("placement_jobs");
                if($this->db->affected_rows() > 0){
                    $data_view['check_inset'] = "OK";
                }else{
                    $data_view['check_inset'] = "ERROR";
                }
            }
        }
        $data_view['short_data'] = $this->Sp_model->get_job_data("EDIT_BY_ID",$id);
        $data_view['job_specialization'] = $this->Sp_model->get_job_data("SPECIALIZATION",$data_view['short_data']->domain);
        $data_view['job_domain'] = $this->Sp_model->get_job_data("ALL_JOB_DOMAIN");        
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar'); 
        $this->load->view('sp/job_edit',$data_view); 
        $this->load->view('footer');
    }

    public function job_request_user($id)
    {
            $data_view = [];
            $this->load->model('User_model');
            $this->load->model('Base_model');
            $this->load->model('Sp_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
           
            $data['user'] = $user;
            $data_view['job_request_info'] = $this->Sp_model->get_job_data("JOB_REQUEST_USERS",$id);
            $this->load->view('navbar',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar'); 
            $this->load->view('sp/job_request_user',$data_view); 
            $this->load->view('footer');
    }
    
    
    //Jops #End

    
    public function ap_book_view()
    {
        // $this->load->model('User_model');
        //     if($this->User_model->authorized()==false)
        //     {
        //         $this->session->set_flashdata('msg','You are not authorized to access this section');
        //         redirect(base_url().'/UserController/login');
        //     }
        //     $user = $this->session->userdata('user');
            
        $data =$this->initializer();
        $user = $this->session->userdata('user');
        $data['user'] = $user;
        $q = $this->db->where(["user_id"=>$user["id"]])->order_by("created_at","desc")->get("user_book_appointment");
            $data['ap_book_data'] = $q->result();
            $this->load->view('navbar',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/ap_book_view',$data);
            $this->load->view('footer');
    }
    public function view_counseling()
    {
        $this->load->model('User_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }
            $user = $this->session->userdata('user');
            
            $q = $this->db->where(["user_id"=>$user["id"]])->order_by("created_at","desc")->get("user_book_appointment");
            $data['user'] = $user;
            $data['ap_book_data'] = $q->result();
            $this->load->view('navbar',$data);
            //$this->load->view('sidebar',$data);
            $this->load->view('sp/sidebar');
            $this->load->view('sp/view_counseling',$data);
            $this->load->view('footer');
    }
    public function seo_inputs()
    {
        $data = [];
        // $this->load->model('User_model');
        // $this->load->model('Commen_model');
        $data =$this->initializer();
        $user = $this->session->userdata('user');
        // if($this->User_model->authorized()==false)
        // {
        //     $this->session->set_flashdata('msg','You are not authorized to access this section');
        //     redirect(base_url().'/UserController/login');
        // }
     
        $services = $this->Commen_model->reseller_services($user["email"]);
        $service_lists = [];
        foreach($services as $s_v){
            $service_lists[] = $s_v["c_group"];
        }

        $this->form_validation->set_rules('channels[]', 'Channel', 'required');
        
        if($this->form_validation->run()){
            $data = [
                        "user_id" => $user["id"],
                        "channels" => implode(",",$this->input->post("channels")),
                        "locations" => implode(",",$this->input->post("locations")),
                        "available_days" => implode(",",$this->input->post("available_days")),
                        "star_rating" => $this->input->post("star_rating"),
                        "experience" => $this->input->post("experience"),
                        "counselling_sessions" => $this->input->post("counselling_sessions"),
                        "top_skills" => implode(",",$this->input->post("top_skills")),
                        "most_relevant_education" => $this->input->post("most_relevant_education"),
                        "services" => implode(",",$service_lists),
                    ];
            $tid = $this->input->post("tid");
            if(!empty($tid) && $tid == "add"){
                $this->db->insert("user_details_seo",$data);
                if($this->db->affected_rows() > 0){
                    $this->session->set_flashdata('seo_save', 'SEO Input successfully saved'); 
                }
            }

            if(!empty($tid) && $tid != "add"){                
                unset($data["user_id"]);
                $this->db->set($data);
                $this->db->where("id",$tid);
                $this->db->update("user_details_seo");
                if($this->db->affected_rows() > 0){
                    $this->session->set_flashdata('seo_save', 'SEO Input successfully saved');  
                }  
            }              
            
        }
        
        $data["seo_feildes"] = $this->Commen_model->seo_feildes();
        $q = $this->db->where(["user_id"=>$user["id"]])->get("user_details_seo");
        $data['user'] = $user;
        $data['seo_data'] = $q->row();
        $this->load->view('navbar',$data);
        //$this->load->view('sidebar',$data);
        $this->load->view('sp/sidebar',$data);
        $this->load->view('sp/seo_inputs',$data);
        $this->load->view('footer');
    }




    public function marketplace($num=null)
    {
       
        if($num == null)
        {
            $num = 1;
        }
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
       

        $level['lk'] = $this->Admin_model->provider_level_list();
        $level['sk'] = $this->Admin_model->provider_level3_list();
 
        // $level['s'] = $this->User_model->solutions_list();
        $where = "iam='sp' and status!='0' and id>=$num";
        $this->db->where($where);
        $this->db->limit('6');
        $level['l'] = $this->db->get('user_details');

       
        // $this->load->view('navbar');
        $this->load->view('sp/header3');
        $this->load->view('sp/all_sp',$level);
        $this->load->view('footer');
    }

    public function view_sp_full_detail_public_updated($email)
    {
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Sp_model');
        $this->load->model('Admin_model');
       
        // $email = $this->input->get('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            show_404();
        }
        
        $this->db->where('email',$email);
        $level1['s']=$this->db->get('user_details');
        $this->db->where('email',$email);
        $level1['l']=$this->db->get('provider_detail_four');
      
        $this->load->view('sp/sp_detail_profile_public',$level1);
        $this->load->view('footer');
    }
public function landingsPages($id)
        {

            $data =$this->initializer();
            $user = $this->session->userdata('user');
            $data['user'] = $user;
           
            $metadata['landingId']= $id;
            $metadata['resellerId']= $user['id'];
            $metadata['flow']= $this->User_model->get_landing_section_data($id,$user['id']);
            //echo "<pre>";print_r($metadata);die;
            
            //$metadata['flow']= $this->User_model->get_landing_section_data();['flow']
            $landing = $this->User_model->landingId($user['id']);
            $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
            $metadata['landing_page_section']= $this->User_model->get_landing_section_via_data($id);
            $metadata['landing_page_details']= $this->User_model->get_landing_details_section_via_data($id);
  
            $controller_name = debug_backtrace();
             $file ='list';
       
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/landing_page_section_list',$metadata);
            $this->load->view('footer');
        }
    public function landingSectionDelete($id)
        {
            //echo "edit";die();
            //echo $status;die;
            //$status = ($status==1)?0:1;
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

            $this->load->helper(array('form', 'url'));

            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            
            //$this->load->model('Admin_model');
            $result = $this->User_model->landingSectionDelete($id);
            if($result==True){
                $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/SpController/landingsPages/'.$id,'refresh');
        }else{
            $this->session->set_flashdata('msg','Something Wrong');
            redirect('/SpController/landingsPages','refresh');
        }
            $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/SpController/landingsPages/'.$id,'refresh');

        }
 public function addParameterValues()
        {
             // $id = $this->input->post('id',TRUE);
             // echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            //echo "dgdgdfkgjdfgkjdf";die;

            $this->form_validation->set_rules('values','Value','required');
            //$this->form_validation->set_rules('descripation','Descripation','required');
            //$this->form_validation->set_rules('path','path','required');
           $landingId = $this->input->post('landingId',TRUE);
            $section_id = $this->input->post('id',TRUE);
            $resellerId = $this->input->post('resellerId',TRUE);
            // if($this->form_validation->run()== true)
            // {
              $this->load->model('User_model');
              $values = $this->input->post('values',TRUE);
                $count = count($values);
                if ($count > 0) {
                   for ($i = 0; $i < $count; $i++) {
                       if (!empty($values[$i])) {
                          $data = array(
                            'landing_page_id' => $landingId,
                            'parameter' => $this->input->post('parameterName')[$i],
                            'value' => $this->input->post('values')[$i],
                            'section_id' => $section_id,
                            'reseller_id'=>$resellerId,
                            );
                
                          $result = $this->User_model->insert_landing_section_full_data($data);
                        }
                      } 
                   }
              //$array = array_merge($values,$para);
             // $i=0;
            //   foreach ($values as $value){
            //       //echo "<pre>";print_r($value);die;
            //       //echo $value;die;
            //         $data=[];
            //         $data['landing_page_id']=  $landingId;
            //         $data['value']=  $value;
            //         //$data['parameter'] = $value[$i]:'';
            //         $data['section_id']= $section_id;
            //         $data['reseller_id']= $resellerId;
            //         //$i++;
            //          $result = $this->User_model->insert_landing_section_full_data($data);
                   
            //   }
                //echo "<pre>";print_r($data);die;
                   

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/SpController/landingsPages/'.$landingId,'refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/SpController/landingsPages/'.$landingId,'refresh');
                    }      
            // }
            // else
            // {   
            //     $this->session->set_flashdata("msg","Enter all the required fields");
            //     redirect('/UserController/landingsPages/'.$landingId,'refresh');
            // }

        }
public function addLandingSectionParameter()
        {
             // $id = $this->input->post('id',TRUE);
             // echo $id;die();
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            //echo "dgdgdfkgjdfgkjdf";die;

            $this->form_validation->set_rules('section_name','Name','required');
            //$this->form_validation->set_rules('descripation','Descripation','required');
            //$this->form_validation->set_rules('path','path','required');
           
            if($this->form_validation->run()== true)
            {
               /// echo "dgdgdfkgjdfgkjdffgfgfgffgf";die;
                //echo "if";die();
                $landingId = $this->input->post('landingId',TRUE);
                $name = $this->input->post('section_name',TRUE);
                $resellerId = $this->input->post('resellerId',TRUE);
                //$path = $this->input->post('path',TRUE);
                $data=[];
                $data['sectionId']=  $name;
                $data['resellerId']= $resellerId;
                $data['landingPageId']= $landingId;

                $this->load->model('User_model');
                $result = $this->User_model->insert_landing_section_data($data);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                        redirect('/SpController/landingsPages/'.$landingId,'refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                        redirect('/SpController/landingsPages/'.$landingId,'refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                redirect('/SpController/landingsPages/'.$landingId,'refresh');
            }

        }
    
public function section_via_parameter()
        {
            
            
            $sectionId = $this->input->post('sectionId');
            
            $this->load->model('User_model');
            $landing_page_details = $this->User_model->sectionViaPArameter($sectionId);
            //echo "<pre>";print_r($reseller);die;
                $html ='';
                    foreach ($landing_page_details as $row) {
                      $html .='<div class="row">';
                        $html .= '<div class="col-md-6">';
                            $html .= '<div class="mb-3">';
                             $html .= ' <label class="form-label" for="basic-default-fullname">Parameter</label>';
                               $html .= '<h2>'.$row["parameter"].'</h2>';
                               $html .= '<input type="hidden" name="parameterName[]" class="form-control" id="basic-default-fullname" value="'.$row["parameter"].'"  />';
                          $html .='</div>';
                        $html .='</div>';
                        $html .='<div class="col-md-6">';
                          $html .='<div class="mb-3">';
                            $html .='<label class="form-label" for="basic-default-fullname">Value</label>';
                              $html .='<input type="text" name="values[]" class="form-control" id="basic-default-fullname" required placeholder="Value" />';
                              $html .='<div class="render-template-msg"></div>';
                          $html .='</div>';
                        $html .='</div>';
                        $html .='</div>';
                    }
           
            //echo $html;exit;
            echo json_encode($html);
        }
         public function book_link()
        {
            $this->load->library('form_validation');
        //     $this->load->model('User_model');
        //     $this->load->model('Admin_model');
        //     $this->load->model('Commen_model');
        //     if($this->User_model->authorized()==false)
        //     {
        //         $this->session->set_flashdata('msg','You are not authorized to access this section');
        //         redirect(base_url().'/UserController/login');
        //     }
        //     $user = $this->session->userdata('user');
        //     $data['user'] = $user;
        //     $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        // //echo $allowed_services;die;
        //     $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
        //     $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
        //     $landing = $this->User_model->landingId($user['id']);
        //     $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
            $data = $this->initializer();
            $userData['calender_link'] = $this->User_model->getcalenderlinkById($data['user']['id']);
            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/book_link',$userData);
            $this->load->view('footer');
            
        }
         public function update_book_link()
        {
                $this->load->model('User_model');
                $this->form_validation->set_rules('book_link','Book Link','required');
               
                if($this->form_validation->run() == true)
                {            
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;   
                    $book_link = $_POST['book_link'];
                    $updateData= $this->User_model->book_link_update($user['id'],$book_link);
                    $this->session->set_flashdata("msg","update successfully!!");
                    redirect('/SpController/book_link','refresh');
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field Required");
                    redirect('/SpController/book_link','refresh');
                } 
        }
        public function counselingParameters()
        {
            
        //     $this->load->database();
        //     $this->load->model('User_model');
        //     $this->load->model('Admin_model');
            
        //     $this->load->model('Commen_model');
        //     if($this->User_model->authorized()==false)
        //     {
        //         $this->session->set_flashdata('msg','You are not authorized to access this section');
        //         redirect(base_url().'/UserController/login');
        //     }
        //     $user = $this->session->userdata('user');
        //     $data['user'] = $user;
        //     $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        // //echo $allowed_services;die;
        //     $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
        //     $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
        //     $landing = $this->User_model->landingId($user['id']);
        //     $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);

            //$this->load->model('User_model');
            
            //$metadata['landingId']= $id;
            $data = $this->initializer();
            $user = $data['user'];
            $metadata['resellerId']= $user['id'];
            $metadata['flow']= $this->User_model->getCounselingPara($user['id']);
            $data['counseling_type'] = $this->User_model->getcounselingTypeById($user['id']);
            $controller_name = debug_backtrace();
            //load view
            $this->load->view('navbar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/counseling_parameters_list',$metadata);
            $this->load->view('footer');
        }
        public function addCounselingPara()
        {
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            $this->form_validation->set_rules('para_name','Parameter Name','required');
            if($this->form_validation->run()== true)
            {
                $para_name = $this->input->post('para_name',TRUE);
                $counseling_type = $this->input->post('counseling_type',TRUE);
                $resellerId = $this->input->post('resellerId',TRUE);
                $mrp = $this->input->post('mrp',TRUE);
                $duration = $this->input->post('duration',TRUE);
                $data=[];
                $data['name']=  $para_name;
                $data['type']=  $counseling_type;
                $data['duration']=  $duration;
                $data['resellerId']= $resellerId;
                $data['mrp']= $mrp;

                $this->load->model('User_model');
                $result = $this->User_model->insert_counseling_para_data($data);

                    if($result)
                    {  
                        $this->session->set_flashdata("msg2","Added Successfully");
                    redirect('/SpController/counselingParameters/','refresh');
                        
                    }
                    else
                    {
                        $this->session->set_flashdata("msg","Opps Unable to add data");
                         redirect('/SpController/counselingParameters/','refresh');
                    }      
            }
            else
            {   
                $this->session->set_flashdata("msg","Enter all the required fields");
                 redirect('/SpController/counselingParameters/','refresh');
            }

        }
         public function counselingUpdate()
        {
            $this->load->model('User_model');
                if($this->User_model->authorized()==false)
                {
                    $this->session->set_flashdata('msg','You are not authorized to access this section');
                    redirect(base_url().'/UserController/login');
                }
            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $this->load->library('form_validation');
            $this->form_validation->set_rules('para_name','Parameter Name','required');
            if($this->form_validation->run()== true)
            {
                $para_name = $this->input->post('para_name',TRUE);
                $counseling_type = $this->input->post('counseling_type',TRUE);
                $id = $this->input->post('id',TRUE);
                $mrp = $this->input->post('mrp',TRUE);
                $duration = $this->input->post('duration',TRUE);
                $update=$this->User_model->counselingUpdate($id,$para_name,$counseling_type,$mrp,$duration);
                if($update){
                $this->session->set_flashdata('msg  ','updated successfully');
                redirect(base_url().'SpController/counselingParameters','refresh');
                }else{
                    $this->session->set_flashdata('msg  ','something wrong');
                redirect(base_url().'SpController/counselingParameters','refresh');
                }
            }else{
                $this->session->set_flashdata('msg  ','all Field Required');
                redirect(base_url().'SpController/counselingParameters','refresh');
            }
        }
        public function counselingDelete($id)
        {
            $this->load->database();
            $this->load->model('User_model');
            $this->load->model('Commen_model');
            if($this->User_model->authorized()==false)
            {
                $this->session->set_flashdata('msg','You are not authorized to access this section');
                redirect(base_url().'/UserController/login');
            }

            $this->load->helper(array('form', 'url'));

            $user = $this->session->userdata('user');
            $data['user'] = $user;
            $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam'], 'dev');
            $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam'], 'dev');
            
            //$this->load->model('Admin_model');
            $result = $this->User_model->counselingDelete($id);
            if($result==True){
                $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/SpController/counselingParameters/','refresh');
        }else{
            $this->session->set_flashdata('msg','Something Wrong');
            redirect('/SpController/counselingParameters/','refresh');
        }
            $this->session->set_flashdata('msg','Record deleted Successfully');
            redirect('/SpController/counselingParameters/','refresh');

        }
         public function counseling_type()
        {
            $this->load->library('form_validation');
        //     $this->load->model('User_model');
        //     $this->load->model('Admin_model');
        //     $this->load->model('Commen_model');
        //     if($this->User_model->authorized()==false)
        //     {
        //         $this->session->set_flashdata('msg','You are not authorized to access this section');
        //         redirect(base_url().'/UserController/login');
        //     }
        //     $user = $this->session->userdata('user');
        //     $data['user'] = $user;
        //     $data['allowed_services'] = $this->Admin_model->getUserDetailsById($user['id']);
        // //echo $allowed_services;die;
        //     $data['mainmenu'] = $this->Commen_model->get_marketplace_menu($data['user']['iam']);
        //     $data['submenu'] = $this->Commen_model->get_marketplace_submenu($data['user']['iam']);
        //     $landing = $this->User_model->landingId($user['id']);
        //     $data['landing']= $this->Admin_model->get_landing_data_by_user($landing['landing_id']);
            $data = $this->initializer();
            $data['counseling_type'] = $this->User_model->getcounselingTypeById($data['user']['id']);
            $this->load->model('Admin_model');
            $this->load->view('navbar',$data);
            $this->load->view('sp/sidebar',$data);
            $this->load->view('sp/counseling_type',$data);
            $this->load->view('footer');
            
        }
        public function update_counseling_type()
        {
                $this->load->model('User_model');
                $this->form_validation->set_rules('counseling_type','counseling type','required');
               
                if($this->form_validation->run() == true)
                {            
                    $user = $this->session->userdata('user');
                    $data['user'] = $user;   
                    $book_links = $_POST['counseling_type'];
                    $counseling_type = $this->User_model->getcounselingTypeById($user['id']);
                    if($counseling_type!=''){
                        $book_link = $counseling_type.','.$book_links;
                    }else{
                        $book_link = $book_links;
                    }
                    $updateData= $this->User_model->counseling_type_update($user['id'],$book_link);
                    $this->session->set_flashdata("msg","update successfully!!");
                    redirect('/SpController/counseling_type','refresh');
                }
                else
                {
                    $this->session->set_flashdata("msg","All Field Required");
                    redirect('/SpController/counseling_type','refresh');
                } 
        }
} //class #END
?>