<?php
 
class AccountManager extends EKEModel {
 

    /**
     * @var int
     */
    private $account_id;
            
    /**
     * @var array
     */
    private $account_data;


    // constructor
    function __construct($account) {
        
        parent::__construct();

        // Declare database connection
        $this->connectDB();

        $this->account_id = $this->db->real_escape_string($account);
        $this->account_data = $this->readAccountData();

    }
 
    /**
     * Read the user data from the database
     */
    public function readAccountData() {

      $query = "SELECT * FROM "._T_ACCOUNT." WHERE id='".$this->account_id."';";

      $result = $this->db->query($query);

      if($result){

        // Return the array of data
        return $result->fetch_assoc();

      }
      
      // No account found
      // Return an empty array
      return [];

    }

    /**
     * Get the account role
     */
    public function getRole() {
    	return $this->account_data['role'];
    }


    /**
     * Update Account Data
     */
    public function updateAccount($email, $firstname, $lastname, $background, $skills, $about) {
      
      // Should be better here to doublecheck if some parameters is empty (not required now)

      // clean up strings to build the query
      $email = $this->db->real_escape_string($email);
      $firstname = $this->db->real_escape_string($firstname);
      $lastname = $this->db->real_escape_string($lastname);
      $background = $this->db->real_escape_string($background);
      $skills = $this->db->real_escape_string($skills);
      $about = $this->db->real_escape_string($about);

      $query = "UPDATE "._T_ACCOUNT." SET
                      email='".$email."',
                      firstname='".$firstname."',
                      lastname='".$lastname."',
                      background='".$background."',
                      skills='".$skills."',
                      about='".$about."' 
                      WHERE id='".$this->account_id."';";

      $this->db->query($query);

      if($this->db->affected_rows == 1){

        $log_data = [
                      "account" => $this->account_id,
                      "action" => "update info",
                      "data" => [
                                  "email" => $email,
                                  "firstname" => $firstname,
                                  "lastname" => $lastname,
                                  "background" => $background,
                                  "skills" => $skills,
                                  "about" => $about,
                                ]
                    ];

        $this->LOG->save( 'account', $log_data );

        return true;

      } else {

        return false;

      }

    }



    /**
     * Update User Password
     */
    public function updatePassword($old_password, $new_password) {
        
      $old_password = $this->db->real_escape_string($old_password);
      $new_password = $this->db->real_escape_string($new_password);

      if ($old_password != $new_password || !empty($new_password) ) {

        $query = "UPDATE "._T_ACCOUNT." 
                  SET password='".md5($new_password)."' 
                  WHERE id='".$this->account_id."' 
                  AND password='".md5($old_password)."';";

        $this->db->query($query);

        if ($this->db->affected_rows == 1) {

          $log_data = [
                        "account" => $this->account_id,
                        "action" => "update password"
                      ];

          $this->LOG->save( 'account', $log_data );

          return true;

        }
      
      }

      return false;

    }

    /**
     *  Save New Social Data
     */
    public function saveSocialdata($socialdata) {

      $socialdata = $this->db->real_escape_string($socialdata);

      if(!empty($socialdata)){

        $query = "UPDATE "._T_ACCOUNT." SET socials='".$socialdata."' WHERE id='".$this->account_id."';";

        $this->db->query($query);

        if ($this->db->affected_rows == 1) {

          $log_data = [
                        "account" => $this->account_id,
                        "action" => "update socialdata",
                        "data" => $socialdata
                      ];

          $this->LOG->save( 'account', $log_data );

          return true;

        }

      }

      return false;

    }

    /**
     *  Save New Profile Picture
     */
    public function saveProfilePicture($avatar) {

      // Check if the new avatar has the same name (extension) of the old one
      if($this->account_data["avatar"] == $avatar){
        // Do not need to update the db
        return true;
      }

      $avatar = $this->db->real_escape_string($avatar);

      if(!empty($avatar)){

        $query = "UPDATE "._T_ACCOUNT." SET avatar='".$avatar."' WHERE id='".$this->account_id."';";

        $this->db->query($query);

        if ($this->db->affected_rows == 1) {

          $log_data = [
                        "account" => $this->account_id,
                        "action" => "update avatar",
                        "data" => $avatar
                      ];

          $this->LOG->save( 'account', $log_data );

          return true;

        }

      }

      return false;

    }

    /**
     *  Delete Profile Picture
     */
    public function deleteProfilePicture() {

      $path = dirname( __DIR__ ) . "/assets/pics/people/".$this->account_data["avatar"];

      $query = "UPDATE "._T_ACCOUNT." SET avatar='' WHERE id='".$this->account_id."';";

      $this->db->query($query);

      if ($this->db->affected_rows == 1 && unlink($path)) {

        $log_data = [
                      "account" => $this->account_id,
                      "action" => "delete avatar",
                      "data" => $this->account_data["avatar"]
                    ];

        $this->LOG->save( 'account', $log_data );

        return true;

      }

      return false;

    }    

}

?>