<?php

/**
 * Include session class and init new session
 */
require_once MODELS_DIR . '/Session.php';
Session::init();

/**
 * Check if current session contains already the user data
 */
$userLogged = Session::exists('username');

/**
 * Define useful global variables
 */
$account_id = Session::get('id');

/**
 * Prepare variables for twig template
 */
$template_variables['sess'] = $_SESSION;
$template_variables['userLogged'] = $userLogged;
