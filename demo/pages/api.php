<?php

/**
 * Define the API names as whitelists
 */

/**
 * First level whitelist ('features whitelist')
 * NOTE Each feature has a folder in the API folder.
 */
$feature_whitelist = ['auth','blog'];

/**
 * Actions whitelists
 * NOTE Each action is link to a feature: see variable names
 * The action name is the class name of the api controller to be executed
 */
$auth_whitelist = ['signin','signout','signup'];
$blog_whitelist = ['articles'];

/**
 * Include Api controller script
 */
require_once CONTROLLERS_DIR . '/_api.php';
