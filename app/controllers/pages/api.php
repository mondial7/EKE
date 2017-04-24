<?php

/**
 * Define the API names as whitelists
 */

/**
 * First level whitelist ('features whitelist')
 * Each feature has a folder in the API folder.
 */
$feature_whitelist = ['blog'];
/**
 * Actions whitelists
 * Each action is link to a feature: see variable names
 * The action name is the class name of the controller to be executed
 */
$blog_whitelist = ['articles'];

/**
 * Default instantiation and call to API Controllers
 *
 * @todo Generalize and move this part in the core, called by the router, so that the api controller is a reserved controller by default with possibility to rename it.
 */

// Validate whitelists
if (!isset($_GET['feature']) ||
    !isset($_GET['action'])) {

      // Default error answer
    	exit("{'error':'Wrong request'}");

} else if (!in_array($_GET['feature'], $feature_whitelist) ||
  		     !in_array($_GET['action'], ${$_GET['feature'] . "_whitelist"})) {

      // Default error answer
    	exit("{'error':'Access forbidden'}");

} else {

  $partial_path = $_GET['feature'] . DIRECTORY_SEPARATOR . $_GET['action'] . ".php";

}

// include api script/class and execute it
include_once API_DIR . DIRECTORY_SEPARATOR . $partial_path;
(new $_GET['action']())->run()->answer();
