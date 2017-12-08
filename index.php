<?php

/**
 * Prevent showing errors and warnings
 * (uncomment while developing)
 */
//error_reporting(0);


/**
 * Load application core
 */
require __DIR__ . '/app/core/autoload.php';


/**
 * Handle user sessions
 *
 */
require CONTROLLERS_DIR . '/_session.php';


/**
 * Define routes
 */
Dump_Router::route('/',[
  'controller' => "landing"
]);


/**
 * Declare routes where router will not apply
 * Currently helpful for ajax direct calls to controllers
 */
Dump_Router::noRoute('app');


/**
 * Trigger the router and evaluate the uri path
 */
require Dump_Router::loadController($_SERVER['REQUEST_URI'],
                                    "./app/controllers/pages/");
