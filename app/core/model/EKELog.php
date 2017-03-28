<?php
/**
 * @todo avoid to execute a query to a specific db table from the core of framework
 */

class EKELog {
	
    /**
     * Database connection
     *
     * @var Object mysqli
     */
    protected $db = null;

	/**
	 * @var string
	 */
	private $log_target,
			$log_data;

	function __construct() {

        // Declare database connection
        $this->connectDB();

	}

    /**
     * Declare a new connection to the database
     *
     * @return void
     * 
     */
    private function connectDB(){

        // Prevent to open multiple connections
        if (!isset($this->db)) {

            // Get instance from db class
            $db_instance =  DB::getInstance();

            // Create a new connection
            $this->db = $db_instance->connect();
        
        }

    }

    /**
     * Set target field
     *
     * @param string
     * @return void
     */
	public function setTarget( $target ){

		$this->log_target = $target;

	}

    /**
     * Set data field
     *
     * @param array
     * @return void
     */
	public function setData( $data ){

		$this->log_data = json_encode( $data );

	}

    /**
     * Store target and data
     *
     * @param string (optional)
     * @param array (optional)
     * @return boolean of success state
     */
	public function save( $target = null, $data = null ){

		if ($target) {
			$this->setTarget($target);
		}
		if ($data) {
			$this->setData($data);
		}

        global $userLogged;

        if ($userLogged) {

            $user_logged = 1;

        } else {

            $user_logged = 0;

        }

        $ip = $_SERVER['REMOTE_ADDR'];

        $url = $_SERVER['REQUEST_URI'];

		$result = false;

		$query = "INSERT INTO " . _T_LOG . " (target, data, url, ip, user_logged) VALUES ( ? , ? , ? , ? , ? );";

        if ($stmt = $this->db->prepare($query)) {
        
            $stmt->bind_param('ssssi', $this->log_target, 
                                       $this->log_data,
                                       $url,
                                       $ip,
                                       $user_logged);
            $stmt->execute();

            if ($stmt->affected_rows == 1) {
                $result = true;
            }

            $stmt->close();
        
        }

        return $result;

	}

}