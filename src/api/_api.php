<?php

/**
 * Validate, instantiate and execute required api controller
 *
 * @todo Generalize and move this part in the core, called by the router,
 * so that the api controller is a reserved controller by default.
 */

// Validate whitelists
if (!isset($_GET['feature']) ||
    !isset($_GET['action'])) {

      // Default error answer
    	exit('{"error":"Wrong request"}');

} else if (!in_array($_GET['feature'], $feature_whitelist) ||
  		     !in_array($_GET['action'], ${$_GET['feature'].'_whitelist'})) {

      // Default error answer
    	exit('{"error":"Access forbidden"}');

}

// include api script/class and execute it
include_once API_DIR .'/'. $_GET['feature'] .'/'. $_GET['action'].'.php';
(new $_GET['action']())->run()->answer();
