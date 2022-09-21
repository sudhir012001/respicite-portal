<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');



$config['asmt_map'] =[
    
    'sol_tbl'=>
    [
        'ocs'=>
        [
            '1'=>'uce_part1_2','2'=>'uce_part1_4','3'=>'uce_part1_4_2','4'=>'ce_nextmove_pref_que','5'=>'ce_nextmove_que','6'=>'asmt_convas_a','7'=>'asmt_convas_b','8'=>'asmt_convas_c'
        ],
        'ocss'=>
        [
            '1'=>'uce_part1_2','2'=>'uce_part1_4','3'=>'uce_part1_4_2','4'=>'ce_nextmove_pref_que','5'=>'ce_nextmove_que','6'=>'asmt_convas_a','7'=>'asmt_convas_b','8'=>'asmt_convas_c'
        ],
        'doccp'=>
        [
            '1'=>'uce_part1_2','2'=>'uce_part1_4','3'=>'uce_part1_4_2','4'=>'ce_nextmove_pref_que','5'=>'ce_nextmove_que','6'=>'asmt_convas_a','7'=>'asmt_convas_b','8'=>'asmt_convas_c'
        ],
        'doccps'=>
        [
            '1'=>'uce_part1_2','2'=>'uce_part1_4','3'=>'uce_part1_4_2','4'=>'ce_nextmove_pref_que','5'=>'ce_nextmove_que','6'=>'asmt_convas_a','7'=>'asmt_convas_b','8'=>'asmt_convas_c'
        ],
        'all_options'=>[
            'uce_part1_2'=>['within'=>false,'opt'=>'uce_part1_2','q_cnt'=>60,'has_right_answer'=>false],
            'uce_part1_4'=>['within'=>true, 'opt'=>['optionA','optionB','optionC'], 'q_cnt'=>26,'has_right_answer'=>false],
            'uce_part1_4_2'=>['within'=>true, 'opt'=>['optionA','optionB','optionC','optionD'],'q_cnt'=>24,'has_right_answer'=>false],
            'ce_nextmove_pref_que'=>['within'=>true, 'opt'=>['opt_1','opt_2'],'q_cnt'=>28,'has_right_answer'=>false],
            'ce_nextmove_que'=>['within'=>false,'opt'=>'cap_que','q_cnt'=>54,'has_right_answer'=>false],
            'asmt_convas_a'=>['within'=>true, 'opt'=>['optionA','optionB','optionC','optionD','optionE'],'q_cnt'=>120,'has_right_answer'=>true],
            'asmt_convas_b'=>['within'=>false, 'opt'=>'uce_part2_2','q_cnt'=>19,'has_right_answer'=>true],//
            'asmt_convas_c'=>['within'=>false, 'opt'=>'uce_part5','q_cnt'=>86,'has_right_answer'=>true]

        ]
        
    ]
];



?>
