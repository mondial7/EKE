<?php

if ($userLogged || (!IS_REAL_CLIENT && SP_CLIENT !== "localhost")) {
    header("Location: ../");
    exit;
}


$currentPage = "register";
$page_title = "Register - Startuppuccino";
$metatags = [
                [
                    "kind" => "link",
                    "type" => "text/css",
                    "rel"  => "stylesheet",
                    "href" => "app/assets/css/register.css"
                ]
            ];
$footer_scripts = [];



if (isset($_POST['submit'])) {

    // Include register function
    require_once $CONTROLLERS_DIR . '/_register.php';
    $registration_ = register($_POST['course_key'],
                              $_POST['email'],
                              $_POST['firstname'],
                              $_POST['lastname'],
                              $_POST['background'],
                              $_POST['role'],
                              $_POST['skills'], 
                              $_POST['password'], 
                              $_POST['password_check']);

    if ($registration_["status"]) {
    
        // automatic login
        require_once $CONTROLLERS_DIR . '/_login.php';
        if (login($_POST['email'], $_POST['password'], true)) {
    
            // Redirect to home page
            header("Location: ../");
            // Client redirect if header fails
            echo "<script>window.location='../'</script>";

        }
    
    } else {
    
        $template_variables['error_message'] = $registration_['error_message'];
        $template_variables['placeholder'] = $registration_['data'];
    
    }

}


// Include header and footer controllers
include 'page__init.php';

// Set template name and variables

$template_file = "register.twig";

$template_variables['page_title'] = $page_title;
$template_variables['metatags'] = $metatags;
$template_variables['footer_scripts'] = $footer_scripts;

// Render the template
require_once $CONTROLLERS_DIR . '/Twig_Loader.php';
Twig_Loader::show($template_file, $template_variables);