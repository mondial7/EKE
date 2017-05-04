<?php

if ($userLogged || (!IS_REAL_CLIENT && SP_CLIENT !== "localhost")) {
    header("Location: ../");
    exit;
}

// Include login function
require_once $CONTROLLERS_DIR . '/_login.php';


$currentPage = "login";
$page_title = "Login - Startuppuccino";
$metatags = [
                [
                    "kind" => "link",
                    "type" => "text/css",
                    "rel"  => "stylesheet",
                    "href" => "app/assets/css/login.css"
                ]
            ];
$footer_scripts = [];


$login_data = ["email"=>"","password"=>""];


if (isset($_POST['login'])) {

    $isPermaLogin = isset($_POST['permalogin']) && ($_POST['permalogin'] === "y");

    $loginOk = login($_POST['email'], $_POST['password'], $isPermaLogin);
    
    if ($loginOk) {

        header("Location: ../");
        // Client redirect if header fails
        echo "<script>window.location='../'</script>";
    
    }

    $resetOk = true;

} else if (isset($_POST['reset_password'])) {

    $resetOk = reset_password($_POST['email']);

    if ($resetOk) {

        // Reload the page with a successfull message
        header("Location: ./?reset&reset_done");
        // Client redirect if header fails
        echo "<script>window.location='./?reset&reset_done'</script>";

    }

    $loginOk = true;

} else {

    // initialize variable to prevent to show the error message
    $loginOk = true;
    $resetOk = true;
    
}

// Include header and footer controllers
include 'page__init.php';

// Set template name and variables

$template_file = "login.twig";

$template_variables['page_title'] = $page_title;
$template_variables['metatags'] = $metatags;
$template_variables['footer_scripts'] = $footer_scripts;

$template_variables['reset_password'] = isset($_GET['reset']);
$template_variables['reset_password_success'] = isset($_GET['reset_done']);
$template_variables['resetOk'] = $resetOk;
$template_variables['loginOk'] = $loginOk;
$template_variables['login_data'] = $login_data;


// Render the template
require_once $CONTROLLERS_DIR . '/Twig_Loader.php';
Twig_Loader::show($template_file, $template_variables);