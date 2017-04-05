<?php

/**
 * Include session class and init new session
 */
require_once MODELS_DIR . '/Session.php';
Session::init();


/**
 * Import Cookie Class
 */
require_once MODELS_DIR . "/Cookie.php";


/**
 * Check if current session contains already the user data
 */
$userLogged = Session::exists('firstname');


/**
 * Check if the user required to stay logged in
 */
if (!$userLogged) {

	// Get required_logged_id cookie
	$cookie_token = Cookie::getPermaLogin();

	if (!is_null($cookie_token)) {

		/**
		 * Perform Autologin
		 */
		// ...
		// ...

		/**
		 * Check if current session contains the user data
		 * that is, has been correctly logged in
		 */
        if ($userLogged = Session::exists('firstname')) {

        	// do nothing

        } else {
        	
        	// Remove cookie permalogin, since there is no match in the db
        	Cookie::removePermaLogin();

        }

    }

}


/**
 * Define useful global variables
 */
$account_id = Session::get('id');
$isAdmin = Session::is('role', "admin");
$isGuest = Session::is('role', "guest");


/**
 * Prepare variables for twig template
 */ 
$template_variables['sess'] = $_SESSION;
$template_variables['userLogged'] = $userLogged;