<?php

if ($userLogged || (!IS_REAL_CLIENT && SP_CLIENT !== "localhost")) {
    header("Location: ../");
    exit;
}

// Include login function
require_once $CONTROLLERS_DIR . '/_login.php';


$currentPage = "login";
$page_title = "Reset - Startuppuccino";
$metatags = [
                [
                    "kind" => "link",
                    "type" => "text/css",
                    "rel"  => "stylesheet",
                    "href" => "app/assets/css/login.css"
                ]
            ];
$footer_scripts = [];

$resetOk = true;

if (isset($_GET['success'])) {

    // Nothing happen, only a message is shown

} else if (isset($_POST['reset'])) {

    if ($_POST['new_password'] != $_POST['new_password_repeat']) {

        $resetOk = false;

    } else if (!Session::exists('temp_user_id') || !Session::exists('temp_secret_hash') || !Session::exists('temp_user_email')) {

        $resetOk = set_new_password($_POST['new_password']);

        if ($resetOk) {

            // Reload the page with a successfull message
            header("Location: ./?success");
            // Client redirect if header fails
            echo "<script>window.location='./?success'</script>";

        }

    }

} else {

    /**
      * Check if parameters are correct
      * save parameters in current session
      */

    require_once $MODELS_DIR . '/PasswordUtility.php';
    require_once $MODELS_DIR . '/Credentials.php';
    
    $email = (new Credentials)->getEmail($_POST['user_id']);

    if (!empty($email)) {

        // Check if the hash is correct
        if (PasswordUtility::match(md5('sp_secret') . $email), $_POST['secret_hash'])) {

            Session::add('temp_user_id', $_POST['user_id']);
            Session::add('temp_secret_hash', $_POST['secret_hash']);
            Session::add('temp_user_email', $email);

        }

    }

}

// Include header and footer controllers
include 'page__init.php';

// Set template name and variables

$template_file = "reset.twig";

$template_variables['page_title'] = $page_title;
$template_variables['metatags'] = $metatags;
$template_variables['footer_scripts'] = $footer_scripts;

$template_variables['reset_password_success'] = isset($_GET['success']);
$template_variables['resetOk'] = $resetOk;


// Render the template
require_once $CONTROLLERS_DIR . '/Twig_Loader.php';
Twig_Loader::show($template_file, $template_variables);