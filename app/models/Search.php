<?php
 
class Search extends EKEModel {

    /**
     * @var array [string]
     */
    private $result_set;

    function __construct() {

        parent::__construct();

        // Declare database connection
        $this->connectDB();
        
        $this->result_set = $this->loadResults();
    }
    

    /**
     * Return a json object with all results
     */
    public function getAll(){
        
        $result_set = [];
        foreach ($this->result_set as $key => $value) {
            $result_set[$key] = array_map("utf8_encode", $value);
        }

        return json_encode($result_set);

    }

    /**
     * Get all results (People,Projects, Ideas)
     * Temporary only people and ideas, TODO -> finish after new db is ready
     */
    private function loadResults(){
        
        $users = $this->loadUsers();
        $ideas = $this->loadIdeas();

        $result = $users;

        if(count($ideas) > 0){
            foreach ($ideas as $idea) {
                array_push($result, $idea);
            }
        }

        return $result;

    }

    /**
     * Get all Users
     */
    private function loadUsers(){
    
        $query = "SELECT id, firstName, lastName, role, avatar  FROM "._T_ACCOUNT.";";

        $result = $this->db->query($query);

        if($result){

            while ($user = $result->fetch_assoc()){

                $users[] = ["id"=>"people/" . $user['id'],
                            "name"=>$user['firstName']." ".$user['lastName'],
                            "role"=>$user['role'],
                            "avatar"=>"people-profile/img/" . $user['avatar']];

            }

            return $users;

        }

        // No result found [should never reach this point]
        // Return an empty array
        return [];

    }

    /**
     * Get all Ideas
     *
     * @todo adapt id: ideas/#i or startups/#i
     */
    private function loadIdeas(){
    
        $query = "SELECT id, title, avatar  FROM "._T_PROJECT;

        $result = $this->db->query($query);

        $ideas = [];

        if($result){

            while ($idea = $result->fetch_assoc()){

                $ideas[] = ["id"=>"ideas/#i" . $idea['id'],
                            "name"=>$idea['title'],
                            "role"=>"",
                            "avatar"=>"projects-profile/img/" . $idea['avatar']];

            }

        }

        return $ideas;

    }
}

?>