<?php

$currentPage = "";

$page_title = "EKE - Your new home";
$metatags = [
                [
                    "kind" => "link",
                    "type" => "text/css",
                    "rel"  => "stylesheet",
                    "href" => "app/assets/css/style.css"
                ]
            ];
$footer_scripts = [];


// Include header and footer controllers
include 'page__init.php';


// Set template variables
$template_variables['page_title'] = $page_title;
$template_variables['metatags'] = $metatags;
$template_variables['footer_scripts'] = $footer_scripts;

// Render the template
EKETwig::show("landing.twig", $template_variables);