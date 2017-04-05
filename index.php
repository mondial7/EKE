<?php

/**
 * Define development mode
 */
define("DEV_MODE", "off");  


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
 * Store memory usage
 */
if (defined("DEV_MODE") && DEV_MODE === "on") {

  $core_memory = round((memory_get_usage()/1024/1024), 3);
  $core_peak = round((memory_get_peak_usage(true)/1024/1024), 3);

}

/**
 * Handle user sessions
 *
 */
require CONTROLLERS_DIR . '/session.php';


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


/**
 * Store and show memory usage
 */
if (defined("DEV_MODE") && DEV_MODE === "on" && isset($_GET['dev'])) {

  $template_variables = [

    'core_memory' => $core_memory,
    'core_peak' => $core_peak,
    'total_memory' => round((memory_get_usage()/1024/1024), 3),
    'total_peak' => round((memory_get_peak_usage(true)/1024/1024), 3)

  ];
  
  EKETwig::setDir(VIEWS_DIR . "/components/");
  EKETwig::show("memory_monitor_dev.twig", $template_variables);

}