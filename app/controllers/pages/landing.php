<?php

$currentPage = '';

// Include header and footer controllers
include 'page__init.php';

// Set template variables
$template_variables['page_title'] = 'EKE - Your new home';
$template_variables['metatags'] = [
  [
    'kind'  => 'link',
    'type'  => 'text/css',
    'rel'   => 'stylesheet',
    'href'  => 'app/assets/css/style.css'
  ]
];
$template_variables['footer_scripts'] = [];

// Render the template
EKETwig::show('landing.twig', $template_variables);
