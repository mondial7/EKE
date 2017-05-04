<?php

/**
 * Login function
 *
 * @return boolean login success status
 *
 */
function login($login_email, $login_password, $isPermaLogin = false){
	
	/**
	 * Declare global scope
	 */
	global $login_data, $MODELS_DIR;
	
	/**
	 * Include and instatiate models
	 */
	require_once $MODELS_DIR . '/Credentials.php';
	require_once $MODELS_DIR . '/Account.php';
	$account = new Account();
	$credential_func = new Credentials();
	
	/**
	 * Set user inputs
	 */
	$account->setEmail($login_email);
	$account->setPassword($login_password);
	
	/**
	 * Perform login
	 */
	$account_data = $credential_func->login($account);
	
	/**
	 * Set defalut values of email
	 */
	$login_data['email'] = $login_email;
	
	/**
	 * default global variable to switch and redirect if login is successful 
	 * or show error message on login_form
	 */
	$loginOk = (count($account_data) > 0);
	
	/**
	 * If login is successful: 
	 * - save session data
	 * - save cookie for permanent login
	 * - redirect to home page
	 */
	if ($loginOk) {
		
		require_once $MODELS_DIR . '/Session.php';
		require_once $MODELS_DIR . '/Cookie.php';

		// Set session data
		Session::addArray($account_data);

		// Check if a persistent login has been required
		if  ($isPermaLogin) {
			
			$cookie_token = Cookie::addPermaLogin();

			if (!$credential_func->addPermaLogin($cookie_token)) {
				// catch error
			}

		}

	}

	return $loginOk;
}

/**
 * Reset password 
 *
 * @return boolean password reset success status
 */
function reset_password($email){

	/**
	 * Declare global scope
	 */
	global $login_data, $MODELS_DIR;

	/**
	 * Include and instatiate models
	 */
	require_once $MODELS_DIR . '/Credentials.php';
	$credential_func = new Credentials();

	// Set defalut values of email
    $login_data = ['email' => $email];

	return (new Credentials())->reset_password($email);

}


/**
 * Set the new password 
 *
 * @return boolean password reset success status
 */
function set_new_password($password){

	/**
	 * Declare global scope
	 */
	global $MODELS_DIR;

	/**
	 * Include and instatiate models
	 */
	require_once $MODELS_DIR . '/Credentials.php';
	$credential_func = new Credentials();

	return (new Credentials())->setNewPassword($password);

}