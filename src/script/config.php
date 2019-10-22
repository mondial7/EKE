<?php

/**
 * Set session cookie properties
 */

// **PREVENTING SESSION HIJACKING**
// Prevents javascript XSS attacks aimed to steal the session ID
ini_set('session.cookie_httponly', 1);

// **PREVENTING SESSION FIXATION**
// Session ID cannot be passed through URLs
ini_set('session.use_only_cookies', 1);

// Uses a secure connection (HTTPS) if possible
ini_set('session.cookie_secure', 1);

/**
 * Define headers for security issues
 * NOTE already defined in the htaccess
 */
// header("X-Frame-Options: SAMEORIGIN");
// header("X-XSS-Protection: 1");

/**
 * Constants available in any application script
 */

define('APP_DIR', dirname( dirname( __DIR__ ) ));

define('CONTROLLERS_DIR', APP_DIR . '/controllers');

define('MODELS_DIR', APP_DIR . '/models');

define('VIEWS_DIR', APP_DIR . '/templates');

define('CORE_DIR', APP_DIR . '/core');

define('API_DIR', APP_DIR . '/api');

define('UTILS_DIR', APP_DIR . '/utils');
