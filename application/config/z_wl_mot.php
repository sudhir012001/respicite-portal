<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');


$config['seo_params'] = [
    "meta_desc"=>"",
    "meta_key"=>[]
  ];

$config['company_details'] = [
    "logo"=>'default_logo.png',
    "favicon"=>'',
    "add" => '',
    "mobile"=> '', 
    "email" => '',
    "url" => [
      "text"=>"",
      "link"=>""
    ]
  ];

  $config['other_details'] = [
    "tnc_banner"=>'',
  ];

  $config['timing'] = [
    "M2T"=>["text"=>"", "value"=>""],
    "F"=>["text"=>"", "value"=>""],
    "S"=>["text"=>"", "value"=>""],
    "Su"=>["text"=>"", "value"=>""],
  ];


  $config['footer_social_links'] = [
       "link_1"=>["text"=>"","link"=>""],
      "link_2"=>["text"=>"","link"=>""],
      "link_3"=>["text"=>"","link"=>""]
  ];

  $config['footer'] = [
    "section-1"=>'company_details',
    "section-2"=>'footer_social_links',
    "section-3"=>'timing'
   ];
  

   $config['services'] = [
    'overseas'
   ];


   $config['page_params_demo'] = 
   [
    'dashboard'=>
    [
    'title'=>'Marketing Optimization Dashboard',
    'bc'=>['1'=>'marketing/dashboard'],
    'img_path'=>'../assets/customers/506/mot/img/',
    'img_name'=>'graphs.webp',
    'tbls'=>[
      'Order Performance'=>
      [
        'head' =>['Platform'	 , 'Orders',	'cost/Order',	'Spend'],
        'row_1'=>['Google'	   , '30	   ',  '666.7'	   ,   '20000'],
        'row_2'=>['Facebook'	 , '10	   ',  '3000.0'	  ,  '30000'],
        'row_3'=>['Instagram'	,'25	   ' , '2400.0'	   , '60000'],
        'row_4'=>['Amazon'	   , '100	 ' , '500.0'	    ,  '50000'],

      ],
      'Engagement'=>
      [
      'head'=>['Platform','Google	',  'Facebook',  'Instagram', 'Amazon'],
      'row_1'=>['Orders	  ','30	    ', '10	   ',  '25','100'],
      'row_2'=>['Cost/Order	','666.7	',  '3000.0	   ',  '2400','500'],
      'row_3'=>['Total Spend	','20000',  '30000	   ',  '60000','50000'],
      
      ],  

    ]
    
  ]
]

   


?>
