<?php

$currentPage = "404";

$page_title = "You Are Lost - 404";
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
EKETwig::show("404.twig", $template_variables);