<?php

/**
 * Register function
 *
 * @return array registration status
 * ["status"=>,"error_message"=>,"data"=>[]]
 *
 */
function register($course_key, $email, $firstname, $lastname, $background, $role, $skills, $password, $passwordCheck){

	/**
	 * Declare global scope
	 */
	global $MODELS_DIR;
	
	/**
	 * Default return array
	 */
	$registration_data = ["email" => $email, 
					      "firstname" => $firstname,
					      "lastname" => $lastname,
					      "background" => $background,
					      "role" => $role,
					      "skills" => $skills,
					      "password" => $password];
	$registration_ = ["status"=>false, "error_message"=>"", "data"=>$registration_data];
	
	/**
	 * Check course key
	 */
	if ($course_key !== COURSE_KEY) {

		$registration_["error_message"] = "Wrong course key";
		return $registration_;

	}

	/**
	 * Check if passwords match
	 */
	if ($password !== $passwordCheck) {
		
		$registration_["error_message"] = "Passwords do not match.";
		return $registration_;
	}	

	/**
	 * Include and instatiate models
	 */
	require_once $MODELS_DIR . '/Account.php';
	$account = new Account();
	require_once $MODELS_DIR . '/Credentials.php';
	$credentials = new Credentials();
	
	/**
	 * Set account data
	 */
	$account->setEmail($email);
	$account->setName($firstname, $lastname);
	$account->setBackground($background);
	$account->setRole($role);
	$account->setSkills($skills);
	$account->setPassword($password);

	/**
	 * Check if email already exists
	 */
	if ($credentials->emailExists($account->getEmail())) {
		
		$registration_["error_message"] = "Email already exists.";
		return $registration_;
	}
	
	/**
	 * Validate inputs
	 */
	if ($account->isValid()) {
	
		// Execute query and evaluate result
		if ($credentials->register($account)) {
		    
		    // Send "Welcome email"
		    // ...
			$registration_["status"] = true;
	
		} else {
	
			// DB answered with error status
			$registration_["error_message"] = "We had some problem creating your account, try again and if the problem persist, please contact us at info@startuppuccino.com";
	
		}
	
	} else {
	
		$registration_["error_message"] = "Inputs are not valid.";
	
	}


	return $registration_;
}
