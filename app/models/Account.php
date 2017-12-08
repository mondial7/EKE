<?php

/**
 * Account Model
 *
 */
class Account extends EKEEntityModel {

    /**
     * Account properties : string
     *
     * @var string
     */
    private $email,
            $username,
            $password;


    function __construct(){

      parent::__construct();

      $this->properties = ['email','username','password'];

    }

    /**
     * Validate, sanitize and set the account email and validate
     *
     * @param string
     * @return void
     *
     */
    public function setEmail($email) {

      $this->email = $this->validateEmail($email);

    }

    /**
     * Set the account password
     * no need to sanitize or validate since we
     * are going to hash it
     *
     * @param string
     * @return void
     *
     */
    public function setPassword($password) {

        require_once MODELS_DIR . '/PasswordUtility.php';
        $this->password = PasswordUtility::hash($password);

    }

    /**
     * Setter of the username
     */
    public function setUsername($username) {

      $this->username = $this->clearText($username);

    }

    /**
     * Getters
     */

    public function getEmail(){

      return $this->email;

    }

    public function getUsername(){

      return $this->username;

    }

    public function getPassword(){

      return $this->password;

    }

}
