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
            $name,
            $surname,
            $username,
            $password;

    /**
     * Account properties : int
     *
     * @var int
     */
    private $role;

    /**
     * Account array [string]
     *
     * @var array of strings
     */
    private $roles = ['farmer','...'];

    function __construct(){

      parent::__construct();

      $this->properties = ['email','name','surname','username','password','role'];

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
     * Sanitize and set the account first name and sanitize
     *
     * @param string
     * @return void
     *
     */
    public function setName($name) {

        $this->name = $this->clearText($name);

    }

    /**
     * Sanitize and set the account last name
     *
     * @param string
     * @return void
     *
     */
    public function setSurname($surname) {

        $this->surname = $this->clearText($surname);

    }

    /**
     * Validate, sanitize and set the account role
     *
     * @param int
     * @return void
     *
     */
    public function setRole($role) {

        $this->role = $this->clearNumber($role);

        // Extra check on input (kind of 'enum')
        if (!in_array($this->role, $this->roles)) {

            $this->valid = false;

        }

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

    public function getName(){

      return $this->name;

    }

    public function getSurname(){

      return $this->surname;

    }

    public function getUsername(){

      return $this->username;

    }

    public function getPassword(){

      return $this->password;

    }

    public function getRole(){

      return $this->role;

    }

    /**
     * Extra setter and Getter
     * for a shortcut for the full name
     */

    /**
     * Set the account name
     *
     * @param string
     * @param string
     * @return void
     *
     */
    public function setFullName($first, $last) {

        $this->setName($first);
        $this->setSurname($last);

    }

    /**
     * Get the account name
     *
     * @return string name
     *
     */
    public function getFullName() {
        return $this->name . " " . $this->surname;
    }

}
