<?php

if ($userLogged) {

	require_once $MODELS_DIR . "/Cookie.php";

	// Delete Permalogin
	if (!is_null($cookie_token = Cookie::getPermaLogin())) {
		
		// delete record
		require_once $MODELS_DIR . '/Credentials.php';
		if (!(new Credentials())->removePermaLogin($cookie_token)) {
			// here set an application log error
		}

		// delete cookie
		Cookie::removePermaLogin();

	}

	Session::end();

}

header("Location: ../");