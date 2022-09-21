<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');


$config['seo_params'] = [
    "meta_desc"=>"",
    "meta_key"=>[],
    'title'=>''
  ];

$config['company_details'] = [
    "logo"=>'',
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


?>
