<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use setasign\Fpdi\Fpdi;
require_once('OtherAjax/fpdf181/fpdf181/fpdf.php');
require_once('OtherAjax/fpdi2/fpdi2/src/autoload.php');
class Report_PDF {

        protected $CI,$code;
        private $pdf;
        private $reseller_data;
        protected $priority;
        protected $abroad;

        function __construct(){
            $this->CI =& get_instance();
            $this->CI->load->model("Commen_model");
            $this->pdf = new FPDI();
                  
        }

        function __deconstruct(){
            $this->pdf->Close();
        }

        // ecp report PDF #START HERE
        public function ecp_report_pdf($code)
        {
            $this->code = $code;

            $this->reseller_data = $this->CI->Commen_model
            ->reseller_data_from_code($this->code);

           $this->pdf->SetTitle("ECP Report" , true);
           $template_url = "OtherAjax/report_template/ecp_report.pdf";
           $template_url_personality = "OtherAjax/report_template/WLA.pdf"; 
           $template_url_aptitude = "OtherAjax/report_template/WPA.pdf"; 
           $this->page_import($template_url);
           $this->page_import('OtherAjax/report_template/Client_Details.pdf');           
           $this->second_page_data($code); 
           $logo = $this->reseller_data['rows_data']->logo;
            $this->logo_check_size($logo);          
           $this->page_import($template_url,2);
           /*writing page 3 - personality report */
           $this->logo_check_size($logo);
           $this->page_import($template_url_personality,3);
           $this->personality_score();
           /*End of page 3*/
           //aptitude score #START.
           $this->page_import($template_url_aptitude,3);
           $this->aptitude_score();
           //aptitude score #END.

           $this->ecp_competence($template_url);           
           $this->pdf->output();
        }
        // ecp report PDF #END HERE

        // disha report PDF #START here.
        public function disha_report_pdf($code)
        {
            $this->code = $code;

            $this->reseller_data = $this->CI->Commen_model
            ->reseller_data_from_code($this->code);

           $this->pdf->SetTitle("Disha Report" , true);
           $template_url = "OtherAjax/report_template/rpt_tpt_disha.pdf";       
           $template_url_2 = "OtherAjax/report_template/rpt_tpt_disha_page_2.pdf";       
           $this->page_import($template_url);
           $this->page_import('OtherAjax/report_template/Client_Details.pdf');           
           $this->second_page_data($code);           
        //    $this->page_import($template_url,2);
           $this->page_import($template_url_2);
           $logo = $this->reseller_data['rows_data']->logo;
            $this->logo_check_size($logo); 
           $this->page_import($template_url,3);
           $this->post_college_pref();
           $this->page_import($template_url,4);
           $this->strength_indicator();
           $this->page_import($template_url,5);           
           $this->observations_remm();
          
          // $this->pdf->output();
        }
        // disha report PDF #END here.

        

        /*
         #second page data from DB
         @param String var $code
        */
        function second_page_data($code){
            $this->CI->db->select("a.name,DATE_FORMAT(`a`.`dob`,'%d-%m-%Y') as dob,a.gender,a.mobile,a.email,a.address,a.reseller_id,a.cls_nature,a.cls_detail,a.cls_type,a.c_remark,a.remark_update_last,a.asignment_submission_date,b.logo as reseller_logo,b.reseller_signature,b.address as reseller_address,b.contact,b.email as reseller_email,c.fullname as user_full_name");
            $this->CI->db->where(['code'=>$code]);
            $this->CI->db->join("reseller_homepage as b","b.r_email = a.reseller_id","left");
            $this->CI->db->join("user_details as c","c.email = a.reseller_id","left");
            $q = $this->CI->db->get('user_code_list as a');
            //echo $this->CI->db->last_query();
            if($q->num_rows() > 0){
                $d = $q->row();
                $setX = 88;
                $cellX = 20;
                $cellY = 10;
                $this->set_cell("C",[$setX,61],[$cellX, $cellY,$d->name]);
                // $this->set_cell("M",[$setX,69],[$cellX+100, 4,$d->cls_nature.' '.$d->cls_type."\n".$d->cls_detail]);
                $this->set_cell("M",[$setX,72],[$cellX+100, 4,$d->cls_detail]);
                $this->set_cell("C",[$setX,78],[$cellX, $cellY,$d->dob]);
                $this->set_cell("C",[$setX,87],[$cellX, $cellY,$d->gender]);
                $this->set_cell("C",[$setX,95],[$cellX, $cellY,$d->mobile]);
                $this->set_cell("C",[$setX,104],[$cellX, $cellY,$d->email]);
                $this->set_cell("C",[$setX,113],[$cellX, $cellY,$d->address]);
                $this->set_cell("C",[$setX,125],[$cellX, $cellY,$d->asignment_submission_date]);
                $this->set_cell("C",[$setX,160],[$cellX, $cellY,$d->user_full_name]);
                $this->set_cell("C",[$setX,170],[$cellX, $cellY,$d->contact]);
                $this->set_cell("C",[$setX,180],[$cellX, $cellY,$d->reseller_email]);
                $this->set_cell("C",[$setX,180],[$cellX, $cellY,$d->reseller_email]);               
                $this->set_cell("C",[$setX,188],[$cellX, $cellY,$d->contact]);               
                $this->set_cell("C",[$setX,198],[$cellX, $cellY,$d->reseller_address]);
            }else{
                $this->CI->load->view("pdf_views/certificate_no_found");                             
            }            
        }

        /*
         #$potion : single cell | multiple cell,setXY:[X,Y],$cell:[X,Y,"Message"]
         set_cell($option = "C", $setXY=[0,0],$cell=[0,0,""],$fontSize = 12,$font = "arial")
         @param Varchar var $option,$font
         @param Int var $font
         @param Array var $setXY[0,0],$cell[0,0,""]
        */
        function set_cell($option = "C", $setXY=[0,0],$cell=[0,0,""],$fontSize = 12,$font = "arial"){
            $this->pdf->SetFont($font);
            $this->pdf->SetFontSize($fontSize);
            $this->pdf->SetXY($setXY[0],$setXY[1]);
            if($option == "M"){
             $this->pdf->Multicell($cell[0],$cell[1],$cell[2]);
            }elseif($option == "C"){
             $this->pdf->Cell($cell[0],$cell[1],$cell[2]);
            }elseif($option = "T"){
             $this->pdf->Text($cell[0],$cell[1],$cell[2]);
            }
         }

        /*
         #import page for use in templet 
         @param String var $page_url
         @param int var $page_no  
        */
        function page_import($page_url,$page_no = 1){
            $this->pdf->setSourceFile($page_url);
            $tpl = $this->pdf->importPage($page_no);
            $this->pdf->AddPage();
            $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);         
        }

        function personality_score(){           
            //$wla_ppe_data = $this->CI->Commen_model->wla_ppe_data("ACEE670326","wla_part1");
            $wla_ppe_data = $this->CI->Commen_model
            ->wla_ppe_data($this->code,"ecp_part_1");
            
            $category_temp_ans = array(0,0,0,0,0);
            $increase = array(0,0,0,0,0);
            $que_nature = ["F", "R"];
            $ans_val = [1,2,3,4];
            $cat_name = ["N","E","O","A","C"];
            $cat_cnt = sizeof($cat_name);
            
            foreach($wla_ppe_data['rows_data'] as $row){
                /*by sudhir*/
                if($row->nature==$que_nature[0])
                {
                    $temp_ans = intval($row->ans) - 1;
                }
                else 
                if ($row->nature==$que_nature[1])
                {
                    $temp_ans = 5 - intval($row->ans);
                }
                 /*end of by sudhir */
                /*by sudhir */
                for($i=0;$i<$cat_cnt;$i++)
                {
                    if($row->category == $cat_name[$i])
                    {
                        $category_temp_ans[$i] += $temp_ans;
                        $increase[$i] +=1;
                        break;
                    }
                }
                /*end of by sudhir */

            } //foreach loop #END here. 
            
                                        
            $arr_inference = ["Strength","Satisfactory",
                            "Moderate","Needs attention",
                            "Needs immediate attention"];
           
            $coma_count = 0;
            $fa = '';
            if($this->reseller_data['rows_data']->gender == 'Male')
            {
                //checking N
                if($category_temp_ans[0] > 0 && $category_temp_ans[0] <= 8)
                {
                    $p1_status = 'Strength';
                }
                else if($category_temp_ans[0] > 8 && $category_temp_ans[0] <= 15)
                {
                    $p1_status = 'Satisfactory';
                } 
                else if($category_temp_ans[0] > 15 && $category_temp_ans[0] <= 23)
                {
                    $p1_status = 'Moderate';
                }
                else if($category_temp_ans[0] > 23 && $category_temp_ans[0] <= 30)
                {
                    $p1_status = 'Needs attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Neuroticism';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Neuroticism';
                        $coma_count = 1;
                    }
                }
                else
                {
                    $p1_status = 'Needs immediate Attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Neuroticism';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Neuroticism';
                        $coma_count = 1;
                    }
                }
                //checking E
                if($category_temp_ans[1] > 0 && $category_temp_ans[1] <= 17)
                {
                    $p2_status = 'Needs immediate attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Extraversion';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Extraversion';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[1] > 17 && $category_temp_ans[1] <= 23)
                {
                    $p2_status = 'Needs attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Extraversion';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Extraversion';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[1] > 23 && $category_temp_ans[1] <= 30)
                {
                    $p2_status = 'Moderate';
                }
                else if($category_temp_ans[1] > 30 && $category_temp_ans[1] <= 36)
                {
                    $p2_status = 'Satisfactory';
                }
                else
                {
                    $p2_status = 'Strength';
                }
                //checking O
                if($category_temp_ans[2]>0 && $category_temp_ans[2]<=17)
                {
                    $p3_status = 'Needs immediate attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Openness to experience';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Openness to experience';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[2]>17 && $category_temp_ans[2]<=23)
                {
                    $p3_status = 'Needs attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Openness to experience';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Openness to experience';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[2]>23 && $category_temp_ans[2]<=30)
                {
                    $p3_status = 'Moderate';
                }
                else if($category_temp_ans[2]>30 && $category_temp_ans[2]<=36)
                {
                    $p3_status = 'Satisfactory';
                }
                else
                {
                    $p3_status = 'Strength';
                }
                //checking A
                if($category_temp_ans[3]>0 && $category_temp_ans[3]<=21)
                {
                    $p4_status = 'Needs immediate attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Agreeableness';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Agreeableness';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[3]>21 && $category_temp_ans[3]<=26)
                {
                    $p4_status = 'Needs attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Agreeableness';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Agreeableness';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[3]>26 && $category_temp_ans[3]<=33)
                {
                    $p4_status = 'Moderate';
                }
                else if($category_temp_ans[3]>33 && $category_temp_ans[3]<=38)
                {
                    $p4_status = 'Satisfactory';
                }
                else
                {
                    $p4_status = 'Strength';
                }
                //checking C
                if($category_temp_ans[4]>0 && $category_temp_ans[4]<=22)
                {
                    $p5_status = 'Needs immediate attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Contentiousness';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Contentiousness';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[4]>22 && $category_temp_ans[4]<=28)
                {
                    $p5_status = 'Needs attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Contentiousness';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Contentiousness';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[4]>28 && $category_temp_ans[4]<=35)
                {
                    $p5_status = 'Moderate';
                }
                else if($category_temp_ans[4]>35 && $category_temp_ans[4]<=41)
                {
                    $p5_status = 'Satisfactory';
                }
                else
                {
                    $p5_status = 'Strength';
                }
            }
            else
            {
                //checking N
                if($category_temp_ans[0]>0 && $category_temp_ans[0]<=9)
                {
                    $p1_status = 'Strength';
                }
                else if($category_temp_ans[0]>9 && $category_temp_ans[0]<=17)
                {
                    $p1_status = 'Satisfactory';
                }
                else if($category_temp_ans[0]>17 && $category_temp_ans[0]<=26)
                {
                    $p1_status = 'Moderate';
                }
                else if($category_temp_ans[0]>26 && $category_temp_ans[0]<=34)
                {
                    $p1_status = 'Needs attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Neuroticism';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Neuroticism';
                        $coma_count = 1;
                    }
                }
                else
                {
                    $p1_status = 'Needs immediate attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Neuroticism';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Neuroticism';
                        $coma_count = 1;
                    }
                }
                //checking E
                if($category_temp_ans[1]>0 && $category_temp_ans[1]<=19)
                {
                    $p2_status = 'Needs immediate attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Extraversion';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Extraversion';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[1]>19 && $category_temp_ans[1]<=25)
                {
                    $p2_status = 'Needs attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Extraversion';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Extraversion';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[1]>25 && $category_temp_ans[1]<=32)
                {
                    $p2_status = 'Moderate';
                }
                else if($category_temp_ans[1]>32 && $category_temp_ans[1]<=38)
                {
                    $p2_status = 'Satisfactory';
                }
                else
                {
                    $p2_status = 'Strength';
                }
                //checking O
                if($category_temp_ans[2]>0 && $category_temp_ans[2]<=19)
                {
                    $p3_status = 'Needs immediate attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Openness to experience';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Openness to experience';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[2]>19 && $category_temp_ans[2]<=25)
                {
                    $p3_status = 'Needs attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Openness to experience';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Openness to experience';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[2]>25 && $category_temp_ans[2]<=32)
                {
                    $p3_status = 'Moderate';
                }
                else if($category_temp_ans[2]>32 && $category_temp_ans[2]<=38)
                {
                    $p3_status = 'Satisfactory';
                }
                else
                {
                    $p3_status = 'Strength';
                }
                //checking A
                if($category_temp_ans[3]>0 && $category_temp_ans[3]<=24)
                {
                    $p4_status = 'Needs immediate attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Agreeableness';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Agreeableness';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[3]>24 && $category_temp_ans[3]<=30)
                {
                    $p4_status = 'Needs attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Agreeableness';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Agreeableness';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[3]>30 && $category_temp_ans[3]<=36)
                {
                    $p4_status = 'Moderate';
                }
                else if($category_temp_ans[3]>36 && $category_temp_ans[3]<=42)
                {
                    $p4_status = 'Satisfactory';
                }
                else
                {
                    $p4_status = 'Strength';
                }
                //checking C
                if($category_temp_ans[4]>0 && $category_temp_ans[4]<=22)
                {
                    $p5_status = 'Needs immediate attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Contentiousness';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Contentiousness';
                        $coma_count = 1;
                    }

                }
                else if($category_temp_ans[4]>22 && $category_temp_ans[4]<=29)
                {
                    $p5_status = 'Needs attention';
                    if($coma_count==0)
                    {
                        $fa = $fa.'Contentiousness';
                        $coma_count = 1;
                    }
                    else
                    {
                        $fa = $fa.', Contentiousness';
                        $coma_count = 1;
                    }
                }
                else if($category_temp_ans[4]>29 && $category_temp_ans[4]<=36)
                {
                    $p5_status = 'Moderate';
                }
                else if($category_temp_ans[4]>36 && $category_temp_ans[4]<=42)
                {
                    $p5_status = 'Satisfactory';
                }
                else
                {
                    $p5_status = 'Strength';
                }
            }

                /* echo "Personality Score & Status:<br>";
                echo "Neuroticism Score :".$category_temp_ans[0]." Count :".$increase[0]." Status :".$p1_status."<br>";
                echo "Extraversion :".$category_temp_ans[1]." Count :".$increase[1]."Status :".$p2_status."<br>";
                echo "Openness :".$category_temp_ans[2]." Count :".$increase[2]."Status :".$p3_status."<br>";
                echo "Agreeableness :".$category_temp_ans[3]." Count :".$increase[3]."Status :".$p4_status."<br>";
                echo "Conscientiousness :".$category_temp_ans[4]." Count :".$increase[4]."Status :".$p5_status."<br>";
                */

                $p_score1 = ($category_temp_ans[0] * 100) / ($increase[0] * 4);
                $p_score2 = ($category_temp_ans[1] * 100) / ($increase[1] * 4);
                $p_score3 = ($category_temp_ans[2] * 100) / ($increase[2] * 4);
                $p_score4 = ($category_temp_ans[3] * 100) / ($increase[3] * 4);
                $p_score5 = ($category_temp_ans[4] * 100) / ($increase[4] * 4);

                //reseller logo set in pdf #STERT.
                $logo = base_url($this->reseller_data['rows_data']->logo);
                $size = getimagesize($logo);
                $wImg = $size[0];
                $hImg = $size[1];
                $this->logo_check_size($logo);
                //reseller logo set in pdf #END.

                //set Values in pdf Personality table #START.
                $this->pdf->SetFontSize('10');
                $this->pdf->SetXY(148,90);
                $this->pdf->Cell(20, 10,$p3_status, 0, 0, 'L');

                $this->pdf->SetXY(148,112);
                $this->pdf->Cell(20, 10,$p5_status, 0, 0, 'L');

                $this->pdf->SetXY(148,128);
                $this->pdf->Cell(20, 10,$p2_status, 0, 0, 'L');

                $this->pdf->SetXY(148,144);
                $this->pdf->Cell(20, 10,$p4_status, 0, 0, 'L');

                $this->pdf->SetXY(148,164);
                $this->pdf->Cell(20, 10,$p1_status, 0, 0, 'L');
                //set Values in pdf Personality table #END.

                //make a Personality graph chart in pdf #START.
                //start graph
                $chartX=37;
                $chartY=199;
                //dimension
                $chartWidth=100;
                $chartHeight=45;
                //padding
                $chartTopPadding=10;
                $chartLeftPadding=10;
                $chartBottomPadding=10;
                $chartRightPadding=5;
                //chart box
                $chartBoxX=$chartX+$chartLeftPadding;
                $chartBoxY=$chartY+$chartTopPadding;
                $chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
                $chartBoxHeight=$chartHeight-$chartTopPadding-$chartBottomPadding;
                //bar width
                $barWidth=8;
                //chart data
                $data=Array(
                        'N'=>['color'=>[0,255,0],'value'=>$p_score1],
                        'E'=>['color'=>[0,25,10],'value'=>$p_score2],
                        'O'=>['color'=>[0,105,100],'value'=>$p_score3],
                        'A'=>['color'=>[100,105,100],'value'=>$p_score4],
                        'C'=>['color'=>[10,25,100],'value'=>$p_score5]
                );
                $data2=Array(
                    'N-Neuroticism'=>['color'=>[0,255,0],'value'=>$p_score1],
                    'E-Extraversion'=>['color'=>[0,25,10],'value'=>$p_score2],
                    'O-Openness to experience'=>['color'=>[0,105,100],'value'=>$p_score3],
                    'A-Agreeableness'=>['color'=>[100,105,100],'value'=>$p_score4],
                    'C-Contentiousness'=>['color'=>[10,25,100],'value'=>$p_score5]
                    
                );
                //data max
                $dataMax=0;
                foreach($data as $item){
                    if($item['value']>$dataMax)$dataMax=100;
                } 
                //data step
                $dataStep=20;
                //set font, line width, color
                $this->pdf->SetFont('Arial', '',9);
                $this->pdf->SetLineWidth(0.2);
                $this->pdf->SetDrawColor(0);
                //chart boundary
                $this->pdf->Rect($chartX,$chartY,$chartWidth,$chartHeight);
                //vertical axis line
                $this->pdf->Line(
                    $chartBoxX,
                    $chartBoxY,
                    $chartBoxX,
                    $chartBoxY+$chartBoxHeight

                );
                //horizontal axis line
                $this->pdf->Line(
                    $chartBoxX,
                    $chartBoxY+$chartBoxHeight,
                    $chartBoxX+$chartBoxWidth,
                    $chartBoxY+$chartBoxHeight

                );
                //vertical axis calculate chart y axis scale unit
                $yAxisUnits=$chartBoxHeight/$dataMax;
                //draw vertical y axis label
                for($i=0;$i<=$dataMax; $i+=$dataStep){
                    //y position
                    $yAxisPos=$chartBoxY+($yAxisUnits*$i);
                    //draw y axis line
                    $this->pdf->Line(
                $chartBoxX-2,
                $yAxisPos,
                $chartBoxX,
                $yAxisPos     
                    );
                
                    //set cell position for y axis labels
                    $this->pdf->SetXY($chartBoxX-$chartLeftPadding,$yAxisPos-2);
                    //write label
                    $this->pdf->Cell($chartLeftPadding-4,5,$dataMax-$i,0,0,'R');
                    
                }
                //horizontal axis set cell width
                $this->pdf->SetXY($chartBoxX,$chartBoxY+$chartBoxHeight);
                //cell width
                $xLabelWidth=$chartBoxWidth/ count($data);
                //loop horizontal axis and draw the bars
                $barXPos=0;
                foreach($data as $itemName=>$item){
                    //print the label
                    $this->pdf->Cell($xLabelWidth,5,$itemName,0,0,'C');
                //drawing the bar
                //bar color
                $this->pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);
                //bar height
                $barHeight=$yAxisUnits*$item['value'];
                //bar x position
                $barX=($xLabelWidth/2)+($xLabelWidth*$barXPos);
                $barX=$barX-($barWidth/2);
                $barX=$barX+$chartBoxX;
                //bar y position
                $barY=$chartBoxHeight-$barHeight;
                $barY=$barY+$chartBoxY;
                //draw the bar
                $this->pdf->Rect($barX,$barY,$barWidth,$barHeight,'DF');
                //increment $barXPos
                $barXPos++;
                }
                $this->pdf->SetFont('Arial','B',16);
                $this->pdf->SetXY($chartX,$chartY);
                //$this->pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2));
                $this->pdf->Cell(100,10,"Personality",0,0,'C');


                //legend properties
                $legendX=139;
                $legendY=210;

                //draw th legend
                $this->pdf->SetFont('Arial','',8.5);

                //store current legend Y position
                $currentLegendY=$legendY;
                foreach($data2 as $index=>$item)
                {
                //add legend color
                $this->pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);

                // remove border
                $this->pdf->SetDrawColor($item['color'][0],$item['color'][1],$item['color'][2]);
                $this->pdf->Rect($legendX,$currentLegendY,5,5,'DF');
                $this->pdf->SetXY($legendX+6,$currentLegendY);
                $this->pdf->Cell(50,5,$index,0,0);
                $currentLegendY+=5;
                }

                $this->pdf->SetFontSize('11');
                $this->pdf->SetXY(33,267);
                $this->pdf->Cell(20, 10,$fa, 0, 0, 'L');
                //make a Personality graph chart in pdf #END.
                
                //show pdf page by conditional #START.
                $this->pdf->setSourceFile('OtherAjax/report_template/Personality.pdf');
                if($category_temp_ans[0] > 23)
                {
                    $tpl = $this->pdf->importPage(5);
                    $this->pdf->AddPage();
                    $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                    $this->logo_check_size($logo);
                 /*    if($wImg<=512 && $hImg<=512)
                    {
                        $this->pdf->SetXY(170, 8);
                        $this->pdf->SetFont('arial');
                       // $this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),30), 0, 0,'R',false);
                    }
                    else
                    {
                        $this->pdf->SetXY(150, 10);
                        $this->pdf->SetFont('arial');
                       // $this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),50), 0, 0,'R',false);   
                    } */
                }
                if($category_temp_ans[1] <= 23)
                {
                    $tpl = $this->pdf->importPage(3);
                    $this->pdf->AddPage();
                    $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                    $this->logo_check_size($logo);
                    /* if($wImg<=512 && $hImg<=512)
                    {
                        $this->pdf->SetXY(170, 8);
                        $this->pdf->SetFont('arial');
                      //  $this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),30), 0, 0,'R',false);
                    }
                    else
                    {
                        $this->pdf->SetXY(150, 10);
                        $this->pdf->SetFont('arial');
                      //  $this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),50), 0, 0,'R',false);   
                    } */
                }
                if($category_temp_ans[2] <= 23)
                {
                    $tpl = $this->pdf->importPage(1);
                    $this->pdf->AddPage();
                    $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                    $this->logo_check_size($logo);
                    /* if($wImg<=512 && $hImg<=512)
                    {
                        $this->pdf->SetXY(170, 8);
                        $this->pdf->SetFont('arial');
                        //$this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),30), 0, 0,'R',false);
                    }
                    else
                    {
                        $this->pdf->SetXY(150, 10);
                        $this->pdf->SetFont('arial');
                        //$this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),50), 0, 0,'R',false);   
                    } */
                }
                if($category_temp_ans[3] <= 26)
                {
                    $tpl = $this->pdf->importPage(4);
                    $this->pdf->AddPage();
                    $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                    $this->logo_check_size($logo);
                   /*  if($wImg<=512 && $hImg<=512)
                    {
                        $this->pdf->SetXY(170, 8);
                        $this->pdf->SetFont('arial');
                        //$this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),30), 0, 0,'R',false);
                    }
                    else
                    {
                        $this->pdf->SetXY(150, 10);
                        $this->pdf->SetFont('arial');
                        //$this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),50), 0, 0,'R',false);   
                    } */
                }
                if($category_temp_ans[4] <= 28)
                {
                    $tpl = $this->pdf->importPage(2);
                    $this->pdf->AddPage();
                    $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                    $this->logo_check_size($logo);
                    /* if($wImg<=512 && $hImg<=512)
                    {
                        $this->pdf->SetXY(170, 8);
                        $this->pdf->SetFont('arial');
                        //$this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),30), 0, 0,'R',false);
                    }
                    else
                    {
                        $this->pdf->SetXY(150, 10);
                        $this->pdf->SetFont('arial');
                        //$this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),50), 0, 0,'R',false);   
                    } */
                }
                //show pdf page by conditional #END.


        } //function personality_score #END here.

        function aptitude_score(){
            /* $wpa_ppe_data  = $this->CI->Commen_model
            ->wpa_ppe_data("ACEE471891","wpa_part1"); */
            $wpa_ppe_data  = $this->CI->Commen_model
            ->wpa_ppe_data($this->code,"ecp_part_2");
            $score = 0;
            $logo = $this->reseller_data['rows_data']->logo;
           
            foreach($wpa_ppe_data['rows_data'] as $row){
                if($row->ans == $row->right_ans)
                {
                    $score = $score + 1;
                }
            }
            $this->pdf->SetFontSize('14');
            $this->pdf->SetXY(35,229);
            $this->pdf->Cell(20, 10,'Your Aptitude Score : '.$score, 0, 0, 'L');
            $this->logo_check_size($logo);
            $this->pdf->SetFontSize('11');
            if($score>27)
            {
                $tpl = $this->pdf->importPage(4);
                $this->pdf->AddPage();
                $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                $this->logo_check_size($logo);
            }
            else if($score>=22 && $score<=27)
            {
                $tpl = $this->pdf->importPage(5);
                $this->pdf->AddPage();
                $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

                $this->logo_check_size($logo);
                $tpl = $this->pdf->importPage(6);
                $this->pdf->AddPage();
                $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                
                $this->logo_check_size($logo);
                
            }
            else
            {
                $tpl = $this->pdf->importPage(7);
                $this->pdf->AddPage();
                $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);

                $this->logo_check_size($logo);

            }
        }
        
        function ecp_score(){
           
            $ecp_ds = [
                        "ecp_core" => 
                            [
                              "Ideas and Opportunities" => 
                                 [
                                    "Spotting opportunities","Creativity",
                                    "Vision","Valuing Ideas",
                                    "Ethical and sustainable thinking"
                                 ],
                              "Resources" => 
                                 [
                                    "Leadership",
                                    "Self-awareness and self-efficacy",
                                    "Motivation and perseverance",
                                    "Mobilising resources",
                                    "Financial and economic literacy",
                                    "Mobilising others"
                                 ],
                               "Into Action" =>
                               [
                                   "Taking the initiative",
                                   "Planning and management",
                                   "Coping with uncertainty, ambiguity and risk",
                                   "working with others",
                                   "learning through experience",
                               ]
                            ],
                        "ecp_qual" => [
                                        "Background" => 
                                        [
                                            "Highest Qualification"
                                        ]
                                      ],
                        "ecp_ind_awr" => [
                                            "Background" => 
                                            [
                                                "Industry awareness"
                                            ]
                                         ],
                          "ecp_ent_exp" => [
                                              "Background" => 
                                               [
                                                "Entreprenuership exposure"
                                               ]
                                            ],
                    ];

            $ecp_score = $this->CI->Commen_model->ecp_score($this->code,"ecp_part_3");
            $global_score = 0;
            $ecp_cat = [
                "Ideas and Opportunities","Resources",
                "Into Action","Qualification","Awareness",
                "Exposure",
               ];
            
            $ecp_cat_score = array();           
            foreach($ecp_cat as $ecp_cat_name)
            {
                $ecp_cat_score[$ecp_cat_name] = 0;                
            }

            $ecp_subcat_score;
            foreach($ecp_ds as $k=>$v)
            {
                if($k=="ecp_core")
                {
                    foreach($v as $k_cat=>$v_cat)
                    {
                        foreach($v_cat as $k_subcat)
                        {
                            $ecp_subcat_score[$k_cat][$k_subcat]= 0;
                        }

                    }
                }
            }

            foreach($ecp_score['rows_data'] as $row){
                //Global by Score Total
                $global_score += $row->score;

                 //cat by Score Total
                foreach($ecp_cat as $ecp_cat_name)
                {
                    if($row->cat == $ecp_cat_name)
                    {
                        $ecp_cat_score[$ecp_cat_name] += $row->score;

                        //sub cat by Score Total
                        foreach($ecp_subcat_score as $cat_name=>$arr_subcat_names)
                        {                           
                            foreach($arr_subcat_names as $subcat_key => $subcat_name)
                            {
                                if($row->cat == $ecp_cat_name && $row->sub_cat == $subcat_key)
                                {                                   
                                   $ecp_subcat_score[$ecp_cat_name][$subcat_key] += $row->score;
                                }
                            }
                        }
                    }
                }                 
            } 
            
                return [
                    "global_score" => $global_score,
                    "ecp_cat_score" => $ecp_cat_score,
                    "ecp_subcat_score" =>$ecp_subcat_score
                ];

        }

        function ecp_competence($template_url){
            $arr_glb_score = [140, 290, 420, 640];
            $arr_glb_lvl = ["Beginner","Intermediate","Competent","Expert"];
            $arr_io_score = [6,12,18,24];
            $arr_io_lvl = ["Foundation","Intermediate","Advanced","Expert"];
            $arr_re_score = [6,12,18,24];
            $arr_re_lvl = ["Foundation","Intermediate","Advanced","Expert"];
            $arr_ia_score = [12,18,36,48];
            $arr_ia_lvl = ["Foundation","Intermediate","Advanced","Expert"];
            $arr_io_c_score = [25,50,80];
            $arr_io_c_lvl = ["Foundation","Intermediate","Advanced","Expert"];
            $arr_re_c_score = [50,100,160];
            $arr_re_c_lvl = ["Foundation","Intermediate","Advanced","Expert"];
            $arr_ia_c_score = [30,60,96];
            $arr_ia_c_lvl = ["Foundation","Intermediate","Advanced","Expert"];
            $arr_focus_area_all = [];
            $current_level = [];
            $ecp_score = $this->ecp_score();          

           $logo = $this->reseller_data['rows_data']->logo;
           $this->pdf->setSourceFile($template_url);
            //Global Score check #START.
            if($ecp_score['global_score'] <= $arr_glb_score[0]){
                $tpl = $this->pdf->importPage(9);
            }elseif($ecp_score['global_score'] <= $arr_glb_score[1]){
                $tpl = $this->pdf->importPage(9);
            }elseif($ecp_score['global_score'] <= $arr_glb_score[2]){
                $tpl = $this->pdf->importPage(9);            
            }elseif($ecp_score['global_score'] <= $arr_glb_score[3]){
                $tpl = $this->pdf->importPage(9);
            }
            //Global Score check #END.

            
            $lvl_cat = "";
            
            
            $this->pdf->AddPage();
            $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
            $this->logo_check_size($logo);
            $this->pdf->SetFontSize(20);
            $this->pdf->SetXY(160,246);
            $this->pdf->Cell(20, 10,$ecp_score['global_score'], 0, 0, 'L');
                
            $tpl = $this->pdf->importPage(10);
                $this->pdf->AddPage();
                $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                $this->logo_check_size($logo);
                

            $tpl = $this->pdf->importPage(11);
                $this->pdf->AddPage();
                $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                $this->logo_check_size($logo);
                /* Ideas and Opportunities #START */
            if($ecp_score['ecp_cat_score']['Ideas and Opportunities'] <= $arr_io_c_score[0])
            $lvl_cat = $arr_io_c_lvl[0];
            elseif($ecp_score['ecp_cat_score']['Ideas and Opportunities'] <= $arr_io_c_score[1])
            $lvl_cat = $arr_io_c_lvl[1];
            elseif($ecp_score['ecp_cat_score']['Ideas and Opportunities'] <= $arr_io_c_score[2])
            $lvl_cat = $arr_io_c_lvl[2];
            else{
                $lvl_cat = $arr_io_c_lvl[3];
            }
            
            
            $tpl = $this->pdf->importPage(12);
            $this->pdf->AddPage();
            $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
            $this->pdf->SetFontSize(16);
            $this->pdf->SetXY(93,25);
            $this->pdf->Cell(20, 10,$lvl_cat, 0, 0, 'L');
            $this->pdf->SetFontSize(10);

            $sub_cat = "";
            $x = 155;
            $y = [125,138,150,162,175];
            $i=0;

            foreach($ecp_score['ecp_subcat_score']['Ideas and Opportunities'] as $k=>$v)
            {     

                if($v<$arr_io_score[0]){
                    $sub_cat = $arr_ia_lvl[0];                    
                    array_push($arr_focus_area_all,$k);
                    array_push($current_level,$sub_cat);
                }
                elseif($v<$arr_io_score[1]){
                    $sub_cat = $arr_ia_lvl[1];                   
                    array_push($arr_focus_area_all,$k);
                    array_push($current_level,$sub_cat);
                }
                elseif($v<$arr_io_score[2]){
                    $sub_cat = $arr_ia_lvl[2];
                }
                elseif($v<$arr_io_score[3]){
                    $sub_cat = $arr_ia_lvl[3];
                }
                else{
                    $sub_cat = $arr_ia_lvl[3];
                }
                $this->pdf->SetXY($x,$y[$i]);
                $this->pdf->Cell(20, 10,$sub_cat, 0, 0, 'L');
                $i += 1;
            }

             //Ideas and Opportunities #START
             $arr_score = $ecp_score["ecp_subcat_score"]["Ideas and Opportunities"];
             $arr_x_names =[] ;
             $arr_names_full=[];
             $arr_score_perc =[];
             $arr_focus_area=[];
             $chr_start = 833;
             foreach($arr_score as $k=>$v)
             {
                 array_push($arr_names_full,$k);
                 array_push($arr_score_perc, $v*100/24);
                 array_push($arr_x_names, chr($chr_start));
                 $chr_start +=1;
             }

            
             $this->draw_chart($arr_score_perc, $arr_x_names,
             $arr_names_full,"Ideas and Opportunities",213);
            $this->logo_check_size($logo);

             /* Ideas and Opportunities #END */
            /* Resources #START */

            if($ecp_score['ecp_cat_score']['Resources'] <= $arr_re_c_score[0])
            $lvl_cat = $arr_re_c_lvl[0];
            elseif($ecp_score['ecp_cat_score']['Resources'] <= $arr_re_c_score[1])
            $lvl_cat = $arr_re_c_lvl[1];
            elseif($ecp_score['ecp_cat_score']['Resources'] <= $arr_re_c_score[2])
            $lvl_cat = $arr_re_c_lvl[2];
            else{
             $lvl_cat = $arr_re_c_lvl[3];
            }

            $tpl = $this->pdf->importPage(14);
                $this->pdf->AddPage();
                $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                $this->pdf->SetFontSize(16);
                $this->pdf->SetXY(70,34);
                $this->pdf->Cell(20, 10,$lvl_cat, 0, 0, 'L');
                $this->pdf->SetFontSize(10);
                $sub_cat = "";
                $x = 165;
                $y = [130,142,158,170,187,202];
                $i=0;

            foreach($ecp_score['ecp_subcat_score']['Resources'] as $k=>$v)
            {               
                if($v<$arr_re_score[0]){
                    $sub_cat = $arr_re_lvl[0];
                    array_push($arr_focus_area_all,$k);
                    array_push($current_level,$sub_cat);
                }
                elseif($v<$arr_re_score[1]){
                    $sub_cat = $arr_re_lvl[1];
                    array_push($arr_focus_area_all,$k);
                    array_push($current_level,$sub_cat);
                }
                elseif($v<$arr_re_score[2]){$sub_cat = $arr_re_lvl[2];}
                elseif($v<$arr_re_score[3]){$sub_cat = $arr_re_lvl[3];}
                else{$sub_cat = $arr_re_lvl[3];}               
                $this->pdf->SetXY($x,$y[$i]);
                $this->pdf->Cell(20, 10,$sub_cat, 0, 0, 'L');               
                $i += 1;
            }
            
            
                //Resources #START
                $arr_score = $ecp_score["ecp_subcat_score"]["Resources"];
                $arr_x_names =[] ;
                $arr_names_full=[];
                $arr_score_perc =[];
                $arr_focus_area=[];
                $chr_start = 833;
                foreach($arr_score as $k=>$v)
                {
                    array_push($arr_names_full,$k);
                    array_push($arr_score_perc, $v*100/24);
                    array_push($arr_x_names, chr($chr_start));
                    $chr_start +=1;
                }
                
                $this->draw_chart($arr_score_perc, $arr_x_names,
                $arr_names_full,"Resources",235);
                $this->logo_check_size($logo);
                
                $tpl = $this->pdf->importPage(16);
                $this->pdf->AddPage();
                $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
                /* Into Action #START */

                if($ecp_score['ecp_cat_score']['Into Action'] <= $arr_ia_c_score[0])
                $lvl_cat = $arr_ia_c_lvl[0];
                elseif($ecp_score['ecp_cat_score']['Into Action'] <= $arr_ia_c_score[1])
                $lvl_cat = $arr_ia_c_lvl[1];
                elseif($ecp_score['ecp_cat_score']['Into Action'] <= $arr_ia_c_score[2])
                $lvl_cat = $arr_ia_c_lvl[2];
                else{
                $lvl_cat = $arr_ia_c_lvl[3];
                }
            
                $this->pdf->SetFontSize(10);

                //ECP subcat valiables for render table.
            $sub_cat = "";
            $x = 165;
            $y = [118,130,145,160,172];
            $i=0;

            foreach($ecp_score['ecp_subcat_score']['Into Action'] as $k=>$v)
            {               
                if($v<$arr_ia_score[0]){
                    $sub_cat = $arr_io_lvl[0];
                    array_push($arr_focus_area_all,$k);
                    array_push($current_level,$sub_cat);
                }
                elseif($v<$arr_ia_score[1]){
                    $sub_cat = $arr_io_lvl[1];
                    array_push($arr_focus_area_all,$k);
                    array_push($current_level,$sub_cat);
                }
                elseif($v<$arr_ia_score[2]){$sub_cat = $arr_io_lvl[2];}
                elseif($v<$arr_ia_score[3]){$sub_cat = $arr_io_lvl[3];}
                else{$sub_cat = $arr_io_lvl[3];}               
                $this->pdf->SetXY($x,$y[$i]);
                $this->pdf->Cell(20, 10,$sub_cat, 0, 0, 'L');
                $i += 1;
            }

            //Into Action #START
            $arr_score = $ecp_score["ecp_subcat_score"]["Into Action"];
            $arr_x_names =[] ;
            $arr_names_full=[];
            $arr_score_perc =[];
            $arr_focus_area=[];
            $chr_start = 833;
            foreach($arr_score as $k=>$v)
            {
                array_push($arr_names_full,$k);
                array_push($arr_score_perc, $v*100/48);
                array_push($arr_x_names, chr($chr_start));
                $chr_start +=1;
            }
          
            $this->draw_chart($arr_score_perc, $arr_x_names,
            $arr_names_full,"Into Action",214);

            $this->pdf->SetFontSize(16);
            $this->pdf->SetXY(92,24);
            $this->pdf->Cell(20, 10,$lvl_cat, 0, 0, 'L');
            $this->logo_check_size($logo);
          
            $tpl = $this->pdf->importPage(18);
            $this->pdf->AddPage();
            $this->pdf->useTemplate($tpl, ['adjustPageSize'=>true]); 
            $this->logo_check_size($logo);            
            $this->ecp_growth($arr_focus_area_all,$current_level);
                
        }

        
        function ecp_growth($ecp_focus_area_all,$current_level){
            $this->pdf->SetAutoPageBreak(true);

            $ecp_growth_reco = [];
            $increament_v = 1;
           for($a = 0;$a < sizeof($ecp_focus_area_all);$a++){
            $str_reco ='';
            foreach($this->CI->Commen_model
                ->ecp_reco($ecp_focus_area_all[$a],$current_level[$a])['rows_data'] as $v){
                
                $str_reco .= "(".$increament_v."). " .$v->recommendation."\n";
                $increament_v++;
            }
            $increament_v = 1;
            array_push($ecp_growth_reco,$str_reco);
            }

            $this->pdf->SetFont('Arial','',11);

            $cells = 3;
            $maxheight = 0;
            $height = 5;
       
            for($j = 0;$j < count($ecp_focus_area_all);$j++){

                $x = 25;
                $y = 80;
                $yn = 0;
                $page_height = 180;
                $height_of_cell=$y-$yn;
                $space_left=$page_height-($y); // space left on page
            
                if(!empty($j)){
                    $y = $this->pdf->GetY()+7;
                }
              
                for ($i = 0; $i < $cells; $i++) 
                {
                    
                    if($i==0)
                    {
                    // $this->pdf->Line($x + 42 * $i, $y, $x + 42 * $i, $y + $height);
                    $this->pdf->SetXY($x + (0) , $y);
                    $this->pdf->MultiCell(30, $height, $ecp_focus_area_all[$j],0,'L');
                        
                    }
                    else if($i==1)
                    {
                    
                        $this->pdf->SetXY($x + (42) , $y);
                        $this->pdf->MultiCell(30, $height,$current_level[$j],0,'L'); 
                    }
                    else if($i==2)
                    {
                                            
                        $this->pdf->SetXY($x + (84) , $y);                    
                        $this->pdf->MultiCell(78, $height,$ecp_growth_reco[$j],0,'J'); 
                    }
                }

            }
           
        }

        function draw_chart($arr_score_perc,$arr_x_names,$arr_name_full,$graph_name,$char_position = 213){

                //start graph
                $chartX=30;
                // $chartY=210;
                //dimension
                $chartY= $char_position;
                $chartWidth=100;
                $chartHeight=45;
                //padding
                $chartTopPadding=12;
                $chartLeftPadding=13;
                $chartBottomPadding=10;
                $chartRightPadding=5;
                //chart box
                $chartBoxX=$chartX+$chartLeftPadding;
                $chartBoxY=$chartY+$chartTopPadding;
                $chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
                $chartBoxHeight=$chartHeight-$chartTopPadding-$chartBottomPadding;
                //bar width
                $barWidth=8;
                //chart data
                $data = [];
                $cnt=0;
               
                foreach($arr_x_names as $x_name)
                {
                    $data[$x_name] = ['color'=>[($cnt+3)*50, $cnt*35,$cnt*5],
                                        'value'=>$arr_score_perc[$cnt]];
                    $cnt += 1;
                }

                $data2 = [];
                $size_of_full_name = sizeof($arr_name_full);
                for($i = 0;$i<$size_of_full_name;$i++)
                {
                    $temp_str = $arr_x_names[$i]."-".$arr_name_full[$i]; 
                    $data2[$temp_str]=['color'=>[($i+3)*50, $i*35,$i*5],
                                        'value'=>$temp_str];
                }
               
                //data max
                $dataMax=0;
                foreach($data as $item){
                    if($item['value']>$dataMax)$dataMax=100;
                } 
                //data step
                $dataStep=20;
                //set font, line width, color
                $this->pdf->SetFont('Arial', '',9);
                $this->pdf->SetLineWidth(0.2);
                $this->pdf->SetDrawColor(0);
                //chart boundary
                $this->pdf->Rect($chartX,$chartY,$chartWidth,$chartHeight);
                //vertical axis line
                $this->pdf->Line(
                    $chartBoxX,
                    $chartBoxY,
                    $chartBoxX,
                    $chartBoxY+$chartBoxHeight

                );
                //horizontal axis line
                $this->pdf->Line(
                    $chartBoxX,
                    $chartBoxY+$chartBoxHeight,
                    $chartBoxX+$chartBoxWidth,
                    $chartBoxY+$chartBoxHeight

                );
                //vertical axis calculate chart y axis scale unit
                $yAxisUnits=$chartBoxHeight/$dataMax;
                //draw vertical y axis label
                for($i=0;$i<=$dataMax; $i+=$dataStep){
                    //y position
                    $yAxisPos=$chartBoxY+($yAxisUnits*$i);
                    //draw y axis line
                    $this->pdf->Line(
                $chartBoxX-2,
                $yAxisPos,
                $chartBoxX,
                $yAxisPos     
                    );
                
                    //set cell position for y axis labels
                    $this->pdf->SetXY($chartBoxX-$chartLeftPadding,$yAxisPos-2);
                    //write label
                    $this->pdf->Cell($chartLeftPadding-4,5,$dataMax-$i,0,0,'R');
                    
                }
                //horizontal axis set cell width
                $this->pdf->SetXY($chartBoxX,$chartBoxY+$chartBoxHeight);
                //cell width
                $xLabelWidth=$chartBoxWidth/ count($data);
                //loop horizontal axis and draw the bars
                $barXPos=0;
                foreach($data as $itemName=>$item){
                    //print the label
                    $this->pdf->Cell($xLabelWidth,5,$itemName,0,0,'C');
                //drawing the bar
                //bar color
                $this->pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);
                //bar height
                $barHeight=$yAxisUnits*$item['value'];
                //bar x position
                $barX=($xLabelWidth/2)+($xLabelWidth*$barXPos);
                $barX=$barX-($barWidth/2);
                $barX=$barX+$chartBoxX;
                //bar y position
                $barY=$chartBoxHeight-$barHeight;
                $barY=$barY+$chartBoxY;
                //draw the bar
                $this->pdf->Rect($barX,$barY,$barWidth,$barHeight,'DF');
                //increment $barXPos
                $barXPos++;
                }
                $this->pdf->SetFont('Arial','B',16);
                $this->pdf->SetXY($chartX,$chartY);
                //$this->pdf->SetXY(($chartWidth/2)-50 + $chartX, $chartY + $chartHeight-($chartBottomPadding/2));
                $this->pdf->Cell(100,10,$graph_name,0,0,'C');


                //legend properties
                $legendX=133;
                $legendY=$char_position;

                //draw th legend
                $this->pdf->SetFont('Arial','',8.5);

                //store current legend Y position
                $currentLegendY=$legendY;
                foreach($data2 as $index=>$item)
                {
                //add legend color
                $this->pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);

                // remove border
                $this->pdf->SetDrawColor($item['color'][0],$item['color'][1],$item['color'][2]);
                $this->pdf->Rect($legendX,$currentLegendY+2,5,5,'DF');
                $this->pdf->SetXY($legendX+6,$currentLegendY+2);
                $this->pdf->Cell(50,5,$index,0,0);
                $currentLegendY+=5;
                }
                //make a Personality graph chart in pdf #END.
                
        }
        

        function logo_check_size($logo){
            $size = getimagesize($logo);
            $wImg = $size[0];
            $hImg = $size[1];
            $this->pdf->SetXY(155, 9);
            $this->pdf->SetFont('arial');
            $this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),58), 0, 0,'R',false);
          /*  if($wImg<=512 && $hImg<=512)
            {
                $this->pdf->SetXY(155, 9);
                $this->pdf->SetFont('arial');
                $this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),58), 0, 0,'R',false);
            }
            else
            {                
                $this->pdf->SetXY(150,10);
                $this->pdf->SetFont('arial');
                $this->pdf->Cell(0,20,$this->pdf->Image($logo,$this->pdf->GetX(), $this->pdf->GetY(),58), 0, 0,'R',false);
            } */
        }

        function post_college_pref(){
          
            $arr_pref_cat = ["vocation","Location"];            
            $logo = $this->reseller_data['rows_data']->logo;
            $this->logo_check_size($logo);
            $x_3 =  $this->CI->Commen_model->disha_pref_id_arr();
            $sub_cat = [];
            $cat = [];
            $score = [];
            foreach($x_3 as $v){
               $sub_cat[] = $v['sub_cat'];
               $cat[] = $v['cat'];
               $score[] = 0;
            }

            
            $count_of_num = sizeof($sub_cat);            
            $x_1 = $this->CI->Commen_model->disha_pref_id_count($this->code,$solution = "ce_disha_part_2",$opt_name="opt_1",$opt_value="1");
            $x_2 = $this->CI->Commen_model->disha_pref_id_count($this->code,$solution = "ce_disha_part_2",$opt_name="opt_2",$opt_value="2");
            foreach($x_1 as $v){
               
                    for($i = 0; $i < $count_of_num ; $i++)
                    {
                        if($v["pref_cat"] == $cat[$i] && $v['pref_sub_cat']==$sub_cat[$i])
                        {
                            $score[$i] +=1;
                            break;
                        }
                    }
            }
            foreach($x_2 as $v){
               
                    for($i = 0; $i < $count_of_num ; $i++)
                    {                        
                        if($v["pref_cat"] == $cat[$i] && $v['pref_sub_cat']==$sub_cat[$i])
                        {
                            $score[$i] +=1;
                            break;
                        }
                    }
            }

            $max_score_voc =0;
            $tot_score_loc =0;
            for($i=0;$i<$count_of_num;$i++)
            {
                if($cat[$i]==$arr_pref_cat[0]){
                    if($score[$i]>$max_score_voc)
                    {
                        $max_score_voc = $score[$i];
                    }
                }

                if($cat[$i]==$arr_pref_cat[1]){

                    $tot_score_loc += $score[$i];
                }
            }
            
            for($i=0;$i<$count_of_num;$i++)
            {
                if($cat[$i]==$arr_pref_cat[0]){
                    $score[$i] = intval($score[$i]*100/$max_score_voc);
                    if($score[$i] == 100){
                        $this->priority = $sub_cat[$i];
                    }
                }
                
                if($cat[$i]==$arr_pref_cat[1]){
                    $score[$i]= intval($score[$i]*100/$tot_score_loc);
                    if($score[$i] >= 50){
                        $this->abroad = true;
                    }else{
                        $this->abroad = false;
                    }
                }
            }

            $arr_score_perc =[];
            $arr_x_names =[] ;
            $arr_names_full=[];           
            $chr_start = 833;

            for($i=0;$i<$count_of_num;$i++){
                array_push($arr_score_perc, $score[$i]);
                array_push($arr_x_names, chr($chr_start));
                array_push($arr_names_full,$sub_cat[$i]);
                $chr_start +=1;
            }
            
           $this->draw_chart($arr_score_perc, $arr_x_names,
            $arr_names_full,"Post College",218);
            
            // $this->pdf->output();
           
        }


        function strength_indicator(){
            $logo = $this->reseller_data['rows_data']->logo;
            $this->logo_check_size($logo);

            $disha_cap_count = $this->CI->Commen_model->disha_cap_count($this->code,"ce_disha_part_1");
            
            $arr_cat = []; 
            $arr_score = [];
            $arr_cap_id = ["Study habits","Modern workplace skills","Entreprenuerial capacity"
            ,"Teaching","Immigration"];
            $arr_cap_disp = ["Research","Job","Entreprenuership","Academics","Abroad"];
            $arr_cat_score = [];
            $arr_cat_count = [];
            foreach($disha_cap_count as $v){             
               $arr_cat[] = $v['cat']; 
               $arr_score[] = $v['value']; 
            }

            foreach($arr_cap_id  as $v){
                $arr_cat_score[$v] =0;
                $arr_cat_count[$v] =0;
            }

            for($i = 0; $i < sizeof($arr_cat) ; $i++){
                foreach($arr_cap_id  as $v){
                    if($arr_cat[$i] == $v){
                        $arr_cat_score[$v] += $arr_score[$i];
                        $arr_cat_count[$v] +=1;
                        break;
                    }

                }
            }

            $arr_cat_score_v = [];
            foreach($arr_cat_score as $k => $v){
                $arr_cat_score_v[] = $v;               
            }
            $arr_cat_count_v = [];
            foreach($arr_cat_count as $k => $v){
                $arr_cat_count_v[] = $v;               
            }
            
            for($i = 0; $i < sizeof($arr_cat_count_v) ; $i++)
            {
                $arr_cat_score_v[$i] = intval($arr_cat_score_v[$i]*100/$arr_cat_count_v[$i]);
            
            }

            /* print_r($arr_cat_score_v);
            print_r($arr_cat_count); */
           
            $arr_score_perc =[];
            $arr_x_names =[] ;
            $arr_names_full=[];           
            $chr_start = 833;

            for($i=0;$i< sizeof($arr_cat_score_v) ;$i++){
                array_push($arr_score_perc, $arr_cat_score_v[$i]);
                array_push($arr_x_names, chr($chr_start));
                array_push($arr_names_full,$arr_cap_disp[$i]);
                $chr_start +=1;
            }
            
           $this->draw_chart($arr_score_perc, $arr_x_names,
            $arr_names_full,"Strength Indicator",202);

            // $this->abroad
            // print_r($this->priority);
        }

        function observations_remm(){
            $logo = $this->reseller_data['rows_data']->logo;
            $this->logo_check_size($logo);            
            $heading = $this->CI->Commen_model->priority_heading_check($this->priority);
            $parameter_details = $this->CI->Commen_model->priority_details_check($this->priority);
            // $parameter_details = $this->CI->Commen_model->priority_details_check();
           
            $this->pdf->SetFontSize(16);
            $this->pdf->SetXY(82,43);
            $this->pdf->Cell(35,10,$this->priority, 0, 0, 'L');
            $this->pdf->SetFontSize(12);
            $this->pdf->SetXY(65,63);
            $this->pdf->Multicell(115,6," - ".$heading->heading);

            $spacing_table = [102,131,160,188,218];
            $table_x = 25;
            $i = 0;
            $this->pdf->SetFontSize(9);
            foreach($parameter_details as $v){                
                $this->pdf->SetXY($table_x,$spacing_table[$i]);
                $this->pdf->Multicell(35,5,$v['parameter']);
                $this->pdf->SetXY($table_x+45,$spacing_table[$i]);
                $this->pdf->Multicell(110,5,$v['details']);
               
                $i++;
            }

           
            if($this->abroad){
                $heading = $this->CI->Commen_model->priority_heading_check("Abroad");
                $parameter_details = $this->CI->Commen_model->priority_details_check("Abroad");
                $this->page_import("OtherAjax/report_template/rpt_tpt_disha.pdf",5);
                $logo = $this->reseller_data['rows_data']->logo;
                $this->logo_check_size($logo); 
                $this->pdf->SetFontSize(16);
                $this->pdf->SetXY(82,43);
                $this->pdf->Cell(35,10,"Abroad", 0, 0, 'L');
                $this->pdf->SetFontSize(12);
                $this->pdf->SetXY(65,63);
                $this->pdf->Multicell(115,6," - ".$heading->heading);

                $spacing_table = [102,131,160,188,218];
                $table_x = 25;
                $i = 0;
                $this->pdf->SetFontSize(9);
                foreach($parameter_details as $v){                
                    $this->pdf->SetXY($table_x,$spacing_table[$i]);
                    $this->pdf->Multicell(35,5,$v['parameter']);
                    $this->pdf->SetXY($table_x+45,$spacing_table[$i]);
                    $this->pdf->Multicell(110,5,$v['details']);
                
                    $i++;
                }
            }
            $this->pdf->output();

        }

        // Disha function Code #END here.

        

} //class #END here.
?>