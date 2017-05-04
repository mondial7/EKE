<?php

class Credentials extends EKEModel {
 
    /**
     * @var int
     */
    private $temporary_id;


    function __construct() {

        parent::__construct();

        // Declare database connection
        $this->connectDB();

    }
 

    /**
     * Evaluate login
     *
     * @param Account account to log in
     * @return array account data
     *
     */
    public function login(Account $user) {
        
        $result = [];
        $email = $user->getEmail();
        $password = $user->getPassword();
        
        $query = "SELECT id, about, avatar, background, skills, email, firstname, lastname, role
                  FROM " . _T_ACCOUNT . " 
                  WHERE email = ?
                  AND password = ? ";

        if($stmt = $this->db->prepare($query)){
        
            $stmt->bind_param('ss', $email, $password);
            $stmt->execute();
            $db_result = $stmt->get_result();

            // query result is ok if only one match is found (one account)        
            if ($db_result && $db_result->num_rows == 1) {
        
                $result = $db_result->fetch_assoc();
                $result["clientid"] = md5(SP_CLIENT);
        
            }
        
            $stmt->close();
        
        }
        
        // No account found
        // Return an empty array
        return $result;

    }


    /**
     * Evaluate autologin
     *
     * @param string
     * @return array empty or with user data
     */
    public function tryAutologin($cookie_token){

        $result = [];

        $query = "SELECT a.id, a.about, a.avatar, a.background, 
                  a.skills, a.email, a.firstname, a.lastname, a.role
                  FROM " . _T_ACCOUNT . " a
                  JOIN " . _T_ACCOUNT_LOGGED . " al
                  ON a.id = al.account_id
                  WHERE al.cookie_token = ? ";

        if($stmt = $this->db->prepare($query)){

            $stmt->bind_param('s', $cookie_token);
            $stmt->execute();
            $db_result = $stmt->get_result();

            // query result is ok if only one match is found (one account)
            if($db_result && $db_result->num_rows == 1){

                $result = $db_result->fetch_assoc();
                $result["clientid"] = md5(SP_CLIENT);

            }

            $stmt->close();

        }

        return $result;

    }


    /**
     * Register new user
     *
     * @param Account new account to register
     * @return boolean status of registration
     *
     */
    public function register(Account $user){

        $result = false;
        $background = $user->getBackground();
        $skills = $user->getSkills();
        $email = $user->getEmail();
        $firstname = $user->getFirstName();
        $lastname = $user->getLastName();
        $password = $user->getPassword();
        $role = $user->getRole();
        
        $query = "INSERT INTO "._T_ACCOUNT." (background, skills, email, firstname, lastname, password, role)
                  VALUES ( ? , ? , ? , ? , ? , ? , ? );";
        
        if ($stmt = $this->db->prepare($query)) {
        
            $stmt->bind_param('sssssss', $background,
                                         $skills,
                                         $email,
                                         $firstname,
                                         $lastname,
                                         $password,
                                         $role);
            $stmt->execute();
            
            if($stmt->affected_rows == 1){
        
                $result = true;
                
            }
        
            $stmt->close();
        
        }
        
        return $result;
    
    }


    /**
     * Store Permanent Login
     *
     * @param int account id
     * @param string cookie token
     * @return boolean success status
     */
    public function addPermaLogin($cookie_token) {

        $result = false;

        $query = "INSERT INTO "._T_ACCOUNT_LOGGED." (account_id, cookie_token)
                VALUES ( ? , ? );";

        if ($stmt = $this->db->prepare($query)) {
          
        $stmt->bind_param('is', Session::get('id'), $cookie_token);
        $stmt->execute();

        if($stmt->affected_rows == 1){
            $result = true;
        }

        $stmt->close();

        }

        return $result;

    }


    /**
     * Remove permanent login 
     *
     * Delere account logged record
     * 
     * @param string
     * @return boolean
     */
    public function removePermaLogin($cookie_token) {
      
        $result = false;

        $query = "DELETE FROM "._T_ACCOUNT_LOGGED." 
                WHERE account_id = ? 
                AND cookie_token = ? ;";

        if ($stmt = $this->db->prepare($query)) {
              
            $stmt->bind_param('is', Session::get('id'), $cookie_token);
            $stmt->execute();

            if($stmt->affected_rows == 1){
                $result = true;
            }

            $stmt->close();

        }

        return $result;

    }



    /**
     * Check if email already exists
     *
     * @param string email
     * @return boolean
     */
    public function emailExists($email){
        
        $query = "SELECT id FROM "._T_ACCOUNT." WHERE email = ? ;";

        if($stmt = $this->db->prepare($query)){
        
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $db_result = $stmt->get_result();
        
            if($db_result && $db_result->num_rows > 0){
                
                $this->temporary_id = $db_result->fetch_assoc()['id'];
                return true;

            }
        
            $stmt->close();
        
        }
        
        return false;
    
    }



    /**
     * Reset Password
     */
    public function reset_password($address){
    
        if (!$this->emailExists($address)) {
            return false;
        }

        $result = false;

        $old_password = $this->getPassword($address);
        $new_password = $this->generateResetPasswordHash($address);
        $secret_link = "https://startuppuccino.com/reset/" . $this->temporary_id . "/" . $new_password;

        $query = "UPDATE " . _T_ACCOUNT . "
                  SET password = ?
                  WHERE email = ? ";

        // Set email
        $email = new EKEMail();
        $email->setMail($address, "Reset Password - Startuppuccino");
        $email->setMessage("As you asked we are resetting your password, please follow the link in order to set your new password " . $secret_link);
        $email->setFrom("Startuppuccino <info@startuppuccino.com>");

        if ($stmt = $this->db->prepare($query)) {
            
            $stmt->bind_param('ss', $new_password, $address);
            $stmt->execute();

            if ($stmt->affected_rows == 1) {

                if ($email->send()) {

                    $result = true;

                } else {

                    // Restore the old password
                    $stmt->bind_param('ss', $old_password, $address);
                    $stmt->execute();

                    if ($stmt->affected_rows != 1) {
                        // catch error
                    }

                }
            
            }

            $stmt->close();

        }

        return $result;

    }

    /**
     * Get account password based on email
     *
     * @param string email
     * @return string password
     */
    private function getPassword($email){

        $query = "SELECT password 
                  FROM " . _T_ACCOUNT . "
                  WHERE email = ? ";

        if ($stmt = $this->db->prepare($query)) {
            
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows == 1) {

                $password = $result->fetch_assoc()['password'];
            
            }

            $stmt->close();

        }

        return $password;

    }

    /**
     * Get account email based on id
     *
     * @param int id
     * @return string password
     */
    public function getEmail($id){

        $query = "SELECT email 
                  FROM " . _T_ACCOUNT . "
                  WHERE id = ? ";

        if ($stmt = $this->db->prepare($query)) {
            
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows == 1) {

                $email = $result->fetch_assoc()['email'];
            
            }

            $stmt->close();

        }

        return $email;

    }

    /**
     * Set new password
     *
     * @param string
     * @return boolean
     */
    public function setNewPassword($password){

        $result = false;
        $account_id = Session::get('temp_user_id');
        $address = Session::get('temp_user_email');

        $old_password = $this->getPassword($address);
        $new_password = $this->generateResetPasswordHash($address);
        $secret_link = "https://startuppuccino.com/reset/" . $this->temporary_id . "/" . $new_password;

        $query = "UPDATE " . _T_ACCOUNT . "
                  SET password = ?
                  WHERE email = ?
                  AND id = ? ";

        // Set email
        $email = new EKEMail();
        $email->setMail($address, "New Password - Startuppuccino");
        $email->setMessage("Your password has been correctly updated.");
        $email->setFrom("Startuppuccino <info@startuppuccino.com>");

        if ($stmt = $this->db->prepare($query)) {
            
            $stmt->bind_param('ssi', $password, $address, $account_id);
            $stmt->execute();

            if ($stmt->affected_rows == 1) {

                $result = true;

                if (!$email->send()) {
                    // catch error
                }
            
            }

            $stmt->close();

        }

        return $result;

    }

    /**
     * Generate the hash to reset the password
     *
     * @return string
     */
    private function generateResetPasswordHash($email){

        require_once __DIR__ . "/PasswordUtility.php";

        return PasswordUtility::hash(md5('sp_secret') . $email);

    }


    /**
      * Generate a random password
      */ 
    public function generatePassword(){

        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        
        for ($i = 0; $i < 8; $i++) {
            $n = mt_rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    
    }


}